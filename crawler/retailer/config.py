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
        "currys": {
            "base_url": "https://www.currys.ie/products/",
            "selectors": {
                "name": "h1",
                "price": "span.sales span",
                "availability": "div.out-of-stock-text div"
            },
            "driver_mode": "local"
        },
        "timberland": {
            "base_url": "https://www.timberland.ie/en-ie/p/",
            "sku_example": "timberland-premium-6-inch-waterproof-boot-for-men-in-yellow-TB110061713",
            "selectors": {
                "name": "h1",
                "price": "div[data-dynatrace='product-price'] span",
                "availability": "span.availability"
            },
            "driver_mode": "remote"
        },
        "tesco" : {
            "base_url": "https://www.tesco.ie/groceries/en-IE/products/",
            "selectors": {
                "name": "h1",
                "price": "p[class*='styled__PriceText']",
                "availability": 'span:contains("out of stock")'
            },
            "driver_mode": "remote"
        },
        "dunnes": {
            "base_url": "https://www.dunnesstoresgrocery.com/product/", # dunnes/100274908
            "selectors": {
                "name": "h2",
                "price": "span[data-testid='pdpMainPrice-div-testId']",
                "availability": "span.availability"
            },
            "driver_mode": "remote"
        },
        "smyths": {
            "base_url": "https://www.smythstoys.com/ie/en-ie/p/", #p/171375
            "selectors": {
                "name": "h1",
                "price": 'meta[property="og:price:amount"]',
                "availability": 'span:-soup-contains("Out of stock", "Out of Stock", "Out Of Stock")'
            },
            "driver_mode": "remote"
        },
        "airback" : {
            "base_url": "https://airback.store/products/airback?variant=",
            "selectors": {
                "name": "title",
                "price": 'meta[property="product:price:amount"]',
                "availability": 'meta[property="product:availability"]'
            },
            "driver_mode": "local"
        }
    }
    
    bd_proxy_url: str
    
    class Config:
        env_file = ".env"  # Optional for environment variables

settings = RetailerConfig()
