<div class="fab-loginform prose mt-4 px-4">
    <?php
        wp_login_form( array(
            'echo'            => true,
            'redirect'        => home_url(),
            'remember'        => true,
            'value_remember'  => true,
        ) );
    ?>
</div>