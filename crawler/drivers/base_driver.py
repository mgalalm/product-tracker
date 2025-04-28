from abc import ABC, abstractmethod
import logging
from bs4 import BeautifulSoup
from retailer.services.parser_service import ParserService


class BaseDriver(ABC):
    def __init__(self, url, selectors):
        self.logger = logging.getLogger('uvicorn.error')
        self.logger.info(f"Initializing {self.__class__.__name__} driver for {url}")
        
        self.url = url
        self.selectors = selectors

    @abstractmethod
    async def get_content(self):
        """Retrieve content from the given URL."""
        pass
    
    def get_name(self):
        return self.__class__.__name__
    
    async def parse(self):
        return await ParserService(self, self.selectors).parse()
    
    @abstractmethod
    def close(self):
        """Close the driver."""
        pass
    # when destroying the object
    def __del__(self):
        self.logger.info(f"Destroying {self.__class__.__name__} driver")
        self.close()

     
    