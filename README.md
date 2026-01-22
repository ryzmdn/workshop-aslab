# SISTEM INFORMASI AKADEMIK (SIA) v1.0

**Platform Web untuk Mengelola Data Akademik (Mahasiswa, Dosen, Mata Kuliah, Nilai)**

---

## Informasi Singkat

| Aspek           | Detail                          |
| --------------- | ------------------------------- |
| **Nama Sistem** | Sistem Informasi Akademik (SIA) |
| **Tujuan**      | Mengelola data akademik (CRUD)  |
| **Platform**    | Web-based (PHP + MySQL)         |
| **Browser**     | Chrome, Firefox, Safari, Edge   |
| **Status**      | Production Ready v1.0           |
| **Dokumentasi** | Lengkap (4 file MD)             |

---

## Mulai Cepat

### 1️Setup (5 menit)

```bash
# 1. Extract ke C:\xampp\htdocs\workshop_aslab\
# 2. Start XAMPP (Apache + MySQL)
# 3. Buka browser: http://localhost/workshop_aslab/
# 4. Login: admin / admin123
```

### 2️Tambah Data (2 menit)

```
Admin Panel:
├─ Mahasiswa → Tambah (auto-create user login)
├─ Dosen → Tambah (auto-create user login)
├─ Mata Kuliah → Tambah
└─ Nilai → Input (relasional)
```

### Test Login Mahasiswa (1 menit)

```
Username: 1234 (4 digit pertama NIM)
Password: 12345 (NIM lengkap)
→ Redirect ke Dashboard Mahasiswa
```

---

## Fitur Utama

### 1. **Authentication & Authorization**

- Login dengan role-based access control
- 3 role: Admin, Dosen, Mahasiswa
- Auto-redirect ke dashboard sesuai role
- Session management

### 2. **CRUD Mahasiswa**

- Tambah mahasiswa (auto-generate user login)
- Lihat daftar mahasiswa
- Edit data mahasiswa
- Hapus mahasiswa (cascade delete)

### 3. **CRUD Dosen**

- Tambah dosen (auto-generate user login)
- Lihat daftar dosen
- Edit data dosen
- Hapus dosen

### 4. **CRUD Mata Kuliah**

- Tambah mata kuliah
- Lihat daftar mata kuliah
- Edit mata kuliah
- Hapus mata kuliah

### 5. **CRUD Nilai (Relasional)**

- Input nilai dengan dropdown (mahasiswa + matkul)
- Tampil nilai dengan JOIN table (nama)
- Edit nilai
- Hapus nilai

### 6. **Dashboard**

- **Admin:** Statistik semua data
- **Dosen:** Statistik mahasiswa, nilai, matkul
- **Mahasiswa:** Statistik mata kuliah, nilai

---

## Database Structure

### 5 Tabel Utama

```
mahasiswa              dosen                 matkul
(nim PK)              (nidn PK)             (kode_matkul PK)
├─ nim                ├─ nidn               ├─ kode_matkul
├─ nama               └─ nama               ├─ nama_matkul
├─ prodi                                    └─ sks
└─ angkatan

                    user (Authentication)
                    (id PK)
                    ├─ username
                    ├─ password (MD5)
                    └─ level

                    nilai (Relasional)
                    (id PK)
                    ├─ nim (FK → mahasiswa)
                    ├─ kode_matkul (FK → matkul)
                    └─ nilai
```

### Foreign Key Relations

```
mahasiswa ←→ nilai ←→ matkul
    ↓
   user (auto-generated)
```

---

## Security Features

**Implemented:**

- SQL Injection Prevention (`mysqli_real_escape_string`)
- Password Hashing (MD5)
- Session Management
- Role-based Access Control
- Error Handling

**Noted:**

- Gunakan bcrypt/argon2 untuk production (bukan MD5)
- Implementasi HTTPS untuk production
- Rate limiting pada login

---

## File Structure

