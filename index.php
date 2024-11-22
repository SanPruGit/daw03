<?php
header("Access-Control-Allow-Origin: *"); // Permitir cualquier origen
header("Access-Control-Allow-Methods: GET, POST, OPTIONS"); // Permitir métodos
header("Access-Control-Allow-Headers: Content-Type"); // Permitir encabezados
header("Content-Type: application/json");


$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$operacion = basename($uri);
$num1 = isset($_GET['num1']) ? (float) $_GET['num1'] : null;
$num2 = isset($_GET['num2']) ? (float) $_GET['num2'] : null;
$resultado = null;


if ($num1 === null || $num2 === null) {
    echo json_encode(["error" => "Falta el número 1 y/o el número 2"]);
    exit;
}


switch ($operacion) {
    case 'sumar':
        $resultado = $num1 + $num2;
        break;
    case 'restar':
        $resultado = $num1 - $num2;
        break;
    case 'multiplicar':
        $resultado = $num1 * $num2;
        break;
    case 'dividir':
        if ($num2 == 0) {
            echo json_encode(["error" => "No se puede dividir entre cero"]);
            exit;
        } else {
            $resultado = $num1 / $num2;
        }
        break;
    default:
        echo json_encode(["error" =>  $operacion]);
        exit;
}

error_log("El resultado es: " . $resultado);

echo json_encode(["resultado" => $resultado]);