<?php
include '\xampp\htdocs\class\connection.php';

// Get collection ID from URL
$collection_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Get collection details
$stmt = $pdo->prepare("SELECT * FROM collections WHERE id = ?");
$stmt->execute([$collection_id]);
$collection = $stmt->fetch();

// Get items from this collection
$stmt = $pdo->prepare("SELECT * FROM items WHERE idcolecao = ? ORDER BY categoria, nome");
$stmt->execute([$collection_id]);
$items = $stmt->fetchAll();





?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <title>Detalhes da Coleção</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
</head>

<style>
    .frame__colecoes {
        height: 100%;
        display: flex;
        gap: 100px;
    }

    .frame__colecoes__header {
        display: flex;
        flex-direction: column;
    }

    .frame__colecoes__header__name {
        display: flex;
        border-bottom: 1px solid #80808057;
        padding-bottom: 10px;
        flex-direction: column;
    }

    .frame__colecoes__header__name h2 {
        font-size: 45px;
        font-weight: 900;
        color: #4b4b4b;
        line-height: 0.8;
    }

    .frame__colecoes__header__name__avatar {
        display: flex;
        gap: 5px;
        flex-direction: column;
    }

    .frame__colecoes__header__info {
        display: flex;
        flex-direction: column;
        padding: 20px 0;
        gap: 10px;
    }

    .frame__colecoes__header__info p {
        margin: 0;
        padding: 0;
    }

    .frame__colecoes__body {
        overflow: hidden;
        width: 100%;
    }

    .frame__colecoes__body__add {
        display: flex;
    }

    .frame__colecoes__body__list {
        overflow: auto;
        height: 100%;
        padding-right: 40px;
    }

    .modal {
        position: fixed;
        top: 0;
        left: 0;
        z-index: 1055 !important;
        width: 100vw !important;
        height: 100vh !important;
        overflow-x: auto;
    }
</style>

