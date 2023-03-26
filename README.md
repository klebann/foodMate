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

Tests
-----

Execute this command to run tests:

```bash
$ cd my_project/
$ ./bin/phpunit
```

[1]: https://symfony.com/doc/current/setup.html#technical-requirements