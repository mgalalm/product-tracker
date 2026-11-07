FROM python:3.11-slim

ENV PYTHONUNBUFFERED=1
ENV PYTHONDONTWRITEBYTECODE=1
ENV SSL_CERT_DIR="/etc/ssl/certs"
ENV SSL_CERT_FILE="/etc/ssl/certs/ca-certificates.crt"

# Update system packages and install necessary dependencies, including chromium-driver
RUN apt-get update && apt-get install -y \
    chromium-driver \
    && apt-get install -y \
    ca-certificates && \
    rm -rf /var/lib/apt/lists/*

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

# Copy the SSL certificate to the container
# COPY mycert.crt /etc/ssl/certs/mycert.crt
COPY certs/brightdata_proxy_33335.crt /etc/ssl/certs/mycert.crt

# RUN python -m pip install --upgrade pip

EXPOSE 8000

ENV SSL_CERT_FILE="/etc/ssl/certs/mycert.crt"

CMD ["uvicorn", "retailer.main:app", "--host", "0.0.0.0", "--port", "8000", "--reload", "--log-config" , "log_config.yaml", "--workers", "2"]
# CMD ["python", "crawler.py"]
