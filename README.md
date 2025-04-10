# Library Management System

A modern web-based library management system built with Laravel, featuring book management, borrowing system, and user role-based permissions.

## Features

### User Management
- Role-based access control (admin, user)
- User registration and authentication
- Profile management

### Book Management
- Complete CRUD operations for books
- Book details include title, author, genre, description, cover image, and PDF version
- Book availability tracking
- Genre categorization

### Borrowing System
- Request to borrow books
- Admin approval workflow
- Return management
- Borrowing history tracking
- Automatic availability updates

### Admin Dashboard
- **Book Management**:
  - Create, edit, and delete books with full CRUD operations
  - Upload and manage book cover images and PDF files
  - Monitor book availability and inventory
  - Control which books are available for borrowing
  
- **Borrowing Management**:
  - Process borrowing requests (approve/reject)
  - View all user borrowings system-wide
  - Update borrowing status manually (pending/approved/rejected/returned)
  - Monitor borrowing history and current borrowings
  - Handle returns and special cases through the admin interface

### User Interface
- Responsive design using Bootstrap
- DataTables for efficient data display and search
- SweetAlert2 for improved user interactions
- AJAX-based operations for seamless experience

## Technical Architecture

### Backend
- Laravel 12.x framework
- PHP 8.4+
- MySQL database
- RESTful API architecture
- Service-oriented design pattern

### Frontend
- Bootstrap 5
- jQuery
- DataTables.js for dynamic tables
- SweetAlert2 for modals and notifications

### Security Features
- CSRF protection
- Form validation (server and client-side)
- Authentication middleware
- Role-based access control

## Directory Structure

The application follows Laravel's standard directory structure with additional service-oriented architecture:

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── BookController.php
│   │   ├── BorrowingController.php
│   └── Requests/
│       ├── Book/
│       ├── Borrowing/
├── Models/
│   ├── Book.php
│   ├── Borrowing.php
│   ├── Genre.php
│   ├── User.php
├── Services/
│   ├── Book/
│   │   ├── BookService.php
│   │   ├── SetBookDataTable.php
│   ├── BorrowingServices/
│       ├── BorrowingCreateService.php
│       ├── BorrowingDataTableService.php
│       ├── BorrowingDetailService.php
│       ├── BorrowingStatusService.php
public/
├── js/
│   ├── books/
│   │   ├── datatable.js
│   │   ├── borrow.js
│   │   ├── delete.js
│   │   ├── edit.js
│   ├── borrowings/
resources/
├── views/
    ├── layouts/
    │   ├── book-layout.blade.php
    │   ├── borrowing-layout.blade.php
    │   ├── guest.blade.php
    ├── components/
    ├── books/
    ├── borrowings/
    ├── profile/
```

## Service Classes

The application uses service classes to maintain clean separation of concerns:

### Book Services
- **BookService**: Handles book CRUD operations
- **SetBookDataTable**: Configures DataTables for book display

### Borrowing Services
- **BorrowingCreateService**: Manages borrowing request creation
- **BorrowingDataTableService**: Configures DataTables for borrowing display
- **BorrowingDetailService**: Retrieves borrowing details with related information
- **BorrowingStatusService**: Handles status changes (approve, reject, return)

## Installation & Setup

### Prerequisites
- PHP 8.1 or higher
- Composer
- MySQL
- Node.js and NPM

### Setup Steps

1. Clone the repository
   ```
   git clone https://github.com/Osama-A-Abbas/LibraryManagementSystem.git
   cd to project dir
   ```

2. Install PHP dependencies
   ```
   composer install
   ```

3. Install frontend dependencies
   ```
   npm install && npm run dev
   ```

4. Configure environment
   ```
   cp .env.example .env
   php artisan key:generate
   ```

5. Configure database in `.env` file
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=library_db
   DB_USERNAME=root
   DB_PASSWORD=
   ```

6. Run migrations and seed database
   ```
   php artisan migrate --seed
   ```

7. Configure storage links
   ```
   php artisan storage:link
   ```

8. Start the development server
   ```
   php artisan serve
   ```

## Usage

### Default User Accounts
- Admin: admin@example.com / password   | all permissions granted
- User: user@example.com / password

### Book Management
1. Navigate to the Books section
2. Use the DataTable to view, search, and sort books
3. Admin users can add, edit, and delete books
4. Upload cover images and PDF versions of books

### Borrowing Process
1. Click "Borrow" button on a book
2. Select borrow and return dates
3. Submit request
4. Admin approves or rejects the request
5. Return books by clicking "Return" in the borrowings section

## API Endpoints

### Books
- `GET /books`: Display book management page
- `GET /books/index`: Get all books data (DataTable AJAX)
- `POST /books/store`: Create a new book
- `GET /books/{book}/edit`: Get book edit form
- `POST /books/{book}/update`: Update book information
- `DELETE /books/{book}/delete`: Delete a book
- `GET /books/{book}/download`: Download book PDF
- `GET /books/{book}/view`: View book PDF in browser

### Borrowings
- `GET /borrowings`: Display borrowings list and dashboard
- `GET /borrowings/{borrowing}`: Get specific borrowing details
- `PUT /borrowings/{borrowing}`: Update borrowing status (approve/reject/return)
- `POST /borrowing`: Create new borrowing request (authenticated users only)

### User Profile
- `GET /profile`: View user profile
- `PATCH /profile`: Update user profile information
- `DELETE /profile`: Delete user account

### Authentication
- `GET /register`: Display registration form
- `POST /register`: Create a new user account
- `GET /login`: Display login form
- `POST /login`: Authenticate user
- `POST /logout`: Log out user
- `GET /forgot-password`: Display password reset request form
- `POST /forgot-password`: Send password reset link
- `GET /reset-password/{token}`: Display password reset form
- `POST /reset-password`: Reset user password
- `GET /verify-email`: Show email verification notice
- `GET /verify-email/{id}/{hash}`: Verify email address
- `POST /email/verification-notification`: Resend verification email
- `PUT /password`: Update user password

## Development Guidelines

### Adding New Features
1. Create appropriate migrations
2. Define model relationships
3. Implement services for business logic
4. Create controller methods
5. Define routes
6. Create blade templates and JavaScript

### Coding Standards
- Follow PSR-12 coding standards
- Use type hints and return types
- Implement form requests for validation
- Use services for business logic
- Add PHPDoc comments

#### Used PHP Packages
- **Spatie Laravel Permission** (^6.16): Role and permission management
- **Yajra Laravel DataTables** (12.0): Server-side processing for DataTables
- **Laravel Breeze** (^2.3):  Authentication scaffolding

## Contributors

- Osama Ali Abbas 

## Overview Video: https://youtu.be/HLiymJSrucA

![image](https://github.com/user-attachments/assets/f688f2ef-b65d-4946-b7e1-8c3917d53008)


![image](https://github.com/user-attachments/assets/5cdce337-bfbc-4f92-80c7-62a176ecd715)


![image](https://github.com/user-attachments/assets/50c00f93-3a68-4092-8d05-34de964b0fbf)


