# CIT University - Enrollment Management System

A modern, responsive, and secure enrollment management system built with PHP, MySQL, and Docker. This platform facilitates student interest monitoring, enrollment planning, and administrative management for the CIT University academic community.

## 🚀 Quick Start

### Prerequisites
- Docker & Docker Compose

### Installation & Setup

1.  **Clone and Navigate:**
    ```bash
    git clone <repository-url>
    cd CSIT226-Php-Web-Server
    ```

2.  **Launch Services:**
    ```bash
    docker-compose up -d
    ```

3.  **Initialize Database:**
    ```bash
    docker exec php-app bash /var/www/html/database/setup.sh
    ```

4.  **Access Points:**
    - **Student Portal:** `http://localhost:8000/login`
    - **Admin Portal:** `http://localhost:8000/admin/login`

### 🔑 Demo Credentials

| Role | Email | Password |
| :--- | :--- | :--- |
| **Admin** | `admin@cit.edu` | `password123` |
| **Student** | `demo@example.com` | `password123` |

---

## 🛠 Features

### 🎓 Student Interface
- **Dashboard:** Overview of enrollment status and announcements.
- **Section Demand:** Real-time visualization of course popularity.
- **Enrollment Plan:** Interactive tool to plot and manage semester schedules.
- **Alternative Sections:** Quick access to backup class options.
- **Account Security:** Integrated password management system.

### 💼 Admin Management
- **Student Interest Monitoring:** Detailed analytics on course and section demand.
- **Enrollment Updates:** Dynamic CRUD interface for publishing system-wide announcements.
- **Admin Management:** Secure portal for managing administrative staff accounts and roles.
- **Secure Authentication:** Dedicated admin login flow with role-based access control (RBAC).

---

## 📂 Project Structure

```text
├── app/
│   ├── Controllers/       # Logic for Admin, Student, and Auth flows
│   ├── Core/              # Router and Database abstraction layers
├── database/
│   ├── migration/         # SQL schema definitions
│   └── seeds/             # Initial data for testing
├── public/
│   ├── assets/            # CSS (Maroon & Gold theme), JS, and Images
│   ├── views/             # Structured UI Templates
│   │   ├── admin/         # Administrative modules
│   │   ├── auth/          # Authentication screens (Login/Signup)
│   │   ├── student/       # Student dashboard and planning tools
│   │   └── partials/      # Reusable components (Sidebar, Navbar)
├── routes/
│   └── web.php            # Clean URL route definitions
└── docker/                # Environment configuration
```

## 💻 Tech Stack

-   **Backend:** PHP 8.2 (MVC Architecture)
-   **Database:** MySQL 8.0
-   **Frontend:** HTML5, Vanilla CSS3 (Custom Design System), JavaScript
-   **Infrastructure:** Docker, Apache (mod_rewrite)

---

## 📜 Common Commands

| Command | Description |
| :--- | :--- |
| `docker-compose up -d` | Start all services in background |
| `docker-compose logs -f` | Tail application logs |
| `docker-compose down -v` | Stop services and remove volumes |
| `docker exec php-app bash /var/www/html/database/setup.sh` | Reset and seed database |
| `mysql -h 127.0.0.1 -P 3306 -u root -p` | Connect database |

---

Developed for **CSIT226 - Web Server Technologies**.
