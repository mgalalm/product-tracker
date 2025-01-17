import requests

def expand_amazon_url(short_url):
    """
    Expands an Amazon short URL to its full form.
    
    Args:
        short_url (str): Amazon short URL (e.g., https://amzn.eu/d/hzcPXEj)
        
    Returns:
        str: Full Amazon URL
        
    Raises:
        requests.RequestException: If there's an error during the request
        ValueError: If the URL is not a valid Amazon short URL
    """
    try:
        # Verify it's an Amazon short URL
        if not short_url.startswith(('http://amzn.', 'https://amzn.')):
            raise ValueError("Not a valid Amazon short URL")
            
        # Make a GET request to the short URL
        # Don't follow redirects (allow_redirects=False) to get the redirect URL
        response = requests.get(short_url, allow_redirects=False)
        
        # Check if we got a redirect response
        if response.status_code in (301, 302):
            return response.headers['Location']
        else:
            raise ValueError("URL did not redirect to a full Amazon URL")
            
    except requests.RequestException as e:
        raise requests.RequestException(f"Error accessing URL: {str(e)}")

# Example usage
if __name__ == "__main__":
    short_url = "https://amzn.eu/d/hzcPXEj"
    try:
        full_url = expand_amazon_url(short_url)
        print(f"Full URL: {full_url}")
    except (requests.RequestException, ValueError) as e:
        print(f"Error: {str(e)}")