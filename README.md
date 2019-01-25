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
    $ cd folder_with_the_project 
    $ docker-compose build
    ```
  - Run the docker-compose project 
    ```sh
    $ docker-compose up
    ```
    or to run as deamon
    ```sh
    $ docker-compose up -d
    ```
  - Access the API at localhost:81/api/... using PostMan or via your web browser

### Data initialization

### API calls 

### Todos

 - Detach Business Logic from the objects 
 - API request throttling
 - Tests
