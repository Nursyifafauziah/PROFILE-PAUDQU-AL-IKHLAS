<?php
session_start();
require_once '../koneksi.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = $conn->real_escape_string($_GET['id']);
    
    // Hapus pesan dari database
    $sql_delete = "DELETE FROM pesan WHERE id = '$id'";
    if ($conn->query($sql_delete) === TRUE) {
        $_SESSION['pesan_aksi'] = "Pesan berhasil dihapus!";
    } else {
        $_SESSION['pesan_aksi'] = "Gagal menghapus pesan: " . $conn->error;
    }
}

header("Location: pesan.php");
exit();
?>
