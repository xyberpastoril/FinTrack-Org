# [Playground] - QR Attendance Management System

This is my own implementation of the QR Attendance System using Laravel 10. Inspired from `vsuqrams` by R. R.

P.S. This is a fast-paced implementation, not all features are implemented.

## Requirements
- PHP Version = 8.1
- MySQL = 8.1.12

## Setup Instructions
### 1. Clone GitHub repo for this project locally.
```
git clone https://github.com/xyberpastoril/playground-qrams.git
```

### 2. `cd` into the `playground-qrams` project.
```
cd playground-qrams
```

### 3. Install Composer Packages required for this project.
```
composer install
```

### 4. Create a copy of `.env` file from `.env.example`. 
The `.env.example` file is already filled with default database information including the name of the database `helpinghand`.
```
cp .env.example .env
```

### 5. Generate an application encryption key.
```
php artisan key:generate
```

### 6. Create an empty database named `playground-qrams`.
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

Create a `.csv` file containing the degree programs' data. Its columns are as follows: `name`, and `abbr`.
Example:
| name         | abbr     |
|--------------|-----------|
| Bachelor of Science in Computer Science | BSCS     |

Create a `.csv` file containing the students' data. Its columns are also as follows: `id_number`, `last_name`, `first_name`, **`degree_program_id`**, `year_level`. The `degree_program_id` is an index starting from `1` based on the `.csv` file created from earlier.
For example, the **`BSCS`** entry we created earlier is index **`1`**. Hence,
| id_number       | last_name     | first_name       | degree_program_id      | year_level
|--------------|-----------|-----------|-----------|-----------|
| 12-3-45678 | Pastoril     | Xyber | **1** | 4 |

### QR Scanning
The QR Scanning functionality requires `https`. It is possible to enable using ngrok, but you need to add a temporary line at the end of `routes/web.php`:
```
URL::forceScheme('https');
```
It is also possible to enable offline, but it wasn't tested yet if it can be shared across different PCs. (For Ubuntu)
```
sudo php artisan serve --port=443
```
