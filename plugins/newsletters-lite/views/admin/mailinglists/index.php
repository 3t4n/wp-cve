<?php // phpcs:ignoreFile ?>
<!-- Manage Mailing Lists -->
<?php
$isSerialKeyValid = false;
$serial_validation_status = $this -> ci_serial_valid();

?>
<?php $Mailinglist -> get_default(); ?>

<div class="wrap newsletters">
	<h1><?php esc_html_e('Manage Lists', 'wp-mailinglist'); ?> <a class="add-new-h2" href="<?php echo esc_url_raw($this -> url); ?>&method=save" title="<?php esc_html_e('Create a new mailing list', 'wp-mailinglist'); ?>"><?php esc_html_e('Add New', 'wp-mailinglist'); ?></a></h1>
    <?php
         if (!is_array($serial_validation_status) && !$serial_validation_status ) {
            ?>
             <!--<div class="notice notice-error"><p><?php _e('Only 1 mailing list allowed in the free version. <a href="' . admin_url('admin.php?page=' . $this -> sections -> lite_upgrade) . '" >Upgrade to PRO</a> to create unlimited mailing lists.', 'wp-mailinglist'); ?></p></div>-->
    <?php
        }

        if (is_array($serial_validation_status) ) {
            ?>
            <!--<div class="notice notice-error"><p><?php _e('Your serial key has expired. Only 1 mailing list allowed in the free version.  <a href="https://tribulant.com/downloads/" target="_blank" >Renew your license</a> to be able to manage unlimited mailing lists.', 'wp-mailinglist'); ?></p></div>-->
            <?php
        }
    ?>
	<form id="posts-filter" action="?page=<?php echo esc_html( $this -> sections -> lists); ?>" method="post">
		<?php wp_nonce_field($this -> sections -> lists . '_search'); ?>
		<ul class="subsubsub">
			<li><?php echo (empty($_GET['showall'])) ? $paginate -> allcount : count($mailinglists); ?> <?php esc_html_e('mailing lists', 'wp-mailinglist'); ?> |</li>
			<?php if (empty($_GET['showall'])) : ?>
				<li><?php echo ( $Html -> link(__('Show All', 'wp-mailinglist'), $this -> url . '&showall=1')); ?></li>
			<?php else : ?>
				<li><?php echo ( $Html -> link(__('Show Paging', 'wp-mailinglist'), '?page=' . $this -> sections -> lists)); ?></li>
			<?php endif; ?>
		</ul>
		<p class="search-box">
            <input id="post-search-input" class="search-input" type="text" name="searchterm" value="<?php echo (!empty($_POST['searchterm'])) ? esc_attr($_POST['searchterm']) : (isset($_GET[$this -> pre . 'searchterm']) ? esc_attr($_GET[$this -> pre . 'searchterm']) : '' ) ; ?>" />
			<button value="1" type="submit" class="button">
				<?php esc_html_e('Search Lists', 'wp-mailinglist'); ?>
			</button>
		</p>
	</form>
	<?php $this -> render('mailinglists' . DS . 'loop', array('mailinglists' => $mailinglists, 'paginate' => $paginate), true, 'admin'); ?>
</div>