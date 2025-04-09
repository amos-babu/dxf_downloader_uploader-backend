# Backend Repository
**Frontend repository** https://github.com/amos-babu/dxf_downloader_uploader_frontend
## Project Overview
This repository contains the backend code for the web application, built using **Laravel**. It provides a RESTful API for managing and processing data, handling authentication, and supporting real-time features.

## Features
- **Laravel Framework**: Robust and scalable backend.
- **Authentication**: Secure user authentication and authorization.
- **API Endpoints**: Provides data and functionality for the frontend.
- **Real-Time Features**: Supports WebSocket for real-time communication.
- **File Management**: Handles uploads and file processing.

## Technologies Used
- Laravel
- SQlite (or other DBMS if applicable)
- Laravel Sanctum for API authentication

## Setup Instructions

1. **Clone the repository:**
   ```bash
   git clone https://github.com/amos-babu/dxf_downloader_uploader-backend.git
   ```

2. **Navigate to the project directory:**
   ```bash
   cd backend-repo
   ```

3. **Install dependencies:**
   ```bash
   composer install
   ```

4. **Copy the `.env.example` file and configure your environment variables:**
   ```bash
   cp .env.example .env
   ```
   Update the following:
   - Database credentials
   - API URL
   - Real-time broadcast driver (e.g., Pusher)

5. **Generate the application key:**
   ```bash
   php artisan key:generate
   ```

6. **Run migrations:**
   ```bash
   php artisan migrate
   ```

7. **Start the development server:**
   ```bash
   php artisan serve
   ```

## Deployment
To deploy the backend application, you can use platforms like **AWS**, **DigitalOcean**, or **Heroku**. Ensure to:
- Set up a production database.
- Configure the `.env` file for production.
- Use a queue worker for background tasks.
- Set up SSL for secure communication.

## API Documentation
For detailed API documentation, refer to the `docs/api-documentation.md` file or tools like **Postman** or **Swagger** (if implemented).

---

## Contribution
If you want to contribute to this project, feel free to submit a pull request or open an issue in the repository.

## License
This project is licensed under the **MIT License**. See the `LICENSE` file for details.
