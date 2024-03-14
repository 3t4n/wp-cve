<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
class Stylish_Cost_Calculator_Settings {

    protected $page;
    protected $isSCCFreeVersion;
    protected $privKeyPlaceHolder;
    protected $pubKeyPlaceHolder;
    private $scc_icons;
    public function __construct() {
        wp_localize_script(
            'scc-backend',
            'SCC_Settings',
            [
                'security' => wp_create_nonce( 'scostc-admin-settings-referer' ),
            ]
        );
        wp_localize_script( 'scc-backend', 'pageGlobalSettings', [ 'nonce' => wp_create_nonce( 'global-settings-page' ) ] );
        wp_enqueue_style( 'scc-material', 'https://fonts.googleapis.com/icon?family=Material+Icons|Material+Icons+Outlined' );
        $this->isSCCFreeVersion     = defined( 'STYLISH_COST_CALCULATOR_VERSION' );
        $this->scc_icons            = require SCC_DIR . '/assets/scc_icons/icon_rsrc.php';
        $stripe_opts                = wp_parse_args(
            get_option( 'df_scc_stripe_keys' ),
            [
                'privKey' => null,
                'pubKey'  => null,
            ]
        );
        $this->privKeyPlaceHolder = $stripe_opts['privKey'] ? substr( $stripe_opts['privKey'], 0, - ( strlen( $stripe_opts['privKey'] ) * .7 ) ) . '*****' . substr( $stripe_opts['privKey'], - ( strlen( $stripe_opts['privKey'] ) * .2 ) ) : 'Please enter Stripe API Private Key';
        $this->pubKeyPlaceHolder  = $stripe_opts['pubKey'] ? substr( $stripe_opts['pubKey'], 0, - ( strlen( $stripe_opts['pubKey'] ) * .7 ) ) . '*****' . substr( $stripe_opts['pubKey'], - ( strlen( $stripe_opts['pubKey'] ) * .2 ) ) : 'Please enter Stripe API Public Key';
        $this->pageTwo();
        $this->pageScript();
    }

    private function pageTwo() {
        $currencyFields              = [
            'name'          => 'Currency Settings',
            'helpdesk_link' => "<a class='material-icons-outlined text-decoration-none' target='_blank' href=\"https://designful.freshdesk.com/a/solutions/articles/48001143319\">" . '<span class="scc-icn-wrapper" style="margin-left:5px;">' . scc_get_kses_extended_ruleset( $this->scc_icons['help-circle'] ) . '</span> </a>',
            'fields'        => [
                'currency',
                'currency_num_format',
                'currency_conversion_type',
                'currency_for_autoconv',
            ],
            'icon'			       => 'attach_money',
            'notes'         => 'Select a currency: will automatically show the selected currency to convert
            Auto detect: will use the users current location to automatically detect their currency.',
            'action_btn'    => 'Save',
            'action_cb'     => 'saveCurencySettings(this)',
        ];
        $stripeFields                = [
            'name'          => 'Stripe Settings',
            'helpdesk_link' => "<a class='material-icons-outlined text-decoration-none' target='_blank' href=\"https://designful.freshdesk.com/a/solutions/articles/48001167920\">" . '<span class="scc-icn-wrapper" style="margin-left:5px;">' . scc_get_kses_extended_ruleset( $this->scc_icons['help-circle'] ) . '</span> </a>',
            'fields'        => [
                'stripe_secret_key',
                'stripe_public_key',
            ],
            'icon'			       => 'payment',
            'action_btn'    => 'Save',
            'action_cb'     => 'updateStripeKey(this)',
        ];
        $pdfFooterField              = [
            'name'       => 'Detailed List/PDF Settings',
            'fields'     => [
                'pdf_footer_notes',
            ],
            'icon'			    => 'description',
            'action_btn' => 'Save',
            'action_cb'  => 'saveSCCEmailSetting(this)',
        ];
        $detailListBannerLogo        = [
            'name'   => 'Header: Detailed List & PDF',
            'fields' => [
                'banner_and_logo',
            ],
            'icon'			=> 'description',
        ];
        $email_quote_settings_fields = [
            'name'          => 'Email Quote Settings',
            'helpdesk_link' => "<a class='scc-material-icons-outlined text-decoration-none' target='_blank' href=\"https://designful.freshdesk.com/support/solutions/articles/48001142348-email-quote-form-a-complete-guide\">" . '<span class="scc-icn-wrapper" style="margin-left:5px;">' . scc_get_kses_extended_ruleset( $this->scc_icons['help-circle'] ) . '</span>' . '</a>',
            'fields'        => [
                'email_quote_settings_form',
            ],
            'icon'			       => 'email',
            'action_btn'    => 'Save',
            'action_cb'     => 'saveSCCEmailSetting(this)',
            'hasShortcodes' => true,
        ];
        $pdf_settings_fields         = [
            'name'          => 'PDF Settings',
            'helpdesk_link' => "<a class='scc-material-icons-outlined text-decoration-none' target='_blank' href=\"https://designful.freshdesk.com/a/solutions/articles/48001178732\">" . '<span class="scc-icn-wrapper" style="margin-left:5px;">' . scc_get_kses_extended_ruleset( $this->scc_icons['help-circle'] ) . '</span>' . '</a>',
            'fields'        => [
                'pdf_settings_form',
            ],
            'icon'			    => 'picture_as_pdf',
            'action_btn' => 'Save',
            'action_cb'  => 'sccPDFSettings(this)',
        ];
        $recaptcha_settings_fields   = [
            'name'          => 'reCaptcha Settings',
            'helpdesk_link' => "<a class='scc-material-icons-outlined text-decoration-none' target='_blank' href=\"https://designful.freshdesk.com/a/solutions/articles/48001153881\">" . '<span class="scc-icn-wrapper" style="margin-left:5px;">' . scc_get_kses_extended_ruleset( $this->scc_icons['help-circle'] ) . '</span>' . '</a>',
            'fields'        => [
                'recaptcha_settings_form',
            ],
            'icon'			    => 'verified_user',
            'action_btn' => 'Save',
            'action_cb'  => 'sccSaveRecaptchaKeys(this)',
        ];
		$integration_settings_fields   = [
            'name'          => 'Integration Settings',
            'fields'        => [
                'integration_settings_form',
            ],
            'icon'       => 'code',
        ];
        $google_maps_settings_fields   = [
            'name'          => 'Google Maps Settings',
            'helpdesk_link' => "<a class='scc-material-icons-outlined text-decoration-none' target='_blank' href=\"https://designful.freshdesk.com/a/solutions/articles/48001244300\">" . '<span class="scc-icn-wrapper" style="margin-left:5px;">' . scc_get_kses_extended_ruleset( $this->scc_icons['help-circle'] ) . '</span>' . '</a>',
            'fields'        => [
                'google_maps_settings_form',
            ],
            'icon'			    => 'place',
            'action_btn' => 'Save',
            'action_cb'  => 'sccSaveGoogleMapsAPI(this)',
        ];
        $twilio_settings_fields      = [
            'name'          => 'SMS Quotes (Twilio)',
            'helpdesk_link' => "<a class='scc-material-icons-outlined text-decoration-none' target='_blank' href=\"https://designful.freshdesk.com/support/solutions/articles/48001238157-sms-quotes-a-complete-guide\">" . '<span class="scc-icn-wrapper" style="margin-left:5px;">' . scc_get_kses_extended_ruleset( $this->scc_icons['help-circle'] ) . '</span>' . '</a>',
            'fields'        => [
                'sms_settings_form',
            ],
            'icon'			    => 'sms',
            'action_btn' => 'Save',
            'action_cb'  => 'sccTextMessageSettings(this)',
        ];
        $restore_calc_fields         = [
            'name'       => 'Restore/Import Calculator Form',
            'fields'     => [
                'restore_calc_form',
            ],
            'icon'			    => 'restore',
            'action_btn' => 'Restore',
            'action_cb'  => 'sscUploadSccBackup()',
        ];
        ?>
		<div class="container-fluid" id="scc-global-settings">
		<div class="scc_title_bar">Global Settings</div>
			<button type="button" class="btn btn-primary btn-lg" style="margin-top:15px;"><a class="lead text-decoration-none" id="coupon-page" style="color:#fff" href="<?php echo get_admin_url() . 'admin.php?page=scc-coupons-management'; ?>">Manage Coupons</a></button>
			<div class="accordion mt-2" id="settings-page-accordion">
				<?php
                    $global_settings_sections_order = [
                        $currencyFields,
                        $email_quote_settings_fields,
                        $twilio_settings_fields,
                        $detailListBannerLogo,
                        $pdfFooterField,
                        $pdf_settings_fields,
                        $stripeFields,
                        $recaptcha_settings_fields,
						$integration_settings_fields,
                        $google_maps_settings_fields,
                        $restore_calc_fields,
                    ];

        foreach ( $global_settings_sections_order as $key => $settings ) {
            $this->output_card( $settings );
        }
        ?>
			</div>
		</div>
		<?php
    }

