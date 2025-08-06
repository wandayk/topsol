<style>
    .nav {
        display: flex;
        flex-direction: column;
        padding: 20px 0;
        border-right: 1px solid #80808047;
        width: 280px;
        height: 100%;
    }

    .nav__link {
        font-family: "Poppins", sans-serif;
        font-weight: 500;
        color: #000;
        text-decoration: none;
        font-size: 15px;
        height: 40px;
        display: flex;
        align-items: center;
        position: relative;
        padding: 0 40px;
        gap: 10px;
    }

    .nav__link:before {
        content: "";
        width: 4px;
        height: 0;
        background-color: #5252fc;
        position: absolute;
        left: 0;
        transition: height 0.3s ease;
    }

    .nav__link:hover {
        background: #80808021;
    }

    .nav__link:hover:before {
        height: 40px;
    }

    .active {
        background: #c4c4c421;
    }

    .active:before {
        height: 40px;
    }



    .mobile-menu-container {
        position: fixed;
        top: 75px;
        left: 0;
        width: 0;
        height: calc(100vh - 75px);
        background: white;
        z-index: 9999;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        flex-direction: column;
        padding: 20px 0;
        overflow: hidden;
    }

    .mobile-nav-item {
        padding: 15px 20px;
        border-bottom: 1px solid #eee;
        font-family: 'Poppins', sans-serif;
        font-size: 14px;
        color: #333;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .mobile-nav-item img {
        width: 20px;
        height: 20px;
    }

    @media screen and (max-width: 768px) {
        .nav {
            display: none !important;
        }
    }
</style>
<div class="nav">
    <a class="nav__link <?php echo $_GET['page'] == 'dashboard' ? 'active' : ''; ?>" href="dashboard.php">
        <img src="img/dashboard.png" alt="dashboard" width="20">
        Dashboard
    </a>
    <a class="nav__link <?php echo $_GET['page'] == 'clientes' ? 'active' : ''; ?>" href="clientes.php">
        <img src="img/clientes.png" alt="clientes" width="20">
        Clientes
    </a>
    <a class="nav__link <?php echo $_GET['page'] == 'colecoes' ? 'active' : ''; ?>" href="colecoes.php">
        <img src="img/collections.png" alt="clientes" width="20">
        Coleções
    </a>
    <a class="nav__link <?php echo $_GET['page'] == 'notas' ? 'active' : ''; ?>" href="notas.php">
        <img src="img/notas.png" alt="notas" width="20">
        Notas
    </a>
    <a class="nav__link <?php echo $_GET['page'] == 'financeiro' ? 'active' : ''; ?>" href="financeiro.php">
        <img src="img/financeiro.png" alt="financeiro" width="20">
        Financeiro
    </a>
</div>

<div class="mobile-menu-container">
    <a class="nav__link <?php echo $_GET['page'] == 'dashboard' ? 'active' : ''; ?>" href="dashboard.php">
        <img src="img/dashboard.png" alt="dashboard" width="20">
        DASHBOARD
    </a>
    <a class="nav__link <?php echo $_GET['page'] == 'clientes' ? 'active' : ''; ?>" href="clientes.php">
        <img src="img/clientes.png" alt="clientes" width="20">
        CLIENTES
    </a>
    <a class="nav__link <?php echo $_GET['page'] == 'colecoes' ? 'active' : ''; ?>" href="colecoes.php">
        <img src="img/collections.png" alt="collections" width="20">
        COLEÇÕES
    </a>
    <a class="nav__link <?php echo $_GET['page'] == 'notas' ? 'active' : ''; ?>" href="notas.php">
        <img src="img/notas.png" alt="notas" width="20">
        NOTAS
    </a>
    <a class="nav__link <?php echo $_GET['page'] == 'financeiro' ? 'active' : ''; ?>" href="financeiro.php">
        <img src="img/financeiro.png" alt="financeiro" width="20">
        FINANCEIRO
    </a>
</div>