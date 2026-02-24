<?php

session_start();
header('Content-Type: application/json');

try {

    //Validamos el método

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception("Método no permitido.");
    }

    //Validamos que el carrito exista

    if (empty($_SESSION['cart'])) {
        throw new Exception("El carrito está vacío.");
    }

    //Obtenemos los datos

    $id = intval($_POST['id'] ?? 0);
    $cantidad = intval($_POST['cantidad'] ?? 0);

    if ($id <= 0) {
        throw new Exception("ID inválido.");
    }

    if ($cantidad < 1 || $cantidad > 10) {
        throw new Exception("La cantidad debe estar entre 1 y 10.");
    }

    if (!isset($_SESSION['cart'][$id])) {
        throw new Exception("El servicio no existe en el carrito.");
    }

    //Actualizamos la cantidad

    $_SESSION['cart'][$id] = $cantidad;

    //Calculamos el total de unidades

    $totalItems = array_sum($_SESSION['cart']);

   //Calculamos el subtotal

    $servicesPrecios = [
        1 => 450,
        2 => 1200,
        3 => 2800,
        4 => 4500,
        5 => 350,
        6 => 600,
        7 => 900,
        8 => 2000,
        9 => 250,
        10 => 750,
        11 => 1500,
        12 => 1100
    ];

    $subtotal = 0;

    foreach ($_SESSION['cart'] as $serviceId => $cant) {
        if (isset($servicesPrecios[$serviceId])) {
            $subtotal += $servicesPrecios[$serviceId] * $cant;
        }
    }

    //Respuesta que nos devuelve el json

    echo json_encode([
        'success' => true,
        'totalItems' => $totalItems,
        'subtotal' => $subtotal
    ]);

} catch (Exception $e) {

    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}