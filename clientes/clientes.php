<?php
include 'class/connection.php';
?>

<style>
    .frame__header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-right: 40px;
        width: 100%;
    }

    .frame__body h2 {
        font-family: "Poppins", sans-serif;
        font-weight: 500;
        color: #000;
        margin: 0;
        padding: 0;
    }

    .frame__dash {
        margin-bottom: 20px;
        width: 60px;
        height: 4px;
        background: #5252fc;
        border-radius: 2px;
    }

    .button-name {
        align-items: center;
        appearance: none;
        background-color: #7373ff;
        border-radius: 4px;
        border-width: 0;
        box-shadow: rgba(45, 35, 66, 0.2) 0 2px 4px, rgba(45, 35, 66, 0.15) 0 7px 13px -3px, #d6d6e7 0 -3px 0 inset;
        box-sizing: border-box;
        color: #36395a;
        cursor: pointer;
        display: inline-flex;
        height: 48px;
        justify-content: center;
        line-height: 1;
        list-style: none;
        overflow: hidden;
        padding-left: 16px;
        padding-right: 16px;
        position: relative;
        text-align: left;
        text-decoration: none;
        transition: box-shadow 0.15s, transform 0.15s;
        user-select: none;
        -webkit-user-select: none;
        touch-action: manipulation;
        white-space: nowrap;
        will-change: box-shadow, transform;
        color: white;
        font-size: 14px;
        font-weight: 600;
    }

    .button-name:focus {
        box-shadow:
            #d6d6e7 0 0 0 1.5px inset,
            rgba(45, 35, 66, 0.4) 0 2px 4px,
            rgba(45, 35, 66, 0.3) 0 7px 13px -3px,
            #d6d6e7 0 -3px 0 inset;
    }

    .button-name:hover {
        box-shadow:
            rgba(45, 35, 66, 0.3) 0 4px 8px,
            rgba(45, 35, 66, 0.2) 0 7px 13px -3px,
            #d6d6e7 0 -3px 0 inset;
        transform: translateY(-2px);
    }

    .button-name:active {
        box-shadow: #d6d6e7 0 3px 7px inset;
        transform: translateY(2px);
    }

    .modal {
        backdrop-filter: blur(10px);
    }

    .modal-header {
        padding: 20px 40px;
    }

    .modal-header h5 {
        font-size: 20px;
        font-weight: 700;
        margin-left: 10px;
        color: #3c3b3b
    }

    .modal-body {
        padding: 20px 40px;
    }

    .modal-footer {
        padding: 0;
        margin: 0;
    }

    .modal-footer button {
        padding: 10px;
        margin: 0;
        width: 100%;
        border-radius: 0;
        font-size: 15px;
        font-weight: 500;
    }

    .form-control {
        border: none;
        border-bottom: 1px solid #80808075;
        border-radius: 0;
        text-transform: uppercase;
    }

    .form-label {
        font-size: 14px;
        font-weight: 600;
    }

    form {
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }

    .frame__content {
        height: 100%;
        padding-bottom: 20px;
        overflow: hidden;
        display: flex;
    }



    .frame__content__list {
        height: 100%;
        width: 100%;
        overflow: hidden;
        border-radius: 5px;
        margin-left: 40px;
        display: flex;
        flex-direction: column;

    }

    .frame__content__list__header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-right: 40px;
        padding-bottom: 20px;

    }

    .frame__content__list__body {
        height: 100%;
        padding-bottom: 20px;
        overflow: auto;
        padding-right: 40px;
        padding-left: 5px;
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        justify-content: space-between;
        padding-bottom: 5px;
    }

    /* From Uiverse.io by joe-watson-sbf */
    .search {
        display: flex;
        align-items: center;
        justify-content: space-between;
        text-align: center;
        width: 100%;
        border: 1px solid #8080803b;
        border-radius: 3px;
        margin-left: 4px;
    }

    .search__input {
        font-family: inherit;
        font-size: inherit;
        background-color: #f4f3f300;
        color: #646464;
        padding: 0.7rem 1rem;
        border: 0;
        transition: all ease-in-out .5s;
        margin-right: -2rem;
        width: 100%;
    }



    .search__input:focus {
        outline: none;
        background-color: rgba(240, 238, 238, 0);
    }

    .search__input::-webkit-input-placeholder {
        font-weight: 100;
        color: #ccc;
    }

    .search__input:focus+.search__button {
        background-color: rgba(240, 238, 238, 0);
    }

    .search__button {
        border: none;
        background-color: #f4f2f200;
        display: flex;
        padding-right: 20px;

    }

    .search__button:hover {
        cursor: pointer;
    }

    .search__icon {
        height: 1.3em;
        width: 1.3em;
        fill: #b4b4b4;
    }

    .modal-backdrop {
        width: 0 !important;
        height: 0 !important;
    }

    .frame__content__dashboard {
        display: flex;
    }

    .cliente__header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 5px 20px;
        border-bottom: 1px solid #80808040;
    }

    .cliente__header__name {
        display: flex;
        text-transform: uppercase;
        flex-direction: column;
        gap: 3px;
    }

    .cliente {
        background: #fff;
        border-radius: 5px;
        overflow: hidden;
        width: 48%;
        height: fit-content;
        border: 1px solid #8080803b;
        box-shadow: rgba(9, 30, 66, 0.25) 0px 4px 8px -2px, rgba(9, 30, 66, 0.08) 0px 0px 0px 1px;
    }

    .cliente__info {
        padding: 10px 20px;
        display: flex;
        flex-direction: column;
    }

    .cliente__footer {
        display: flex;
        justify-content: flex-end;
        padding: 5px 20px;
        border-top: 1px solid #80808040;
    }

    .cliente__header__actions {
        display: flex;
        gap: 10px;
    }

    @media screen and (max-width: 768px) {
        .frame__body {
            overflow-y: scroll;
            padding: 20px 0 70px 40px;
            display: unset;
        }

        .frame__content {
            flex-direction: column;
            padding-right: 40px;
            height: unset;
            overflow: unset;
        }

        .frame__content__list {
            margin-left: 0;
            height: unset;
            overflow: unset;
        }

        .frame__content__list__header {
            padding-right: 0;
            padding-top: 25px;
        }

        .search {
            margin-left: 0;
        }

        .frame__content__list__body {
            height: unset;
            overflow: unset;
            padding-right: 0;
            padding-left: 0;
        }

        .cliente {
            width: 100%;
        }
    }
