## Tested on
* Chromium - Version 76.0.3809.100
* PHP - 7.2.19
* MariaDB - 10.1.41

## Installation

### Database
You will need a MariaDB/MySQL Server.
Run the installation script 'sql/install.sql' to create the database 'todo'.

A database user 'todo_user' with the password 'todo_user' will be created.
Change the credentials in the following lines if you prefer. 
```mysql
CREATE USER 'todo_user'@'%' IDENTIFIED BY 'todo_password';
GRANT ALL PRIVILEGES ON todo.* TO 'todo_user'@'%';
```

An application user named 'test' with the password 'test' will be created. The users credentials can be edited by changing following line.
```mysql
INSERT INTO `todo`.`user` (`userName`, `userMail`, `userPasshash`) VALUES ('test', 'test@testmail.com', '$2y$10$0LtG0zCf8s2oGrVzppme6.vhPpBVULjDHIpQbSBur4n6HG7Dra7Bq');
```

The password has to be hashed with the PHP function password_hash()
```php
$password = password_hash('test', PASSWORD_DEFAULT);
```

### Application
Run composer
```bash
composer install --no-dev
```

Configure your database settings in the pimple file (pimple.php). Use the credentials you used before (database installatio).
```php
$pimple['dbHost'] = 'localhost';
$pimple['dbUser'] = 'todo_user';
$pimple['dbPassword'] = 'todo_password';
```