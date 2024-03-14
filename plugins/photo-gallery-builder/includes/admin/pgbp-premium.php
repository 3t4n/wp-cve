<?php
/**
 * Plugin Premium Offer Page
 *
 * @package Photo Gallery Builder
 * @since 1.0.0
 */

if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<div class="wrap">

	<h2><?php _e( 'Photo Gallery Builder', 'pg_builder' ); ?></h2><br />	

	<style>
		.pgbp-plugin-pricing-table thead th h2{font-weight: 400; font-size: 2.4em; line-height:normal; margin:0px; color: #57a7c9;}
		.pgbp-plugin-pricing-table thead th h2 + p{font-size: 1.25em; line-height: 1.4; color: #999; margin:5px 0 5px 0;}

		table.pgbp-plugin-pricing-table{width:100%; text-align: left; border-spacing: 0; border-collapse: collapse; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box;}

		.pgbp-plugin-pricing-table th, .pgbp-plugin-pricing-table td{font-size:14px; line-height:normal; color:#444; vertical-align:middle; padding:12px;}

		.pgbp-plugin-pricing-table colgroup:nth-child(1) { width: 31%; border: 0 none; }
		.pgbp-plugin-pricing-table colgroup:nth-child(2) { width: 22%; border: 1px solid #ccc; }
		.pgbp-plugin-pricing-table colgroup:nth-child(3) { width: 25%; border: 10px solid #57a7c9; }

		/* Tablehead */
		.pgbp-plugin-pricing-table thead th {background-color: #fff; background:linear-gradient(to bottom, #ffffff 0%, #ffffff 100%); text-align: center; position: relative; border-bottom: 1px solid #ccc; padding: 1em 0 1em; font-weight:400; color:#999;}
		.pgbp-plugin-pricing-table thead th:nth-child(1) {background: transparent;}
		.pgbp-plugin-pricing-table thead th:nth-child(3) p{color:#000;}	
		.pgbp-plugin-pricing-table thead th p.promo {font-size: 14px; color: #fff; position: absolute; bottom:0; left: -17px; z-index: 1000; width: 100%; margin: 0; padding: .625em 17px .75em; background-color: #ca4a1f; box-shadow: 0 2px 4px rgba(0,0,0,.25); border-bottom: 1px solid #ca4a1f;}
		.pgbp-plugin-pricing-table thead th p.promo:before {content: ""; position: absolute; display: block; width: 0px; height: 0px; border-style: solid; border-width: 0 7px 7px 0; border-color: transparent #900 transparent transparent; bottom: -7px; left: 0;}
		.pgbp-plugin-pricing-table thead th p.promo:after {content: ""; position: absolute; display: block; width: 0px; height: 0px; border-style: solid; border-width: 7px 7px 0 0; border-color: #900 transparent transparent transparent; bottom: -7px; right: 0;}

		/* Tablebody */
		.pgbp-plugin-pricing-table tbody th{background: #fff; border-left: 1px solid #ccc; font-weight: 600;}
		.pgbp-plugin-pricing-table tbody th span{font-weight: normal; font-size: 87.5%; color: #999; display: block;}

		.pgbp-plugin-pricing-table tbody td{background: #fff; text-align: center;}
		.pgbp-plugin-pricing-table tbody td .dashicons{height: auto; width: auto; font-size:30px;}
		.pgbp-plugin-pricing-table tbody td .dashicons-no-alt{color: #ff2700;}
		.pgbp-plugin-pricing-table tbody td .dashicons-yes{color: #57a7c9;}

		.pgbp-plugin-pricing-table tbody tr:nth-child(even) th,
		.pgbp-plugin-pricing-table tbody tr:nth-child(even) td { background: #f5f5f5; border: 1px solid #ccc; border-width: 1px 0 1px 1px; }
		.pgbp-plugin-pricing-table tbody tr:last-child td {border-bottom: 0 none;}

		/* Table Footer */
		.pgbp-plugin-pricing-table tfoot th, .pgbp-plugin-pricing-table tfoot td{text-align: center; border-top: 1px solid #ccc;}
		.pgbp-plugin-pricing-table tfoot a, .pgbp-plugin-pricing-table thead a{font-weight: 600; color: #fff; text-decoration: none; text-transform: uppercase; display: inline-block; padding: 1em 2em; background: #ff2700; border-radius: .2em;}
		
		.pgbp-epb{color:#ff2700 !important;}
		
		/* SideBar */
		.pgbp-sidebar .pgbp-epb-wrap{background:#0055fb; color:#fff; padding:15px;}
		.pgbp-sidebar .pgbp-epb-wrap  h2{font-size:24px !important; color:#fff; margin:0 0 15px 0; padding:0px !important;}
		.pgbp-sidebar .pgbp-epb-wrap  h2 span{font-size:20px !important; color:#ffff00 !important;}
		.pgbp-sidebar .pgbp-epb-wrap ul li{font-size:16px; margin-bottom:8px;}
		.pgbp-sidebar .pgbp-epb-wrap ul li span{color:#ffff00 !important;}
		.pgbp-sidebar .pgbp-epb-wrap ul{list-style: decimal inside none;}
		.pgbp-sidebar .pgbp-epb-wrap b{font-weight:bold !important;}
		.pgbp-sidebar .pgbp-epb-wrap p{font-size:16px;}
		.pgbp-sidebar .pgbp-epb-wrap .button-yellow{font-weight: 600;color: #000; text-align:center;text-decoration: none;display:block;padding: 1em 2em;background: #ffff00;border-radius: .2em;}
		.pgbp-sidebar .pgbp-epb-wrap .button-orange{font-weight: 600;color: #fff; text-align:center;text-decoration: none;display:block;padding: 1em 2em;background: #ff2700;border-radius: .2em;}
	</style>

	<div id="poststuff">
		<div id="post-body" class="metabox-holder">
			<div id="post-body-content">
				<table class="pgbp-plugin-pricing-table">
					<colgroup></colgroup>
					<colgroup></colgroup>
					<colgroup></colgroup>
					<thead>
						<tr>
							<th></th>
							<th>
								<h2><?php _e( 'Free', 'pg_builder' ); ?></h2>
							</th>
							<th>
								<h2 class="pgbp-epb"><?php _e( 'Premium', 'pg_builder' ); ?></h2>
								<p><?php echo sprintf( __( 'Gain access to <strong>Photo Gallery Builder Pro</strong>', 'pg_builder' ) ); ?></p>
								<a href="<?php echo PHOTO_GALLERY_BUILDER_UPGRADE; ?>" target="_blank">Buy Now</a>
							</th>
						</tr>
					</thead>

					<tfoot>
						<tr>
							<th></th>
							<td></td>
							<td><p><?php echo sprintf( __( 'Gain access to <strong>Photo Gallery Builder Pro</strong>', 'pg_builder' ) ); ?></p>
							<a href="<?php echo PHOTO_GALLERY_BUILDER_UPGRADE; ?>" target="_blank">Buy Now</a></td>
						</tr>
					</tfoot>

					 <tbody>
						<tr>
							<th>Designs <span>Designs that make your website better</span></th>
							<td>6</td>
							<td>30+ Grid And Maosnry Design</td>
						</tr>
						<tr>
							<th>Shortcodes <span>Shortcode provide output to the front-end side</span></th>
							<td>1 shortcode and adding 6 designs</td>
							<td>1 Shortcode and adding 30+ Designs</td>
						</tr>
						
						<tr>
							<th>Shortcode Generator <span>Play with all shortcode parameters. No documentation required!!</span></th>
							<td><i class="dashicons dashicons-yes"></i></td>
							<td><i class="dashicons dashicons-yes"></i></td>
						</tr>
						
						<tr>
							<th>Divi Page Builder Native Support <em class="pgbp-new-feature">New</em> <span>Use this plugin with Divi Builder easily</span></th>
							<td><i class="dashicons dashicons-yes"></i></td>
							<td><i class="dashicons dashicons-yes"></i></td>
						</tr>
						<tr>
				    		<th>Fusion Page Builder (Avada) native support<em class="pgbp-new-feature">New</em> <span>Use this plugin with Fusion Page Builder (Avada) easily</span></th>
				    		<td><i class="dashicons dashicons-yes"></i></td>
				    		<td><i class="dashicons dashicons-yes"></i></td>
				    	</tr>
				    	<tr>				
							<th>WP Templating Features  <span>You can modify plugin html/designs in your current theme.</span></th>
							<td><i class="dashicons dashicons-yes"> </i></td>
							<td><i class="dashicons dashicons-yes"> </i></td>
						</tr>
						<tr>
							<th>Drag & Drop Photo and Order Change <span>Arrange your desired gallery with your desired order and display</span></th>
							<td><i class="dashicons dashicons-yes"></i></td>
							<td><i class="dashicons dashicons-yes"></i></td>
						</tr>
						<tr>
							<th>Photo Gallery Categories Wise <span>Display Photo categories wise</span></th>
							<td><i class="dashicons dashicons-no-alt"></i></td>
							<td><i class="dashicons dashicons-yes"></i></td>
						</tr>   	
						
						<tr>
							<th>Content Words Limit <span>Controls Word limit for content. Default value is 70</span></th>
							<td><i class="dashicons dashicons-no-alt"></i></td>
							<td><i class="dashicons dashicons-yes"></i></td>
						</tr>
						
						
						<tr>
							<th>Elementor Page Builder Support <em class="pgbp-new-feature">New</em> <span>Use this plugin with Elementor easily</span></th>
							<td><i class="dashicons dashicons-no-alt"></i></td>
							<td><i class="dashicons dashicons-yes"></i></td>
						</tr>
						<tr>
							<th>Beaver Builder Support <em class="pgbp-new-feature">New</em> <span>Use this plugin with Beaver Builder easily</span></th>
							<td><i class="dashicons dashicons-no-alt"></i></td>
							<td><i class="dashicons dashicons-yes"></i></td>
						</tr>
						<tr>
							<th>SiteOrigin Page Builder Support <em class="pgbp-new-feature">New</em> <span>Use this plugin with SiteOrigin easily</span></th>
							<td><i class="dashicons dashicons-no-alt"></i></td>
							<td><i class="dashicons dashicons-yes"></i></td>
						</tr>
						
						<tr>
							<th>Multiple Slider Parameters <span>Slider parameters like autoplay, number of slide, sider dots and etc.</span></th>
							<td><i class="dashicons dashicons-no-alt"></i></td>
							<td><i class="dashicons dashicons-yes"></i></td>
						</tr>
						
						<tr>
							<th>Automatic Update <span>Get automatic  plugin updates </span></th>
							<td>Lifetime</td>
							<td>Lifetime</td>
						</tr>
						<tr>
							<th>Support <span>Get support for plugin</span></th>
							<td>Limited</td>
							<td>1 Year</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>