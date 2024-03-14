<?php
/**
 * Plugin Premium Offer Page
 *
 * @package Footer Mega Grid Columns
 * @since 1.1.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<div class="wrap">

	<h2><?php _e( 'Footer Mega Grid Columns', 'footer-mega-grid-columns' ); ?></h2><br />

	<style>
		.wpos-plugin-pricing-table thead th h2{font-weight: 400; font-size: 2.4em; line-height:normal; margin:0px; color: #2ECC71;}
		.wpos-plugin-pricing-table thead th h2 + p{font-size: 1.25em; line-height: 1.4; color: #999; margin:5px 0 5px 0;}

		table.wpos-plugin-pricing-table{width:100%; text-align: left; border-spacing: 0; border-collapse: collapse; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box;}

		.wpos-plugin-pricing-table th, .wpos-plugin-pricing-table td{font-size:14px; line-height:normal; color:#444; vertical-align:middle; padding:12px;}

		.wpos-plugin-pricing-table colgroup:nth-child(1) { width: 31%; border: 0 none; }
		.wpos-plugin-pricing-table colgroup:nth-child(2) { width: 22%; border: 1px solid #ccc; }
		.wpos-plugin-pricing-table colgroup:nth-child(3) { width: 25%; border: 10px solid #2ECC71; }

		/* Tablehead */
		.wpos-plugin-pricing-table thead th {background-color: #fff; background:linear-gradient(to bottom, #ffffff 0%, #ffffff 100%); text-align: center; position: relative; border-bottom: 1px solid #ccc; padding: 1em 0 1em; font-weight:400; color:#999;}
		.wpos-plugin-pricing-table thead th:nth-child(1) {background: transparent;}
		.wpos-plugin-pricing-table thead th:nth-child(3) p{color:#000;}	
		.wpos-plugin-pricing-table thead th p.promo {font-size: 14px; color: #fff; position: absolute; bottom:0; left: -17px; z-index: 1000; width: 100%; margin: 0; padding: .625em 17px .75em; background-color: #ca4a1f; box-shadow: 0 2px 4px rgba(0,0,0,.25); border-bottom: 1px solid #ca4a1f;}
		.wpos-plugin-pricing-table thead th p.promo:before {content: ""; position: absolute; display: block; width: 0px; height: 0px; border-style: solid; border-width: 0 7px 7px 0; border-color: transparent #900 transparent transparent; bottom: -7px; left: 0;}
		.wpos-plugin-pricing-table thead th p.promo:after {content: ""; position: absolute; display: block; width: 0px; height: 0px; border-style: solid; border-width: 7px 7px 0 0; border-color: #900 transparent transparent transparent; bottom: -7px; right: 0;}

		/* Tablebody */
		.wpos-plugin-pricing-table tbody th{background: #fff; border-left: 1px solid #ccc; font-weight: 600;}
		.wpos-plugin-pricing-table tbody th span{font-weight: normal; font-size: 87.5%; color: #999; display: block;}

		.wpos-plugin-pricing-table tbody td{background: #fff; text-align: center;}
		.wpos-plugin-pricing-table tbody td .dashicons{height: auto; width: auto; font-size:30px;}
		.wpos-plugin-pricing-table tbody td .dashicons-no-alt{color: #ff2700;}
		.wpos-plugin-pricing-table tbody td .dashicons-yes{color: #2ECC71;}

		.wpos-plugin-pricing-table tbody tr:nth-child(even) th,
		.wpos-plugin-pricing-table tbody tr:nth-child(even) td { background: #f5f5f5; border: 1px solid #ccc; border-width: 1px 0 1px 1px; }
		.wpos-plugin-pricing-table tbody tr:last-child td {border-bottom: 0 none;}

		/* Table Footer */
		.wpos-plugin-pricing-table tfoot th, .wpos-plugin-pricing-table tfoot td{text-align: center; border-top: 1px solid #ccc;}
		.wpos-plugin-pricing-table tfoot a, .wpos-plugin-pricing-table thead a{font-weight: 600; color: #fff; text-decoration: none; text-transform: uppercase; display: inline-block; padding: 1em 2em; background: #ff2700; border-radius: .2em;}
		
		.wpos-epb{color:#ff2700 !important;}
		
		/* SideBar */
		.wpos-sidebar .wpos-epb-wrap{background:#0055fb; color:#fff; padding:15px;}
		.wpos-sidebar .wpos-epb-wrap  h2{font-size:24px !important; color:#fff; margin:0 0 15px 0; padding:0px !important;}
		.wpos-sidebar .wpos-epb-wrap  h2 span{font-size:20px !important; color:#ffff00 !important;}
		.wpos-sidebar .wpos-epb-wrap ul li{font-size:16px; margin-bottom:8px;}
		.wpos-sidebar .wpos-epb-wrap ul li span{color:#ffff00 !important;}
		.wpos-sidebar .wpos-epb-wrap ul{list-style: decimal inside none;}
		.wpos-sidebar .wpos-epb-wrap b{font-weight:bold !important;}
		.wpos-sidebar .wpos-epb-wrap p{font-size:16px;}
		.wpos-sidebar .wpos-epb-wrap .button-yellow{font-weight: 600;color: #000; text-align:center;text-decoration: none;display:block;padding: 1em 2em;background: #ffff00;border-radius: .2em;}
		.wpos-sidebar .wpos-epb-wrap .button-orange{font-weight: 600;color: #fff; text-align:center;text-decoration: none;display:block;padding: 1em 2em;background: #ff2700;border-radius: .2em;}
	</style>
	
	<div id="poststuff">
		<div id="post-body" class="metabox-holder">
			<div id="post-body-content">

					<table class="wpos-plugin-pricing-table">
						<colgroup></colgroup>
						<colgroup></colgroup>
						<colgroup></colgroup>	
							<thead>
							<tr>
								<th></th>
								<th>
									<h2><?php _e( 'Free', 'footer-mega-grid-columns' ); ?></h2>
								</th>
								<th>
									<h2 class="wpos-epb"><?php _e( 'Premium', 'footer-mega-grid-columns' ); ?></h2>
									<p><?php echo sprintf( __( 'Gain access to <strong>Footer Mega Grid Columns</strong>', 'footer-mega-grid-columns' ) ); ?></p>
									<a href="<?php echo FMGC_PLUGIN_LINK_UPGRADE; ?>" class="wpos-button" target="_blank"><?php _e( 'Buy Now', 'footer-mega-grid-columns' ); ?></a></td>
								</th>	    		
							</tr>
						</thead>
						<tfoot>
							<tr>
								<th></th>
								<td></td>
								<td><p><?php echo sprintf( __( 'Gain access to <strong>Footer Mega Grid Columns</strong>', 'footer-mega-grid-columns' ) ); ?></p>
								<a href="<?php echo FMGC_PLUGIN_LINK_UPGRADE; ?>" class="wpos-button" target="_blank"><?php _e( 'Buy Now', 'footer-mega-grid-columns' ); ?></a></td>
							</tr>
						</tfoot>
						<tbody>
							<tr>
								<th>
									<?php _e( 'Method ', 'footer-mega-grid-columns' ); ?>
									<span class="subtext"><?php _e( 'Display output method.', 'footer-mega-grid-columns' ); ?></span>
								</th>
								<td>1</td>
								<td>3</td>
							</tr>
							<tr>
								<th>
									<?php _e( 'Shortcodes ', 'footer-mega-grid-columns' ); ?>
									<span class="subtext"><?php _e( 'Shortcode provide output to the front-end side', 'footer-mega-grid-columns' ); ?></span>
								</th>
								<td><?php _e( 'null', 'footer-mega-grid-columns' ); ?></td>
								<td>1</td>
							</tr>
							<tr>
								<th>
									<?php _e( 'Grid Support', 'footer-mega-grid-columns' ); ?>
									<span class="subtext"><?php _e( 'Display widget in grid view.', 'footer-mega-grid-columns' ); ?></span>
								</th>
								<td><i class="dashicons dashicons-no-alt"></i></td>
								<td><i class="dashicons dashicons-yes"></i></td>
							</tr>
							<tr>
								<th>
									<?php _e( 'Custom CSS Class', 'footer-mega-grid-columns' ); ?>
									<span class="subtext"><?php _e( 'Set Custom css class for widget.', 'footer-mega-grid-columns' ); ?></span>
								</th>
								<td><i class="dashicons dashicons-no-alt"></i></td>
								<td><i class="dashicons dashicons-yes"></i></td>
							</tr>
							<tr>
								<th>
									<?php _e( 'Footer Background Color', 'footer-mega-grid-columns' ); ?>
									<span class="subtext"><?php _e( 'Set footer background color.', 'footer-mega-grid-columns' ); ?></span>
								</th>
								<td><i class="dashicons dashicons-no-alt"></i></td>
								<td><i class="dashicons dashicons-yes"></i></td>
							</tr>
							<tr>
								<th>
									<?php _e( 'Widget Title Color', 'footer-mega-grid-columns' ); ?>
									<span class="subtext"><?php _e( 'Set widget title color.', 'footer-mega-grid-columns' ); ?></span>
								</th>
								<td><i class="dashicons dashicons-no-alt"></i></td>
								<td><i class="dashicons dashicons-yes"></i></td>
							</tr>
							<tr>
								<th>
									<?php _e( 'Widget Link Color', 'footer-mega-grid-columns' ); ?>
									<span class="subtext"><?php _e( 'Set widget anchor color.', 'footer-mega-grid-columns' ); ?></span>
								</th>
								<td><i class="dashicons dashicons-no-alt"></i></td>
								<td><i class="dashicons dashicons-yes"></i></td>
							</tr>
							<tr>
								<th>
									<?php _e( 'Widget Content Color', 'footer-mega-grid-columns' ); ?>
									<span class="subtext"><?php _e( 'Set widget content color.', 'footer-mega-grid-columns' ); ?></span>
								</th>
								<td><i class="dashicons dashicons-no-alt"></i></td>
								<td><i class="dashicons dashicons-yes"></i></td>
							</tr>
							<tr>
								<th>
									<?php _e( 'Footer Wrap width', 'footer-mega-grid-columns' ); ?>
									<span class="subtext"><?php _e( 'Enter inner Wrap width of footer in PX.', 'footer-mega-grid-columns' ); ?></span>
								</th>
								<td><i class="dashicons dashicons-no-alt"></i></td>
								<td><i class="dashicons dashicons-yes"></i></td>
							</tr>
							<tr>
								<th>
									<?php _e( 'Custom CSS Editor', 'footer-mega-grid-columns' ); ?>
									<span class="subtext"><?php _e( 'Enter custom CSS to override plugin CSS.', 'footer-mega-grid-columns' ); ?></span>
								</th>
								<td><i class="dashicons dashicons-no-alt"></i></td>
								<td><i class="dashicons dashicons-yes"></i></td>
							</tr>
							<tr>
								<th>
									<?php _e( 'Support ', 'footer-mega-grid-columns' ); ?>
									<span class="subtext"><?php _e( 'Get support for plugin', 'footer-mega-grid-columns' ); ?></span>
								</th>
								<td><?php _e( 'Limited', 'footer-mega-grid-columns' ); ?></td>
								<td><?php _e( '1 Year', 'footer-mega-grid-columns' ); ?></td>
							</tr>
						</tbody>
					</table>
			</div>	
				
			</div>
		</div>			
</div>