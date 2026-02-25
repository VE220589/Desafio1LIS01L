<?php
session_start();
require_once '../classes/Service.class.php';

/* ==========================
   CATÁLOGO BASE DE SERVICIOS
========================== */

$servicesData = [
    [1, 'Landing Page Profesional', 'Diseño de landing page optimizada para conversión.', 450, 'Desarrollo Web'],
    [2, 'Sitio Web Corporativo', 'Desarrollo de sitio web empresarial.', 1200, 'Desarrollo Web'],
    [3, 'Tienda Online', 'Implementación completa de e-commerce.', 2800, 'Desarrollo Web'],
    [4, 'Sistema Web Personalizado', 'Sistema empresarial a medida.', 4500, 'Desarrollo Web'],
    [5, 'Gestión de Redes Sociales', 'Administración mensual de redes.', 350, 'Marketing Digital'],
    [6, 'Campaña Publicitaria', 'Publicidad digital segmentada.', 600, 'Marketing Digital'],
    [7, 'Optimización SEO', 'Mejora posicionamiento en buscadores.', 900, 'Marketing Digital'],
    [8, 'Estrategia Integral Marketing', 'Plan completo de marketing digital.', 2000, 'Marketing Digital'],
    [9, 'Soporte Técnico Mensual', 'Soporte continuo para sistemas.', 250, 'Soporte y Consultoría'],
    [10, 'Auditoría Seguridad Web', 'Evaluación de vulnerabilidades.', 750, 'Soporte y Consultoría'],
    [11, 'Consultoría Tecnológica', 'Asesoramiento tecnológico profesional.', 1500, 'Soporte y Consultoría'],
    [12, 'Mantenimiento Web Anual', 'Mantenimiento preventivo anual.', 1100, 'Soporte y Consultoría']
];

$services = [];

foreach ($servicesData as $data) {
    $services[] = new Service(...$data);
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Catálogo de Servicios</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body class="bg-light">

<div class="container my-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Catálogo de Servicios</h2>
        <a href="view-quote.php" class="btn btn-outline-dark">
            Ver Cotizaciones
        </a>
    </div>

    <div class="row">

        <!-- CATÁLOGO -->
        <div class="col-md-8">
            <div class="row">

                <?php foreach ($services as $service): ?>

                    <div class="col-md-6 mb-4">
                        <div class="card shadow-sm h-100">
                            <div class="card-body d-flex flex-column">

                                <h5 class="card-title">
                                    <?= $service->getNombre(); ?>
                                </h5>

                                <p class="card-text">
                                    <?= $service->getDescripcion(); ?>
                                </p>

                                <p class="fw-bold">
                                    $<?= number_format($service->getPrecio(), 2); ?>
                                </p>

                                <p class="text-muted">
                                    <?= $service->getCategoria(); ?>
                                </p>

                                <button 
                                    class="btn btn-primary mt-auto add-to-cart"
                                    data-id="<?= $service->getId(); ?>"
                                >
                                    Agregar al carrito
                                </button>

                            </div>
                        </div>
                    </div>

                <?php endforeach; ?>

            </div>
        </div>

        <!-- CARRITO -->
        <div class="col-md-4">

            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    Carrito
                    <span class="badge bg-warning text-dark" id="cart-count">
                        <?= isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0; ?>
                    </span>
                </div>

                <div class="card-body">

                    <div id="cart-items">
                        <p class="text-muted">No hay servicios en el carrito.</p>
                    </div>

                    <hr>

                    <p>
                        <strong>Subtotal:</strong>
                        $<span id="cart-subtotal">0.00</span>
                    </p>

                    <button class="btn btn-success w-100 mt-2" id="generate-quote">
                        Generar Cotización
                    </button>

                    <button class="btn btn-outline-danger w-100 mt-2" id="clear-cart">
                        Vaciar Carrito
                    </button>

                </div>
            </div>

        </div>

    </div>

</div>
<!-- MODAL GENERAR COTIZACIÓN -->
<div class="modal fade" id="quoteModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Confirmar Cotización</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">

        <div id="modal-summary" class="mb-3"></div>

        <form id="quote-form">
          <div class="mb-2">
            <input type="text" class="form-control" name="nombre" placeholder="Nombre" required>
          </div>

          <div class="mb-2">
            <input type="text" class="form-control" name="empresa" placeholder="Empresa" required>
          </div>

          <div class="mb-2">
            <input type="email" class="form-control" name="email" placeholder="Email" required>
          </div>

          <div class="mb-2">
            <input type="text" class="form-control" name="telefono" placeholder="Teléfono" required>
          </div>

          <button type="submit" class="btn btn-success w-100">
            Confirmar Cotización
          </button>
        </form>

      </div>

    </div>
  </div>
</div>

<script src="../assets/js/services-catalog.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>