    private function pageScript() {
        ?>
		<style>
			.card.mb-3.p-4 .a-over {
				background-color: orange;
				border-radius: 5px;
				color: #FFF;
				padding: 5px 15px 5px 15px;
				text-transform: uppercase;
			}
			.accordion-collapse.collapse.show .accordion-body {
				padding: 4rem 1.25rem;
			}
		</style>
		<script type="text/javascript">
			const df_scc_resources = {
				dropdownTumbnailDefaultImage: "<?php echo esc_url( SCC_ASSETS_URL . '/images/image.png' ); ?>",
				assetsPath: "<?php echo esc_url( SCC_ASSETS_URL ); ?>"
			}
			jQuery(document).ready(function($) {
				// Upload button click
				$('.scc-media-upload-button').click(function(event) {
					event.preventDefault();
					// var formField = $(this).parents('.scc-form-field');
					sourceBtn = $(event.target);
					buttonWrapper = sourceBtn.closest('.col-sm-6');

					if (typeof(mediaUploader) !== 'undefined') {
						mediaUploader.open();
						return;
					}

					mediaUploader = wp.media.frames.file_frame = wp.media({
						title: 'Choose Image',
						button: {
							text: 'Choose Image'
						},
						multiple: false
					});

					mediaUploader.on("select", onPDFLogoMediaImageSelect);

					mediaUploader.open();
				});

				// Remove button click
				$('.scc-media-uploader-remove').click(function(event) {
					var sourceBtn = $(event.target);
					var buttonWrapper = sourceBtn.closest('.col-sm-6');

					var name = buttonWrapper.find('.scc-media-upload-field').attr('name');

					setUploadedImage(name, '', function(response) {
						var image = buttonWrapper.find('.scc-media-uploader-image');
						image.hide();
						image.find('img').remove();
						// buttons.show();
						buttonWrapper.find('.text-center').show();
					});
				});
				// Handling decrease of height of the accordion header when expanded
				$('[data-bs-parent="#settings-page-accordion"]').on('show.bs.collapse', ({target: accordionBody}) => {
					$(accordionBody).parent().find('.accordion-button').removeClass('py-4');
				})
				// Handling revert of height of the accordion header when collapsed
				$('[data-bs-parent="#settings-page-accordion"]').on('hide.bs.collapse', ({target: accordionBody}) => {
					$(accordionBody).parent().find('.accordion-button').addClass('py-4');
				})
			})
			function handleEditBox() {
				window.setTimeout(function () {
					handleEditBox();
				}, 300)
			}
			handleEditBox();
			document.addEventListener('DOMContentLoaded', (event) => {
				new TomSelect("#currency_code",{
				allowEmptyOption: false,
				create: false
			});
			})
			function toggleShortCodes() {
				if (jQuery('#email_form_short_codes_section').css('display') === 'none') {
					jQuery('#email_form_short_codes_section').css('display', 'block')
				} else {
					jQuery('#email_form_short_codes_section').css('display', 'none')
				}
			}

			/**
			 * *Handles the old backup
			 */
			function showConfirmOld(next) {
				Swal.fire({
					title: 'Are you sure?',
					text: "Are you sure you want to restore old backup",
					icon: 'warning',
					showCancelButton: true,
					confirmButtonColor: '#3085d6',
					cancelButtonColor: '#d33',
					confirmButtonText: 'Yes, continue!'
				}).then((result) => {
					if (result.isConfirmed) {
						console.log("backup old")
						next()
					}
				})
			}

			/**
			 * *Handles backup restore from json file
			 */
			function sscUploadSccBackup() {
				// restore2()
				// return
				showLoadingChanges()
				// jQuery('#scc_backup_message').html('Wait... Analyzing your file and uploading it...');
				// jQuery('#scc_backup_message').css('color', 'orange')
				var files = jQuery('#backup_scc_file')[0].files[0];
				if (!files) {
					console.log("no hay archivo ")
					return
				}
				var reader = new FileReader()
				reader.readAsText(files, "UTF-8")
				reader.onload = function(ee) {
					let json = JSON.parse(ee.target.result)
					var o = ("scc_form" in json)
					if (o) {
						showConfirmOld(restore2)
					} else {
						const fdata = new FormData()
						fdata.append('file', files)
						fdata.append('action', "sccRestoreBackup")
						fdata.append('nonce', pageGlobalSettings.nonce)
						jQuery.ajax({
							type: 'POST',
							url: ajaxurl,
							data: fdata,
							contentType: false,
							processData: false,
							success: function(data) {
								if (data.passed) {
									showSweet(true, 'SCC restored successfully.')
									// jQuery('#scc_backup_message').html('SCC restored successfully.');
									// jQuery('#scc_backup_message').css('color', 'green')
								} else {
									showSweet(false, data.msj)
									// jQuery('#scc_backup_message').html(data.msj);
									// jQuery('#scc_backup_message').css('color', 'red')
								}

							}
						})
					}
				}
				// console.log(files)

			}

			/**
			 * Handle upload settings functionalty
			 */
			function setUploadedImage(name, url, callback) {
				var data = {
					action: "pdf_logo_settings_ajax",
					security: SCC_Settings.security,
					method: "pdf_logo_save_uploaded_image",
					data: {
						name: name,
						value: url,
					}
				};

				jQuery.post(ajaxurl, data, callback);
			}
			/**
			 * On media image select
			 */
			function onPDFLogoMediaImageSelect() {
				var attachment = mediaUploader.state().get('selection').first().toJSON();
				var field = buttonWrapper.find('.scc-media-upload-field');
				field.val(attachment.url);
				// spinner.addClass('is-active');

				setUploadedImage(field.attr('name'), attachment.url, function(response) {
					// spinner.removeClass('is-active');
					var html = '<img src="' + response.data.url + '" />';
					var image = buttonWrapper.find('div.scc-media-uploader-image');
					image.append(html);
					image.show();
					field.parent().hide();
				});
			}

			function saveCurencySettings($btn) {
				$btn = jQuery($btn);
				var currency = jQuery("#currency_code").val()
				var style = jQuery("#currency-style").val()
				var mode = jQuery("#scc_currency_coversion_mode").val()
				var manual = jQuery("#scc_currency_coversion_manual_selection").val()
				var originalText = $btn.text()
				if(currency == null || currency == ''){
					currency = 'USD';
				}
				jQuery.ajax({
					url: wp.ajax.settings.url,
					data: {
						action: 'sccGlobalSettings',
						currency: currency,
						format: style,
						mode: mode,
						manual_select: manual,
						nonce: pageGlobalSettings.nonce
					},
					beforeSend: function() {
						$btn.text('saving...');
					},
					success: function(data) {
						var datajson = JSON.parse(data)
						if (datajson.passed == true) {
							// showSweet(true, "The changes have been saved.")
						} else {
							// showSweet(false, "An error occurred, please try again")
						}
					}
				}).done(function( data ) {
					$btn.text(originalText);
					$btn.prev('.notice-text').addClass('text-primary').text('Saved Successfully').show().delay(5000).queue(function(n) {
						let element = jQuery(this);
						element.removeClass('text-primary');
						element.text(''); n();
					});
				});
			}

			function showSweet(respuesta, message) {
				if (respuesta) {
					Swal.fire({
						toast: true,
						title: message,
						icon: "success",
						background: 'white',
						showConfirmButton: false,
						timer: 1000000,
						position: 'top-end',
					})
				} else {
					Swal.fire({
						toast: true,
						title: message,
						icon: "error",
						background: 'white',
						showConfirmButton: false,
						timer: 5000,
						position: 'top-end',
					})
				}
			}

			/**
			 * *Handles the old backup
			 */
			function showConfirmOld(next) {
				Swal.fire({
					title: 'Are you sure?',
					text: "Are you sure you want to restore old backup",
					icon: 'warning',
					showCancelButton: true,
					confirmButtonColor: '#3085d6',
					cancelButtonColor: '#d33',
					confirmButtonText: 'Yes, continue!'
				}).then((result) => {
					if (result.isConfirmed) {
						console.log("backup old")
						next()
					}
				})
			}

			function saveSCCEmailSetting($btn) {
				event.preventDefault();
				$btn = jQuery($btn);
				var sendername = jQuery('#sendername').val();
				var senderemail = jQuery('#senderemail').val();
				var originalText = $btn.text();
				<?php
                if ( get_option( 'df_scc_licensed' ) != 0 ) {
                    ?>
					var messageform = jQuery('#messagetemplate').val();
					<?php
                } else {
                    ?>
					var messageform = "Hello <customer-name>, <br><br> Attached to this email is a PDF file that contains your quote. <br> If you have any further questions please call us email us here ____. <br><br> Sincerely,<br> Your Company Name<br><br> <hr><br> <b>Customer's Name</b> l <customer-name> <b>Customer's Phone</b> l <customer-phone> <b>Customer's Emai</b> l <customer-email> <b>Customer's IP</b> l <customer-ip-address> <b>Browser Info</b> l <customer-browser-info ><b>Device</b> l <device> <b> Referral </b> | <customer-referral>";
					<?php
                }
        ?>
				var sccemailfooter = jQuery('#sccemailfooter').val();
				var scc_emailsubject = jQuery('#emailsubject').val();
				var scc_email_banner_image = jQuery('#scc_email_banner_image').val();
				var scc_email_logo_image = jQuery('#scc_email_logo_image').val()
				var scc_email_send_copy = jQuery('#scc_email_send_copy').val();
				if (location.protocol === 'https:') {
					if (scc_email_banner_image) {
						if (scc_email_banner_image.indexOf('http://') != -1) {
							scc_email_banner_image = scc_email_banner_image.replace('http://', 'https://')
						}
					}
					if (scc_email_logo_image) {
						if (scc_email_logo_image.indexOf('http://') != -1) {
							scc_email_logo_image = scc_email_logo_image.replace('http://', 'https://')
						}
					}
				} else {
					// is http
				}
				messageform = messageform.replace(/(\r\n|\n|\r)/gm, "<br>")
				if (senderemail == '') {
					showSweet(false, 'Sender Email is Mandatory. Please, add a valid email. Thank you ')
				} else if (sendername == '') {
					showSweet(false, 'Sender Name is Mandatory. Please, add a valid email. Thank you ')
				} else if (scc_emailsubject == '') {
					showSweet(false, 'Email subject is Mandatory. Please, add a valid email. Thank you ')
				} else {
					$fragment_refresh = {
						url: ajaxurl,
						type: 'POST',
						data: {
							action: 'sccSaveEmailSettings',
							sender_name: sendername,
							sender_email: senderemail,
							emailsubject_testing: scc_emailsubject,
							scc_email_send_copy: scc_email_send_copy,
							message_form: messageform,
							sccemailfooter: sccemailfooter,
							nonce: pageGlobalSettings.nonce
						},
						beforeSend: function() {
							$btn.text('saving...');
						},
						success: function(data) {
							showSweet(true, 'Email Quote & Detailed List has been saved!')
						}
					};
					jQuery.ajax($fragment_refresh).done(function( data ) {
						$btn.removeClass('button-glow');
						$btn.text(originalText);
						$btn.prev('.notice-text').addClass('text-primary').text('Saved Successfully').show().delay(5000).queue(function(n) {
							let element = jQuery(this);
							element.removeClass('text-primary');
							element.text(''); n();
						});
					});
				}
			}

			function sccPDFSettings($btn) {
				$btn = jQuery($btn);
				var sccPDFFont = jQuery('#pdf_font').children("option:selected").val();
				var sccPDFDateFmt = jQuery('#pdf_datefmt').children("option:selected").val();
				var originalText = $btn.text();
				$fragment_refresh = {
					url: ajaxurl,
					type: 'POST',
					data: {
						action: 'sccPDFSettings',
						pdfSettings: {
							sccPDFFont,
							sccPDFDateFmt
						},
						nonce: pageGlobalSettings.nonce
					},
					beforeSend: function() {
						$btn.text('saving...');
					},
					success: function(data) {
						showSweet(true, 'Saved successfully.')
					},
					error: function(err) {}
				};
				jQuery.ajax($fragment_refresh).done(function( data ) {
					$btn.text(originalText);
					$btn.prev('.notice-text').addClass('text-primary').text('Saved Successfully').show().delay(5000).queue(function(n) {
						let element = jQuery(this);
						element.removeClass('text-primary');
						element.text(''); n();
					});
				});
			}

			/**
			 * *Handles backup restore from json file
			 */
			function sscUploadSccBackup() {
				showLoadingChanges()
				var files = jQuery('#backup_scc_file')[0].files[0];
				if (!files) {
					console.log("no hay archivo ")
					return
				}
				var reader = new FileReader()
				reader.readAsText(files, "UTF-8")
				reader.onload = function(ee) {
					let json = JSON.parse(ee.target.result)
					var o = ("scc_form" in json)
					if (o) {
						showConfirmOld(restore2)
					} else {
						const fdata = new FormData()
						fdata.append('file', files)
						fdata.append('action', "sccRestoreBackup")
						fdata.append('nonce', pageGlobalSettings.nonce)
						jQuery.ajax({
							type: 'POST',
							url: ajaxurl,
							data: fdata,
							contentType: false,
							processData: false,
							success: function(data) {
								if (data.passed) {
									showSweet(true, 'SCC restored successfully.')
								} else {
									showSweet(false, data.msj)
								}

							}
						})
					}
				}
			}

			function handleCurrencyCoversionMode(element) {
				var selection = jQuery(element).val()
				if (selection == "manual_selection") {
					jQuery("#scc_currency_coversion_manual_selection_container").css("display", "")
				} else {
					jQuery("#scc_currency_coversion_manual_selection_container").css("display", "none")
				}
			}

			function handleCurrency() {
				if (jQuery('#currency_code').val() == 'AED' ||
					jQuery('#currency_code').val() == 'COP' ||
					jQuery('#currency_code').val() == 'ANG' ||
					jQuery('#currency_code').val() == 'PKR' ||
					jQuery('#currency_code').val() == 'TWD' ||
					jQuery('#currency_code').val() == 'ZMW' ||
					jQuery('#currency_code').val() == 'CFA') {
					jQuery('#currency_conversion_incompatibility_message').html('The current selected currency is not supported for the "Currency conversion feature"')
					jQuery('#scc_currency_coversion_global_container').css('display', 'none')
				} else {
					jQuery('#currency_conversion_incompatibility_message').html('')
					jQuery('#scc_currency_coversion_global_container').css('display', 'inline')
				}
			}

		</script>
		<?php
    }

