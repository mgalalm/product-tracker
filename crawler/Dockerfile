FROM python:3.11-slim


ENV PYTHONUNBUFFERED=1
ENV PYTHONDONTWRITEBYTECODE=1


# Update system packages and install necessary dependencies, including chromium-driver
RUN apt-get update && apt-get install -y \
    chromium-driver \
    && rm -rf /var/lib/apt/lists/*

# Install Selenium
RUN pip install selenium

# Set the working directory in the container
WORKDIR /app

# Copy the requirements file into the container
COPY requirements.txt .

# Install any needed packages specified in requirements.txt
RUN pip install --no-cache-dir -r requirements.txt

# Copy the rest of the application's code into the container
COPY . .

CMD ["uvicorn", "retailer.main:app", "--host", "0.0.0.0", "--port", "8000", "--reload"]
# CMD ["python", "crawler.py"]
