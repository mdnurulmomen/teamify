## Requirements

- PHP >= 8.0
- Laravel >= 8.0
- SQLite database
- Docker
- Docker-compose

 This challenge does not require any additional library. DO NOT MODIFY the composer.json or composer.lock file as that may result in a test failure.
 The project already contain a sample SQLite database at /database/database.sqlite. Please don´t change the database structure by creating a seed or migration file because this may also result in a test failure.

## Installation

### Project cloning

-   Run an instance of terminal (Git bash, PowerShell, windows terminal, etc.) & clone repository with HTTPS

```bash
git clone https://github.com/mdnurulmomen/teamify.git
```

### Docker Setup

_Please install Docker Desktop first. Docker requires high memory to run smoothly. Please close unnecessary applications from taskbar if you have less than or equal to 8 GB Ram_

-   Navigate to project directory and build docker YML file using docker compose

```bash
cd teamify
```

```bash
docker-compose build
```

_Please wait until docker completes build process_

-   Mount docker containers

```bash
docker-compose up
```

_if you see I/O timeout error, please restart docker desktop and run above command again_

-   Run another instance of terminal & navigate to docker app

```bash
docker-compose exec app bash
```

-   Update composer

```bash
composer update
```

```bash
composer dump-autoload
```

### Environment, Migration & Seeding Setup

-   While in docker app, create .env file and copy contents of .env.example to .env file

```bash
touch .env
```

_if touch command is not supportted in your terminal, create .env file manually_

```bash
cp .env.example .env
```

_if cp command is not supportted in your terminal, copy contents of .env.example to .env file manually_

-   Generate application key

```bash
php artisan key:generate
```

-   Update database and password in .env file

-   Complete migration and seeding steps using below commands:-

```bash
php artisan migrate
```

```bash
php artisan db:seed
```

### Passport Setup

-   Create personal access and password grant clients

```bash
php artisan passport:install --force
```

_Project setup is complete. You can authenticate and test API’s now._

## Server Port

_see docker-compose.local.yml_

### Author

[Md. Nurul Momen](https://www.linkedin.com/in/md-nurul-momen-9aa287a2/) <br />

See more [contributions](https://github.com/mdnurulmomen?tab=repositories).