<body>
    <div class="frame__colecoes">
        <!-- Collection Header -->
        <div class="frame__colecoes__header">
            <div class="frame__colecoes__header__name">
                <div class="frame__colecoes__header__name__avatar">
                    <div style="display: flex;justify-content: space-between;">
                        <h2><?php echo htmlspecialchars(strtoupper($collection['nome'])); ?></h2>
                        <button type="button" class="btn-close btn-close-collection" aria-label="Close"></button>
                    </div>
                    <span style="display: flex;flex-direction: column;">
                        <p style="color: gray;font-size: 10px;font-weight: 600;margin: 0;padding: 0;line-height: 1;text-wrap: nowrap;">Data de Lançamento</p>
                        <p style="color: black;font-weight: 700;font-size: 12px;"> <?php echo date('d/m/Y', strtotime($collection['data_lancamento'])); ?></p>
                    </span>
                </div>
                <div class="frame__colecoes__body__add">
                    <button type="button" class="button-name" data-bs-toggle="modal" data-bs-target="#addItemModal" style="padding: 13px 5px;margin: 0;font-size: 11px;font-weight: 700;color: whitesmoke;background-color: #309f56;height: 0;">
                        <span style="font-size: 20px;font-weight: 700;line-height: 1;margin-bottom: 7px;margin-right: 10px;">+</span>
                        <span style="margin-bottom: 4px;font-size: 12px;font-weight: 600;margin-right: 8px;">ITEM</span>
                    </button>
                </div>
            </div>
            <div class="frame__colecoes__header__info">
                <span>
                    <p style="color: gray;font-size: 10px;font-weight: 600;margin: 0;padding: 0;line-height: 1;">Data de Criação</p>
                    <p style="color: black;font-weight: 700;font-size: 12px;"> <?php echo date('d/m/Y', strtotime($collection['data_criacao'])); ?></p>
                </span>

                <?php
                $totalItems = count($items);
                $categoryTotals = [];
                foreach ($items as $item) {
                    if (!isset($categoryTotals[$item['categoria']])) {
                        $categoryTotals[$item['categoria']] = 0;
                    }
                    $categoryTotals[$item['categoria']] += 1;
                }

                // Sort categories by name
                ksort($categoryTotals);
                ?>
                <span>
                    <p style="color: gray;font-size: 10px;font-weight: 600;margin: 0;padding: 0;line-height: 1;">Total de Itens</p>
                    <p style="color: black;font-weight: 700;font-size: 12px;"> <?php echo $totalItems; ?></p>
                </span>
                <div>
                    <?php foreach ($categoryTotals as $categoria => $total): ?>
                        <span style="display: flex; justify-content: space-between; margin-bottom: 5px;align-items: center;">
                            <p style="color: gray; font-size: 10px; font-weight: 600; margin: 0; padding: 0; line-height: 1;">
                                <?php echo htmlspecialchars($categoria); ?>
                            </p>
                            <p style="color: black; font-weight: 700; font-size: 12px; margin: 0;">
                                <?php echo $total; ?>
                            </p>
                        </span>
                    <?php endforeach; ?>
                </div>

            </div>
        </div>

        <!-- Items Table -->
        <div class="frame__colecoes__body">

            <div class="frame__colecoes__body__list">
                <?php
                // Group items by category
                $itemsByCategory = [];
                foreach ($items as $item) {
                    $itemsByCategory[$item['categoria']][] = $item;
                }
                // Sort categories alphabetically
                ksort($itemsByCategory);

                // Display items by category
                foreach ($itemsByCategory as $categoria => $categoryItems):
                ?>
                    <div class="category-section mb-4">
                        <h4 class="category-title" style="color: #969696; font-size: 19px; font-weight: 700; text-align: end;margin: 0 0 20px 0;border-right: 5px solid #5252fc5c;padding-right: 20px;">
                            <?php echo htmlspecialchars($categoria); ?>
                        </h4>

                        <div class="category-section-item" style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;padding-left: 5px;direction: rtl;">
                            <?php foreach ($categoryItems as $item): ?>
                                <div style="padding: 0; border-radius: 8px;box-shadow: rgba(9, 30, 66, 0.25) 0px 4px 8px -2px, rgba(9, 30, 66, 0.08) 0px 0px 0px 1px;">
                                    <div style="display: flex; flex-direction: column; gap: 10px;">
                                        <?php
                                        $photos = !empty(json_decode($item['ensaio_fotos'], true)) ?
                                            json_decode($item['ensaio_fotos'], true) :
                                            json_decode($item['referencia_foto'], true);

                                        if (!empty($photos)):
                                            $carouselId = "carousel_" . $item['id'];
                                        ?>
                                            <div id="<?php echo $carouselId; ?>" class="carousel slide" style="height: 230px;" data-bs-ride="carousel">
                                                <div class="carousel-indicators">
                                                    <?php foreach ($photos as $index => $photo): ?>
                                                        <button type="button"
                                                            data-bs-target="#<?php echo $carouselId; ?>"
                                                            data-bs-slide-to="<?php echo $index; ?>"
                                                            <?php echo $index === 0 ? 'class="active"' : ''; ?>
                                                            aria-current="true">
                                                        </button>
                                                    <?php endforeach; ?>
                                                </div>
                                                <div class="carousel-inner">
                                                    <?php foreach ($photos as $index => $photo): ?>
                                                        <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                                                            <img src="<?php echo $photo; ?>"
                                                                class="d-block w-100"
                                                                style="object-fit: cover; height: 230px; width: 230px; border-radius: 4px;">
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                                <button class="carousel-control-prev" type="button" data-bs-target="#<?php echo $carouselId; ?>" data-bs-slide="prev">
                                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                </button>
                                                <button class="carousel-control-next" type="button" data-bs-target="#<?php echo $carouselId; ?>" data-bs-slide="next">
                                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                </button>
                                            </div>
                                        <?php endif; ?>
                                        <div style="margin-top:15px;padding: 0 20px;display:flex;justify-content:space-between;flex-direction: row-reverse;">
                                            <div style="display: flex; justify-content: center; gap: 5px;flex-direction: column;margin-bottom: 15px;">
                                                <button class="btn btn-sm btn-primary edit-item" data-id="<?php echo $item['id']; ?>"
                                                    style="padding: 5px;margin: 0;background: #5050dbb2;border: 0;display: flex;justify-content: center;width: fit-content;"><img src="img/edit_white.svg" alt="" width="15"></button>
                                                <button class="btn btn-sm btn-danger delete-item" data-id="<?php echo $item['id']; ?>"
                                                    style="padding: 5px;margin: 0;background:rgba(219, 80, 80, 0.7);border: 0;display: flex;justify-content: center;width: fit-content;"><img src="img/delete_white.svg" alt="" width="15"></button>
                                            </div>
                                            <div>
                                                <h5 style="margin: 0; font-size: 16px; font-weight: 900;"><?php echo htmlspecialchars(strtoupper($item['nome'])); ?></h5>
                                                <p style=" font-size: 14px; font-weight: 700; color: #acacac;">
                                                    R$ <?php echo number_format($item['valor'], 2, ',', '.'); ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <div class="modal fade" id="addItemModal" tabindex="-1" aria-labelledby="addItemModalLabel" aria-hidden="true" style="overflow: hidden !important;">
        <div class="modal-dialog" style="overflow: hidden;height: 100vh;margin: 0 auto;padding: 30px 0;">
            <div class="modal-content" style="height: 100%;">
                <div class="modal-header">
                    <div>
                        <span style="font-size: 10px;font-weight: 800;color: gray;">COLEÇÃO</span>
                        <h5 class="modal-title" id="addItemModalLabel" style="margin: 0;line-height: 1;"><?php echo htmlspecialchars(strtoupper($collection['nome'])); ?></h5>
                        <span style="font-size: 12px;font-weight: 900;color: #5c85ff;">ADICIONAR ITEM</span>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="overflow: auto;">
                    <form id="addItemForm" enctype="multipart/form-data" style="padding: 5px;">
                        <input type="hidden" name="idcolecao" value="<?php echo $collection_id; ?>">

                        <div class="mb-3">
                            <label for="nome" class="form-label">Nome do Item</label>
                            <input type="text" class="form-control" id="nome" name="nome" required>
                        </div>
                        <div class="mb-3">
                            <label for="categoria" class="form-label">Categoria</label>
                            <select class="form-select" id="categoria" name="categoria" required>
                                <option value="">Selecione uma categoria</option>
                                <option value="Leggins">Leggins</option>
                                <option value="Short">Short</option>
                                <option value="Top">Top</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="valor" class="form-label">Valor (R$)</label>
                            <input type="number" step="0.01" class="form-control" id="valor" name="valor" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Referência</label>
                            <div id="insert_referencias" class="d-flex flex-wrap gap-2 mb-2"></div>
                            <div class="input-group">
                                <input type="file" class="form-control" id="referencia" name="referencia[]" accept="image/*" multiple>
                            </div>
                            <small class="text-muted">Foto de referência do produto</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Ensaio Fotográfico</label>
                            <div id="insert_ensaios" class="d-flex flex-wrap gap-2 mb-2"></div>
                            <div class="input-group">
                                <input placeholder="Procurar" type="file" class="form-control" id="ensaio" name="ensaio[]" accept="image/*" multiple>
                            </div>
                            <small class="text-muted">Fotos do ensaio (múltiplas)</small>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="saveItem">Salvar Item</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editItemModal" tabindex="-1" aria-labelledby="editItemModalLabel" aria-hidden="true" style="overflow: hidden !important;">
        <div class="modal-dialog" style="overflow: hidden;height: 100vh;margin: 0 auto;padding: 30px 0;">
            <div class="modal-content" style="height: 100%;">
                <div class="modal-header">
                    <div>
                        <span style="font-size: 10px;font-weight: 800;color: gray;">COLEÇÃO</span>
                        <h5 class="modal-title" id="addItemModalLabel" style="margin: 0;line-height: 1;"><?php echo htmlspecialchars(strtoupper($collection['nome'])); ?></h5>
                        <span style="font-size: 12px;font-weight: 900;color: #5c85ff;">EDITAR ITEM</span>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="overflow: auto;">
                    <form id="editItemForm" enctype="multipart/form-data" style="padding: 5px;">
                        <input type="hidden" name="item_id" id="edit_item_id">
                        <input type="hidden" name="idcolecao" value="<?php echo $collection_id; ?>">

                        <div class="mb-3">
                            <label for="edit_nome" class="form-label">Nome do Item</label>
                            <input type="text" class="form-control" id="edit_nome" name="nome" required>
                        </div>

                        <div class="mb-3">
                            <label for="edit_categoria" class="form-label">Categoria</label>
                            <select class="form-select" id="edit_categoria" name="categoria" required>
                                <option value="">Selecione uma categoria</option>
                                <option value="Leggins">Leggins</option>
                                <option value="Short">Short</option>
                                <option value="Top">Top</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="edit_valor" class="form-label">Valor (R$)</label>
                            <input type="number" step="0.01" class="form-control" id="edit_valor" name="valor" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Referências Atuais</label>
                            <div id="current_referencias" class="d-flex flex-wrap gap-2 mb-2"></div>
                            <div class="input-group">
                                <input placeholder="Procurar" type="file" class="form-control" id="edit_referencia" name="referencia[]" accept="image/*" multiple>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Ensaios Atuais</label>
                            <div id="current_ensaios" class="d-flex flex-wrap gap-2 mb-2"></div>
                            <div class="input-group">
                                <input placeholder="Procurar" type="file" class="form-control" id="edit_ensaio" name="ensaio[]" accept="image/*" multiple>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="updateItem">Atualizar</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#saveItem').click(function() {
                const formData = new FormData($('#addItemForm')[0]);
                formData.append('action', 'add_item');

                $.ajax({
                    url: 'colecoes/actions.php',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (!response.success) {
                            alert('Erro: ' + response.message);
                        } else {
                            var idcolecao = $("[name='idcolecao']").val();

                            $.ajax({
                                url: 'colecoes/item.php',
                                type: 'GET',
                                data: {
                                    id: idcolecao
                                },
                                success: function(response) {
                                    $("#frame__content__collection").html(response);
                                }
                            });
                        }
                    }
                });



            });

            $('.btn-close-collection').click(function() {
                const mobileCollection = $('#frame__content__collection__mobile');
                const buttonCloseCollection = $('.btn-close-collection');

                mobileCollection.css('width', '0');
                buttonCloseCollection.css('display', 'none');

                // Clear content after animation
                setTimeout(() => {
                    mobileCollection.empty();
                }, 300); // Match transition duration
            });

            $('.edit-item').click(function() {

                const id = $(this).data('id');
                // Fetch item data
                $.ajax({
                    url: 'colecoes/get.php',
                    type: 'GET',
                    data: {
                        id: id,
                        action: 'get_item'
                    },
                    success: function(response) {
                        const item = response.data;
                        $('#edit_item_id').val(item.id);
                        $('#edit_nome').val(item.nome);
                        $('#edit_categoria').val(item.categoria);
                        $('#edit_valor').val(item.valor);

                        // Display current images
                        const referencias = JSON.parse(item.referencia_foto || '[]');
                        const ensaios = JSON.parse(item.ensaio_fotos || '[]');

                        $('#current_referencias').html(referencias.map(ref =>
                            `<div class="position-relative">
                                <img src="${ref}" class="img-thumbnail" style="height: 100px">
                                <button type="button" class="btn-close position-absolute top-0 end-0" 
                                data-path="${ref}" onclick="removeImage(this, 'referencia')"></button>
                            </div>`
                        ).join(''));

                        $('#current_ensaios').html(ensaios.map(ens =>
                            `<div class="position-relative">
                                <img src="${ens}" class="img-thumbnail" style="height: 100px">
                                <button type="button" class="btn-close position-absolute top-0 end-0" 
                                data-path="${ens}" onclick="removeImage(this, 'ensaio')"></button>
                            </div>`
                        ).join(''));

                        $('#editItemModal').modal('show');
                    }
                });



            });

            $('.delete-item').click(function() {
                if (confirm('Tem certeza que deseja excluir este item?')) {
                    const itemId = $(this).data('id');

                    $.ajax({
                        url: 'colecoes/actions.php',
                        type: 'POST',
                        data: {
                            action: 'delete_item',
                            item_id: itemId
                        },
                        success: function(response) {
                            if (response.success) {
                                var idcolecao = $("[name='idcolecao']").val();
                                $.ajax({
                                    url: 'colecoes/item.php',
                                    type: 'GET',
                                    data: {
                                        id: idcolecao
                                    },
                                    success: function(html) {
                                        $("#frame__content__collection").html(html);
                                    }
                                });
                            } else {
                                alert('Erro ao deletar item: ' + response.message);
                            }
                        }
                    });
                }
            });

            // Update item submit handler
            $('#updateItem').click(function() {
                const formData = new FormData($('#editItemForm')[0]);
                formData.append('action', 'update_item');

                $.ajax({
                    url: 'colecoes/actions.php',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (!response.success) {
                            alert('Erro: ' + response.message);
                        } else {

                            var idcolecao = $("[name='idcolecao']").val();

                            $.ajax({
                                url: 'colecoes/item.php',
                                type: 'GET',
                                data: {
                                    id: idcolecao
                                },
                                success: function(response) {
                                    $("#frame__content__collection").html(response);
                                }
                            });

                        }
                    }
                });



            });

            // Preview for reference photo
            $('#referencia').change(function(e) {
                $('#insert_referencias').empty();
                const files = e.target.files;
                for (let i = 0; i < files.length; i++) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $('#insert_referencias').append(`
                        <div class="position-relative">
                        <img src="${e.target.result}" class="img-thumbnail" style="height: 100px">
                        </div>`);
                    }
                    reader.readAsDataURL(files[i]);
                }
            });

            // Preview for photoshoot images
            $('#ensaio').change(function(e) {
                $('#insert_ensaios').empty();
                const files = e.target.files;
                for (let i = 0; i < files.length; i++) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $('#insert_ensaios').append(`
                        <div class="position-relative">
                        <img src="${e.target.result}" class="img-thumbnail" style="height: 100px">
                        </div>`);
                    }
                    reader.readAsDataURL(files[i]);
                }
            });

            // Preview for reference photo
            $('#edit_referencia').change(function(e) {
                $('#current_referencias').empty();
                const files = e.target.files;
                for (let i = 0; i < files.length; i++) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $('#current_referencias').append(`
                        <div class="position-relative">
                        <img src="${e.target.result}" class="img-thumbnail" style="height: 100px">
                        </div>`);
                    }
                    reader.readAsDataURL(files[i]);
                }
            });

            // Preview for photoshoot images
            $('#edit_ensaio').change(function(e) {
                $('#current_ensaios').empty();
                const files = e.target.files;
                for (let i = 0; i < files.length; i++) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $('#current_ensaios').append(`
                        <div class="position-relative">
                        <img src="${e.target.result}" class="img-thumbnail" style="height: 100px">
                        </div>`);
                    }
                    reader.readAsDataURL(files[i]);
                }
            });

            const mainCollection = $('#frame__content__collection');
            const buttonCloseCollection = $('.btn-close-collection');
            if (mainCollection.css('display') === 'none') {
                buttonCloseCollection.css('display', 'block');
            }
        });

        function removeImage(button, type) {
            if (confirm('Deseja remover esta imagem?')) {
                const path = $(button).data('path');
                $.ajax({
                    url: 'colecoes/actions.php',
                    type: 'POST',
                    data: {
                        action: 'remove_image',
                        item_id: $('#edit_item_id').val(),
                        image_path: path,
                        image_type: type
                    },
                    success: function(response) {
                        if (response.success) {
                            $(button).parent().remove();
                        }
                    }
                });
            }
        }
    </script>
</body>

</html>