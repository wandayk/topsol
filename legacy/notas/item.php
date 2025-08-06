<?php

setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set('America/Sao_Paulo');

require_once '../class/connection.php';

// Get note ID from URL
$note_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Get note details with client info
$stmt = $pdo->prepare("
    SELECT n.*, c.name as client_name 
    FROM notes n
    JOIN clients c ON n.client_id = c.id
    WHERE n.id = ?
");
$stmt->execute([$note_id]);
$note = $stmt->fetch();

if (!$note) {
    die('Nota não encontrada');
}

// Decode JSON data
$items = json_decode($note['items'], true) ?: [];
$payments = json_decode($note['payments'], true) ?: [];
?>

<div class="frame__container">
    <div class="frame__notas">
        <div class="frame__notas__header">
            <div class="frame__notas__header__name">
                <div class="frame__notas__header__name__title">
                    <div style="display: flex;gap: 3px;flex-direction:column;">
                        <div style="display: flex;gap: 5px;">
                            <h2 style="font-size: 45px;line-height: 1;">#<?php echo $note['id']; ?></h2>
                            <span style="display: flex;flex-direction: column;text-align: end;">
                                <p style="color: #989898;font-weight: 700;font-size: 12px;margin: 0;"><?php echo date('d/m/Y', strtotime($note['created_at'])); ?></p>
                            </span>
                        </div>
                        <div style="display: flex;flex-direction: column;">
                            <span style="color: gray;font-size: 10px;font-weight: 600;">CLIENTE</span>
                            <h3 style="line-height: 1;font-size: 12px;font-weight: 800;text-transform: uppercase;margin: 0;"><?php echo htmlspecialchars($note['client_name']); ?></h3>
                        </div>
                    </div>
                    <div style="text-align: end;display: flex;flex-direction: column;gap: 2px;justify-content: space-between;align-items: end;">
                        <div class="<?php echo $note['is_closed'] ? 'status-closed' : 'status-open'; ?>">
                            <?php echo $note['is_closed'] ? '<img src="img\closed.svg" alt="fechada" width="16";>FECHADA' : '<img src="img\open.svg" alt="aberta" width="16">ABERTA'; ?>
                        </div>
                        <div style="display: flex; justify-content: end; gap: 5px;">
                            <button class="btn btn-sm btn-primary edit-nota" data-id="<?php echo $note['id']; ?>" data-bs-toggle="modal" data-bs-target="#editNoteModal" style="padding: 5px;margin: 0;background: #5050dbb2;border: 0;display: flex;justify-content: center;width: fit-content;"><img src="img/edit_white.svg" alt="" width="15"></button>
                            <button class="btn btn-sm btn-danger delete-nota" data-id="<?php echo $note['id']; ?>" style="padding: 5px;margin: 0;background:rgba(219, 80, 80, 0.7);border: 0;display: flex;justify-content: center;width: fit-content;"><img src="img/delete_white.svg" alt="" width="15"></button>
                        </div>
                    </div>
                </div>

            </div>


        </div>

        <div class="frame__notas__body">

            <div class="frame__notas__header__info">

                <div style="display: flex;gap: 20px;justify-content: space-between;">
                    <span>
                        <p style="color: gray;font-size: 10px;font-weight: 600;">PAGAMENTO</p>
                        <p style="color: black;font-weight: 700;"><?php echo $note['payment_method']; ?></p>
                    </span>

                    <?php if ($note['is_installment']): ?>
                        <span style="text-align: end;">
                            <p style="color: gray;font-size: 10px;font-weight: 600;">PARCELAS</p>
                            <p style="color: black;font-weight: 700;"><?php echo $note['installment_count']; ?>x</p>
                        </span>
                    <?php endif; ?>
                </div>

                <div style="display: flex;flex-direction: column;gap: 5px;">
                    <?php
                    // Create array indexed by installment number
                    $paymentsByInstallment = [];
                    foreach ($payments as $payment) {
                        $paymentsByInstallment[$payment['installment']] = $payment;
                    }

                    // Count unpaid installments and calculate remaining value
                    $unpaidCount = 0;
                    $totalPaid = 0;

                    // Calculate total paid
                    foreach ($paymentsByInstallment as $payment) {
                        $totalPaid += $payment['value'];
                    }

                    // Count unpaid installments
                    for ($i = 1; $i <= $note['installment_count']; $i++) {
                        if (!isset($paymentsByInstallment[$i]) || $paymentsByInstallment[$i]['value'] == 0) {
                            $unpaidCount++;
                        }
                    }

                    // Calculate new installment value
                    $remainingValue = $note['total_value'] - $totalPaid;
                    $installmentValue = $unpaidCount > 0 ? $remainingValue / $unpaidCount : 0;

                    // Loop through all installments
                    for ($i = 1; $i <= $note['installment_count']; $i++):
                        if (isset($paymentsByInstallment[$i]) && $paymentsByInstallment[$i]['value'] > 0):
                            $payment = $paymentsByInstallment[$i];
                    ?>
                            <div class="pay__nota" style="cursor:pointer;display: flex; justify-content: space-between;background: #a2ffb9;border-radius: 5px;padding: 3px 10px;align-items: center;">
                                <span style="color: #555; font-size: 13px;font-weight: 700;">
                                    <?php echo $i . "ª"; ?>
                                </span>
                                <span style="font-weight: 600;font-size: 13px;display: flex;align-items: center;gap: 10px;">
                                    <small style="color: gray;">(<?php echo date('d/m/Y', strtotime($payment['date'])); ?>)</small>
                                    R$ <?php echo number_format($payment['value'], 2, ',', '.'); ?>
                                </span>
                            </div>
                        <?php else: ?>
                            <div class="pay__nota" style="cursor:pointer;display: flex; justify-content: space-between;background: #ffdfdf;border-radius: 5px;padding: 3px 10px;align-items: center;">
                                <span style="color: #555; font-size: 13px;font-weight: 700;">
                                    <?php echo $i . "ª"; ?>
                                </span>
                                <span style="font-weight: 600;font-size: 13px;">
                                    R$ <?php echo number_format($installmentValue, 2, ',', '.'); ?>
                                </span>
                            </div>
                        <?php endif; ?>
                    <?php endfor; ?>
                </div>

                <!-- Add click handler to pay_nota divs -->
                <script>
                    $('.pay__nota').click(function() {
                        const installmentNumber = $(this).find('span:first').text().replace('ª', '');
                        const installmentValue = $(this).find('span:last').text().replace('R$ ', '').replace('.', '').replace(',', '.');

                        $('#installmentNumber').val(installmentNumber);
                        $('#paymentValue').val(installmentValue);
                        $('#payNoteModal').modal('show');
                        $('#installmentNumber__span').text(installmentNumber + 'ª PARCELA');
                    });
                </script>

                <span>
                    <p style="color: gray;font-size: 10px;font-weight: 600;">PAGO</p>
                    <p style="color: black;font-weight: 700;">R$ <?php echo number_format($note['total_paid'], 2, ',', '.'); ?></p>
                </span>




            </div>

            <div style="display: flex;justify-content: end;border-top: 1px solid #8080803b;padding: 20px;background: #f6f6f6;text-align: end;flex-direction: column;">
                <span style="color: gray;font-size: 10px;font-weight: 600;">
                    VALOR TOTAL
                </span>
                <span style="color: black;font-weight: 700;">
                    R$ <?php echo number_format($note['total_value'], 2, ',', '.'); ?>
                </span>
            </div>


        </div>
    </div>

    <div class="frame__notas__header__itens">
        <div class="frame__notas__header__itens__header">
            <span style="color: #525252; font-size: 19px; font-weight: 700;line-height: 1;">
                Produtos
            </span>
            <button type="button" class="button-name" data-bs-toggle="modal" data-bs-target="#addItemModal" style="padding: 13px 5px;margin: 0;font-size: 11px;font-weight: 700;color: whitesmoke;background-color: #309f56;height: 0;">
                <span style="font-size: 20px;font-weight: 700;line-height: 1;margin-bottom: 7px;margin-right: 10px;">+</span>
                <span style="margin-bottom: 4px;font-size: 12px;font-weight: 600;margin-right: 8px;">ITEM</span>
            </button>
        </div>
        <div style="gap: 20px; display: flex;flex-direction: column;overflow: auto;padding:20px 0;">
            <?php if (empty($items)): ?>
                <span style="text-align: center; display: block; color: #666; font-size: 16px; padding: 20px;">
                    Nenhum item adicionado
                </span>
                <?php else:
                foreach ($items as $item):
                    // Get item details from items table
                    $stmt = $pdo->prepare("SELECT i.*, c.nome as collection_name FROM items i LEFT JOIN collections c ON i.idcolecao = c.id WHERE i.id = ?");
                    $stmt->execute([$item['id']]);
                    $itemDetails = $stmt->fetch();

                    // Get photos
                    $photos = [];
                    $ensaioFotos = json_decode($itemDetails['ensaio_fotos'], true);
                    if ($ensaioFotos && count($ensaioFotos) > 0) {
                        $photos = $ensaioFotos;
                    } else {
                        $referenciaFotos = json_decode($itemDetails['referencia_foto'], true);
                        if ($referenciaFotos && count($referenciaFotos) > 0) {
                            $photos = $referenciaFotos;
                        }
                    }


                ?>
                    <div style="position:relative;cursor: pointer; background: white; display:flex;border-top: 1px solid #80808040;border-bottom: 1px solid #80808040;">
                        <div style="right:15px;top:15px;position:absolute;display: flex; justify-content: center; gap: 5px;flex-direction: column;margin-bottom: 15px;">
                            <button class="btn btn-sm btn-primary edit-produto" data-id="<?php echo $item['id']; ?>" data-price="<?php echo $item['price']; ?>" data-quantity="<?php echo $item['quantity']; ?>" data-total="<?php echo $item['total']; ?>"
                                style="padding: 5px;margin: 0;background: #5050dbb2;border: 0;display: flex;justify-content: center;width: fit-content;"><img src="img/edit_white.svg" alt="" width="15"></button>
                            <button class="btn btn-sm btn-danger delete-produto" data-id="<?php echo $item['id']; ?>"
                                style="padding: 5px;margin: 0;background:rgba(219, 80, 80, 0.7);border: 0;display: flex;justify-content: center;width: fit-content;"><img src="img/delete_white.svg" alt="" width="15"></button>
                        </div>
                        <?php if (!empty($photos)): ?>
                            <div id="note_item_<?php echo $item['id']; ?>" class="carousel slide" data-bs-ride="carousel" style="height: 150px;width: 150px;">
                                <div class="carousel-indicators">
                                    <?php foreach ($photos as $index => $photo): ?>
                                        <button type="button"
                                            data-bs-target="#note_item_<?php echo $item['id']; ?>"
                                            data-bs-slide-to="<?php echo $index; ?>"
                                            <?php echo $index === 0 ? 'class="active"' : ''; ?>
                                            aria-current="true">
                                        </button>
                                    <?php endforeach; ?>
                                </div>
                                <div class="carousel-inner">
                                    <?php foreach ($photos as $index => $photo): ?>
                                        <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                                            <img src="<?php echo $photo; ?>" class="d-block w-100"
                                                style="object-fit: cover; height: 150px; border-radius: 8px 8px 0 0;">
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <button class="carousel-control-prev" type="button" data-bs-target="#note_item_<?php echo $item['id']; ?>" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#note_item_<?php echo $item['id']; ?>" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                </button>
                            </div>
                        <?php endif; ?>

                        <div style="padding: 15px;">
                            <div style="display: flex; justify-content: space-between; width: 100%;flex-direction: column;">
                                <div>
                                    <h5 style="margin: 0; font-size: 14px; font-weight: 900;">
                                        <?php echo htmlspecialchars(strtoupper($itemDetails['nome'])); ?>
                                    </h5>
                                    <p style="font-size: 14px; font-weight: 700; color: #acacac;margin: 0;">
                                        R$ <?php echo number_format($item['price'], 2, ',', '.'); ?> x <?php echo $item['quantity']; ?> =
                                        R$ <?php echo number_format($item['total'], 2, ',', '.'); ?>
                                    </p>
                                </div>
                                <div style="padding-top: 15px;display: flex;flex-direction: column;gap: 10px;">
                                    <div style="display: flex;flex-direction: column;gap: 3px;">
                                        <span style="color: gray; font-size: 10px; font-weight: 700;line-height: 1;">
                                            CATEGORIA
                                        </span>
                                        <span style="margin: 0; font-size: 13px; font-weight: 700;line-height: 1;">
                                            <?php echo htmlspecialchars(strtoupper($itemDetails['categoria'])); ?>
                                        </span>
                                    </div>
                                    <div style="display: flex;flex-direction: column;gap: 3px;">
                                        <span style="color: gray; font-size: 10px; font-weight: 700;line-height: 1;">
                                            COLEÇÃO
                                        </span>
                                        <span style="margin: 0; font-size: 13px; font-weight: 700;line-height: 1;">
                                            <?php echo htmlspecialchars(strtoupper($itemDetails['collection_name'])); ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Edit Note Modal -->
<div class="modal fade" id="editNoteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <div style="display: flex;flex-direction: column;">
                    <span style="font-size: 20px;font-weight: 800;color: #525252;">NOTA #<?php echo $note['id']; ?></span>
                    <span style="font-size: 14px;font-weight: 600;color: gray;">EDITAR NOTA</span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body" style="padding: 0;margin: 0;">
                <form id="editNoteForm" style="padding: 20px 40px;">
                    <input type="hidden" name="note_id" value="<?php echo $note['id']; ?>">

                    <div class="mb-4">
                        <label class="form-label" style="font-size: 12px;color: gray;font-weight: 600;">CLIENTE</label>
                        <select class="form-select" name="client_id" required
                            style="border: 1px solid #80808075;border-radius: 5px;font-size: 14px;font-weight: 500;">
                            <?php
                            $clients = $pdo->query("SELECT id, name FROM clients ORDER BY name");
                            while ($client = $clients->fetch()) {
                                $selected = ($client['id'] == $note['client_id']) ? 'selected' : '';
                                echo "<option value='{$client['id']}' {$selected}>{$client['name']}</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label" style="font-size: 12px;color: gray;font-weight: 600;">FORMA DE PAGAMENTO</label>
                        <select class="form-select" name="payment_method" id="edit_payment_method" required
                            style="border: 1px solid #80808075;border-radius: 5px;font-size: 14px;font-weight: 500;">
                            <option value="PIX" <?php echo $note['payment_method'] == 'PIX' ? 'selected' : ''; ?>>PIX</option>
                            <option value="Cartão" <?php echo $note['payment_method'] == 'Cartão' ? 'selected' : ''; ?>>Cartão</option>
                            <option value="Dinheiro" <?php echo $note['payment_method'] == 'Dinheiro' ? 'selected' : ''; ?>>Dinheiro</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <div class="form-check" style="margin-bottom: 10px;">
                            <input class="form-check-input" type="checkbox" name="is_installment" id="edit_is_installment"
                                <?php echo $note['is_installment'] ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="edit_is_installment" style="font-size: 14px;font-weight: 500;">
                                Pagamento Parcelado
                            </label>
                        </div>

                        <div id="edit_installmentDiv" style="display: <?php echo $note['is_installment'] ? 'block' : 'none'; ?>">
                            <label class="form-label" style="font-size: 12px;color: gray;font-weight: 600;">NÚMERO DE PARCELAS</label>
                            <input type="number" class="form-control" name="installment_count"
                                value="<?php echo $note['installment_count']; ?>" min="2" max="12"
                                style="border: 1px solid #80808075;border-radius: 5px;font-size: 14px;font-weight: 500;">
                        </div>
                    </div>

                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="updateNote">Salvar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addItemModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <div style="display: flex;flex-direction: column;">
                    <span style="font-size: 20px;font-weight: 800;color: #525252;">NOTA #<?php echo $note['id']; ?></span>
                    <span style="font-size: 14px;font-weight: 600;color: gray;">ADICIONAR ITEM</span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="padding: 0;margin: 0;">
                <form id="addNoteItemForm" style="padding: 20px 40px;">
                    <input type="hidden" name="note_id" value="<?php echo $note['id']; ?>">
                    <input type="hidden" name="item_id" value="">

                    <div class="mb-3" style="margin: 0 !important;">
                        <input type="text" class="form-control" id="searchItem" placeholder="Digite para pesquisar..." style="border: 1px solid #80808075;border-radius: 5px;text-transform: uppercase;font-size: 14px;font-weight: 500;">
                        <div id="searchResults" class="list-group mt-2" style="margin-top: 25px !important;display: flex;flex-direction: column;gap: 20px;"></div>
                    </div>

                    <div id="selectedItem" style="display: none;">
                        <div id="selectedItem__html"
                            style="cursor: pointer; background: white; border-radius: 8px; box-shadow: rgba(9, 30, 66, 0.25) 0px 4px 8px -2px, rgba(9, 30, 66, 0.08) 0px 0px 0px 1px;display:flex;">
                        </div>

                        <div class="mb-3" style="display: flex;padding-top: 20px;">
                            <div>
                                <label class="form-label">Preço Unitário</label>
                                <input type="number" step="0.01" class="form-control" name="price" id="itemPrice" required>
                            </div>
                            <div>
                                <label class="form-label">Quantidade</label>
                                <input type="number" class="form-control" name="quantity" id="itemQuantity" value="1" min="1" required>
                            </div>
                            <div>
                                <label class="form-label">Total</label>
                                <input type="text" class="form-control" id="itemTotal" readonly>

                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="saveNoteItem">Adicionar</button>
            </div>
        </div>
    </div>
</div>


<!-- Payment Modal -->
<div class="modal fade" id="payNoteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div style="display: flex;flex-direction: column;">
                    <span style="font-size: 20px;font-weight: 800;color: #525252;">NOTA #<?php echo $note['id']; ?></span>
                    <span style="font-size: 14px;font-weight: 600;color: gray;">REGISTRAR PAGAMENTO</span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body" style="padding: 0;">
                <form id="paymentForm" style="padding: 20px 40px;">
                    <input type="hidden" name="note_id" value="<?php echo $note['id']; ?>">
                    <input type="hidden" name="installment" id="installmentNumber">
                    <input type="hidden" name="max_value" id="maxValue" value="<?php echo $note['total_value'] - $note['total_paid']; ?>">

                    <span id="installmentNumber__span" style="font-size: 18px;font-weight: 800;color: #505050;padding-bottom: 15px;"></span>

                    <div class="mb-4">
                        <label class="form-label" style="font-size: 12px;color: gray;font-weight: 600;">VALOR</label>
                        <?php
                        $maxValue = $note['total_value'] - $note['total_paid'];
                        if ($maxValue > 0): ?>
                            <input type="number"
                                step="0.01"
                                class="form-control"
                                name="payment_value"
                                id="paymentValue"
                                required
                                max="<?php echo $maxValue; ?>"
                                style="border: 1px solid #80808075;border-radius: 5px;font-size: 14px;font-weight: 500;">
                            <small class="text-muted">Valor máximo: R$ <?php echo number_format($maxValue, 2, ',', '.'); ?></small>
                        <?php else: ?>
                            <input type="number"
                                step="0.01"
                                class="form-control"
                                name="payment_value"
                                id="paymentValue"
                                required
                                style="border: 1px solid #80808075;border-radius: 5px;font-size: 14px;font-weight: 500;">
                        <?php endif; ?>
                    </div>

                    <div class="mb-4">
                        <label class="form-label" style="font-size: 12px;color: gray;font-weight: 600;">FORMA DE PAGAMENTO</label>
                        <select class="form-select" name="payment_method" required
                            style="border: 1px solid #80808075;border-radius: 5px;font-size: 14px;font-weight: 500;">
                            <option value="PIX">PIX</option>
                            <option value="Cartão">Cartão</option>
                            <option value="Dinheiro">Dinheiro</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label" style="font-size: 12px;color: gray;font-weight: 600;">DATA</label>
                        <input type="date"
                            class="form-control"
                            name="payment_date"
                            placeholder="dd-mm-yyyy"
                            required
                            value="<?php echo strftime('%Y-%m-%d', strtotime('today')); ?>"
                            data-date-format="dd/mm/yyyy"
                            style="border: 1px solid #80808075;border-radius: 5px;font-size: 14px;font-weight: 500;">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="confirmPayment">Confirmar</button>
            </div>
        </div>
    </div>
</div>
<!-- Edit Item Modal -->
<div class="modal fade" id="editItemModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div style="display: flex;flex-direction: column;">
                    <span style="font-size: 20px;font-weight: 800;color: #525252;">EDITAR ITEM</span>
                    <span style="font-size: 14px;font-weight: 600;color: gray;">NOTA #<?php echo $note['id']; ?></span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body" style="padding: 20px 40px;">
                <form id="editItemForm">
                    <input type="hidden" name="note_id" value="<?php echo $note['id']; ?>">
                    <input type="hidden" name="item_id" id="editItemId">

                    <div id="selectedItem">
                        <div id="selectedItem__html"
                            style="cursor: pointer; background: white; border-radius: 8px; box-shadow: rgba(9, 30, 66, 0.25) 0px 4px 8px -2px, rgba(9, 30, 66, 0.08) 0px 0px 0px 1px;display:flex;">
                        </div>

                        <div class="mb-3" style="display: flex;padding-top: 20px;">
                            <div>
                                <label class="form-label">Preço Unitário</label>
                                <input type="number" step="0.01" class="form-control" name="price" id="editItemPrice" required>
                            </div>
                            <div>
                                <label class="form-label">Quantidade</label>
                                <input type="number" class="form-control" name="quantity" id="editItemQuantity" value="1" min="1" required>
                            </div>
                            <div>
                                <label class="form-label">Total</label>
                                <input type="text" class="form-control" id="editItemTotal" readonly>

                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="updateItem">Salvar</button>
            </div>
        </div>
    </div>
</div>


<script>
    $('#updateItem').click(function() {
        // Get form values
        const noteId = $('input[name="note_id"]').val();
        const itemId = $('#editItemId').val();
        const price = $('#editItemPrice').val();
        const quantity = $('#editItemQuantity').val();

        // Validate inputs
        if (!price || !quantity) {
            alert('Por favor, preencha todos os campos');
            return;
        }

        // Calculate total
        const total = price * quantity;

        // Prepare form data
        const formData = {
            action: 'update_note_item',
            note_id: noteId,
            item_id: itemId,
            price: price,
            quantity: quantity,
            total: total
        };

        // Send AJAX request
        $.ajax({
            url: 'notas/actions.php',
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    $('#editItemModal').modal('hide');
                    // Reload note content
                    $.ajax({
                        url: 'notas/item.php',
                        type: 'GET',
                        data: {
                            id: noteId
                        },
                        success: function(response) {
                            $("#frame__content__collection").html(response);
                        }
                    });
                } else {
                    alert('Erro ao atualizar item: ' + response.message);
                }
            },
            error: function() {
                alert('Erro ao processar requisição');
            }
        });
    });

    $('.delete-produto').click(function() {
        const itemId = $(this).data('id');
        const noteId = $('input[name="note_id"]').val();

        if (confirm('Tem certeza que deseja excluir este item?')) {
            $.ajax({
                url: 'notas/actions.php',
                type: 'POST',
                data: {
                    action: 'delete_note_item',
                    note_id: noteId,
                    item_id: itemId
                },
                success: function(response) {
                    if (response.success) {
                        // Reload note content
                        $.ajax({
                            url: 'notas/item.php',
                            type: 'GET',
                            data: {
                                id: noteId
                            },
                            success: function(html) {
                                $("#frame__content__collection").html(html);
                            }
                        });
                    } else {
                        alert('Erro ao excluir item: ' + response.message);
                    }
                },
                error: function() {
                    alert('Erro ao processar requisição');
                }
            });
        }
    });

    $('.edit-produto').click(function() {
        const itemId = $(this).data('id');
        const price = $(this).data('price');
        const quantity = $(this).data('quantity');
        const total = $(this).data('total');

        $('#editItemId').val(itemId);
        $('#editItemPrice').val(price);
        $('#editItemQuantity').val(quantity);
        $('#editItemTotal').val(total);

        $('#editItemModal').modal('show');
    });

    $('#confirmPayment').click(function() {
        // Get form values
        const paymentValue = parseFloat($('#paymentValue').val());
        const maxValue = parseFloat($('#maxValue').val());
        const paymentMethod = $('select[name="payment_method"]').val();
        const paymentDate = $('input[name="payment_date"]').val();

        // Validation checks
        if (isNaN(paymentValue)) {
            alert('Por favor, insira um valor válido');
            return;
        }

        if (paymentValue > maxValue && maxValue != 0) {
            alert('O valor não pode ser maior que R$ ' + maxValue.toFixed(2));
            return;
        }

        if (!paymentMethod) {
            alert('Selecione uma forma de pagamento');
            return;
        }

        if (!paymentDate) {
            alert('Selecione uma data');
            return;
        }

        // If validation passes, submit form
        const formData = $('#paymentForm').serialize() + '&action=register_payment';
        const noteId = $('input[name="note_id"]').val();
        $.ajax({
            url: 'notas/actions.php',
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    $('#payNoteModal').modal('hide');
                    const id = $(this).data('id');
                    $.ajax({
                        url: 'notas/item.php',
                        type: 'GET',
                        data: {
                            id: noteId
                        },
                        success: function(response) {
                            $("#frame__content__collection").html(response);
                        }
                    });
                } else {
                    alert('Erro ao registrar pagamento: ' + response.message);
                }
            }
        });
    });
