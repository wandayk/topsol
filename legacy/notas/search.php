<?php

include '\xampp\htdocs\class\connection.php';

$search = $_POST['search'] ?? '';

if (empty($search)) {
    $stmt = $pdo->query("
        SELECT n.*, c.name as client_name 
        FROM notes n 
        JOIN clients c ON n.client_id = c.id 
        ORDER BY n.created_at DESC
    ");
} else {
    $search = "%$search%";
    $stmt = $pdo->prepare("
        SELECT n.*, c.name as client_name 
        FROM notes n 
        JOIN clients c ON n.client_id = c.id 
        WHERE c.name LIKE ? OR n.id LIKE ?
        ORDER BY n.created_at DESC
    ");
    $stmt->execute([$search, $search]);
}

while ($note = $stmt->fetch()) {
    $statusClass = $note['is_closed'] ? 'status-closed' : 'status-open';
    $statusText = $note['is_closed'] ? '<img src="img\closed.svg" alt="fechada" width="16";>FECHADA' : '<img src="img\open.svg" alt="aberta" width="16">ABERTA';

    echo '<div class="notas"
            data-id="' . $note['id'] . '">
            <div class="notas__header">
                <div class="notas__header__name">
                    <div style="display: flex;gap: 5px;">
                    <span style="font-weight: 900;font-size: 25px;line-height: 0.7;">#' . $note['id'] . '</span>
                    <span style="color: gray;font-size: 10px;font-weight: 600;margin: 0;padding: 0;line-height: 1;">' . date('d/m/Y', strtotime($note['created_at'])) . '</span>
                    </div>
                    <div style="display: flex;flex-direction: column;">
                        <span style="color: gray;font-size: 10px;font-weight: 600;margin: 0;padding: 0;line-height: 1;">Cliente</span>
                        <h3 style="font-size: 12px;font-weight: 700;color: #414141;margin: 0;padding: 0;line-height: 1;">' .
        htmlspecialchars($note['client_name']) .
        '</h3>
                    </div>
                </div>
                <div style="display: flex;flex-direction: column;align-items: flex-end;gap: 7px;">
                    <span class="status-badge ' . $statusClass . '">' . $statusText . '</span>
                    <div style="display: flex;flex-direction: column;align-items: flex-end;">
                        <span style="color: gray;font-size: 10px;font-weight: 600;margin: 0;padding: 0;line-height: 1;">VALOR TOTAL</span>
                        <span style="color: black;font-weight: 700;font-size: 12px;">R$ ' .
        number_format($note['total_value'], 2, ',', '.') .
        '</span>
                    </div>
                </div>
            </div>
        </div>';
}
?>



<script>
    $(document).ready(function() {
        // load Nota
        $('.notas').click(function() {
            const id = $(this).data('id');
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

        });

        if ($('.notas').length > 0) {
            $('.notas:first').trigger('click');
        } else {
            $("#frame__content__collection").empty();
        }
    });
</script>