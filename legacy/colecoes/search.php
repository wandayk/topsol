<?php

include '\xampp\htdocs\class\connection.php';

$search = $_POST['search'] ?? '';

if (empty($search)) {
    $stmt = $pdo->query("SELECT * FROM collections ORDER BY data_criacao DESC");
} else {
    $search = "%$search%";
    $stmt = $pdo->prepare("
        SELECT * FROM collections 
        WHERE nome LIKE ?
        ORDER BY data_criacao DESC
    ");
    $stmt->execute([$search]);
}

while ($client = $stmt->fetch()) {
    echo '<div class="colecoes"
            data-id="' . $client['id'] . '">
            <div class="colecoes__header">
                <div class="colecoes__header__name">
                    <img src="https://ui-avatars.com/api/?name=' . urlencode($client['nome']) . '&size=64" alt="Avatar" width="33" style="border-radius: 50%;border: 1px solid #808080b5;">
                    <div style="display: flex;flex-direction: column;">
                        <span style="color: gray;font-size: 10px;font-weight: 600;margin: 0;padding: 0;line-height: 1;">Coleção</span>
                        <h3 style="font-size: 14px;font-weight: 900;color: #3b3b3b;margin: 0;padding: 0;line-height: 1;">' . htmlspecialchars($client['nome']) . '</h3>
                    </div>
                </div>
                <div style="display: flex;flex-direction: column;">
                    <span style="color: gray;font-size: 10px;font-weight: 600;margin: 0;padding: 0;line-height: 1;">LANÇAMENTO</span>
                    <span style="color: black;font-weight: 700;font-size: 12px;"> ' . htmlspecialchars($client['data_lancamento']) . '</span>
                </div>
            </div>
        </div>
    ';
}

?>

<script>
    $(document).ready(function() {

        // Edit Client - Populate Modal
        $('.edit-client').click(function() {
            $('#edit_id').val($(this).data('id'));
            $('#edit_nome').val($(this).data('nome'));
            $('#edit_data_criacao').val($(this).data('data_criacao'));
            $('#edit_data_lancamento').val($(this).data('data_lancamento'));
        });

        // Update Client
        $('#editClientForm').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: 'clientes/actions.php',
                type: 'POST',
                data: $(this).serialize() + '&action=update',
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        alert('Error: ' + response.message);
                    }
                }
            });
        });


        // Delete Client
        $('.delete-client').click(function() {
            if (confirm('Are you sure you want to delete this client?')) {
                const id = $(this).data('id');
                $.ajax({
                    url: 'clientes/actions.php',
                    type: 'POST',
                    data: {
                        action: 'delete',
                        id: id
                    },
                    success: function(response) {
                        if (response.success) {
                            location.reload();
                        } else {
                            alert('Error: ' + response.message);
                        }
                    }
                });
            }
        });

        function displayCollection(response) {
            const mainCollection = $('#frame__content__collection');
            const mobileCollection = $('#frame__content__collection__mobile');
            const buttonCloseCollection = $('.btn-close-collection');
            if (mainCollection.css('display') === 'none') {
                mobileCollection.html(response);
                mobileCollection.css('width', '90vw');
                setTimeout(() => {
                    buttonCloseCollection.css('display', 'block');
                }, 200);

            } else {
                mainCollection.html(response);
                buttonCloseCollection.css('display', 'none');

            }
        }

        // load Client
        $('.colecoes').click(function() {
            const id = $(this).data('id');
            $.ajax({
                url: 'colecoes/item.php',
                type: 'GET',
                data: {
                    id: id
                },
                success: function(response) {
                    displayCollection(response);
                }
            });

        });

        if ($('.colecoes').length > 0 && $('#frame__content__collection').css('display') != 'none') {
            $('.colecoes:first').trigger('click');
        } else {
            $("#frame__content__collection").empty();
        }

        

    });
</script>