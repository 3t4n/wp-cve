<?php
/**
 * This file contains javascript code.
 *
 * @author  Tech Banker
 * @package captcha-bank/includes
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} //exit if accessed directly
if ( ! is_user_logged_in() ) {
	return;
} else {
	$access_granted = false;
	foreach ( $user_role_permission as $permission ) {
		if ( current_user_can( $permission ) ) {
			$access_granted = true;
			break;
		}
	}
	if ( ! $access_granted ) {
		return;
	} else {
		?>
		</div>
		</div>
		</div>
		<script type="text/javascript">
		jQuery("li > a").parents("li").each(function ()
		{
			if (jQuery(this).parent("ul.page-sidebar-menu-tech-banker").size() === 1)
			{
				jQuery(this).find("> a").append("<span class=\"selected\"></span>");
			}
		});
		jQuery(".page-sidebar-tech-banker").on("click", "li > a", function (e)
		{
			var hasSubMenu = jQuery(this).next().hasClass("sub-menu");
			var parent = jQuery(this).parent().parent();
			var sidebar_menu = jQuery(".page-sidebar-menu-tech-banker");
			var sub = jQuery(this).next();
			var slideSpeed = parseInt(sidebar_menu.data("slide-speed"));
			parent.children("li.open").children(".sub-menu:not(.always-open)").slideUp(slideSpeed);
			parent.children("li.open").removeClass("open");
			if (sub.is(":visible"))
			{
				jQuery(this).parent().removeClass("open");
				sub.slideUp(slideSpeed);
			} else if (hasSubMenu)
			{
				jQuery(this).parent().addClass("open");
				sub.slideDown(slideSpeed);
			}
		});
		if (typeof (load_sidebar_content_captcha_bank) !== "function")
		{
			function load_sidebar_content_captcha_bank()
			{
				var menus_height = jQuery(".page-sidebar-menu-tech-banker").height();
				var content_height = jQuery(".page-content").height() + 30;
				if (parseInt(menus_height) > parseInt(content_height))
				{
					jQuery(".page-content").attr("style", "min-height:" + menus_height + "px");
				} else
				{
					jQuery(".page-sidebar-menu-tech-banker").attr("style", "min-height:" + content_height + "px");
				}
			}
		}
		var sidebar_load_interval = setInterval(load_sidebar_content_captcha_bank, 1000);
		setTimeout(function ()
		{
			clearInterval(sidebar_load_interval);
		},3000);
		if (typeof (prevent_paste_captcha_bank) !== "function")
		{
			function prevent_paste_captcha_bank(control_id)
			{
				jQuery("#" + control_id).live("paste", function (e)
				{
					e.preventDefault();
				});
			}
		}
		if (typeof (overlay_loading_captcha_bank) !== "function")
		{
			function overlay_loading_captcha_bank(control_id)
			{
				var overlay_opacity = jQuery("<div class=\"opacity_overlay\"></div>");
				jQuery("body").append(overlay_opacity);
				var overlay = jQuery("<div class=\"loader_opacity\"><div class=\"processing_overlay\"></div></div>");
				jQuery("body").append(overlay);
				if (control_id !== undefined)
				{
					var message = control_id;
					var success = <?php echo wp_json_encode( $cpb_success ); ?>;
					var issuccessmessage = jQuery("#toast-container").exists();
					if (issuccessmessage !== true)
					{
					var shortCutFunction = jQuery("#manage_messages input:checked").val();
					toastr[shortCutFunction](message, success);
					}
				}
			}
		}
		if (typeof (remove_overlay_captcha_bank) !== "function")
		{
			function remove_overlay_captcha_bank()
			{
				jQuery(".loader_opacity").remove();
				jQuery(".opacity_overlay").remove();
			}
		}
		if (typeof (check_value_captcha_bank) !== "function")
		{
			function check_value_captcha_bank(id)
			{
				jQuery(id).val() === "" ? jQuery(id).val(0) : jQuery(id).val();
			}
		}
		function premium_edition_notification_captcha_bank()
		{
			var premium_edition = <?php echo wp_json_encode( $cpb_message_premium_edition ); ?>;
			var shortCutFunction = jQuery("#toastTypeGroup_error input:checked").val();
			toastr[shortCutFunction](premium_edition);
		}
		if (typeof (paste_only_digits_captcha_bank) !== "function")
		{
			function paste_only_digits_captcha_bank(control_id)
			{
				jQuery("#" + control_id).on("paste keypress", function (e)
				{
					var $this = jQuery("#" + control_id);
					setTimeout(function ()
					{
					$this.val($this.val().replace(/[^0-9]/g, ""));
					}, 5);
				});
			}
		}
		jQuery(document).ready(function ()
		{
			jQuery("#ux_txt_cpb_start_date_ip_range").datepicker
			({
				dateFormat: 'mm/dd/yy',
				numberOfMonths: 1,
				changeMonth: true,
				changeYear: true,
				yearRange: "1970:2039",
				onSelect: function (selected)
				{
					jQuery("#ux_txt_cpb_end_date_ip_range").datepicker("option", "minDate", selected);
				}
			});
			jQuery("#ux_txt_cpb_end_date_ip_range").datepicker
			({
				dateFormat: 'mm/dd/yy',
				numberOfMonths: 1,
				changeMonth: true,
				changeYear: true,
				yearRange: "1970:2039",
				onSelect: function (selected)
				{
					jQuery("#ux_txt_cpb_start_date_ip_range").datepicker("option", "maxDate", selected);
				}
			});
			jQuery("#ux_txt_cpb_start_date").datepicker
			({
				dateFormat: 'mm/dd/yy',
				numberOfMonths: 1,
				changeMonth: true,
				changeYear: true,
				yearRange: "1970:2039",
				onSelect: function (selected)
				{
					jQuery("#ux_txt_cpb_end_date").datepicker("option", "minDate", selected);
				}
			});
			jQuery("#ux_txt_cpb_end_date").datepicker
			({
				dateFormat: 'mm/dd/yy',
				numberOfMonths: 1,
				changeMonth: true,
				changeYear: true,
				yearRange: "1970:2039",
				onSelect: function (selected)
				{
					jQuery("#ux_txt_cpb_start_date").datepicker("option", "maxDate", selected);
				}
			});
		});
		if (typeof (prevent_data_captcha_bank) !== "function")
		{
			function prevent_data_captcha_bank(event)
			{
				event.preventDefault();
			}
		}
		if (typeof (sort_function_captcha_bank) !== "function")
		{
			function sort_function_captcha_bank(control_id)
			{
				var options = jQuery("#" + control_id + " option");
				var arr = options.map(function (_, o)
				{
					return{
					t: jQuery(o).text(),
					v: o.value
					};
				}).get();
				arr.sort(function (o1, o2)
				{
					return o1.t > o2.t ? 1 : o1.t < o2.t ? -1 : 0;
				});
				options.each(function (i, o)
				{
					o.value = arr[i].v;
					jQuery(o).text(arr[i].t);
				});
			}
		}
		if (typeof (check_color_captcha_bank) !== "function")
		{
			function check_color_captcha_bank(id)
			{
				jQuery(id).val() === "" ? jQuery(id).val("#000000") : jQuery(id).val();
			}
		}
		if (typeof (valid_ip_address_captcha_bank) !== "function")
		{
			function valid_ip_address_captcha_bank(event)
			{
				if (event.which === 8 || event.keyCode === 37 || event.keyCode === 39 || event.keyCode === 46 || event.keyCode === 9 || event.keyCode === 110)
				{
					return true;
				} else if (event.which !== 46 && (event.which < 48 || event.which > 57))
				{
					event.preventDefault();
				}
			}
		}
		if (typeof (ip2long) !== "function")
		{
			function ip2long(IP)
			{
				var i = 0;
				IP = IP.match(
						/^([1-9]\d*|0[0-7]*|0x[\da-f]+)(?:\.([1-9]\d*|0[0-7]*|0x[\da-f]+))?(?:\.([1-9]\d*|0[0-7]*|0x[\da-f]+))?(?:\.([1-9]\d*|0[0-7]*|0x[\da-f]+))?$/i
						);
				if (!IP)
				{
					return false;
				}
				IP[0] = 0;
				for (i = 1; i < 5; i += 1) {
					IP[0] += !!((IP[i] || '')
							.length);
					IP[i] = parseInt(IP[i]) || 0;
				}
				IP.push(256, 256, 256, 256);
				IP[4 + IP[0]] *= Math.pow(256, 4 - IP[0]);
				if (IP[1] >= IP[5] || IP[2] >= IP[6] || IP[3] >= IP[7] || IP[4] >= IP[8])
				{
					return false;
				}
				return IP[1] * (IP[0] === 1 || 16777216) + IP[2] * (IP[0] <= 2 || 65536) + IP[3] * (IP[0] <= 3 || 256) + IP[4] * 1;
			}
		}
		if (typeof (base64_encode) !== "function")
		{
			function base64_encode(data)
			{
				var b64 = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=';
				var o1, o2, o3, h1, h2, h3, h4, bits, i = 0,
						ac = 0,
						enc = '',
						tmp_arr = [];
				if (!data)
				{
					return data;
				}
				do
				{
					o1 = data.charCodeAt(i++);
					o2 = data.charCodeAt(i++);
					o3 = data.charCodeAt(i++);
					bits = o1 << 16 | o2 << 8 | o3;
					h1 = bits >> 18 & 0x3f;
					h2 = bits >> 12 & 0x3f;
					h3 = bits >> 6 & 0x3f;
					h4 = bits & 0x3f;
					tmp_arr[ac++] = b64.charAt(h1) + b64.charAt(h2) + b64.charAt(h3) + b64.charAt(h4);
				} while (i < data.length);
				enc = tmp_arr.join('');
				var r = data.length % 3;
				return (r ? enc.slice(0, r - 3) : enc) + '==='.slice(r || 3);
			}
		}
		function select_type_contact_bank(id)
		{
			var type = jQuery(id).val();
			switch( type )
			{
				case 'single':
					jQuery("#ux_div_single_ip").css("display", "block");
					jQuery("#ux_div_ip_range").css("display", "none");
					jQuery("#ux_div_multiple_ip").css("display", "none");
				break;
				case 'range':
					jQuery("#ux_div_single_ip").css("display", "none");
					jQuery("#ux_div_ip_range").css("display", "block");
					jQuery("#ux_div_multiple_ip").css("display", "none");
				break;
				case 'multiple':
					jQuery("#ux_div_single_ip").css("display", "none");
					jQuery("#ux_div_ip_range").css("display", "none");
					jQuery("#ux_div_multiple_ip").css("display", "block");
				break;
			}
		}
		if (typeof (get_datatable_captcha_bank) !== "function")
		{
			function get_datatable_captcha_bank(id)
			{
				var oTable = jQuery(id).dataTable
				({
					"pagingType": "full_numbers",
					"language":
							{
							"emptyTable": "No data available in table",
							"info": "Showing _START_ to _END_ of _TOTAL_ entries",
							"infoEmpty": "No entries found",
							"infoFiltered": "(filtered1 from _MAX_ total entries)",
							"lengthMenu": "Show _MENU_ entries",
							"search": "Search:",
							"zeroRecords": "No matching records found"
							},
					"bSort": true,
					"pageLength": 10,
					"aoColumnDefs": [{"bSortable": false, "aTargets": [0]}]
				});
				return oTable;
			}
		}
		if (typeof (check_all_captcha_bank) !== "function")
		{
			function check_all_captcha_bank( id, table_id )
			{
				if ((jQuery("input:checked", table_id.fnGetFilteredNodes()).length) === jQuery("input[type=checkbox]", table_id.fnGetFilteredNodes()).length)
				{
					jQuery(id).attr("checked", "checked");
				} else
				{
					jQuery(id).removeAttr("checked");
				}
			}
		}
		if (typeof (check_ip_address_captcha_bank) !== "function")
		{
			function check_ip_address_captcha_bank( id )
			{
				var single_ip  = jQuery(id).val();
				var flag;
				if (single_ip !== "")
				{
					if (!single_ip.match(/^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/))
					{
						var shortCutFunction = jQuery("#toastTypeGroup_error input:checked").val();
						toastr[shortCutFunction](<?php echo wp_json_encode( $cpb_valid_ip_address ); ?>,<?php echo wp_json_encode( $cpb_error_message ); ?>);
						return flag = false;
					}
					return flag = true;
				}
			}
		}
		if (typeof (check_multiple_ip_address_captcha_bank) !== "function")
		{
			function check_multiple_ip_address_captcha_bank( id )
			{
				var multiple_ip  = jQuery(id).val();
				var res = multiple_ip.split(",");
				var flag;
				jQuery.each( res, function( index, value )
				{
					if (value !== "")
					{
						if (!value.match(/^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/))
						{
							var shortCutFunction = jQuery("#toastTypeGroup_error input:checked").val();
							toastr[shortCutFunction](<?php echo wp_json_encode( $cpb_valid_ip_address ); ?>,<?php echo wp_json_encode( $cpb_error_message ); ?>);
							return flag = false;
						}
					}
					return flag = true;
				});
				return flag;
			}
		}
		function delete_ip_address_data_captcha_bank( id, param, nonce, page_url )
		{
			var confirm_delete = confirm(<?php echo wp_json_encode( $cpb_confirm ); ?>);
			if (confirm_delete === true)
			{
				overlay_loading_captcha_bank(<?php echo wp_json_encode( $cpb_delete_data ); ?>);
				jQuery.post(ajaxurl,
				{
					id: id,
					param: param,
					action: "captcha_bank_action_library",
					_wp_nonce: nonce
				},
				function ()
				{
					setTimeout(function ()
					{
					remove_overlay_captcha_bank();
					window.location.href = "admin.php?page=" + page_url;
					}, 3000);
				});
			}
		}
		if (typeof (cpb_colorpicker) !== "function")
		{
			function cpb_colorpicker(id, value)
			{
				jQuery("#" + id).colpick
						({
							layout: "hex",
							colorScheme: "dark",
							color: value,
							onChange: function (hsb, hex, rgb, el, bySetColor)
							{
							if (!bySetColor)
								jQuery(el).val("#" + hex);
							}
						}).keyup(function ()
				{
					jQuery(this).colpickSetColor("#" + this.value);
				});
			}
		}
		var latitude = 51.83790;
		var longitude = -17.35093;
		if (typeof (cpb_initialize) !== "function")
		{
			function cpb_initialize()
			{
				var mapOptions =
				{
					center: new google.maps.LatLng(latitude, longitude),
					zoom: 2,
					streetViewControl: false
				};
				var map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);
				<?php
				if ( isset( $cpb_data_logs ) ) {
					foreach ( $cpb_data_logs as $latlong ) {
						?>
						var position = new google.maps.LatLng(<?php echo wp_json_encode( $latlong['latitude'] ); ?>,<?php echo wp_json_encode( $latlong['longitude'] ); ?>);
						var marker = new google.maps.Marker(
						{
							position: position,
							map: map,
							draggable: false,
							icon: "<?php echo esc_attr( plugins_url( 'assets/global/img/map-marker.png', dirname( __FILE__ ) ) ); ?>",
							title: <?php echo wp_json_encode( $latlong['location'] ); ?>
						});
						marker.content = "<b>" +<?php echo wp_json_encode( $cpb_ip_address ); ?> + ": </b>" + <?php echo wp_json_encode( long2ip( $latlong['user_ip_address'] ) ); ?> +
						"<br><b>" +<?php echo wp_json_encode( $cpb_location ); ?> + ": </b>" + <?php echo '' !== $latlong['location'] ? wp_json_encode( $latlong['location'] ) : wp_json_encode( $cpb_na ); ?> +
						"<br><b>" +<?php echo wp_json_encode( $cpb_latitude ); ?> + ": </b>" +<?php echo '' !== $latlong['latitude'] ? wp_json_encode( $latlong['latitude'] ) : wp_json_encode( $cpb_na ); ?> +
						"<br><b>" +<?php echo wp_json_encode( $cpb_longitude ); ?> + ": </b>" +<?php echo '' !== $latlong['longitude'] ? wp_json_encode( $latlong['longitude'] ) : wp_json_encode( $cpb_na ); ?> +
						"<br><b>" +<?php echo wp_json_encode( $cpb_http_user_agent ); ?> + ": </b>" + <?php echo wp_json_encode( $latlong['http_user_agent'] ); ?>;
						var infoWindow = new google.maps.InfoWindow();
						google.maps.event.addListener(marker, "click", function ()
						{
							infoWindow.setContent(this.content);
							infoWindow.open(this.getMap(), this);
						});
						<?php
					}
				}
				?>
			}
		}
		if (typeof (delete_selected_log_captcha_bank) !== "function")
		{
			function delete_selected_log_captcha_bank(meta_id, overlay_loading, url)
			{
				var confirm_delete = confirm(<?php echo wp_json_encode( $cpb_confirm ); ?>);
				if (confirm_delete === true)
				{
					overlay_loading_captcha_bank(overlay_loading);
					jQuery.post(ajaxurl,
					{
						meta_id: meta_id,
						param: "captcha_log_delete_module",
						action: "captcha_bank_action_library",
						_wp_nonce: "<?php echo isset( $cpb_selected_logs_delete ) ? esc_attr( $cpb_selected_logs_delete ) : ''; ?>"
					},
					function ()
					{
						setTimeout(function ()
						{
							remove_overlay_captcha_bank();
							window.location.href = url;
						}, 3000);
					});
				}
			}
		}
		if (typeof (captcha_bank_show_time_for) !== "function")
		{
			function captcha_bank_show_time_for(id, div_id)
			{
				if (jQuery(id).val() === "block")
				{
					jQuery(div_id).css("display", "inline-block");
				} else
				{
					jQuery(div_id).css("display", "none");
				}
			}
		}
		<?php
		$check_captcha_bank_wizard = get_option( 'captcha-bank-wizard-set-up' );
		if ( isset( $_GET['page'] ) ) {
			$page = sanitize_text_field( wp_unslash( $_GET['page'] ) ); // WPCS: CSRF ok, input var ok.
		}
		$licensing_url = false === $check_captcha_bank_wizard ? 'captcha_bank_wizard' : $page;
		if ( isset( $_REQUEST['page'] ) ) {// WPCS: CSRF ok,WPCS: input var ok.
			switch ( $licensing_url ) {
				case 'captcha_bank_wizard':
					?>
					if (typeof (show_hide_details_captcha_bank) !== "function")
					{
						function show_hide_details_captcha_bank()
						{
							if (jQuery("#ux_div_wizard_set_up").hasClass("wizard-set-up"))
							{
								jQuery("#ux_div_wizard_set_up").css("display", "none");
								jQuery("#ux_div_wizard_set_up").removeClass("wizard-set-up");
							} else
							{
								jQuery("#ux_div_wizard_set_up").css("display", "block");
								jQuery("#ux_div_wizard_set_up").addClass("wizard-set-up");
							}
						}
					}
					if (typeof (plugin_stats_captcha_bank) !== "function")
					{
						function plugin_stats_captcha_bank(type)
						{
							var email_pattern = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
							if( (jQuery("#ux_txt_email_address_notifications").val() ===  "" || false == email_pattern.test(jQuery("#ux_txt_email_address_notifications").val())) && type !== "skip")
							{
								if( jQuery("#ux_txt_email_address_notifications").val() ===  "" || false == email_pattern.test(jQuery("#ux_txt_email_address_notifications").val()) )
								{
									jQuery("#ux_txt_validation_gdpr_captcha_bank").css({"display":'','color':'red'});
									jQuery("#ux_txt_email_address_notifications").css("border-color","red");
								}
								else {
									jQuery("#ux_txt_validation_gdpr_captcha_bank").css( 'display','none' );
									jQuery("#ux_txt_email_address_notifications").css("border-color","#ddd");
								}
							}
							else{
								jQuery("#ux_txt_validation_gdpr_captcha_bank").css( 'display','none' );
								jQuery("#ux_txt_email_address_notifications").css("border-color","#ddd");
								overlay_loading_captcha_bank();
								jQuery.post(ajaxurl,
									{
										id: jQuery("#ux_txt_email_address_notifications").val(),
										type: type,
										param: "wizard_captcha_bank",
										action: "captcha_bank_action_library",
										_wp_nonce: "<?php echo esc_attr( $captcha_bank_check_status ); ?>"
									},
									function (data)
									{
										console.log(data);
										alert(data);
										remove_overlay_captcha_bank();
										window.location.href = "admin.php?page=captcha_bank";
									});
							}
						}
					}
					<?php
					break;
				case 'captcha_bank':
					?>
					jQuery("#ux_li_captcha_settings").addClass("active");
					load_sidebar_content_captcha_bank();
					<?php
					if ( CAPTCHA_SETTINGS_CAPTCHA_BANK === '1' ) {
						?>
						if (typeof (captcha_bank_second_step_settings) !== "function")
						{
							function captcha_bank_second_step_settings()
							{
								jQuery("#ux_div_first_step").css("display", "none");
								jQuery("#ux_div_third_step").css("display", "none");
								jQuery("#ux_div_fourth_step").css("display", "none");
								jQuery("#ux_div_second_step").css("display", "block");
								jQuery("#ux_div_step_progres_bar_width").css("width", "50%");
								jQuery("#ux_div_frm_wizard li:eq(1)").addClass("active");
								jQuery("#ux_div_frm_wizard li:eq(2)").removeClass("active");
							}
						}
						if (typeof (captcha_bank_third_step_settings) !== "function")
						{
							function captcha_bank_third_step_settings()
							{
								jQuery("#ux_div_fourth_step").removeClass("fourth-step-helper");
								jQuery("#ux_div_first_step").css("display", "none");
								jQuery("#ux_div_second_step").css("display", "none");
								jQuery("#ux_div_fourth_step").css("display", "none");
								jQuery("#ux_div_third_step").css("display", "block");
								jQuery("#ux_div_step_progres_bar_width").css("width", "75%");
								jQuery("#ux_div_frm_wizard li:eq(1)").addClass("active");
								jQuery("#ux_div_frm_wizard li:eq(2)").addClass("active");
								jQuery("#ux_div_frm_wizard li:eq(3)").removeClass("active");
							}
						}
						if (typeof (captcha_bank_fourth_step_settings) !== "function")
						{
							function captcha_bank_fourth_step_settings()
							{
								jQuery("#ux_div_first_step").removeClass("first-step-helper");
								jQuery("#ux_div_second_step").removeClass("second-step-helper");
								jQuery("#ux_div_fourth_step").css("display", "block");
								jQuery("#ux_div_first_step").css("display", "none");
								jQuery("#ux_div_second_step").css("display", "none");
								jQuery("#ux_div_third_step").css("display", "none");
								jQuery("#ux_div_step_progres_bar_width").css("width", "100%");
								jQuery("#ux_div_frm_wizard li:eq(1)").addClass("active");
								jQuery("#ux_div_frm_wizard li:eq(2)").addClass("active");
								jQuery("#ux_div_frm_wizard li:eq(3)").addClass("active");
							}
						}
						if (typeof (captcha_bank_move_back_to_second_step) !== "function")
						{
							function captcha_bank_move_back_to_second_step()
							{
								jQuery("#ux_div_first_step").removeClass("first-step-helper");
								jQuery("#ux_div_second_step").removeClass("second-step-helper");
								jQuery("#ux_div_first_step").css("display", "block");
								jQuery("#ux_div_fourth_step").css("display", "none");
								jQuery("#ux_div_second_step").css("display", "none");
								jQuery("#ux_div_step_progres_bar_width").css("width", "33%");
								jQuery("#ux_div_frm_wizard li:eq(1)").removeClass("active");
							}
						}
						if (typeof (captcha_bank_move_to_first_step) !== "function")
						{
							function captcha_bank_move_to_first_step()
							{
								jQuery("#ux_div_first_step").removeClass("first-step-helper");
								jQuery("#ux_div_second_step").removeClass("second-step-helper");
								jQuery("#ux_div_fourth_step").removeClass("fourth-step-helper");
								jQuery("#ux_div_second_step").css("display", "none");
								jQuery("#ux_div_first_step").css("display", "block");
								jQuery("#ux_div_fourth_step").css("display", "none");
								jQuery("#ux_div_step_progres_bar_width").css("width", "33%");
								jQuery("#ux_div_frm_wizard li:eq(1)").removeClass("active");
							}
						}
						if (typeof (captcha_bank_move_to_second_step) !== "function")
						{
							function captcha_bank_move_to_second_step()
							{
								jQuery("#ux_div_first_step").addClass("first-step-helper");
								captcha_bank_validate_settings();
							}
						}
						if (typeof (captcha_bank_move_to_third_step) !== "function")
						{
							function captcha_bank_move_to_third_step()
							{
								var type = jQuery('#ux_ddl_mathematical_operations').val();
								if ( ( type === 'relational' || type === 'arrange_order' ) && ( jQuery("#ux_rdl_captcha_type_logical_captcha").attr('checked') ) ) {
									premium_edition_notification_captcha_bank();
								} else {
									jQuery("#ux_div_first_step").removeClass("first-step-helper")
									jQuery("#ux_div_second_step").addClass("second-step-helper");
									captcha_bank_validate_settings();
								}
							}
						}
						if (typeof (captcha_bank_move_to_fourth_step) !== "function")
						{
							function captcha_bank_move_to_fourth_step()
							{
								jQuery("#ux_div_first_step").removeClass("first-step-helper");
								jQuery("#ux_div_second_step").removeClass("second-step-helper");
								jQuery("#ux_div_fourth_step").addClass("fourth-step-helper");
								captcha_bank_validate_settings();
							}
						}
						function captcha_bank_check_website_behind_proxy() {
							<?php
							if ( isset( $meta_data_array['captcha_bank_behind_proxy'] ) && '1' === $meta_data_array['captcha_bank_behind_proxy'] ) {
								$proxy_headers = array(
									'HTTP_VIA',
									'VIA',
									'Proxy-Connection',
									'HTTP_X_FORWARDED_FOR',
									'HTTP_FORWARDED_FOR',
									'HTTP_X_FORWARDED',
									'HTTP_FORWARDED',
									'HTTP_CLIENT_IP',
									'HTTP_FORWARDED_FOR_IP',
									'X-PROXY-ID',
									'MT-PROXY-ID',
									'X-TINYPROXY',
									'X_FORWARDED_FOR',
									'FORWARDED_FOR',
									'X_FORWARDED',
									'FORWARDED',
									'CLIENT-IP',
									'CLIENT_IP',
									'PROXY-AGENT',
									'HTTP_X_CLUSTER_CLIENT_IP',
									'FORWARDED_FOR_IP',
									'HTTP_PROXY_CONNECTION',
								);
								foreach ( $proxy_headers as $header ) {
									if ( isset( $_SERVER[ $header ] ) && ! empty( $_SERVER[ $header ] ) ) { // @codingStandardsIgnoreLine.
											// exit( 'Please disable your proxy connection!' );
									}
								}
							}
							?>
						}
						if (typeof (show_hide_recaptcha_attribute_captcha_bank) !== "function")
						{
							function show_hide_recaptcha_attribute_captcha_bank()
							{
								var captcha_type = jQuery("#ux_ddl_recaptcha_key_type").val();
								if( captcha_type === "v2" )
								{
									jQuery("#ux_data_size").css("display", "block");
									jQuery("#ux_data_badge").css("display", "none");
									jQuery("#ux_div_recaptcha_settings").css("display", "block");
									jQuery("#ux_div_recaptcha_key_type").removeClass("col-md-12").addClass("col-md-6");
									jQuery("#ux_div_recaptcha_type").css("display","block");
								} else if( captcha_type === "v3" ){
									jQuery("#ux_data_size").css("display", "block");
									jQuery("#ux_data_badge").css("display", "none");
									jQuery("#ux_div_recaptcha_type").css("display","none");
									jQuery("#ux_div_recaptcha_key_type").removeClass("col-md-6").addClass("col-md-12");
									jQuery("#ux_div_recaptcha_settings").css("display", "none");
								} else {
									jQuery("#ux_data_badge").css("display", "block");
									jQuery("#ux_data_size").css("display", "none");
									jQuery("#ux_div_recaptcha_settings").css("display", "block");
									jQuery("#ux_div_recaptcha_key_type").removeClass("col-md-12").addClass("col-md-6");
									jQuery("#ux_div_recaptcha_type").css("display","block");
								}
							}
						}
						if (typeof (show_captcha_type_shortcode_captcha_bank) !== "function")
						{
							function show_captcha_type_shortcode_captcha_bank()
							{
								if ( jQuery("#ux_rdl_captcha_type_recaptcha").attr("checked") )
								{
									jQuery("#ux_txt_shortcode").val('[captcha_google_recaptcha]');
								} else if ( jQuery("#ux_rdl_captcha_type_text_captcha").attr("checked") )
								{
									jQuery("#ux_txt_shortcode").val('[captcha_bank_text]');
								} else if ( jQuery("#ux_rdl_captcha_type_logical_captcha").attr("checked") )
								{
									jQuery("#ux_txt_shortcode").val('[captcha_bank_logical]');
								}
							}
						}
						if (typeof (check_conditions_display_settings_captcha_bank) !== "function")
						{
							function check_conditions_display_settings_captcha_bank(type)
							{
								switch (type)
								{
									case "login" :
										<?php
										if ( class_exists( 'bbPress' ) ) {
											?>
													jQuery("#ux_chk_login_form").prop("checked") === false ? jQuery("#ux_chk_captcha_bbpress_login").attr("disabled", "disabled") : jQuery("#ux_chk_captcha_bbpress_login").removeAttr("disabled");
													jQuery("#ux_chk_login_form").prop("checked") === false ? jQuery("#ux_chk_captcha_bbpress_login").removeAttr("checked") : "";
											<?php
										}
										if ( class_exists( 'BuddyPress' ) ) {
											?>
													jQuery("#ux_chk_login_form").prop("checked") === false ? jQuery("#ux_chk_captcha_buddypress_login").attr("disabled", "disabled") : jQuery("#ux_chk_captcha_buddypress_login").removeAttr("disabled");
													jQuery("#ux_chk_login_form").prop("checked") === false ? jQuery("#ux_chk_captcha_buddypress_login").removeAttr("checked") : "";
											<?php
										}
										?>
									break;
									case "register" :
										<?php
										if ( class_exists( 'bbPress' ) ) {
											?>
													jQuery("#ux_chk_registration_form").prop("checked") === false ? jQuery("#ux_chk_captcha_bbpress_register").attr("disabled", "disabled") : jQuery("#ux_chk_captcha_bbpress_register").removeAttr("disabled");
													jQuery("#ux_chk_registration_form").prop("checked") === false ? jQuery("#ux_chk_captcha_bbpress_register").removeAttr("checked") : "";
											<?php
										}
										?>
									break;
									case "lost_password" :
										<?php
										if ( class_exists( 'bbPress' ) ) {
											?>
													jQuery("#ux_chk_password_form").prop("checked") === false ? jQuery("#ux_chk_captcha_bbpress_lost_password").attr("disabled", "disabled") : jQuery("#ux_chk_captcha_bbpress_lost_password").removeAttr("disabled");
													jQuery("#ux_chk_password_form").prop("checked") === false ? jQuery("#ux_chk_captcha_bbpress_lost_password").removeAttr("checked") : "";
											<?php
										}
										if ( class_exists( 'BuddyPress' ) ) {
											?>
													jQuery("#ux_chk_password_form").prop("checked") === false ? jQuery("#ux_chk_buddypress_lost_password").attr("disabled", "disabled") : jQuery("#ux_chk_buddypress_lost_password").removeAttr("disabled");
													jQuery("#ux_chk_password_form").prop("checked") === false ? jQuery("#ux_chk_buddypress_lost_password").removeAttr("checked") : "";
											<?php
										}
										?>
									break;
								}
							}
						}
						if (typeof (captcha_bank_save_changes) !== "function")
						{
							function captcha_bank_save_changes()
							{
								overlay_loading_captcha_bank(<?php echo wp_json_encode( $cpb_setting_saved ); ?>);
								setTimeout(function ()
								{
									remove_overlay_captcha_bank();
									window.location.href = "admin.php?page=captcha_bank";
								}, 3000);
							}
						}
						if (typeof (change_captcha_type_captcha_bank) !== "function")
						{
							function change_captcha_type_captcha_bank( type )
							{
								switch (type)
								{
									case "recaptcha":
										jQuery("#ux_div_recaptcha").css("display", "block");
										jQuery("#ux_div_text_captcha").css("display", "none");
										jQuery("#ux_div_logical_captcha").css("display", "none");
										break;
									case "text_captcha":
										jQuery("#ux_div_text_captcha").css("display", "block");
										jQuery("#ux_div_recaptcha").css("display", "none");
										jQuery("#ux_div_logical_captcha").css("display", "none");
										break;
									case "logical_captcha":
										jQuery("#ux_div_logical_captcha").css("display", "block");
										jQuery("#ux_div_recaptcha").css("display", "none");
										jQuery("#ux_div_text_captcha").css("display", "none");
										break;
								}
							}
						}
						if (typeof (change_mathematical_captcha_bank) !== "function")
						{
							function change_mathematical_captcha_bank()
							{
								var type = jQuery("#ux_ddl_mathematical_operations").val();
								switch (type)
								{
								case "arithmetic":
									jQuery("#ux_div_arithmetic_captcha").css("display", "block");
									jQuery("#ux_div_relational_captcha").css("display", "none");
									jQuery("#ux_div_arrange_captcha").css("display", "none");
								break;
								case "relational":
									jQuery("#ux_div_arithmetic_captcha").css("display", "none");
									jQuery("#ux_div_relational_captcha").css("display", "block");
									jQuery("#ux_div_arrange_captcha").css("display", "none");
								break;
								case "arrange_order":
									jQuery("#ux_div_arithmetic_captcha").css("display", "none");
									jQuery("#ux_div_relational_captcha").css("display", "none");
									jQuery("#ux_div_arrange_captcha").css("display", "block");
								break;
								}
							}
						}
						if (typeof (captcha_bank_save_changes) !== "function")
						{
							function captcha_bank_save_changes()
							{
								overlay_loading_captcha_bank(<?php echo wp_json_encode( $cpb_setting_saved ); ?>);
								setTimeout(function ()
								{
									remove_overlay_captcha_bank();
									window.location.href = "admin.php?page=captcha_bank";
								}, 3000);
							}
						}
						jQuery(document).ready(function ()
						{
							<?php
							if ( isset( $_REQUEST['live_preview'] ) && sanitize_text_field( wp_unslash( $_REQUEST['live_preview'] ) ) === 'true' ) {// @codingStandardsIgnoreLine.
								?>
								captcha_bank_fourth_step_settings();
								<?php
							}
							?>
							jQuery("#ux_ddl_recaptcha_key_type").val("<?php echo isset( $meta_data_array['recaptcha_key_type'] ) ? esc_attr( $meta_data_array['recaptcha_key_type'] ) : 'v2'; ?>");
							jQuery("#ux_ddl_recaptcha_data_badge").val("<?php echo isset( $meta_data_array['recaptcha_data_badge'] ) ? esc_attr( $meta_data_array['recaptcha_data_badge'] ) : 'bottomright'; ?>");
							jQuery("#ux_ddl_recaptcha_type").val("<?php echo isset( $meta_data_array['recaptcha_type'] ) ? esc_attr( $meta_data_array['recaptcha_type'] ) : 'image'; ?>");
							jQuery("#ux_ddl_recaptcha_theme_type").val("<?php echo isset( $meta_data_array['recaptcha_theme'] ) ? esc_attr( $meta_data_array['recaptcha_theme'] ) : 'light'; ?>");
							jQuery("#ux_ddl_recaptcha_size").val("<?php echo isset( $meta_data_array['recaptcha_size'] ) ? esc_attr( $meta_data_array['recaptcha_size'] ) : 'normal'; ?>");
							jQuery("#ux_ddl_recaptcha_language").val("<?php echo isset( $meta_data_array['recaptcha_language'] ) ? esc_attr( $meta_data_array['recaptcha_language'] ) : 'en'; ?>");
							jQuery("#ux_ddl_mathematical_operations").val("<?php echo isset( $meta_data_array['mathematical_operations'] ) ? esc_attr( $meta_data_array['mathematical_operations'] ) : 'arithmetic'; ?>");
							jQuery("#ux_ddl_captcha_type").val("<?php echo isset( $meta_data_array['captcha_type_text_logical'] ) ? esc_attr( $meta_data_array['captcha_type_text_logical'] ) : 'recaptcha'; ?>");
							jQuery("#ux_ddl_alphabets").val("<?php echo isset( $meta_data_array['captcha_type'] ) ? esc_attr( $meta_data_array['captcha_type'] ) : 'alphabets_and_digits'; ?>");
							jQuery("#ux_ddl_case").val("<?php echo isset( $meta_data_array['text_case'] ) ? esc_attr( $meta_data_array['text_case'] ) : 'upper_case'; ?>");
							jQuery("#ux_ddl_case_disable").val("<?php echo isset( $meta_data_array['case_sensitive'] ) ? esc_attr( $meta_data_array['case_sensitive'] ) : 'disable'; ?>");
							jQuery("#ux_ddl_background").val("<?php echo isset( $meta_data_array['captcha_background'] ) ? esc_attr( stripslashes( htmlspecialchars_decode( urldecode( $meta_data_array['captcha_background'] ) ) ) ) : 'bg1.gif'; ?>");
							jQuery("#ux_ddl_border_style_value").val("<?php echo isset( $meta_data_array['border_style'] ) ? esc_attr( $border_style[0] ) : '0'; ?>");
							jQuery("#ux_ddl_border_style").val("<?php echo isset( $meta_data_array['border_style'] ) ? esc_attr( $border_style[1] ) : '0'; ?>");
							jQuery("#ux_ddl_signature_style_value").val("<?php echo isset( $meta_data_array['signature_style'] ) ? esc_attr( $signature_style[0] ) : '0'; ?>");
							jQuery("#ux_ddl_signature_style").val("<?php echo isset( $meta_data_array['signature_style'] ) ? esc_attr( $signature_style[1] ) : '0'; ?>");
							jQuery("#ux_ddl_sign_font").val("<?php echo isset( $meta_data_array['signature_font'] ) ? stripslashes( htmlspecialchars_decode( urldecode( $meta_data_array['signature_font'] ) ) ) : 'Poppins';// WPCS: XSS ok. ?>");
							jQuery("#ux_ddl_text_style_value").val("<?php echo isset( $meta_data_array['text_style'] ) ? esc_attr( $text_style[0] ) : '0'; ?>");
							jQuery("#ux_ddl_text_style").val("<?php echo isset( $meta_data_array['text_style'] ) ? esc_attr( $text_style[1] ) : '0'; ?>");
							jQuery("#ux_ddl_text_font").val("<?php echo isset( $meta_data_array['text_font'] ) ? stripslashes( htmlspecialchars_decode( urldecode( $meta_data_array['text_font'] ) ) ) : 'Poppins';// WPCS: XSS ok. ?>");
							change_captcha_type_captcha_bank("<?php echo isset( $meta_data_array['captcha_type_text_logical'] ) ? esc_attr( $meta_data_array['captcha_type_text_logical'] ) : 'recaptcha'; ?>");
							change_mathematical_captcha_bank();
							show_hide_recaptcha_attribute_captcha_bank();
							show_captcha_type_shortcode_captcha_bank();
							check_conditions_display_settings_captcha_bank("login");
							check_conditions_display_settings_captcha_bank("register");
							check_conditions_display_settings_captcha_bank("lost_password");
							jQuery("#ux_txt_transperancy").keyup(function ()
							{
								if (jQuery(this).val() > 100)
								{
									var shortCutFunction = jQuery("#toastTypeGroup_error input:checked").val();
									toastr[shortCutFunction](<?php echo wp_json_encode( $cpb_number_of_digits ); ?>,<?php echo wp_json_encode( $cpb_error_message ); ?>);
									jQuery(this).val("50");
								}
							});
							jQuery("#ux_txt_character").keyup(function ()
							{
								if (jQuery(this).val() > 10)
								{
									var shortCutFunction = jQuery("#toastTypeGroup_error input:checked").val();
									toastr[shortCutFunction](<?php echo wp_json_encode( $cpb_number_of_captcha_character ); ?>,<?php echo wp_json_encode( $cpb_error_message ); ?>);
									jQuery(this).val("4");
								}
							});
						});
						if (typeof (captcha_bank_validate_settings) !== "function")
						{
							function captcha_bank_validate_settings()
							{
								jQuery("#ux_frm_captcha_type").validate
								({
									rules:
									{
										ux_txt_site_key:
										{
											required: true
										},
										ux_txt_secret_key:
										{
											required: true
										},
										ux_txt_character:
										{
											required: true,
											digits: true
										},
										ux_txt_width:
										{
											required: true,
											digits: true
										},
										ux_txt_height:
										{
											required: true,
											digits: true
										},
										ux_txt_border_style:
										{
											required: true
										},
										ux_txt_line:
										{
											required: true
										},
										ux_txt_color:
										{
											required: true
										},
										ux_txt_noise_level:
										{
											required: true,
											digits: true
										},
										ux_txt_noise_color:
										{
											required: true
										},
										ux_txt_transperancy:
										{
											required: true,
											digits: true
										},
										ux_txt_signature_text:
										{
											required: true
										},
										ux_txt_signature_style:
										{
											required: true
										},
										ux_txt_shadow_color:
										{
											required: true
										}
									},
									errorPlacement: function ()
									{
									},
									highlight: function (element)
									{
										jQuery(element).closest(".form-group").removeClass("has-success").addClass("has-error");
									},
									success: function (label, element)
									{
										var icon = jQuery(element).parent(".input-icon").children("i");
										jQuery(element).closest(".form-group").removeClass("has-error").addClass("has-success");
										icon.removeClass("fa-warning").addClass("fa-check");
									},
									submitHandler: function ()
									{
										if (jQuery("#ux_div_first_step").hasClass("first-step-helper"))
										{
											captcha_bank_second_step_settings();
										} else if (jQuery("#ux_div_second_step").hasClass("second-step-helper"))
										{
											captcha_bank_third_step_settings();
										} else if (jQuery("#ux_div_fourth_step").hasClass("fourth-step-helper")) {
											var chkBoxArray_settings      = [];

											var arithmetic_array = [];
											var count_arth = 0;
											var mathematical_captcha_type = jQuery("#ux_ddl_mathematical_operations").val();
											var count_rel = 0;
											jQuery("input:checkbox[name=ux_chk_captcha_form]").each(function ()
											{
												if (jQuery(this).val() !== "")
												{
													var isChecked = jQuery(this).attr("checked");
													if (isChecked === "checked")
													{
														chkBoxArray_settings.push(jQuery(this).val());
													} else
													{
														chkBoxArray_settings.push(0);
													}
												}
											});
											jQuery("input:checkbox[name=ux_chk_arithmetic_action]").each(function ()
											{
												if (jQuery(this).val() !== "")
												{
														var isChecked = jQuery(this).attr("checked");
														if (isChecked === "checked")
														{
															arithmetic_array.push(jQuery(this).val());
														} else
														{
															arithmetic_array.push(0);
															count_arth = count_arth + 1;
														}
													}
											});
										var count_arrange = 0;
										if ( jQuery("#ux_rdl_captcha_type_text_captcha").attr('checked') || jQuery("#ux_rdl_captcha_type_recaptcha").attr('checked') )
										{
											submit();
										} else
										{
											switch (mathematical_captcha_type)
											{
												case "arithmetic":
													if (count_arth === 4)
													{
														var shortCutFunction = jQuery("#toastTypeGroup_error input:checked").val();
														toastr[shortCutFunction](<?php echo wp_json_encode( $cpb_arithmetic_action ); ?>);
													} else
													{
														submit();
													}
												break;
												case "relational":
													premium_edition_notification_captcha_bank();
												break;
												case "arrange_order":
													premium_edition_notification_captcha_bank();
												break;
											}
										}
										function submit()
										{
											overlay_loading_captcha_bank();
											jQuery.post(ajaxurl,
											{
												data: base64_encode(jQuery("#ux_frm_captcha_type").serialize()),
												chkBoxArray_settings : JSON.stringify(chkBoxArray_settings),
												arithmetic: JSON.stringify(arithmetic_array),
												param: "captcha_type_module",
												action: "captcha_bank_action_library",
												_wp_nonce: "<?php echo esc_attr( $captcha_type_update ); ?>"
											},
											function ( data )
											{
												setTimeout(function ()
												{
												remove_overlay_captcha_bank();
												window.location.href = "admin.php?page=captcha_bank&live_preview=true";
												}, 3000);
											});
										}
									}
								}
							});
						}
					}
					var sidebar_load_interval = setInterval(load_sidebar_content_captcha_bank, 1000);
					setTimeout(function ()
					{
						clearInterval(sidebar_load_interval);
					}, 5000);
						<?php
					}
					break;
				case 'captcha_bank_whitelist_ip_addresses':
					?>
					jQuery("#ux_li_whitelist_ip_addresses").addClass("active");
					load_sidebar_content_captcha_bank();
					<?php
					if ( WHITELIST_CAPTCHA_BANK === '1' ) {
						?>
							var oTable = get_datatable_captcha_bank("#ux_tbl_manage_whitelist_ip_addresses");
							jQuery("#ux_chk_all_manage_ip_address").click(function ()
							{
								jQuery("input[type=checkbox]", oTable.fnGetFilteredNodes()).attr("checked", this.checked);
							});
							if (typeof (check_captcha_bank_ip_ranges_all) !== "function")
							{
								function check_captcha_bank_ip_ranges_all(control_id)
								{
									var ip_value = jQuery(control_id).val();
									var flag;
									if (ip_value !== "")
									{
										if (!jQuery(control_id).val().match(/^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/))
										{
											var shortCutFunction = jQuery("#toastTypeGroup_error input:checked").val();
											switch (jQuery(control_id).attr("id"))
											{
												case "ux_txt_whitelist_start_range" :
													toastr[shortCutFunction](<?php echo wp_json_encode( $cpb_valid_ip_range ); ?>,<?php echo wp_json_encode( $cpb_error_message ); ?>);
												break;
												case "ux_txt_whitelist_end_range" :
													toastr[shortCutFunction](<?php echo wp_json_encode( $cpb_valid_ip_range ); ?>,<?php echo wp_json_encode( $cpb_error_message ); ?>);
												break;
											}
											return flag = false;
										}
										return flag = true;
									}
								}
							}
							if (typeof (clear_value_ip_address_captcha_bank) !== "function")
							{
								function clear_value_ip_address_captcha_bank()
								{
									jQuery("#ux_txt_whitelist_single_address").val("");
									jQuery("#ux_txt_whitelist_start_range").val("");
									jQuery("#ux_txt_whitelist_end_range").val("");
									jQuery("#ux_txt_whitelist_multiple_ip").val("");
									jQuery("#ux_txtarea_comments").val("");
								}
							}
							jQuery("#ux_frm_whitelist_ip_addresses").validate
							({
								rules:
								{
									ux_txt_whitelist_single_address:
									{
										required: true
									},
									ux_txt_whitelist_start_range:
									{
										required: true
									},
									ux_txt_whitelist_end_range:
									{
										required: true
									}
								},
								errorPlacement: function ()
								{
								},
								highlight: function (element)
								{
									jQuery(element).closest(".form-group").removeClass("has-success").addClass("has-error");
								},
								success: function (label, element)
								{
									var icon = jQuery(element).parent(".input-icon").children("i");
									jQuery(element).closest(".form-group").removeClass("has-error").addClass("has-success");
									icon.removeClass("fa-warning").addClass("fa-check");
								},
								submitHandler: function ()
								{
									var ip_type = jQuery("#ux_ddl_whitelist_ip_type").val();
									switch( ip_type ){
										case'single':
											var ip_address_flag = check_ip_address_captcha_bank("#ux_txt_whitelist_single_address");
											if (ip_address_flag === true)
											{
												var ip_address = jQuery("#ux_txt_whitelist_single_address").val();
												jQuery.post(ajaxurl,
												{
													data: base64_encode(jQuery("#ux_frm_whitelist_ip_addresses").serialize()),
													ip_address: ip_address,
													param: "captcha_whitelist_ip_module",
													action: "captcha_bank_action_library",
													_wp_nonce: "<?php echo esc_attr( $captcha_bank_whitelist_ip_nonce ); ?>"
												},
												function (data)
												{
													if (parseInt(data) === 1)
													{
														var shortCutFunction = jQuery("#toastTypeGroup_error input:checked").val();
														toastr[shortCutFunction](<?php echo wp_json_encode( $cpb_ip_address_already_whitelisted ); ?>,<?php echo wp_json_encode( $cpb_notification ); ?>);
													}
													else if (parseInt(data) === 2)
													{
														var shortCutFunction = jQuery("#toastTypeGroup_error input:checked").val();
														toastr[shortCutFunction](<?php echo wp_json_encode( $cpb_block_own_ip_whitelist ); ?>,<?php echo wp_json_encode( $cpb_notification ); ?>);
													}
													else {
														overlay_loading_captcha_bank(<?php echo wp_json_encode( $cpb_whitelist_ip_address_added_msg ); ?>);
														setTimeout(function ()
														{
															remove_overlay_captcha_bank();
															window.location.href = "admin.php?page=captcha_bank_whitelist_ip_addresses";
														}, 3000);
													}
												});
											}
											break;
										case'range':
											var control_start_range = jQuery("#ux_txt_whitelist_start_range");
											var control_end_range = jQuery("#ux_txt_whitelist_end_range");
											if (check_captcha_bank_ip_ranges_all(control_start_range) && check_captcha_bank_ip_ranges_all(control_end_range))
											{
												if (ip2long(control_start_range.val()) < ip2long(control_end_range.val()))
												{
													var start_range = jQuery("#ux_txt_whitelist_start_range").val();
													var end_range = jQuery("#ux_txt_whitelist_end_range").val();
													jQuery.post(ajaxurl,
													{
														data: base64_encode(jQuery("#ux_frm_whitelist_ip_addresses").serialize()),
														start_range: start_range,
														end_range: end_range,
														param: "captcha_whitelist_ip_module",
														action: "captcha_bank_action_library",
														_wp_nonce: "<?php echo esc_attr( $captcha_bank_whitelist_ip_nonce ); ?>"
													},
													function ( data )
													{
														if (parseInt(data) === 1)
														{
															var shortCutFunction = jQuery("#toastTypeGroup_error input:checked").val();
															toastr[shortCutFunction](<?php echo wp_json_encode( $cpb_ip_address_already_whitelisted ); ?>,<?php echo wp_json_encode( $cpb_notification ); ?>);
														} else if (parseInt(data) === 2) {
															var shortCutFunction = jQuery("#toastTypeGroup_error input:checked").val();
															toastr[shortCutFunction](<?php echo wp_json_encode( $cpb_block_own_ip_whitelist_range ); ?>,<?php echo wp_json_encode( $cpb_notification ); ?>);
														} else {
															overlay_loading_captcha_bank(<?php echo wp_json_encode( $cpb_whitelist_ip_address_added_msg ); ?>);
															setTimeout(function ()
															{
																remove_overlay_captcha_bank();
																window.location.href = "admin.php?page=captcha_bank_whitelist_ip_addresses";
															}, 3000);
														}
													});
												}
												else
												{
													var shortCutFunction = jQuery("#toastTypeGroup_error input:checked").val();
													toastr[shortCutFunction](<?php echo wp_json_encode( $cpb_valid_ip_range ); ?>,<?php echo wp_json_encode( $cpb_error_message ); ?>);
												}
											}
											break;
										case'multiple':
											premium_edition_notification_captcha_bank();
											break;
									}
								}
							});
						<?php
					}
					break;
				case 'captcha_bank_message_settings':
					?>
					jQuery("#ux_li_general_settings").addClass("active");
					jQuery("#ux_li_message_settings").addClass("active");
					load_sidebar_content_captcha_bank();
					<?php
					if ( GENERAL_SETTINGS_CAPTCHA_BANK === '1' ) {
						?>
						jQuery("#ux_frm_error_message").validate
						({
							rules:
							{
								ux_txt_attempts:
								{
									required: true
								},
								ux_txt_invalid:
								{
									required: true
								},
								ux_txt_ip_address:
								{
									required: true
								},
								ux_txt_empty:
								{
									required: true
								},
								ux_txt_ip_range:
								{
									required: true
								},
								ux_txt_blocked_country:
								{
									required: true
								}
							},
							errorPlacement: function ()
							{
							},
							highlight: function (element)
							{
								jQuery(element).closest(".form-group").removeClass("has-success").addClass("has-error");
							},
							success: function (label, element)
							{
								var icon = jQuery(element).parent(".input-icon").children("i");
								jQuery(element).closest(".form-group").removeClass("has-error").addClass("has-success");
								icon.removeClass("fa-warning").addClass("fa-check");
							},
							submitHandler: function ()
							{
								premium_edition_notification_captcha_bank();
							}
						});
						<?php
					}
					break;
				case 'captcha_bank_notifications_setup':
					?>
					jQuery("#ux_li_general_settings").addClass("active");
					jQuery("#ux_li_notification_setup").addClass("active");
					load_sidebar_content_captcha_bank();
					<?php
					if ( GENERAL_SETTINGS_CAPTCHA_BANK === '1' ) {
						?>
						jQuery(document).ready(function ()
						{
							jQuery("#ux_ddl_fail").val("<?php echo isset( $meta_data_array['email_when_a_user_fails_login'] ) ? esc_attr( $meta_data_array['email_when_a_user_fails_login'] ) : ''; ?>");
							jQuery("#ux_ddl_success").val("<?php echo isset( $meta_data_array['email_when_a_user_success_login'] ) ? esc_attr( $meta_data_array['email_when_a_user_success_login'] ) : ''; ?>");
							jQuery("#ux_ddl_Ip_address").val("<?php echo isset( $meta_data_array['email_when_an_ip_address_is_blocked'] ) ? esc_attr( $meta_data_array['email_when_an_ip_address_is_blocked'] ) : ''; ?>");
							jQuery("#ux_ddl_address").val("<?php echo isset( $meta_data_array['email_when_an_ip_address_is_unblocked'] ) ? esc_attr( $meta_data_array['email_when_an_ip_address_is_unblocked'] ) : ''; ?>");
							jQuery("#ux_ddl_Ip").val("<?php echo isset( $meta_data_array['email_when_an_ip_range_is_blocked'] ) ? esc_attr( $meta_data_array['email_when_an_ip_range_is_blocked'] ) : ''; ?>");
							jQuery("#ux_ddl_range").val("<?php echo isset( $meta_data_array['email_when_an_ip_range_is_unblocked'] ) ? esc_attr( $meta_data_array['email_when_an_ip_range_is_unblocked'] ) : ''; ?>");
						});
						jQuery("#ux_frm_alert_setup").validate
						({
							submitHandler: function ()
							{
								premium_edition_notification_captcha_bank();
							}
						});
						<?php
					}
					break;
				case 'captcha_bank_blockage_settings':
					?>
					jQuery("#ux_li_security_settings").addClass("active");
					jQuery("#ux_li_blockage_settings").addClass("active");
					load_sidebar_content_captcha_bank();
					<?php
					if ( BLOCKAGE_SETTINGS_CAPTCHA_BANK === '1' ) {
						?>
						if (typeof (change_auto_ip_block_captcha_bank) !== "function")
						{
							function change_auto_ip_block_captcha_bank()
							{
								var change = jQuery("#ux_ddl_auto_ip").val();
								switch (change)
								{
									case "enable":
										jQuery("#ux_div_auto_ip").css("display", "block");
									break;
									case "disable":
										jQuery("#ux_div_auto_ip").css("display", "none");
									break;
								}
							}
						}
						jQuery(document).ready(function ()
						{
							jQuery("#ux_ddl_auto_ip").val("<?php echo isset( $blocking_options_unserialized_data['auto_ip_block'] ) ? esc_attr( $blocking_options_unserialized_data['auto_ip_block'] ) : ''; ?>");
							jQuery("#ux_ddl_blocked_for").val("<?php echo isset( $blocking_options_unserialized_data['block_for_time'] ) ? esc_attr( $blocking_options_unserialized_data['block_for_time'] ) : ''; ?>");
							change_auto_ip_block_captcha_bank();
						});
						jQuery("#ux_frm_blocking_options").validate
						({
							rules:
							{
								ux_txt_login:
								{
									required: true
								}
							},
							errorPlacement: function ()
							{
							},
							highlight: function (element)
							{
								jQuery(element).closest(".form-group").removeClass("has-success").addClass("has-error");
							},
							success: function (label, element)
							{
								var icon = jQuery(element).parent(".input-icon").children("i");
								jQuery(element).closest(".form-group").removeClass("has-error").addClass("has-success");
								icon.removeClass("fa-warning").addClass("fa-check");
							},
							submitHandler: function ()
							{
								overlay_loading_captcha_bank(<?php echo wp_json_encode( $cpb_setting_saved ); ?>);
								jQuery.post(ajaxurl,
								{
									data: base64_encode(jQuery("#ux_frm_blocking_options").serialize()),
									param: "captcha_blocking_options_module",
									action: "captcha_bank_action_library",
									_wp_nonce: "<?php echo esc_attr( $captcha_type_block ); ?>"
								},
								function ()
								{
									setTimeout(function ()
									{
										remove_overlay_captcha_bank();
										window.location.href = "admin.php?page=captcha_bank_blockage_settings";
									}, 3000);
								});
							}
						});
						<?php
					}
					break;
				case 'captcha_bank_block_unblock_ip_addresses':
					?>
					jQuery("#ux_li_block_unblock_ip_address").addClass("active");
					var sidebar_load_interval = setInterval(load_sidebar_content_captcha_bank, 1000);
					setTimeout(function ()
					{
					clearInterval(sidebar_load_interval);
					}, 5000);
					<?php
					if ( BLACKLIST_CAPTCHA_BANK === '1' ) {
						?>
						var oTable_manage_ip_address = get_datatable_captcha_bank("#ux_tbl_manage_ip_addresses");
						var oTable_manage_ip_range   = get_datatable_captcha_bank("#ux_tbl_manage_ip_range");
						jQuery("#ux_chk_all_manage_ip_address").click(function ()
						{
							jQuery("input[type=checkbox]", oTable_manage_ip_address.fnGetFilteredNodes()).attr("checked", this.checked);
						});
						jQuery("#ux_chk_all_manage_ip_range").click(function ()
						{
							jQuery("input[type=checkbox]", oTable_manage_ip_range.fnGetFilteredNodes()).attr("checked", this.checked);
						});
						if (typeof (clear_value_ip_range_captcha_bank) !== "function")
						{
							function clear_value_ip_range_captcha_bank()
							{
								jQuery("#ux_txt_start_ip_range").val("");
								jQuery("#ux_txt_end_range").val("");
								jQuery("#ux_txtarea_manage_ip_range").val("");
							}
						}
						if (typeof (clear_value_ip_address_captcha_bank) !== "function")
						{
							function clear_value_ip_address_captcha_bank()
							{
								jQuery("#ux_txt_address").val("");
								jQuery("#ux_txtarea_comments").val("");
							}
						}
						if (typeof (delete_ip_address_captcha_bank) !== "function")
						{
							function delete_ip_address_captcha_bank(captcha_id)
							{
								var confirm_delete = confirm(<?php echo wp_json_encode( $cpb_confirm ); ?>);
								if (confirm_delete === true)
								{
									overlay_loading_captcha_bank(<?php echo wp_json_encode( $cpb_delete_data ); ?>);
									jQuery.post(ajaxurl,
									{
										id: captcha_id,
										param: "captcha_delete_ip_address_module",
										action: "captcha_bank_action_library",
										_wp_nonce: "<?php echo esc_attr( $advance_security_manage_ip_address_delete ); ?>"
									},
									function ()
									{
										setTimeout(function ()
										{
										remove_overlay_captcha_bank();
										window.location.href = "admin.php?page=captcha_bank_block_unblock_ip_addresses";
										}, 3000);
									});
								}
							}
						}
						if (typeof (check_captcha_bank_ip_ranges_all) !== "function")
						{
							function check_captcha_bank_ip_ranges_all(control_id)
							{
								var ip_value = jQuery(control_id).val();
								var flag;
								if (ip_value !== "")
								{
									if (!jQuery(control_id).val().match(/^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/))
									{
										var shortCutFunction = jQuery("#toastTypeGroup_error input:checked").val();
										switch (jQuery(control_id).attr("id"))
										{
											case "ux_txt_start_ip_range" :
												toastr[shortCutFunction](<?php echo wp_json_encode( $cpb_valid_ip_range ); ?>,<?php echo wp_json_encode( $cpb_error_message ); ?>);
											break;
											case "ux_txt_end_range" :
												toastr[shortCutFunction](<?php echo wp_json_encode( $cpb_valid_ip_range ); ?>,<?php echo wp_json_encode( $cpb_error_message ); ?>);
											break;
										}
										return flag = false;
									}
									return flag = true;
								}
							}
						}
						if (typeof (delete_ip_range_captcha_bank) !== "function")
						{
							function delete_ip_range_captcha_bank(captcha_id)
							{
								var confirm_delete = confirm(<?php echo wp_json_encode( $cpb_confirm ); ?>);
								if (confirm_delete === true)
								{
									overlay_loading_captcha_bank(<?php echo wp_json_encode( $cpb_delete_data ); ?>);
									jQuery.post(ajaxurl,
									{
										id: captcha_id,
										param: "captcha_delete_ip_range_module",
										action: "captcha_bank_action_library",
										_wp_nonce: "<?php echo esc_attr( $advance_security_manage_ip_ranges_delete ); ?>"
									},
									function ()
									{
										setTimeout(function ()
										{
											remove_overlay_captcha_bank();
											window.location.href = "admin.php?page=captcha_bank_block_unblock_ip_addresses";
										}, 3000);
									});
								}
							}
						}
						jQuery("#ux_frm_view_blocked_ip_addresses").validate
						({
								rules:
								{
									ux_txt_cpb_start_date:
									{
										required: true
									},
									ux_txt_cpb_end_date:
									{
										required: true
									}
								},
								errorPlacement: function ()
								{
								},
								highlight: function (element)
								{
									jQuery(element).closest(".form-group").removeClass("has-success").addClass("has-error");
								},
								success: function (label, element)
								{
									var icon = jQuery(element).parent(".input-icon").children("i");
									jQuery(element).closest(".form-group").removeClass("has-error").addClass("has-success");
									icon.removeClass("fa-warning").addClass("fa-check");
								},
								submitHandler: function ()
								{
									premium_edition_notification_captcha_bank();
								}
							});
							jQuery("#ux_frm_manage_ip_addreses").validate
							({
								rules:
								{
									ux_txt_address:
									{
										required: true
									}
								},
								errorPlacement: function ()
								{
								},
								highlight: function (element)
								{
								jQuery(element).closest(".form-group").removeClass("has-success").addClass("has-error");
								},
								success: function (label, element)
								{
								var icon = jQuery(element).parent(".input-icon").children("i");
								jQuery(element).closest(".form-group").removeClass("has-error").addClass("has-success");
								icon.removeClass("fa-warning").addClass("fa-check");
								},
								submitHandler: function ()
								{
									var ip_address_flag = check_ip_address_captcha_bank("#ux_txt_address");
									if (ip_address_flag === true)
									{
										var ip_address = jQuery("#ux_txt_address").val();
										jQuery.post(ajaxurl,
										{
											data: base64_encode(jQuery("#ux_frm_manage_ip_addreses").serialize()),
											ip_address: ip_address,
											param: "captcha_manage_ip_address_module",
											action: "captcha_bank_action_library",
											_wp_nonce: "<?php echo esc_attr( $advance_security_manage_ip_address_nonce ); ?>"
										},
										function (data)
										{
											if (parseInt(data) === 1)
											{
												var shortCutFunction = jQuery("#toastTypeGroup_error input:checked").val();
												toastr[shortCutFunction](<?php echo wp_json_encode( $cpb_ip_address_already_blocked ); ?>,<?php echo wp_json_encode( $cpb_notification ); ?>);
											}
											else if (parseInt(data) === 2)
											{
												var shortCutFunction = jQuery("#toastTypeGroup_error input:checked").val();
												toastr[shortCutFunction](<?php echo wp_json_encode( $cpb_block_own_ip_address ); ?>,<?php echo wp_json_encode( $cpb_notification ); ?>);
											}
											else
											{
												overlay_loading_captcha_bank(<?php echo wp_json_encode( $cpb_ip_address_block ); ?>);
												setTimeout(function ()
												{
													remove_overlay_captcha_bank();
													window.location.href = "admin.php?page=captcha_bank_block_unblock_ip_addresses";
												}, 3000);
											}
										});
									}
								}
							});
							jQuery("#ux_frm_manage_ip_ranges").validate
							({
								rules:
								{
									ux_txt_start_ip_range:
									{
										required: true
									},
									ux_txt_end_range:
									{
										required: true
									}
								},
								errorPlacement: function ()
								{
								},
								highlight: function (element)
								{
									jQuery(element).closest(".form-group").removeClass("has-success").addClass("has-error");
								},
								success: function (label, element)
								{
									var icon = jQuery(element).parent(".input-icon").children("i");
									jQuery(element).closest(".form-group").removeClass("has-error").addClass("has-success");
									icon.removeClass("fa-warning").addClass("fa-check");
								},
								submitHandler: function ()
								{
									var control_start_range = jQuery("#ux_txt_start_ip_range");
									var control_end_range = jQuery("#ux_txt_end_range");
									if (check_captcha_bank_ip_ranges_all(control_start_range) && check_captcha_bank_ip_ranges_all(control_end_range))
									{
										if (ip2long(control_start_range.val()) < ip2long(control_end_range.val()))
										{
											var start_range = jQuery("#ux_txt_start_ip_range").val();
											var end_range = jQuery("#ux_txt_end_range").val();
											jQuery.post(ajaxurl,
											{
												data: base64_encode(jQuery("#ux_frm_manage_ip_ranges").serialize()),
												start_range: start_range,
												end_range: end_range,
												param: "captcha_manage_ip_ranges_module",
												action: "captcha_bank_action_library",
												_wp_nonce: "<?php echo esc_attr( $captcha_manage_ip_range ); ?>"
											},
											function (data)
											{
											if (parseInt(data) === 1)
											{
												var shortCutFunction = jQuery("#toastTypeGroup_error input:checked").val();
												toastr[shortCutFunction](<?php echo wp_json_encode( $cpb_ip_address_already_blocked ); ?>,<?php echo wp_json_encode( $cpb_notification ); ?>);
											}
											else if(parseInt(data) === 2)
											{
												var shortCutFunction = jQuery("#toastTypeGroup_error input:checked").val();
												toastr[shortCutFunction](<?php echo wp_json_encode( $cpb_block_own_ip_range ); ?>,<?php echo wp_json_encode( $cpb_notification ); ?>);
											}
											else
											{
												overlay_loading_captcha_bank(<?php echo wp_json_encode( $cpb_ip_address_block ); ?>);
												setTimeout(function ()
												{
													remove_overlay_captcha_bank();
													window.location.href = "admin.php?page=captcha_bank_block_unblock_ip_addresses";
												}, 3000);
											}
										});
									} else
									{
										var shortCutFunction = jQuery("#toastTypeGroup_error input:checked").val();
										toastr[shortCutFunction](<?php echo wp_json_encode( $cpb_valid_ip_range ); ?>,<?php echo wp_json_encode( $cpb_error_message ); ?>);
									}
								}
							}
						});
						jQuery("#ux_view_manage_ip_ranges").validate
						({
							rules:
							{
								ux_txt_cpb_start_date_ip_range:
								{
									required: true
								},
								ux_txt_cpb_end_date_ip_range:
								{
									required: true
								}
							},
							errorPlacement: function ()
							{
							},
							highlight: function (element)
							{
								jQuery(element).closest(".form-group").removeClass("has-success").addClass("has-error");
							},
							success: function (label, element)
							{
							var icon = jQuery(element).parent(".input-icon").children("i");
								jQuery(element).closest(".form-group").removeClass("has-error").addClass("has-success");
								icon.removeClass("fa-warning").addClass("fa-check");
							},
							submitHandler: function ()
							{
								premium_edition_notification_captcha_bank();
							}
						});
						<?php
					}
					break;

				case 'captcha_bank_block_unblock_countries':
					?>
					jQuery("#ux_li_block_unblock_countries").addClass("active");
					jQuery("#ux_li_security_settings").addClass("active");
					<?php
					if ( BLOCK_UNBLOCK_COUNTRIES_CAPTCHA_BANK === '1' ) {
						?>
						jQuery(document).ready(function ()
						{
						var available_countries = ["AF", "AX", "AL", "DZ", "AS", "AD", "AO", "AI", "AQ", "AG", "AR", "AM", "AW", "AU", "AT", "AZ", "BS", "BH", "BD", "BB", "BY", "BE", "BZ", "BJ", "BM", "BT", "BO", "BQ", "BA", "BW", "BV", "BR", "IO", "BN", "BG", "BF", "BI", "KH", "CM", "CA", "CV", "KY", "CF", "TD", "CL", "CN", "CX", "CC", "CO", "KM", "CG", "CD", "CK", "CR", "CI", "HR", "CU", "CW", "CY", "CZ", "DK", "DJ", "DM", "DO", "EC", "EG", "SV", "GQ", "ER", "EE", "ET", "FK", "FO", "FJ", "FI", "FR", "GF", "PF", "TF", "GA", "GM", "GE", "DE", "GH", "GI", "GR", "GL", "GD", "GP", "GU", "GT", "GG", "GN", "GW", "GY", "HT", "HM", "VA", "HN", "HK", "HU", "IS", "IN", "ID", "IR", "IQ", "IE", "IM", "IL", "IT", "JM", "JP", "JE", "JO", "KZ", "KE", "KI", "KP", "KR", "KW", "KG", "LA", "LV", "LB", "LS", "LR", "LY", "LI", "LT", "LU", "MO", "MK", "MG", "MW", "MY", "MV", "ML", "MT", "MH", "MQ", "MR", "MU", "YT", "MX", "FM", "MD", "MC", "MN", "ME", "MS", "MA", "MZ", "MM", "NA", "NR", "NP", "NL", "NC", "NZ", "NI", "NE", "NG", "NU", "NF", "MP", "NO", "OM", "PK", "PW", "PS", "PA", "PG", "PY", "PE", "PH", "PN", "PL", "PT", "PR", "QA", "RE", "RO", "RU", "RW", "BL", "SH", "KN", "LC", "MF", "PM", "VC", "WS", "SM", "ST", "SA", "SN", "RS", "SC", "SL", "SG", "SX", "SK", "SI", "SB", "SO", "ZA", "GS", "SS", "ES", "LK", "SD", "SR", "SJ", "SZ", "SE", "CH", "SY", "TW", "TJ", "TZ", "TH", "TL", "TG", "TK", "TO", "TT", "TN", "TR", "TM", "TC", "TV", "UG", "UA", "AE", "GB", "US", "UM", "UY", "UZ", "VU", "VE", "VN", "VG", "VI", "WF", "EH", "YE", "ZM", "ZW"];
						var all_available_countries = [];
						var selected_countries = "<?php echo isset( $country_data_array['country_block_data'] ) ? esc_attr( $country_data_array['country_block_data'] ) : ''; ?>";
						var strings = selected_countries.split(",");
						all_available_countries = available_countries.filter(function (val)
						{
							return selected_countries.indexOf(val) === -1;
						});
						var option = "";
						var option1 = "";
						if (all_available_countries.length > 0)
						{
							for (var flag = 0; flag < all_available_countries.length; flag++)
							{
								if (all_available_countries[flag] !== "")
								{
								option += '<option value="' + all_available_countries[flag] + '"> ' + jQuery("#ux_ddl_available_country_duplicate option[value=" + all_available_countries[flag] + "]").text() + '</option>';
								}
							}
							jQuery("#ux_ddl_available_country").append(option);
							sort_function_captcha_bank("ux_ddl_selected_country");
						}
						var sel_coun = selected_countries.split(",");
						if (sel_coun.length > 0)
						{
							for (var flag = 0; flag < sel_coun.length; flag++)
							{
								if (sel_coun[flag] !== "")
								{
									option1 += '<option value="' + sel_coun[flag] + '"> ' + jQuery("#ux_ddl_available_country_duplicate option[value=" + sel_coun[flag] + "]").text() + '</option>';
								}
							}
							jQuery("#ux_ddl_selected_country").append(option1);
							sort_function_captcha_bank("ux_ddl_available_country");
						}
						});
						if (typeof (add_country_captcha_bank) !== "function")
						{
							function add_country_captcha_bank()
							{
								var selected_countries = [];
								jQuery.each(jQuery("#ux_ddl_available_country option:selected"), function ()
								{
									selected_countries.push(jQuery(this));
									jQuery(this).remove();
								});
								var value = "";
								for (var flag = 0; flag < selected_countries.length; flag++)
								{
									value += '<option value="' + jQuery(selected_countries[flag]).val() + '">' + jQuery(selected_countries[flag]).text() + '</option>';
								}
								jQuery("#ux_ddl_selected_country").append(value);
								sort_function_captcha_bank("ux_ddl_selected_country");
							}
						}
						if (typeof (remove_country_captcha_bank) !== "function")
						{
							function remove_country_captcha_bank()
							{
								var selected_countries = [];
								jQuery.each(jQuery("#ux_ddl_selected_country option:selected"), function ()
								{
									selected_countries.push(jQuery(this));
									jQuery(this).remove();
								});
								var value = "";
								for (var flag = 0; flag < selected_countries.length; flag++)
								{
									value += '<option value="' + jQuery(selected_countries[flag]).val() + '">' + jQuery(selected_countries[flag]).text() + '</option>';
								}
								jQuery("#ux_ddl_available_country").append(value);
								sort_function_captcha_bank("ux_ddl_available_country");
							}
						}
						jQuery("#ux_frm_country_blocks").validate
						({
							submitHandler: function ()
							{
								premium_edition_notification_captcha_bank();
							}
						});
						<?php
					}
					break;
				case 'captcha_bank_email_templates':
					?>
					jQuery("#ux_li_general_settings").addClass("active");
					jQuery("#ux_li_email_templates").addClass("active");
					load_sidebar_content_captcha_bank();
					<?php
					if ( GENERAL_SETTINGS_CAPTCHA_BANK === '1' ) {
						?>
						if (typeof (email_template_type_captcha_bank) !== "function")
						{
							function email_template_type_captcha_bank()
							{
								jQuery.post(ajaxurl,
								{
									data: jQuery("#ux_ddl_user_success").val(),
									param: "captcha_type_email_templates_module",
									action: "captcha_bank_action_library",
									_wp_nonce: "<?php echo esc_attr( $captcha_type_email_templates ); ?>"
								},
								function (data)
								{
									jQuery("#ux_email_template_meta_id").val(jQuery.parseJSON(data)[0]["meta_id"]);
									jQuery("#ux_txt_send_to").val(jQuery.parseJSON(data)[0]["send_to"]);
									jQuery("#ux_txt_cc").val(jQuery.parseJSON(data)[0]["email_cc"]);
									jQuery("#ux_txt_bcc").val(jQuery.parseJSON(data)[0]["email_bcc"]);
									jQuery("#ux_txt_subject").val(jQuery.parseJSON(data)[0]["email_subject"]);
									if (window.CKEDITOR)
									{
										CKEDITOR.instances["ux_heading_content"].setData(jQuery.parseJSON(data)[0]["email_message"]);
									} else if (jQuery("#wp-ux_heading_content-wrap").hasClass("tmce-active"))
									{
										tinyMCE.get("ux_heading_content").setContent(jQuery.parseJSON(data)[0]["email_message"]);
									} else
									{
										jQuery("#ux_heading_content").val(jQuery.parseJSON(data)[0]["email_message"]);
									}
								});
							}
						}
						jQuery(document).ready(function ()
						{
						if (window.CKEDITOR)
						{
							CKEDITOR.replace("ux_heading_content");
						}
						email_template_type_captcha_bank();
						});
						jQuery("#ux_frm_email_templates").validate
						({
							submitHandler: function ()
							{
								premium_edition_notification_captcha_bank();
							}
						});
						<?php
					}
					break;
				case 'captcha_bank_other_settings':
					?>
					jQuery("#ux_li_other_settings").addClass("active");
					load_sidebar_content_captcha_bank();
					<?php
					if ( OTHER_SETTINGS_CAPTCHA_BANK === '1' ) {
						?>
						jQuery(document).ready(function ()
						{
							jQuery("#ux_ddl_remove_tables").val("<?php echo isset( $meta_data_array['remove_tables_at_uninstall'] ) ? esc_attr( $meta_data_array['remove_tables_at_uninstall'] ) : ''; ?>");
							jQuery("#ux_ddl_ip_address_fetching_method").val("<?php echo isset( $meta_data_array['ip_address_fetching_method'] ) ? esc_attr( $meta_data_array['ip_address_fetching_method'] ) : ''; ?>");
						});
						jQuery("#ux_frm_other_settings").validate
						({
							submitHandler: function ()
							{
								overlay_loading_captcha_bank(<?php echo wp_json_encode( $cpb_setting_saved ); ?>);
								jQuery.post(ajaxurl,
								{
									data: base64_encode(jQuery("#ux_frm_other_settings").serialize()),
									param: "captcha_bank_other_settings_module",
									action: "captcha_bank_action_library",
									_wp_nonce: "<?php echo esc_attr( $captcha_bank_other_settings ); ?>"
								},
								function ()
								{
									setTimeout(function ()
									{
										remove_overlay_captcha_bank();
										window.location.href = "admin.php?page=captcha_bank_other_settings";
									}, 3000);
								});
							}
						});
						<?php
					}
					break;
				case 'captcha_bank_roles_capabilities':
					?>
					jQuery("#ux_li_general_settings").addClass("active");
					jQuery("#ux_li_roles_capabilities").addClass("active");
					var sidebar_load_interval = setInterval(load_sidebar_content_captcha_bank, 1000);
					setTimeout(function ()
					{
					clearInterval(sidebar_load_interval);
					}, 5000);
					<?php
					if ( GENERAL_SETTINGS_CAPTCHA_BANK === '1' ) {
						?>
						if (typeof (show_roles_capabilities_captcha_bank) !== "function")
						{
							function show_roles_capabilities_captcha_bank(id, div_id)
							{
								if (jQuery(id).prop("checked"))
								{
									jQuery("#" + div_id).css("display", "block");
								} else
								{
									jQuery("#" + div_id).css("display", "none");
								}
							}
						}
						if (typeof (full_control_function_captcha_bank) !== "function")
						{
							function full_control_function_captcha_bank(id, div_id)
							{
								var checkbox_id = jQuery(id).prop("checked");
								jQuery("#" + div_id + " input[type=checkbox]").each(function ()
								{
									if (checkbox_id)
									{
										jQuery(this).attr("checked", "checked");
										if (jQuery(id).attr("id") !== jQuery(this).attr("id"))
										{
											jQuery(this).attr("disabled", "disabled");
										}
									} else
									{
										if (jQuery(id).attr("id") !== jQuery(this).attr("id"))
										{
											jQuery(this).removeAttr("disabled");
											jQuery("#ux_chk_other_capabilities_manage_options").attr("disabled", "disabled");
											jQuery("#ux_chk_other_capabilities_read").attr("disabled", "disabled");
										}
									}
								});
							}
						}
						jQuery(document).ready(function ()
						{
							jQuery("#ux_ddl_settings").val("<?php echo isset( $details_roles_capabilities['show_captcha_bank_top_bar_menu'] ) ? esc_attr( $details_roles_capabilities['show_captcha_bank_top_bar_menu'] ) : 'enable'; ?>");
							show_roles_capabilities_captcha_bank("#ux_chk_author", "ux_div_author_roles");
							full_control_function_captcha_bank("#ux_chk_full_control_author", "ux_div_author_roles");
							show_roles_capabilities_captcha_bank("#ux_chk_editor", "ux_div_editor_roles");
							full_control_function_captcha_bank("#ux_chk_full_control_editor", "ux_div_editor_roles");
							show_roles_capabilities_captcha_bank("#ux_chk_contributor", "ux_div_contributor_roles");
							full_control_function_captcha_bank("#ux_chk_full_control_contributor", "ux_div_contributor_roles");
							show_roles_capabilities_captcha_bank("#ux_chk_subscriber", "ux_div_subscriber_roles");
							full_control_function_captcha_bank("#ux_chk_full_control_subscriber", "ux_div_subscriber_roles");
							show_roles_capabilities_captcha_bank("#ux_chk_other", "ux_div_other_roles");
							full_control_function_captcha_bank("#ux_chk_full_control_others", "ux_div_other_roles");
							full_control_function_captcha_bank("#ux_chk_full_control_other_roles", "ux_div_other_roles_capabilities");
						});
						jQuery("#ux_frm_roles_and_capabilities").validate
						({
							submitHandler: function ()
							{
								premium_edition_notification_captcha_bank();
							}
						});
						<?php
					}
					break;
				case 'captcha_bank_feature_requests':
					?>
					jQuery("#ux_li_feature_requests").addClass("active");
					load_sidebar_content_captcha_bank();
					var features_array = [];
					var url = "<?php echo esc_attr( TECH_BANKER_URL ) . '/feedbacks.php'; ?>";
					var domain_url = "<?php echo esc_attr( site_url() ); ?>";
					jQuery("#ux_frm_feature_requests").validate
					({
						rules:
						{
							ux_txt_your_name:
							{
								required: true
							},
							ux_txt_email_address:
							{
								required: true,
								email: true
							},
							ux_txtarea_feature_request:
							{
								required: true
							}
						},
						errorPlacement: function ()
						{
						},
						highlight: function (element)
						{
							jQuery(element).closest(".form-group").removeClass("has-success").addClass("has-error");
						},
						success: function (label, element)
						{
							var icon = jQuery(element).parent(".input-icon").children("i");
							jQuery(element).closest(".form-group").removeClass("has-error").addClass("has-success");
							icon.removeClass("fa-warning").addClass("fa-check");
						},
						submitHandler: function ()
						{
							features_array.push(jQuery("#ux_txt_your_name").val(), jQuery("#ux_txt_email_address").val(), domain_url, jQuery("#ux_txtarea_feature_request").val());
							overlay_loading_captcha_bank(<?php echo wp_json_encode( $cpb_feature_request ); ?>);
							jQuery.post(url,
							{
							data: JSON.stringify(features_array),
							param: "captcha_bank_feature_requests"
							},
							function ()
							{
							setTimeout(function ()
							{
							remove_overlay_captcha_bank();
							window.location.href = "admin.php?page=captcha_bank_feature_requests";
							},
							3000);
							});
						}
					});
					<?php
					break;
				case 'captcha_bank_system_information':
					?>
					jQuery("#ux_li_system_information").addClass("active");
					load_sidebar_content_captcha_bank();
					<?php
					if ( SYSTEM_INFORMATION_CAPTCHA_BANK === '1' ) {
						?>
						jQuery.getSystemReport = function (strDefault, stringCount, string, location)
						{
						var o = strDefault.toString();
						if (!string)
						{
							string = "0";
						}
						while (o.length < stringCount)
						{
							if (location === "undefined")
							{
								o = string + o;
							} else
							{
								o = o + string;
							}
						}
						return o;
						};
						jQuery(".system-report").click(function ()
						{
						var report = "";
						jQuery(".custom-form-body").each(function ()
						{
							jQuery("h3.form-section", jQuery(this)).each(function ()
							{
								report = report + "\n### " + jQuery.trim(jQuery(this).text()) + " ###\n\n";
							});
							jQuery("tbody > tr", jQuery(this)).each(function ()
							{
								var the_name = jQuery.getSystemReport(jQuery.trim(jQuery(this).find("strong").text()), 25, " ");
								var the_value = jQuery.trim(jQuery(this).find("span").text());
								var value_array = the_value.split(", ");
								if (value_array.length > 1)
								{
								var temp_line = "";
								jQuery.each(value_array, function (key, line)
								{
									var tab = (key === 0) ? 0 : 25;
									temp_line = temp_line + jQuery.getSystemReport("", tab, " ", "f") + line + "\n";
								});
								the_value = temp_line;
								}
								report = report + "" + the_name + the_value + "\n";
							});
						});
						try
						{
							jQuery("#ux_system_information").slideDown();
							jQuery("#ux_system_information textarea").val(report).focus().select();
							return false;
						} catch (e)
						{
							console.log(e);
						}
						return false;
						});
						jQuery("#ux_btn_system_information").click(function ()
						{
						if (jQuery("#ux_btn_system_information").text() === "Close System Information!")
						{
							jQuery("#ux_system_information").slideUp();
							jQuery("#ux_btn_system_information").html("Get System Information!");
						} else
						{
							jQuery("#ux_btn_system_information").html("Close System Information!");
							jQuery("#ux_btn_system_information").removeClass("system-information");
							jQuery("#ux_btn_system_information").addClass("close-information");
						}
						setTimeout(function ()
						{
							load_sidebar_content_captcha_bank();
						}, 1000);
						});
						<?php
					}
					break;
			}
		}
		?>
		</script>
		<?php
	}
}
