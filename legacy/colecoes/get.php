<?php
header('Content-Type: application/json');
require_once '../class/connection.php';

try {
    if (!isset($_GET['action'])) {
        throw new Exception('Ação não especificada');
    }

    switch ($_GET['action']) {
        case 'get_item':
            if (!isset($_GET['id'])) {
                throw new Exception('ID do item não fornecido');
            }

            $id = (int)$_GET['id'];

            $stmt = $pdo->prepare("
                SELECT 
                    id,
                    nome,
                    categoria,
                    valor,
                    referencia_foto,
                    ensaio_fotos,
                    idcolecao
                FROM items 
                WHERE id = ?
            ");

            $stmt->execute([$id]);
            $item = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$item) {
                throw new Exception('Item não encontrado');
            }

            echo json_encode([
                'success' => true,
                'data' => $item
            ]);
            break;

        default:
            throw new Exception('Ação inválida');
    }
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
