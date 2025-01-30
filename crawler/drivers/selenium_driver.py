
from selenium import webdriver
from selenium.webdriver.chrome.service import Service
from drivers.base_driver import BaseDriver
from selenium.webdriver.common.by import By
from webdriver_manager.chrome import ChromeDriverManager

class SeleniumDriver(BaseDriver):
    def __init__(self, url, selectors):
        super().__init__(url, selectors)
        self.driver =  self.initialize_driver()
    
    def initialize_driver(self):
        chrome_options = webdriver.ChromeOptions()
        chrome_options.add_argument("--no-sandbox")
        chrome_options.add_argument("--headless=new")
        chrome_options.add_argument("user-agent=Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/114.0.0.0 Safari/537.36")
        return webdriver.Chrome(options=chrome_options)
        # self.driver = webdriver.Chrome(service=Service(ChromeDriverManager().install()), options=chrome_options)

    def get_content(self):
        try: 
            self.driver.get(self.url)
        except Exception as e:
            print(f"Error accessing URL: {str(e)}")
            self.driver.quit()
        page = self.driver.page_source
        return page
    
    def close(self):
        self.driver.quit()
        # return {"title": "title", "price": "price"}
