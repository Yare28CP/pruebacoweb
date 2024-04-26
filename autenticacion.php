<?php
session_start();

// Obtener credenciales del formulario
$username_input = $_POST['correo'] ?? '';
$password_input = $_POST['password'] ?? '';
$confirm_password_input = $_POST['confirm_password'] ?? '';

// Validar si se han enviado las credenciales
if(empty($username_input) || empty($password_input) || empty($confirm_password_input)) {
    // Redirigir al formulario de inicio de sesión con un mensaje de error
    header("Location: login.html?error=empty");
    exit(); // Terminar el script después de redirigir
}

// Verificar si las contraseñas coinciden
if ($password_input !== $confirm_password_input) {
    // Redirigir al formulario de inicio de sesión con un mensaje de error
    header("Location: login.html?error=password_mismatch");
    exit(); // Terminar el script después de redirigir
}

// Datos de conexión a la base de datos
$servername = "localhost";
$username_db = "root"; // Usuario root
$password_db = ""; // Sin contraseña para root
$dbname = "cotoweb"; // Nombre de la base de datos

// Crear conexión
$conn = new mysqli($servername, $username_db, $password_db, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Consulta para verificar las credenciales
$sql = "SELECT id, correo, password FROM usuarios WHERE correo=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username_input);
$stmt->execute();
$result = $stmt->get_result();

// Verificar si hay algún resultado
if ($result->num_rows > 0) {
    // Obtener el resultado de la consulta
    $row = $result->fetch_assoc();
    
    // Verificar si la contraseña coincide
    if (password_verify($password_input, $row['password'])) {
        // Inicio de sesión exitoso
        $_SESSION['loggedin'] = true;
        $_SESSION['id'] = $row['id'];
        $_SESSION['correo'] = $row['correo'];
        // Redireccionar al usuario a la página de inicio
        header("Location: index.php");
        exit(); // Terminar el script después de redirigir
    } else {
        // Credenciales incorrectas, redirigir de nuevo al formulario de inicio de sesión con un mensaje de error
        header("Location: login.html?error=incorrect");
        exit(); // Terminar el script después de redirigir
    }
} else {
    // Credenciales incorrectas, redirigir de nuevo al formulario de inicio de sesión con un mensaje de error
    header("Location: login.html?error=incorrect");
    exit(); // Terminar el script después de redirigir
}

// Cerrar la conexión y liberar recursos
$stmt->close();
$conn->close();
?>