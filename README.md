## Setting up the project

### Prerequisites
- PHP 8.0 or higher
- Composer
- MySQL

### Installation
1. Clone the repository
```bash
git clone the repository
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

## API Endpoints
  Postman documentation link is [here](https://documenter.getpostman.com/view/24437385/2sA3QtfXC2)



