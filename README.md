# MediCare - Doctor Appointment Booking System 🏥

A modern, responsive, single-page **Doctor Appointment Booking System** built with **HTML5, CSS3 (Glassmorphism), JavaScript (Fetch API), PHP, and MySQL**, designed for running locally on **XAMPP**.

---

## ✨ Features

- **Modern Glassmorphism UI:** Frosted-glass aesthetic using `backdrop-filter: blur(16px)`, translucent layers, glowing ambient depth orbs, and medical cyan/blue accents.
- **Dynamic Doctor Profiles:** Fetches doctor details, specializations, profile photos, and consultation fees directly from MySQL.
- **Interactive Card-to-Form Sync:** Clicking **"Select & Book"** on any doctor profile card pre-selects that doctor in the booking form, updates the fee, and scrolls smoothly to the booking section.
- **Dynamic Fee Calculation:** Displays the selected doctor's consultation fee in Indian Rupees (`₹`) in real time.
- **Past Date Restriction:** Disallows booking for past dates on both client-side datepicker and server-side validation.
- **No-Reload AJAX Booking:** Submits appointment requests asynchronously via JavaScript's `fetch()` API with loading state feedback.
- **Instant Booking Confirmation:** Displays an animated frosted-glass modal receipt with the unique booking reference token (`#APT-000X`), patient details, consulting doctor, scheduled slot, and a **Print Receipt** option.
- **Secure Backend:** PHP backend using MySQLi Prepared Statements (`bind_param`) to protect against SQL injection vulnerabilities.

---

## 🛠️ Technology Stack

- **Frontend:** HTML5, CSS3 (Vanilla Glassmorphism, CSS Grid & Flexbox), JavaScript (ES6+ Fetch API)
- **Backend:** PHP 7.4+ / 8.x
- **Database:** MySQL / MariaDB
- **Server Environment:** XAMPP (Apache + MySQL)

---

## 📂 Project Structure

```text
├── database.sql           # MySQL database schema, tables, and sample doctor records
├── db.php                 # Database connection handler with UTF-8 encoding & diagnostics
├── index.php              # Complete single-page application structure
├── style.css              # Custom Glassmorphism styles, glowing orbs & responsive design
├── script.js              # Client-side validation, fee calculation, AJAX booking & modal logic
├── save_appointment.php   # Secure backend appointment processor & JSON API endpoint
└── README.md              # Project documentation
```

---

## 🚀 Installation & Setup Guide

### 1. Prerequisites
- Install [XAMPP](https://www.apachefriends.org/) on your computer.

### 2. Project Directory
Ensure this repository is placed inside your XAMPP web root directory:
```
C:\xampp\htdocs\Capstone Project\
```

### 3. Start XAMPP Services
1. Open the **XAMPP Control Panel**.
2. Click **Start** for **Apache**.
3. Click **Start** for **MySQL**.

### 4. Import the Database
1. Open your browser and go to `http://localhost/phpmyadmin/`.
2. Click the **Import** tab at the top.
3. Choose the `database.sql` file from this project folder.
4. Click **Import** (or **Go**).
5. The `doctor_appointment` database with `doctors` and `appointments` tables will be created automatically.

### 5. Launch the Application
Open your browser and navigate to:
```
http://localhost/Capstone%20Project/
```

---

## 📋 Database Schema

### `doctors` Table
| Column | Type | Description |
| :--- | :--- | :--- |
| `id` | INT (PK, Auto Increment) | Doctor ID |
| `name` | VARCHAR(100) | Doctor's full name |
| `specialization` | VARCHAR(100) | Medical specialty |
| `consultation_fee` | DECIMAL(10, 2) | Consultation fee in INR |
| `image` | VARCHAR(255) | Profile image URL |
| `created_at` | TIMESTAMP | Registration timestamp |

### `appointments` Table
| Column | Type | Description |
| :--- | :--- | :--- |
| `id` | INT (PK, Auto Increment) | Appointment reference ID |
| `patient_name` | VARCHAR(100) | Patient's full name |
| `phone` | VARCHAR(20) | Patient contact phone |
| `doctor_id` | INT (FK -> doctors.id) | Consulting doctor ID |
| `appointment_date` | DATE | Scheduled date |
| `appointment_time` | VARCHAR(30) | Scheduled time slot |
| `booking_timestamp` | TIMESTAMP | Booking timestamp |

---

## 📄 License
This project was developed for educational and academic purposes. Feel free to use and modify it.
