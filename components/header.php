<header>
    <script>
    const toggleMenu = () => {
        const menu = document.getElementById('menu');


        if (menu.classList.contains('open')) {
            menu.classList.remove('open');
        } else {
            menu.classList.add('open');
        }


    }

    const toggleModal = () => {
        const modal = document.getElementById('modal__logout');

        if (modal.classList.contains('open')) {
            modal.classList.remove('open');
        } else {
            modal.classList.add('open');
        }
    }
    </script>
    <?php
        if (isset($_POST['logout'])) {
            session_destroy();
            echo "<script>window.location.href='./login.php'</script>";
        }
    ?>
    <div class="header__inner">
        <div class="logo" onclick="window.location.href='./'">
            Car Gallery
        </div>

        <div class="settings" onclick="toggleMenu()">
            <div class="menu" id="menu">
                <a href="./profile.php"><span class="menu__btn"><img src="./images/user-circle.svg" />Profile</span></a>
                <span class="menu__btn" onclick="toggleModal()"><img src="./images/sign-out-alt.svg">Logout</span>
            </div>
        </div>

        <div class="modal" id="modal__logout">
            <div class="modal__content">
                <div class="modal__close" onclick="toggleModal()">
                    <img src="./images/times-circle.svg" />
                </div>
                <div class="modal__heading">
                    Are you sure you want to logout?
                </div>
                <div class="modal__options">
                    <button onclick="toggleModal()">No</button>
                    <form method="POST">
                        <button type="submit" name="logout">Yes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>