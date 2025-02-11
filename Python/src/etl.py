import shutil
import pandas as pd
import re
from google.cloud import bigquery, storage
import kagglehub
import os

# Get the path to the main directory and set the path for the service account JSON file
BASE_DIR = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
json_path = os.path.join(BASE_DIR, "recipe-hub-448420-5e86d58492de.json")
os.environ["GOOGLE_APPLICATION_CREDENTIALS"] = json_path

# Google Cloud configuration
PROJECT_ID = "recipe-hub-448420"
BUCKET_NAME = "recipe-hub-448420"
BIGQUERY_DATASET = "recipes_dataset"


def upload_to_gcs(local_path, gcs_path):
    """Uploads a file from the local system to Google Cloud Storage."""
    client = storage.Client(project=PROJECT_ID)
    bucket = client.bucket(BUCKET_NAME)
    blob = bucket.blob(gcs_path)
    blob.upload_from_filename(local_path)
    print(f"Uploaded {local_path} to gs://{BUCKET_NAME}/{gcs_path}")


def load_to_bigquery(table_name, schema, file_path):
    """Loads a CSV file into a BigQuery table."""
    client = bigquery.Client(project=PROJECT_ID)
    dataset_ref = client.dataset(BIGQUERY_DATASET)
    table_ref = dataset_ref.table(table_name)

    job_config = bigquery.LoadJobConfig(
        source_format=bigquery.SourceFormat.CSV,
        schema=schema,
        skip_leading_rows=1,
        autodetect=True,
        write_disposition=bigquery.WriteDisposition.WRITE_TRUNCATE,
        create_disposition=bigquery.CreateDisposition.CREATE_IF_NEEDED
    )

    # Upload the file to GCS before loading it to BigQuery
    gcs_path = "transformed_recipes.csv"
    upload_to_gcs(file_path, gcs_path)

    uri = f"gs://{BUCKET_NAME}/{gcs_path}"
    load_job = client.load_table_from_uri(uri, table_ref, job_config=job_config)

    load_job.result()  # Wait for the job to complete
    print(f"Loaded data into BigQuery table: {table_name}")


def download_kaggle_datasets(destination_path):
    """Downloads the Kaggle dataset and saves it to the specified directory."""
    print("Downloading Kaggle dataset...")
    path = kagglehub.dataset_download("shuyangli94/food-com-recipes-and-user-interactions")

    if not os.path.exists(destination_path):
        os.makedirs(destination_path)

    for file_name in os.listdir(path):
        full_file_path = os.path.join(path, file_name)
        if os.path.isfile(full_file_path):
            shutil.copy(full_file_path, destination_path)

    print(f"Dataset saved to: {destination_path}")


def extract_minutes(description):
    """Extracts the number of minutes from the given description."""
    match = re.search(r'(\d+)\s*Minuten', str(description))
    if match:
        return int(match.group(1))
    return None


def transform_and_load():
    """Main function that downloads, transforms, and loads the data into BigQuery."""
    download_kaggle_datasets("data")

    # Load the CSV file
    df = pd.read_csv("data/recipes.csv")
    print("Initial columns:", df.columns)

    # Extract the number of minutes from the description column
    df['minutes'] = pd.to_numeric(df['description'].apply(extract_minutes), errors='coerce').fillna(30).astype(int)

    # Clean 'id': Ensure all values are integers, drop rows with invalid 'id'
    df['id'] = pd.to_numeric(df['id'], errors='coerce')
    df = df.dropna(subset=['id'])
    df['id'] = df['id'].astype(int)

    # Clean 'description': Remove problematic characters (extra quotes and newlines)
    df['description'] = df['description'].astype(str).str.replace(r'[\r\n]+', ' ', regex=True)  # Remove newlines
    df['description'] = df['description'].str.replace('"', '', regex=False)  # Remove double quotes

    # Ensure 'servings' is a positive integer
    df['servings'] = pd.to_numeric(df['servings'], errors='coerce').fillna(1).astype(int)
    df = df[df['servings'] > 0]

    # Transform the data by adding 10 minutes to the total time
    df["total_time"] = df["minutes"] + 10

    # Save the first 20 rows of the cleaned and transformed data to a new CSV file
    transformed_path = "data/transformed_recipes.csv"
    df.to_csv(transformed_path, index=False, encoding="utf-8-sig", quotechar='"', quoting=1)

    print(f"Transformed data saved to {transformed_path}")

    # Define the schema for the BigQuery table
    schema = [
        bigquery.SchemaField("id", "INTEGER"),
        bigquery.SchemaField("name", "STRING"),
        bigquery.SchemaField("description", "STRING"),
        bigquery.SchemaField("ingredients", "STRING"),
        bigquery.SchemaField("ingredients_raw_str", "STRING"),
        bigquery.SchemaField("serving_size", "STRING"),
        bigquery.SchemaField("servings", "INTEGER"),
        bigquery.SchemaField("steps", "STRING"),
        bigquery.SchemaField("tags", "STRING"),
        bigquery.SchemaField("search_terms", "STRING"),
        bigquery.SchemaField("minutes", "INTEGER"),
        bigquery.SchemaField("total_time", "INTEGER"),
    ]

    # Load the transformed data into BigQuery
    load_to_bigquery("food_recipes", schema, transformed_path)


if __name__ == "__main__":
    transform_and_load()
