## Setting up the project

### Prerequisites
- PHP 8.0 or higher
- Composer
- MySQL

### Installation
1. Clone the repository
```bash
git clone https://github.com/horlathunbhosun/blinqpay-assessment-api.git
cd blinqpay-assessment-api
```

after cloning the repository, you need to install the dependencies by running the following command:
```bash
composer install
```

2. Configure the environment variables with your database credentials in the `.env` file. You can copy the `.env.example` file and rename it to `.env` and fill in the values.

3. Run the migrations
```bash
php artisan migrate
```

4. Run test cases
```bash
php artisan test
```
5. Start the server
```bash
php artisan serve
```
lastly please dont forget to run this command for the images you want
```bash
php artisan storage:link
```

**important note: create and sqlite database for testing and copy the env.testing.example to the .env.testing 
the sqlite database is used for testing purposes only, and it is created in the databased directory in the root of the project**




## API Endpoints
  Postman documentation link is [here](https://documenter.getpostman.com/view/24437385/2sA3QtfXC2)



