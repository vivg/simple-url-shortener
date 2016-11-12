## Simple Url Shortener

Simple Url Shortener is a minimalistic shortening API written in Laravel.

### Requirements
- [Git](https://git-scm.com/downloads)
- [Docker](https://www.docker.com/products/docker/) `>= 1.12`

### Installation

- Setup docker environment
```
$ git clone https://github.com/vivg/simple-url-shortener
$ cd simple-url-shortener/laradock
$ docker-compose up -d nginx mysql phpmyadmin
```

- Install composer packages and run migrations
```
$ docker-compose exec workspace bash
$ php composer.phar update
$ php artisan migrate
```

### Available APIs
| API endpoints  | Description                     |
|----------------|---------------------------------|
| /api/urls      | Lists all the shortened urls    |
| /api/shorten   | Shorten the urls                |
| /{short-code}  | Redirect to provided short-code |

### Usage

####/api/urls
- Default
```
$ curl -X GET \
    -H "Content-Type: application/json" \
    http://localhost/api/urls
```

- With Page Number
```
$ curl -X GET \
    -H "Content-Type: application/json" \
    http://localhost/api/urls?page=1
```

####/api/shorten
- Single Url
```
$ curl -X POST \
    -H "Content-Type: application/json" \
    -d '{"url":"http://google.com"}' \
    http://localhost/api/shorten
```
- Urls with devices
```
curl -X POST -H \
    "Content-Type: application/json" \
    -d '{"urls":{"desktop":"https://www.reddit.com","mobile":"https://m.reddit.com", "tablet":"https://m.reddit.com"}}' \
    http://localhost/api/shorten
```

####/{short-code}
```
$ curl -X GET http://localhost/wOF
```

## Running Tests
```
$ docker-compose exec workspace bash
$ vendor/bin/phpunit
```