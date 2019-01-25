# A RESTful web service for representing Points of Interests (POI)

The project is developed and organized as:

  - Programming language: PHP
  - Dependencies: Firebase JWT (included in source)
  - Stack: PHP-FPM, NGINX, MySQL
  - Infrastructure: Docker, Docker-Compose

### Steps

  - [Install docker-compose](https://docs.docker.com/compose/install/) on your local machine 
  - [Clone] (https://git-scm.com/docs/git-clone) the project using: ```https://github.com/dimsamaras/pois-test.git```
    or download throught the download ZIP option found at the upper right section
  - Place the project to the destination folder
  - Build the docker-compose project 
    ```sh
    $ cd dillinger
    $ npm install -d
    $ node app
    ```
  - Run the docker-compose project 
  - Access the API at localhost:81/api/...


### API calls 

### Docker
Dillinger is very easy to install and deploy in a Docker container.

By default, the Docker will expose port 8080, so change this within the Dockerfile if necessary. When ready, simply use the Dockerfile to build the image.

```sh
cd folder
docker build -t joemccann/dillinger:${package.json.version} .
```
This will create the dillinger image and pull in the necessary dependencies. Be sure to swap out `${package.json.version}` with the actual version of Dillinger.

Once done, run the Docker image and map the port to whatever you wish on your host. In this example, we simply map port 8000 of the host to port 8080 of the Docker (or whatever port was exposed in the Dockerfile):

```sh
docker run -d -p 8000:8080 --restart="always" <youruser>/dillinger:${package.json.version}
```

Verify the deployment by navigating to your server address in your preferred browser.

```sh
127.0.0.1:8000
```

### Todos

 - Write MORE Tests
 - Add Night Mode
