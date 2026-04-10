#!/bin/bash
# Health Appointment System - Automatic Setup Script
# Run this script to automatically set up the entire backend

set -e

echo "================================"
echo "Health Appointment System Setup"
echo "================================"
echo ""

# Check if .env exists
if [ ! -f .env ]; then
    echo "📋 Creating .env file from .env.example..."
    cp .env.example .env
    php artisan key:generate
    echo "✅ .env file created and key generated"
else
    echo "✅ .env file already exists"
fi

echo ""
echo "📦 Installing dependencies..."
composer install --no-interaction
npm install

echo ""
echo "🗄️  Setting up database..."
echo "Running migrations..."
php artisan migrate --force
echo "✅ Migrations completed"

echo ""
echo "🌱 Seeding database with sample data..."
php artisan db:seed --class=DatabaseSeeder
echo "✅ Database seeded"

echo ""
echo "🔧 Caching configuration (optional)..."
# Uncomment for production
# php artisan config:cache
# php artisan route:cache

echo ""
echo "================================"
echo "✅ Setup Complete!"
echo "================================"
echo ""
echo "📝 Documentation Files:"
echo "   - API_DOCUMENTATION.md     (API Reference)"
echo "   - SETUP_GUIDE.md           (Installation & Integration)"
echo "   - API_TESTING.md           (Testing Examples)"
echo "   - IMPLEMENTATION_SUMMARY.md (Quick Overview)"
echo ""
echo "🚀 Next Steps:"
echo "   1. Start server:  php artisan serve"
echo "   2. API URL:       http://localhost:8000/api"
echo "   3. Admin:         Phone: 01700000000, Password: password"
echo "   4. Check docs:    Read API_DOCUMENTATION.md"
echo ""
echo "💡 Test Endpoint:"
echo "   curl http://localhost:8000/api/specialists"
echo ""
