import requests

def unshorten_url(short_url):
    api_url = f"https://unshorten.me/json/{short_url}"
    response = requests.get(api_url)
    if response.status_code == 200:
        return response.json().get("resolved_url")
    else:
        return f"Error: {response.status_code}"

short_url = "https://tinyurl.com/2fcpre6"
expanded_url = unshorten_url(short_url)
print(f"Expanded URL: {expanded_url}")