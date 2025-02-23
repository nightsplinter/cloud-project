import dash
from dash import dcc, html, dash_table
import plotly.express as px
import pandas as pd
import os
from google.cloud import bigquery

# Set Google Cloud credentials for BigQuery access
BASE_DIR = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
json_path = os.path.join(BASE_DIR, "recipe-hub-448420-5e86d58492de.json")
os.environ["GOOGLE_APPLICATION_CREDENTIALS"] = json_path

# Initialize the Dash app
app = dash.Dash(__name__)

# Create a BigQuery Client for fetching data
client = bigquery.Client(project="recipe-hub-448420")

# SQL query to fetch the recipe data from BigQuery
QUERY = """
   SELECT * FROM `recipe-hub-448420.recipes_dataset.food_recipes` ORDER BY id DESC LIMIT 25
"""
# Execute the query and load data into a pandas DataFrame
df = client.query(QUERY).to_dataframe()

# Create visualizations using Plotly

# Histogram to visualize the distribution of preparation times (minutes)
fig_histogram = px.histogram(df, x="minutes", nbins=10, title="Distribution of Preparation Times",
                             labels={"minutes": "Preparation Time (Minutes)"}, color_discrete_sequence=["#636EFA"])

# Boxplot to show the distribution of servings across recipes
fig_boxplot = px.box(df, y="servings", title="Distribution of Servings per Recipe",
                     labels={"servings": "Number of Servings"}, color_discrete_sequence=["#EF553B"])

# Pie chart showing the distribution of total time for each recipe
fig_pie = px.pie(df, names="name", values="total_time", title="Distribution of Recipe Times")

# Layout definition for the Dash app
app.layout = html.Div([
    html.H1("📊 Recipe Analysis Dashboard"),

    html.Div([
        # Dropdown to allow the user to select a specific recipe
        html.Label("🔍 Select a Recipe:"),
        dcc.Dropdown(
            id="recipe-dropdown",
            options=[{"label": name, "value": name} for name in df["name"].unique()],
            placeholder="Select a recipe",
            style={"width": "50%"}
        ),
    ], style={"margin-bottom": "20px"}),

    # Display the selected recipe data and pie chart
    html.Div([
        dcc.Graph(id="filtered-graph"),  # Dynamic graph based on selected recipe
        dcc.Graph(figure=fig_pie),  # Pie chart showing total time distribution
    ], style={"display": "flex", "flex-wrap": "wrap"}),

    # Display histogram and boxplot for preparation time and servings
    html.Div([
        dcc.Graph(figure=fig_histogram),  # Histogram for distribution of preparation times
        dcc.Graph(figure=fig_boxplot),    # Boxplot for distribution of servings
    ], style={"display": "flex", "flex-wrap": "wrap"}),

    html.H3("📋 Recipe Data"),

    # DataTable to display the recipe data in a tabular format
    dash_table.DataTable(
        id="data-table",
        columns=[{"name": col, "id": col} for col in df.columns],
        data=df.to_dict("records"),
        page_size=10,  # Number of records per page
        style_table={"overflowX": "auto"},
    ),
])

# Callback to update the graph based on the selected recipe
@app.callback(
    dash.dependencies.Output("filtered-graph", "figure"),
    [dash.dependencies.Input("recipe-dropdown", "value")]
)
def update_graph(selected_recipe):
    # Filter the DataFrame based on selected recipe
    if selected_recipe:
        filtered_df = df[df["name"] == selected_recipe]
    else:
        filtered_df = df

    # Return a bar chart displaying the total time for the selected recipe (or all recipes)
    return px.bar(filtered_df, x="name", y="total_time", title=f"Total Time for {selected_recipe}" if selected_recipe else "Total Time for All Recipes")

# Run the Dash app
if __name__ == "__main__":
    app.run_server(debug=True)
