## Ecommerce Petshop API
A Petshop API with all the main features of an ecommerce system
- [x] users
- [ ] orders
- [ ] products
- [ ] auth

### Running the application
The app uses Laravel Sail to setup the dev environment. You must have [docker](https://docs.docker.com/engine/install/) and [docker-compose](https://docs.docker.com/compose/install/) (or [Docker Desktop](https://docs.docker.com/get-docker/)) installed in order to run the project. 

### 1. Install dependencies
With docker installer, run the following command to [install composer dependencies with sail](https://laravel.com/docs/10.x/sail#installing-composer-dependencies-for-existing-projects).
```shell
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php82-composer:latest \
    composer install --ignore-platform-reqs
```

### 2. Configure environment variables
Copy .env.example to .env file.
```shell
cp .env.example .env
```

### 3. Run docker containers
Once the composer dependencies are installed, you can start docker containers running the following command
```shell
./vendor/bin/sail up
```
You can also configure an alias for `sail` following [this steps](https://laravel.com/docs/10.x/sail#configuring-a-shell-alias).

### 4. Run migrations
With the containers running, run the following command
```shell
./vendor/bin/sail artisan migrate
```

---

Now you should be able to access the application docs on http://localhost/api/documentation.
