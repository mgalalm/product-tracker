### ERD
![alt text](docs/ERD.png)  
Stocks belong to many products, which means it can be shared between multiple users, hence we don't have to track redundant stock, while keeping our database size small.
   
### Architecture
![alt text](docs/architecture.png)

- TrackProduct Job is scheduled will check if the product is in stock or not, and update the stock status in the database. And dispatch an event if the stock status changes.
- Regex is used to validate the product url 

- If the product never tracked before, the value of the product will be set to false / 0 in the database

- Product model has a method called `track` which will be called the track method on each stock of the product.

