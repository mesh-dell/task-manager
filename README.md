# Task Manager API

A RESTful Task Management API built with Laravel 11, MySQL, and Vue 3.

## Live Demo

- **App**: https://task-manager-production-5c27.up.railway.app
- **GitHub**: https://github.com/mesh-dell/task-manager
- **Docker Image**: https://hub.docker.com/repository/docker/meshdell/task-manager

## Tech Stack

- **Backend**: Laravel 11, PHP 8.4
- **Database**: MySQL 8.4
- **Frontend**: Vue 3, TypeScript, Tailwind CSS, Vite
- **Deployment**: Docker, Railway

---

## Running Locally

### Option 1 — Docker (Recommended)

**Requirements**: Docker, Docker Compose

1. Clone the repo

```bash
   git clone https://github.com/mesh-dell/task-manager.git
   cd task-manager
```

2. Set your APP_KEY in `.env`

```bash
   cp .env.example .env
   php artisan key:generate
```

3. Start the containers

```bash
   docker compose up --build
```

4. Visit http://localhost:8000

Migrations and seeders run automatically on startup.

---

### Option 2 — Local PHP

**Requirements**: PHP 8.4, Composer, pnpm, MySQL

1. Clone the repo

```bash
   git clone https://github.com/mesh-dell/task-manager.git
   cd task-manager
```

2. Configure database in `.env`

```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=task_manager
   DB_USERNAME=root
   DB_PASSWORD=your_password
```

3. Run setup command — installs all dependencies, generates app key, runs migrations and seeders, builds frontend assets

```bash
   composer run setup
```

4. Start all dev servers

```bash
   composer run dev
```

5. Visit http://localhost:8000

---

## API Documentation

Interactive API docs are available locally via [Scramble](https://scramble.dedoc.co/) at:

```
http://localhost:8000/docs/api
```

> ⚠️ API docs are not available in the production environment.

---

## Deployment

1. Build and push your Docker image to Docker Hub:

```bash
   docker build -t <your-dockerhub-username>/task-manager:latest .
   docker push <your-dockerhub-username>/task-manager:latest
```

2. Create new project on Railway

3. Deploy from Docker Hub image:

```
  <your-dockerhub-username>/task-manager:latest
```

4. Add MySQL service

5. Set environment variables

```env
   APP_NAME=TaskManager
   APP_ENV=production
   APP_DEBUG=false
   APP_KEY=your_app_key
   APP_URL=your_app_url
   DB_CONNECTION=mysql
   DB_HOST=${{MySQL.MYSQLHOST}}
   DB_PORT=${{MySQL.MYSQLPORT}}
   DB_DATABASE=${{MySQL.MYSQLDATABASE}}
   DB_USERNAME=${{MySQL.MYSQLUSER}}
   DB_PASSWORD=${{MySQL.MYSQLPASSWORD}}
   CACHE_STORE=file
   SESSION_DRIVER=file
   QUEUE_CONNECTION=sync
```

6. Set pre-deploy command

```bash
   php artisan migrate --force
```

7. Generate domain in Railway networking settings

---

## Database

MySQL with a single `tasks` table. Migration files are in `database/migrations/`.

A SQL dump file `task_manager.sql` is included in the repository.

Import the SQL dump:

```bash
mysql -u root -p task_manager < task_manager.sql
```

Export the SQL dump:

```bash
mysqldump -u root -p task_manager > task_manager.sql
```

ℹ️ Replace 'task_manager' with your actual database name as setup in .env

---

## API Endpoints

Base URL: `https://task-manager-production-5c27.up.railway.app`

### Create Task

```
POST /api/tasks
Content-Type: application/json

{
  "title": "Fix critical bug",
  "due_date": "2026-04-05",
  "priority": "high"
}
```

Rules:

- Title must be unique per due date
- Due date must be today or in the future
- Priority must be `low`, `medium`, or `high`

---

### List Tasks

```
GET /api/tasks
GET /api/tasks?status=pending
GET /api/tasks?status=in_progress
GET /api/tasks?status=done
```

Sorted by priority (high → low) then due date ascending.

---

### Show Task

```
GET /api/tasks/{id}
```

---

### Advance Task Status

```
PATCH /api/tasks/{id}/status
```

Status progression: `pending` → `in_progress` → `done`

Cannot skip or revert status.

---

### Delete Task

```
DELETE /api/tasks/{id}
```

Only tasks with status `done` can be deleted. Returns `403` otherwise.

---

### Daily Report

```
GET /api/tasks/report
GET /api/tasks/report?date=2026-04-05
```

Defaults to today if no date provided.

Response:

```json
{
    "date": "2026-04-05",
    "summary": {
        "high": { "pending": 2, "in_progress": 1, "done": 0 },
        "medium": { "pending": 1, "in_progress": 0, "done": 3 },
        "low": { "pending": 0, "in_progress": 0, "done": 1 }
    }
}
```

---

## Business Rules

| Rule               | Details                                 |
| ------------------ | --------------------------------------- |
| Unique title       | Title must be unique per due date       |
| Future dates only  | Due date must be today or later         |
| Valid priority     | Must be `low`, `medium`, or `high`      |
| Status progression | `pending` → `in_progress` → `done` only |
| No skipping        | Cannot skip or revert status            |
| Delete restriction | Only `done` tasks can be deleted        |

---

## Project Structure

```
app/
├── Enums/
│   ├── Priority.php        ← priority enum
│   └── Status.php          ← status enum with state machine
├── Http/
│   ├── Controllers/
│   │   └── TaskController.php
│   ├── Requests/
│   │   └── StoreTaskRequest.php
│   └── Resources/
│       └── TaskResource.php
└── Models/
    └── Task.php
database/
├── migrations/
│   └── xxxx_create_tasks_table.php
└── seeders/
    ├── DatabaseSeeder.php
    └── TaskSeeder.php
resources/
└── js/
    ├── views/
    │   ├── HomeView.vue
    │   ├── CreateView.vue
    │   ├── ReportView.vue
    │   └── NotFoundView.vue
    └── services/
        └── api.ts
routes/
├── api.php
└── web.php
```
