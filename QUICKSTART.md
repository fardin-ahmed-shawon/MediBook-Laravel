# ⚡ Quick Start Guide - Health Appointment System

## 🎯 Getting Started in 5 Minutes

### Step 1: Configure Database (1 minute)
```bash
# Edit .env file
DB_DATABASE=health_appointment
DB_USERNAME=root
DB_PASSWORD=  # Leave empty for local dev
```

### Step 2: Run Setup (2 minutes)
```bash
# Windows
setup.bat

# Linux/Mac
bash setup.sh

# OR Manual
php artisan migrate
php artisan db:seed
```

### Step 3: Start Server (1 minute)
```bash
php artisan serve
# API runs at http://localhost:8000/api
```

### Step 4: Test (1 minute)
```bash
# Login as admin
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "phone": "01700000000",
    "password": "password"
  }'
```

✅ **Done!** Your backend is ready.

---

## 📚 Documentation Quick Links

| Document | Purpose | Read Time |
|----------|---------|-----------|
| **API_DOCUMENTATION.md** | All API endpoints & examples | 15 min |
| **SETUP_GUIDE.md** | Installation & integration | 10 min |
| **API_TESTING.md** | Testing with cURL/Postman | 10 min |
| **PROJECT_OVERVIEW.md** | Architecture & file structure | 15 min |

---

## 🔑 Test Credentials (After Seeding)

```
Admin:
  Phone: 01700000000
  Password: password
  
Doctor:
  Phone: 01710000001
  Password: password
  
Patient:
  Phone: 01700000001
  Password: password
```

---

## 🚀 Common Commands

```bash
# Start server
php artisan serve

# Fresh migrations + seeding
php artisan migrate:fresh --seed

# Create new migration
php artisan make:migration create_table_name

# Create controller
php artisan make:controller ControllerName

# Create model + migration
php artisan make:model ModelName -m

# View routes
php artisan route:list | grep -i api

# Run tests
php artisan test

# Clear cache
php artisan cache:clear
```

---

## 🧪 Quick API Tests

### 1. Get Doctors
```bash
curl http://localhost:8000/api/doctors
```

### 2. Get Specializations
```bash
curl http://localhost:8000/api/specialists
```

### 3. Login
```bash
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"phone":"01700000000","password":"password"}'
```

### 4. Use Token
```bash
TOKEN="1|abc123..."
curl http://localhost:8000/api/auth/me \
  -H "Authorization: Bearer $TOKEN"
```

---

## 🔗 Frontend Integration

### Install Dependencies
```bash
npm install axios
```

### Create API Service
```javascript
// services/api.js
import axios from 'axios';

const API = axios.create({
  baseURL: 'http://localhost:8000/api'
});

// Add token to requests
API.interceptors.request.use(config => {
  const token = localStorage.getItem('token');
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

export default API;
```

### Use in Components
```vue
<script setup>
import { ref } from 'vue';
import API from '@/services/api';

const doctors = ref([]);

const getDoctors = async () => {
  const { data } = await API.get('/doctors');
  doctors.value = data.data;
};
</script>
```

---

## ❌ Troubleshooting

### Port 8000 already in use
```bash
php artisan serve --port=8001
```

### Database connection error
```bash
# Check .env database settings
# Create database if not exists:
mysql -u root -e "CREATE DATABASE health_appointment;"
```

### Migrations fail
```bash
# Check database is running
# Run with force flag
php artisan migrate --force
```

### Token not working
```bash
# Clear cache
php artisan cache:clear
# Ensure Sanctum is installed
composer require laravel/sanctum
```

---

## 📊 Project Stats

| Item | Count |
|------|-------|
| Controllers | 7 |
| Models | 6 |
| Migrations | 6 |
| API Endpoints | 40+ |
| Documentation Pages | 4 |
| Lines of Code | 3,000+ |
| Setup Time | < 5 min |

---

## ✨ Key Features

✅ User authentication (Sanctum)  
✅ Doctor profile management  
✅ Appointment scheduling  
✅ Patient booking system  
✅ Admin dashboard  
✅ Role-based access control  
✅ Complete API documentation  
✅ Sample data included  

---

## 🎓 Next Steps

1. **Read** API_DOCUMENTATION.md for endpoint details
2. **Test** endpoints using provided cURL commands
3. **Build** Vue.js frontend using examples
4. **Connect** frontend to backend API
5. **Style** and deploy

---

## 📞 Help & Support

**For API questions:** See `API_DOCUMENTATION.md`  
**For setup help:** See `SETUP_GUIDE.md`  
**For testing:** See `API_TESTING.md`  
**For architecture:** See `PROJECT_OVERVIEW.md`  

---

## ✅ Ready to Go!

Your backend is production-ready. Start building your Vue.js frontend and connect it using the provided examples.

**Happy coding! 🚀**
