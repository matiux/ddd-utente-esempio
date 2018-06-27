Esempio di modellazione DDD di un Utente con Symfony 3.4
========================

## Preparazione ambiente sviluppo
L'applicazione funziona all'interno di un conainer docker. Preparare l'ambiente in questo modo:

#### Clone del progetto
```
git clone git@github.com:matiux/UtenteDDDExample.git && cd UtenteDDDExample
```

#### Variabili d'ambiente
```
cp env.dist .env
```
#### Build e run dei containers
```
./dc up -d
```
#### Smontare i containers e i volumi
```
./dc down -v
```
Per ulteriori informazioni controllare il readme in `./docker/redme.md`

## Sviluppo

#### Entrare nel container PHP per lo sviluppo
```
./dc exec --user utente php bash
~~composer install~~
./build
```
Il container php è configurato per far comunicare Xdebug con PhpStorm

## Accessi

- host db: servicedb
- name db: app_db
- user db: root
- password db: root 
* `localhost:8080` risponde nginx
* `localhost:8081` phpmyadmin
* All'interno del container PHP, il database è raggiungibile con l'host `servicedb` alla porta `3306`
* All'esterno del container, il database è raggiungibile con l'host `127.0.0.1` alla porta `3307`

## Comandi e Aliases all'interno del container PHP

* `test` è un alias a `vendor/bin/phpunit`
* `sf` è un alias a `bin/console` per usare la console di Symfony
* `sfcc` è un alias a `rm -Rf var/cache/*` per svuotare la cache
* `memflush` è un alias a `echo \"flush_all\" | nc servicememcached 11211 -q 1"` per svuotare memcached

## Test
All'interno del container PHP
```
test (Esegue tutti i test)
test --group utente
test --group integration
```

Gestione utente
=====

## Creazione utente da shell
Gli utenti creeati da shell vengono creati già abilitati `enabled = 1`

#### Creazione utenti da shell
```
Crea un admin
./sf dddapp:create:utente utente@dominio.it password

Crea un utente disabilitato
./sf dddapp:create:utente user2@email.it password --ruolo user --abilitato false
```
## Signup utente da client
Gli utente che fanno il signup da client vengono creati disabilitati `enabled = 0`
#### Rotta
```
POST /v1/signup
```
#### Payload
```json
{
	"email":"utente@daclient.it",
	"password": "lamiapassword"
}
```
#### Responso
```json
{
    "email": "utente@daclient.it",
    "id": "dd1a2f8e-796e-4ebe-afc7-d3e7c5c8b391",
    "ruolo": "user",
    "enabled": false,
    "locked": false
}
```
## Signin - Ottenere un token per il login
Per ottenere un token un utente deve essere ebilitato `enabled = 1` e non bloccato `locked = 0`

#### Rotta
```
POST /v1/login
```
#### Payload
```json
{
	"email": "utente@daclient.it",
	"password": "lamiapassword"
}
```
#### Responso
```json
{
    "utente": {
        "email": "utente@daclient.it",
        "id": "dd1a2f8e-796e-4ebe-afc7-d3e7c5c8b391",
        "ruolo": "user",
        "enabled": true,
        "locked": false
    },
    "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJqdGkiOiJlYjU5OWJhYy01NGJkLTQyMjMtYjI5NS1jOTUxNjA4YWFjMWEiLCJpYXQiOjE1Mjg0ODYyMTksImV4cCI6MTUyODUxNTAxOSwic3ViIjoiZGQxYTJmOGUtNzk2ZS00ZWJlLWFmYzctZDNlN2M1YzhiMzkxIn0.N50cTdFQMc0PS4UhZ5nqXuB_v8b6oOzyNLC16nNGNCI",
    "token_expire": 1528515019
}
```
Il token dovrà essere passato a tutte le future chiamate tramite header:
```
Authorization: Bearer [token]
```
La durata del token è dettata dal parametro `auth_expiration` nel file `parameters.yml` e di default è di 8 ore

## Vedere un utente

#### Rotta
```
GET /v1/utente/{utenteId}
```
E' necessario essere autenticati passando un token jwt tramite header:
```
Authorization: Bearer [token]
```
#### Responso
```json
{
    "email": "utente@daclient.it",
    "id": "dd1a2f8e-796e-4ebe-afc7-d3e7c5c8b391",
    "ruolo": "user",
    "enabled": true,
    "locked": false
}
```
