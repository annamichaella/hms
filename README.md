# Hospital Management System - Laravel

A comprehensive hospital management system built with Laravel 11, featuring role-based access control, appointment scheduling, patient records management, billing, and ward management.

## Features

### 🏥 Core Functionality
- **User Management**: Multi-role system (Admin, Doctor, Nurse, Staff, Patient)
- **Appointment Scheduling**: Book, manage, and track appointments
- **Patient Records**: Medical history, allergies, emergency contacts
- **Billing System**: Generate and manage patient bills
- **Ward Management**: Hospital ward and bed management
- **Dashboard**: Role-specific dashboards with analytics

### 🔐 Authentication & Authorization
- Secure login/registration system
- Role-based access control
- Password hashing and validation
- Session management

### 📱 User Roles

#### Admin
- Full system access
- User management
- System analytics
- All module access

#### Doctor
- View assigned appointments
- Manage patient records
- Update appointment status
- View patient information

#### Nurse
- Patient care management
- Ward assignments
- Patient monitoring

#### Staff
- Appointment management
- Billing operations
- Ward administration

#### Patient
- Book appointments
- View medical records
- Check billing status
- Manage profile

## Installation

### Prerequisites
- PHP 8.2 or higher
- Composer
- MySQL/SQLite
- Node.js (for asset compilation)

### Setup Instructions

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd hospital-management-laravel
   ```

2. **Install dependencies**
   ```bash
   composer install
   ```

3. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database configuration**
   - Update `.env` file with your database credentials
   - For SQLite (default): Database file will be created automatically
   - For MySQL: Create a database and update `.env` accordingly

5. **Run migrations and seeders**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

6. **Start the development server**
   ```bash
   php artisan serve
   ```

7. **Access the application**
   - Open your browser and go to `http://localhost:8000`

## Default Login Credentials

The seeder creates several test accounts:

### Admin
- **Email**: admin@hospital.com
- **Password**: password

### Doctor
- **Email**: doctor1@hospital.com
- **Password**: password

### Nurse
- **Email**: nurse1@hospital.com
- **Password**: password

### Staff
- **Email**: staff1@hospital.com
- **Password**: password

### Patient
- **Email**: patient1@hospital.com
- **Password**: password

## Project Structure

```
hospital-management-laravel/
├── app/
│   ├── Http/Controllers/
│   │   ├── Auth/           # Authentication controllers
│   │   ├── Admin/          # Admin-specific controllers
│   │   ├── Doctor/         # Doctor-specific controllers
│   │   ├── Nurse/          # Nurse-specific controllers
│   │   ├── Patient/        # Patient-specific controllers
│   │   └── Staff/          # Staff-specific controllers
│   └── Models/             # Eloquent models
├── database/
│   ├── migrations/         # Database migrations
│   └── seeders/           # Database seeders
├── resources/
│   └── views/             # Blade templates
│       ├── auth/          # Authentication views
│       ├── admin/         # Admin dashboard views
│       ├── doctor/        # Doctor dashboard views
│       ├── nurse/         # Nurse dashboard views
│       ├── patient/       # Patient dashboard views
│       ├── staff/         # Staff dashboard views
│       └── layouts/       # Layout templates
└── routes/
    └── web.php            # Web routes
```

## Database Schema

### Core Tables
- **users**: User accounts with role-based access
- **appointments**: Appointment scheduling and management
- **patient_records**: Medical records and patient information
- **billings**: Billing and payment tracking
- **wards**: Hospital ward management
- **beds**: Bed assignments and availability
- **patient_admissions**: Patient admission tracking
- **doctor_schedules**: Doctor availability schedules

## Key Features Implementation

### 1. Role-Based Access Control
- Custom middleware for role verification
- Route protection based on user roles
- Dynamic navigation based on user permissions

### 2. Appointment System
- Multi-role appointment management
- Status tracking (pending, confirmed, completed, cancelled)
- Doctor-patient relationship management

### 3. Patient Records
- Comprehensive medical history storage
- Blood type and allergy tracking
- Emergency contact information

### 4. Billing System
- Service-based billing
- Payment status tracking
- Due date management

### 5. Ward Management
- Ward capacity tracking
- Bed availability management
- Patient admission system

## Technology Stack

- **Backend**: Laravel 11
- **Frontend**: Blade templates with Tailwind CSS
- **Database**: SQLite (development) / MySQL (production)
- **Authentication**: Laravel's built-in auth system
- **Icons**: Font Awesome
- **Styling**: Tailwind CSS

## Development

### Running Tests
```bash
php artisan test
```

### Code Style
```bash
php artisan pint
```

### Database Refresh
```bash
php artisan migrate:fresh --seed
```

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests if applicable
5. Submit a pull request

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Support

For support and questions, please contact the development team or create an issue in the repository.

---

**Note**: This is a development version of the hospital management system. For production use, additional security measures, testing, and configuration should be implemented.