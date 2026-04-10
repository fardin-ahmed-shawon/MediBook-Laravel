# Health Appointment System - API Testing Collection

This file contains ready-to-use cURL commands and test scenarios for the Health Appointment System API.

## Testing Scenarios

### 1. USER REGISTRATION & AUTHENTICATION

#### Register as Patient
```bash
curl -X POST http://localhost:8000/api/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "full_name": "Ahmed Khan",
    "phone": "01850001234",
    "email": "ahmed@example.com",
    "password": "password123",
    "password_confirmation": "password123",
    "user_type": "patient"
  }'
```

Expected Response: `201 Created`
```json
{
  "message": "User registered successfully",
  "user": {
    "id": 11,
    "full_name": "Ahmed Khan",
    "phone": "01850001234",
    "email": "ahmed@example.com",
    "user_type": "patient",
    "created_at": "2025-04-10T..."
  },
  "token": "1|abc123def456..."
}
```

#### Register as Doctor
```bash
curl -X POST http://localhost:8000/api/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "full_name": "Dr. Fatima Ahmed",
    "phone": "01851001234",
    "email": "fatima@example.com",
    "password": "password123",
    "password_confirmation": "password123",
    "user_type": "doctor"
  }'
```

#### Login with Phone
```bash
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "phone": "01850001234",
    "password": "password123"
  }'
```

Expected Response: `200 OK`
```json
{
  "message": "Login successful",
  "user": { ... },
  "token": "2|xyz789..."
}
```

#### Get Current User (Save token from login)
```bash
TOKEN="2|xyz789..."
curl -X GET http://localhost:8000/api/auth/me \
  -H "Authorization: Bearer $TOKEN"
```

#### Update Profile
```bash
TOKEN="2|xyz789..."
curl -X PUT http://localhost:8000/api/auth/profile \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer $TOKEN" \
  -d '{
    "full_name": "Ahmed Khan Updated",
    "email": "newemail@example.com"
  }'
```

#### Change Password
```bash
TOKEN="2|xyz789..."
curl -X PUT http://localhost:8000/api/auth/change-password \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer $TOKEN" \
  -d '{
    "old_password": "password123",
    "password": "newpassword123",
    "password_confirmation": "newpassword123"
  }'
```

#### Logout
```bash
TOKEN="2|xyz789..."
curl -X POST http://localhost:8000/api/auth/logout \
  -H "Authorization: Bearer $TOKEN"
```

---

### 2. DOCTOR PROFILE MANAGEMENT

#### Create Doctor Profile (As Doctor User)
```bash
DOCTOR_TOKEN="3|doctor_token..."
curl -X POST http://localhost:8000/api/doctor/profile \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer $DOCTOR_TOKEN" \
  -d '{
    "specialized_area": 1,
    "years_of_experience": 15
  }'
```

Expected Response: `201 Created`

#### Get Doctor Profile
```bash
DOCTOR_TOKEN="3|doctor_token..."
curl -X GET http://localhost:8000/api/doctor/profile \
  -H "Authorization: Bearer $DOCTOR_TOKEN"
```

#### Update Doctor Profile
```bash
DOCTOR_TOKEN="3|doctor_token..."
curl -X PUT http://localhost:8000/api/doctor/profile \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer $DOCTOR_TOKEN" \
  -d '{
    "specialized_area": 2,
    "years_of_experience": 18
  }'
```

---

### 3. SPECIALIZATIONS (ADMIN ONLY)

#### View All Specializations (Public)
```bash
curl -X GET "http://localhost:8000/api/specialists?per_page=10"
```

Expected Response: `200 OK`
```json
{
  "data": [
    {
      "id": 1,
      "name": "Cardiology",
      "created_at": "2025-04-10T..."
    },
    ...
  ],
  "links": { ... },
  "meta": { ... }
}
```

#### Get Specialization by ID
```bash
curl -X GET http://localhost:8000/api/specialists/1
```

