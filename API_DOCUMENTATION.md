 # Health Appointment Booking System - Backend API Documentation

## Overview
This is a Laravel-based REST API for a Health Appointment Booking System. It provides comprehensive endpoints for managing users (patients, doctors, and admins), appointments, schedules, and bookings.

## Tech Stack
- **Framework**: Laravel 11
- **Authentication**: Laravel Sanctum (API Tokens)
- **Database**: MySQL
- **PHP**: 8.2+

## Installation & Setup

### Prerequisites
- PHP 8.2 or higher
- Composer
- MySQL 8.0 or higher
- Node.js (for frontend assets)

### Steps

1. **Clone Repository**
   ```bash
   git clone <repository-url>
   cd health-appointment
   ```

2. **Install Dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Environment Configuration**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database Setup**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

5. **Start Development Server**
   ```bash
   php artisan serve
   ```

## API Endpoints

### Base URL
```
http://localhost:8000/api
```

### Authentication

All protected endpoints require the `Authorization` header:
```
Authorization: Bearer <token>
```

---

## Public Endpoints

### 1. Authentication Routes

#### Register
- **POST** `/auth/register`
- **Request Body:**
  ```json
  {
    "full_name": "John Doe",
    "phone": "01712345678",
    "email": "john@example.com",
    "password": "password",
    "password_confirmation": "password",
    "user_type": "patient"
  }
  ```
- **Response:** `201 Created`
  ```json
  {
    "message": "User registered successfully",
    "user": { ... },
    "token": "token_string"
  }
  ```

#### Login
- **POST** `/auth/login`
- **Request Body:**
  ```json
  {
    "phone": "01712345678",
    "password": "password"
  }
  ```
- **Response:** `200 OK`

### 2. Doctor Discovery

#### Get All Doctors
- **GET** `/doctors`
- **Query Parameters:**
  - `specialized_area` (optional): Filter by specialization
  - `search` (optional): Search by name, phone, or email
  - `per_page` (optional): Default 15
- **Response:** `200 OK` - Paginated list of doctors

#### Get Doctor by ID
- **GET** `/doctors/{id}`
- **Response:** `200 OK` - Doctor details with appointments and schedules

### 3. Specializations

#### Get All Specializations
- **GET** `/specialists`
- **Query Parameters:**
  - `per_page` (optional): Default 15
- **Response:** `200 OK` - List of specializations

#### Get Specialization by ID
- **GET** `/specialists/{id}`
- **Response:** `200 OK`

### 4. Appointments

#### Get Available Appointments
- **GET** `/appointments`
- **Query Parameters:**
  - `doctor_id` (optional): Filter by doctor
  - `per_page` (optional): Default 15
- **Response:** `200 OK` - Paginated list

#### Get Appointment Details
- **GET** `/appointments/{id}`
- **Response:** `200 OK` - With schedules and bookings

#### Get Appointment Schedules
- **GET** `/appointments/{id}/schedules`
- **Response:** `200 OK` - List of schedules

---

## Protected Endpoints (Requires Authentication)

### 1. Authentication Routes

#### Logout
- **POST** `/auth/logout`
- **Response:** `200 OK`

#### Get Current User
- **GET** `/auth/me`
- **Response:** `200 OK`

#### Update Profile
- **PUT** `/auth/profile`
- **Request Body:**
  ```json
  {
    "full_name": "Updated Name",
    "email": "newemail@example.com",
    "phone": "01712345678"
  }
  ```
- **Response:** `200 OK`

#### Change Password
- **PUT** `/auth/change-password`
- **Request Body:**
  ```json
  {
    "old_password": "current_password",
    "password": "new_password",
    "password_confirmation": "new_password"
  }
  ```
- **Response:** `200 OK`

### 2. Doctor Profile Routes

#### Create Doctor Profile
- **POST** `/doctor/profile` (Doctor users only)
- **Request Body:**
  ```json
  {
    "specialized_area": 1,
    "years_of_experience": 10
  }
  ```
- **Response:** `201 Created`

#### Get Doctor Profile
- **GET** `/doctor/profile` (Doctor users only)
- **Response:** `200 OK`

#### Update Doctor Profile
- **PUT** `/doctor/profile` (Doctor users only)
- **Request Body:**
  ```json
  {
    "specialized_area": 2,
    "years_of_experience": 12
  }
  ```
- **Response:** `200 OK`

### 3. Appointment Management Routes (Doctors)

