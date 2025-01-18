FROM python:3.11-slim

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

CMD ["python", "-m", "flask", "run", "--host=0.0.0.0"]
# CMD ["python", "crawler.py"]
