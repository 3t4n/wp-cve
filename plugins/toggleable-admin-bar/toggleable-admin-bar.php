<?php
	/*
		Plugin Name: Toggleable Admin Bar
		Plugin URI: http://www.michaelsmyth.co.uk
		Description: Allows you to toggle the admin bar on the front end. Useful for websites with fixed positioned elements, as the admin bar can get in the way. Now has quick-links.
		Author: Michael Smyth
		Version: 1.3.1
		Author URI: http://www.michaelsmyth.co.uk
	*/

	function remove_admin_margin() {

		if ( is_user_logged_in() && is_admin_bar_showing() ) {
			remove_action( 'wp_head', '_admin_bar_bump_cb' );
		}

	}

	function toggleable_admin_bar() {

		if ( is_user_logged_in() && is_admin_bar_showing() ) {

			global $wp_admin_bar;
			$wp_admin_bar->add_menu( array(
				'id' => 'wpadminbar-dashboard-ql',
				'parent' => 'top-secondary',
				'title' => '<a id="wpadminbar-dashboard-ql" href="' . get_admin_url() . '"><span class="ab-icon"></span></a>'
			));
			$wp_admin_bar->add_menu( array(
				'id' => 'wpadminbar-edit-ql',
				'parent' => 'top-secondary',
				'title' => '<a id="wpadminbar-edit-ql" href="' . get_edit_post_link() . '"><span class="ab-icon"></span></a>'
			));
			$wp_admin_bar->add_menu( array(
				'id' => 'wpadminbar-toggle',
				'parent' => 'top-secondary',
				'title' => '<a id="wpadminbar-toggle"><span class="ab-icon"></span></a>'
			));

	?>
			<style type="text/css">
				#wpadminbar {
					top: -32px;
					-webkit-transition: all .25s ease-in-out;
					   -moz-transition: all .25s ease-in-out;
						-ms-transition: all .25s ease-in-out;
						 -o-transition: all .25s ease-in-out;
							transition: all .25s ease-in-out;
				}
				#wpadminbar.open {
					top: 0;
				}
				#wpadminbar #wp-admin-bar-wpadminbar-toggle,
				#wpadminbar #wp-admin-bar-wpadminbar-edit-ql,
				#wpadminbar #wp-admin-bar-wpadminbar-dashboard-ql {
					width: 0;
					height: 0;
					position: static;
					display: block !important;
				}
				#wpadminbar #wp-admin-bar-wpadminbar-toggle .ab-item,
				#wpadminbar #wp-admin-bar-wpadminbar-edit-ql .ab-item,
				#wpadminbar #wp-admin-bar-wpadminbar-dashboard-ql .ab-item {
					padding: 0;
					width: 0;
					height: 0;
				}
				#wpadminbar #wp-admin-bar-wpadminbar-toggle .ab-item #wpadminbar-toggle,
				#wpadminbar #wp-admin-bar-wpadminbar-edit-ql .ab-item #wpadminbar-edit-ql,
				#wpadminbar #wp-admin-bar-wpadminbar-dashboard-ql .ab-item #wpadminbar-dashboard-ql {
					width: 32px;
					padding: 0;
					height: 32px;
					text-align: center;
					line-height: 32px;
					position: absolute;
					top: 100%;
					right: 0;
					cursor: pointer;
					background: #222;
				}
				#wpadminbar #wp-admin-bar-wpadminbar-edit-ql .ab-item #wpadminbar-edit-ql {
					right: 32px;
				}
				#wpadminbar #wp-admin-bar-wpadminbar-dashboard-ql .ab-item #wpadminbar-dashboard-ql {
					right: 64px;
				}
				#wpadminbar #wp-admin-bar-wpadminbar-toggle .ab-item #wpadminbar-toggle:hover,
				#wpadminbar #wp-admin-bar-wpadminbar-edit-ql .ab-item #wpadminbar-edit-ql:hover,
				#wpadminbar #wp-admin-bar-wpadminbar-dashboard-ql .ab-item #wpadminbar-dashboard-ql:hover {
					background: #333;
				}
				#wpadminbar #wp-admin-bar-wpadminbar-toggle .ab-item #wpadminbar-toggle .ab-icon,
				#wpadminbar #wp-admin-bar-wpadminbar-edit-ql .ab-item #wpadminbar-edit-ql .ab-icon,
				#wpadminbar #wp-admin-bar-wpadminbar-dashboard-ql .ab-item #wpadminbar-dashboard-ql .ab-icon {
					float: none;
					padding: 0;
					margin: 0;
					display: block;
					line-height: 28px;
				}
				#wpadminbar #wp-admin-bar-wpadminbar-toggle .ab-item #wpadminbar-toggle .ab-icon,
				#wpadminbar #wp-admin-bar-wpadminbar-toggle.hover .ab-item #wpadminbar-toggle .ab-icon,
				#wpadminbar #wp-admin-bar-wpadminbar-toggle .ab-item #wpadminbar-toggle .ab-icon:before,
				#wpadminbar #wp-admin-bar-wpadminbar-toggle.hover .ab-item #wpadminbar-toggle .ab-icon:before,
				#wpadminbar #wp-admin-bar-wpadminbar-edit-ql .ab-item #wpadminbar-edit-ql .ab-icon,
				#wpadminbar #wp-admin-bar-wpadminbar-edit-ql.hover .ab-item #wpadminbar-edit-ql .ab-icon,
				#wpadminbar #wp-admin-bar-wpadminbar-edit-ql .ab-item #wpadminbar-edit-ql .ab-icon:before,
				#wpadminbar #wp-admin-bar-wpadminbar-edit-ql.hover .ab-item #wpadminbar-edit-ql .ab-icon:before,
				#wpadminbar #wp-admin-bar-wpadminbar-dashboard-ql .ab-item #wpadminbar-dashboard-ql .ab-icon,
				#wpadminbar #wp-admin-bar-wpadminbar-dashboard-ql.hover .ab-item #wpadminbar-dashboard-ql .ab-icon,
				#wpadminbar #wp-admin-bar-wpadminbar-dashboard-ql .ab-item #wpadminbar-dashboard-ql .ab-icon:before,
				#wpadminbar #wp-admin-bar-wpadminbar-dashboard-ql.hover .ab-item #wpadminbar-dashboard-ql .ab-icon:before {
					color: #999 !important;
				}
				#wpadminbar #wp-admin-bar-wpadminbar-toggle .ab-item #wpadminbar-toggle:hover .ab-icon,
				#wpadminbar #wp-admin-bar-wpadminbar-toggle .ab-item #wpadminbar-toggle:hover .ab-icon:before,
				#wpadminbar #wp-admin-bar-wpadminbar-edit-ql .ab-item #wpadminbar-edit-ql:hover .ab-icon,
				#wpadminbar #wp-admin-bar-wpadminbar-edit-ql .ab-item #wpadminbar-edit-ql:hover .ab-icon:before,
				#wpadminbar #wp-admin-bar-wpadminbar-dashboard-ql .ab-item #wpadminbar-dashboard-ql:hover .ab-icon,
				#wpadminbar #wp-admin-bar-wpadminbar-dashboard-ql .ab-item #wpadminbar-dashboard-ql:hover .ab-icon:before {
					color: #45bbe6 !important;
				}
				#wpadminbar #wp-admin-bar-wpadminbar-toggle .ab-item #wpadminbar-toggle .ab-icon:before {
					content: '\f347';
					top: 4px;
				}
				#wpadminbar #wp-admin-bar-wpadminbar-edit-ql .ab-item #wpadminbar-edit-ql .ab-icon:before {
					content: '\f464';
					top: 2px;
				}
				#wpadminbar #wp-admin-bar-wpadminbar-dashboard-ql .ab-item #wpadminbar-dashboard-ql .ab-icon:before {
					content: '\f226';
					top: 2px;
				}

				#wpadminbar #wp-admin-bar-wpadminbar-toggle .ab-item #wpadminbar-toggle.active .ab-icon:before {
					content: '\f343';
				}

				.no-js {
					margin-top: 32px;
				}
				.no-js #wp-admin-bar-top-secondary {
					display: none !important;
				}
				.no-js #wpadminbar {
					top: 0;
				}
				@media screen and (max-width: 782px) {
					.no-js {
						margin-top: 46px;
					}
					#wpadminbar {
						top: -46px;
					}
					#wpadminbar #wp-admin-bar-wpadminbar-toggle .ab-item #wpadminbar-toggle,
					#wpadminbar #wp-admin-bar-wpadminbar-edit-ql .ab-item #wpadminbar-edit-ql,
					#wpadminbar #wp-admin-bar-wpadminbar-dashboard-ql .ab-item #wpadminbar-dashboard-ql,
					#wpadminbar #wp-admin-bar-wpadminbar-toggle .ab-item #wpadminbar-toggle .ab-icon,
					#wpadminbar #wp-admin-bar-wpadminbar-edit-ql .ab-item #wpadminbar-edit-ql .ab-icon,
					#wpadminbar #wp-admin-bar-wpadminbar-dashboard-ql .ab-item #wpadminbar-dashboard-ql .ab-icon {
						width: 34px;
						height: 34px;
						line-height: 28px !important;
						font-size: 24px !important;
					}
					#wpadminbar #wp-admin-bar-wpadminbar-edit-ql .ab-item #wpadminbar-edit-ql {
						right: 34px;
					}
					#wpadminbar #wp-admin-bar-wpadminbar-dashboard-ql .ab-item #wpadminbar-dashboard-ql {
						right: 68px;
					}
				}
			</style>
	<?php
		}
	}

	function toggleable_admin_bar_js() {

		if ( is_user_logged_in() && is_admin_bar_showing() ) {
	?>
			<script type="text/javascript">
				jQuery(document).ready(function() {
					jQuery('#wpadminbar-toggle').on('click', function() {
						jQuery(this).toggleClass('active');
						jQuery('#wpadminbar').toggleClass('open');
					});
				});
			</script>
	<?php
		}
	}

	add_action( 'wp_head', 'toggleable_admin_bar', 100 );
	add_action( 'wp_footer', 'toggleable_admin_bar_js', 100 );
	add_action( 'get_header', 'remove_admin_margin' );
?>