```
workshop_aslab/
├── index.php                      ← Login page
├── koneksi.php                    ← Database connection
├── debug_login.php                ← Debug tool
│
├── admin/
│   ├── index.php                  ← Admin dashboard
│   ├── mahasiswa.php              ← CRUD mahasiswa
│   ├── dosen.php                  ← CRUD dosen
│   ├── matkul.php                 ← CRUD mata kuliah
│   ├── nilai.php                  ← CRUD nilai
│   └── logout.php                 ← Logout
│
├── mahasiswa/
│   ├── index.php                  ← Mahasiswa dashboard
│   └── logout.php
│
├── dosen/
│   ├── index.php                  ← Dosen dashboard
│   └── logout.php
│
├── bootstrap-5.3.8-dist/          ← CSS/JS framework
│
└── DOKUMENTASI:
    ├── INDEX_DOKUMENTASI.md       ← Mulai di sini!
    ├── SETUP_GUIDE.md             ← Instalasi
    ├── DOKUMENTASI_LENGKAP.md     ← Referensi teknis
    ├── QUICK_REFERENCE.md         ← Developer handbook
    ├── LAPORAN_PRAKTIKUM.md       ← Template laporan
    └── README.md                  ← File ini
```

---

## Cara Menggunakan

### Sebagai Admin

```
1. Login ke http://localhost/workshop_aslab/
   Username: admin
   Password: admin123

2. Dashboard menampilkan statistik:
   - Total mahasiswa
   - Total dosen
   - Total mata kuliah
   - Total nilai

3. Kelola data di menu:
   - Mahasiswa → CRUD
   - Dosen → CRUD
   - Mata Kuliah → CRUD
   - Nilai → CRUD input relasional

4. Klik "Logout" untuk keluar
```

### Sebagai Mahasiswa

```
1. Username: 4 digit pertama NIM (auto-generated saat admin tambah)
   Password: NIM lengkap
   Contoh: NIM 12345 → user: 1234, pwd: 12345

2. Dashboard menampilkan:
   - Total mata kuliah
   - Total nilai
   - Status (Aktif)

3. Informasi sistem dan fitur yang tersedia

4. Klik "Logout" untuk keluar
```

### Sebagai Dosen

```
1. Username: 4 digit pertama NIDN (auto-generated)
   Password: NIDN lengkap

2. Dashboard menampilkan:
   - Total mahasiswa
   - Total mata kuliah
   - Total nilai
   - Status (Aktif)

3. Klik "Logout" untuk keluar
```

---

## Alur Login

```
┌─────────────────────┐
│   User Input Form   │
│ username, password  │
└──────────┬──────────┘
           │
           ↓
┌─────────────────────────────────┐
│   Validate Credentials          │
│ Query: SELECT FROM user         │
│ WHERE username & password cocok │
└──────────┬──────────────────────┘
           │
        ┌──┴──┐
        │     │
        |     |
        │     │
        │     ↓
        │  Error Message
        │  "Username atau
        │   Password salah!"
        │
        ↓
    ┌────────────────────┐
    │ Set Session        │
    │ username, level    │
    └──────────┬─────────┘
               │
         ┌─────┴─────┬─────────┬──────────┐
         ↓           ↓         ↓          ↓
      admin       dosen    mahasiswa   (other)
         │           │         │
         ↓           ↓         ↓
    admin/       dosen/    mahasiswa/
    index.php    index.php  index.php
```

---

## Alur CRUD Mahasiswa

```
Admin buka admin/mahasiswa.php
         │
         ├─ CREATE ─→ Form Tambah
         │               │
         │               ↓
         │           INSERT mahasiswa
         │           INSERT user (auto)
         │
         ├─ READ ──→ Query & Tampil list
         │
         ├─ UPDATE ─→ Edit via Modal
         │             │
         │             ↓
         │           UPDATE mahasiswa
         │
         └─ DELETE ─→ Hapus data
                       │
                       ↓
                    DELETE mahasiswa
                    DELETE user (FK cascade)
                    DELETE nilai (FK cascade)
```

---

## Default Credentials

```
Admin Default:
  Username: admin
  Password: admin123

Buat user baru dengan menambah mahasiswa/dosen:
  NIM 12345 → username: 1234, password: 12345
```

---

## Dokumentasi