#### Create Appointment
- **POST** `/appointments` (Doctor users only)
- **Request Body:**
  ```json
  {
    "hospital_name": "City Hospital",
    "hospital_location": "Downtown",
    "chamber_location": "Building A, Room 101",
    "visiting_fee": 500
  }
  ```
- **Response:** `201 Created`

#### Update Appointment
- **PUT** `/appointments/{id}` (Doctor users only - own appointments)
- **Request Body:** Same as create
- **Response:** `200 OK`

#### Delete Appointment
- **DELETE** `/appointments/{id}` (Doctor users only - own appointments)
- **Response:** `200 OK`

#### Get Doctor's Appointments
- **GET** `/my-appointments` (Doctor users only)
- **Query Parameters:**
  - `per_page` (optional): Default 15
- **Response:** `200 OK` - Paginated list

### 4. Appointment Schedule Routes (Doctors)

#### Create Schedule
- **POST** `/appointment-schedules` (Doctor users only)
- **Request Body:**
  ```json
  {
    "appointment_id": 1,
    "appointment_day": "Monday",
    "available_start_time": "09:00",
    "appointment_duration_max": 30
  }
  ```
- **Response:** `201 Created`

#### Update Schedule
- **PUT** `/appointment-schedules/{id}` (Doctor users only - own schedules)
- **Request Body:** Partial update allowed
- **Response:** `200 OK`

#### Delete Schedule
- **DELETE** `/appointment-schedules/{id}` (Doctor users only - own schedules)
- **Response:** `200 OK`

### 5. Booking Routes (Patients)

#### Create Booking
- **POST** `/bookings` (Patient users only)
- **Request Body:**
  ```json
  {
    "appointment_id": 1,
    "appointment_schedule_id": 5,
    "appointment_date": "2025-04-15"
  }
  ```
- **Response:** `201 Created`

#### Get Booking Details
- **GET** `/bookings/{id}`
- **Response:** `200 OK`

#### Cancel Booking
- **DELETE** `/bookings/{id}` (Patient users - own bookings, or Admin)
- **Response:** `200 OK`

#### Get Patient's Bookings
- **GET** `/my-bookings` (Patient users only)
- **Query Parameters:**
  - `per_page` (optional): Default 15
- **Response:** `200 OK` - Paginated list

#### Get Doctor's Bookings
- **GET** `/bookings-doctor` (Doctor users only)
- **Query Parameters:**
  - `per_page` (optional): Default 15
- **Response:** `200 OK` - All bookings for their appointments

#### Get Bookings for Appointment
- **GET** `/appointments/{id}/bookings` (Doctor users - own appointments, or Admin)
- **Query Parameters:**
  - `per_page` (optional): Default 15
- **Response:** `200 OK` - Paginated list

### 6. Admin Routes

#### Create Specialization
- **POST** `/specialists` (Admin users only)
- **Request Body:**
  ```json
  {
    "name": "Neurology"
  }
  ```
- **Response:** `201 Created`

#### Update Specialization
- **PUT** `/specialists/{id}` (Admin users only)
- **Request Body:** Same as create
- **Response:** `200 OK`

#### Delete Specialization
- **DELETE** `/specialists/{id}` (Admin users only)
- **Response:** `200 OK`

#### Get All Users
- **GET** `/admin/users` (Admin users only)
- **Query Parameters:**
  - `per_page` (optional): Default 15
- **Response:** `200 OK` - Paginated list

#### Get Dashboard Statistics
- **GET** `/admin/statistics` (Admin users only)
- **Response:** `200 OK`
  ```json
  {
    "total_users": 100,
    "total_patients": 70,
    "total_doctors": 25,
    "total_appointments": 50,
    "total_bookings": 200,
    "active_appointments": 50
  }
  ```

#### Get All Bookings
- **GET** `/admin/bookings` (Admin users only)
- **Query Parameters:**
  - `per_page` (optional): Default 15
- **Response:** `200 OK` - Paginated list

#### Delete User
- **DELETE** `/admin/users/{id}` (Admin users only)
- **Response:** `200 OK`

---

## Error Responses

### 400 Bad Request
```json
{
  "message": "Bad Request"
}
```

### 401 Unauthorized
```json
{
  "message": "Unauthorized - Invalid credentials",
  "errors": {}
}
```

### 403 Forbidden
```json
{
  "message": "Unauthorized - Only [type] can [action]"
}
```

### 404 Not Found
```json
{
  "message": "[Resource] not found"
}
```

