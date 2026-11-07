
import ssl
from drivers.base_driver import BaseDriver
from retailer.config import settings
from logger import logger
import httpx
import certifi

class BrightDataDriver(BaseDriver):

    def __init__(self, url, selectors):
      super().__init__(url, selectors)

      logger.info(f"Initializing {self.__class__.__name__} driver for {url}")
      self.proxy_url = settings.bd_proxy_url

      logger.info(f"Using proxy: {self.proxy_url}")

      verify = self._build_verify()

      # For async usage with error handling
      try:

          proxy_mounts = {
          "http://": httpx.AsyncHTTPTransport(proxy=self.proxy_url),
          "https://": httpx.AsyncHTTPTransport(proxy=self.proxy_url)
          }

          self.client = httpx.AsyncClient(
                mounts=proxy_mounts,
                verify=verify
                )
          logger.info(f"HTTP client initialized with proxy: {self.proxy_url}")
      except Exception as e:
          logger.error(f"Error initializing HTTP client: {e}")
          raise

    def _build_verify(self):
        if not settings.bd_verify_ssl:
            logger.warning(
                f"{self.__class__.__name__}: TLS certificate verification is disabled (bd_verify_ssl=False)"
            )
            return False

        ca_path = settings.bd_ca_cert_path or certifi.where()
        return ssl.create_default_context(cafile=ca_path)

    async def get_content(self):
        try:
            page = await self.client.get(self.url, timeout=40)

            logger.info(f"Page content fetched successfully")
            return page.text

        except Exception as e:
          logger.error(f"Error fetching content from {self.url}: {e}")
          await self.client.aclose()
          raise

    async def close(self):
      # pass
      await self.client.aclose()

