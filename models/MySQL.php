<?php
//* Clase para gestionar la conexión a la base de datos
class MySQL
{

    //! Datos de conexión
    //? Clever Cloud
    // private $ipServidor = "bbhjjxpf2t984wp4v61z-mysql.services.clever-cloud.com";  // <- (localhost o host)
    // private $usuarioBase = "u0xmlaz3mc2of6jy";      // <- usuario (en local es root normalmente)
    // private $contrasena = "MOPhMg0QGXjsVNtoR2Nr";           // <- contraseña (local no tiene normalmente, va vacio)
    // private $nombreBaseDatos = "bbhjjxpf2t984wp4v61z";  // <- nombre de base de datos

    //? Local
    private $ipServidor = "bbhjjxpf2t984wp4v61z-mysql.services.clever-cloud.com";  // <- (localhost o host)
    private $usuarioBase = "u0xmlaz3mc2of6jy";      // <- usuario (en local es root normalmente)
    private $contrasena = "MOPhMg0QGXjsVNtoR2Nr";           // <- contraseña (local no tiene normalmente, va vacio)
    private $nombreBaseDatos = "bbhjjxpf2t984wp4v61z";  // <- nombre de base de datos


    private $conexion;

    //* Método para conectar a la base de datos
    public function conectar()
    {
        $this->conexion = mysqli_connect($this->ipServidor, $this->usuarioBase, $this->contrasena, $this->nombreBaseDatos);

        //! Validar si hubo un error en la conexión
        if (!$this->conexion) {
            die("Error al conectar a la base de datos: " . mysqli_connect_error());
        }

        //! Establecer codificación utf8
        mysqli_set_charset($this->conexion, "utf8");
    }

    //* Método para desconectar la base de datos
    public function desconectar()
    {
        if ($this->conexion) {
            mysqli_close($this->conexion);
        }
    }

    //* Método para ejecutar una consulta y devolver su resultado
    public function efectuarConsulta($consulta)
    {
        //! Verificar que la codificación sea utf8 antes de ejecutar
        mysqli_query($this->conexion, "SET NAMES 'utf8'");
        mysqli_query($this->conexion, "SET CHARACTER SET 'utf8'");

        $resultado = mysqli_query($this->conexion, $consulta);

        if (!$resultado) {
            echo "Error en la consulta: " . mysqli_error($this->conexion);
        }

        return $resultado;
    }
}
