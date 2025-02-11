import dash
from dash import dcc, html
import plotly.express as px
import pandas as pd

# Initialize the Dash application
app = dash.Dash(__name__)

# Load the transformed recipe data (after saving it as a CSV)
df = pd.read_csv("data/transformed_recipes.csv")

# Create a simple visualization (e.g., bar chart for total time)
fig = px.bar(df, x="name", y="total_time", title="Total Time per Recipe")

# Define the layout for the Dash app
app.layout = html.Div([
    html.H1("Recipe Analysis Dashboard"),
    html.Div("Interactive visualizations of recipe data."),
    dcc.Graph(
        id="total-time-graph",
        figure=fig
    )
])

# Run the Dash app
if __name__ == "__main__":
    app.run_server(debug=True)
