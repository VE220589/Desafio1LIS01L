<?php
session_start();
require_once '../classes/Service.class.php';

header('Content-Type: application/json');

/* ==========================
   CATÁLOGO BASE
========================== */

$servicesData = [
    [1, 'Landing Page Profesional', 'Diseño de landing page optimizada.', 450, 'Desarrollo Web'],
    [2, 'Sitio Web Corporativo', 'Desarrollo empresarial.', 1200, 'Desarrollo Web'],
    [3, 'Tienda Online', 'E-commerce completo.', 2800, 'Desarrollo Web'],
    [4, 'Sistema Web Personalizado', 'Sistema a medida.', 4500, 'Desarrollo Web'],
    [5, 'Gestión de Redes Sociales', 'Administración mensual.', 350, 'Marketing Digital'],
    [6, 'Campaña Publicitaria', 'Publicidad segmentada.', 600, 'Marketing Digital'],
    [7, 'Optimización SEO', 'Posicionamiento en Google.', 900, 'Marketing Digital'],
    [8, 'Estrategia Integral Marketing', 'Plan completo.', 2000, 'Marketing Digital'],
    [9, 'Soporte Técnico Mensual', 'Soporte continuo.', 250, 'Soporte y Consultoría'],
    [10, 'Auditoría Seguridad Web', 'Evaluación seguridad.', 750, 'Soporte y Consultoría'],
    [11, 'Consultoría Tecnológica', 'Asesoría profesional.', 1500, 'Soporte y Consultoría'],
    [12, 'Mantenimiento Web Anual', 'Mantenimiento anual.', 1100, 'Soporte y Consultoría']
];

/* ==========================
   RECONSTRUIR OBJETOS
========================== */

$services = [];

foreach ($servicesData as $data) {
    $service = new Service(...$data);
    $services[$service->getId()] = $service;
}

/* ==========================
   PROCESAR CARRITO
========================== */

$items = [];
$subtotal = 0;
$totalItems = 0;

if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {

    foreach ($_SESSION['cart'] as $id => $cantidad) {

        if (isset($services[$id])) {

            $service = $services[$id];
            $total = $service->getPrecio() * $cantidad;

            $items[] = [
                'id' => $service->getId(),
                'nombre' => $service->getNombre(),
                'precio' => $service->getPrecio(),
                'cantidad' => $cantidad,
                'total' => $total
            ];

            $subtotal += $total;
            $totalItems += $cantidad;
        }
    }
}

$descuento = 0;

if ($totalItems >= 3) {
    $descuento = $subtotal * 0.10;
}

$iva = ($subtotal - $descuento) * 0.13;
$totalFinal = $subtotal - $descuento + $iva;

echo json_encode([
    'success' => true,
    'items' => $items,
    'subtotal' => $subtotal,
    'descuento' => $descuento,
    'iva' => $iva,
    'total' => $totalFinal,
    'totalItems' => $totalItems
]);