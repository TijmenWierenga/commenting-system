# Commenting system
A tailored and extensible commenting micro-service API.

## Prerequisites
* Docker Engine >=18.09

## Usage
Start by building the Docker image (run command from project root directory):
```bash
DOCKER_BUILDKIT=1 docker build -t tijmenwierenga/commenting-system:latest .
```

Next, start the stack:
```bash
docker-compose up -d
```

It might take a few seconds in order to initialize the database.
Verify that the stack has started successfully by requesting all comments for an existing article:
```bash
curl http://localhost/article/780fdc7e-adeb-4cf5-9521-e53c52557a6d/comments
```

Further documentation is available through the [ReDoc API documentation](http://localhost:8080).