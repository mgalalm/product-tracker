
import importlib
import json
from pathlib import Path
from fastapi import Depends
from .base_driver import BaseDriver
from retailer.config import settings

class DriverFactory:
    DRIVER_MAPPING = {
        "local": {"module": "drivers.selenium_driver", "class_name": "SeleniumDriver"},
        "remote": {"module": "drivers.brightdata_driver", "class_name": "BrightDataDriver"}
    }

    def __init__(self, site, pid):
        self.site = site
        self.mode = settings.retailers[self.site]["driver_mode"] or "local"
        self.base_url = settings.retailers[self.site]["base_url"]
        self.url = f'{self.base_url}{pid}'
        self.selectors = settings.retailers[self.site]["selectors"]
        

    def get_driver(self) -> BaseDriver:
        # driver_mode = self.config["driver_mode"].lower() # default to local 
        
        
        # if driver_mode not in self.DRIVER_MAPPING:
        #     raise ValueError(f"Unsupported driver mode: {driver_mode}")

        driver_info = self.DRIVER_MAPPING[self.mode]
        
        try:
            module = importlib.import_module(driver_info["module"])
            driver_class = getattr(module, driver_info["class_name"])
            instance = driver_class(url=self.url, selectors=self.selectors)

            if not isinstance(instance, BaseDriver):
                raise TypeError(f"{driver_info['class_name']} does not implement BaseDriver")

            return instance
        except (ImportError, AttributeError, TypeError) as e:
            raise ImportError(f"Error loading driver: {e}")

def get_driver_factory(site: str, pid: str):
    return DriverFactory(site, pid).get_driver()
