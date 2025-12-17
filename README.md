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
  password varchar
  created_at timestamp
  updated_at timestamp
}

Table departments {
  id bigint [pk, increment]
  name varchar [unique]
  created_at timestamp
  updated_at timestamp
}

Table shifts {
  id varchar [pk]  // UUID
  name varchar [unique]
  start_time time
  end_time time
  created_at timestamp
  updated_at timestamp
}

Table schedules {
  id bigint [pk, increment]
  department_id bigint [ref: > departments.id]
  date date
  created_at timestamp
  updated_at timestamp
}

Table schedule_assignments {
  id bigint [pk, increment]
  schedule_id bigint [ref: > schedules.id]
  user_id bigint [ref: > users.id]
  shift_id varchar [ref: > shifts.id]
  created_at timestamp
  updated_at timestamp
}
```

### 🔎 Kenapa ERD Ini Bagus?

-   **Tidak ribet dan realistis**: Fokus pada entitas utama tanpa over-engineering.
-   **Relasi jelas**: 1 schedule bisa assign banyak employee, 1 employee bisa assign ke banyak schedule (dengan validasi bentrok).
-   **Mudah dikembangkan**: Bisa tambah fitur lembur, shift swap, approval, atau report tanpa ubah struktur utama.
-   **Fokus pada inti**: Prioritas mencegah bentrok jadwal shift.

## 🔄 Alur Sistem

### 👤 Role & Akses

-   **Admin/HR**: 
    - Akses penuh ke semua fitur: buat/edit/hapus department, shift, schedule, assign karyawan.
    - Lihat semua data jadwal.
-   **Employee**: 
    - Hanya bisa lihat jadwal shift pribadi.
    - Tidak bisa akses master data (department, shift).

### 🔄 Alur Utama

1. **Setup Master Data (HR)**:
    - Buat department (e.g., IT, HR, Finance).
    - Buat shift (e.g., Pagi 08:00-16:00, Siang 14:00-22:00).

2. **Buat Jadwal (HR)**:
    - Pilih tanggal dan department.
    - Sistem buat schedule entry.

3. **Assign Karyawan (HR)**:
    - Pilih employee dari department tersebut.
    - Assign ke shift dengan validasi:
        - Tidak bentrok jam shift.
        - Tidak assign 2 shift di tanggal sama.

4. **Validasi Sistem**:
    - Backend cegah duplikasi assignment per user per date.
    - Jika bentrok, return error 422.

5. **Employee Lihat Jadwal**:
    - Login dan akses endpoint jadwal pribadi.
    - Filter berdasarkan tanggal/range.

**Kalimat Sakti**: "Sistem mencegah bentrok jadwal dengan validasi backend yang ketat."

## 🛠️ Logic Backend Inti

### ✅ Validasi Bentrok Shift

-   **Rule 1**: Satu karyawan tidak boleh assign 2 shift di tanggal yang sama.
    - Query: `SELECT * FROM schedule_assignments WHERE user_id = ? AND schedule_id IN (SELECT id FROM schedules WHERE date = ?)`
    - Jika ada, tolak dengan error "Employee already assigned on this date".

-   **Rule 2**: Jam shift tidak bentrok (opsional, tergantung bisnis).
    - Jika shift A end_time > shift B start_time, tolak.

### ✅ Query Penting

-   **Jadwal per karyawan**: 
    ```sql
    SELECT sa.*, s.date, sh.name as shift_name, d.name as department_name
    FROM schedule_assignments sa
    JOIN schedules s ON sa.schedule_id = s.id
    JOIN shifts sh ON sa.shift_id = sh.id
    JOIN departments d ON s.department_id = d.id
    WHERE sa.user_id = ?
    ORDER BY s.date DESC
    ```

-   **Jadwal per departemen**: 
    ```sql
    SELECT sa.*, u.name as employee_name, sh.name as shift_name
    FROM schedule_assignments sa
    JOIN schedules s ON sa.schedule_id = s.id
    JOIN users u ON sa.user_id = u.id
    JOIN shifts sh ON sa.shift_id = sh.id
    WHERE s.department_id = ? AND s.date = ?
    ```

-   **Jadwal harian**: Filter berdasarkan `date` di table `schedules`.

## 🚀 Agile Development – Step by Step

### 🟦 Sprint 0 – Perencanaan (2 Hari) ✅

-   Tentukan fitur inti: shift scheduling dengan validasi bentrok.
-   Buat ERD sederhana tapi scalable.
-   Setup Laravel 11 + Spatie Permission untuk role-based access.
-   Git init dan commit awal.

### 🟩 Sprint 1 – Auth & Role (3 Hari) ✅

-   **Implementasi Auth**: Login/logout dengan Sanctum token.
-   **Setup Role**: Gunakan Spatie Permission untuk role 'Hr' dan 'Employee'.
-   **Permission Dasar**: HR bisa CRUD semua, Employee read-only jadwal sendiri.
-   **Testing**: Buat test login dan role assignment.

### 🟨 Sprint 2 – Master Data (4 Hari) ✅

-   **Department CRUD**:
    - Model: `Department` dengan fillable name.
    - Policy: `DepartmentPolicy` – HR bisa viewAny/create/update/delete, Employee tidak.
    - Controller: `DepartmentController` dengan authorize di setiap method.
    - Routes: `Route::apiResource('departments', DepartmentController::class)` di middleware auth:sanctum.
    - Factory: `DepartmentFactory` untuk seeding dummy data.
    - Seeder: `DepartmentSeeder` buat 3 department.

-   **Shift CRUD**:
    - Model: `Shift` dengan UUID id, unique name.
    - Factory: `ShiftFactory` dengan faker unique word untuk name, time untuk start/end.
    - Seeder: `ShiftSeeder` buat 10 shift dummy.
    - Policy & Controller: Belum diimplementasi (akan di Sprint 3).

-   **Testing dengan Pest**:
    - Test Department: HR bisa get list (200), Employee 403.
    - Test Shift: Belum ada, akan ditambah.
    - Jalankan: `php artisan test` – semua pass.

### 🟧 Sprint 3 – Scheduling Core (5-6 Hari) 🔄 (Sedang Dikerjakan)

-   Buat model Schedule dan ScheduleAssignment.
-   CRUD Schedule: Create schedule per date+dept, assign employee ke shift.
-   Validasi bentrok di backend.
-   API endpoints lengkap dengan pagination.
-   Testing: Unit test validasi, feature test assign.

### 🟥 Sprint 4 – View & Report (Opsional)

-   Dashboard jadwal per karyawan.
-   Filter jadwal per tanggal/departemen.
-   Export CSV/PDF (bonus).

## 🏗️ Setup & Installation

### Prerequisites

-   PHP 8.1+ (Laravel 11 requirement)
-   Composer
-   MySQL/PostgreSQL
-   Node.js (jika ada frontend)
-   Git

### Installation Steps

1. **Clone & Install**:
    ```bash
    git clone <repo-url>
    cd backend-asset
    composer install
    npm install  # Jika ada frontend
    ```

2. **Environment Setup**:
    ```bash
    cp .env.example .env
    php artisan key:generate
    # Edit .env untuk DB connection
    ```

3. **Database**:
    ```bash
    php artisan migrate
    php artisan db:seed  # Buat dummy data HR, Employee, Departments, Shifts
    ```

4. **Run Server**:
    ```bash
    php artisan serve  # Akses di http://localhost:8000
    ```

### API Endpoints Utama

-   **Auth**:
    - `POST /api/v1/login` - Login (return token)
    - `GET /api/v1/me` - Get current user

-   **Departments (HR Only)**:
    - `GET /api/v1/departments` - List all
    - `POST /api/v1/departments` - Create
    - `GET /api/v1/departments/{id}` - Show
    - `PUT /api/v1/departments/{id}` - Update
    - `DELETE /api/v1/departments/{id}` - Delete

-   **Shifts (HR Only, Belum Implementasi Penuh)**:
    - `GET /api/v1/shifts` - List all (akan ditambah)
    - `POST /api/v1/shifts` - Create (akan ditambah)

-   **Schedules (HR Only, Belum Implementasi)**:
    - `POST /api/v1/schedules` - Create schedule
    - `POST /api/v1/schedules/{id}/assign` - Assign employee

-   **Employee**:
    - `GET /api/v1/my-schedule` - Jadwal pribadi (akan ditambah)

**Response Format**: Semua API return JSON standar dengan `success`, `message`, `data`.

## 🧪 Testing

-   **Framework**: Pest (lebih readable dari PHPUnit).
-   **Jalankan Test**:
    ```bash
    php artisan test  # Semua test
    php artisan test --filter=DepartmentTest  # Test spesifik
    ```
-   **Coverage**: Test auth, role, CRUD department.
-   **Manual Test**: Gunakan Thunder Client dengan JSON collection untuk test API.

## 📊 Kenapa Project Ini Kuat untuk Fresh Grad D3 SI?

-   ✅ **Real-World Usage**: Sistem scheduling digunakan di perusahaan nyata.
-   ✅ **Logic Kompleks**: Validasi waktu, relasi database, role-based.
-   ✅ **Unik**: Tidak pasaran seperti todo app.
-   ✅ **Mudah Dijelaskan**: Bisa presentasi ke dosen/HR dengan ERD dan alur.
-   ✅ **Scalable**: Bisa tambah fitur tanpa rewrite.

## 🎯 Cara Menjual di CV

**Employee Shift Scheduling System (Backend Developer)**

-   Designed and implemented shift scheduling system with conflict prevention logic.
-   Implemented role-based access control using Laravel Policies and Spatie Permission.
-   Built relational database schema for departments, shifts, and assignments.
-   Developed RESTful API with standardized responses and comprehensive testing.
-   Ensured data integrity with unique constraints and backend validations.

**Tech Stack**: Laravel 11, MySQL, Pest, Sanctum.

**Level**: Junior Backend Developer.

## 📄 License

MIT License