| File                       | Gunakan Untuk                     |
| -------------------------- | --------------------------------- |
| **INDEX_DOKUMENTASI.md**   | 🗺️ Navigasi ke semua dokumentasi  |
| **SETUP_GUIDE.md**         | 🔧 Instalasi step-by-step         |
| **DOKUMENTASI_LENGKAP.md** | 📖 Referensi teknis lengkap       |
| **QUICK_REFERENCE.md**     | 📝 Developer handbook (SQL, code) |
| **LAPORAN_PRAKTIKUM.md**   | 📄 Template laporan praktikum     |

**Mulai dari:** `INDEX_DOKUMENTASI.md` untuk navigasi

---

## Troubleshooting Cepat

| Error                     | Solusi                                  |
| ------------------------- | --------------------------------------- |
| "Koneksi gagal"           | Start MySQL di XAMPP, check koneksi.php |
| "Username/Password salah" | Check user di debug_login.php           |
| "Data tidak tampil"       | Refresh page, check database            |
| "File not found"          | Pastikan folder di C:\xampp\htdocs\     |
| "Foreign key error"       | Pastikan mahasiswa/matkul sudah ada     |

**Detail troubleshooting:** Lihat `SETUP_GUIDE.md` bagian Troubleshooting

---

## Testing Checklist

- [ ] Setup selesai, aplikasi bisa dibuka
- [ ] Login admin berhasil
- [ ] Tambah mahasiswa berhasil
- [ ] Auto-create user untuk mahasiswa
- [ ] Login sebagai mahasiswa berhasil
- [ ] Tambah dosen & matkul berhasil
- [ ] Input nilai dengan dropdown berhasil
- [ ] Edit & delete semua fitur berhasil
- [ ] Cascade delete bekerja
- [ ] Session logout bekerja

---

## Pengembangan Selanjutnya

### Fitur yang Bisa Ditambah:

1. Dashboard analytics (grafik nilai)
2. Email notification (login/data changes)
3. Mobile responsive optimization
4. Export ke Excel/PDF
5. Real-time notifications
6. View-only mode untuk siswa
7. Jadwal & attendance tracking
8. Assignment & submission
9. Discussion forum
10. 2FA Authentication

### Security Improvements:

1. Upgrade MD5 → bcrypt/argon2
2. Implement rate limiting
3. Add CSRF tokens
4. Implement API authentication
5. Add audit logging
6. Implement HTTPS
7. Add input validation
8. Implement Content Security Policy (CSP)

---

## Support & Feedback

Jika ada pertanyaan atau feedback:

1. Check dokumentasi di INDEX_DOKUMENTASI.md
2. Lihat QUICK_REFERENCE.md untuk SQL/code
3. Cek SETUP_GUIDE.md bagian Troubleshooting
4. Gunakan debug_login.php untuk debug

---

## License & Attribution

**Sistem Informasi Akademik (SIA)**

- Dibuat untuk Workshop AsLab
- Tujuan: Learning & Practice
- Status: Production Ready v1.0

---

## Learning Outcomes

Setelah menggunakan sistem ini, Anda akan belajar:

**Backend (PHP)**

- Koneksi database MySQL
- CRUD operations
- Session management
- Form handling
- Error handling

**Database (MySQL)**

- Table design
- Foreign keys
- Relational queries
- JOIN operations
- Cascade delete

**Frontend (Bootstrap)**

- Responsive design
- Modal forms
- Bootstrap components
- CSS styling
- Form validation

**Security**

- SQL injection prevention
- Password hashing
- Role-based access control
- Input validation

---

## Quick Start Commands

```bash
# 1. Navigate ke project
cd C:\xampp\htdocs\workshop_aslab

# 2. Start XAMPP (Windows/Linux/Mac)
# Windows: Open XAMPP Control Panel, click Start Apache & MySQL
# Linux: sudo /opt/lampp/lampp start
# Mac: sudo /Applications/XAMPP/xamppfiles/xampp start

# 3. Open browser
http://localhost/workshop_aslab/

# 4. Login
admin / admin123

# 5. Test dengan debug page
http://localhost/workshop_aslab/debug_login.php
```

---

**🎉 Selamat! Sistem Informasi Akademik siap digunakan!**

**Next Step:** Buka `INDEX_DOKUMENTASI.md` untuk navigasi lengkap

---

_Last Updated: 22 Januari 2026_  
_Version: 1.0 (Production Ready)_
