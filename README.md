# Commenting system
A tailored and extensible commenting micro-service API.

## Prerequisites
* Docker Engine >=18.09

## Usage
If you have [make](https://www.gnu.org/software/make/) installed, you can build and start the stack with:
```bash
make
```

If you don't have **make** installed, follow the instructions below:

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

The service ships with an OpenAPI v3 specification.
The API documentation is available through the [ReDoc API documentation](http://localhost:8080).

If you like you can also import the OpenAPI specification into Postman to browse the API in a quick and eady way. The document is located at [`public/openapi.yaml`](public/openapi.yaml)
