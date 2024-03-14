</div>

<?php foreach ($js_files as $file): ?>
    <script type='text/javascript' src='<?php echo esc_url($file); ?>'></script>
<?php endforeach; ?>

<?php do_action('wppayform/frameless_footer', $action); ?>
</body>
</html>
