<?php


header('Content-Type: application/json');

require_once '../class/connection.php';

try {
    $action = $_POST['action'] ?? '';

    switch ($action) {

        case 'add_expense':
            try {
                // Get form data
                $description = $_POST['description'];
                $value = floatval($_POST['value']);
                $date = $_POST['expense_date'];
                $collectionId = !empty($_POST['collection_id']) ? $_POST['collection_id'] : null;

                // Validate required fields
                if (empty($description) || empty($value) || empty($date)) {
                    throw new Exception('Todos os campos obrigatórios devem ser preenchidos');
                }

                // Insert expense
                $stmt = $pdo->prepare("
                INSERT INTO expenses 
                (description, value, collection_id, expense_date) 
                VALUES (?, ?, ?, ?)
            ");

                $stmt->execute([
                    $description,
                    $value,
                    $collectionId,
                    $date
                ]);

                echo json_encode([
                    'success' => true,
                    'message' => 'Despesa adicionada com sucesso'
                ]);
            } catch (Exception $e) {
                echo json_encode([
                    'success' => false,
                    'message' => $e->getMessage()
                ]);
            }
            break;

        case 'get_expense':
            try {
                $expenseId = $_POST['expense_id'];

                $stmt = $pdo->prepare("
                        SELECT 
                            e.*,
                            c.nome as nome 
                        FROM expenses e
                        LEFT JOIN collections c ON e.collection_id = c.id
                        WHERE e.id = ?
                    ");

                $stmt->execute([$expenseId]);
                $expense = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$expense) {
                    throw new Exception('Despesa não encontrada');
                }

                echo json_encode([
                    'success' => true,
                    'data' => $expense
                ]);
            } catch (Exception $e) {
                echo json_encode([
                    'success' => false,
                    'message' => $e->getMessage()
                ]);
            }
            break;

        case 'update_expense':
            try {
                $expenseId = $_POST['expense_id'];
                $description = $_POST['description'];
                $value = floatval($_POST['value']);
                $date = $_POST['expense_date'];
                $collectionId = !empty($_POST['collection_id']) ? $_POST['collection_id'] : null;

                // Validate required fields
                if (empty($description) || empty($value) || empty($date)) {
                    throw new Exception('Todos os campos obrigatórios devem ser preenchidos');
                }

                // Update expense
                $stmt = $pdo->prepare("
                        UPDATE expenses 
                        SET 
                            description = ?,
                            value = ?,
                            collection_id = ?,
                            expense_date = ?
                        WHERE id = ?
                    ");

                $stmt->execute([
                    $description,
                    $value,
                    $collectionId,
                    $date,
                    $expenseId
                ]);

                echo json_encode([
                    'success' => true,
                    'message' => 'Despesa atualizada com sucesso'
                ]);
            } catch (Exception $e) {
                echo json_encode([
                    'success' => false,
                    'message' => $e->getMessage()
                ]);
            }
            break;

        case 'delete_expense':
            try {
                $expenseId = $_POST['expense_id'];

                // Check if expense exists
                $stmt = $pdo->prepare("SELECT id FROM expenses WHERE id = ?");
                $stmt->execute([$expenseId]);

                if (!$stmt->fetch()) {
                    throw new Exception('Despesa não encontrada');
                }

                // Delete expense
                $stmt = $pdo->prepare("DELETE FROM expenses WHERE id = ?");
                $stmt->execute([$expenseId]);

                echo json_encode([
                    'success' => true,
                    'message' => 'Despesa excluída com sucesso'
                ]);
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
