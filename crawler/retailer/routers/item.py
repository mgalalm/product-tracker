from fastapi import APIRouter, Depends
from retailer.models.item import Item
from retailer.services.parser_service import ParserService
from drivers.driver_factory import get_driver_factory
from drivers.base_driver import BaseDriver
from logger import logger

router = APIRouter()

#TODO validate site and pid
#TODO validate the response type
@router.get("/{site}/{pid}")
async def get_item(site: str, pid: str, driver: BaseDriver = Depends(get_driver_factory)):
  
  try:
      # await driver.initialize()
      return await driver.parse()
  except Exception as e:
      # Log the error
      logger.error(f"Error occurred while fetching item: {e}")
      raise

