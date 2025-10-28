<?php
header('Content-Type: application/json; charset=utf-8');

require_once 'config.php';

$event = filter_input(INPUT_GET, 'event', FILTER_SANITIZE_STRING);

if (!$event) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing event parameter']);
    exit();
}

try {
    $sql = "SELECT package_name, description, price FROM packages WHERE event_name = :event_name ORDER BY price ASC";
    $stmt = $pdo->prepare($sql);

    $stmt->execute(['event_name' => $event]);

    $packages = $stmt->fetchAll();

    echo json_encode($packages);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Query failed: ' . $e->getMessage()]);
    exit();
}
