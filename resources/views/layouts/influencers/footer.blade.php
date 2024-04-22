</div>
</div>
</div>
</div>
</div>



</main>

<footer class="text-muted mt-5">
    <div class="container text-center">
       <p>© 2023 RHNUBE . Todos los derechos reservados.</p>
    </div>
</footer>

<!-- ===== IONICONS ===== -->
<script src="https://unpkg.com/ionicons@5.1.2/dist/ionicons.js"></script>

<!--===== MAIN JS =====-->

<script>
    /*===== MENU SHOW Y HIDDEN =====*/
    const navMenu = document.getElementById('nav-menu'),
        toggleMenu = document.getElementById('nav-toggle'),
        closeMenu = document.getElementById('nav-close')

    /*SHOW*/
    toggleMenu.addEventListener('click', () => {
        navMenu.classList.toggle('show')
    })

    /*HIDDEN*/
    closeMenu.addEventListener('click', () => {
        navMenu.classList.remove('show')
    })

    /*===== ACTIVE AND REMOVE MENU =====*/
    const navLink = document.querySelectorAll('.nav__link');

    function linkAction() {
        /*Active link*/
        navLink.forEach(n => n.classList.remove('active'));
        this.classList.add('active');

        /*Remove menu mobile*/
        navMenu.classList.remove('show')
    }
    navLink.forEach(n => n.addEventListener('click', linkAction));
</script>
</body>

</html>
