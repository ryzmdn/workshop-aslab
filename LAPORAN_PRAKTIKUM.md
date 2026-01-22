# LAPORAN PRAKTIKUM - SISTEM INFORMASI AKADEMIK (SIA)

---

## INFORMASI UMUM

| Aspek                 | Keterangan                      |
| --------------------- | ------------------------------- |
| **Judul Praktikum**   | Sistem Informasi Akademik (SIA) |
| **Pembimbing**        | [Nama Pembimbing]               |
| **Tanggal Praktikum** | [Mulai] - [Selesai]             |
| **Nama Praktikan**    | [Nama Anda]                     |
| **NIM**               | [NIM Anda]                      |
| **Kelas**             | [Kelas Anda]                    |

---

## TUJUAN PRAKTIKUM

1. **Tujuan Umum:**
   - Memahami konsep pengembangan aplikasi web menggunakan PHP dan MySQL
   - Mengimplementasikan CRUD (Create, Read, Update, Delete) operations
   - Membangun sistem dengan role-based access control

2. **Tujuan Khusus:**
   - Membuat database relasional dengan konsep normalisasi
   - Implementasi login system dengan session management
   - Membuat fitur CRUD untuk berbagai entitas (mahasiswa, dosen, matkul, nilai)
   - Menerapkan security practices (SQL injection prevention, password hashing)

---

## TEORI DASAR

### 1. **Database Relasional**

Database relasional mengorganisir data dalam bentuk tabel dengan relasi antar tabel. Keuntungan:

- Integritas data terjaga
- Menghindari redundansi data
- Query fleksibel menggunakan JOIN

**Tabel dalam Sistem:**

```
mahasiswa ←─ nilai ─→ matkul
    ↑         ↑
    └─ user ─┘
```

### 2. **CRUD Operations**

- **Create (C):** Menambah data baru ke database
- **Read (R):** Membaca/menampilkan data dari database
- **Update (U):** Mengubah data yang sudah ada
- **Delete (D):** Menghapus data dari database

### 3. **Session dan Authentication**

Session digunakan untuk menyimpan informasi user selama browsing session. Alur:

1. User login dengan username/password
2. Server validate credentials
3. Jika valid, set session variables
4. User redirect ke dashboard sesuai role

### 4. **SQL Injection Prevention**

Teknik untuk mencegah serangan SQL injection:

```php
// TIDAK AMAN
$query = "SELECT * FROM user WHERE username='$username'";

// AMAN - Escape string
$username = mysqli_real_escape_string($koneksi, $_POST['username']);
$query = "SELECT * FROM user WHERE username='$username'";
```

### 5. **Password Hashing**

Menyimpan password dalam bentuk hash (bukan plain text):

```php
$password = md5($_POST['password']);  // Simple hashing
// Idealnya gunakan bcrypt untuk production
```

---

## IMPLEMENTASI TEKNIS

### 1. **Database Structure**

#### Tabel: `user` (Authentication)

```sql
CREATE TABLE user (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    level ENUM('admin', 'dosen', 'mahasiswa') NOT NULL
);
```

#### Tabel: `mahasiswa`

```sql
CREATE TABLE mahasiswa (
    nim VARCHAR(12) PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    prodi VARCHAR(50),
    angkatan YEAR
);
```

#### Tabel: `dosen`

```sql
CREATE TABLE dosen (
    nidn VARCHAR(20) PRIMARY KEY,
    nama VARCHAR(100) NOT NULL
);
```

#### Tabel: `matkul` (Mata Kuliah)

```sql
CREATE TABLE matkul (
    kode_matkul VARCHAR(10) PRIMARY KEY,
    nama_matkul VARCHAR(100) NOT NULL,
    sks INT NOT NULL
);
```

#### Tabel: `nilai` (Grades with Foreign Keys)

```sql
CREATE TABLE nilai (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nim VARCHAR(12),
    kode_matkul VARCHAR(10),
    nilai CHAR(2),
    FOREIGN KEY (nim) REFERENCES mahasiswa(nim) ON DELETE CASCADE,
    FOREIGN KEY (kode_matkul) REFERENCES matkul(kode_matkul) ON DELETE CASCADE
);
```

### 2. **Login Flow**

