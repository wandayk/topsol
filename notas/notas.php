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
        width: 300px;
        overflow: hidden;
        border-radius: 5px;
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
        overflow: auto;
        margin-right: 40px;
        display: flex;
        padding-bottom: 5px;
        flex-direction: column;
        border-top: 1px solid #80808040;
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

    .notas__header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
        border-bottom: 1px solid #80808040;
        width: 100%;
        cursor: pointer;
        transition: all 0.3s;
    }

    .notas__header:hover {
        background: #f4f2f2;
        padding: 10px 5px;
        border-left: 1px solid #80808040;
        border-right: 1px solid #80808040;
    }


    .notas__header__name {
        display: flex;
        text-transform: uppercase;
        gap: 12px;
        justify-content: center;
        flex-direction: column;
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

    .status-closed {
        background: rgb(15, 213, 8);
        color: #fff;
        padding: 2px 10px;
        border-radius: 5px;
        font-size: 10px;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 5px;
        box-shadow: rgba(50, 50, 93, 0.25) 0px 2px 5px -1px, rgba(0, 0, 0, 0.3) 0px 1px 3px -1px;
        border: 1px solid #e2e2e2;
    }

    .status-open {
        background: #d5bf08;
        color: #fff;
        padding: 2px 10px;
        border-radius: 5px;
        font-size: 10px;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 5px;
        box-shadow: rgba(50, 50, 93, 0.25) 0px 2px 5px -1px, rgba(0, 0, 0, 0.3) 0px 1px 3px -1px;
        border: 1px solid #e2e2e2;
    }

    .frame__notas__header__name__title {
        display: flex;
        justify-content: space-between;
        border-bottom: 1px solid #8080803b;
        gap: 40px;
        padding: 20px;
        background: #f6f6f6;
    }

    .frame__notas__body {
        display: flex;
        justify-content: space-between;
        flex-direction: column;
        height: 100%;
        overflow: hidden;
    }

    .frame__notas__header__info {
        padding: 20px;
        display: flex;
        flex-direction: column;
        gap: 10px;
        width: 100%;
        height: 100%;
        overflow: auto;
    }

    .frame__notas__header__info p {
        margin: 0;
        padding: 0;
    }

    .frame__notas__header__itens {
        position: relative;
        display: flex;
        flex-direction: column;
        width: 100%;
        margin-right: 40px;
        border: 1px solid #a4a4a44d;
        box-shadow: rgba(0, 0, 0, 0.25) 0px 0.0625em 0.0625em, rgba(0, 0, 0, 0.25) 0px 0.125em 0.5em, rgba(255, 255, 255, 0.1) 0px 0px 0px 1px inset;
    }

    .frame__container {
        display: flex;
        justify-content: space-between;
        gap: 60px;
        height: 100%;
        overflow: hidden;
    }

    .frame__notas {
        width: 50%;
        display: flex;
        flex-direction: column;
        border: 1px solid #a4a4a44d;
        box-shadow: rgba(0, 0, 0, 0.25) 0px 0.0625em 0.0625em, rgba(0, 0, 0, 0.25) 0px 0.125em 0.5em, rgba(255, 255, 255, 0.1) 0px 0px 0px 1px inset;
    }

    .frame__notas__header__itens__header {
        padding: 20px;
        display: flex;
        gap: 5px;
        justify-content: space-between;
        align-items: center;
        background: #f6f6f6;
        border-bottom: 1px solid #8080803b;
        position: relative;
    }

    .frame__notas__header__itens__header:before {
        content: '';
        position: absolute;
        left: -22px;
        top: 50%;
        transform: translateY(-50%);
        width: 0;
        height: 0;
        border-top: 10px solid transparent;
        border-bottom: 10px solid transparent;
        border-right: 22px solid #f6f6f6;
        filter: drop-shadow(-3px 0px 2px rgba(83, 82, 82, 0.63));
    }
</style>


<div class="frame__header">

    <h2>Notas</h2>


    <button type="button" class="button-name" data-bs-toggle="modal" data-bs-target="#addNoteModal">
        </i><img src="img/notas_white.svg" alt="cliente" width="20px" style="margin-right: 10px;">NOVA NOTA
    </button>

</div>
<div class="frame__dash"></div>
<div class="frame__content">
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
            <?php
            include 'notas/search.php';
            ?>
        </div>
    </div>
    <div id="frame__content__collection" style="width: 100%;padding: 0 0 0 40px;">
    </div>
</div>


<!-- Create notes -->
<div class="modal fade" id="addNoteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">NOVA NOTA</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addNoteForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Cliente</label>
                        <select class="form-select" name="client_id" required>
                            <option value="">Selecione o cliente</option>
                            <?php

                            $clients = $pdo->query("SELECT id, name FROM clients ORDER BY name");
                            while ($client = $clients->fetch()) {
                                echo "<option value='" . $client['id'] . "'>" . $client['name'] . "</option>";
                            }

                            ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Forma de Pagamento</label>
                        <select class="form-select" name="payment_method" id="payment_method" required>
                            <option value="PIX">PIX</option>
                            <option value="Cartão">Cartão</option>
                            <option value="Dinheiro">Dinheiro</option>
                        </select>
                    </div>

                    <div class="mb-3" style="display: flex;justify-content: space-between;align-items: center;">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_installment" id="is_installment">
                            <label class="form-check-label" for="is_installment">
                                Pagamento Parcelado
                            </label>
                        </div>
                        <div style="display: none;" id="installmentDiv" style="width: 150px;">
                            <input type="number" class="form-control" name="installment_count" min="2" max="12">
                        </div>
                    </div>


                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">CRIAR NOTA</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#is_installment').change(function() {
            if ($(this).is(':checked')) {
                $('#installmentDiv').slideDown();
                $('input[name="installment_count"]').prop('required', true);
            } else {
                $('#installmentDiv').slideUp();
                $('input[name="installment_count"]').prop('required', false).val('');
            }
        });

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
                    url: 'notas/search.php',
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


        $('#addNoteForm').submit(function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            formData.append('action', 'add_note');

            $.ajax({
                url: 'notas/actions.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        // Reset form
                        $('#addNoteForm')[0].reset();
                        $('#addNoteModal').modal('hide');

                        // Reload notes list
                        location.reload();
                    } else {
                        alert('Erro ao criar nota: ' + response.message);
                    }
                },
                error: function() {
                    alert('Erro ao processar requisição');
                }
            });
        });
    });
</script>