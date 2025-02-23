import shutil
import pandas as pd
import re
from google.cloud import bigquery, storage
import kagglehub
import os
import numpy as np
from sympy import false

# Set up the path to the service account JSON file
BASE_DIR = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
json_path = os.path.join(BASE_DIR, "recipe-hub-448420-5e86d58492de.json")
os.environ["GOOGLE_APPLICATION_CREDENTIALS"] = json_path
gcs_path = "transformed_recipes.csv"

# Google Cloud project and bucket configuration
PROJECT_ID = "recipe-hub-448420"
BUCKET_NAME = "recipe-hub-448420"
BIGQUERY_DATASET = "recipes_dataset"

# Initialize a false boolean for BigQuery to accept it as a serializable value
bool_false = np.bool_(False)
json_serializable_value = bool(bool_false)

def upload_to_gcs(local_path, gcs_path):
    """Uploads the new file to Google Cloud Storage and deletes the old one if it exists."""
    client = bigquery.Client(project=PROJECT_ID)
    table_id = f"{PROJECT_ID}.{BIGQUERY_DATASET}.food_recipes"

    # Delete the existing table in BigQuery if it exists
    try:
        client.delete_table(table_id, not_found_ok=True)
        print(f"Deleted table {table_id} from BigQuery.")
    except Exception as e:
        print(f"Error deleting table: {e}")

    # Initialize the Google Cloud Storage client
    client = storage.Client(project=PROJECT_ID)
    bucket = client.bucket(BUCKET_NAME)
    blob = bucket.blob(gcs_path)

    # If the file already exists, delete it
    if blob.exists():
        blob.delete()
        print(f"Old file {gcs_path} deleted from GCS.")

    # Upload the new file to GCS
    blob.upload_from_filename(local_path)
    print(f"New file uploaded to gs://{BUCKET_NAME}/{gcs_path}.")

def load_to_bigquery(table_name, schema, file_path):
    """Deletes the existing table in BigQuery and uploads the new data from the CSV file."""
    client = bigquery.Client(project=PROJECT_ID)
    dataset_ref = client.dataset(BIGQUERY_DATASET)
    table_ref = dataset_ref.table(table_name)

    # Define the job configuration for loading the data into BigQuery
    job_config = bigquery.LoadJobConfig(
        source_format=bigquery.SourceFormat.CSV,
        schema=schema,
        skip_leading_rows=1,  # Skip the header row in the CSV file
        write_disposition=bigquery.WriteDisposition.WRITE_TRUNCATE,  # Overwrite existing data
        create_disposition=bigquery.CreateDisposition.CREATE_IF_NEEDED  # Create the table if it doesn't exist
    )

    # Upload the file to GCS first
    gcs_path = "transformed_recipes.csv"
    upload_to_gcs("data/transformed_recipes.csv", gcs_path)

    # Define the GCS URI and load the file into BigQuery
    uri = f"gs://{BUCKET_NAME}/{gcs_path}"
    load_job = client.load_table_from_uri(uri, table_ref, job_config=job_config)

    load_job.result()  # Wait for the job to complete
    print(f"New data loaded into BigQuery table: {table_name}.")

def download_kaggle_datasets(destination_path):
    """Downloads the Kaggle dataset and saves it to the specified directory."""
    print("Downloading Kaggle dataset...")
    path = kagglehub.dataset_download("shuyangli94/food-com-recipes-and-user-interactions")

    # Create the destination directory if it doesn't exist
    if not os.path.exists(destination_path):
        os.makedirs(destination_path)

    # Copy the files from the downloaded dataset to the destination path
    for file_name in os.listdir(path):
        full_file_path = os.path.join(path, file_name)
        if os.path.isfile(full_file_path):
            shutil.copy(full_file_path, destination_path)

    print(f"Dataset saved to: {destination_path}")

def extract_minutes(description):
    """Extracts the number of minutes from the description text."""
    match = re.search(r'(\d+)\s*Minuten', str(description))
    if match:
        return int(match.group(1))
    return None

def transform_and_load():
    """Main function that downloads, transforms, and loads the data into BigQuery."""
    download_kaggle_datasets("data")

    # Load the transformed CSV file
    df = pd.read_csv("data/recipes.csv")

    # Extract the number of minutes from the description and handle missing values
    df['minutes'] = pd.to_numeric(df['description'].apply(extract_minutes), errors='coerce')
    df['minutes'] = df['minutes'].apply(lambda x: x if not pd.isna(x) else np.random.randint(20, 90))
    df['minutes'] = df['minutes'].astype(int)

    # Clean 'id' column and drop rows with invalid values
    df['id'] = pd.to_numeric(df['id'], errors='coerce').dropna().astype(int)

    # Clean 'description' column by removing newlines and quotes
    df['description'] = df['description'].astype(str).str.replace(r'[\r\n]+', ' ', regex=True).str.replace('"', '', regex=False)

    # Ensure 'servings' is a positive integer and drop invalid rows
    df['servings'] = pd.to_numeric(df['servings'], errors='coerce').fillna(1).astype(int)
    df = df[df['servings'] > 0]

    # Add a 'total_time' column by adding 10 minutes to the 'minutes' column
    df["total_time"] = df["minutes"] + 10

    # Save the transformed data to a new CSV file
    transformed_path = "data/transformed_recipes.csv"
    df.to_csv(transformed_path, index=False, encoding="utf-8-sig", quotechar='"', quoting=1)

    # Print the first 10 rows of the transformed data for verification
    print("📂 New transformed data:")
    print(df.head(10))

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

    # Query BigQuery to verify that the data was successfully uploaded
    query = f"SELECT minutes, total_time FROM `{PROJECT_ID}.{BIGQUERY_DATASET}.food_recipes` ORDER BY id DESC LIMIT 10"
    client = bigquery.Client(project=PROJECT_ID)
    df_check = client.query(query).to_dataframe()
    print("🔎 BigQuery data after upload:")
    print(df_check)

if __name__ == "__main__":
    transform_and_load()
