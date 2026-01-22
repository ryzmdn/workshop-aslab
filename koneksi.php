<?php
$koneksi = mysqli_connect("localhost", "root", "", "workshop");

if (! $koneksi) {
    die('Koneksi gagal' . mysqli_connect_error());
}
?>