#### Shamelessly sourced from https://github.com/sprintcube/docker-compose-lamp

# LAMP stack built with Docker Compose

A basic LAMP stack environment built using Docker Compose. It consists of the following:

* PHP
* Apache
* MySQL

## Installation

Clone this repository on your local computer and checkout the appropriate branch e.g. 7.4.x. 
Run the `docker-compose up -d`.

```shell
git clone https://github.com/sprintcube/docker-compose-lamp.git
cd docker-compose-lamp/
git fetch --all
git checkout 7.4.x
cp sample.env .env
docker-compose up -d
```

Your LAMP stack is now ready!! You can access it via `http://localhost`.

## Configuration and Usage

Please read from appropriate version branch.

### Connect to MySQL

```
> docker exec -ti mend-mysql /bin/bash
```
Inside the container:
```
root@96362952ce65:/# mysql -u root -p 
Enter password: 

Welcome to the MySQL monitor.  Commands end with ; or \g.
Your MySQL connection id is 6
Server version: 5.7.29 MySQL Community Server (GPL)

Copyright (c) 2000, 2020, Oracle and/or its affiliates. All rights reserved.

Oracle is a registered trademark of Oracle Corporation and/or its
affiliates. Other names may be trademarks of their respective
owners.

Type 'help;' or '\h' for help. Type '\c' to clear the current input statement.

mysql>
```

```
1. Create a web page task list that submits form data and inserts it into the database then shows the updated task list. 
2. Also allow the deleting of those entries and show the updated list after deletion.
3. Must use JavaScript, PHP and MySQL.
4. Should not take you longer than 3-4 hours to complete.
5. The front end should make ajax restful calls to a php api endpoint.
6. (optional) Make task editable.

You may use any frameworks or extra libraries that you wish. 
You may submit the test as a PR.

We specifically are looking for your implementation of the following in your code:
* Good programming principles
* Validation
* Unit testing
* Restful 
* Ajax

We are not looking for examples of:
* CSS styling, color choices etc
```