</style>


<div class="frame__header">

    <h2>Clientes</h2>


    <button type="button" class="button-name" data-bs-toggle="modal" data-bs-target="#addClientModal">
        <img src="img/cliente.png" alt="cliente" width="20px" style="margin-right: 10px;">NOVO CLIENTE
    </button>

</div>
<div class="frame__dash"></div>
<div class="frame__content">
    <div class="frame__content__dashboard">
        <?php
        include 'clientes/dashboard.php';
        ?>
    </div>
    <div class="frame__content__list">

        <div class="frame__content__list__header">
            <div class="search">
                <input type="text" class="search__input" id="searchInput" placeholder="Procure...">
                <button class="search__button">
                    <svg class="search__icon" aria-hidden="true" viewBox="0 0 24 24">
                        <g>
                            <path d="M21.53 20.47l-3.66-3.66C19.195 15.24 20 13.214 20 11c0-4.97-4.03-9-9-9s-9 4.03-9 9 4.03 9 9 9c2.215 0 4.24-.804 5.808-2.13l3.66 3.66c.147.146.34.22.53.22s.385-.073.53-.22c.295-.293.295-.767.002-1.06zM3.5 11c0-4.135 3.365-7.5 7.5-7.5s7.5 3.365 7.5 7.5-3.365 7.5-7.5 7.5-7.5-3.365-7.5-7.5z"></path>
                        </g>
                    </svg>
                </button>
            </div>
        </div>

        <div class="frame__content__list__body">
            <tbody>
                <?php
                include 'clientes/search.php';
                ?>
            </tbody>
        </div>
    </div>
</div>


<!-- Add Client Modal -->
<div class="modal fade" id="addClientModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <img src="img/clientes.png" alt="clientes" width="30">
                <h5 class="modal-title">NOVO CLIENTE</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addClientForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nome</label>
                        <input type="text" class="form-control" name="name" placeholder="Ex: João da Silva" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Origem</label>
                        <input type="text" class="form-control" name="origin" placeholder="Ex: Hospital, Internet, indicação, etc." required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" placeholder="Ex: joaodasilva@gmail.com" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Telefone</label>
                        <input type="tel" class="form-control" name="phone" placeholder="Ex: (11) 99999-9999">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Endereço</label>
                        <textarea class="form-control" name="address" rows="3" placeholder="Ex: Rua das Flores, 123" style="border: 1px solid #8080806e;border-radius: 4px;"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">SALVAR CLIENTE</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Client Modal -->
<div class="modal fade" id="editClientModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <img src="img/clientes.png" alt="clientes" width="30">
                <h5 class="modal-title">EDITAR CLIENTE</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editClientForm">
                <input type="hidden" name="id" id="edit_id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" class="form-control" name="name" id="edit_name" placeholder="Ex: João da Silva" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Origem</label>
                        <input type="text" class="form-control" name="origin" id="edit_origin" placeholder="Ex: Hospital, Internet, indicação, etc." required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" id="edit_email" placeholder="Ex: joaodasilva@gmail.com" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Telefone</label>
                        <input type="tel" class="form-control" name="phone" id="edit_phone" placeholder="Ex: (11) 99999-9999">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Endereço</label>
                        <textarea class="form-control" name="address" id="edit_address" rows="3" placeholder="Ex: Rua das Flores, 123" style="border: 1px solid #8080806e;border-radius: 4px;"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">ATUALIZAR DADOS</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {

        let searchTimer;

        // Live Search with AJAX
        $("#searchInput").on("keyup", function() {
            clearTimeout(searchTimer);
            const searchText = $(this).val();

            // Add loading indicator
            if (searchText.length > 0) {
                $(".frame__content__list__body").addClass("opacity-50");
            }

            // Debounce the search to prevent too many requests
            searchTimer = setTimeout(function() {
                $.ajax({
                    url: 'clientes/search.php',
                    type: 'POST',
                    data: {
                        search: searchText
                    },
                    success: function(response) {
                        $(".frame__content__list__body").html(response);
                        $(".frame__content__list__body").removeClass("opacity-50");
                    }
                });
            }, 300); // Wait 300ms after user stops typing
        });


        // Add Client
        $('#addClientForm').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: 'clientes/actions.php',
                type: 'POST',
                data: $(this).serialize() + '&action=add',
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        alert('Error: ' + response.message);
                    }
                }
            });
        });

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

    window.addEventListener('load', function() {
        setTimeout(function() {
            window.scrollTo(1, 0);
            window.addEventListener('popstate', popStateHandler, false);
        }, 0);
    }, false);
</script>