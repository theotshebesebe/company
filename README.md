# company
Basic staff management system (CRUD)
# Staff Records Management System

## Description
This is a simple PHP-based web application for managing staff records. It allows Admin users to create, read, update, and delete staff profiles, while Staff users can view and update their own profiles. The application uses MySQL for data storage and Bootstrap for styling.

## Features
- **Admin Role**:
  - Manage all staff records (CRUD operations).
  - Add new staff members.
- **Staff Role**:
  - View and update their own profile.
- **Authentication**:
  - Login system with role-based access control.
- **Security**:
  - Passwords are hashed using `password_hash`.
  - SQL queries use prepared statements to prevent SQL injection.

## File Structure
```
├── add_staff.php       # Add new staff (Admin only)
├── Database.php        # Database connection class
├── db.php              # Legacy database connection file
├── delete_staff.php    # Delete staff (Admin only)
├── edit_staff.php      # Edit staff details
├── index.php           # Dashboard
├── login.php           # Login page
├── logout.php          # Logout logic
├── profile.php         # Staff profile management
├── staff_list.php      # List all staff (Admin only)
├── User.php            # User authentication class
└── includes/
    ├── footer.php      # Shared footer
    └── header.php      # Shared header
```

## Requirements
- PHP 7.4 or higher
- MySQL
- XAMPP or any local server environment
- Web browser

## Installation
1. Clone the repository or copy the project files to your server directory.
2. Import the `company` database using the provided SQL.
3. Update the database credentials in `Database.php`.
4. Start your server and access the application in your browser.

## Usage
1. **Admin Login**:
   - Use the admin credentials to log in and manage staff records.
2. **Staff Login**:
   - Use staff credentials to log in and manage your profile.

## Notes
- Ensure the `Database.php` file is configured with the correct database credentials.
- I used a temporary script (called `add_admin.php`) to add the admin because I was getting an error while creating an admin.
- Please remove the script after use for security.