</script>

<script>
    $(document).ready(function() {
        let typingTimer;
        const doneTypingInterval = 300;

        $('#editItemQuantity, #editItemPrice').on('input', updateTotalEdit);

        function updateTotalEdit() {
            const quantity = $('#editItemQuantity').val();
            const price = $('#editItemPrice').val();
            const total = quantity * price;
            $('#editItemTotal').val(`R$ ${total.toFixed(2)}`);
        }

        $('#edit_is_installment').change(function() {
            if ($(this).is(':checked')) {
                $('#edit_installmentDiv').slideDown();
                $('input[name="installment_count"]').prop('required', true);
            } else {
                $('#edit_installmentDiv').slideUp();
                $('input[name="installment_count"]').prop('required', false).val('');
            }
        });

        $('#searchItem').on('keyup', function() {
            clearTimeout(typingTimer);
            const searchText = $(this).val();

            if (searchText.length > 2) {
                typingTimer = setTimeout(function() {
                    $.ajax({
                        url: 'notas/search_items.php',
                        type: 'GET',
                        data: {
                            search: searchText
                        },
                        success: function(response) {
                            if (response.success) {

                                let resultsHtml = '';

                                response.items.forEach(item => {
                                    let photos = [];
                                    try {
                                        if (item.ensaio_fotos && JSON.parse(item.ensaio_fotos).length > 0) {
                                            photos = JSON.parse(item.ensaio_fotos);
                                        } else if (item.referencia_foto) {
                                            photos = JSON.parse(item.referencia_foto);
                                        }
                                    } catch (e) {
                                        console.error('Error parsing photos:', e);
                                    }
                                    resultsHtml += `
                                <div class="search-item" 
                                    data-id="${item.id}" 
                                    data-name="${item.nome}" 
                                    data-price="${item.valor}"
                                    style="cursor: pointer; background: white; border-radius: 8px; box-shadow: rgba(9, 30, 66, 0.25) 0px 4px 8px -2px, rgba(9, 30, 66, 0.08) 0px 0px 0px 1px;display:flex;">
                                    ${photos && photos.length > 0 ? `
                                        <div id="search_${item.id}" class="carousel slide" style="height: 150px;width: 150px;">
                                            <div class="carousel-inner">
                                                ${photos.map((photo, index) => `
                                                    <div class="carousel-item ${index === 0 ? 'active' : ''}">
                                                        <img src="${photo}" class="d-block w-100" 
                                                            style="object-fit: cover; height: 150px; border-radius: 8px 8px 0 0;">
                                                    </div>
                                                `).join('')}
                                            </div>
                                        </div>
                                    ` : ''}
                                    <div style="padding: 15px;">
                                        <div style="display: flex; justify-content: space-between; width: 100%;flex-direction: column;">
                                            <div>
                                                <h5 style="margin: 0; font-size: 14px; font-weight: 900;">
                                                    ${item.nome.toUpperCase()}
                                                </h5>
                                               
                                            </div>
                                            <div>
                                                <p style="font-size: 14px; font-weight: 700; color: #acacac;margin: 0;">
                                                    R$ ${parseFloat(item.valor).toFixed(2).replace('.', ',')}
                                                </p>
                                            </div>
                                        </div>
                                        <div style="padding-top: 15px;display: flex;flex-direction: column;gap: 10px;">
                                        <div style="display: flex;flex-direction: column;gap: 3px;">
                                                <span style="color: gray; font-size: 10px; font-weight: 700;line-height: 1;">
                                                    CATEGORIA
                                                </span>
                                                <span style="margin: 0; font-size: 13px; font-weight: 700;line-height: 1;"">
                                                    ${item.categoria.toUpperCase()}    
                                                </span>
                                            </div>
                                            <div style="display: flex;flex-direction: column;gap: 3px;">
                                                <span style="color: gray; font-size: 10px; font-weight: 700;line-height: 1;">
                                                    COLEÇÃO
                                                </span>
                                                <span style="margin: 0; font-size: 13px; font-weight: 700;line-height: 1;"">
                                                    ${item.collection_name.toUpperCase()}    
                                                </span>
                                            </div>

                                        </div>
                                    </div>
                                </div>`;
                                });


                                $('#searchResults').html(resultsHtml);

                                $('#selectedItem').hide();

                                // Handle item selection
                                $('.search-item').click(function() {
                                    const id = $(this).data('id');
                                    const name = $(this).data('name');
                                    const price = $(this).data('price');
                                    const html = $(this).html();

                                    $('#selectedItem').show();
                                    $('#searchResults').empty();
                                    $('#searchItem').val('');
                                    $('#selectedItem__html').html(html);
                                    $('input[name="item_id"]').val(id);
                                    $('#itemPrice').val(price);
                                    $('#originalPrice').text(parseFloat(price).toFixed(2));
                                    selectedItem__html
                                    const quantity = $('#itemQuantity').val();
                                    const total = quantity * price;
                                    $('#itemTotal').val(`R$ ${total.toFixed(2)}`);
                                });
                            }
                        }
                    });
                }, doneTypingInterval);
            } else {
                $('#searchResults').empty();
            }
        });

        $('#itemQuantity, #itemPrice').on('input', updateTotal);

        function updateTotal() {
            const quantity = $('#itemQuantity').val();
            const price = $('#itemPrice').val();
            const total = quantity * price;
            $('#itemTotal').val(`R$ ${total.toFixed(2)}`);
        }

        $('#saveNoteItem').click(function() {
            const id = $('input[name="note_id"]').val();
            let formData = $('#addNoteItemForm').serialize();
            formData += '&action=add_note_item';

            $.ajax({
                url: 'notas/actions.php',
                type: 'POST',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        $('#addItemModal').modal('hide');
                        $.ajax({
                            url: 'notas/item.php',
                            type: 'GET',
                            data: {
                                id: id
                            },
                            success: function(response) {
                                $("#frame__content__collection").html(response);
                            }
                        });
                    } else {
                        alert('Erro ao adicionar item: ' + response.message);
                    }
                }
            });
        });

        $('#edit_is_installment').change(function() {
            $('#edit_installmentDiv').toggle(this.checked);
        });

        $('#updateNote').click(function() {
            const formData = $('#editNoteForm').serialize();
            const id = $('input[name="note_id"]').val();
            $.ajax({
                url: 'notas/actions.php',
                type: 'POST',
                data: formData + '&action=update_note',
                success: function(response) {
                    if (response.success) {
                        $.ajax({
                            url: 'notas/item.php',
                            type: 'GET',
                            data: {
                                id: id
                            },
                            success: function(response) {
                                $("#frame__content__collection").html(response);
                            }
                        });
                    } else {
                        alert('Erro ao atualizar nota: ' + response.message);
                    }
                }
            });
        });

        $('.delete-nota').click(function() {
            if (confirm('Tem certeza que deseja excluir esta nota?')) {
                const noteId = $('.delete-nota').data('id');
                $.ajax({
                    url: 'notas/actions.php',
                    type: 'POST',
                    data: {
                        action: 'delete_note',
                        note_id: noteId
                    },
                    success: function(response) {
                        if (response.success) {
                            window.location.href = 'notas.php';
                        } else {
                            alert('Erro ao excluir nota: ' + response.message);
                        }
                    }
                });
            }
        });

    });
</script>