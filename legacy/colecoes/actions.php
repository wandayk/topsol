<?php

header('Content-Type: application/json');

require_once '../class/connection.php';

try {
    $action = $_POST['action'] ?? '';

    switch ($action) {
        case 'add':
            $stmt = $pdo->prepare("INSERT INTO collections (nome, data_criacao, data_lancamento) VALUES (?, ?, ?)");
            $stmt->execute([
                $_POST['nome'],
                $_POST['data_criacao'],
                $_POST['data_lancamento']
            ]);
            echo json_encode(['success' => true]);
            break;

        case 'update':
            $stmt = $pdo->prepare("UPDATE collections SET nome = ?, data_criacao = ?, data_lancamento = ? WHERE id = ?");
            $stmt->execute([
                $_POST['nome'],
                $_POST['data_criacao'],
                $_POST['data_lancamento'],
                $_POST['id']
            ]);
            echo json_encode(['success' => true]);
            break;

        case 'delete':
            $stmt = $pdo->prepare("DELETE FROM collections WHERE id = ?");
            $stmt->execute([$_POST['id']]);
            echo json_encode(['success' => true]);
            break;

        case 'add_item':
            // Create upload directories
            $uploadDir = __DIR__ . '/uploads/';
            $referenciaDir = $uploadDir . 'referencias/';
            $ensaioDir = $uploadDir . 'ensaios/';

            if (!file_exists($referenciaDir)) mkdir($referenciaDir, 0777, true);
            if (!file_exists($ensaioDir)) mkdir($ensaioDir, 0777, true);

            // Process reference photos
            $referenciaPaths = [];
            if (isset($_FILES['referencia'])) {
                foreach ($_FILES['referencia']['tmp_name'] as $key => $tmp_name) {
                    if ($_FILES['referencia']['error'][$key] === 0) {
                        $filename = uniqid() . '_' . basename($_FILES['referencia']['name'][$key]);
                        $referenciaPath = 'colecoes/uploads/referencias/' . $filename;
                        move_uploaded_file($tmp_name, $referenciaDir . $filename);
                        $referenciaPaths[] = $referenciaPath;
                    }
                }
            }

            // Process photoshoot images
            $ensaiosPaths = [];
            if (isset($_FILES['ensaio'])) {
                foreach ($_FILES['ensaio']['tmp_name'] as $key => $tmp_name) {
                    if ($_FILES['ensaio']['error'][$key] === 0) {
                        $filename = uniqid() . '_' . basename($_FILES['ensaio']['name'][$key]);
                        $ensaioPath = 'colecoes/uploads/ensaios/' . $filename;
                        move_uploaded_file($tmp_name, $ensaioDir . $filename);
                        $ensaiosPaths[] = $ensaioPath;
                    }
                }
            }

            // Insert item into database
            $stmt = $pdo->prepare("
                INSERT INTO items (
                    nome, 
                    categoria, 
                    valor, 
                    idcolecao, 
                    referencia_foto,
                    ensaio_fotos
                ) VALUES (?, ?, ?, ?, ?, ?)
            ");

            $stmt->execute([
                $_POST['nome'],
                $_POST['categoria'],
                $_POST['valor'],
                $_POST['idcolecao'],
                json_encode($referenciaPaths),
                json_encode($ensaiosPaths)
            ]);


            echo json_encode(['success' => true]);
            break;

        case 'remove_image':
            try {
                if (!isset($_POST['item_id']) || !isset($_POST['image_path']) || !isset($_POST['image_type'])) {
                    throw new Exception('Parâmetros inválidos');
                }

                // Get current item data
                $stmt = $pdo->prepare("SELECT referencia_foto, ensaio_fotos FROM items WHERE id = ?");
                $stmt->execute([$_POST['item_id']]);
                $item = $stmt->fetch();

                if (!$item) {
                    throw new Exception('Item não encontrado');
                }

                // Update appropriate image array
                if ($_POST['image_type'] === 'referencia') {
                    $images = json_decode($item['referencia_foto'], true) ?: [];
                    $images = array_filter($images, fn($img) => $img !== $_POST['image_path']);

                    $stmt = $pdo->prepare("UPDATE items SET referencia_foto = ? WHERE id = ?");
                    $stmt->execute([json_encode(array_values($images)), $_POST['item_id']]);
                } else {
                    $images = json_decode($item['ensaio_fotos'], true) ?: [];
                    $images = array_filter($images, fn($img) => $img !== $_POST['image_path']);

                    $stmt = $pdo->prepare("UPDATE items SET ensaio_fotos = ? WHERE id = ?");
                    $stmt->execute([json_encode(array_values($images)), $_POST['item_id']]);
                }

                // Delete physical file
                //$filePath = __DIR__ . '\\' . str_replace('colecoes/', '', $_POST['image_path']);

                $filePath = str_replace('colecoes', '',  __DIR__) . str_replace('/', '\\', $_POST['image_path']);

                if (file_exists($filePath)) {
                    unlink($filePath);
                }

                echo json_encode(['success' => true]);
            } catch (Exception $e) {
                echo json_encode([
                    'success' => false,
                    'message' => $e->getMessage()
                ]);
            }
            break;

        case 'update_item':
            try {
                // Get current item data
                $stmt = $pdo->prepare("SELECT referencia_foto, ensaio_fotos FROM items WHERE id = ?");
                $stmt->execute([$_POST['item_id']]);
                $currentItem = $stmt->fetch();

                // Create upload directories
                $uploadDir = __DIR__ . '/uploads/';
                $referenciaDir = $uploadDir . 'referencias/';
                $ensaioDir = $uploadDir . 'ensaios/';

                $referenciaPaths = json_decode($currentItem['referencia_foto'] ?? '[]', true);
                $ensaiosPaths = json_decode($currentItem['ensaio_fotos'] ?? '[]', true);

                // Delete old reference photos only if new ones are uploaded
                if (isset($_FILES['referencia']) && !empty($_FILES['referencia']['tmp_name'][0])) {
                    foreach ($referenciaPaths as $oldPath) {
                        $filePath = str_replace('colecoes', '',  __DIR__) . str_replace('/', '\\', $oldPath);
                        if (file_exists($filePath)) {
                            unlink($filePath);
                        }
                    }
                    $referenciaPaths = [];

                    // Process new reference photos
                    foreach ($_FILES['referencia']['tmp_name'] as $key => $tmp_name) {
                        if ($_FILES['referencia']['error'][$key] === 0) {
                            $filename = uniqid() . '_' . basename($_FILES['referencia']['name'][$key]);
                            $referenciaPath = 'colecoes/uploads/referencias/' . $filename;
                            move_uploaded_file($tmp_name, $referenciaDir . $filename);
                            $referenciaPaths[] = $referenciaPath;
                        }
                    }
                }

                // Delete old ensaio photos only if new ones are uploaded
                if (isset($_FILES['ensaio']) && !empty($_FILES['ensaio']['tmp_name'][0])) {
                    foreach ($ensaiosPaths as $oldPath) {
                        $filePath = str_replace('colecoes', '',  __DIR__) . str_replace('/', '\\', $oldPath);
                        if (file_exists($filePath)) {
                            unlink($filePath);
                        }
                    }
                    $ensaiosPaths = [];

                    // Process new photoshoot images
                    foreach ($_FILES['ensaio']['tmp_name'] as $key => $tmp_name) {
                        if ($_FILES['ensaio']['error'][$key] === 0) {
                            $filename = uniqid() . '_' . basename($_FILES['ensaio']['name'][$key]);
                            $ensaioPath = 'colecoes/uploads/ensaios/' . $filename;
                            move_uploaded_file($tmp_name, $ensaioDir . $filename);
                            $ensaiosPaths[] = $ensaioPath;
                        }
                    }
                }

                // Update item in database
                $stmt = $pdo->prepare("
                        UPDATE items 
                        SET 
                            nome = ?,
                            categoria = ?,
                            valor = ?,
                            referencia_foto = ?,
                            ensaio_fotos = ?
                        WHERE id = ?
                    ");

                $stmt->execute([
                    $_POST['nome'],
                    $_POST['categoria'],
                    $_POST['valor'],
                    json_encode(array_values($referenciaPaths)),
                    json_encode(array_values($ensaiosPaths)),
                    $_POST['item_id']
                ]);

                echo json_encode(['success' => true]);
            } catch (Exception $e) {
                echo json_encode([
                    'success' => false,
                    'message' => $e->getMessage()
                ]);
            }
            break;

        case 'delete_item':
            try {
                // Get item data first to get file paths
                $stmt = $pdo->prepare("SELECT referencia_foto, ensaio_fotos FROM items WHERE id = ?");
                $stmt->execute([$_POST['item_id']]);
                $item = $stmt->fetch();

                if ($item) {
                    // Delete reference photos
                    $referencias = json_decode($item['referencia_foto'], true) ?: [];
                    foreach ($referencias as $path) {
                        $filePath = str_replace('colecoes', '',  __DIR__) . str_replace('/', '\\', $path);
                        if (file_exists($filePath)) {
                            unlink($filePath);
                        }
                    }

                    // Delete ensaio photos
                    $ensaios = json_decode($item['ensaio_fotos'], true) ?: [];
                    foreach ($ensaios as $path) {
                        $filePath = str_replace('colecoes', '',  __DIR__) .  str_replace('/', '\\', $path);
                        if (file_exists($filePath)) {
                            unlink($filePath);
                        }
                    }

                    // Delete item from database
                    $stmt = $pdo->prepare("DELETE FROM items WHERE id = ?");
                    $stmt->execute([$_POST['item_id']]);

                    echo json_encode(['success' => true]);
                } else {
                    throw new Exception('Item não encontrado');
                }
            } catch (Exception $e) {
                echo json_encode([
                    'success' => false,
                    'message' => $e->getMessage()
                ]);
            }
            break;

        default:
            echo json_encode(['success' => false, 'message' => 'Invalid action']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
