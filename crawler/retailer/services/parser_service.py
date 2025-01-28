from bs4 import BeautifulSoup
import logging
from fastapi import Depends

class ParserService:
  
  def __init__(self,  driver, selectors):
    self.selectors = selectors
    self.driver = driver
    page = self.driver.get_content()
    self.soup = BeautifulSoup(page, 'html.parser')

  def parse(self):
      item = {}
      for key, value in self.selectors.items():
        item[key] = self.get_attribute_value(value)
      return item


  def get_attribute_value(self, selector):
    element = self.soup.select_one(selector)
    return element.text.strip() if element else "N/A"
