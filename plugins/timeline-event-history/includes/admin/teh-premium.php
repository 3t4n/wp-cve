<?php
/**
 * Plugin Premium Offer Page
 *
 * @package Timeline Event History
 * @since 1.0.0
 */

if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<div class="wrap">

	<h2><?php _e( 'Timeline Event History', timeline_wp ); ?></h2><br />	

	<style>
		.teh-plugin-pricing-table thead th h2{font-weight: 400; font-size: 2.4em; line-height:normal; margin:0px; color: #57a7c9;}
		.teh-plugin-pricing-table thead th h2 + p{font-size: 1.25em; line-height: 1.4; color: #999; margin:5px 0 5px 0;}

		table.teh-plugin-pricing-table{width:100%; text-align: left; border-spacing: 0; border-collapse: collapse; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box;}

		.teh-plugin-pricing-table th, .teh-plugin-pricing-table td{font-size:14px; line-height:normal; color:#444; vertical-align:middle; padding:12px;}

		.teh-plugin-pricing-table colgroup:nth-child(1) { width: 31%; border: 0 none; }
		.teh-plugin-pricing-table colgroup:nth-child(2) { width: 22%; border: 1px solid #ccc; }
		.teh-plugin-pricing-table colgroup:nth-child(3) { width: 25%; border: 10px solid #57a7c9; }

		/* Tablehead */
		.teh-plugin-pricing-table thead th {background-color: #fff; background:linear-gradient(to bottom, #ffffff 0%, #ffffff 100%); text-align: center; position: relative; border-bottom: 1px solid #ccc; padding: 1em 0 1em; font-weight:400; color:#999;}
		.teh-plugin-pricing-table thead th:nth-child(1) {background: transparent;}
		.teh-plugin-pricing-table thead th:nth-child(3) p{color:#000;}	
		.teh-plugin-pricing-table thead th p.promo {font-size: 14px; color: #fff; position: absolute; bottom:0; left: -17px; z-index: 1000; width: 100%; margin: 0; padding: .625em 17px .75em; background-color: #ca4a1f; box-shadow: 0 2px 4px rgba(0,0,0,.25); border-bottom: 1px solid #ca4a1f;}
		.teh-plugin-pricing-table thead th p.promo:before {content: ""; position: absolute; display: block; width: 0px; height: 0px; border-style: solid; border-width: 0 7px 7px 0; border-color: transparent #900 transparent transparent; bottom: -7px; left: 0;}
		.teh-plugin-pricing-table thead th p.promo:after {content: ""; position: absolute; display: block; width: 0px; height: 0px; border-style: solid; border-width: 7px 7px 0 0; border-color: #900 transparent transparent transparent; bottom: -7px; right: 0;}

		/* Tablebody */
		.teh-plugin-pricing-table tbody th{background: #fff; border-left: 1px solid #ccc; font-weight: 600;}
		.teh-plugin-pricing-table tbody th span{font-weight: normal; font-size: 87.5%; color: #999; display: block;}

		.teh-plugin-pricing-table tbody td{background: #fff; text-align: center;}
		.teh-plugin-pricing-table tbody td .dashicons{height: auto; width: auto; font-size:30px;}
		.teh-plugin-pricing-table tbody td .dashicons-no-alt{color: #ff2700;}
		.teh-plugin-pricing-table tbody td .dashicons-yes{color: #57a7c9;}

		.teh-plugin-pricing-table tbody tr:nth-child(even) th,
		.teh-plugin-pricing-table tbody tr:nth-child(even) td { background: #f5f5f5; border: 1px solid #ccc; border-width: 1px 0 1px 1px; }
		.teh-plugin-pricing-table tbody tr:last-child td {border-bottom: 0 none;}

		/* Table Footer */
		.teh-plugin-pricing-table tfoot th, .teh-plugin-pricing-table tfoot td{text-align: center; border-top: 1px solid #ccc;}
		.teh-plugin-pricing-table tfoot a, .teh-plugin-pricing-table thead a{font-weight: 600; color: #fff; text-decoration: none; text-transform: uppercase; display: inline-block; padding: 1em 2em; background: #ff2700; border-radius: .2em;}
		
		.teh-epb{color:#ff2700 !important;}
		
		/* SideBar */
		.teh-sidebar .teh-epb-wrap{background:#0055fb; color:#fff; padding:15px;}
		.teh-sidebar .teh-epb-wrap  h2{font-size:24px !important; color:#fff; margin:0 0 15px 0; padding:0px !important;}
		.teh-sidebar .teh-epb-wrap  h2 span{font-size:20px !important; color:#ffff00 !important;}
		.teh-sidebar .teh-epb-wrap ul li{font-size:16px; margin-bottom:8px;}
		.teh-sidebar .teh-epb-wrap ul li span{color:#ffff00 !important;}
		.teh-sidebar .teh-epb-wrap ul{list-style: decimal inside none;}
		.teh-sidebar .teh-epb-wrap b{font-weight:bold !important;}
		.teh-sidebar .teh-epb-wrap p{font-size:16px;}
		.teh-sidebar .teh-epb-wrap .button-yellow{font-weight: 600;color: #000; text-align:center;text-decoration: none;display:block;padding: 1em 2em;background: #ffff00;border-radius: .2em;}
		.teh-sidebar .teh-epb-wrap .button-orange{font-weight: 600;color: #fff; text-align:center;text-decoration: none;display:block;padding: 1em 2em;background: #ff2700;border-radius: .2em;}
	</style>

	<div id="poststuff">
		<div id="post-body" class="metabox-holder">
			<div id="post-body-content">
				<table class="teh-plugin-pricing-table">
					<colgroup></colgroup>
					<colgroup></colgroup>
					<colgroup></colgroup>
					<thead>
						<tr>
							<th></th>
							<th>
								<h2><?php _e( 'Free', timeline_wp ); ?></h2>
							</th>
							<th>
								<h2 class="teh-epb"><?php _e( 'Premium', timeline_wp ); ?></h2>
								<p><?php echo sprintf( __( 'Gain access to <strong>Timeline Event History Pro</strong>', timeline_wp ) ); ?></p>
								<a href="<?php echo TIMELINE_WP_PLUGIN_UPGRADE; ?>" target="_blank">Buy Now</a>
							</th>
						</tr>
					</thead>

					<tfoot>
						<tr>
							<th></th>
							<td></td>
							<td><p><?php echo sprintf( __( 'Gain access to <strong>Timeline Event History Pro</strong>', timeline_wp ) ); ?></p>
							<a href="<?php echo TIMELINE_WP_PLUGIN_UPGRADE; ?>" target="_blank">Buy Now</a></td>
						</tr>
					</tfoot>

					 <tbody>
						<tr>
							<th>Designs <span>Designs that make your website better</span></th>
							<td>2</td>
							<td>17+ Verical And Horizontal Design</td>
						</tr>
						<tr>
							<th>Shortcodes <span>Shortcode provide output to the front-end side</span></th>
							<td>1</td>
							<td>1 Shortcode and adding 17+ Designs</td>
						</tr>
						
						<tr>
							<th>Shortcode Generator <span>Play with all shortcode parameters. No documentation required!!</span></th>
							<td><i class="dashicons dashicons-yes"></i></td>
							<td><i class="dashicons dashicons-yes"></i></td>
						</tr>
						
						<tr>
							<th>Gutenberg Block Supports <span>Use this plugin with Gutenberg easily</span></th>
							<td><i class="dashicons dashicons-yes"></i></td>
							<td><i class="dashicons dashicons-yes"></i></td>
						</tr>
						<tr>
							<th>Divi Page Builder Native Support <em class="teh-new-feature">New</em> <span>Use this plugin with Divi Builder easily</span></th>
							<td><i class="dashicons dashicons-yes"></i></td>
							<td><i class="dashicons dashicons-yes"></i></td>
						</tr>
						<tr>
				    		<th>Fusion Page Builder (Avada) native support<em class="teh-new-feature">New</em> <span>Use this plugin with Fusion Page Builder (Avada) easily</span></th>
				    		<td><i class="dashicons dashicons-yes"></i></td>
				    		<td><i class="dashicons dashicons-yes"></i></td>
				    	</tr>
				    	<tr>				
							<th>WP Templating Features  <span>You can modify plugin html/designs in your current theme.</span></th>
							<td><i class="dashicons dashicons-yes"> </i></td>
							<td><i class="dashicons dashicons-yes"> </i></td>
						</tr>
						<tr>
							<th>Drag & Drop Slide Order Change <span>Arrange your desired slides with your desired order and display</span></th>
							<td><i class="dashicons dashicons-yes"></i></td>
							<td><i class="dashicons dashicons-yes"></i></td>
						</tr>
						<tr>
							<th>Timeline Categories Wise <span>Display Timeline categories wise</span></th>
							<td><i class="dashicons dashicons-no-alt"></i></td>
							<td><i class="dashicons dashicons-yes"></i></td>
						</tr>
						

						<tr>
							<th>Show/hide post link  <span>Option Show/hide post link</span></th>
							<td><i class="dashicons dashicons-no-alt"></i></td>
							<td><i class="dashicons dashicons-yes"></i></td>
						</tr> 
						<tr>
							<th>Separate Field for Custom icon <span>Seprate Field availabe if you want to add Custom icon</span></th>
							<td><i class="dashicons dashicons-no-alt"></i></td>
							<td><i class="dashicons dashicons-yes"></i></td>
						</tr>	    	
						
						<tr>
							<th>Content Words Limit <span>Controls Word limit for content. Default value is 70</span></th>
							<td><i class="dashicons dashicons-no-alt"></i></td>
							<td><i class="dashicons dashicons-yes"></i></td>
						</tr>
						
						
						<tr>
							<th>Elementor Page Builder Support <em class="teh-new-feature">New</em> <span>Use this plugin with Elementor easily</span></th>
							<td><i class="dashicons dashicons-no-alt"></i></td>
							<td><i class="dashicons dashicons-yes"></i></td>
						</tr>
						<tr>
							<th>Beaver Builder Support <em class="teh-new-feature">New</em> <span>Use this plugin with Beaver Builder easily</span></th>
							<td><i class="dashicons dashicons-no-alt"></i></td>
							<td><i class="dashicons dashicons-yes"></i></td>
						</tr>
						<tr>
							<th>SiteOrigin Page Builder Support <em class="teh-new-feature">New</em> <span>Use this plugin with SiteOrigin easily</span></th>
							<td><i class="dashicons dashicons-no-alt"></i></td>
							<td><i class="dashicons dashicons-yes"></i></td>
						</tr>
						
						<tr>
							<th>Exclude Categories <span>You can pass multiple categories ids by comma separated.</span></th>
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