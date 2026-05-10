<?php
session_start();
require_once '../koneksi.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = $conn->real_escape_string($_GET['id']);
    
    // Ambil data gambar untuk dihapus
    $sql_select = "SELECT gambar FROM fasilitas WHERE id = '$id'";
    $result = $conn->query($sql_select);
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $gambar = $row['gambar'];
        
        // Hapus file gambar dari folder
        if (!empty($gambar) && file_exists('../uploads/' . $gambar)) {
            unlink('../uploads/' . $gambar);
        }
        
        // Hapus data dari database
        $sql_delete = "DELETE FROM fasilitas WHERE id = '$id'";
        if ($conn->query($sql_delete) === TRUE) {
            $_SESSION['pesan'] = "Data fasilitas berhasil dihapus!";
        } else {
            $_SESSION['pesan'] = "Gagal menghapus data: " . $conn->error;
        }
    }
}

header("Location: fasilitas.php");
exit();
?>
