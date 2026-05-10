<?php
session_start();
require_once '../koneksi.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = $conn->real_escape_string($_GET['id']);
    
    // Ambil data foto untuk dihapus
    $sql_select = "SELECT foto FROM guru WHERE id = '$id'";
    $result = $conn->query($sql_select);
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $foto = $row['foto'];
        
        // Hapus file foto dari folder
        if (!empty($foto) && file_exists('../uploads/' . $foto)) {
            unlink('../uploads/' . $foto);
        }
        
        // Hapus data dari database
        $sql_delete = "DELETE FROM guru WHERE id = '$id'";
        if ($conn->query($sql_delete) === TRUE) {
            $_SESSION['pesan'] = "Data guru berhasil dihapus!";
        } else {
            $_SESSION['pesan'] = "Gagal menghapus data: " . $conn->error;
        }
    }
}

header("Location: guru.php");
exit();
?>
