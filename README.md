Prototype (example) of API

Response format: json

**Installation**

0. Clone repo
1. Install docker (https://docs.docker.com/get-started/), docker-compose (https://docs.docker.com/compose/install/)
5. Install dependencies (`composer install`)
2. Run `docker-compose up -d` for building and running the containers
3. Run `docker exec -it exampleapi_php_1 ./console/command app:db:init` for MySQL DB populating
4. Add host "example-api" to `/etc/hosts`
6. Run tests: `bin/phpunit tests`
7. Request endpoints (example: `http://example-api/`, `http://example-api/23`) with Authorization header (`Bearer: 12345`)

**Endpoints description**
* GET /?page={page} - list of messages with pagination
* GET /archived - list of archived messages
* GET /{id} - view message
* PATCH /{id}/read - mark message as read
* PATCH /{id}/archived - mark message as archived

**Error response example**:

`{"error":"message", "code": http status code}`

**Further improvements**
* Schema generation with all possible request/response params/status codes (Swagger)
* Support of different content types
* Request param converting/validation
* Passing filter params in request (i.e. - get rid of "/archived" endpoint)
* Tests improvements (fixtures, more scenarios)
* Code refactoring (some code parts is not "single-responsible")
* using ORM
* Deployment improvements (setting test env, etc)
