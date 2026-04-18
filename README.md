# Task App (Technical assessment)

## Introduction

A simple Laravel application for task management workflow. A user can:

-   Create tasks
-   Assign tasks to users
-   Set a due date
-   Mark tasks as completed or non-compliant
-   Capture a corrective action when something is non-compliant
-   View and filter tasks in a simple dashboard

## Tech Stack

-   Laravel
-   MySQL
-   Blade
-   Bootstrap
-   JavaScript / jQuery
-   AJAX
-   Vite

## Project setup

-   Download and install `composer`, `php`, `npm` and `mysql` if not locally available already
-   Create a database locally named `task_app`
-   Run `git clone https://github.com/yasaryousuf/task-app.git` to clone the repository
-   Open the console and cd project root directory
-   Rename `.env.example` file to `.env` inside project root and fill the database information.
-   Run `composer install` for installing php/laravel dependancies
-   Run `php artisan key:generate` to generate secure key
-   Run `php artisan migrate` to migrate the tables in connected database
-   Run `php artisan db:seed` to run seeders and add fake data to the database
-   Run `php artisan serve` to serve the app locally
-   Run `npm i`
-   Run `npm run dev`

## Practical judgements and assumptions

-   I tried to write the code as clean as possible and also complete the workflow without overengineering
-   There are three types of tasks statuses are mentioned which are pending, completed and non compliant. I didn't add any other status even if I needed. I used Laravel's `Enum` for better code readability and reusability. Same goes for task priority.
-   Utilized Laravel's Eloquent relationship with User and Task modal.
-   Used Resource class for API response to only provide data that are needed and opt out data that are not
-   Used Laravel seeder and factory to add data to database
-   Used Vite, the most modern approach to add jQuery and bootstrap to codebase
-   Structured UI components into layouts and sections
-   By default kept task status as pending, then user can change status to completed or non compliant
-   AJAX with jQuery is used in task status update as minimum UI expectation
-   Corrective action input field is kept hidden and only shown when task status is changed to non_compliant
-   Laravel validation used with separate Request class and injected into controllers
-   Time isn't used for due date as per assessment specification file it isn't needed

## AI assisted tools

No AI assisted tools used.

## Future improvements

If I had 2-3 hours of more time. I'd:

-   I didn't complete two of the Optional extras. If I had more time I'd definitly complete those.
-   I'd structure the JavaScript and jQuery code better and make reusable components.
-   I'd use frontend framework such as Vuejs
-   I'd structure blade systax even better
-   I'd use animations e.g. loading spinners, toggling components, toaster for better user experience
-   I'd design the UI better with typography and colors
-   Caching for filtering queries
-   Pagination in task list page
-   I'd also use separate service class to make controller methods slim
-   I'd add a search by text field so users can search for specific task by just entering task title or description