    private function output_card( $card_props ) {
        $settings_slug  = preg_replace( '~[^\pL\d]+~u', '_', $card_props['name'] );
        $settings_slug  = trim( $settings_slug, '_' );
        $settings_slug  = strtolower( $settings_slug );
        ?>
		<div class="accordion-item" style="max-width: 40rem;">
			<h2 class="accordion-header">
			<button class="accordion-button py-4 collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#accordion-<?php echo esc_attr( $settings_slug ); ?>" aria-expanded="true" aria-controls="<?php echo 'accordion-' . esc_attr( $settings_slug ); ?>">
				<?php
                echo isset( $card_props['icon'] ) ? '<i class="material-icons-outlined scc-icn-wrapper" style="margin-left:5px;">' . $card_props['icon'] . '</i>&nbsp;' : '';
        echo sanitize_text_field( $card_props['name'] );
        echo isset( $card_props['helpdesk_link'] ) ? ' ' . scc_get_kses_extended_ruleset( $card_props['helpdesk_link'] ) : '';
        ?>
			</button>
			</h2>
			<div id="<?php echo 'accordion-' . esc_attr( $settings_slug ); ?>" class="accordion-collapse collapse" data-bs-parent="#settings-page-accordion">
			<div class="accordion-body">
			<?php if ( isset( $card_props['hasShortcodes'] ) && $card_props['hasShortcodes'] ) { ?>
						<div class="text-primary mb-0" role="button" onclick="toggleShortCodes()">Shortcodes</div>
				<?php } ?>
			<?php
        for ( $i = 0; $i < count( $card_props['fields'] ); $i++ ) {
            call_user_func( [ $this, 'field_' . $card_props['fields'][ $i ] ] );
        }
        ?>
				<?php if ( isset( $card_props['notes'] ) ) { ?>
					<p><?php echo esc_attr( $card_props['notes'] ); ?></p>
				<?php } ?>
				<?php if ( isset( $card_props['action_btn'] ) && $card_props['action_cb'] ) { ?>
				<div class="d-flex w-100 justify-content-between">
					<p class="mb-0 notice-text"></p>
					<button type="button" class="btn btn-primary btn-lg" id="<?php echo $settings_slug; ?>" onclick="<?php echo esc_attr( $card_props['action_cb'] ); ?>"><?php echo esc_attr( $card_props['action_btn'] ); ?></button>
				</div>
				<?php } ?>
				<?php
            if ( isset( $card_props['extra_fragment'] ) ) {
                call_user_func( [ $this, 'extra_fragment_' . $card_props['extra_fragment'] ] );
            }
        ?>
			</div>
			</div>
  		</div>
		<?php
    }

