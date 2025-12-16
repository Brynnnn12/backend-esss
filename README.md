# Employee Shift Scheduling System

Sistem penjadwalan shift karyawan yang efisien, dengan validasi bentrok jadwal, role-based access, dan logic backend yang kuat. Cocok untuk perusahaan yang membutuhkan manajemen jadwal kerja yang terstruktur.

## 📋 Deskripsi Proyek

Proyek ini dibangun dengan Laravel untuk mengelola jadwal shift karyawan. Fokus utama adalah mencegah bentrok jadwal, kemudahan pengembangan, dan implementasi best practice backend.

### 🎯 Fitur Utama

-   ✅ Validasi bentrok shift per karyawan
-   ✅ Manajemen departemen dan shift
-   ✅ Role-based access (HR/Admin & Employee)
-   ✅ RESTful API dengan response standar
-   ✅ Relasi database yang efisien

## 🧩 ERD – Database Structure

```
Table users {
  id bigint [pk, increment]
  name varchar
  email varchar
  created_at timestamp
}

Table departments {
  id bigint [pk, increment]
  name varchar
}

Table shifts {
  id bigint [pk, increment]
  name varchar          // Pagi, Siang, Malam
  start_time time
  end_time time
}

Table schedules {
  id bigint [pk, increment]
  department_id bigint [ref: > departments.id]
  date date
  created_at timestamp
}

Table schedule_assignments {
  id bigint [pk, increment]
  schedule_id bigint [ref: > schedules.id]
  user_id bigint [ref: > users.id]
  shift_id bigint [ref: > shifts.id]
}
```

### 🔎 Kenapa ERD Ini Bagus?

-   Tidak ribet dan realistis
-   Relasi jelas (1 schedule bisa assign banyak employee)
-   Mudah dikembangkan (lembur, shift swap, approval, report)
-   Fokus pada inti: jadwal kerja tanpa bentrok

## 🔄 Alur Sistem

### 👤 Role & Akses

-   **Admin/HR**: Buat shift, jadwal, assign karyawan, lihat semua data
-   **Employee**: Lihat jadwal shift sendiri

### 🔄 Alur Utama

1. **Admin/HR**:

    - Buat shift (Pagi/Siang/Malam) dengan jam kerja
    - Buat jadwal per tanggal & departemen
    - Assign karyawan ke shift dengan validasi

2. **Sistem**:

    - Validasi: 1 karyawan tidak boleh 2 shift di tanggal sama
    - Validasi: Jam shift tidak bentrok

3. **Employee**:
    - Login dan lihat jadwal shift pribadi

**Kalimat Sakti**: "Sistem mencegah bentrok jadwal dengan validasi backend."

## 🛠️ Logic Backend Inti

### ✅ Validasi Bentrok Shift

-   Jika `user_id + date` sudah ada assignment → Tolak

### ✅ Validasi Jam Shift

-   Shift malam boleh lintas hari (opsional)

### ✅ Query Penting

-   Jadwal per karyawan: `schedule_assignments` join dengan filter `user_id`
-   Jadwal per departemen: Join dengan `schedules` dan `departments`
-   Jadwal harian: Filter berdasarkan `date`

## 🚀 Agile Development – Step by Step

### 🟦 Sprint 0 – Perencanaan (2 Hari)

-   Tentukan fitur inti
-   Buat ERD
-   Setup Laravel + Spatie Permission
-   Git init

### 🟩 Sprint 1 – Auth & Role (3 Hari)

-   Implementasi login/logout
-   Setup role HR & Employee
-   Permission dasar (CRUD berdasarkan role)

### 🟨 Sprint 2 – Master Data (4 Hari)

-   CRUD Department
-   CRUD Shift
-   Seeder dummy data

### 🟧 Sprint 3 – Scheduling Core (5-6 Hari) ⭐

-   Create schedule (tanggal + departemen)
-   Assign employee ke shift dengan validasi bentrok
-   List jadwal dengan pagination
-   API endpoints lengkap

### 🟥 Sprint 4 – View & Report (Opsional)

-   Dashboard jadwal per karyawan
-   Filter jadwal per tanggal/departemen
-   Export CSV/PDF (bonus)

## 🏗️ Setup & Installation

### Prerequisites

-   PHP 8.1+
-   Composer
-   MySQL/PostgreSQL
-   Node.js (untuk frontend jika ada)

### Installation Steps

1. Clone repository:

    ```bash
    git clone <repo-url>
    cd backend-asset
    ```

2. Install dependencies:

    ```bash
    composer install
    npm install
    ```

3. Setup environment:

    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

4. Configure database di `.env`:

    ```
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=shift_scheduling
    DB_USERNAME=your_username
    DB_PASSWORD=your_password
    ```

5. Run migrations & seeders:

    ```bash
    php artisan migrate
    php artisan db:seed
    ```

6. Jalankan server:
    ```bash
    php artisan serve
    ```

### API Endpoints Utama

-   `GET /api/departments` - List departemen
-   `POST /api/departments` - Buat departemen (HR only)
-   `GET /api/shifts` - List shift
-   `POST /api/schedules` - Buat jadwal (HR only)
-   `POST /api/schedules/{id}/assign` - Assign employee ke shift
-   `GET /api/my-schedule` - Jadwal karyawan (Employee only)

## 🧪 Testing

```bash
php artisan test
```

## 📊 Kenapa Project Ini Kuat untuk Fresh Grad D3 SI?

-   ✅ Digunakan di dunia kerja nyata
-   ✅ Logic waktu & relasi database
-   ✅ Tidak pasaran (unik)
-   ✅ Mudah dijelaskan ke dosen/HR
-   ✅ Tidak tergantung UI mewah

## 🎯 Cara Menjual di CV

**Employee Shift Scheduling System (Backend Developer)**

-   Designed scheduling system with conflict validation
-   Implemented role-based access using Spatie Permission
-   Built relational database for shift & department management
-   Developed RESTful API with Laravel

**Level**: Junior Backend Developer

## 📄 License

MIT License
