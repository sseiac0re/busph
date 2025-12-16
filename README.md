# BusPH - Bus Booking System

A comprehensive Laravel-based bus reservation and management system for the Philippines.

## Features

- **User Features:**
  - User registration and email verification
  - Bus route search and booking
  - Seat selection
  - Booking management (view, cancel bookings)
  - Digital receipts
  - Profile management

- **Admin Features:**
  - Bus management (CRUD operations)
  - Route management with coordinates
  - Schedule management
  - Schedule templates for bulk generation
  - Reservation management and verification
  - Cancellation approval/rejection
  - Dashboard analytics

## Technology Stack

- **Backend:** Laravel 11
- **Frontend:** Blade Templates, Tailwind CSS, JavaScript
- **Database:** MySQL/PostgreSQL
- **Authentication:** Laravel Sanctum

## Installation

1. Clone the repository:
```bash
git clone https://github.com/sseiac0re/busph.git
cd busph
```

2. Install dependencies:
```bash
composer install
npm install
```

3. Configure environment:
```bash
cp .env.example .env
php artisan key:generate
```

4. Set up database in `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=busph
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

5. Run migrations:
```bash
php artisan migrate
```

6. Build assets:
```bash
npm run build
```

7. Start the development server:
```bash
php artisan serve
```

## Project Structure

```
busph/
├── app/
│   ├── Console/Commands/     # Artisan commands
│   ├── Http/
│   │   ├── Controllers/      # Application controllers
│   │   ├── Middleware/       # Custom middleware
│   │   └── Requests/         # Form request validation
│   ├── Interfaces/           # Repository interfaces
│   ├── Mail/                 # Email classes
│   ├── Models/               # Eloquent models
│   ├── Notifications/        # Notification classes
│   ├── Providers/            # Service providers
│   └── Repositories/         # Repository implementations
├── database/
│   ├── migrations/           # Database migrations
│   └── seeders/             # Database seeders
├── resources/
│   ├── views/               # Blade templates
│   ├── css/                 # Stylesheets
│   └── js/                  # JavaScript files
└── routes/
    ├── web.php              # Web routes
    └── auth.php             # Authentication routes
```

## Key Routes

### Public Routes
- `/` - Home/Landing page
- `/about` - About page
- `/contact` - Contact form
- `/faq` - FAQ page

### Authenticated Routes
- `/dashboard` - User dashboard
- `/booking/{schedule}/seats` - Seat selection
- `/my-bookings` - User's bookings

### Admin Routes
- `/admin/dashboard` - Admin dashboard
- `/admin/buses` - Bus management
- `/admin/routes` - Route management
- `/admin/schedules` - Schedule management
- `/admin/reservations` - Reservation management
- `/admin/templates` - Schedule templates

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
