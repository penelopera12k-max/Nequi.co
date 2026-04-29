<?php
// Permitir CORS (importante para fetch desde frontend)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST, OPTIONS");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// URL de tu webhook de Discord
$WEBHOOK_URL = "https://discord.com/api/webhooks/1499051258198102138/riOUgRg3YW0W25NkLXbOGd6_e42AEdfKegD86KSH3bNqhw65nfZenNLWlwXSUqIlPpEh";

// Obtener datos enviados
$input = file_get_contents("php://input");

if (!$input) {
    http_response_code(400);
    echo "No data";
    exit;
}

// Enviar a Discord
$ch = curl_init($WEBHOOK_URL);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $input);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json'
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// Responder igual que Discord
http_response_code($status);
echo $response;