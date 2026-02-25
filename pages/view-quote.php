<?php
session_start();

$filePath = '../data/quotes.json';

$quotes = [];

if (file_exists($filePath)) {
    $json = file_get_contents($filePath);
    $quotes = json_decode($json, true);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Historial de Cotizaciones</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container my-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Historial de Cotizaciones</h2>
        <a href="services-catalog.php" class="btn btn-outline-dark">
            Volver al Catálogo
        </a>
    </div>

    <?php if (empty($quotes)): ?>

        <div class="alert alert-info">
            No hay cotizaciones registradas.
        </div>

    <?php else: ?>

        <?php foreach (array_reverse($quotes) as $quote): ?>

            <div class="card mb-4 shadow-sm">

                <div class="card-header bg-dark text-white">
                    Código: <?= htmlspecialchars($quote['codigo']); ?>
                </div>

                <div class="card-body">

                    <div class="row mb-3">

                        <div class="col-md-6">
                            <strong>Cliente:</strong> <?= htmlspecialchars($quote['nombre']); ?><br>
                            <strong>Empresa:</strong> <?= htmlspecialchars($quote['empresa']); ?><br>
                            <strong>Email:</strong> <?= htmlspecialchars($quote['email']); ?><br>
                            <strong>Teléfono:</strong> <?= htmlspecialchars($quote['telefono']); ?>
                        </div>

                        <div class="col-md-6">
                            <strong>Fecha:</strong> <?= htmlspecialchars($quote['fecha']); ?><br>
                            <strong>Válido hasta:</strong> <?= htmlspecialchars($quote['validez']); ?><br>
                            <strong>Estado:</strong> 
                            <span class="badge bg-success">
                                <?= htmlspecialchars($quote['estado']); ?>
                            </span>
                        </div>

                    </div>

                    <h5>Servicios:</h5>

                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Servicio</th>
                                <th>Cantidad</th>
                                <th>Precio Unitario</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>

                        <?php foreach ($quote['servicios'] as $servicio): ?>

                            <tr>
                                <td><?= htmlspecialchars($servicio['nombre']); ?></td>
                                <td><?= $servicio['cantidad']; ?></td>
                                <td>$<?= number_format($servicio['precio'], 2); ?></td>
                                <td>$<?= number_format($servicio['precio'] * $servicio['cantidad'], 2); ?></td>
                            </tr>

                        <?php endforeach; ?>

                        </tbody>
                    </table>

                    <div class="text-end">

                        <p><strong>Subtotal:</strong> $<?= number_format($quote['subtotal'], 2); ?></p>
                        <p><strong>Descuento:</strong> $<?= number_format($quote['descuento'], 2); ?></p>
                        <p><strong>IVA:</strong> $<?= number_format($quote['iva'], 2); ?></p>

                        <h5 class="text-success">
                            Total: $<?= number_format($quote['total'], 2); ?>
                        </h5>

                    </div>

                </div>

            </div>

        <?php endforeach; ?>

    <?php endif; ?>

</div>

</body>
</html>