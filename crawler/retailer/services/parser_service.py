from bs4 import BeautifulSoup
from logger import logger
from fastapi import Depends
import re

class ParserService:
  
  def __init__(self,  driver, selectors):
    self.selectors = selectors
    self.driver = driver
    # page = self.driver.get(self.driver.url)
    page = self.driver.get_content()
    # save the page source to a file pages folder with the url of the site
    safe_name = re.sub(r'[^a-zA-Z0-9]', '_', self.driver.url) + '.html'
    logger.info(f"Saving page source to {safe_name}")
    
    with open(f'pages/{safe_name}', 'w') as file:
      file.write(page)
    
    # with open('page.html', 'w') as file:
    #   file.write(page)
    self.soup = BeautifulSoup(page, 'html.parser')

  def parse(self):
      item = {}
      for key, value in self.selectors.items():
        item[key] = self.get_attribute_value(value)
      return item


  def get_attribute_value(self, selector):
    
    logger.info(f"Using selector: {selector}")
    
    element = self.soup.select_one(selector)
    
    content = None
    
    if not element:
        logger.warning(f"Element not found for selector: {selector}")
        return "N/A"
    
    if element.name == 'meta':
        content = element.get('content')
    else:
        content = element.text.strip().rstrip('.')
        
    return content
