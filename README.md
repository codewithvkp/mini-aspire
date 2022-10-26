# Mini-Aspire Loan App

A Laravel app that helps user to apply for loan for a specific term period and then repay the part of amount every week once admin approves the loan application.

This app consists of three different modules,

### Authentication

Contains registration, login and logout functionality for users. App uses [Laravel Passport](https://laravel.com/docs/9.x/passport) for token based authentication system. When user registers or perform login operation, app will return auth token in the response that can be used to authorize protected APIs later.

### Loan application

Contains logic to create, update, delete and approve loan. Loan applications are created by user but can be only approved by admins. This module also contains functionality to get approved/pending loan applications. In theory, via loan application user can inform admin that he wants to apply for a loan for a certain period. Then admin needs to approve that application.

### Loan repayments

Contains logic to get pending/paid loan repayments and repay a specific repayment. Loan repayment records are created once admin approves the loan application. Once loan repayment records are created, user can fetch the list of repayments for a specific loan application and then repay a particular repayment. Currently, app assumes a weekly repayment frequency.


## Project structure

App follows default directory structure of Laravel. Below are the directories that contains app's main logic,

`app/Models`

Contains models for user, loan application and loan repayment.

`app/Http/Controllers`

Contains all controllers to handle routes functionalities.

`database/factories`

Contains factories that can be used to generate mock data for models.

`database/migrations`

Contains migrations for database schema.

`database/seeders`

Contains database seeder for models that can be used to generate mock data to init the app.

`app/Http/Resources/General`

Contains JSON resources for general use that can be used anywhere in the controller to format response data.

`app/Http/Resources/Models`

Contains JSON resources specifically for models.

`app/Http/Requests`

Contains rules to validate route requests that can be injected in controller methods.

`app/Events`

Contains events that can be called after/before a series of actions.

`routes`

Contains all the API routes and controller methods mapping.

`tests/Feature`

Contains all the feature tests.

`app/Actions`

Contains small and isolated actions that can be used separately or in combination to perform a series of operations.

## Installation

### Prerequisites

-  PHP >= 8.0
-  MySQL >= 8.0
-  Mcrypt PHP Extension
-  OpenSSL PHP Extension
-  Mbstring PHP Extension
-  Tokenizer PHP Extension

You can execute below file to set up project. It will also create a admin and a normal user that you can use to test the app.

```
sh ./bin/setup.sh
```

Once the setup is done, you can run below command to serve an app,
```
php artisan serve
```

### Test cases

To run test cases you can run,

```
./vendor/bin/phpunit 
```

## Response structure

#### Success response

```json
{
	"data":  {
		"id": 1,
		"name": "John Doe",
		"email": "johndoe@gmail.com",
		"type": "admin"
	}
}
```

#### Error response

```json
{
	"message": "Invalid credentials!"
}
```

#### Validation error response

```json
{
	"message": "The email field is required.",
	"errors": {
		"email": [
			"The email field is required."
		]
	}
}
```

## Postman collection

[Here](https://drive.google.com/file/d/1nQSJSCAK2FIS1Hrv4RLAVYAfNH5U20u6/view?usp=sharing) you can find the postman collection for all the APIs that can help user and admin to interact with the application.
Once you import the collection, set `URL_PREFIX` in collection variable to the base url where app is being served i.e. `http://127.0.0.1:8000`.
After running `register` and `login` API, the postman will automatically set the `token` variable that will be used by other APIs.

## Test users

You can use below users to test the APIs.

#### Admin user

Email: `admin@gmail.com`
Password: `password`

#### Normal user

Email: `user@gmail.com`
Password: `password`

