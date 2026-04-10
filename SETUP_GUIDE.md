# Health Appointment Booking System - Setup Guide

## Project Structure

```
health-appointment/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       ├── AuthController.php              (User authentication)
│   │       ├── DoctorController.php            (Doctor management)
│   │       ├── DoctorSpecializedCategoryController.php
│   │       ├── AppointmentController.php       (Appointment CRUD)
│   │       ├── AppointmentScheduleController.php
│   │       ├── BookedAppointmentController.php (Booking management)
│   │       └── AdminController.php             (Admin functions)
│   └── Models/
│       ├── User.php
│       ├── Doctor.php
│       ├── DoctorSpecializedCategory.php
│       ├── Appointment.php
│       ├── AppointmentSchedule.php
│       └── BookedAppointment.php
├── database/
│   ├── migrations/
│   │   ├── 0001_01_01_000000_create_users_table.php
│   │   ├── 2025_04_10_000001_create_doctors_specialized_categories_table.php
│   │   ├── 2025_04_10_000002_create_doctors_table.php
│   │   ├── 2025_04_10_000003_create_appointments_table.php
│   │   ├── 2025_04_10_000004_create_appointment_schedules_table.php
│   │   └── 2025_04_10_000005_create_booked_appointments_table.php
│   └── seeders/
│       └── DatabaseSeeder.php
└── routes/
    └── api.php
```

## Quick Start

### 1. Database Setup

```bash
# Run migrations
php artisan migrate

# Seed sample data
php artisan db:seed
```

**Sample Data Created:**
- 1 Admin user (phone: 01700000000, password: password)
- 5 Patient users (phone: 01700000001-5)
- 5 Doctor users (phone: 01710000001-5)
- 8 Specialization categories
- Sample appointments and schedules for each doctor

### 2. Start Development Server

```bash
php artisan serve
# API will be available at http://localhost:8000/api
```

### 3. Test Authentication

Open Postman or similar and test:

```bash
# Register
POST http://localhost:8000/api/auth/register

# Login
POST http://localhost:8000/api/auth/login

# Use returned token for authenticated requests
GET http://localhost:8000/api/auth/me
Header: Authorization: Bearer <token>
```

---

## API Features

### Authentication System
- **Sanctum Tokens**: Each user gets a unique API token
- **User Types**: patient, doctor, admin
- **Password Hashing**: Uses bcrypt

### Doctor Module
- Create and manage doctor profiles
- Link to specialized categories
- Track years of experience
- View all appointments and bookings

### Appointment Management
- Doctors create appointment setups
- Define multiple locations/chambers per doctor
- Set visiting fees
- Create weekly schedules per appointment

### Booking System
- Patients book specific appointment slots
- Future date validation
- Duplicate booking prevention
- Booking history for patients and doctors

### Admin Dashboard
- View all users and statistics
- Manage specialization categories
- Monitor all bookings
- Delete users if necessary

---

## Important Model Relationships

```
User
├── hasOne: Doctor
├── hasMany: Appointment (as doctor_user_id)
└── hasMany: BookedAppointment (as booking_user_id)

Doctor
├── belongsTo: User
├── belongsTo: DoctorSpecializedCategory
└── hasMany: Appointment

Appointment
├── belongsTo: User (as doctor_user_id)
├── hasMany: AppointmentSchedule
└── hasMany: BookedAppointment

AppointmentSchedule
├── belongsTo: Appointment
└── hasMany: BookedAppointment

BookedAppointment
├── belongsTo: User (as booking_user_id - patient)
├── belongsTo: Appointment
└── belongsTo: AppointmentSchedule
```

---

## Authentication Flow for Frontend

### 1. Register New User
```javascript
const response = await fetch('http://localhost:8000/api/auth/register', {
  method: 'POST',
  headers: { 'Content-Type': 'application/json' },
  body: JSON.stringify({
    full_name: 'John Doe',
    phone: '01712345678',
    email: 'john@example.com',
    password: 'password',
    password_confirmation: 'password',
    user_type: 'patient'
  })
});

const data = await response.json();
localStorage.setItem('token', data.token);
```

### 2. Login
```javascript
const response = await fetch('http://localhost:8000/api/auth/login', {
  method: 'POST',
  headers: { 'Content-Type': 'application/json' },
  body: JSON.stringify({
    phone: '01712345678',
    password: 'password'
  })
});

const data = await response.json();
localStorage.setItem('token', data.token);
```

### 3. Make Authenticated Requests
```javascript
const response = await fetch('http://localhost:8000/api/auth/me', {
  headers: {
    'Authorization': `Bearer ${localStorage.getItem('token')}`
  }
});

const user = await response.json();
```

