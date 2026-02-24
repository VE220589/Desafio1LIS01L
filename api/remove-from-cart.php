<?php

session_start();
header('Content-Type: application/json');

try {

    //Validamos el método

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception("Método no permitido.");
    }

    //Validamos el carrito

    if (empty($_SESSION['cart'])) {
        throw new Exception("El carrito ya está vacío.");
    }

    //Verificar si se va a limpiar por completo el carrito

    if (isset($_POST['clear']) && $_POST['clear'] === 'true') {

        unset($_SESSION['cart']);

        echo json_encode([
            'success' => true,
            'totalItems' => 0,
            'subtotal' => 0,
            'message' => 'Carrito vaciado correctamente.'
        ]);

        exit;
    }

  //Eliminamos un servicio en especifico

    $id = intval($_POST['id'] ?? 0);

    if ($id <= 0) {
        throw new Exception("ID inválido.");
    }

    if (!isset($_SESSION['cart'][$id])) {
        throw new Exception("El servicio no existe en el carrito.");
    }

    unset($_SESSION['cart'][$id]);

    //Si el carrito queda vacío se le manda un mensaje

    if (empty($_SESSION['cart'])) {

        echo json_encode([
            'success' => true,
            'totalItems' => 0,
            'subtotal' => 0,
            'message' => 'Servicio eliminado. El carrito está vacío.'
        ]);

        exit;
    }

    //Se recalculan los totales

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

    $totalItems = array_sum($_SESSION['cart']);

   //Respuesta del json

    echo json_encode([
        'success' => true,
        'totalItems' => $totalItems,
        'subtotal' => $subtotal,
        'message' => 'Servicio eliminado correctamente.'
    ]);

} catch (Exception $e) {

    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}