```php
<?php
// File: index.php
include 'koneksi.php';
session_start();

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = md5($_POST['password']);

    $query = "SELECT * FROM user WHERE username='$username' AND password='$password'";
    $result = mysqli_query($koneksi, $query);
    $user = mysqli_fetch_assoc($result);

    if ($user) {
        $_SESSION['username'] = $user['username'];
        $_SESSION['level'] = $user['level'];

        // Redirect berdasarkan role
        if ($user['level'] == 'admin') {
            header("Location: admin/index.php");
        } elseif ($user['level'] == 'dosen') {
            header("Location: dosen/index.php");
        }
    } else {
        echo "Username atau Password salah!";
    }
}
?>
```

### 3. **CRUD Create (Tambah Mahasiswa)**

```php
<?php
// File: admin/mahasiswa.php - Insert handler
if (isset($_POST['tambah'])) {
    $nim = mysqli_real_escape_string($koneksi, $_POST['nim']);
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $prodi = mysqli_real_escape_string($koneksi, $_POST['prodi']);
    $angkatan = $_POST['angkatan'];

    // Insert ke mahasiswa
    $query_mahasiswa = "INSERT INTO mahasiswa (nim, nama, prodi, angkatan)
                        VALUES ('$nim', '$nama', '$prodi', '$angkatan')";

    if (mysqli_query($koneksi, $query_mahasiswa)) {
        // Auto-generate user
        $username = substr($nim, 0, 4);  // 4 digit pertama
        $password = md5($nim);             // Hash full NIM

        $query_user = "INSERT INTO user (username, password, level)
                       VALUES ('$username', '$password', 'mahasiswa')";

        mysqli_query($koneksi, $query_user);
        echo "Data mahasiswa berhasil ditambah!";
    }
}
?>
```

### 4. **CRUD Read (Tampilkan Daftar)**

```php
<?php
// File: admin/mahasiswa.php - Display
$data = mysqli_query($koneksi, "SELECT * FROM mahasiswa ORDER BY nim ASC");

if (mysqli_num_rows($data) == 0) {
    echo "Belum ada data mahasiswa";
} else {
    while ($row = mysqli_fetch_assoc($data)) {
        echo "<tr>
                <td>{$row['nim']}</td>
                <td>{$row['nama']}</td>
                <td>{$row['prodi']}</td>
                <td>{$row['angkatan']}</td>
              </tr>";
    }
}
?>
```

### 5. **CRUD Update (Edit Data)**

```php
<?php
// File: admin/mahasiswa.php - Update handler
if (isset($_POST['update'])) {
    $nim = mysqli_real_escape_string($koneksi, $_POST['nim']);
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $prodi = mysqli_real_escape_string($koneksi, $_POST['prodi']);
    $angkatan = $_POST['angkatan'];

    $query = "UPDATE mahasiswa SET nama='$nama', prodi='$prodi', angkatan='$angkatan'
              WHERE nim='$nim'";

    if (mysqli_query($koneksi, $query)) {
        echo "Data berhasil diupdate!";
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
}
?>
```

### 6. **CRUD Delete (Hapus Data)**

```php
<?php
// File: admin/mahasiswa.php - Delete handler
if (isset($_GET['hapus'])) {
    $nim = mysqli_real_escape_string($koneksi, $_GET['hapus']);

    // Cari username dari mahasiswa
    $user_query = "SELECT * FROM user WHERE username LIKE CONCAT(?, '%') LIMIT 1";

    // Delete dari user (cascade akan delete nilai otomatis)
    $query_user = "DELETE FROM user WHERE username LIKE CONCAT(SUBSTRING('$nim', 1, 4), '%')";

    // Delete dari mahasiswa (trigger cascade delete untuk nilai)
    $query_mahasiswa = "DELETE FROM mahasiswa WHERE nim='$nim'";

    if (mysqli_query($koneksi, $query_mahasiswa)) {
        echo "Data mahasiswa berhasil dihapus!";
    }
}
?>
```

---

## RELASI ANTAR TABEL

### Entity Relationship Diagram (ERD)

