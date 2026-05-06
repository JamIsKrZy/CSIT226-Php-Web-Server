# PHP Login Web Server Setup

## Quick Start

### Prerequisites
- Docker & Docker Compose installed

### Running the Application

1. **Build and start Docker containers:**
   ```bash
   docker-compose up -d
   ```

2. **Initialize the database (run this once):**
   ```bash
   docker exec php-app bash /var/www/html/database/setup.sh
   ```

3. **Access the application:**
   - Home: `http://localhost:8000/`
   - Login: `http://localhost:8000/login`
   - Users: `http://localhost:8000/users`

4. **Demo Credentials:**
   - Email: `demo@example.com`
   - Password: `password123`

   Additional users:
   - john.doe@example.com / password123
   - jane.smith@example.com / password123
   - bob.wilson@example.com / password123
   - alice.johnson@example.com / password123

### Directory Structure

```
├── app/
│   ├── controllers/       # Application controllers
│   │   └── LoginController.php
│   ├── core/
│   │   └── Router.php     # URL routing engine
│   └── views/             # View templates
│       └── login.php      # Login page
├── database/
│   ├── migration/         # Database migrations
│   └── seeds/             # Database seeders
├── public/
│   ├── index.php          # Entry point
│   ├── assets/            # CSS, JS, images
│   ├── views/             # View files
│   └── .htaccess          # URL rewriting
├── routes/
│   └── web.php            # Route definitions
├── composer.json          # PHP dependencies
├── docker-compose.yml     # Docker services config
└── dockerfile             # PHP container definition
```

## Docker Services

### PHP Application (app)
- **Port:** 80
- **Image:** PHP 8.2 with Apache
- **Features:** 
  - Rewrite module enabled
  - PDO & MySQL support
  - Composer autoloading

### MySQL Database (db)
- **Port:** 3306
- **Version:** MySQL 8.0
- **Credentials:**
  - Root: `rootpw`
  - User: `myuser`
  - Password: `mypassword`
  - Database: `mydb`

## Features Implemented

✅ Login form with validation
✅ Session management
✅ Professional UI/UX
✅ Error and success messages
✅ URL routing (clean URLs)
✅ Docker containerization
✅ Apache rewrite rules for routing
✅ MySQL database integration
✅ User authentication with bcrypt password hashing
✅ Database migrations and seeds
✅ Users table with display view
✅ PDO database abstraction layer
✅ MVC architecture

## Common Commands

```bash
# Start services
docker-compose up -d

# View logs
docker-compose logs -f app

# Stop services
docker-compose down

# Remove volums and Rebuild
docker compose down && docker compose up -d --build

# Access MySQL container
docker exec -it mysql-container mysql -u myuser -p mydb

# Initialize database
docker exec php-app bash /var/www/html/database/setup.sh

# View database
docker exec mysql-container mysql -u myuser -pmypassword mydb -e "SELECT * FROM users;"

# Check PHP logs
docker-compose logs -f app

# Setup migration and seed database
docker exec php-app bash /var/www/html/database/setup.sh
```


## Database Setup

### Manual Database Setup (if setup.sh doesn't work)

1. Connect to MySQL container:
    #### Connect as regular user
   ```bash
   docker exec -it mysql-container mysql -u myuser -pmypassword mydb
   ```

    #### Connect as admin
    ```bash
    docker exec -it mysql-container mysql -u root -p 
    ```


2. Run the migration:
   ```bash
   docker exec php-app bash /var/www/html/database/setup.sh;
   ```


## API Documentation

### Routes

- `GET /` - Home page with navigation links
- `GET /login` - Display login form
- `POST /login` - Handle login submission
- `GET /users` - Display all users in a table

### Database Class (App\Core\Database)

Usage in controllers:

```php
$db = new Database();

// Query multiple rows
$users = $db->query('SELECT * FROM users');

// Query single row
$user = $db->queryOne('SELECT * FROM users WHERE id = ?', [1]);

// Execute INSERT/UPDATE/DELETE
$db->execute('INSERT INTO users (email, password) VALUES (?, ?)', [$email, $password]);
```

### UserController (App\Controllers\UserController)

Methods:
- `listUsers()` - Fetch and display all users
- `getUser($id)` - Get user by ID
- `login($email, $password)` - Verify credentials
- `createUser($email, $password, $first_name, $last_name)` - Create new user
- `updateUser($id, $first_name, $last_name)` - Update user info
- `deleteUser($id)` - Delete user

## Next Steps

1. ✅ Database Setup - DONE
2. ✅ Authentication - DONE (using bcrypt)
<!-- 3. Add dashboard page after login
4. Add user registration
5. Add password reset functionality
6. Add user profile editing
7. Implement role-based access control -->

## Troubleshooting

### 404 Errors
- Ensure Apache rewrite module is enabled
- Check `.htaccess` file exists in `public/`

### Database Connection Issues
- Ensure MySQL service is running: `docker-compose ps`
- Check credentials in docker-compose.yml

### Blank Page
- Check PHP error logs: `docker-compose logs app`
- Verify autoload.php exists: `docker-compose exec app ls -la vendor/`
