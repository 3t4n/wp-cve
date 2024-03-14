<?php include_once( dirname(__FILE__).'/../common_header.php' ); ?>

<style type="text/css">

/*	a.right,
	input.button {
		float: right;
	}
*/
</style>

<div class="wrap">
	<div class="icon32" style="background: url(<?php echo $wpl_plugin_url; ?>img/amazon-32x32.png) no-repeat;" id="wpl-icon"><br /></div>
	<h2><?php echo __( 'Import Products', 'wp-lister-for-amazon' ) ?></h2>
	<?php echo $wpl_message ?>


	<div style="width:100%" class="postbox-container">
		<div class="metabox-holder">
			<div class="meta-box-sortables ui-sortable">

				<?php if ( $wpl_import_is_done && ! empty($wpl_listings) ) : ?>

					<div class="postbox" id="RunImportBox">
						<h3 class="hndle"><span><?php echo __( 'Import Result', 'wp-lister-for-amazon' ); ?></span></h3>
						<div class="inside">

							<p>
								<?php echo sprintf( __( 'It looks like there was a problem with %s product(s) which could not be imported.', 'wp-lister-for-amazon' ), count($wpl_listings) ); ?><br>
								<?php echo __( 'Please check if the ASINs are correct and whether you selected the right Amazon account and marketplace.', 'wp-lister-for-amazon' ); ?>
								<?php echo __( 'If ASINs and marketplace are correct, please contact support.', 'wp-lister-for-amazon' ); ?>
							</p>

						</div>
					</div> <!-- postbox -->

				<?php elseif ( $wpl_import_is_done ) : ?>

					<div class="postbox" id="RunImportBox">
						<h3 class="hndle"><span><?php echo __( 'Import Result', 'wp-lister-for-amazon' ); ?></span></h3>
						<div class="inside">

							<p>
								<?php echo __( 'All your products have been imported.', 'wp-lister-for-amazon' ); ?>
								<?php echo __( 'Now you can visit the Products page and update prices and stock quantities accordingly.', 'wp-lister-for-amazon' ); ?>
							</p>

						</div>
					</div> <!-- postbox -->

				<?php else: ?>

				<?php endif; ?>
				


				<?php if ( ! empty($wpl_listings) ) : ?>

					<div class="postbox" id="RunImportBox">
						<h3 class="hndle"><span><?php echo __( 'Import Products', 'wp-lister-for-amazon' ); ?></span></h3>
						<div class="inside">

							<p>
								<?php echo sprintf( __( 'There are %s new product(s) prepared to be imported by ASIN.', 'wp-lister-for-amazon' ), count($wpl_listings) ); ?><br>
								<?php echo __( 'Click on "Start Import" to fetch product details from Amazon and add them to your website.', 'wp-lister-for-amazon' ); ?><br>
							</p>

							<p>
								<a id="btn_batch_create_products_reminder" class="button button-primary button-small wpl_job_button">
									<?php echo __( 'Start Import', 'wp-lister-for-amazon' ); ?>
								</a>
							</p>

							<p>
								<?php echo __( 'Note: The quantity of all imported products will be zero and the price will be the current "Buy Box Price" from Amazon.', 'wp-lister-for-amazon' ); ?>
							</p>

						</div>
					</div> <!-- postbox -->

					<div class="postbox" id="ImportPreviewBox">
						<h3 class="hndle"><span><?php echo __( 'ASIN Queue', 'wp-lister-for-amazon' ); ?></span></h3>
						<div class="inside">

							<p>
								The following ASINs are waiting to be imported:
							</p>

							<p>
							<?php foreach ($wpl_listings as $item) : ?>
								&bull; <?php echo $item['asin'] ?><br>
							<?php endforeach; ?>

							<?php
								#echo "<pre>";print_r($wpl_listings);echo"</pre>";#die();
							?>
							</p>

						</div>
					</div> <!-- postbox -->

				<?php endif; ?>

			</div>
		</div>
	</div>

	<br style="clear:both;"/>

</div>