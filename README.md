Food Mate
========================

Symfony app designed to help users organize meals, plan menus, create shopping lists and keep track of fridge contents.

Requirements
------------

* PHP 8.1.0 or higher;
* PDO-MySQL PHP extension enabled;
* and the [usual Symfony application requirements][1].

Usage
------------

Prepare server:
```bash
sudo apt install php8.1-cli
sudo nano /etc/resolv.conf

php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php -r "if (hash_file('sha384', 'composer-setup.php') === '55ce33d7678c5a611085589f1f3ddf8b3c52d662cd01d4ba75c0ee0459970c2200a51f492d557530c71c15d8dba01eae') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
php composer-setup.php
php -r "unlink('composer-setup.php');"

wget https://get.symfony.com/cli/installer -O - | bash

symfony check:requirements

sudo apt-get install -y php-simplexml php-mbstring php-initl php-mysql php-xml zip unzip php-zip

composer install
```

Start server:

```bash
symfony server:start
```

Share 8000 port publicly:

```bash
ngrok http 8000
```

Forward IP and Port on Windows:

```cmd
netsh interface portproxy add v4tov4 listenport=8000 listenaddress=82.145.79.215 connectport=8000 connectaddress=172.21.44.198
```

Reset forwarding:

```cmd
netsh interface portproxy reset
```

Run database from Docker:
```cmd
docker compose up
```

Tests
-----

Execute this command to run tests:

```bash
$ cd my_project/
$ ./bin/phpunit
```

[1]: https://symfony.com/doc/current/setup.html#technical-requirements
