# Harvard News Aggregator - Puzzles

Harvard News website built on Laravel to create a web app that aggregates and personalizes Harvard-related news for its users.

## Group members
Alex, Bowen, Martin, Shai  

## Requirements
- php 5.5
- mcrypt for php
- MySql >=5.6 (We are using 5.7. The version requirement is because we use a recently added feature: fulltext indexes on innodb databases. MariaDB does not support this yet)

## Installation instructions

- Clone the repository from Github

  ```
  git clone git@github.com:cs164/puzzles-news.git
  ```
  
- Run composer install

  ```
  composer install
  ```
  
- Create a new database table and give full rights to user jharvard with password crimson (or edit config/database.php to use custom settings)

- Run all migrations

  ``` 
  php artisan migrate
  ```
  
- Seed the database with an initial set of tweeters and of RSS feeds (admin users can add more through the web interface)
  
  ```
  php artisan db:seed
  ```

- Run the news import script manually, or set up an automated task to run it at regular intervals. This will import all new posts from feeds in the database, and automatically assign tags to them. This may take a few minutes. 

  ```
  php artisan command:importrss
  ```
  
- run the import tweets script

  ```
  php artisan command:importtweets
  ```
  
- In order to create an admin user, manually set the role field in the database to 3 for that particular user
- Start the server and enjoy the news
  
  ```
  php artisan serve
  ```
  

