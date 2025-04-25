<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

# Geldiah Belaraby

Geldiah Belaraby is a Laravel-based web application that provides a robust backend powered by Laravel 11.9. It is containerized using Docker for a seamless development experience.

## 🚀 Features
- Laravel 11.9 framework
- Authentication using Laravel Sanctum
- Flash notifications with PHP Flasher
- Debugging tools like Laravel Debugbar
- Fully Dockerized environment with Nginx and MySQL
- Pre-configured database migrations and seeders

---

## 📦 Installation

### 1️⃣ Clone the Repository
```bash
git clone https://github.com/your-username/geldiah-belaraby.git
cd geldiah-belarab
```

### 2️⃣ Set Up Environment Variables
Copy the example .env file and configure it:
```bash
cp .env.example .env
```
Edit the .env file and update database credentials.

### 3️⃣ Run the Application with Docker
Ensure Docker and Docker Compose are installed, then start the services:
```bash
docker-compose up -d --build
```


### 4️⃣ Install Dependencies & Generate App Key
```bash
docker exec -it laravel_app composer install
docker exec -it laravel_app php artisan key:generate
docker exec -it laravel_app php artisan migrate --seed
```
### 5️⃣ Access the Application
Frontend: http://localhost <br>
API Routes: http://localhost/api


### 🛠 Useful Commands
Run Migrations
```bash
docker exec -it laravel_app php artisan migrate
```

Seed the Database
```bash
docker exec -it laravel_app php artisan db:seed
```

View Application Logs
```bash
docker logs -f laravel_app
```


Restart Docker Containers
```bash
docker-compose down && docker-compose up -d --build
```

Stop Docker Containers
```bash
docker-compose down
```

