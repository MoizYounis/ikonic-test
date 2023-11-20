Here's a step-by-step guide to clone the repository, set up the project, and run the tests:

1. Clone Repository
   Open your terminal and run the following command to clone the repository: git clone https://github.com/MoizYounis/ikonic-test.git

2. Navigate to Project Folder
   Change your current directory to the project folder: cd folder-name

3. Install Composer Dependencies
   Run the following command to install the project dependencies using Composer: composer install

4. Create .env File
   Copy the provided .env.save file to create a new .env file: cp .env.save .env

5. Create SQLite Database
   Run the following command to create an SQLite database file: touch database/database.sqlite

6. Run Migrations
   Execute the Laravel migration command to set up the database schema: php artisan migrate

7. Run Tests
   To run the tests, use the following command: php artisan test

# IKONIC Interview

## Time

The alloted time frame for this task is 2 days.

## Your Goal

Your goal is to get all the tests to pass within your allotted time frame. The only rules is that you **can't** modify anything in the `tests` directory or any factories. Feel free to use any resources you would usually use, including Stack Overflow.

You'll also need to complete a few other TODOs in the code. Most IDEs should pick this up; if you're using emacs or vim you might find it easier to use `grep -R TODO` in both the `database` and `app` directories.

Good luck!

## Blueprint

You'll need to complete the following files:

-   `app/Services/MerchantService.php`
-   `app/Services/AffiliateService.php`
-   `app/Services/OrderService.php`
-   `app/Jobs/PayoutOrderJob.php`
-   `app/Http/Controllers/WebhookController.php`
-   `app/Http/Controllers/MerchantController.php`
-   `database/migrations/2022_05_13_220658_create_affiliates_table.php`
-   `database/migrations/2022_05_16_143445_create_orders_table.php`

## Getting started

You don't need to worry about a frontend, and we're using SQLite for simplicity. All you need to do is install the composer dependencies and you can get started. Feel free to refer to the tests if you need help understanding how a method should operate.
