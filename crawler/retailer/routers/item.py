from typing import Optional
from fastapi import APIRouter, Depends, Path
from pydantic import BaseModel
from drivers.driver_factory import get_driver_factory
from drivers.base_driver import BaseDriver
from logger import logger
from fastapi import HTTPException, status
from fastapi.security import HTTPAuthorizationCredentials, HTTPBearer
from retailer.config import settings

router = APIRouter()


class ItemResponse(BaseModel):
    name: Optional[str] = None
    price: Optional[str] = None
    availability: Optional[str] = None


AUTH_TOKEN = settings.auth_token

auth_scheme = HTTPBearer()

def verify_token(credentials: HTTPAuthorizationCredentials = Depends(auth_scheme)):
    if credentials.credentials != AUTH_TOKEN:
        raise HTTPException(status_code=status.HTTP_401_UNAUTHORIZED, detail="Invalid token")
    return credentials.credentials

def validate_site(site: str) -> str:
    if site not in settings.retailers:
        raise HTTPException(
            status_code=status.HTTP_404_NOT_FOUND,
            detail=f"Unknown site '{site}'. Configured retailers: {', '.join(settings.retailers.keys())}"
        )
    return site

@router.get("/{site}/{pid}", response_model=ItemResponse)
async def get_item(
    site: str = Depends(validate_site),
    pid: str = Path(..., min_length=1, max_length=64),
    driver: BaseDriver = Depends(get_driver_factory),
    token: str = Depends(verify_token)
):

  try:
      # await driver.initialize()
      return await driver.parse()
  except Exception as e:
      # Log the error
      logger.error(f"Error occurred while fetching item: {e}")
      raise

