<?php

include '\xampp\htdocs\class\connection.php';

$search = $_POST['search'] ?? '';

if (empty($search)) {
    $stmt = $pdo->query("SELECT * FROM clients ORDER BY name");
} else {
    $search = "%$search%";
    $stmt = $pdo->prepare("
        SELECT * FROM clients 
        WHERE name LIKE ?
        OR origin LIKE ?  
        OR email LIKE ? 
        OR phone LIKE ? 
        OR address LIKE ? 
        ORDER BY name
    ");
    $stmt->execute([$search, $search, $search, $search, $search]);
}

while ($client = $stmt->fetch()) {
    echo '<div class="cliente">
            <div class="cliente__header">
                <div class="cliente__header__name">
                <span style="color: gray;font-size: 10px;font-weight: 600;margin: 0;padding: 0;line-height: 1;">Cliente</span>
                <h3 style="font-size: 14px;font-weight: 900;color: #3b3b3b;margin: 0;padding: 0;line-height: 1;">' . htmlspecialchars($client['name']) . '</h3>
                </div>
                <img src="https://ui-avatars.com/api/?name=' . urlencode($client['name']) . '&size=64" alt="Avatar" width="33" style="border-radius: 50%;border: 1px solid #808080b5;">
                
                
            </div>
            <div class="cliente__info">
                <span style="color: gray;font-size: 10px;margin-top: 2px;font-weight: 600;">Origem</span>
                <span style="color: #131313;font-size: 12px;margin-top: 2px;font-weight: 700;text-transform: uppercase;margin-bottom: 5px;">' . htmlspecialchars($client['origin']) . '</span>
                <span style="color: gray;font-size: 10px;margin-top: 2px;font-weight: 600;">Email</span>
                <span style="color: #131313;font-size: 12px;margin-top: 2px;font-weight: 700;text-transform: uppercase;margin-bottom: 5px;">' . htmlspecialchars($client['email']) . '</span>
                <span style="color: gray;font-size: 10px;margin-top: 2px;font-weight: 600;">Telefone</span>  
                <span style="color: #131313;font-size: 12px;margin-top: 2px;font-weight: 700;text-transform: uppercase;margin-bottom: 5px;">' . htmlspecialchars($client['phone']) . '</span>
                <span style="color: gray;font-size: 10px;margin-top: 2px;font-weight: 600;">Endereço</span>
                <span style="color: #131313;font-size: 12px;margin-top: 2px;font-weight: 700;text-transform: uppercase;margin-bottom: 5px;">' . htmlspecialchars($client['address']) . '</span>
            </div>
            <div class="cliente__footer">
                <div class="cliente__header__actions">
                    <button class="btn btn-sm btn-primary edit-client" 
                            data-bs-toggle="modal" 
                            data-bs-target="#editClientModal"
                            data-id="' . $client['id'] . '"
                            data-name="' . htmlspecialchars($client['name']) . '"
                            data-origin="' . htmlspecialchars($client['origin']) . '"
                            data-email="' . htmlspecialchars($client['email']) . '"
                            data-phone="' . htmlspecialchars($client['phone']) . '"
                            data-address="' . htmlspecialchars($client['address']) . '"
                            style="width: 60px;
  justify-content: center;border-radius: 3px;background: #6854f5;display: flex;align-items: center;gap: 5px;padding: 3px 10px;border: 0;/*! border-radius: 4px; */box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, rgba(0, 0, 0, 0.2) 0px -3px 0px inset;"
                            >
                       
                    <span style="color: white;font-weight: 700;/*! text-transform: uppercase; */margin: 0;padding-bottom: 2px;"=>Editar</span>
                    </button>
                    <button class="btn btn-sm btn-danger delete-client" 
                            data-id="' . $client['id'] . '"
                            style="width: 60px;
  justify-content: center;border-radius: 3px;background: #f55454;display: flex;align-items: center;gap: 5px;padding: 3px 10px;border: 0;/*! border-radius: 4px; */box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, rgba(0, 0, 0, 0.2) 0px -3px 0px inset;">
                        <span style="color: white;font-weight: 700;/*! text-transform: uppercase; */margin: 0;padding-bottom: 2px;"=>Deletar</span>
                        </button>
                        </div>
                        </div>
                        </div>
                        ';

    /*
                        <img src="../img/delete.svg" alt="deletar" width="25">
    echo "<tr>";
    echo "<td>" . htmlspecialchars($client['name']) . "</td>";
    echo "<td>" . htmlspecialchars($client['origin']) . "</td>";
    echo "<td>" . htmlspecialchars($client['email']) . "</td>";
    echo "<td>" . htmlspecialchars($client['phone']) . "</td>";
    echo "<td>" . htmlspecialchars($client['address']) . "</td>";
    echo "<td>
            <button class='btn btn-sm btn-primary edit-client' 
                    data-bs-toggle='modal' 
                    data-bs-target='#editClientModal'
                    data-id='" . $client['id'] . "'
                    data-name='" . htmlspecialchars($client['name']) . "'
                    data-origin='" . htmlspecialchars($client['origin']) . "'
                    data-email='" . htmlspecialchars($client['email']) . "'
                    data-phone='" . htmlspecialchars($client['phone']) . "'
                    data-address='" . htmlspecialchars($client['address']) . "'>
                <i class='bi bi-pencil'></i>
            </button>
            <button class='btn btn-sm btn-danger delete-client' 
                    data-id='" . $client['id'] . "'>
                <i class='bi bi-trash'></i>
            </button>
            </td>";
    echo "</tr>";
    */
}

?>

<script>
    $(document).ready(function() {

        // Edit Client - Populate Modal
        $('.edit-client').click(function() {
            $('#edit_id').val($(this).data('id'));
            $('#edit_name').val($(this).data('name'));
            $('#edit_origin').val($(this).data('origin'));
            $('#edit_email').val($(this).data('email'));
            $('#edit_phone').val($(this).data('phone'));
            $('#edit_address').val($(this).data('address'));
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
    });
</script>