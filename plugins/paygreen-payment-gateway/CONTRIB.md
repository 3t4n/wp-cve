# Wordpress Paygreen Payment

## Start project
```sh
make up
```

This command will:
1. Run docker-compose stack
2. Install woocommerce
3. Install paygreen_payment
4. Run composer install

### Admin
To access the administration, go to: [http://localhost/wp-admin](http://localhost/wp-admin).

Credentials:
- Username: `dev-module@paygreen.fr`
- Password: `password`

Setup:
1. Go to WooCommerce and do the initial setup.
2. Go to WooCommerce > Settings > Payments and enable PayGreen method.
3. Click on PayGreen to access the configuration.

## Stop project
```sh
make down
```

This command will:
1. Stop dowker-compose stack
