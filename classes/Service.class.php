<?php

class Service
{
    //Definimos las constantes

    public const CATEGORIAS_VALIDAS = [
        'Desarrollo Web',
        'Marketing Digital',
        'Soporte y Consultoría'
    ];

    public const PRECIO_MINIMO = 100;
    public const PRECIO_MAXIMO = 10000;


    //Definimos las propiedades (en este caso, privadas)

    private int $id;
    private string $nombre;
    private string $descripcion;
    private float $precio;
    private string $categoria;


    //Constructor

    public function __construct(
        int $id,
        string $nombre,
        string $descripcion,
        float $precio,
        string $categoria
    ) {
        $this->validarId($id);
        $this->validarNombre($nombre);
        $this->validarDescripcion($descripcion);
        $this->validarPrecio($precio);
        $this->validarCategoria($categoria);

        $this->id = $id;
        $this->nombre = trim($nombre);
        $this->descripcion = trim($descripcion);
        $this->precio = $precio;
        $this->categoria = $categoria;
    }


   //Métodos de validación

    private function validarId(int $id): void
    {
        if ($id <= 0) {
            throw new InvalidArgumentException("El ID del servicio debe ser mayor que 0.");
        }
    }

    private function validarNombre(string $nombre): void
    {
        $nombre = trim($nombre);

        if (empty($nombre)) {
            throw new InvalidArgumentException("El nombre del servicio no puede estar vacío.");
        }

        if (strlen($nombre) < 3) {
            throw new InvalidArgumentException("El nombre del servicio debe tener al menos 3 caracteres.");
        }
    }

    private function validarDescripcion(string $descripcion): void
    {
        $descripcion = trim($descripcion);

        if (empty($descripcion)) {
            throw new InvalidArgumentException("La descripción del servicio no puede estar vacía.");
        }

        if (strlen($descripcion) < 10) {
            throw new InvalidArgumentException("La descripción debe tener al menos 10 caracteres.");
        }
    }

    private function validarPrecio(float $precio): void
    {
        if (!is_numeric($precio)) {
            throw new InvalidArgumentException("El precio debe ser numérico.");
        }

        if ($precio < self::PRECIO_MINIMO || $precio > self::PRECIO_MAXIMO) {
            throw new InvalidArgumentException(
                "El precio debe estar entre $" . self::PRECIO_MINIMO .
                " y $" . self::PRECIO_MAXIMO . "."
            );
        }
    }

    private function validarCategoria(string $categoria): void
    {
        if (!in_array($categoria, self::CATEGORIAS_VALIDAS)) {
            throw new InvalidArgumentException("Categoría no válida para el servicio.");
        }
    }


    //Aplicamos los getters, la parte de encapsulación

    public function getId(): int
    {
        return $this->id;
    }

    public function getNombre(): string
    {
        return $this->nombre;
    }

    public function getDescripcion(): string
    {
        return $this->descripcion;
    }

    public function getPrecio(): float
    {
        return $this->precio;
    }

    public function getCategoria(): string
    {
        return $this->categoria;
    }
}