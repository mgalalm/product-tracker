from pydantic_settings import BaseSettings
from typing import ClassVar, Dict

class RetailerConfig(BaseSettings):
    retailers: ClassVar[Dict[str, Dict[str, str]]] = {
        "amazon-uk": {
            "base_url": "https://www.amazon.co.uk/dp/",
            "selectors": {
                "name": "span#productTitle",
                "price": "span.a-offscreen",
                "availability": "div#availability span"
            },
            "driver_mode": "remote"
        },
        "ebay": {
            "base_url": "https://www.ebay.com/itm",
            "title_selector": "h1.it-ttl",
            "price_selector": "span#prcIsum",
            "availability_selector": "div#availability span"
        },
        "currys": {
            "base_url": "https://www.currys.ie/products/",
            "selectors": {
                "name": "h1",
                "price": "span.sales span",
                "availability": "div.out-of-stock-text div"
            },
            "driver_mode": "local"
        }
    }
    
    bd_proxy_url: str
    
    class Config:
        env_file = ".env"  # Optional for environment variables

settings = RetailerConfig()
