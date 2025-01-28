from fastapi import APIRouter, Depends
from retailer.models.item import Item
from retailer.services.parser_service import ParserService
from drivers.driver_factory import get_driver_factory
from drivers.base_driver import BaseDriver


router = APIRouter()

#TODO validate site and pid
#TODO validate the response type
@router.get("/{site}/{pid}")
async def get_item(site: str, pid: str, driver: BaseDriver = Depends(get_driver_factory)):
  
  return driver.parse()

