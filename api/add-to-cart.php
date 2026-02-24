<?php

session_start();
header('Content-Type: application/json');

try {

    //Validamos el método

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception("Método no permitido.");
    }

    //Obteneemos el valor del id del servicio

    $id = intval($_POST['id'] ?? 0);

    if ($id <= 0) {
        throw new Exception("ID de servicio inválido.");
    }

   //Cátalogo base para buscar dicho servicio

    $servicesValidos = [1,2,3,4,5,6,7,8,9,10,11,12];

    if (!in_array($id, $servicesValidos)) {
        throw new Exception("El servicio no existe.");
    }

    //Inicializamos carrito

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    //Agregamos o incrementamos

    if (isset($_SESSION['cart'][$id])) {

        if ($_SESSION['cart'][$id] >= 10) {
            throw new Exception("No se pueden agregar más de 10 unidades del mismo servicio.");
        }

        $_SESSION['cart'][$id]++;

    } else {

        $_SESSION['cart'][$id] = 1;
    }

   //Cálculo del total de unidades

    $totalItems = array_sum($_SESSION['cart']);

    echo json_encode([
        'success' => true,
        'totalItems' => $totalItems,
        'message' => 'Servicio agregado correctamente.'
    ]);

} catch (Exception $e) {

    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}