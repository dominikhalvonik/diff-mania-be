# Use the official Python image from the Docker Hub
FROM python:3.9

# Set the working directory in the container
WORKDIR /app

# Copy the current directory contents into the container at /app
COPY . /app

# Install the required packages
RUN apt-get update && \
  apt-get install -y python3-opencv && \
  apt-get install -y libgl1-mesa-glx && \
  pip install opencv-python && \
  pip install mysql-connector-python && \
  pip install requests

# Run load_image.py when the container launches
CMD ["python", "load_images.py"]