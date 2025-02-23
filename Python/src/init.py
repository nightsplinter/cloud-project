import apache_beam as beam
from apache_beam.io.gcp.bigquery import WriteToBigQuery
from apache_beam.options.pipeline_options import PipelineOptions

PROJECT_ID = "recipe-hub-448420"
BUCKET_NAME = "recipe-hub-448420.appspot.com"
BIGQUERY_TABLE = f"{PROJECT_ID}:recipes_dataset.food_recipes"

def parse_csv_line(line):
    fields = line.split(",")
    return {
        "recipe_id": int(fields[0]),
        "name": fields[1],
        "minutes": int(fields[2]),
        "submitted": fields[3],
        "tags": fields[4],
    }

pipeline_options = PipelineOptions(
    runner="DataflowRunner",  # Use "DirectRunner" for local testing
    project=PROJECT_ID,
    temp_location=f"gs://{BUCKET_NAME}/temp",
    region="europe-west1",  # Adjust region if needed
)

with beam.Pipeline(options=pipeline_options) as pipeline:
    (
        pipeline
        | "Read from CSV" >> beam.io.ReadFromText("gs://recipe-hub-448420.appspot.com/transformed_recipes.csv", skip_header_lines=1)
        | "Parse CSV" >> beam.Map(parse_csv_line)
        | "Write to BigQuery" >> WriteToBigQuery(
            BIGQUERY_TABLE,
            create_disposition=beam.io.gcp.bigquery.BigQueryDisposition.CREATE_IF_NEEDED,
            write_disposition=beam.io.gcp.bigquery.BigQueryDisposition.WRITE_APPEND,
        )
    )
