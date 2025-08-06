<style>
    .header__top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0;
        border-bottom: 1px solid #80808047;
        height: 75px;
        position: relative;
    }

    .header__top__logo {
        padding: 10px 0 10px 40px;
        display: flex;
        gap: 10px;
    }

    .header__top__user {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 10px 40px 10px 0;
        gap: 3px;

    }

    .header__top__user__info {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        justify-content: space-between;
        gap: 3px;
    }

    .header__top__user__image {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .header__top__user__info__name {
        font-family: 'Poppins', sans-serif;
        font-weight: 600;
        font-size: 15px;
        text-transform: uppercase;
        line-height: 1;
    }

    .header__top__user__info__logout {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
    }

    .header__top__user__info__logout__exit {
        font-family: "Poppins", sans-serif;
        font-weight: 400;
        color: #5252fc;
        text-decoration: none;
        font-size: 13px;
        margin-top: 2px;
        line-height: 1;
    }

    .header__top__user__info__logout__icon {
        -webkit-transform: scaleX(-1);
        transform: scaleX(-1);
    }

    @media screen and (max-width: 768px) {

        .header__top {
            position: fixed;
            width: 100vw;
        }

        .mobile-menu {
            display: flex;
            align-items: center;
        }

        .frame__navbar {
            display: none;
        }

        .header__top__user__image {
            display: none;
        }

        .header__logo {
            padding-top: 2px;
        }
    }
</style>
<div class="header__top">
    <div class="header__top__logo">
        <div class="mobile-menu">
            <img src="img/menu.png" alt="Menu" width="24">
        </div>


        <img class="header__logo" src="img/logo.svg" alt="logo da marcar" width="100">
    </div>
    <div class="header__top__user">
        <div class="header__top__user__info">
            <span class="header__top__user__info__name"><?php echo $_SESSION['username']; ?></span>
            <div class="header__top__user__info__logout">
                <img class="header__top__user__info__logout__icon" src="img/logout.svg" alt="logout" width="13">
                <a class="header__top__user__info__logout__exit" href="logout.php">SAIR</a>
            </div>
        </div>
        <div class="header__top__user__image">
            <img src="img/user.svg" alt="user" width="55">
        </div>
    </div>
</div>


<script>
    document.querySelector('.header__top__logo').addEventListener('click', function() {
        // Toggle active class on menu button
        this.classList.toggle('active');

        // Get menu container
        const menuContainer = document.querySelector('.mobile-menu-container');

        // Toggle menu visibility
        if (this.classList.contains('active')) {
            menuContainer.style.width = '100%';
        } else {
            menuContainer.style.width = '0';
        }
    });
</script>