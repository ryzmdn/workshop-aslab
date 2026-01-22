<?php
/**
 * ==========================================
 * DATABASE CONNECTION
 * ==========================================
 *
 * File: koneksi.php
 * Fungsi: Koneksi ke database MySQL
 *
 * Database Config:
 * - Host: localhost (server lokal)
 * - User: root (user default XAMPP)
 * - Password: (kosong)
 * - Database: workshop
 *
 * Error Handling:
 * - Jika koneksi gagal, tampil error message & die()
 *
 * Usage:
 * - Include file ini di setiap halaman yang butuh database
 * - Contoh: include './koneksi.php';
 * - Gunakan $koneksi untuk query
 *
 * Example Query:
 * $result = mysqli_query($koneksi, "SELECT * FROM mahasiswa");
 */

$koneksi = mysqli_connect("localhost", "root", "", "workshop");

if (! $koneksi) {
    die('Koneksi gagal: ' . mysqli_connect_error());
}
