## Todo List API PHP Application

### Requirements
* PHP 8.0 or higher
* MySQL 5.7 / MariaDB 10.0 or higher

## Installation

Upload script folder somewhere and enjoy!

Create your database and import .sql file

Start to test the script by registering the following URL:

`http(s)://{YOUR_DB_HOST}/{YOUR_FOLDER_NAME}/register.php`

Get your personal X-API-key and use it in Headers

## Configuration

Edit config file:

```php
	DB_HOST="YOUR_DB_HOST"
	DB_NAME="YOUR_DB_NAME"
	DB_USER="YOUR_DB_USERNANE"
	DB_PASS="YOUR_DB_PASSWORD"
```

### CRUD List fields

The API has following fields:

```code 
- id
- name
- description
- is_urgent
- is_personal
- is_working
- is_done
- completed_at
- user_id
 ```

### CRUD List operations

A request can be written in URL format as:

`http(s)://{YOUR_DB_HOST}/{YOUR_FOLDER_NAME}/api/tasks`

For example:

`http://dubov.softwars.com.ua/api/tasks`

### Create

To create a record the request can be written in URL format as:

`POST http://dubov.softwars.com.ua/api/tasks`

Request body (example):

```json
{
    "name": "A new task",
    "description": "Task description",
    "completed_at": "2022-10-22 22:14:07"
}
```
Response (if it is all good):

```json
{
    "message": "Task was created",
    "id": "1"
}
```

### Read

To read all your records the request can be written in URL format as:

`GET http://dubov.softwars.com.ua/api/tasks`

To read a record by id the request can be written in URL format as:

`GET http://dubov.softwars.com.ua/api/tasks/{id}`

  Where {id} is the value of the primary key of the record that you want to get.

### Update

To update a record by id the request can be written in URL format as:

`PATCH http://dubov.softwars.com.ua/api/tasks/{id}`

  Where {id} is the value of the primary key of the record that you want to update.

### Delete

To delete a record by id the request can be written in URL format as:

`DELETE http://dubov.softwars.com.ua/api/tasks/{id}`

  Where {id} is the value of the primary key of the record that you want to delete.