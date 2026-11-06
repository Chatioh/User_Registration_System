<?php
/**
 * Footer Template for Pizza Lovers Club
 * Based on CENY6103 Module 2 - PHP Templating with Includes
 */
?>
        </div> <!-- Close container -->
    </main>

    <footer>
        <p>&copy; <?php echo date('Y'); ?> Pizza Lovers Club. All rights reserved.</p>
        <p>Built with PHP, HTML, and CSS for CENY6103 Module 2</p>
    </footer>

    <script src="/assets/js/main.js"></script>
    <?php if (isset($page_js)): ?>
        <script src="/assets/js/<?php echo $page_js; ?>"></script>
    <?php endif; ?>
</body>
</html>