    private function field_currency() {
        $currency      = get_option( 'df_scc_currency', 'USD' );
        $currency_data = require SCC_DIR . '/lib/currency_data.php';
        ?>
		 <div class="mb-3 row scc-currency">
			<label for="currency_code" class="col-sm-5 col-form-label" data-setting-tooltip-type="currency-selector-tt">Currency</label>
			<div class=" col-sm-7">
			<select class="form-select form-select-lg mb-3" id="currency_code"   autocomplete="off" name="currency_code" class="form-control"  onchange="handleCurrency()">
				<option value="">Select one currency</option>			
				<?php foreach ( $currency_data as $key => $value ) { ?>
					<option value="<?php echo esc_attr( $value['code'] ); ?>" <?php echo ( $currency == $value['code'] ) ? 'selected' : ''; ?>><?php echo esc_attr( $value['currency'] ); ?></option>
				<?php } ?>
			</select>
			</div>
		</div>
		<?php
    }
    private function field_currency_for_autoconv() {
        $currency_conversion_selection = get_option( 'df_scc_currency_coversion_manual_selection' );
        $currency_conversion_mode      = get_option( 'df_scc_currency_coversion_mode', 'off' );
        $currency_data                 = require SCC_DIR . '/lib/currency_data.php';
        ?>
		<div class="mb-3 row 
		<?php
        if ( $currency_conversion_mode !== 'manual_selection' ) {
            echo 'd-none';
        }
        ?>
		" id="scc_currency_coversion_manual_selection_container">
		<label class="col-sm-5 col-form-label" for="scc_currency_coversion_manual_selection">Select your currency for automatic conversion: </label>
			<div class=" col-sm-7">
			<select class="form-select form-select-lg mb-3" name="scc_currency_coversion_manual_selection" id="scc_currency_coversion_manual_selection">
				<option value="">Select currency</option>
				<?php foreach ( $currency_data as $key => $value ) { ?>
					<option value="<?php echo esc_attr( $value['code'] ); ?>" <?php selected( $currency_conversion_selection == $value['code'] ); ?>><?php echo esc_attr( $value['currency'] ); ?></option>
				<?php } ?>
			</select>
			</div>
		</div>
		<?php
    }
    private function field_currency_num_format() {
        $currency_style = get_option( 'df_scc_currency_style', 'default' ); // dot or comma
        ?>
		<div class="mb-3 row">
			<label class="col-sm-5 col-form-label" data-setting-tooltip-type="currency-format-tt"  for="currency-style">Currency Format:</label>
			<div class=" col-sm-7">
				<select class="form-select form-select-lg mb-3" name="currency-style" id="currency-style">
					<option value="default" <?php echo ( $currency_style == 'default' ) ? 'selected' : ''; ?>>Browser Locale</option>
					<option value="comma" <?php echo ( $currency_style == 'comma' ) ? 'selected' : ''; ?>>Comma separated</option>
				</select>
			</div>
		</div>
		<?php
    }
    private function field_currency_conversion_type() {
        $currency_conversion_mode = get_option( 'df_scc_currency_coversion_mode', 'off' );
        ?>
		<div class="mb-3 row 
		<?php
        if ( $this->isSCCFreeVersion ) {
            echo '';
        }
        ?>
		" >
			<label class="col-sm-5 col-form-label" data-setting-tooltip-type="auto-currency-conversion-tt" for="scc_currency_coversion_mode">Currency Conversion: </label>
			<span class=" col-sm-7">
				<select class="form-select form-select-lg mb-3" 
				<?php
                if ( $this->isSCCFreeVersion ) {
                    echo 'disabled';
                }
        ?>
				 name="scc_currency_coversion_mode" id="scc_currency_coversion_mode" onchange="handleCurrencyCoversionMode(this)">
					<option value="off" <?php echo ( $currency_conversion_mode == 'off' ) ? 'selected' : ''; ?>>OFF (default)</option>
					<option value="manual_selection" <?php echo ( $currency_conversion_mode == 'manual_selection' ) ? 'selected' : ''; ?>>Select a currency</option>
					<option value="auto_detect" <?php echo ( $currency_conversion_mode == 'auto_detect' ) ? 'selected' : ''; ?>>Auto detect currency</option>
				</select>
				</span>
		</div>
		<?php
    }
    private function field_pdf_footer_notes() {
        $disclaimer = wp_kses_post( get_option( 'df_scc_footerdisclaimer' ) );
        ?>
		<div class="mb-3">
			<label for="sccemailfooter" class="form-label">Footer/Desclaimer Notes</label>
			<textarea class="form-control" id="sccemailfooter" rows="3"><?php echo wp_kses_post( str_replace( '\\', '', stripslashes( $disclaimer ) ) ); ?></textarea>
		</div>
		<?php
    }
    private function field_banner_and_logo() {
        $disclaimer   = wp_kses_post( get_option( 'df_scc_footerdisclaimer' ) );
        $banner_image = get_option( 'df_scc_email_banner_image', false );
        $logo_image   = get_option( 'df_scc_email_logo_image', false );
        ?>
		<div class="mb-3 row scc-form-field">
			<div class=" col-sm-6">
				<label class="col-form-label">Banner <span 
					class="tooltipadmin-right"
					data-tooltip="Add a banner to the PDF/Detail View pop-up. Make sure you have GD library installed on your server. Choose a compressed/optimized image to conserve RAM usage">
					<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000"><path d="M0 0h24v24H0z" fill="none"/><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
				</span></label>
				<div class="text-center upload-btn-wrapper" style="display: <?php echo $banner_image ? 'none' : 'block'; ?>;">
					<button type="button" class="btn btn-primary scc-media-upload-button">Upload Banner</button>
					<input type="hidden" name="df_scc_email_banner_image" class="scc-media-upload-field" value="<?php echo esc_attr( $banner_image ); ?>">
				</div>
				<div class="scc-media-uploader-image" style="display: <?php echo $banner_image ? 'block' : 'none'; ?>;">
					<?php
                    if ( $banner_image ) {
                        echo '<img src="' . esc_attr( $banner_image ) . '" />';
                    }
        ?>
					<span class="scc-media-uploader-remove">&times;</span>
				</div>
			</div>
			<div class=" col-sm-6">
				<label class="col-form-label">Logo <span 
					class="tooltipadmin-right"
					data-tooltip="Add a logo to the PDF/Detail View pop-up. Make sure you have GD library installed on your server. Choose a compressed/optimized image to conserve RAM usage">
					<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000"><path d="M0 0h24v24H0z" fill="none"/><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
				</span></label>
				<div class="text-center upload-btn-wrapper" style="display: <?php echo $logo_image ? 'none' : 'block'; ?>;">
					<button type="button" class="btn btn-primary scc-media-upload-button">Upload Logo</button>
					<input type="hidden" name="df_scc_email_logo_image" class="scc-media-upload-field" value="<?php echo esc_attr( $logo_image ); ?>">
				</div>
				<div class="scc-media-uploader-image" style="display: <?php echo $logo_image ? 'block' : 'none'; ?>;">
					<?php
        if ( $logo_image ) {
            echo '<img src="' . esc_attr( $logo_image ) . '" />';
        }
        ?>
					<span class="scc-media-uploader-remove">&times;</span>
				</div>
			</div>
		</div>
		<?php
    }
    private function field_email_quote_settings_form() {
        $scc_sendername   = get_option( 'df_scc_sendername' );
        $scc_emailsender  = get_option( 'df_scc_emailsender' );
        $scc_emailsubject = get_option( 'df_scc_emailsubject' );
        ?>
		<div id="email_form_short_codes_section" style="display: none; background: rgb(221, 223, 248); padding: 20px; margin-bottom: 10px;">
					<strong>
						<p>NOTE: Use these shortcodes to customize your email template to your customers.</p>
					</strong>
					<p>Customer's Email &lt;customer-email&gt;</p>
					<p>Customer Name &lt;customer-name&gt;</p>
					<p>Customer Phone &lt;customer-phone&gt;</p>
					<p>Customer Broswer &lt;customer-browser-info&gt;</p>
					<p>Customer Device &lt;device&gt;</p>
					<p>Customer IP &lt;customer-ip-address&gt;</p>
					<p>Sender (Your) Name &lt;sender&gt;</p>
				</div>
		<div class="row">
			<div class="col-sm-6 col-form-label">
				<input type="text" class="form-control" id="sendername" placeholder="Sender Name (You)" value="<?php echo esc_attr( sanitize_text_field( $scc_sendername ) ); ?>">
			</div>
			<div class=" col-sm-6 col-form-label">
				<input type="text" class="form-control" id="senderemail" placeholder="Sender Email (Your Email)" value="<?php echo esc_attr( sanitize_text_field( $scc_emailsender ) ); ?>">
			</div>
		</div>
		<div class="row">
			<div class="col-sm-6 col-form-label">
				<input type="text" class="form-control" id="scc_email_send_copy" placeholder="CC Email Address (optional)" value="<?php echo esc_attr( get_option( 'df_scc_email_send_copy' ) ); ?>">
			</div>
		</div>
		<div class="mb-3 row">
			<div class="col-sm-12 col-form-label">
				<input type="text" class="form-control" id="emailsubject" placeholder="Email Subject" value="<?php echo esc_attr( stripslashes( htmlspecialchars( $scc_emailsubject ) ) ); ?>">
			</div>
		</div>
		<?php
        $scc_messageform = get_option( 'df_scc_messageform' );
        $tags            = [ '<customer-name>', '<customer-phone>', '<customer-email>', '<customer-ip-address>', '<customer-browser-info>', '<device>', '<customer-referral>' ];
        $tags_htmlified  = array_map(
            function ( $a ) {
                return htmlentities( $a );
            },
            $tags
        );
        $scc_messageform = str_replace( $tags, $tags_htmlified, $scc_messageform );
        wp_editor( stripslashes( str_replace( [ '<br/>', '<br>', '< br/>' ], [ "\r\n", "\r", "\n" ], $scc_messageform ) ), 'messagetemplate', [] );
    }
	private function field_integration_settings_form() {
        $zapier_key = get_option( 'scc_zapier_api_key', false );

        if ( empty( $zapier_key ) ) {
            $zapier_key = wp_generate_password( 32, false );
            update_option( 'scc_zapier_api_key', $zapier_key );
        }
        $site_url = site_url();
        ?>
		<div class="accordion mt-2" id="settings-page-accordion-integrations">
		<div class="accordion-item" style="max-width: 40rem;">
			<h2 class="accordion-header">
			<button class="accordion-button py-4 collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#accordion-integration_settings_zapier" aria-expanded="false" aria-controls="accordion-integration_settings_zapier">
				<span class="scc-icn-wrapper ms-1"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-code"><path d="M16 18l6-6-6-6M8 6l-6 6 6 6"></path></svg></span>&nbsp;Zapier</button>
			</h2>
			<div id="accordion-integration_settings_zapier" class="accordion-collapse collapse" data-bs-parent="#settings-page-accordion-integrations">
			<div class="accordion-body">
			<div class="mb-3 align-items-center row">
			<label for="currency_code" class="col-sm-12 col-form-label use-tooltip d-flex align-items-center" >Authentication Key <span data-global-setting-tooltip-key="zapier-key-global-tt"  class="scc-icn-wrapper ms-1"> <?php echo scc_get_kses_extended_ruleset( $this->scc_icons['help-circle'] ); ?></span> </label> 
			<div class="col-sm-12 d-flex align-items-center">
				<span data-clip-value-for="zapier-key"><?php echo esc_attr( $zapier_key ); ?></span>
				<span title="Copy" class="use-tooltip scc-icn-wrapper ms-1" data-copy-field="zapier-key" role="button"><?php echo scc_get_kses_extended_ruleset( $this->scc_icons['copy'] ); ?></span>
			</div>
		</div>
		<div class="mb-3 align-items-center row">
			<label for="currency_code" class="col-sm-12 col-form-label use-tooltip d-flex align-items-center" >Website URL <span data-global-setting-tooltip-key="zapier-sitename-global-tt"  class="scc-icn-wrapper ms-1"> <?php echo scc_get_kses_extended_ruleset( $this->scc_icons['help-circle'] ); ?></span> </label> 
			<div class="col-sm-12 d-flex align-items-center">
				<span data-clip-value-for="zapier-site-url"><?php echo esc_url( $site_url ); ?></span>
				<span title="Copy" class="use-tooltip scc-icn-wrapper ms-1" data-copy-field="zapier-site-url" role="button"><?php echo scc_get_kses_extended_ruleset( $this->scc_icons['copy'] ); ?></span>
			</div>
		</div>
				</div>
				</div>
			</div>
		</div>
		<?php
    }
    private function field_google_maps_settings_form() {
        ?>
		<div id="google-maps">
			<p class="">Please enter the Google Maps Api key.</p>


			<div class="mb-3 align-items-center row">
				<label class="col-sm-5 col-form-label scc-distance-global-setting-label">API Key <span data-setting-tooltip-type="distance-global-settings-tt"  class="scc-icn-wrapper" style=""> <?php echo scc_get_kses_extended_ruleset( $this->scc_icons['help-circle'] ); ?></span></label>
				<div class=" col-sm-12">
					<input 
					<?php
                    if ( $this->isSCCFreeVersion ) {
                        echo 'disabled';
                    }
        ?>
					 type="text" class="form-control" name="scc-google-maps-api-key" placeholder="Insert API Key" value="">
				</div>
			</div>

		</div>
		<?php
    }