#### Create Specialization (Admin Only)
```bash
ADMIN_TOKEN="admin_token..."
curl -X POST http://localhost:8000/api/specialists \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer $ADMIN_TOKEN" \
  -d '{
    "name": "Oncology"
  }'
```

#### Update Specialization (Admin Only)
```bash
ADMIN_TOKEN="admin_token..."
curl -X PUT http://localhost:8000/api/specialists/9 \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer $ADMIN_TOKEN" \
  -d '{
    "name": "Oncology - Updated"
  }'
```

#### Delete Specialization (Admin Only)
```bash
ADMIN_TOKEN="admin_token..."
curl -X DELETE http://localhost:8000/api/specialists/9 \
  -H "Authorization: Bearer $ADMIN_TOKEN"
```

---

### 4. DOCTOR DISCOVERY

#### Get All Doctors (Public)
```bash
curl -X GET "http://localhost:8000/api/doctors?per_page=10"
```

#### Filter Doctors by Specialization
```bash
curl -X GET "http://localhost:8000/api/doctors?specialized_area=1&per_page=10"
```

#### Search Doctors by Name
```bash
curl -X GET "http://localhost:8000/api/doctors?search=Dr.%20Doctor&per_page=10"
```

#### Get Doctor Details
```bash
curl -X GET http://localhost:8000/api/doctors/1
```

Response includes:
- Doctor information
- User details
- Specialized category
- All appointments
- Available schedules

---

### 5. APPOINTMENT MANAGEMENT (DOCTOR)

#### Create Appointment Setup (Doctor Only)
```bash
DOCTOR_TOKEN="doctor_token..."
curl -X POST http://localhost:8000/api/appointments \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer $DOCTOR_TOKEN" \
  -d '{
    "hospital_name": "City Medical Hospital",
    "hospital_location": "Downtown Area",
    "chamber_location": "Flat 5, Building A",
    "visiting_fee": 750
  }'
```

Expected Response: `201 Created`
```json
{
  "message": "Appointment setup created successfully",
  "appointment": {
    "id": 11,
    "doctor_user_id": 6,
    "hospital_name": "City Medical Hospital",
    "hospital_location": "Downtown Area",
    "chamber_location": "Flat 5, Building A",
    "visiting_fee": "750.00",
    "created_at": "2025-04-10T..."
  }
}
```

#### Get Doctor's Appointments (Doctor Only)
```bash
DOCTOR_TOKEN="doctor_token..."
curl -X GET "http://localhost:8000/api/my-appointments?per_page=15" \
  -H "Authorization: Bearer $DOCTOR_TOKEN"
```

#### Update Appointment (Doctor Only)
```bash
DOCTOR_TOKEN="doctor_token..."
curl -X PUT http://localhost:8000/api/appointments/11 \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer $DOCTOR_TOKEN" \
  -d '{
    "visiting_fee": 800
  }'
```

#### Delete Appointment (Doctor Only)
```bash
DOCTOR_TOKEN="doctor_token..."
curl -X DELETE http://localhost:8000/api/appointments/11 \
  -H "Authorization: Bearer $DOCTOR_TOKEN"
```

---

### 6. APPOINTMENT SCHEDULES (DOCTOR)

#### Create Schedule for Appointment
```bash
DOCTOR_TOKEN="doctor_token..."
curl -X POST http://localhost:8000/api/appointment-schedules \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer $DOCTOR_TOKEN" \
  -d '{
    "appointment_id": 1,
    "appointment_day": "Monday",
    "available_start_time": "09:00",
    "appointment_duration_max": 30
  }'
```

#### Get Schedules for Appointment (Public)
```bash
curl -X GET http://localhost:8000/api/appointments/1/schedules
```

Expected Response:
```json
[
  {
    "id": 1,
    "appointment_id": 1,
    "appointment_day": "Monday",
    "available_start_time": "09:00:00",
    "appointment_duration_max": 30,
    "created_at": "2025-04-10T..."
  },
  ...
]
```

