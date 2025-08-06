<?php

setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set('America/Sao_Paulo');
// connection.php
include 'class/connection.php';

// Query to get total number of clients
$totalClientsQuery = "SELECT COUNT(*) as total FROM clients";
$totalClientsStmt = $pdo->prepare($totalClientsQuery);
$totalClientsStmt->execute();
$totalClients = $totalClientsStmt->fetch(PDO::FETCH_ASSOC)['total'];

// Query to get number of clients grouped by origin
$clientsByOriginQuery = "SELECT origin, COUNT(*) as count FROM clients GROUP BY origin";
$clientsByOriginStmt = $pdo->prepare($clientsByOriginQuery);
$clientsByOriginStmt->execute();
$clientsByOrigin = $clientsByOriginStmt->fetchAll(PDO::FETCH_ASSOC);


// Query to get client growth for each month in the last 6 months
$clientGrowthQuery = "
    SELECT 
        DATE_FORMAT(created_at, '%Y-%m') as month, 
        COUNT(*) as count 
    FROM clients 
    WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH) 
    GROUP BY month 
    ORDER BY month";
$clientGrowthStmt = $pdo->prepare($clientGrowthQuery);
$clientGrowthStmt->execute();
$clientGrowth = $clientGrowthStmt->fetchAll(PDO::FETCH_ASSOC);

// Prepare data for the chart
$months = [];
$counts = [];
$start = new DateTime('-6 months');
$end = new DateTime('+1 months');
$interval = new DateInterval('P1M');
$period = new DatePeriod($start, $interval, $end);

foreach ($period as $date) {
    $month = $date->format('Y-m');
    $months[] = strftime('%B', $date->getTimestamp());
    $counts[] = 0;
    foreach ($clientGrowth as $growth) {
        if ($growth['month'] == $month) {
            $counts[count($counts) - 1] = $growth['count'];
            break;
        }
    }
}
?>
<style>
    #client-stats {
        width: 400px;
        margin: auto;
        height: 100%;
        display: flex;
        flex-direction: column;
        overflow: auto;
    }

    h2,
    h3 {
        color: #333;
    }

    ul {
        list-style-type: none;
        padding: 0;
        margin: 20px 26px 20px 0;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    h4 {
        color: #333;
        margin: 0;
        line-height: 1;
        font-weight: 600;
        font-size: 18px;
    }

    h1 {
        font-size: 60px;
        margin: 0;
        color: #858585;
        line-height: 1;
    }

    span {
        color: gray;
        font-size: 12px;
        margin-top: 2px;
    }

    .client-stats-total {
        display: flex;
        align-items: end;
        gap: 10px;
        margin-bottom: 20px;
    }

    #clientGrowthChart {
        margin: auto !important;
        width: 100% !important;
        height: 100% !important;
    }

    @media screen and (max-width: 768px) {
        ul {
            margin: 20px 0 20px 0;
        }

    }
</style>


<div id="client-stats">
    <h4>Total</h4>
    <div class="client-stats-total">
        <h1><?php echo $totalClients; ?></h1><span style="font-size: 14px;font-weight: 600;color: black;">Clientes cadastrados</span>
    </div>

    <ul>
        <?php foreach ($clientsByOrigin as $origin): ?>
            <li>
                <div style="margin: 0;padding: 0;display: flex;justify-content: space-between;">
                    <div style="display: flex;gap: 10px;">
                        <span style="text-transform: uppercase;line-height: 1;font-weight: 700;color: black;">
                            <?php echo $origin['origin']; ?>:
                        </span>
                        <span style="line-height: 1;font-weight: 700;color: black;">
                            <?php echo $origin['count']; ?>
                        </span>
                    </div>
                    <span style="line-height: 1;font-weight: 700;color: black;">
                        <?php echo number_format(($origin['count'] / $totalClients) * 100, 0); ?>%
                    </span>
                </div>
                <div style="margin-top: 5px;background: #eaeaea;border-radius: 3px;overflow: hidden;">
                    <div style="width: <?php echo ($origin['count'] / $totalClients) * 100; ?>%;background-color: #5252fc;height: 5px;"></div>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
    <h4 style="margin-top: 20px;">Crescimento</h4>
    <span>Últimos 6 meses</span>
    <canvas id="clientGrowthChart" width="100"></canvas>
</div>

<script>
    const ctx = document.getElementById('clientGrowthChart').getContext('2d');
    const clientGrowthChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($months); ?>,
            datasets: [{
                label: 'Number of Clients',
                data: <?php echo json_encode($counts); ?>,
                backgroundColor: '#5252fc',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 0,
                barThickness: 15 // Diminuir a espessura da barra
            }]
        },
        options: {
            indexAxis: 'y',
            scales: {
                x: {
                    display: false,
                    grid: {
                        display: false
                    }
                },
                y: {
                    display: true,
                    grid: {
                        display: false,
                        drawBorder: false
                    }
                }
            },
            layout: {
                padding: {
                    left: 0,
                    right: 40,
                    top: 20,
                    bottom: 20
                }
            },
            plugins: {
                legend: {
                    display: false
                },
                datalabels: {
                    anchor: 'end',
                    align: 'end',
                    formatter: function(value, context) {
                        return value;
                    },
                    color: '#5252fc',
                    font: {
                        weight: 'bold'
                    }
                }
            }
        },
        plugins: [ChartDataLabels]
    });
</script>