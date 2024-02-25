<?php

class CuentaBancaria {
    private $id;
    private $saldo;

    public function __construct($id, $saldoInicial) {
        $this->id = $id;
        $this->saldo = $saldoInicial;
    }

    public function depositar($monto) {
        $this->saldo += $monto;
    }

    public function retirar($monto) {
        if ($this->saldo >= $monto) {
            $this->saldo -= $monto;
            return true;
        }
        return false;
    }

    public function consultarSaldo() {
        return $this->saldo;
    }

    public function getId() {
        return $this->id;
    }
}

class Banco {
    private $cuentas = [];

    public function abrirCuenta($cuenta) {
        $this->cuentas[$cuenta->getId()] = $cuenta;
    }

    public function realizarTransaccion($idCuenta, $monto, $tipo) {
        if (isset($this->cuentas[$idCuenta])) {
            $cuenta = $this->cuentas[$idCuenta];
            switch ($tipo) {
                case 'Deposito':
                    $cuenta->depositar($monto);
                    echo "Depósito realizado: Cuenta $idCuenta, Monto: $monto\n";
                    break;
                case 'Retiro':
                    if (!$cuenta->retirar($monto)) {
                        echo "No se pudo retirar: Saldo insuficiente en la cuenta $idCuenta\n";
                    } else {
                        echo "Retiro realizado: Cuenta $idCuenta, Monto: $monto\n";
                    }
                    break;
            }
        }
    }

    public function imprimirSaldoCuentas() {
        foreach ($this->cuentas as $cuenta) {
            echo "Cuenta " . $cuenta->getId() . ", Saldo: " . $cuenta->consultarSaldo() . "\n";
        }
    }
}

// Simulación de ejecución
$banco = new Banco();
$banco->abrirCuenta(new CuentaBancaria(1, 1000));
$banco->abrirCuenta(new CuentaBancaria(2, 1500));

echo "Saldo inicial de las cuentas:\n";
$banco->imprimirSaldoCuentas();

// Simular un número mayor de transacciones de manera aleatoria
$numeroDeTransacciones = 10; // Define cuántas transacciones quieres simular
for ($i = 0; $i < $numeroDeTransacciones; $i++) {
    $idCuentaAleatoria = rand(1, 2); // Suponiendo que solo tienes 2 cuentas
    $montoAleatorio = rand(50, 500); // Monto aleatorio entre 50 y 500
    $tipoTransaccion = (rand(0, 1) == 0) ? 'Deposito' : 'Retiro';

    $banco->realizarTransaccion($idCuentaAleatoria, $montoAleatorio, $tipoTransaccion);
}

echo "\nSaldo final de las cuentas:\n";
$banco->imprimirSaldoCuentas();
