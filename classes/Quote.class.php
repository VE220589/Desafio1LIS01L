<?php

require_once 'Service.class.php';

class Quote
{
   //Definimos las constantes según nuestra lógica de negocio

    public const IVA = 0.13;
    public const MONTO_MINIMO = 100;


    //Definición de propiedades privadas

    private string $codigo;
    private array $cliente;
    private array $items = [];

    private float $subtotal = 0.0;
    private float $descuento = 0.0;
    private float $iva = 0.0;
    private float $total = 0.0;

    private string $fechaGeneracion;
    private string $fechaValidez;


    //Constructor

    public function __construct(array $cliente)
    {
        $this->validarCliente($cliente);
        $this->cliente = $cliente;
    }


   //Validación del cliente
    private function validarCliente(array $cliente): void
    {
        $camposRequeridos = ['nombre', 'empresa', 'email', 'telefono'];

        foreach ($camposRequeridos as $campo) {
            if (empty(trim($cliente[$campo] ?? ''))) {
                throw new InvalidArgumentException("El campo {$campo} es obligatorio.");
            }
        }

        if (!filter_var($cliente['email'], FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException("El email no tiene formato válido.");
        }
    }


    //Agregamos items

    public function agregarItem(Service $service, int $cantidad): void
    {
        if ($cantidad < 1 || $cantidad > 10) {
            throw new InvalidArgumentException("La cantidad debe estar entre 1 y 10.");
        }

        $this->items[] = [
            'service' => $service,
            'cantidad' => $cantidad
        ];
    }


   //Cálculos

    public function calcularSubtotal(): void
    {
        $this->subtotal = 0;

        foreach ($this->items as $item) {
            $this->subtotal += $item['service']->getPrecio() * $item['cantidad'];
        }
    }

    public function calcularDescuento(): void
    {
        $totalUnidades = 0;

        foreach ($this->items as $item) {
            $totalUnidades += $item['cantidad'];
        }

        $porcentaje = 0;

        if ($totalUnidades >= 3 && $totalUnidades <= 5) {
            $porcentaje = 0.08;
        } elseif ($totalUnidades >= 6 && $totalUnidades <= 9) {
            $porcentaje = 0.12;
        } elseif ($totalUnidades >= 10) {
            $porcentaje = 0.18;
        }

        $this->descuento = $this->subtotal * $porcentaje;
    }

    public function calcularIVA(): void
    {
        $baseImponible = $this->subtotal - $this->descuento;
        $this->iva = $baseImponible * self::IVA;
    }

    public function calcularTotal(): void
    {
        $this->total = ($this->subtotal - $this->descuento) + $this->iva;
    }


    //Generar el códdigo de la cotización

    public static function generarCodigo(): string
    {
        if (!isset($_SESSION['quote_counter'])) {
            $_SESSION['quote_counter'] = 1;
        } else {
            $_SESSION['quote_counter']++;
        }

        $anio = date('Y');
        $consecutivo = str_pad($_SESSION['quote_counter'], 4, '0', STR_PAD_LEFT);

        return "COT-{$anio}-{$consecutivo}";
    }

    public static function validarMonto(float $subtotal): void
    {
        if ($subtotal < self::MONTO_MINIMO) {
            throw new InvalidArgumentException(
                "El monto mínimo para generar una cotización es $" . self::MONTO_MINIMO
            );
        }
    }


    //Definimos el método para generar la cotización

    public function generar(): void
    {
        if (empty($this->items)) {
            throw new RuntimeException("No se puede generar una cotización con el carrito vacío.");
        }

        $this->calcularSubtotal();
        self::validarMonto($this->subtotal);
        $this->calcularDescuento();
        $this->calcularIVA();
        $this->calcularTotal();

        $this->codigo = self::generarCodigo();

        $this->fechaGeneracion = date('Y-m-d');
        $this->fechaValidez = date('Y-m-d', strtotime('+7 days'));
    }


    //Aplicamos los getters, la parte de encapsulación

    public function getCodigo(): string
    {
        return $this->codigo;
    }

    public function getCliente(): array
    {
        return $this->cliente;
    }

    public function getItems(): array
{
    $itemsFormateados = [];

    foreach ($this->items as $item) {

        $service = $item['service'];

        $itemsFormateados[] = [
            'id' => $service->getId(),
            'nombre' => $service->getNombre(),
            'precio' => $service->getPrecio(),
            'cantidad' => $item['cantidad']
        ];
    }

    return $itemsFormateados;
}

    public function getSubtotal(): float
    {
        return $this->subtotal;
    }

    public function getDescuento(): float
    {
        return $this->descuento;
    }

    public function getIVA(): float
    {
        return $this->iva;
    }

    public function getTotal(): float
    {
        return $this->total;
    }

    public function getFechaGeneracion(): string
    {
        return $this->fechaGeneracion;
    }

    public function getFechaValidez(): string
    {
        return $this->fechaValidez;
    }
}