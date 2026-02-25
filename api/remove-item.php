<?php
session_start();
header('Content-Type: application/json');

if (!isset($_POST['id'])) {
    echo json_encode(['success' => false]);
    exit;
}

$id = (int) $_POST['id'];

if (isset($_SESSION['cart'][$id])) {
    unset($_SESSION['cart'][$id]);
}

echo json_encode(['success' => true]);