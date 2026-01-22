# Dokumentasi Lengkap Sistem Informasi Akademik (SIA)

**Dibuat:** 22 Januari 2026  
**Versi:** 1.0  
**Status:** Production

---

## Daftar Isi

1. [Struktur Proyek](#struktur-proyek)
2. [Database](#database)
3. [Fitur Utama](#fitur-utama)
4. [Dokumentasi File](#dokumentasi-file)
5. [Alur Sistem](#alur-sistem)
6. [Cara Menggunakan](#cara-menggunakan)

---

## Struktur Proyek

```
workshop_aslab/
├── index.php                    # Login page
├── koneksi.php                  # Database connection
├── debug_login.php              # Debug helper untuk login
├── DOKUMENTASI_LENGKAP.md       # File dokumentasi ini
├── LAPORAN_PRAKTIKUM.md         # Template laporan praktikum
│
├── admin/                        # Admin panel
│   ├── index.php                # Dashboard admin
│   ├── mahasiswa.php            # CRUD mahasiswa
│   ├── dosen.php                # CRUD dosen
│   ├── matkul.php               # CRUD mata kuliah
│   ├── nilai.php                # CRUD nilai
│   ├── logout.php               # Logout admin
│   └── user.php                 # (Optional) User management
│
├── mahasiswa/                    # Mahasiswa panel
│   ├── index.php                # Dashboard mahasiswa
│   └── logout.php               # Logout mahasiswa
│
├── dosen/                        # Dosen panel
│   ├── index.php                # Dashboard dosen
│   └── logout.php               # Logout dosen
│
└── bootstrap-5.3.8-dist/        # Bootstrap CSS/JS
```

---

## Database

### Nama Database: `workshop`

#### 1. Tabel `user`

**Fungsi:** Menyimpan akun login untuk semua role

| Kolom    | Tipe                                | Keterangan          |
| -------- | ----------------------------------- | ------------------- |
| id       | INT PRIMARY KEY AUTO_INCREMENT      | ID unik             |
| username | VARCHAR(50) UNIQUE                  | Username login      |
| password | VARCHAR(255)                        | Password (MD5 hash) |
| level    | ENUM('admin', 'dosen', 'mahasiswa') | Role pengguna       |

**Auto-generated:**

- Saat menambah mahasiswa: username = 4 digit pertama NIM, password = MD5(NIM lengkap)
- Saat menambah dosen: username = 4 digit pertama NIDN, password = MD5(NIDN lengkap)

#### 2. Tabel `mahasiswa`

**Fungsi:** Data mahasiswa

| Kolom    | Tipe                    | Keterangan            |
| -------- | ----------------------- | --------------------- |
| nim      | VARCHAR(12) PRIMARY KEY | Nomor Induk Mahasiswa |
| nama     | VARCHAR(100)            | Nama mahasiswa        |
| prodi    | VARCHAR(50)             | Program studi         |
| angkatan | YEAR                    | Tahun masuk           |

#### 3. Tabel `dosen`

**Fungsi:** Data dosen/pengajar

| Kolom | Tipe                    | Keterangan                 |
| ----- | ----------------------- | -------------------------- |
| nidn  | VARCHAR(20) PRIMARY KEY | Nomor Induk Dosen Nasional |
| nama  | VARCHAR(100)            | Nama dosen                 |

#### 4. Tabel `matkul`

**Fungsi:** Data mata kuliah

| Kolom       | Tipe                    | Keterangan             |
| ----------- | ----------------------- | ---------------------- |
| kode_matkul | VARCHAR(10) PRIMARY KEY | Kode mata kuliah       |
| nama_matkul | VARCHAR(100)            | Nama mata kuliah       |
| sks         | INT                     | Satuan Kredit Semester |

#### 5. Tabel `nilai`

**Fungsi:** Nilai mahasiswa per mata kuliah

| Kolom       | Tipe                           | Keterangan                   |
| ----------- | ------------------------------ | ---------------------------- |
| id          | INT PRIMARY KEY AUTO_INCREMENT | ID unik                      |
| nim         | VARCHAR(12) FK                 | Referensi ke tabel mahasiswa |
| kode_matkul | VARCHAR(10) FK                 | Referensi ke tabel matkul    |
| nilai       | CHAR(2)                        | Nilai (A, B, C, D, E)        |

**Foreign Keys:**

- `nilai.nim` → `mahasiswa.nim` (CASCADE DELETE)
- `nilai.kode_matkul` → `matkul.kode_matkul` (CASCADE DELETE)

---

## Fitur Utama

### 1. **Authentication (Login)**

- **File:** `index.php`
- **Fitur:** Validasi username/password, auto-redirect berdasarkan role
- **Security:** MD5 password hashing, SQL injection prevention (mysqli_real_escape_string)

### 2. **Admin Dashboard**

- **File:** `admin/index.php`
- **Fitur:**
  - Statistik data (mahasiswa, dosen, matkul, nilai)
  - Quick access ke CRUD pages
  - Info sistem

### 3. **CRUD Mahasiswa**

- **File:** `admin/mahasiswa.php`
- **Operasi:**
  - **Create:** Tambah mahasiswa (auto-create user untuk login)
  - **Read:** Tampilkan list mahasiswa
  - **Update:** Edit data mahasiswa
  - **Delete:** Hapus mahasiswa (auto-delete user)

### 4. **CRUD Dosen**

- **File:** `admin/dosen.php`
- **Operasi:**
  - **Create:** Tambah dosen (auto-create user untuk login)
  - **Read:** Tampilkan list dosen
  - **Update:** Edit data dosen
  - **Delete:** Hapus dosen (auto-delete user)

### 5. **CRUD Mata Kuliah**

- **File:** `admin/matkul.php`
- **Operasi:**
  - **Create:** Tambah mata kuliah
  - **Read:** Tampilkan list mata kuliah
  - **Update:** Edit data mata kuliah
  - **Delete:** Hapus mata kuliah

### 6. **CRUD Nilai**

- **File:** `admin/nilai.php`
- **Operasi:**
  - **Create:** Input nilai dengan dropdown mahasiswa & mata kuliah
  - **Read:** Tampilkan list nilai dengan join table
  - **Update:** Edit nilai
  - **Delete:** Hapus nilai

### 7. **Role-based Dashboards**

- **Mahasiswa:** `mahasiswa/index.php` - Lihat statistik nilai & mata kuliah
- **Dosen:** `dosen/index.php` - Lihat statistik mahasiswa & nilai
- **Admin:** `admin/index.php` - Lihat statistik lengkap semua data

---

## Dokumentasi File

### `index.php` - Login Page

```php
/**
 * LOGIN PAGE - ENTRY POINT APLIKASI
 *
 * Fitur:
 * - Validasi username & password
 * - Auto-redirect berdasarkan role
 * - Session management
 *
 * Security:
 * - MD5 password hashing
 * - SQL injection prevention
 *
 * Alur:
 * 1. User masukkan username & password
 * 2. Query ke tabel user
 * 3. Cek password cocok
 * 4. Redirect ke dashboard sesuai role
 *    - admin → admin/index.php
 *    - dosen → dosen/index.php
 *    - mahasiswa → mahasiswa/index.php
 */
```

### `admin/mahasiswa.php` - CRUD Mahasiswa

```php
/**
 * CRUD MAHASISWA
 *
 * Fitur:
 * - Tambah mahasiswa (auto-create user)
 * - Edit mahasiswa
 * - Hapus mahasiswa (auto-delete user)
 * - Validasi duplicate NIM
 *
 * Auto-user Generation:
 * - username = substr(NIM, 0, 4)
 * - password = md5(NIM)
 * - level = 'mahasiswa'
 *
 * Cascade Delete:
 * - Hapus mahasiswa → Hapus user
 * - Hapus mahasiswa → Hapus nilai (via FK)
 *
 * Modal:
 * - Tambah: Form input NIM, Nama, Prodi, Angkatan
 * - Edit: Edit data mahasiswa
 */
```

### `admin/dosen.php` - CRUD Dosen

```php
/**
 * CRUD DOSEN
 *
 * Fitur:
 * - Tambah dosen (auto-create user)
 * - Edit dosen
 * - Hapus dosen (auto-delete user)
 * - Validasi duplicate NIDN
 *
 * Auto-user Generation:
 * - username = substr(NIDN, 0, 4)
 * - password = md5(NIDN)
 * - level = 'dosen'
 *
 * Cascade Delete:
 * - Hapus dosen → Hapus user
 *
 * Modal:
 * - Tambah: Form input NIDN, Nama
 * - Edit: Edit data dosen
 */
```

### `admin/matkul.php` - CRUD Mata Kuliah

```php
/**
 * CRUD MATA KULIAH
 *
 * Fitur:
 * - Tambah mata kuliah
 * - Edit mata kuliah
 * - Hapus mata kuliah
 * - Validasi duplicate kode_matkul
 *
 * Modal:
 * - Tambah: Form input Kode, Nama, SKS
 * - Edit: Edit data mata kuliah
 */
```

### `admin/nilai.php` - CRUD Nilai

```php
/**
 * CRUD NILAI (COMPLEX)
 *
 * Fitur:
 * - Input nilai dengan relasi mahasiswa & matkul
 * - Dropdown mahasiswa (SELECT * FROM mahasiswa)
 * - Dropdown mata kuliah (SELECT * FROM matkul)
 * - Tampil nilai dengan JOIN table
 *
 * Query Utama:
 * SELECT nilai.id, mahasiswa.nim, mahasiswa.nama,
 *        matkul.kode_matkul, matkul.nama_matkul, nilai.nilai
 * FROM nilai
 * JOIN mahasiswa ON nilai.nim = mahasiswa.nim
 * JOIN matkul ON nilai.kode_matkul = matkul.kode_matkul
 *
 * Modal:
 * - Tambah: Pilih mahasiswa + matkul, input nilai
 * - Edit: Edit nilai
 */
```

### `koneksi.php` - Database Connection

```php
/**
 * DATABASE CONNECTION
 *
 * Config:
 * - Host: localhost
 * - User: root
 * - Password: (kosong)
 * - Database: workshop
 *
 * Error Handling:
 * - Die dengan error message jika gagal koneksi
 */
```

---

## Alur Sistem

### 1. Alur Login

```
User Input (username, password)
    ↓
Query: SELECT * FROM user WHERE username='$u' AND password=MD5('$p')
    ↓
Cek hasil query
    ├─ Ada → Set $_SESSION['username'] & $_SESSION['level']
    │         ├─ admin → Redirect admin/index.php
    │         ├─ dosen → Redirect dosen/index.php
    │         └─ mahasiswa → Redirect mahasiswa/index.php
    │
    └─ Tidak ada → Tampil "Username atau Password salah!"
```

### 2. Alur Tambah Mahasiswa

```
Admin buka admin/mahasiswa.php
    ↓
Admin klik "Tambah Mahasiswa"
    ↓
Admin isi form (NIM, Nama, Prodi, Angkatan)
    ↓
Klik "Simpan"
    ↓
INSERT INTO mahasiswa VALUES (...)
    ↓
INSERT INTO user VALUES (
    username = substr(NIM, 0, 4),
    password = md5(NIM),
    level = 'mahasiswa'
)
    ↓
Mahasiswa bisa login dengan username 4-digit & password NIM lengkap
```

### 3. Alur Input Nilai

```
Admin buka admin/nilai.php
    ↓
Admin klik "Tambah Nilai"
    ↓
Dropdown Mahasiswa + Dropdown Matkul + Input Nilai
    ↓
Klik "Simpan"
    ↓
INSERT INTO nilai (nim, kode_matkul, nilai) VALUES (...)
    ↓
Tampil di tabel dengan JOIN table (nama mahasiswa, nama matkul)
```

---

## Cara Menggunakan

### 1. Setup Database

```sql
-- Buat database
CREATE DATABASE workshop;

-- Import tabel (lihat di struktur tabel di atas)
-- Buat minimal 1 user admin untuk login awal
INSERT INTO user (username, password, level)
VALUES ('admin', MD5('admin123'), 'admin');
```

### 2. Login Awal

- **URL:** `http://localhost/workshop_aslab/`
- **Username:** admin
- **Password:** admin123

### 3. Tambah Master Data

- **Admin → Mata Kuliah:** Tambah minimal 3 mata kuliah
- **Admin → Mahasiswa:** Tambah minimal 5 mahasiswa
  - Username otomatis = 4 digit pertama NIM
  - Password otomatis = MD5 dari NIM lengkap
  - Contoh: NIM=12345 → username=1234, password=md5('12345')
- **Admin → Dosen:** Tambah minimal 3 dosen

### 4. Input Nilai

- **Admin → Nilai:** Pilih mahasiswa + matkul, input nilai (A-E)

### 5. Test Login sebagai Mahasiswa/Dosen

- Gunakan username 4-digit yang sudah auto-generate
- Password = NIM/NIDN lengkap

---

## Keamanan

1. **Password Hashing:** MD5 (sudah di-hash, tidak plain text)
2. **SQL Injection Prevention:** `mysqli_real_escape_string()`
3. **Session Management:** Check `$_SESSION` di setiap halaman protected
4. **Role-based Access:** Redirect jika role tidak sesuai
5. **Error Handling:** Query error dengan `mysqli_error()`

---

---

## Catatan Penting

- Password menggunakan MD5 (gunakan bcrypt untuk production)
- Tidak ada validasi email
- Tidak ada reset password
- Tidak ada audit trail
- Support multi-role (admin, dosen, mahasiswa)
- Auto-user generation saat tambah mahasiswa/dosen
- Cascade delete untuk menjaga integritas data

---

**Dibuat dengan sepenuh hati**
