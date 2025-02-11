from google.cloud import bigquery
import pandas as pd
import dash
from dash import dcc, html
import plotly.express as px
import os

# Set the environment variable for Google Cloud credentials
BASE_DIR = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
json_path = os.path.join(BASE_DIR, "recipe-hub-448420-5e86d58492de.json")
os.environ["GOOGLE_APPLICATION_CREDENTIALS"] = json_path

# Initialize Dash app
app = dash.Dash(__name__)

# Create BigQuery client
client = bigquery.Client(project="recipe-hub-448420")

# Define BigQuery query
QUERY = """
    SELECT 
        name, 
        minutes, 
        total_time, 
        servings 
    FROM `recipe-hub-448420.recipes_dataset.food_recipes`
    LIMIT 100
"""

# Retrieve data from BigQuery and convert it to a Pandas DataFrame
df = client.query(QUERY).to_dataframe()

# Create a Plotly bar chart (Example: Total time vs. recipe name)
fig = px.bar(df, x="name", y="total_time", title="Total Time per Recipe", labels={"total_time": "Total Time (minutes)"})

# Define the layout of the Dash app
app.layout = html.Div([
    html.H1("Recipe Analysis Dashboard (Data from BigQuery)"),
    dcc.Graph(
        id="total-time-graph",
        figure=fig
    )
])

app.layout = html.Div([
    html.H1("Recipe Analysis Dashboard"),
    dcc.Dropdown(
        id="recipe-dropdown",
        options=[{"label": name, "value": name} for name in df["name"].unique()],
        placeholder="Select a recipe"
    ),
    dcc.Graph(id="filtered-graph")
])

# Callback function to update the graph based on the selected recipe
@app.callback(
    dash.dependencies.Output("filtered-graph", "figure"),
    [dash.dependencies.Input("recipe-dropdown", "value")]
)
def update_graph(selected_recipe):
    if selected_recipe is None:
        filtered_df = df
    else:
        filtered_df = df[df["name"] == selected_recipe]
    fig = px.bar(filtered_df, x="name", y="total_time", title=f"Total Time for {selected_recipe}")
    return fig

# Run the Dash app
if __name__ == "__main__":
    app.run_server(debug=True)
