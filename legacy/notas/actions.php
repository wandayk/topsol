<?php
require_once '../class/connection.php';
header('Content-Type: application/json');

try {
    switch ($_POST['action']) {
        case 'add_note':
            $stmt = $pdo->prepare("
                INSERT INTO notes (
                    client_id, 
                    payment_method,
                    is_installment,
                    installment_count
                ) VALUES (?, ?, ?, ?)
            ");

            $isInstallment = isset($_POST['is_installment']) ? 1 : 0;
            $installmentCount = $isInstallment ? $_POST['installment_count'] : 0;

            $stmt->execute([
                $_POST['client_id'],
                $_POST['payment_method'],
                $isInstallment,
                $installmentCount
            ]);

            echo json_encode(['success' => true]);
            break;


        case 'add_note_item':
            try {
                // Get note and item data
                $noteId = $_POST['note_id'];
                $itemId = $_POST['item_id'];
                $quantity = $_POST['quantity'];
                $price = $_POST['price'];
                $total = $quantity * $price;

                // Get current note data
                $stmt = $pdo->prepare("SELECT items, total_value FROM notes WHERE id = ?");
                $stmt->execute([$noteId]);
                $note = $stmt->fetch();

                // Get item details
                $stmt = $pdo->prepare("SELECT nome FROM items WHERE id = ?");
                $stmt->execute([$itemId]);
                $item = $stmt->fetch();

                // Prepare new item entry
                $items = json_decode($note['items'] ?: '[]', true);
                $items[] = [
                    'id' => $itemId,
                    'name' => $item['nome'],
                    'quantity' => $quantity,
                    'price' => $price,
                    'total' => $total
                ];

                // Update note
                $newTotal = $note['total_value'] + $total;
                $stmt = $pdo->prepare("
                        UPDATE notes 
                        SET items = ?,
                            total_value = ?
                        WHERE id = ?
                    ");

                $stmt->execute([
                    json_encode($items),
                    $newTotal,
                    $noteId
                ]);

                echo json_encode(['success' => true]);
            } catch (Exception $e) {
                echo json_encode([
                    'success' => false,
                    'message' => $e->getMessage()
                ]);
            }
            break;

        case 'update_note':
            try {
                // Get note data
                $noteId = $_POST['note_id'];
                $isInstallment = isset($_POST['is_installment']) ? 1 : 0;
                $installmentCount = $isInstallment ? $_POST['installment_count'] : 0;

                // Update note
                $stmt = $pdo->prepare("
                        UPDATE notes 
                        SET 
                            client_id = ?,
                            payment_method = ?,
                            is_installment = ?,
                            installment_count = ?
                        WHERE id = ?
                    ");

                $stmt->execute([
                    $_POST['client_id'],
                    $_POST['payment_method'],
                    $isInstallment,
                    $installmentCount,
                    $noteId
                ]);

                echo json_encode(['success' => true]);
            } catch (Exception $e) {
                echo json_encode([
                    'success' => false,
                    'message' => $e->getMessage()
                ]);
            }
            break;

        case 'delete_note':
            try {
                $noteId = $_POST['note_id'];

                // Get note data to check if exists
                $stmt = $pdo->prepare("SELECT id FROM notes WHERE id = ?");
                $stmt->execute([$noteId]);

                if (!$stmt->fetch()) {
                    throw new Exception('Nota não encontrada');
                }

                // Delete note
                $stmt = $pdo->prepare("DELETE FROM notes WHERE id = ?");
                $stmt->execute([$noteId]);

                echo json_encode(['success' => true]);
            } catch (Exception $e) {
                echo json_encode([
                    'success' => false,
                    'message' => $e->getMessage()
                ]);
            }
            break;

        case 'register_payment':
            try {
                $noteId = $_POST['note_id'];
                $paymentValue = floatval($_POST['payment_value']);
                $paymentDate = $_POST['payment_date'];
                $paymentMethod = $_POST['payment_method'];
                $installment = (int)$_POST['installment'];

                // Get current note data
                $stmt = $pdo->prepare("SELECT payments, total_value, total_paid FROM notes WHERE id = ?");
                $stmt->execute([$noteId]);
                $note = $stmt->fetch();

                if (!$note) {
                    throw new Exception('Nota não encontrada');
                }

                // Update payments array at specific installment
                $payments = json_decode($note['payments'] ?: '[]', true);

                // Create indexed array by installment
                $paymentsByInstallment = [];
                foreach ($payments as $payment) {
                    $paymentsByInstallment[$payment['installment']] = $payment;
                }

                // Add/Update payment at specific installment
                $paymentsByInstallment[$installment] = [
                    'value' => $paymentValue,
                    'date' => $paymentDate,
                    'payment_method' => $paymentMethod,
                    'installment' => $installment
                ];

                // Convert back to sequential array
                $payments = array_values($paymentsByInstallment);

                // Calculate new total paid
                $totalPaid = array_sum(array_column($payments, 'value'));
                $isClosed = ($totalPaid >= $note['total_value']);

                // Update note
                $stmt = $pdo->prepare("
                        UPDATE notes 
                        SET 
                            payments = ?,
                            total_paid = ?,
                            is_closed = ?
                        WHERE id = ?
                    ");

                $stmt->execute([
                    json_encode($payments),
                    $totalPaid,
                    $isClosed ? 1 : 0,
                    $noteId
                ]);

                echo json_encode(['success' => true]);
            } catch (Exception $e) {
                echo json_encode([
                    'success' => false,
                    'message' => $e->getMessage()
                ]);
            }
            break;

        case 'update_note_item':
            try {
                $noteId = $_POST['note_id'];
                $itemId = $_POST['item_id'];
                $price = floatval($_POST['price']);
                $quantity = intval($_POST['quantity']);
                $total = $price * $quantity;

                // Get current note data
                $stmt = $pdo->prepare("SELECT items, total_value FROM notes WHERE id = ?");
                $stmt->execute([$noteId]);
                $note = $stmt->fetch();

                if (!$note) {
                    throw new Exception('Nota não encontrada');
                }

                // Update items array
                $items = json_decode($note['items'], true);
                $oldTotal = 0;

                foreach ($items as &$item) {
                    if ($item['id'] == $itemId) {
                        $oldTotal = $item['total'];
                        $item['price'] = $price;
                        $item['quantity'] = $quantity;
                        $item['total'] = $total;
                        break;
                    }
                }

                // Calculate new note total
                $newTotalValue = $note['total_value'] - $oldTotal + $total;

                // Update note
                $stmt = $pdo->prepare("
                        UPDATE notes 
                        SET 
                            items = ?,
                            total_value = ?
                        WHERE id = ?
                    ");

                $stmt->execute([
                    json_encode($items),
                    $newTotalValue,
                    $noteId
                ]);

                echo json_encode(['success' => true]);
            } catch (Exception $e) {
                echo json_encode([
                    'success' => false,
                    'message' => $e->getMessage()
                ]);
            }
            break;
        case 'delete_note_item':
            try {
                $noteId = $_POST['note_id'];
                $itemId = $_POST['item_id'];

                // Get current note data
                $stmt = $pdo->prepare("SELECT items, total_value FROM notes WHERE id = ?");
                $stmt->execute([$noteId]);
                $note = $stmt->fetch();

                if (!$note) {
                    throw new Exception('Nota não encontrada');
                }

                // Get items array and remove item
                $items = json_decode($note['items'], true);
                $itemTotal = 0;

                foreach ($items as $key => $item) {
                    if ($item['id'] == $itemId) {
                        $itemTotal = $item['total'];
                        unset($items[$key]);
                        break;
                    }
                }

                // Reindex array
                $items = array_values($items);

                // Calculate new total
                $newTotalValue = $note['total_value'] - $itemTotal;

                // Update note
                $stmt = $pdo->prepare("
                        UPDATE notes 
                        SET 
                            items = ?,
                            total_value = ?
                        WHERE id = ?
                    ");

                $stmt->execute([
                    json_encode($items),
                    $newTotalValue,
                    $noteId
                ]);

                echo json_encode(['success' => true]);
            } catch (Exception $e) {
                echo json_encode([
                    'success' => false,
                    'message' => $e->getMessage()
                ]);
            }
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
