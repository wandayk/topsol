<?php
require_once '../class/connection.php';
header('Content-Type: application/json');

try {
    $search = isset($_GET['search']) ? $_GET['search'] : '';

    if (empty($search)) {
        throw new Exception('Termo de busca não fornecido');
    }

    $stmt = $pdo->prepare("
        SELECT i.*, c.nome as collection_name 
        FROM items i
        LEFT JOIN collections c ON i.idcolecao = c.id 
        WHERE i.nome LIKE ?
        ORDER BY i.categoria, i.nome
        LIMIT 10
    ");

    $stmt->execute(['%' . $search . '%']);
    $items = [];

    while ($item = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $photos = !empty($item['ensaio_fotos']) ?
            json_decode($item['ensaio_fotos'], true) :
            json_decode($item['referencia_foto'], true);

        $itemHtml = '<div class="item-card" style="padding: 0; border-radius: 8px; box-shadow: rgba(9, 30, 66, 0.25) 0px 4px 8px -2px, rgba(9, 30, 66, 0.08) 0px 0px 0px 1px;">';

        // Carousel
        if (!empty($photos)) {
            $carouselId = "search_carousel_" . $item['id'];
            $itemHtml .= '<div id="' . $carouselId . '" class="carousel slide" style="height: 230px;" data-bs-ride="carousel">
                <div class="carousel-indicators">';

            foreach ($photos as $index => $photo) {
                $itemHtml .= '<button type="button" 
                    data-bs-target="#' . $carouselId . '" 
                    data-bs-slide-to="' . $index . '" 
                    ' . ($index === 0 ? 'class="active"' : '') . '></button>';
            }

            $itemHtml .= '</div><div class="carousel-inner">';

            foreach ($photos as $index => $photo) {
                $itemHtml .= '<div class="carousel-item ' . ($index === 0 ? 'active' : '') . '">
                    <img src="' . $photo . '" class="d-block w-100" 
                        style="object-fit: cover; height: 230px; width: 230px; border-radius: 4px;">
                </div>';
            }

            $itemHtml .= '</div>
                <button class="carousel-control-prev" type="button" data-bs-target="#' . $carouselId . '" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#' . $carouselId . '" data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                </button>
            </div>';
        }

        // Item Info
        $itemHtml .= '<div style="margin-top:15px; padding: 0 20px; display:flex; justify-content:space-between; flex-direction: row-reverse;">
            <div>
                <span style="color: gray; font-size: 10px; font-weight: 600;">COLEÇÃO</span>
                <h5 style="margin: 0; font-size: 12px; font-weight: 700;">' . htmlspecialchars($item['collection_name']) . '</h5>
                <span style="color: gray; font-size: 10px; font-weight: 600;">CATEGORIA</span>
                <h6 style="margin: 0; font-size: 12px; font-weight: 700;">' . htmlspecialchars($item['categoria']) . '</h6>
            </div>
            <div>
                <h5 style="margin: 0; font-size: 16px; font-weight: 900;">' . htmlspecialchars(strtoupper($item['nome'])) . '</h5>
                <p style="font-size: 14px; font-weight: 700; color: #acacac;">
                    R$ ' . number_format($item['valor'], 2, ',', '.') . '
                </p>
            </div>
        </div></div>';

        $item['html'] = $itemHtml;
        $items[] = $item;
    }

    echo json_encode([
        'success' => true,
        'items' => $items
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
