<?php
session_start();
require_once '../koneksi.php';

if (!isset($_SESSION['admin_logged_in'])) {
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

$unread_sql = "SELECT COUNT(*) as unread FROM pesan WHERE status='belum_dibaca'";
$unread_result = $conn->query($unread_sql);
$unread_count = $unread_result ? $unread_result->fetch_assoc()['unread'] : 0;

echo json_encode(['unread' => (int)$unread_count]);
?>
