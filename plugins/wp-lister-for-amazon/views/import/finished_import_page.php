<?php include_once( dirname(__FILE__).'/../common_header.php' ); ?>

<style type="text/css">

	a.right,
	input.button {
		float: right;
	}

</style>

<div class="wrap">
	<div class="icon32" style="background: url(<?php echo $wpl_plugin_url; ?>img/amazon-32x32.png) no-repeat;" id="wpl-icon"><br /></div>
	<h2><?php echo __( 'Import Process Finished', 'wp-lister-for-amazon' ) ?></h2>
	<?php echo $wpl_message ?>


	<div style="width:100%" class="postbox-container">
		<div class="metabox-holder">
			<div class="meta-box-sortables ui-sortable">

				<div class="postbox" id="RunImportBox">
					<h3 class="hndle"><span><?php echo __( 'Next Steps', 'wp-lister-for-amazon' ); ?></span></h3>
					<div class="inside">
                        <p>
                            <?php echo __( 'The import process has been finished successfully.', 'wp-lister-for-amazon' ); ?><br>
                        </p>
						<p>
                            <?php echo __( 'Please check your Listings and Products if everything was imported correctly.', 'wp-lister-for-amazon' ); ?><br>
                            <?php // echo __( 'Remember, WP-Lister is still in beta so please report any issues to support.', 'wp-lister-for-amazon' ); ?>

							<?php if ( $wpl_mode == 'inventory' ) : ?>
							<?php endif; ?>
							<?php if ( $wpl_mode == 'asin' ) : ?>
							<?php endif; ?>
						</p>
                        <p>
                            <a href="admin.php?page=wpla-import" class="button button-small">
                                <?php echo __( 'Run another Import', 'wp-lister-for-amazon' ); ?>
                            </a>
                            &nbsp;&nbsp;&nbsp;
                            <a href="admin.php?page=wpla" class="button button-small">
                                <?php echo __( 'View Listings', 'wp-lister-for-amazon' ); ?>
                            </a>
                            &nbsp;&nbsp;&nbsp;
                            <a href="edit.php?post_type=product" class="button button-small button-primary">
                                <?php echo __( 'View Products', 'wp-lister-for-amazon' ); ?>
                            </a>
                        </p>

					</div>
				</div> <!-- postbox -->

			</div>
		</div>
	</div>

	<br style="clear:both;"/>

</div>