### 409 Conflict
```json
{
  "message": "Conflict - [Details]"
}
```

### 422 Unprocessable Entity
```json
{
  "message": "Validation failed",
  "errors": {
    "field_name": ["Error message"]
  }
}
```

### 500 Internal Server Error
```json
{
  "message": "Internal Server Error"
}
```

---

## Database Schema

### Users Table
- `id` (PK)
- `full_name` (VARCHAR 150)
- `phone` (VARCHAR 20, UNIQUE)
- `email` (VARCHAR 150, UNIQUE, NULLABLE)
- `password_hashed` (VARCHAR 255)
- `user_type` (ENUM: patient, doctor, admin)
- `created_at` (TIMESTAMP)

### Doctors Table
- `id` (PK)
- `user_id` (FK → users)
- `specialized_area` (FK → doctors_specialized_categories, NULLABLE)
- `years_of_experience` (INT)
- `created_at` (TIMESTAMP)

### Doctors Specialized Categories
- `id` (PK)
- `name` (VARCHAR 100, UNIQUE)
- `created_at` (TIMESTAMP)

### Appointments Table
- `id` (PK)
- `doctor_user_id` (FK → users)
- `hospital_location` (VARCHAR 255, NULLABLE)
- `hospital_name` (VARCHAR 150, NULLABLE)
- `chamber_location` (VARCHAR 255, NULLABLE)
- `visiting_fee` (DECIMAL 10,2)
- `created_at` (TIMESTAMP)

### Appointment Schedules Table
- `id` (PK)
- `appointment_id` (FK → appointments)
- `appointment_day` (ENUM: Monday-Sunday)
- `available_start_time` (TIME)
- `appointment_duration_max` (INT - minutes)
- `created_at` (TIMESTAMP)

### Booked Appointments Table
- `id` (PK)
- `booking_user_id` (FK → users)
- `appointment_id` (FK → appointments)
- `appointment_schedule_id` (FK → appointment_schedules)
- `appointment_date` (DATE)
- `created_at` (TIMESTAMP)

---

## User Types & Permissions

### Patient
- Register and login
- View doctors and specializations
- View available appointments and schedules
- Book appointments
- View own bookings
- Cancel own bookings
- Update own profile

### Doctor
- Register and login
- Create doctor profile
- Update doctor profile
- Create appointments (setups)
- Update own appointments
- Delete own appointments
- Create appointment schedules
- Update own schedules
- Delete own schedules
- View own appointments
- View own bookings
- Update own profile

### Admin
- All patient capabilities
- Create/update/delete specializations
- View all users
- View all bookings
- View dashboard statistics
- Delete users

---

## Testing

### Using Postman

1. Register a user
2. Copy the returned token
3. Set up Postman:
   - Collection: Create a new collection
   - Authorization: Inherit from parent
   - Pre-request Script:
     ```javascript
     pm.environment.set("token", pm.response.json().token);
     ```

### Sample cURL Commands

**Register:**
```bash
curl -X POST http://localhost:8000/api/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "full_name": "John Doe",
    "phone": "01712345678",
    "email": "john@example.com",
    "password": "password",
    "password_confirmation": "password",
    "user_type": "patient"
  }'
```

**Login:**
```bash
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "phone": "01712345678",
    "password": "password"
  }'
```

**Protected Request:**
```bash
curl -X GET http://localhost:8000/api/auth/me \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

---

## CORS Configuration

The API is configured to work with Vue.js frontend. Update `config/cors.php` if needed:

```php
'allowed_origins' => ['http://localhost:3000', 'http://localhost:8080'],
```

---

## Environment Variables

Key environment variables to configure:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=health_appointment
DB_USERNAME=root
DB_PASSWORD=

APP_URL=http://localhost:8000
FRONTEND_URL=http://localhost:3000
```

---

## Performance Tips

1. **Pagination**: Always use pagination for list endpoints (`per_page` parameter)
2. **Eager Loading**: Use relationships efficiently (`.with()` in controllers)
3. **Caching**: Implement Redis caching for frequently accessed data
4. **Rate Limiting**: Consider adding rate limiting middleware

---

## Future Enhancements

- Add appointment notifications (Email/SMS)
- Implement review/rating system
- Add payment integration
- Implement appointment reminders
- Add appointment status tracking
- Implement cancellation policies
- Add prescription management
- Implement video consultation bookings

---

## Support & Contact

For issues or questions, please contact the development team.

---

## License

This project is licensed under the MIT License.
