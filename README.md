# FinTrack for University Student Organizations

A work-in-progress demo web project implementing a QR Attendance and Finance Management System for university student organizations using Laravel 10. It allow officers to seamlessly manage their student organization's financial status.

## Requirements
- PHP Version = 8.1
- MySQL = 8.1.12

## Setup Instructions
### 1. Clone GitHub repo for this project locally.
```
git clone https://github.com/xyberpastoril/FinTrack-Org.git
```

### 2. `cd` into the `FinTrack-Org` project.
```
cd FinTrack-Org
```

### 3. Install Composer Packages required for this project.
```
composer install
```

### 4. Create a copy of `.env` file from `.env.example`. 
The `.env.example` file is already filled with default database information including the name of the database `fintrack_org`.
```
cp .env.example .env
```

### 5. Generate an application encryption key.
```
php artisan key:generate
```

### 6. Create an empty database named `fintrack_org`.
This can be done by opening XAMPP, run Apache and MySQL, then create a database to phpMyAdmin.

### 7. Update `.env` values when necessary (Optional)
Just in case your database server's configuration is different from the default `root` and blank password, or the name of the database, you may reflect those changes to the `.env` file.

### 8. Migrate and seed the database.
```
php artisan migrate --seed
```

### 10. Finally, run the app server.
```
php artisan serve
```
---

### Importing Data

Create a `.csv` file containing the students' data. Its columns are also as follows: `id_number`, `last_name`, `first_name`, `middle_name`, **`degree_program`**, `year_level`. We recommend that the `degree_program` shall contain its abbreviation, as seen below.
| id_number       | last_name     | first_name       | middle_name     | degree_program      | year_level
|--------------|-----------|-----------|-----------|-----------|----------|
| 12-3-45678 | Dela Cruz     | Juan | Alejandro | **BSCS** | 4 |

The system will automatically check whether the student exists, then add if not in the list. The same applies for degree programs.

### QR Scanning
The QR Scanning functionality requires `https`. It is possible to enable using ngrok, but you may need to add a temporary line at the end of `routes/web.php`:
```
URL::forceScheme('https');
```

It is also possible to enable offline, for the case of Ubuntu. Unfortunately, I haven't explored how to access it from other PCs.
```
sudo php artisan serve --port=443
```
