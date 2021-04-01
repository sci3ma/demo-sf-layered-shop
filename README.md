## Assumptions
- users in memory
- few random unit tests
- docker
- symfony with twig
- no API
- DDD
- layered architecture
- SOLID
- MailHog (https://github.com/mailhog/MailHog) for email testing
- view product and view list of products routes are open
- add new product route needs authentication
- minimal html/twig views, no css and js
- ADR (Action-Domain-Responder) pattern approach
- no leaks outside domain layer
- framework-agnostic approach

## Possible improvements
- signed/encrypted transaction emails
- more unit tests
- more DDD approach (like ProductId objects, https://msgphp.github.io/)
- better framework-agnostic approach
- doctrine entities mapping in xml files
- API instead of symfony+twig+forms
- CQRS
- Money PHP lib (https://github.com/moneyphp/money) instead string

## Used helpers
- https://github.com/sci3ma/symfony-docker
- https://github.com/sci3ma/symfony-grumphp

## Installation
To install project, clone this repository then build containers:
```shell
$ docker-compose build
```
run containers:
```shell
$ docker-compose up -d
```
install composer dependencies:
```shell
$ docker exec -it php composer install
```
run doctrine migrations:
```shell
$ docker/console doctrine:migrations:migrate
```

## Usage
### Authentication
```
email: admin@example.com
password: test
```
### Add new product
`http://127.0.0.1:8000/product/add`
### View product
`http://127.0.0.1:8000/product/view/{id}`
### View list of products
`http://127.0.0.1:8000/product/list`
