<?php if (!defined('ABSPATH')) exit; // Exit if accessed directly	 ?>

<?php if (!empty($message)) : ?>
	<div id="notice" class="updated fade">
		<p><?php echo $message; ?></p>
	</div>
<?php endif; ?>