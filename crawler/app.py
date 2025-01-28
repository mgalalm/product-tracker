from flask import Flask, request, jsonify
from selenium import webdriver
from selenium.webdriver.chrome.service import Service
from selenium.webdriver.chrome.options import Options
from webdriver_manager.chrome import ChromeDriverManager
from selenium.webdriver import Remote, ChromeOptions
from selenium.webdriver.chromium.remote_connection import ChromiumRemoteConnection

from selenium.webdriver.common.by import By
import json

app = Flask(__name__)

app.config.from_prefixed_env(prefix='CRAWLER')

app.SBR_AUTH =app.config.get('SBR_USER') + "-country-" + app.config.get("SBR_COUNTRY", "IE") +  ":" + app.config.get('SBR_PASS')
app.SBR_BASEURL =app.config.get('SBR_BASEURL')

with open("selectors.json", "r") as file:
    SELECTORS = json.load(file)


def get_driver():
    chrome_options = webdriver.ChromeOptions()
    print("Setting up chrome options")
    chrome_options.add_argument("--headless")
    # chrome_options.add_argument("--disable-dev-shm-usage")
    chrome_options.add_argument("--no-sandbox")
    # chrome_options.add_argument("--disable-gpu")
    # chrome_options.add_argument("--disable-extensions")
    # chrome_options.add_argument("--remote-debugging-port=9222")
    chrome_options.add_argument("user-agent=Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/114.0.0.0 Safari/537.36")
    # Initialize the Chrome driver
    # service = Service("/usr/local/bin/chromedriver")
    return webdriver.Chrome(options=chrome_options)
    # return webdriver.Chrome(service=Service(ChromeDriverManager().install()), options=chrome_options)

# def get_driver():
#     SBR_WEBDRIVER = f'https://{app.SBR_AUTH}@{app.SBR_BASEURL}' 
#     print("SBR_WEBDRIVER", SBR_WEBDRIVER)   
#     sbr_connection = ChromiumRemoteConnection(SBR_WEBDRIVER, 'goog', 'chrome')
#     return Remote(sbr_connection, options=ChromeOptions())


@app.route("/scrape/<site>/<pid>", methods=["GET"])
def scrape(site, pid):
    try:
      print("Scraping  122 :", site)
      product_id = pid.strip()
      driver = get_driver()
      site_config = SELECTORS.get(site)
      if not site_config:
          return jsonify({
              "error": "Site not found"
          })
      driver.get(site_config["url"] + product_id)
      
      
      driver.get_screenshot_as_file("screenshot.png")
      
      # save the screenshot for debugging
    #   driver.save_screenshot("screenshot.png")
      
      
      
    #   # dump the html to a file for debugging
    #   with open("page.html", "w") as file:
    #       file.write(driver.page_source)
      
      selectors = site_config["selectors"]
      
      name_selector = selectors.get("name")
      product_name = extract_Value(driver, name_selector)  
      
      price_selector = selectors.get("price")
      product_price = extract_Value(driver, price_selector)
      
      # if availability selector is not present, return availability as available
      if not selectors.get("availability"):
          product_availability = "Available"
      else:    
          availability_selector = selectors.get("availability")
          product_availability = extract_Value(driver, availability_selector)
          
      driver.quit()
      
      return jsonify({
          "name": product_name,
          "price": product_price,
          "available": product_availability
      })
    except Exception as e:
      return jsonify({
          "error": str(e)
      })

def extract_Value(driver, selector):
  if selector['type'] == "id":
      # if value is list, get all values and join them
      if isinstance(selector['value'], list):
          element_value = ""
          for value in selector['value']:
              element_value += driver.find_element(By.ID, value).text
      else:
          element_value = driver.find_element(By.ID, selector['value']).text
  elif selector['type'] == "css":
      print(selector['value'])
      if isinstance(selector['value'], list):
          element_value = ""
          for value in selector['value']:
              element_value += driver.find_element(By.CSS_SELECTOR, str(value)).text
      else:
          element_value = driver.find_element(By.CSS_SELECTOR, selector['value']).text
          
  elif selector['type'] == 'tag':
          element_value = driver.find_element(By.TAG_NAME, selector['value']).text
  elif selector['type'] == 'xpath':
          element_value = driver.find_element(By.XPATH, selector['value']).text
    
  # replace any \n with . to make it more readable
  element_value = element_value.replace("\n", ".")
  return element_value
  
  
if __name__ == "__main__":
  app.run(debug=True)