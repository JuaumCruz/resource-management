# Resource Management System

## Table of Contents
- [Project Overview](#project-overview)
- [Prerequisites](#prerequisites)
- [Installation](#installation)
- [Development Commands](#development-commands)
- [Project Structure](#project-structure)
- [Features](#features)

## Project Overview
A comprehensive resource management application built with Laravel 10, Vue.js 3, and Inertia.js.

## Prerequisites
- PHP 8.1+
- Composer
- Node.js 18+
- npm
- Make

## Installation

### 1. Clone the Repository
```bash
git clone https://github.com/JuaumCruz/resource-management.git
cd resource-management
```

### 2. Setup Project
```bash
# Initial setup (installs dependencies, configures environment, runs migrations)
make setup
```

### 3. Seed Database (Optional)
```bash
# WARNING: This will truncate existing users and create a new test user
make seed
```

#### Test User Credentials
After seeding, you can log in with:
- Email: `admin@example.com`
- Password: `password`

**CAUTION**: The seeding process will remove all existing users and create new test users. Only use this for development and testing purposes.

### 4. Running the Application
```bash
# Start development servers
make dev
```

### 5. Access the Application
Open your browser and visit:
- Application: `http://localhost:8000/login`

## Development Commands

### Using Makefile
- `make help`: Show available commands
- `make test`: Run tests
- `make clear`: Clear application caches
- `make stop`: Stop development servers
- `make clean`: Cleanup project dependencies

## Project Structure
- Backend: Laravel 10
- Frontend: Vue.js 3
- State Management: Pinia
- Routing: Inertia.js
- Database: SQLite

## Features
- Resource Management
- Category Tracking
- Tag System
- User Authentication
- Responsive Design

Project Link: [https://github.com/JuaumCruz/resource-management](https://github.com/JuaumCruz/resource-management)
