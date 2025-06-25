<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Manejar preflight OPTIONS
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

// Base de datos de cuentas autorizadas
$cuentas_autorizadas = [
    '13608150' => ['tipo' => 'VIP', 'expira' => '2025-10-25', 'max_posiciones' => 10],
    '68020274' => ['tipo' => 'VIP', 'expira' => '2025-10-25', 'max_posiciones' => 10],
    '173145894' => ['tipo' => 'VIP', 'expira' => '2025-10-25', 'max_posiciones' => 10],
    '55555556' => ['tipo' => 'VIP', 'expira' => '2025-10-25', 'max_posiciones' => 10],
    '307599027' => ['tipo' => 'BASICO', 'expira' => '2025-06-15', 'max_posiciones' => 5],
    '305699555' => ['tipo' => 'DEMO', 'expira' => '2024-12-31', 'max_posiciones' => 3],
    '307082978' => ['tipo' => 'VIP', 'expira' => '2025-10-25', 'max_posiciones' => 10],
    '307050983' => ['tipo' => 'BASICO', 'expira' => '2025-08-20', 'max_posiciones' => 5],
    '30711665' => ['tipo' => 'VIP', 'expira' => '2025-10-25', 'max_posiciones' => 10],
    '68020274' => ['tipo' => 'DEMO', 'expira' => '2024-12-31', 'max_posiciones' => 3],
    '307239474' => ['tipo' => 'VIP', 'expira' => '2025-10-25', 'max_posiciones' => 10]
];

// Obtener datos
$account = $_POST['account'] ?? '0';
$broker = $_POST['broker'] ?? '';
$ea_version = $_POST['ea_version'] ?? '';


// Al inicio del archivo PHP, agregar:
$account = $_GET['account'] ?? $_POST['account'] ?? '0';
$broker = $_GET['broker'] ?? $_POST['broker'] ?? '';
$ea_version = $_GET['ea_version'] ?? $_POST['ea_version'] ?? '';

// Log básico (opcional)
$log_entry = date('Y-m-d H:i:s') . " - Account: $account - Broker: $broker - Version: $ea_version\n";
@file_put_contents('license_checks.log', $log_entry, FILE_APPEND | LOCK_EX);

// Verificar cuenta
if (isset($cuentas_autorizadas[$account])) {
    $licencia = $cuentas_autorizadas[$account];
    $fecha_expira = strtotime($licencia['expira']);
    
    if (time() <= $fecha_expira) {
        echo json_encode([
            'status' => 'VALIDA',
            'cuenta' => $account,
            'tipo' => $licencia['tipo'],
            'expira' => $licencia['expira'],
            'max_posiciones' => $licencia['max_posiciones'],
            'mensaje' => 'Licencia válida',
            'servidor_tiempo' => date('Y-m-d H:i:s')
        ]);
    } else {
        echo json_encode([
            'status' => 'EXPIRADA',
            'cuenta' => $account,
            'expira' => $licencia['expira'],
            'mensaje' => 'Licencia expirada'
        ]);
    }
} else {
    echo json_encode([
        'status' => 'INVALIDA',
        'cuenta' => $account,
        'mensaje' => 'Cuenta no autorizada'
    ]);
}
?>