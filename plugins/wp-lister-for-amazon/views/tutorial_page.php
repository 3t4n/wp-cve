<?php #include_once( dirname(__FILE__).'/common_header.php' ); ?>

<style type="text/css">
	p.desc {
		padding-left: 14px;
	}
</style>

<div class="wrap">
	<div class="icon32" style="background: url(<?php echo $wpl_plugin_url; ?>img/amazon-32x32.png) no-repeat;" id="wpl-icon"><br /></div>
	<h2><?php echo __( 'Tutorial', 'wp-lister-for-amazon' ) ?></h2>
	
	<div style="width:640px;" class="postbox-container">
		<div class="metabox-holder">
			<div class="meta-box-sortables ui-sortable">
				<form method="post" action="<?php echo $wpl_form_action; ?>">
				
					<div class="postbox" id="ListingHelpBox">
						<h3 class="hndle"><span><?php echo __( 'Listing items', 'wp-lister-for-amazon' ); ?></span></h3>
						<div class="inside">

							<?php echo $wpl_content_help_listing ?>

						</div>
					</div>

					<div class="postbox" id="ImportHelpBox">
						<h3 class="hndle"><span><?php echo __( 'Importing products', 'wp-lister-for-amazon' ); ?></span></h3>
						<div class="inside">

							<?php echo $wpl_content_help_import ?>

						</div>
					</div>

					<div class="postbox" id="LinksBox">
						<h3 class="hndle"><span><?php echo __( 'Ressources', 'wp-lister-for-amazon' ); ?></span></h3>
						<div class="inside">

							<p><strong><?php echo __( 'Helpful links', 'wp-lister-for-amazon' ); ?></strong></p>
							<p class="desc" style="display: block;">
								<a href="https://www.wplab.com/plugins/wp-lister-for-amazon/faq/" target="_blank"><?php echo __( 'FAQ', 'wp-lister-for-amazon' ); ?></a> <br>
								<a href="https://www.wplab.com/plugins/wp-lister-for-amazon/documentation/" target="_blank"><?php echo __( 'Documentation', 'wp-lister-for-amazon' ); ?></a> <br>
								<a href="https://www.wplab.com/plugins/wp-lister-for-amazon/documentation/account-setup/" target="_blank"><?php echo __( 'Installation and First Time Setup', 'wp-lister-for-amazon' ); ?></a> <br>
								<a href="https://www.wplab.com/plugins/wp-lister-for-amazon/changelog/" target="_blank"><?php echo __( 'Changelog', 'wp-lister-for-amazon' ); ?></a> <br>
							</p>
							<br class="clear" />

						</div>
					</div>

				</form>
			</div>
		</div>
	</div>

	<script type="text/javascript">
	</script>

</div>