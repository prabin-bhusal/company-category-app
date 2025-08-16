Project Setup

Follow the steps below to set up this project:

Clone the repository
git clone <repository-url>

Navigate into the project directory
cd <project-directory>

Copy the environment file
cp .env.example .env

Build Docker containers
docker compose build

Start Docker containers
docker compose up -d

Install dependencies inside the container
docker exec -it laravel_app composer install

Generate application key
docker exec -it laravel_app php artisan key:generate

Create storage symlink
docker exec -it laravel_app php artisan storage:link

Your Laravel project should now be up and running inside Docker.
  
