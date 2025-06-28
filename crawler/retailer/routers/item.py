from fastapi import APIRouter, Depends
from retailer.models.item import Item
from retailer.services.parser_service import ParserService
from drivers.driver_factory import get_driver_factory
from drivers.base_driver import BaseDriver
from logger import logger
from fastapi import HTTPException, status
from fastapi.security import HTTPAuthorizationCredentials, HTTPBearer
# from config import AUTH_TOKEN
from retailer.config import settings

router = APIRouter()


AUTH_TOKEN = settings.auth_token

auth_scheme = HTTPBearer()

def verify_token(credentials: HTTPAuthorizationCredentials = Depends(auth_scheme)):
    if credentials.credentials != AUTH_TOKEN:
        raise HTTPException(status_code=status.HTTP_401_UNAUTHORIZED, detail="Invalid token")
    return credentials.credentials
#TODO validate site and pid
#TODO validate the response type

@router.get("/{site}/{pid}")
async def get_item(site: str, pid: str, driver: BaseDriver = Depends(get_driver_factory), token: str = Depends(verify_token)):
  
  try:
      # await driver.initialize()
      return await driver.parse()
  except Exception as e:
      # Log the error
      logger.error(f"Error occurred while fetching item: {e}")
      raise