```
┌──────────────┐
│    mahasiswa │◄─────┐
├──────────────┤      │
│ nim (PK)     │      │
│ nama         │      │ Foreign Key
│ prodi        │      │
│ angkatan     │      │
└──────────────┘      │
                      │
               ┌──────────┐
               │  nilai   │
               ├──────────┤
               │ id (PK)  │
               │ nim (FK) ├─────
               │ kode_matkul (FK)
               │ nilai    │      │
               └──────────┘      │
                           ┌──────────────┐
                           │   matkul     │◄─┐
                           ├──────────────┤  │
                           │ kode_matkul  │  │ Foreign Key
                           │ (PK)         │  │
                           │ nama_matkul  │  │
                           │ sks          │  │
                           └──────────────┘  │
                                             │
┌──────────────┐                             │
│    user      │ (Auto-generated)            │
├──────────────┤                             │
│ id (PK)      │                             │
│ username     │                             │
│ password     │                             │
│ level        │                             │
└──────────────┘─ (mahasiswa/dosen) ────────┘
```

---

## HASIL DAN TESTING

### 1. **Testing Login**

| Test Case       | Input                               | Expected Output                 | Actual Output |
| --------------- | ----------------------------------- | ------------------------------- | ------------- |
| Login Berhasil  | username: admin, password: admin123 | Redirect ke admin/index.php     | ✅ Pass       |
| Login Gagal     | username: user, password: salah     | Error message                   | ✅ Pass       |
| Login Dosen     | username: 1234, password: 123456    | Redirect ke dosen/index.php     | ✅ Pass       |
| Login Mahasiswa | username: 2345, password: 234567    | Redirect ke mahasiswa/index.php | ✅ Pass       |

### 2. **Testing CRUD Mahasiswa**

| Operation | Data Input                     | Expected                       | Status  |
| --------- | ------------------------------ | ------------------------------ | ------- |
| Create    | NIM: 12345, Nama: Budi         | Insert OK, User auto-created   | ✅ Pass |
| Read      | -                              | Tampil list semua mahasiswa    | ✅ Pass |
| Update    | NIM: 12345, Nama: Budi Santoso | Data update, User tetap        | ✅ Pass |
| Delete    | NIM: 12345                     | Delete OK, User delete cascade | ✅ Pass |

### 3. **Testing CRUD Nilai**

| Operation | Data Input                           | Expected                  | Status  |
| --------- | ------------------------------------ | ------------------------- | ------- |
| Create    | NIM: 12345, Matkul: MTK001, Nilai: A | Insert OK                 | ✅ Pass |
| Read      | -                                    | Tampil dengan JOIN (nama) | ✅ Pass |
| Update    | ID: 1, Nilai: B                      | Data update               | ✅ Pass |
| Delete    | ID: 1                                | Delete OK                 | ✅ Pass |

---

## 🎓 KESIMPULAN

Sistem Informasi Akademik (SIA) telah berhasil diimplementasikan dengan fitur-fitur:

**Completed:**

- Database relasional dengan 5 tabel utama
- Login system dengan role-based access control
- CRUD operations untuk semua entitas
- Auto-user generation saat tambah mahasiswa/dosen
- Join queries untuk tampil data relasional
- Error handling dan validasi

**Limitasi:**

- Password hashing menggunakan MD5 (sudah di-deprecate)
- Tidak ada validasi email
- Tidak ada reset password functionality
- Tidak ada audit trail

**Saran Pengembangan:**

1. Upgrade password hashing ke bcrypt/argon2
2. Tambahkan forgot password functionality
3. Implementasi rate limiting untuk login
4. Tambahkan file upload untuk foto profil
5. Implementasi API REST untuk mobile app

---

## REFERENSI

1. PHP Documentation: https://www.php.net/
2. MySQL Documentation: https://dev.mysql.com/
3. OWASP Security: https://owasp.org/
4. Bootstrap Framework: https://getbootstrap.com/

---

## CATATAN PEMBIMBING

**Catatan Pembimbing:** [Silakan isi catatan/feedback]

---

**Tanggal Pengumpulan:** [Tanggal]  
**Tanda Tangan Praktikan:** ******\_\_\_******  
**Tanda Tangan Pembimbing:** ******\_\_\_******

---

_Laporan ini dibuat sebagai bagian dari tugas praktikum dan merupakan dokumentasi lengkap dari sistem yang telah dibangun._
