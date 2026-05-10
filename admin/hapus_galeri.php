<?php
session_start();
require_once '../koneksi.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Get image filename
    $sql = "SELECT gambar FROM galeri WHERE id = $id";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $gambar = $row['gambar'];
        
        // Delete from database
        $delete_sql = "DELETE FROM galeri WHERE id = $id";
        if ($conn->query($delete_sql) === TRUE) {
            // Delete file
            if (!empty($gambar) && file_exists('../uploads/' . $gambar)) {
                unlink('../uploads/' . $gambar);
            }
            $_SESSION['pesan'] = "Foto galeri berhasil dihapus!";
        } else {
            $_SESSION['error'] = "Gagal menghapus data dari database.";
        }
    } else {
        $_SESSION['error'] = "Data tidak ditemukan.";
    }
} else {
    $_SESSION['error'] = "ID tidak valid.";
}

header("Location: galeri.php");
exit();
?>