    private function field_sms_settings_form() {
        ?>
		<div class="mb-3 align-items-center row">
			<div class="row settings-field-wrapper">
				<div class="scc-vcenter">
					<label class="scc-accordion_switch_button" for="enable-texting-leads">
						<input type="checkbox" name="enable-texting-leads" role="switch" id="enable-texting-leads">
						<span class="scc-accordion_toggle_button round"></span>
					</label>
					<label for="enable-texting-leads" style="vertical-align: sub;" class="lblExtraSettingsEditCalc fw-bold">Enable SMS Quotes</label>
				</div>
			</div>
			<div class="row settings-field-wrapper">
				<div class="col-sm-12 col-form-label">
					<input type="text" class="form-control" id="twilio-account-sid">
					<label class="form-label active" for="twilio-account-sid" title="Enter Your Twilio Account SID">
						<span>Twilio Account SID</span>
						<span role="button" data-setting-tooltip-type="twilio-acct-sid-tt" class="scc-icn-wrapper" style="margin-left:5px;"></span>
					</label>
				</div>
			</div>
			<div class="row settings-field-wrapper">
				<div class="col-sm-12 col-form-label">
					<input type="text" class="form-control" id="twilio-api-key">
					<label class="form-label active" for="twilio-api-key" title="Enter Your Twilio API Key">
						<span>Twilio Auth Token</span>
						<span role="button" data-setting-tooltip-type="twilio-auth-token-tt" class="scc-icn-wrapper" style="margin-left:5px;"></span>
					</label>
				</div>
			</div>
			<div class="row settings-field-wrapper">
				<div class="col-sm-12 col-form-label">
					<input type="text" class="form-control" id="twilio-send-from-phone-number">
					<label class="form-label active" for="twilio-send-from-phone-number">
						<span>From Number</span>
						<span role="button" data-setting-tooltip-type="twilio-send-from-number-tt" class="scc-icn-wrapper" style="margin-left:5px;"></span>
					</label>
				</div>
			</div>
			<div class="row settings-field-wrapper">
				<div class="col-sm-12 col-form-label">
					<textarea class="form-control" id="text-message-template" rows="8"></textarea>
					<label class="form-label active" for="text-message-template">
						<span>Text Message</span>
						<span role="button" data-setting-tooltip-type="twilio-text-template-tt" class="scc-icn-wrapper" style="margin-left:5px;"></span>
					</label>
				</div>
			</div>
			<div class="row settings-field-wrapper">
				<div class="col-sm-11 col-form-label">
					<input class="form-control" id="text-message-webhook" disabled value="<?php echo esc_url( rest_url( 'scc/v1' ) . '/twilio-incoming-text' ); ?>">
					<label class="form-label active" for="text-message-webhook">
						<span>Incoming SMS Webhook Link</span>
						<span role="button" data-setting-tooltip-type="twilio-api-webhook-tt" class="scc-icn-wrapper" style="margin-left:5px;"></span>
					</label>
				</div>
				<div class="col-sm-1 col-form-label">
				</div>
			</div>
		</div>
		<?php
    }
    private function field_pdf_settings_form() {
        ?>
		<p class="mb-3">Select the font and date format you would like to use for email quote and detailed list view.</p>
		<div class="mb-3 row  
		
		">
			<label class="col-sm-5 col-form-label use-tooltip" data-setting-tooltip-type="pdf-format-tt">PDF Format</label>
			<div class=" col-sm-7">
				<select class="form-select form-select-lg mb-3" 
				<?php
                if ( $this->isSCCFreeVersion ) {
                    echo 'disabled';
                }
        ?>
				 id="pdf_font">
					<option value="regular" 
					<?php
            if ( get_option( 'sccPDFFont' ) == 'regular' ) {
                echo 'selected';
            }
        ?>
					>Regular</option>
					<option value="cid0jp" 
					<?php
        if ( get_option( 'sccPDFFont' ) == 'cid0jp' ) {
            echo 'selected';
        }
        ?>
					>cid0jp (Japanese and Russian support)</option>
					<option value="dejavusans" 
					<?php
        if ( get_option( 'sccPDFFont' ) == 'dejavusans' ) {
            echo 'selected';
        }
        ?>
					>DejaVuSans</option>
					<option value="dejavusansb" 
					<?php
        if ( get_option( 'sccPDFFont' ) == 'dejavusansb' ) {
            echo 'selected';
        }
        ?>
					>DejaVuSans-Bold</option>
					<option value="dejavusansbi" 
					<?php
        if ( get_option( 'sccPDFFont' ) == 'dejavusansbi' ) {
            echo 'selected';
        }
        ?>
					>DejaVuSans-BoldOblique</option>
					<option value="helvetica" 
					<?php
        if ( get_option( 'sccPDFFont' ) == 'helvetica' ) {
            echo 'selected';
        }
        ?>
					>Helvetica</option>
					<option value="Helvetica-Bold" 
					<?php
        if ( get_option( 'sccPDFFont' ) == 'Helvetica-Bold' ) {
            echo 'selected';
        }
        ?>
					>Helvetica-Bold</option>
					<option value="Helvetica-BoldOblique" 
					<?php
        if ( get_option( 'sccPDFFont' ) == 'Helvetica-BoldOblique' ) {
            echo 'selected';
        }
        ?>
					>Helvetica-BoldOblique</option>
					<option value="Helvetica-Italic" 
					<?php
        if ( get_option( 'sccPDFFont' ) == 'Helvetica-Italic' ) {
            echo 'selected';
        }
        ?>
					>Helvetica-Italic</option>
				</select>
			</div>
		</div>
		<div class="mb-3 row">
			<label class="col-sm-5 col-form-label use-tooltip" data-setting-tooltip-type="pdf-date-format-tt" >Date Format</label>
			<div class=" col-sm-7">
				<select class="form-select form-select-lg mb-3" id="pdf_datefmt">
					<option value="mm-dd-yyyy" 
					<?php
        if ( get_option( 'scc_pdf_datefmt' ) == 'mm-dd-yyyy' ) {
            echo 'selected';
        }
        ?>
					>mm-dd-yyyy</option>
					<option value="dd-mm-yyyy" 
					<?php
        if ( get_option( 'scc_pdf_datefmt' ) == 'dd-mm-yyyy' ) {
            echo 'selected';
        }
        ?>
					>dd-mm-yyyy</option>
				</select>
			</div>
		</div>
		<?php
    }
    private function field_recaptcha_settings_form() {
        ?>
		<div id="recaptcha">
			<p class="mb-3">Please enter the recaptcha keys in the following fields.</p>
			<div class="">
				<input class="form-check-input" type="checkbox" name="captcha-enablement-status" role="switch" id="captcha-enablement-status" 
				<?php
                if ( get_option( 'df_scc-captcha-enablement-status', false ) ) {
                    echo 'checked';
                }
        ?>
				>
				<label class="form-check-label" for="captcha-enablement-status">Enable reCaptcha v2</label>
			</div>
			<div class="mb-3 row">
				<label class="col-sm-5 col-form-label">Site Key</label>
				<div class=" col-sm-7">
					<input 
					<?php
            if ( $this->isSCCFreeVersion ) {
                echo 'disabled';
            }
        ?>
					 type="text" class="form-control" name="site-key-recaptcha" placeholder="" value="<?php echo esc_attr( get_option( 'df_scc-recaptcha-site-key' ) ); ?>">
				</div>
			</div>
			<div class="mb-3 row">
				<label class="col-sm-5 col-form-label">Secret Key</label>
				<div class=" col-sm-7">
					<input 
					<?php
        if ( $this->isSCCFreeVersion ) {
            echo 'disabled';
        }
        ?>
					 type="text" class="form-control" name="secret-key-recaptcha" placeholder="" value="<?php echo esc_attr( get_option( 'df_scc-recaptcha-secret-key' ) ); ?>">
				</div>
			</div>
		</div>
		<?php
    }
    private function field_restore_calc_form() {
        ?>
		<div class="mb-3">
			<label for="backup_scc_file" class="form-label">Please select a backup file for restoration</label>
			<input class="form-control" type="file" id="backup_scc_file" onclick="sscUploadSccBackup()" accept=".json">
		</div>
		<?php
    }