### 4. Logout
```javascript
const response = await fetch('http://localhost:8000/api/auth/logout', {
  method: 'POST',
  headers: {
    'Authorization': `Bearer ${localStorage.getItem('token')}`
  }
});

localStorage.removeItem('token');
```

---

## Vue.js Integration Example

Create a composable for API requests:

```javascript
// composables/useApi.js
import { ref } from 'vue';

export function useApi() {
  const token = localStorage.getItem('token');
  
  const request = async (method, endpoint, data = null) => {
    const options = {
      method,
      headers: {
        'Content-Type': 'application/json',
        ...(token && { 'Authorization': `Bearer ${token}` })
      }
    };
    
    if (data) options.body = JSON.stringify(data);
    
    const response = await fetch(`http://localhost:8000/api${endpoint}`, options);
    return response.json();
  };
  
  return {
    get: (endpoint) => request('GET', endpoint),
    post: (endpoint, data) => request('POST', endpoint, data),
    put: (endpoint, data) => request('PUT', endpoint, data),
    delete: (endpoint) => request('DELETE', endpoint)
  };
}
```

Usage in Vue components:

```vue
<script setup>
import { useApi } from '@/composables/useApi';

const api = useApi();

// Get doctors
const doctors = ref([]);
const getDoctors = async () => {
  const response = await api.get('/doctors');
  doctors.value = response.data;
};

// Book appointment
const bookAppointment = async (appointmentId, scheduleId, date) => {
  const response = await api.post('/bookings', {
    appointment_id: appointmentId,
    appointment_schedule_id: scheduleId,
    appointment_date: date
  });
  console.log('Booking confirmed:', response);
};

onMounted(() => {
  getDoctors();
});
</script>
```

---

## CORS Configuration (If Needed)

Update CORS in `config/cors.php` after creating the file:

```bash
php artisan add-cors
```

Or manually configure in middleware:

```php
// app/Http/Middleware/HandleCors.php
// Add CORS headers for frontend at localhost:3000 or 8080
```

---

## Development Checklist

- [x] Database schema created
- [x] Models with relationships
- [x] Authentication system (Register, Login, Logout)
- [x] Doctor profile management
- [x] Appointment creation and management
- [x] Schedule management
- [x] Booking system
- [x] Admin dashboard
- [x] Error handling
- [x] Validation
- [x] API documentation
- [ ] Unit tests (To be added)
- [ ] Integration tests (To be added)
- [ ] Email notifications (To be added)
- [ ] SMS notifications (To be added)

---

## Common Issues & Solutions

### Issue: CORS Error
**Solution**: Make sure to configure CORS in Laravel if frontend is on different port.

### Issue: Token Invalid
**Solution**: Ensure token is properly stored and sent with `Authorization: Bearer <token>` header.

### Issue: Database Migration Error
**Solution**: 
- Check database credentials in `.env`
- Run `php artisan migrate:fresh` to reset migrations
- Ensure MySQL is running

### Issue: Can't Login After Registration
**Solution**: 
- Check phone number format (ensure it's unique)
- Verify password is set correctly using Hash::make()

---

## Performance Optimization Tips

1. **Add Database Indexes**: Already handled in migrations
2. **Cache**: Implement Redis for frequently accessed data
3. **Pagination**: Always paginate list endpoints
4. **Eager Loading**: Use `.with()` to load relationships
5. **Query Optimization**: Use appropriate WHERE clauses

---

## Deployment Checklist

- [ ] Set `APP_ENV=production` in `.env`
- [ ] Set `APP_DEBUG=false`
- [ ] Generate new app key: `php artisan key:generate`
- [ ] Run migrations: `php artisan migrate --force`
- [ ] Optimize autoloading: `composer install --optimize-autoloader --no-dev`
- [ ] Cache routes: `php artisan route:cache`
- [ ] Cache config: `php artisan config:cache`
- [ ] Set up proper environment variables
- [ ] Configure CORS for production domain
- [ ] Set up SSL certificate
- [ ] Configure database backups

---

## Next Steps

1. Connect with Vue.js frontend using the provided integration examples
2. Implement email/SMS notifications for bookings
3. Add payment gateway integration
4. Implement review/rating system
5. Add advanced filtering and search
6. Implement appointment status tracking
7. Add prescription management
8. Create admin dashboard UI

---

## Support Files

- Main Documentation: [API_DOCUMENTATION.md](./API_DOCUMENTATION.md)
- Database Schema: See migrations in `database/migrations/`
- Models: `app/Models/`
- Controllers: `app/Http/Controllers/`

---

## Need Help?

Refer to the comprehensive API_DOCUMENTATION.md for:
- Detailed endpoint documentation
- Request/response examples
- Error handling
- Database schema details
- User permissions and roles
