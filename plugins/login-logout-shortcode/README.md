Simple Login-Logout Shortcode
=============================

[![Build Status](https://travis-ci.org/prontotools/login-logout-shortcode.svg?branch=develop)](https://travis-ci.org/prontotools/login-logout-shortcode)

A single shortcode you can place anywhere to allow visitors to login/logout.

Developer Guide
---------------

To run, test, and develop the Simple Login-Logout Shortcode plugin with Docker container, please simply follow these steps:

1. Build the container:

  `$ docker build -t wptest .`
 
2. Test running the PHPUnit on this plugin:

  `$ docker run -it -v $(pwd):/app wptest /bin/bash -c "service mysql start && phpunit"`

Changelog
----------

= 1.1.0 =
- Add login URL parameter so that user can change the URL login page instead of login on WP admin page 
 
= 1.0.0 =
- First release!