#### Update Schedule (Doctor Only)
```bash
DOCTOR_TOKEN="doctor_token..."
curl -X PUT http://localhost:8000/api/appointment-schedules/1 \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer $DOCTOR_TOKEN" \
  -d '{
    "available_start_time": "10:00",
    "appointment_duration_max": 45
  }'
```

#### Delete Schedule (Doctor Only)
```bash
DOCTOR_TOKEN="doctor_token..."
curl -X DELETE http://localhost:8000/api/appointment-schedules/1 \
  -H "Authorization: Bearer $DOCTOR_TOKEN"
```

---

### 7. BOOKING APPOINTMENTS (PATIENT)

#### Get Available Appointments (Public)
```bash
curl -X GET "http://localhost:8000/api/appointments?per_page=15"
```

#### Book Appointment (Patient Only)
```bash
PATIENT_TOKEN="patient_token..."
curl -X POST http://localhost:8000/api/bookings \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer $PATIENT_TOKEN" \
  -d '{
    "appointment_id": 1,
    "appointment_schedule_id": 5,
    "appointment_date": "2025-04-15"
  }'
```

Expected Response: `201 Created`
```json
{
  "message": "Appointment booked successfully",
  "booking": {
    "id": 1,
    "booking_user_id": 11,
    "appointment_id": 1,
    "appointment_schedule_id": 5,
    "appointment_date": "2025-04-15",
    "created_at": "2025-04-10T..."
  }
}
```

#### Get Patient's Bookings (Patient Only)
```bash
PATIENT_TOKEN="patient_token..."
curl -X GET "http://localhost:8000/api/my-bookings?per_page=15" \
  -H "Authorization: Bearer $PATIENT_TOKEN"
```

#### Get Booking Details
```bash
PATIENT_TOKEN="patient_token..."
curl -X GET http://localhost:8000/api/bookings/1 \
  -H "Authorization: Bearer $PATIENT_TOKEN"
```

#### Cancel Booking (Patient Only)
```bash
PATIENT_TOKEN="patient_token..."
curl -X DELETE http://localhost:8000/api/bookings/1 \
  -H "Authorization: Bearer $PATIENT_TOKEN"
```

---

### 8. DOCTOR VIEWING BOOKINGS

#### Get Doctor's Bookings (Doctor Only)
```bash
DOCTOR_TOKEN="doctor_token..."
curl -X GET "http://localhost:8000/api/bookings-doctor?per_page=15" \
  -H "Authorization: Bearer $DOCTOR_TOKEN"
```

Shows all bookings for all the doctor's appointments.

#### Get Bookings for Specific Appointment (Doctor Only)
```bash
DOCTOR_TOKEN="doctor_token..."
curl -X GET "http://localhost:8000/api/appointments/1/bookings?per_page=15" \
  -H "Authorization: Bearer $DOCTOR_TOKEN"
```

---

### 9. ADMIN FUNCTIONS

#### Get All Users (Admin Only)
```bash
ADMIN_TOKEN="admin_token..."
curl -X GET "http://localhost:8000/api/admin/users?per_page=15" \
  -H "Authorization: Bearer $ADMIN_TOKEN"
```

#### Get Dashboard Statistics (Admin Only)
```bash
ADMIN_TOKEN="admin_token..."
curl -X GET http://localhost:8000/api/admin/statistics \
  -H "Authorization: Bearer $ADMIN_TOKEN"
```

Expected Response:
```json
{
  "total_users": 15,
  "total_patients": 5,
  "total_doctors": 5,
  "total_appointments": 10,
  "total_bookings": 3,
  "active_appointments": 10
}
```

#### Get All Bookings (Admin Only)
```bash
ADMIN_TOKEN="admin_token..."
curl -X GET "http://localhost:8000/api/admin/bookings?per_page=15" \
  -H "Authorization: Bearer $ADMIN_TOKEN"
```

