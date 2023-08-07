# Live Football World Cup Score Board

An application to present board of live games.


### Setup
Start Docker containers:
```console
docker-compose up
```

Enter PHP container:
```console
docker-compose exec -it php /bin/sh
```

Install dependencies:
```console
composer install
```


### Usage
In PHP container run `./board` to see all available commands.


### Example usage
```console
/app # ./board summary
+-------------------------------------+
| Live Football World Cup Score Board |
+-------------------------------------+
| There are no live matches now!      |
+-------------------------------------+
/app # ./board start Argentina Brazil
Game started.
/app # ./board start Croatia Denmark
Game started.
/app # ./board start England France
Game started.
/app # ./board summary
+-------------------------------------+
| Live Football World Cup Score Board |
+-------------------------------------+
| England 0 - France 0                |
| Croatia 0 - Denmark 0               |
| Argentina 0 - Brazil 0              |
+-------------------------------------+
/app # ./board update England France 1 3
Score updated.
/app # ./board update Argentina Brazil 2 2
Score updated.
/app # ./board summary
+-------------------------------------+
| Live Football World Cup Score Board |
+-------------------------------------+
| England 1 - France 3                |
| Argentina 2 - Brazil 2              |
| Croatia 0 - Denmark 0               |
+-------------------------------------+
/app # ./board finish Argentina Brazil
Game finished.
/app # ./board finish Croatia Denmark
Game finished.
/app # ./board summary
+-------------------------------------+
| Live Football World Cup Score Board |
+-------------------------------------+
| England 1 - France 3                |
+-------------------------------------+
/app # ./board finish England France
Game finished.
/app # ./board summary
+-------------------------------------+
| Live Football World Cup Score Board |
+-------------------------------------+
| There are no live matches now!      |
+-------------------------------------+
```
