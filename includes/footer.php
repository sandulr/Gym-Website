</main>

<footer class="main-footer">
    <div class="footer-container">
        <div class="footer-about">
            <h3>FitZone Fitness Center</h3>
            <p>Helping you achieve your fitness goals with state-of-the-art equipment and expert trainers.</p>
        </div>
        <div class="footer-links">
            <h4>Quick Links</h4>
            <ul>
                <li><a href="/classes.php">Classes</a></li>
                <li><a href="/membership.php">Membership</a></li>
                <li><a href="/blog.php">Blog</a></li>
                <li><a href="/contact.php">Contact</a></li>
            </ul>
        </div>
        <div class="footer-contact">
            <h4 style="margin-bottom: 5px;">Contact Us</h4>
            <p style="margin-bottom: 5px;">Email: info@fitzone.com</p>
            <p style="margin-bottom: 5px;">Phone: +94 77 123 4567</p>
            <p>No. 45, Kumara Mawatha,
                <br>Kurunegala, 60000<br>Sri Lanka</p>
        </div>
    </div>
    <p class="footer-bottom">© <?php echo date('Y'); ?> FitZone Fitness Center. All rights reserved.</p>
</footer>

<script>
    const hamburger = document.getElementById('hamburger');
    const navLinks = document.getElementById('nav-links');

    hamburger.addEventListener('click', () => {
        navLinks.classList.toggle('open');
    });
</script>


</body>
</html>
