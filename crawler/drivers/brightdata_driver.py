
import requests
from drivers.base_driver import BaseDriver
import ssl
import sys
import urllib.request
from pydantic_settings import BaseSettings, SettingsConfigDict
from retailer.config import settings
import logging

class BrightDataDriver(BaseDriver):
    def __init__(self, url, selectors):
      super().__init__(url, selectors)
      self.logger = logging.getLogger('uvicorn.error')
      self.proxy_url = settings.bd_proxy_url
      self.logger.info(self.proxy_url)
      ssl._create_default_https_context = ssl._create_unverified_context

      if sys.version_info[0] == 2:
        from six.moves.urllib import request
        self.driver = request.build_opener(
          request.ProxyHandler({
            'http': self.proxy_url,
            'https': self.proxy_url
          })
        )
      else:
         self.driver = urllib.request.build_opener(
          urllib.request.ProxyHandler({
            'http':  self.proxy_url,
            'https':  self.proxy_url
          })
        )

    def get_content(self):  
        return self.driver.open(self.url).read().decode('utf-8')

    def close(self):
      pass
    
