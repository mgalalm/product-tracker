import ikea_api
import json

constants = ikea_api.Constants(country="us", language="en")

ingka_items = ikea_api.IngkaItems(constants)
endpoint = ingka_items.get_items(["30457903"])
print(endpoint)
# response = ikea_api.run(endpoint)
# print(response)
# print(item) 

# Output:
# dump all 
