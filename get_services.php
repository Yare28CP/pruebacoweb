<?php
// Datos de conexión a la base de datos
$servername = "localhost";
$username = "root"; // Usuario root
$password = ""; // Sin contraseña para root
$dbname = "cotoweb"; // Cambia esto por el nombre de tu base de datos

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
} else {
    echo "Conexión exitosa<br>"; // Verificar si se conectó correctamente
}

// Consulta para obtener los servicios
$sql = "SELECT * FROM servicios";
echo "Consulta SQL: " . $sql . "<br>"; // Muestra la consulta SQL
$result = $conn->query($sql) or die("Error en la consulta: " . $conn->error);

// Verificar si la consulta devuelve resultados
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<div class='service'>
                <img src='" . $row["imagen_url"] . "' alt='" . $row["nombre"] . "'>
                <div class='service-info'>
                    <h2>" . $row["nombre"] . "</h2>
                    <p>" . $row["descripcion"] . "</p>
                    <p>Precio: $" . $row["precio"] . "</p>
                    <button class='btn' onclick='addToCart(" . $row["id"] . ")'>Agregar al Carrito</button>
                </div>
              </div>";
    }
} else {
    echo "No se encontraron servicios.";
}

$conn->close();
?>
