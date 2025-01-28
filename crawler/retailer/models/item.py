from pydantic import BaseModel
class Item(BaseModel):
    name: str
    price: str
    available: str
    
    # from dict to pydantic model
    @staticmethod
    def from_dict(data: dict):
        return Item(name=data['name'], price=data['price'], available=data['available'])