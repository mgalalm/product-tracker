from fastapi import FastAPI, Depends
from retailer.routers.item import router as item_router

app = FastAPI()

app.include_router(item_router)