    /** stripe settings fields */
    private function field_stripe_secret_key() {
        ?>
		<div class="mb-3 row">
			<label class="col-sm-5 col-form-label">Stripe Secret Key: </label>
			<div class=" col-sm-7">
				<input type="text" class="form-control" name="stripe-api-priv-key" placeholder="<?php echo esc_attr( $this->pubKeyPlaceHolder ); ?>">
			</div>
		</div>
		<?php
    }
    private function field_stripe_public_key() {
        ?>
		<div class="mb-3 row">
			<label class="col-sm-5 col-form-label">Stripe Public Key: </label>
			<div class=" col-sm-7">
				<input type="text" class="form-control" name="stripe-api-pub-key" placeholder="<?php echo esc_attr( $this->privKeyPlaceHolder ); ?>">
			</div>
		</div>
		<?php
    }

    private function formFields( $fieldLabel, $fieldType, $fieldName, $choices = null, $currentValue = null ) {
        ?>
		<div class="mb-3 row">
			<label for="<?php echo esc_attr( $fieldName ); ?>" class="col-sm-5 col-form-label"><?php echo esc_attr( $fieldLabel ); ?></label>
			<div class="col-sm-7">
				<?php
                switch ( $fieldType ) {
                    case 'select':
                        $this->renderChoices( $choices, $currentValue );
                        break;

                    default:
                        echo '<input type="<?php echo esc_attr($fieldType) ?>" readonly class="form-control-plaintext" id="<?php echo esc_attr($fieldName); ?>" value="email@example.com">';
                        break;
                }
        ?>
			</div>
		</div>
		<?php
    }

