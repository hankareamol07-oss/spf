import osmnx as ox
import matplotlib.pyplot as plt

# 1. The exact coordinates you provided for Kuchi
latitude = 17.060563
longitude = 74.853561
center_point = (latitude, longitude)

print(f"Fetching mapping data for Kuchi at coordinates: {center_point}...")

# 2. Fetch the road network
try:
    # We increased 'dist' to 1500 meters to capture more intersecting roads
    graph = ox.graph_from_point(center_point, dist=1500, network_type='all')
    print("Road network downloaded successfully.")
except Exception as e:
    print(f"Error fetching roads: {e}")
    exit()

# 3. Set up the layout for the clean, surveyor-style map
fig, ax = plt.subplots(figsize=(10, 10), facecolor='white')
ax.set_facecolor('white')

# 4. Plot the roads (thick black lines on a pure white background)
try:
    fig, ax = ox.plot_graph(
        graph, 
        ax=ax, 
        show=False, 
        close=False, 
        edge_color='black', 
        edge_linewidth=2.0, 
        node_size=0, 
        bgcolor='white'
    )

    # 5. Save the final high-definition output
    output_filename = "HLB_Najri_Naksha_Final.png"
    plt.savefig(output_filename, dpi=300, bbox_inches='tight', facecolor='white')

    print(f"\nSuccess! Your custom layout map has been saved as: {output_filename}")
    
    # This will pop open a window showing your map
    plt.show()

except Exception as e:
    print(f"An error occurred while drawing the map: {e}")