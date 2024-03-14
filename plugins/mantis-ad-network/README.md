Official Wordpress Plugin for the MANTIS Ad Network
================

Easily serve advertisements from the Mantis Ad Network on your website.

# Local Development

## Setup

`git clone git@github.com:mantisadnetwork/wordpress-mantis.git`
`git submodule init`
`git submodule update --depth 1`
`docker-compose up`

## Wordpress

username: admin

password: Vnds@t%bPQ6CwY!^45


## Refresh MySQL Dump

`docker-compose exec db bash -c "mysqldump -u root -p wordpress -h localhost > /docker-entrypoint-initdb.d/database.sql"`

# Testing

## Advertiser

### Global Pixel

Enter a value for **Advertiser Identifier**: 
`http://localhost/wp-admin/options-general.php?page=mantis_ad_options`

View the source for site and ensure code is injected with appropriate id:
`view-source:http://localhost/`

### WooCommerce

Add the test product to cart:
`http://localhost/product/test/`

Checkout and confirm global pixel is configured with transaction and revenue data