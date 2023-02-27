# Technical test backend Widitrade
Widitrade request test implementation

## Get started
    - You must have installed docker, docker-compose, make and tmux (this is optional)
```sh
sudo apt install dockerio docker-compose make
```
    -Now you should clone this repo and go into root folder, then execute:
```sh
make up
make init-composer
```
Now, you will have running all services. Your ports 80 and 5432 should be running nginx and Posgres SQL server respectively.

## Requests
Show a list of shorten urls:
```
GET http://localhost/api/v1/short-urls
```

Create short url:
```
POST http://localhost/api/v1/short-urls
```
Navigate to a shorted url:
```
GET http://localhost/<shortUrl>
```



<small>Made by Carlos Campo Liébana with love</small>