<?php
include '\xampp\htdocs\class\connection.php';

// Get selected month and year
$month = isset($_POST['month']) ? $_POST['month'] : date('n');
$year = isset($_POST['year']) ? $_POST['year'] : date('Y');

// Get all notes for selected month
$stmt = $pdo->prepare("
        SELECT payments
        FROM notes 
        WHERE payments IS NOT NULL 
        AND JSON_EXTRACT(payments, '$[0].date') LIKE ?
    ");

$datePattern = "%$year-" . str_pad($month, 2, '0', STR_PAD_LEFT) . "%";
$stmt->execute([$datePattern]);
$notes = $stmt->fetchAll();

// Calculate total income from payments
$totalIncome = 0;

foreach ($notes as $note) {
    $payments = json_decode($note['payments'], true);
    if ($payments) {
        foreach ($payments as $payment) {
            $paymentDate = date('Y-m', strtotime($payment['date']));
            $selectedDate = "$year-" . str_pad($month, 2, '0', STR_PAD_LEFT);

            if ($paymentDate === $selectedDate) {
                $totalIncome += floatval($payment['value']);
            }
        }
    }
}

// Get total expenses for selected month
$stmt = $pdo->prepare("
        SELECT COALESCE(SUM(value), 0) as total_expenses
        FROM expenses
        WHERE MONTH(expense_date) = ? AND YEAR(expense_date) = ?
    ");
$stmt->execute([$month, $year]);
$totalExpenses = $stmt->fetch()['total_expenses'];

// Calculate balance
$balance = $totalIncome - $totalExpenses;

// Format numbers
$formattedIncome = number_format($totalIncome, 2, ',', '.');
$formattedExpenses = number_format($totalExpenses, 2, ',', '.');
$formattedBalance = number_format($balance, 2, ',', '.');

?>

<div style="display: flex;width: 50%;">
    <div>
        <div style="display: flex;gap: 5px;padding-top: 10px;">
            <img src="../img/financeiro.png" alt="" width="16" height="16">
            <h4 style="font-size: 16px;font-weight: 300;color: #000;margin: 0;padding: 0;margin-top: -2px;">SALDO</h4>
        </div>
        <h2>R$ <?php echo $formattedBalance; ?></h2>
    </div>
</div>
<div style="width:100%;">
    <div style="display: flex;gap: 5px;padding-top: 10px;">
        <img src="../img/expense.png" alt="" width="16" height="16">
        <h4 style="font-size: 16px;font-weight: 300;color: #000;margin: 0;padding: 0;margin-top: -2px;">RECEITAS</h4>
    </div>
    <h2>R$ <?php echo $formattedIncome; ?></h2>
</div>
<div class="frame__despesas" style="width:70%;">
    <div class="frame__despesas__header">
        <div style="display: flex;justify-content: space-between;align-items: center;">
            <div style="display: flex;gap: 5px;">
                <img src="../img/expense.png" alt="" width="16" height="16">
                <h4 style="font-size: 16px;font-weight: 300;color: #000;margin: 0;padding: 0;margin-top: -2px;">DESPESAS</h4>
            </div>
            <button type="button" class="button-name" data-bs-toggle="modal" data-bs-target="#addExpenseModal" style="padding: 13px 5px;margin: 0;font-size: 11px;font-weight: 700;color: whitesmoke;background-color: #ff7171;height: 0;margin-top: 3px;">
                <span style="font-size: 20px;font-weight: 700;line-height: 1;margin-bottom: 7px;margin-right: 10px;">+</span>
                <span style="margin-bottom: 4px;font-size: 12px;font-weight: 600;margin-right: 8px;">DESPESAS</span>
            </button>
        </div>
        <h2 style="margin-top: -2px;font-size: 25px;padding-bottom: 10px;color: #ff6c6c;">R$ <?php echo $formattedExpenses; ?></h2>
    </div>
    <div class="frame__despesas__content">
        <?php
        $month = isset($_POST['month']) ? $_POST['month'] : date('n');
        $year = isset($_POST['year']) ? $_POST['year'] : date('Y');

        $stmt = $pdo->prepare("
    SELECT 
        e.*,
        c.nome as collection_name
    FROM expenses e
    LEFT JOIN collections c ON e.collection_id = c.id
    WHERE MONTH(e.expense_date) = ? 
    AND YEAR(e.expense_date) = ?
    ORDER BY e.expense_date DESC
");

        $stmt->execute([$month, $year]);
        $expenses = $stmt->fetchAll();
        ?>

        <div class="frame__despesas__content__items">
            <?php if (empty($expenses)): ?>
                <span style="text-align: center;display: block;color: #666;font-size: 14px;padding: 20px;">
                    Nenhuma despesa registrada
                </span>
            <?php else: ?>
                <?php foreach ($expenses as $expense): ?>
                    <div class="frame__despesas__content__item">
                        <div style=" display: flex; flex-direction: column;">
                            <?php if ($expense['collection_name']): ?>
                                <span style="font-size: 12px; color: #666;text-transform: uppercase;font-weight: 800;">
                                    <?php echo htmlspecialchars($expense['collection_name']); ?>
                                </span>
                            <?php endif; ?>
                            <span style="font-weight: 500; color: black;font-size: 12px;text-transform: uppercase;">
                                <?php echo htmlspecialchars($expense['description']); ?>
                            </span>

                            <span style="font-size: 12px; color: #666;">
                                <?php echo date('d/m/Y', strtotime($expense['expense_date'])); ?>
                            </span>
                        </div>
                        <div style="display: flex;flex-direction: column;height: 100%;justify-content: center;">
                            <div class="despesas__button">
                                <button class="btn btn-sm btn-primary edit-despesa" data-id="<?php echo $expense['id']; ?>" data-bs-toggle="modal" data-bs-target="#editDespesaModal" style="padding: 3px;margin: 0;background: #5050dbb2;border: 0;display: flex;justify-content: center;width: fit-content;"><img src="img/edit_white.svg" alt="" width="15"></button>
                                <button class="btn btn-sm btn-danger delete-despesa" data-id="<?php echo $expense['id']; ?>" style="padding: 3px;margin: 0;background:rgba(219, 80, 80, 0.7);border: 0;display: flex;justify-content: center;width: fit-content;"><img src="img/delete_white.svg" alt="" width="15"></button>
                            </div>
                            <span style="font-weight: 600; color: #ff4444;">
                                R$ <?php echo number_format($expense['value'], 2, ',', '.'); ?>
                            </span>
                        </div>
                    </div>
                <?php endforeach; ?>

            <?php endif; ?>
        </div>
    </div>
</div>


</div>

<!-- Add Expense Modal -->
<div class="modal fade" id="addExpenseModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="padding-left: 40px;">
                <div style="display: flex;flex-direction: column;">
                    <span style="font-size: 20px;font-weight: 800;color: #525252;">ADICIONAR DESPESA</span>
                    <span style="font-size: 14px;font-weight: 600;color: gray;">NOVA DESPESA</span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body" style="padding: 20px 40px;">
                <form id="addExpenseForm">
                    <div class="mb-4">
                        <label class="form-label" style="font-size: 12px;color: gray;font-weight: 600;">DESCRIÇÃO</label>
                        <input type="text"
                            class="form-control"
                            name="description"
                            required
                            style="border: 1px solid #80808075;border-radius: 5px;font-size: 14px;font-weight: 500;">
                    </div>

                    <div class="mb-4">
                        <label class="form-label" style="font-size: 12px;color: gray;font-weight: 600;">VALOR</label>
                        <input type="number"
                            step="0.01"
                            class="form-control"
                            name="value"
                            required
                            style="border: 1px solid #80808075;border-radius: 5px;font-size: 14px;font-weight: 500;">
                    </div>

                    <div class="mb-4">
                        <label class="form-label" style="font-size: 12px;color: gray;font-weight: 600;">DATA</label>
                        <input type="date"
                            class="form-control"
                            name="expense_date"
                            required
                            value="<?php echo date('Y-m-d'); ?>"
                            style="border: 1px solid #80808075;border-radius: 5px;font-size: 14px;font-weight: 500;">
                    </div>

                    <div class="mb-4">
                        <label class="form-label" style="font-size: 12px;color: gray;font-weight: 600;">COLEÇÃO (OPCIONAL)</label>
                        <select class="form-select"
                            name="collection_id"
                            style="border: 1px solid #80808075;border-radius: 5px;font-size: 14px;font-weight: 500;">
                            <option value="">Selecione uma coleção</option>
                            <?php
                            $collections = $pdo->query("SELECT id, nome FROM collections ORDER BY nome");
                            while ($collection = $collections->fetch()) {
                                echo "<option value='{$collection['id']}'>{$collection['nome']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="saveExpense">Salvar</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Expense Modal -->
<div class="modal fade" id="editDespesaModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div style="display: flex;flex-direction: column;">
                    <span style="font-size: 20px;font-weight: 800;color: #525252;">EDITAR DESPESA</span>
                    <span style="font-size: 14px;font-weight: 600;color: gray;">ALTERAR INFORMAÇÕES</span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body" style="padding: 20px 40px;">
                <form id="editExpenseForm">
                    <input type="hidden" name="expense_id" id="editExpenseId">

                    <div class="mb-4">
                        <label class="form-label" style="font-size: 12px;color: gray;font-weight: 600;">DESCRIÇÃO</label>
                        <input type="text" class="form-control" name="description" id="editDescription" style="border: 1px solid #80808075;border-radius: 5px;font-size: 14px;font-weight: 500;" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label" style="font-size: 12px;color: gray;font-weight: 600;">VALOR</label>
                        <input type="number" step="0.01" class="form-control" name="value" id="editValue" style="border: 1px solid #80808075;border-radius: 5px;font-size: 14px;font-weight: 500;" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label" style="font-size: 12px;color: gray;font-weight: 600;">DATA</label>
                        <input type="date" class="form-control" name="expense_date" id="editDate" style="border: 1px solid #80808075;border-radius: 5px;font-size: 14px;font-weight: 500;" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label" style="font-size: 12px;color: gray;font-weight: 600;">COLEÇÃO</label>
                        <select class="form-select" name="collection_id" id="editCollection">
                            <option value="">Selecione uma coleção</option>
                            <?php
                            $collections = $pdo->query("SELECT id, nome FROM collections ORDER BY nome");
                            while ($collection = $collections->fetch()) {
                                echo "<option value='{$collection['id']}'>{$collection['nome']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="updateExpense">Salvar</button>
            </div>
        </div>
    </div>
</div>

<script>
    $('#saveExpense').click(function() {
        const formData = $('#addExpenseForm').serialize() + '&action=add_expense';

        $.ajax({
            url: 'financeiro/actions.php',
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    $('#addExpenseModal').modal('hide');
                    const month = $('#monthSelect').val();
                    const year = $('#yearSelect').val();
                    $.ajax({
                        url: 'financeiro/search.php',
                        type: 'POST',
                        data: {
                            month: month,
                            year: year
                        },
                        success: function(response) {
                            $("#frame__content__collection").html(response);
                        }
                    });
                } else {
                    alert('Erro ao adicionar despesa: ' + response.message);
                }
            }
        });
    });
    $('.edit-despesa').click(function() {
        const id = $(this).data('id');
        $.ajax({
            url: 'financeiro/actions.php',
            type: 'POST',
            data: {
                action: 'get_expense',
                expense_id: id
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    const data = response.data;
                    $('#editExpenseId').val(data.id);
                    $('#editDescription').val(data.description);
                    $('#editValue').val(data.value);
                    $('#editDate').val(data.expense_date);
                    $('#editCollection').val(data.collection_id);
                    $('#editDespesaModal').modal('show');
                }
            }
        });
    });

    $('#updateExpense').click(function() {
        const formData = $('#editExpenseForm').serialize() + '&action=update_expense';

        $.ajax({
            url: 'financeiro/actions.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#editDespesaModal').modal('hide');
                    const month = $('#monthSelect').val();
                    const year = $('#yearSelect').val();
                    $.ajax({
                        url: 'financeiro/search.php',
                        type: 'POST',
                        data: {
                            month: month,
                            year: year
                        },
                        success: function(response) {
                            $("#frame__content__collection").html(response);
                        }
                    });
                } else {
                    alert('Erro ao atualizar despesa: ' + result.message);
                }
            }
        });
    });

    $('.delete-despesa').click(function() {
        const id = $(this).data('id');

        if (confirm('Tem certeza que deseja excluir esta despesa?')) {
            $.ajax({
                url: 'financeiro/actions.php',
                type: 'POST',
                data: {
                    action: 'delete_expense',
                    expense_id: id
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        const month = $('#monthSelect').val();
                        const year = $('#yearSelect').val();
                        $.ajax({
                            url: 'financeiro/search.php',
                            type: 'POST',
                            data: {
                                month: month,
                                year: year
                            },
                            success: function(response) {
                                $("#frame__content__collection").html(response);
                            }
                        });
                    } else {
                        alert('Erro ao excluir despesa: ' + response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Ajax error:', error);
                    alert('Erro ao excluir despesa');
                }
            });
        }
    });
</script>