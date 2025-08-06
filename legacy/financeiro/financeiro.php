<?php
include 'class/connection.php';

$months = array(
    1 => 'Janeiro',
    2 => 'Fevereiro',
    3 => 'Março',
    4 => 'Abril',
    5 => 'Maio',
    6 => 'Junho',
    7 => 'Julho',
    8 => 'Agosto',
    9 => 'Setembro',
    10 => 'Outubro',
    11 => 'Novembro',
    12 => 'Dezembro'
);

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

    .frame__body {
        padding: 20px 0 30px 40px;
        width: 100%;
        display: flex;
        flex-direction: column;
        height: 100%;
        overflow: hidden;
    }

    .frame__body h2 {
        font-family: "Poppins", sans-serif;
        font-weight: 500;
        color: #000;
        margin: 0;
        padding: 0;
        margin-top: 5px;
    }

    .frame__dash {
        margin-bottom: 20px;
        width: 60px;
        height: 4px;
        background: #5252fc;
        border-radius: 2px;
    }

    /* Copy all base styles from notas.php and modify as needed */
    .financeiro__header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
        border-bottom: 1px solid #80808040;
        width: 100%;
        cursor: pointer;
        transition: all 0.3s;
    }

    .financeiro__stats {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        margin-bottom: 20px;
    }

    .stats__card {
        background: #fff;
        padding: 20px;
        border-radius: 5px;
        border: 1px solid #8080803b;
        box-shadow: rgba(9, 30, 66, 0.25) 0px 4px 8px -2px;
    }

    #selectedMonthText {
        font-size: 16px;
        text-transform: uppercase;
        color: gray;
        font-weight: 600;
        display: flex;
        margin-top: -5px;
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

    .modal-backdrop {
        width: 0 !important;
        height: 0 !important;
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
        padding: 0 !important;
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
        padding: 20px 40px;
    }

    .frame__despesas {
        width: 100%;
        overflow: hidden;
        height: 100%;
    }

    .frame__despesas__header {
        margin-right: 40px;
        border-bottom: 1px solid rgba(128, 128, 128, 0.23);
    }

    .frame__despesas__content {
        padding-right: 40px;
        display: flex;
        width: 100%;
        flex-direction: column;
        gap: 10px;
        margin-top: 20px;
        margin-bottom: 20px;
        height: 100%;
        overflow: auto;
    }

    .frame__despesas__content__items {
        display: flex;
        flex-direction: column;
        width: 100%;
        gap: 10px;
        align-items: center;
        height: 100%;
    }

    .frame__despesas__content__item {
        display: flex;
        justify-content: space-between;
        padding: 0;
        /*! border-bottom: 1px solid #eee; */
        width: 100%;
        align-items: center;
        border: 2px solid #ff000036;
        padding: 5px 10px;
        border-radius: 4px;
        background: #ffeded;
        cursor: pointer;
    }

    .despesas__button {
        display: flex;
        transition: all 0.5s ease;
        height: 0;
        overflow: hidden;
        justify-content: end;
    }

    .frame__despesas__content__item:hover .despesas__button {
        margin-bottom: 5px;
        gap: 5px;
        height: 20px;
    }
</style>

<div class="frame__header">
    <div>
        <h2>Financeiro</h2>
        <span id="selectedMonthText">
            <?php
            setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'portuguese');
            echo $months[intval(date('n'))];
            ?>
        </span>
    </div>
    <div style="display: flex; gap: 15px; align-items: center;height:48px;">
        <div style="display: flex; align-items: center; gap: 10px;">
            <select id="monthSelect" class="form-select" style="width: auto; font-size: 14px; font-weight: 500; border: 1px solid #80808075;">
                <?php

                $currentMonth = date('n');
                foreach ($months as $num => $name) {
                    $selected = ($num == $currentMonth) ? 'selected' : '';
                    echo "<option value='$num' $selected>$name</option>";
                }
                ?>
            </select>
            <select id="yearSelect" class="form-select" style="width: auto; font-size: 14px; font-weight: 500; border: 1px solid #80808075;">
                <?php
                $currentYear = date('Y');
                for ($year = $currentYear - 2; $year <= $currentYear; $year++) {
                    $selected = ($year == $currentYear) ? 'selected' : '';
                    echo "<option value='$year' $selected>$year</option>";
                }
                ?>
            </select>
        </div>

    </div>
</div>

<script>
    $('#monthSelect, #yearSelect').change(function() {
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
    });

    $('#monthSelect').change(function() {
        const monthNames = {
            1: 'Janeiro',
            2: 'Fevereiro',
            3: 'Março',
            4: 'Abril',
            5: 'Maio',
            6: 'Junho',
            7: 'Julho',
            8: 'Agosto',
            9: 'Setembro',
            10: 'Outubro',
            11: 'Novembro',
            12: 'Dezembro'
        };

        const selectedMonth = $(this).val();
        $('#selectedMonthText').text(monthNames[selectedMonth]);
    });
</script>

<div class="frame__dash"></div>

<div class="frame__content">
    <div id="frame__content__collection">
        <?php
        include 'financeiro/search.php';
        ?>
    </div>
</div>