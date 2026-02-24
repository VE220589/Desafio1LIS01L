<?php

session_start();

header('Content-Type: application/json');

require_once '../classes/Service.class.php';
require_once '../classes/Quote.class.php';


try {

    //Validación del método

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception("Método no permitido.");
    }

    //Validación del carrito

    if (empty($_SESSION['cart'])) {
        throw new Exception("No se puede generar cotización con carrito vacío.");
    }

   //Obtener datos del cliente

    $cliente = [
        'nombre'   => trim($_POST['nombre'] ?? ''),
        'empresa'  => trim($_POST['empresa'] ?? ''),
        'email'    => trim($_POST['email'] ?? ''),
        'telefono' => trim($_POST['telefono'] ?? '')
    ];

    //Definimos el cátalogo de los servicios que vamos a estar operando

    $servicesData = [
        1 => ['Landing Page Profesional', 'Diseño de landing page optimizada.', 450, 'Desarrollo Web'],
        2 => ['Sitio Web Corporativo', 'Desarrollo de sitio web empresarial.', 1200, 'Desarrollo Web'],
        3 => ['Tienda Online', 'Implementación de e-commerce completo.', 2800, 'Desarrollo Web'],
        4 => ['Sistema Web Personalizado', 'Sistema a medida para empresas.', 4500, 'Desarrollo Web'],
        5 => ['Gestión de Redes Sociales', 'Administración mensual de redes.', 350, 'Marketing Digital'],
        6 => ['Campaña Publicitaria', 'Publicidad en Facebook/Instagram.', 600, 'Marketing Digital'],
        7 => ['Optimización SEO', 'Mejora posicionamiento en Google.', 900, 'Marketing Digital'],
        8 => ['Estrategia Integral Marketing', 'Plan completo de marketing digital.', 2000, 'Marketing Digital'],
        9 => ['Soporte Técnico Mensual', 'Soporte continuo para sistemas.', 250, 'Soporte y Consultoría'],
        10 => ['Auditoría Seguridad Web', 'Evaluación de vulnerabilidades.', 750, 'Soporte y Consultoría'],
        11 => ['Consultoría Tecnológica', 'Asesoramiento tecnológico.', 1500, 'Soporte y Consultoría'],
        12 => ['Mantenimiento Web Anual', 'Mantenimiento preventivo anual.', 1100, 'Soporte y Consultoría']
    ];

    //Creamos una cotización

    $quote = new Quote($cliente);

    foreach ($_SESSION['cart'] as $id => $cantidad) {

        if (!isset($servicesData[$id])) {
            throw new Exception("Servicio inválido detectado.");
        }

        [$nombre, $descripcion, $precio, $categoria] = $servicesData[$id];

        $service = new Service(
            $id,
            $nombre,
            $descripcion,
            $precio,
            $categoria
        );

        $quote->agregarItem($service, $cantidad);
    }

   //Generar cotización

    $quote->generar();

    //Guardamos en sesión

    $_SESSION['quotes'][] = $quote;

    echo json_encode([
        'success' => true,
        'codigo' => $quote->getCodigo(),
        'subtotal' => $quote->getSubtotal(),
        'descuento' => $quote->getDescuento(),
        'iva' => $quote->getIVA(),
        'total' => $quote->getTotal(),
        'fechaGeneracion' => $quote->getFechaGeneracion(),
        'fechaValidez' => $quote->getFechaValidez()
    ]);

} catch (Exception $e) {

    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}