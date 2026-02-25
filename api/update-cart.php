<?php
session_start();
header('Content-Type: application/json');

if (!isset($_POST['id']) || !isset($_POST['action'])) {
    echo json_encode(['success' => false]);
    exit;
}

$id = (int) $_POST['id'];
$action = $_POST['action'];

if (!isset($_SESSION['cart'][$id])) {
    echo json_encode(['success' => false]);
    exit;
}

if ($action === 'increase') {
    $_SESSION['cart'][$id]++;
}

if ($action === 'decrease') {
    $_SESSION['cart'][$id]--;

    if ($_SESSION['cart'][$id] <= 0) {
        unset($_SESSION['cart'][$id]);
    }
}

echo json_encode(['success' => true]);