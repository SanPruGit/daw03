<?php
use PHPUnit\Framework\TestCase;

class CalculadoraTest extends TestCase
{
    /**
     * Captura la salida del script.
     */
    private function captureOutput($callback)
    {
        ob_start(); // Inicia el buffer de salida.
        $callback(); // Ejecuta el callback.
        $output = ob_get_clean(); // Captura la salida y limpia el buffer.
        return $output;
    }

    /**
     * Simula una solicitud GET.
     */
    private function simulateRequest($operacion, $num1, $num2)
    {
        $_SERVER['REQUEST_URI'] = "/$operacion"; // Simula la URI.
        $_GET['num1'] = $num1;                  // Simula el primer parámetro.
        $_GET['num2'] = $num2;                  // Simula el segundo parámetro.

        return $this->captureOutput(function () {
            include __DIR__ . '/../index.php'; // Incluye el archivo principal.
        });
    }

    public function testSumar()
    {
        $response = $this->simulateRequest('sumar', 5, 3);
        $this->assertJson($response);
        $data = json_decode($response, true);
        $this->assertArrayHasKey('resultado', $data);
        $this->assertEquals(8, $data['resultado']);
    }

    public function testRestar()
    {
        $response = $this->simulateRequest('restar', 5, 3);
        $this->assertJson($response);
        $data = json_decode($response, true);
        $this->assertArrayHasKey('resultado', $data);
        $this->assertEquals(2, $data['resultado']);
    }

    public function testMultiplicar()
    {
        $response = $this->simulateRequest('multiplicar', 5, 3);
        $this->assertJson($response);
        $data = json_decode($response, true);
        $this->assertArrayHasKey('resultado', $data);
        $this->assertEquals(15, $data['resultado']);
    }

    public function testDividir()
    {
        $response = $this->simulateRequest('dividir', 6, 2);
        $this->assertJson($response);
        $data = json_decode($response, true);
        $this->assertArrayHasKey('resultado', $data);
        $this->assertEquals(3, $data['resultado']);
    }

    public function testDividirEntreCero()
    {
        $response = $this->simulateRequest('dividir', 6, 0);
        $this->assertJson($response);
        $data = json_decode($response, true);
        $this->assertArrayHasKey('error', $data);
        $this->assertEquals('No se puede dividir entre cero', $data['error']);
    }

    public function testFaltaParametros()
    {
        $response = $this->simulateRequest('sumar', null, 3);
        $this->assertJson($response);
        $data = json_decode($response, true);
        $this->assertArrayHasKey('error', $data);
        $this->assertEquals('Falta el número 1 y/o el número 2', $data['error']);
    }

    public function testOperacionInvalida()
    {
        $response = $this->simulateRequest('potencia', 2, 3);
        $this->assertJson($response);
        $data = json_decode($response, true);
        $this->assertArrayHasKey('error', $data);
        $this->assertEquals('potencia', $data['error']);
    }
}