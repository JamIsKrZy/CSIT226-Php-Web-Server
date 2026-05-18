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

3.  **Initialize & Sync Database:**
    ```bash
    # Run this whenever new migrations or seeds are added
    docker exec php-app bash /var/www/html/database/setup.sh
    ```

4.  **Access Points:**
    - **Student Portal:** `http://localhost:8000/login`
    - **Admin Portal:** `http://localhost:8000/admin/login`

### 🔑 Demo Credentials

| Role | Email | Password |
| :--- | :--- | :--- |
| **Admin** | `m.santos@cit.edu` | `password` |
| **Student** | `demo@example.com` | `password123` |

---

## 🛠 Features

### 🎓 Student Interface
- **Dashboard:** Overview of enrollment status and announcements.
- **Section Demand:** Real-time visualization of course popularity.
- **Enrollment Plan:** Interactive tool to plot and manage semester schedules.
- **Alternative Sections:** Quick access to backup class options.
- **Account Security:** Integrated password management system.

### 💼 Admin Management System (Integrated)
- **Unified Authentication:** Dedicated secure login flow for administrators.
- **Student Interest Analytics:** Real-time demand tracking for courses and sections.
- **Announcement Engine (CRUD):** Full management system for publishing and editing system-wide enrollment updates.
- **Staff Management (CRUD):** Comprehensive tools for managing administrative accounts and security roles.
- **Role-Based Access Control (RBAC):** Middleware-protected routes ensuring data security between students and staff.

---

## 📂 Project Structure

```text
├── app/
│   ├── Controllers/       # Integrated logic for Admin, Student, and Auth flows
│   ├── Core/              # Router and Database abstraction layers
├── database/
│   ├── migration/         # SQL schema definitions (v002 updated)
│   └── seeds/             # Initial data for testing (including Admin credentials)
├── public/
│   ├── assets/            # CSS (Maroon & Gold theme), JS, and Images
│   ├── views/             # Structured UI Templates
│   │   ├── admin/         # Administrative modules
│   │   ├── auth/          # Authentication screens (Login/Signup)
│   │   ├── student/       # Student dashboard and planning tools
│   │   └── partials/      # Reusable components (Sidebar, Navbar)
├── routes/
│   └── web.php            # Unified clean URL route definitions
└── docker/                # Environment configuration
```

## 💻 Tech Stack

-   **Backend:** PHP 8.2 (MVC Architecture)
-   **Database:** MySQL 8.0 (PDO Abstraction)
-   **Frontend:** HTML5, Vanilla CSS3 (Custom Design System), JavaScript
-   **Infrastructure:** Docker, Apache (mod_rewrite)

---

## 📜 Common Commands

| Command | Description |
| :--- | :--- |
| `docker-compose up -d` | Start all services in background |
| `docker-compose logs -f` | Tail application logs |
| `docker-compose down -v` | Stop services and remove volumes |
| `docker exec php-app bash /var/www/html/database/setup.sh` | Reset and sync database |

---

Developed for **CSIT226 - Web Server Technologies**.
