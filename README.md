# A RESTful web service for representing Points of Interests (POI)

The project is developed and organized as:

  - Programming language: PHP
  - Dependencies: [Firebase JWT](https://github.com/firebase/php-jwt) (included in source)
  - Stack: PHP-FPM, NGINX, MySQL
  - Infrastructure: Docker, Docker-Compose

### Installation Steps

  - [Install docker-compose](https://docs.docker.com/compose/install/) on your local machine.
  - [Clone] (https://git-scm.com/docs/git-clone) the project using: ```https://github.com/dimsamaras/pois-test.git```
    or download through the download ZIP option found at the upper right section (could cause permissions conflicts).
  - Place the project to the destination folder.
  - Build the docker-compose project.
    ```sh
    $ cd folder_with_the_project 
    $ docker-compose build
    ```
  - Run the docker-compose project. 
    ```sh
    $ docker-compose up
    ```
    or to run as deamon
    ```sh
    $ docker-compose up -d
    ```
  - Access the API at [localhost:81/api/...](localhost:81) using PostMan or via your web browser

### Provisioning

#### Infrastructure

  - Any changes on the infrastructure can be performed on the docker-compose.yml file regarding the source location and ports binding and the containers.
    - MySQL is exposed on port 3316.
    - NginX listens on port 81.
    - Source code is in directory src inside this project and mapped in directory /var/www in the containers.
    
#### Data

  - In the database/config folder the ```db_schema.sql``` has all the required sql queries to provision the database if not run successfully on project build.

### API calls 

-- All API calls return JSON formatted responses.

-- The API user authorization is demonstrated in the 'categories' API requests, where the JWT token is requested in every call.

-- In order to handle efficiently large number of results 'retrieve_all' POI request is returned paginated.
#### a. User

POST localhost:81/api/user/create
   - requires: firstname, lastname, email, password
   - returns: Status code 201,503,400 along with the newly created id and a message
    
POST localhost:81/api/user/delete
   - requires: id
   - returns: Status code 200,503 along with a message
 
POST localhost:81/api/user/login
   - requires: email, password
   - returns: Status code 201,401 along with a message and the JWT token for the rest of the requests authorization.
    
#### b. POI

POST localhost:81/api/poi/create
   - requires: name, latitude, longitude, category (category id)
   - returns: Status code 201,503 along with the newly created id and a message
   
   ```sh
    {
           "id": "63",
           "message": "Poi was created successfully."
    }
```
       
POST localhost:81/api/poi/delete
   - requires: id
   - returns: Status code 200,503 along with a message
   
   ```sh
   {
          "message": "Poi was deleted."
   }
```
GET localhost:81/api/poi/retrieve_all
   - requires:
   - returns: Status code 200,404 along with a JSON:
   
     ```sh
     {      "pois": [
                         {
                             "id": "2",
                             "name": "Athens",
                             "latitude": "37.98381000",
                             "longitude": "23.72753900",
                             "category_name": "greek city"
                         },
                         {
                             "id": "1",
                             "name": "Thessaloniki",
                             "latitude": "40.73685100",
                             "longitude": "22.92022700",
                             "category_name": "greek city"
                         },
                         {
                             "id": "7",
                             "name": "Lefkada",
                             "latitude": "38.69522960",
                             "longitude": "20.53747020",
                             "category_name": "greek city"
                         },
                         {
                             "id": "3",
                             "name": "Larisa",
                             "latitude": "39.64345200",
                             "longitude": "22.41320800",
                             "category_name": "greek city"
                         },
                         ...
     ]}
     ```
    
GET localhost:81/api/poi/retrieve_one?id=ZZ
   - requires: id
   - returns: Status code 200,404 along with a JSON:
   
   ```sh
   {
                "pois": {
                    "id": "63",
                    "name": "Berlin",
                    "latitude": "52.50676140",
                    "longitude": "13.28465050",
                    "created": "2019-01-25 14:14:08",
                    "modified": "2019-01-25 14:14:08",
                    "category_name": "european city"
                }
   }
```   

GET localhost:81/api/poi/retrieve_by_location?lat=XX&lng=YY&distance=WW
   - requires: latitude, longitude, 
   
     distance (optional, range in KM that the POIs are away from the point. Defaults to 100)
   - returns: Status code 200,404 along with a message 
   
   ```sh
{
    "pois": [
        {
            "id": "3",
            "name": "Larisa",
            "latitude": "39.64345200",
            "longitude": "22.41320800",
            "category_name": "greek city",
            "distance_from_target": "63.899491426432725"
        },
        {
            "id": "34",
            "name": "Zagkliveri",
            "latitude": "40.57182210",
            "longitude": "23.28506400",
            "category_name": "greek village",
            "distance_from_target": "68.02601216982723"
        }
    ]
}

```
   
#### c. Category
 
POST localhost:81/api/category/create
   - requires: name, Authorization (JWT token provided from login) in the header
   - returns: Status code 201,503 along with the newly created id and a message
   
   ```sh
    {
           "id": "10",
           "message": "Category was created successfully."
    }
```
       
POST localhost:81/api/category/delete
   - requires: id,  Authorization (JWT token provided from login) in the header
   - returns: Status code 200,503 along with a message
   
   ```sh
   {
          "message": "Category was deleted."
   }
```

GET localhost:81/api/category/retrieve_all
   - requires: Authorization (JWT token provided from login) in the header
   - returns: Status code 200,404 along with a JSON payload
```sh
{
    "categories": [
        {
            "id": "1",
            "name": "greek city",
            "poi_count": "4"
        },
        {
            "id": "2",
            "name": "greek village",
            "poi_count": "2"
        },
        {
            "id": "3",
            "name": "european city",
            "poi_count": "2"
        },
        {
            "id": "5",
            "name": "european village",
            "poi_count": "0"
        },
        {
            "id": "8",
            "name": "american city",
            "poi_count": "0"
        },
        {
            "id": "9",
            "name": "american village",
            "poi_count": "0"
        }
    ]
}
```
   
### Todos

 - Detach Business Logic from the objects 
 - (BL) User Create should be enabled only for Admin users, as well as User Delete
 - API request throttling
 - Full OAUTH2 implementation encapsulating the tokens
 - Cache the responses
 - DB specialized in Geo data?
 - DB Indexing (spatial indexing)
 - Tests
