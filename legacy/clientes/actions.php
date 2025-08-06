<?php

header('Content-Type: application/json');

/*
if (!isset($_SESSION['username'])) {
    die(json_encode(['success' => false, 'message' => 'Unauthorized']));
}
*/

require_once '../class/connection.php';

try {
    $action = $_POST['action'] ?? '';

    switch($action) {
        case 'add':
            $stmt = $pdo->prepare("INSERT INTO clients (name, origin, email, phone, address) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([
                $_POST['name'],
                $_POST['origin'],
                $_POST['email'],
                $_POST['phone'],
                $_POST['address']
            ]);
            echo json_encode(['success' => true]);
            break;

        case 'update':
            $stmt = $pdo->prepare("UPDATE clients SET name = ?, origin = ?, email = ?, phone = ?, address = ? WHERE id = ?");
            $stmt->execute([
                $_POST['name'],
                $_POST['origin'],
                $_POST['email'],
                $_POST['phone'],
                $_POST['address'],
                $_POST['id']
            ]);
            echo json_encode(['success' => true]);
            break;

        case 'delete':
            $stmt = $pdo->prepare("DELETE FROM clients WHERE id = ?");
            $stmt->execute([$_POST['id']]);
            echo json_encode(['success' => true]);
            break;

        default:
            echo json_encode(['success' => false, 'message' => 'Invalid action']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
} 