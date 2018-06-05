# GYG
Provided API endpoint containing product availabilities. Retrieves the product_ids of the products that are available to be booked given a period of time and the requested number of travellers.

## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes.

## Setup Instructions

1. Install composer within the directory. You can find instructions on how to install composer [on composer's site](https://getcomposer.org/download/).

2. Run composer:

  ```sh
  php composer.phar install
  ```

  Or if you installed composer globally:

  ```sh
  composer install
  ```
3. composer require guzzlehttp/guzzle

4. Run composer dump-autoload -o, this will enable auto-loading

5. Run the script on CLI

  ```sh 
  php index.php 2017-11-20T09:30 2017-11-23T19:30 3
  ```
  
## Running the tests

1. composer require --dev phpunit/phpunit
2. vendor/bin/phpunit --bootstrap vendor/autoload.php tests --debug. This will run the test cases which are in the tests directory

## How it works

1. There is a API provider that makes the API request using a guzzle http client.
2. Product Request takes the API object, get the data and validate each item
3. Product retrieves the product_ids of the products that are available to be booked given a period of time and the requested number of travellers.
4. Product Response print response in a JSON format
  
  