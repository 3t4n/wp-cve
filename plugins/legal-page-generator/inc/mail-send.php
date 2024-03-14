<?php
defined('ABSPATH') or die();
?>
<?php if ( count ( $errors ) < 1 ) : ?>
	<p><?php _e( 'Request has sent successfully!', 'legal-page-generator' ); ?></p>
<?php else : ?>
	<p><strong><?php _e( 'Error:', 'legal-page-generator' ) ?></strong> <?php echo end( $errors ) ; ?></p>
<?php endif; ?>
<p><a href="<?php echo admin_url( 'admin.php?page=legal-page-generator' ); ?>"><?php _e( 'Go to Main Page', 'legal-page-generator' ) ?></a></p>