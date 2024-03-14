<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.hardkod.ru
 * @since      1.0.1
 *
 * @package    Ya_Turbo
 * @subpackage Ya_Turbo/admin/partials
 */

/**
 * @var array $feed
 */

/**
 * @var array $error
 */

/**
 * @var array $message
 */

?><?php

    // If this file is called directly, abort.
    defined( 'ABSPATH' ) || exit;

?><div class="wrap">

	<?php @include YATURBO_PATH . '/admin/partials/ya-turbo-admin-promo.php'; ?>

	<h1 class="wp-heading-inline">
        <?php _e('Delete feed', YATURBO_FEED); ?>
    </h1>

    <?php $redirect = add_query_arg(array(
	    'page' => YATURBO_FEED,
    ), admin_url('admin.php')); ?>

	<?php @include YATURBO_PATH . '/admin/partials/ya-turbo-message.php'; ?>

    <hr />

    <?php if( empty($error) ): ?>
        <?php _e( 'Redirecting ...', YATURBO_FEED ); ?>

        <script type="text/javascript">
        <!--
            window.setTimeout( function() {
                window.location.href = "<?php print $redirect; ?>";
            }, 1000 );
        //-->
        </script>
    <?php endif; ?>
</div>