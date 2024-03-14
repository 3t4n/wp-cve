<?php if ( ! defined( 'WPINC' ) ) { die; } ?>

<?php if ( isset( $furgonetka_errors ) ): ?>
    <?php Furgonetka_Admin::print_messages( $furgonetka_errors, 'error' ); ?>
<?php endif; ?>
<?php if ( isset( $furgonetka_messages ) ): ?>
    <?php Furgonetka_Admin::print_messages( $furgonetka_messages, 'message' ); ?>
<?php endif; ?>