    private function renderChoices() {
        $currency_conversion_selection = get_option( 'df_scc_currency_coversion_manual_selection' );
        ?>
		<select class="form-select form-select-lg mb-3" name="scc_currency_coversion_manual_selection" id="scc_currency_coversion_manual_selection">
		<option value="EUR" <?php echo ( $currency_conversion_selection == 'EUR' ) ? 'selected' : ''; ?>>EUR</option>
							<option value="CAD" <?php echo ( $currency_conversion_selection == 'CAD' ) ? 'selected' : ''; ?>>CAD</option>
							<option value="HKD" <?php echo ( $currency_conversion_selection == 'HKD' ) ? 'selected' : ''; ?>>HKD</option>
							<option value="ISK" <?php echo ( $currency_conversion_selection == 'ISK' ) ? 'selected' : ''; ?>>ISK</option>
		</select>
		<?php
    }

    /**
     * HTML for email template banner and logo
     */
    private function email_template_banner_and_logo() {
        $disclaimer   = wp_kses_post( get_option( 'df_scc_footerdisclaimer' ) );
        $banner_image = get_option( 'df_scc_email_banner_image', false );
        $logo_image   = get_option( 'df_scc_email_logo_image', false );
        ?>
		<h2 class="scc-settings-card-title"><span class="highlighted">EMAIL, PDF & DETAILED LIST</span> SETTINGS <a href="https://designful.freshdesk.com/a/solutions/articles/48001167920" target="_blank"><i class="fa fa-book" aria-hidden="true"></i></a></h2>
		</span>
		<div class="scc-settings-card-inner" id="scc_email_quote_settings">
			<div class="scc-form-field" style="padding: 5px;border: 2px solid #2271b1;">
				<label for="sccemailfooter" style="margin-bottom: 15px;">
					<span style="font-weight:800;color:#314bf8;">FOOTER/DISCLAIMER</span> NOTES
				</label>
				<textarea class="scc-textarea-field" onkeyup="jQuery('.scc_save_emdl:eq(0)').addClass('button-glow')" placeholder="" id="sccemailfooter" rows="14"><?php echo wp_kses_post( str_replace( '\\', '', stripslashes( $disclaimer ) ) ); ?></textarea>
				<input class="sccbutton scc_save_emdl" style="width:100%;height:45px;font-size:18px" type="submit" name="Save" value="SAVE FOOTER SETTINGS" onclick="saveSCCEmailSetting(this)">
			</div>
			<?php if ( $this->isSCCFreeVersion ) { ?>
			<div class="blocked" style="width: 100%; padding: 5px; background-color: black; opacity: 0.85; display: inline-block; position: relative;"><div style="position:absolute;left:0px;right:0px;top:0px;bottom:0px;z-index:99999;opacity:1">	<center><h5 style="color:white;margin-top:50px">THIS FEATURE IS AVAILABLE IN THE PREMIUM VERSION</h5></center>	<div style="margin-left:40%;margin-top:20px;background-color:#314af3;padding:5px;max-width:100px;text-align:center">		<a target="_blank" href="https://stylishcostcalculator.com/" style="z-index:99999;opacity:1; color: white">BUY NOW		</a>	</div></div>
			<?php } ?>
			<div class="scc-form-field" style="padding: 5px;border: 2px solid #2271b1;">
				<label style="margin-bottom: 15px;">
					<span style="font-weight:800;color:#314bf8;">DETAIL LIST</span> HEADER
				</label>
				<label>Banner <span 
					class="tooltipadmin-right"
					data-tooltip="Add a banner to the PDF/Detail View pop-up. Make sure you have GD library installed on your server. Choose a compressed/optimized image to conserve RAM usage">
					<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000"><path d="M0 0h24v24H0z" fill="none"/><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
				</span></label>
				<div class="scc-media-uploader-buttons" style="display: <?php echo $banner_image ? 'none' : 'block'; ?>;">
					<input type="button" class="sccbutton scc-media-upload-button" value="Upload Banner">
					<input type="hidden" name="df_scc_email_banner_image" class="scc-media-upload-field" value="<?php echo esc_attr( $banner_image ); ?>">
					<span class="spinner"></span>
				</div>
				<div class="scc-media-uploader-image" style="display: <?php echo $banner_image ? 'block' : 'none'; ?>;">
					<?php
                    if ( $banner_image ) {
                        echo '<img src="' . esc_attr( $banner_image ) . '" />';
                    }
        ?>
					<span class="scc-media-uploader-remove">&times;</span>
				</div>
				<label>Logo <span 
					class="tooltipadmin-right"
					data-tooltip="Add a logo to the PDF/Detail View pop-up. Make sure you have GD library installed on your server. Choose a compressed/optimized image to conserve RAM usage">
					<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000"><path d="M0 0h24v24H0z" fill="none"/><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
				</span></label>
				<div class="scc-media-uploader-buttons" style="display: <?php echo $logo_image ? 'none' : 'block'; ?>;">
					<input type="button" class="sccbutton scc-media-upload-button" value="Upload Logo">
					<input type="hidden" name="df_scc_email_logo_image" class="scc-media-upload-field" value="<?php echo esc_attr( $logo_image ); ?>">
					<span class="spinner"></span>
				</div>
				<div class="scc-media-uploader-image" style="display: <?php echo $logo_image ? 'block' : 'none'; ?>;">
					<?php
        if ( $logo_image ) {
            echo '<img src="' . esc_attr( $logo_image ) . '" />';
        }
        ?>
					<span class="scc-media-uploader-remove">&times;</span>
				</div>
			</div>
				</div>
		<?php
    }
}

new Stylish_Cost_Calculator_Settings();

?>