#### Delete User (Admin Only)
```bash
ADMIN_TOKEN="admin_token..."
curl -X DELETE http://localhost:8000/api/admin/users/11 \
  -H "Authorization: Bearer $ADMIN_TOKEN"
```

---

## Error Response Examples

### Invalid Credentials
```json
{
  "message": "Login failed",
  "errors": {
    "phone": ["The provided credentials are incorrect."]
  }
}
```

### Validation Error
```json
{
  "message": "Validation failed",
  "errors": {
    "full_name": ["The full name field is required."],
    "phone": ["The phone has already been taken."]
  }
}
```

### Unauthorized Access
```json
{
  "message": "Unauthorized - Only patients can book appointments"
}
```

### Resource Not Found
```json
{
  "message": "Doctor not found"
}
```

---

## Testing with Postman

1. **Create Environment Variables**
   - `base_url`: http://localhost:8000/api
   - `token`: (will be set automatically)
   - `patient_phone`: 01850001234
   - `doctor_phone`: 01851001234

2. **Pre-request Script** (for auto token handling):
   ```javascript
   // For endpoints that require token
   pm.request.headers.add({
     key: 'Authorization',
     value: 'Bearer ' + pm.environment.get('token')
   });
   ```

3. **Tests** (for response validation):
   ```javascript
   // Check if response is 200/201
   pm.test("Status code is correct", function () {
     pm.expect(pm.response.code).to.be.oneOf([200, 201]);
   });

   // Save token from login response
   if (pm.response.code === 200 && pm.response.json().token) {
     pm.environment.set('token', pm.response.json().token);
   }
   ```

---

## Complete Flow Example

```bash
#!/bin/bash

BASE_URL="http://localhost:8000/api"

# 1. Register as patient
echo "1. Registering patient..."
REGISTER_RESPONSE=$(curl -s -X POST $BASE_URL/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "full_name": "Test Patient",
    "phone": "01899999999",
    "email": "patient2@example.com",
    "password": "password123",
    "password_confirmation": "password123",
    "user_type": "patient"
  }')

PATIENT_TOKEN=$(echo $REGISTER_RESPONSE | grep -o '"token":"[^"]*' | cut -d'"' -f4)
echo "Patient Token: $PATIENT_TOKEN"

# 2. View all doctors
echo "2. Viewing all doctors..."
curl -s -X GET "$BASE_URL/doctors?per_page=5" | jq .

# 3. Book an appointment
echo "3. Booking an appointment..."
curl -s -X POST $BASE_URL/bookings \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer $PATIENT_TOKEN" \
  -d '{
    "appointment_id": 1,
    "appointment_schedule_id": 1,
    "appointment_date": "2025-04-20"
  }' | jq .

# 4. View patient bookings
echo "4. Viewing patient bookings..."
curl -s -X GET "$BASE_URL/my-bookings?per_page=10" \
  -H "Authorization: Bearer $PATIENT_TOKEN" | jq .
```

---

## Sample Credentials (After Running Seeder)

**Admin Account:**
- Phone: 01700000000
- Password: password
- User Type: admin

**Doctor Accounts:**
- Phone: 01710000001-5
- Password: password
- User Type: doctor

**Patient Accounts:**
- Phone: 01700000001-5
- Password: password
- User Type: patient

---

## Performance Testing Commands

Test API with multiple concurrent requests:

```bash
# Using Apache Bench (ab)
ab -n 100 -c 10 http://localhost:8000/api/doctors

# Using wrk (if installed)
wrk -t4 -c100 -d30s http://localhost:8000/api/doctors

# Using ApacheBench with POST
ab -n 100 -c 10 -p data.json -T application/json http://localhost:8000/api/auth/login
```

---

## Debugging Tips

1. **Enable API debugging**: Set `APP_DEBUG=true` in `.env`
2. **View logs**: `tail -f storage/logs/laravel.log`
3. **Check database queries**: Use Laravel Debugbar or query logging
4. **Test token validity**: Use JWT.io to decode tokens
5. **Monitor PHP**: Use `php artisan tinker` for quick debugging

