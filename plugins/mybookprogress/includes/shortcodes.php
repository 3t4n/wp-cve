<?php

function mbp_init_shortcodes() {
	add_shortcode('mybookprogress', 'mbp_mybookprogress_shortcode');
	add_filter('authormedia_get_shortcodes', 'mbp_add_authormedia_shortcodes');
}
add_action('mbp_init', 'mbp_init_shortcodes');



/*---------------------------------------------------------*/
/* Shortcode Function                                      */
/*---------------------------------------------------------*/

function mbp_mybookprogress_shortcode($attrs) {
	$options = array('location' => 'shortcode');
	if(isset($attrs['showsubscribe'])) { $options['showsubscribe'] = ($attrs['showsubscribe'] === 'true'); }
	if(isset($attrs['simplesubscribe'])) { $options['simplesubscribe'] = ($attrs['simplesubscribe'] === 'true'); }

	if(isset($attrs['book'])) {
		$book = mbp_get_book($attrs['book']);

		if(isset($attrs['book_title'])) {
			if(empty($book)) { $book = array('display_bar_color' => 'CB3301', 'display_cover_image' => 0, 'mbt_book' => null, 'phases' => array(), 'phases_status' => array()); }
			$book['id'] = $attrs['book'];
			$book['title'] = $attrs['book_title'];
			if(isset($attrs['bar_color'])) { $book['display_bar_color'] = $attrs['bar_color']; }
			if(isset($attrs['cover_image'])) { $book['display_cover_image'] = $attrs['cover_image']; }
			if(isset($attrs['mbt_book'])) { $book['mbt_book'] = $attrs['mbt_book']; }
		}

		if(!empty($book)) {
			$progress_data = null;
			if(isset($attrs['progress']) and is_numeric($attrs['progress'])) {
				$progress_data = array();
				$progress_data['progress'] = $attrs['progress'];
				$progress_data['phase_name'] = isset($attrs['phase_name']) ? $attrs['phase_name'] : null;
				$progress_data['deadline'] = isset($attrs['deadline']) ? $attrs['deadline'] : null;
			}
			return mbp_format_book_progress($book, $options, $progress_data);
		}
	}

	return mbp_format_books_progress($options);
}



/*---------------------------------------------------------*/
/* Shortcode Inserter Information                          */
/*---------------------------------------------------------*/

function mbp_add_authormedia_shortcodes($shortcodes) {
	$books = mbp_get_books();
	$single_books = array();
	$snapshot_data = array();
	foreach($books as $book) {
		$single_books[$book['id']] = mbp_get_book_title($book);
		$snapshot_data[$book['id']] = mbp_get_book_current_progress_data($book);
		$snapshot_data[$book['id']]['book_title'] = mbp_get_book_title($book);
		$snapshot_data[$book['id']]['bar_color'] = $book['display_bar_color'];
		if($book['display_cover_image']) { $snapshot_data[$book['id']]['cover_image'] = $book['display_cover_image']; }
		if($book['mbt_book']) { $snapshot_data[$book['id']]['mbt_book'] = $book['mbt_book']; }
	}

	//Add default shortcodes
	$shortcodes['mybookprogress'] = array('name' => 'MyBookProgress', 'shortcodes' => array(
		'mybookprogress' => array(
			'title'			=> __('All Books', 'mybookprogress'),
			'description'	=> __('Display the progress of all your books.', 'mybookprogress'),
		),
		'mybookprogress-single' => array(
			'title'			=> __('Single Book', 'mybookprogress'),
			'description'	=> __('Display the progress of a single book.', 'mybookprogress'),
			'settings'		=> array(
				'book'	=> array(
					'title'			=> __('Book', 'mybookprogress'),
					'description'	=> '',
					'type'			=> 'dropdown',
					'choices'		=> $single_books,
				),
				'showsubscribe'	=> array(
					'title'			=> __('Show subscribe button', 'mybookprogress'),
					'description'	=> '',
					'type'			=> 'checkbox',
					'default'		=> true,
				),
			)
		),
		'mybookprogress-single-snapshot' => array(
			'title'			=> __('Single Book Snapshot', 'mybookprogress'),
			'description'	=> __('Display a static snapshot of the current progress of a single book.', 'mybookprogress'),
			'settings'		=> array(
				'book'	=> array(
					'title'			=> __('Book', 'mybookprogress'),
					'description'	=> '',
					'type'			=> 'dropdown',
					'choices'		=> $single_books,
				),
			),
			'pre-insert'	=> 'var snapshot_data = '.json_encode($snapshot_data).'; jQuery.extend(attrs, snapshot_data[attrs["book"]]);'
		),
	));

	if(mbp_get_setting('mailinglist_type') == 'mailchimp') {
		$simplesubscribe = array(
			'title'			=> __('Use simple subscribe form', 'mybookprogress'),
			'description'	=> __('This displays a compact subscribe form directly on the page instead of taking the user to a separate subscribe page.', 'mybookprogress'),
			'type'			=> 'checkbox',
			'default'		=> true,
		);
		$shortcodes['mybookprogress']['shortcodes']['mybookprogress']['settings']['simplesubscribe'] = $simplesubscribe;
		$shortcodes['mybookprogress']['shortcodes']['mybookprogress-single']['settings']['simplesubscribe'] = $simplesubscribe;
		$shortcodes['mybookprogress']['shortcodes']['mybookprogress-single-snapshot']['settings']['simplesubscribe'] = $simplesubscribe;
	}

	return $shortcodes;
}



/*---------------------------------------------------------*/
/* Author Media Shortcode Inserter                         */
/*---------------------------------------------------------*/

if(!function_exists('load_authormedia_shortcode_inserter')) {
	add_action('init', 'load_authormedia_shortcode_inserter');
	function load_authormedia_shortcode_inserter() {
		remove_action('admin_init', 'authormedia_setup_shortcode_inserter');
		add_action('admin_init', apply_filters('authormedia_shortcode_inserter_setup_func', '__return_null'));
	}
}

add_filter('authormedia_shortcode_inserter_setup_func', 'mbp_authormedia_shortcode_inserter_setup_func', 1);
function mbp_authormedia_shortcode_inserter_setup_func() {
	return 'mbp_setup_authormedia_shortcode_inserter';
}

function mbp_setup_authormedia_shortcode_inserter() {
	if((current_user_can('edit_posts') || current_user_can('edit_pages')) && get_user_option('rich_editing') == 'true') {
		if(in_array(basename($_SERVER['PHP_SELF']), array('post.php', 'page.php', 'page-new.php', 'post-new.php'))) {
			add_filter('media_buttons', 'mbp_authormedia_shortcode_inserter_button', 30);
			add_action('admin_footer', 'mbp_authormedia_shortcode_inserter_form');
		}
	}
}

function mbp_authormedia_shortcode_inserter_button($buttons) {
	echo '<a href="#TB_inline?width=480&inlineId=authormedia-insert-shortcode" class="thickbox button authormedia-insert-shortcode-button"><span class="authormedia-insert-shortcode-icon"></span>'.__('Insert Shortcode', 'authormedia').'</a>';
}

function mbp_authormedia_shortcode_inserter_form() {
	$shortcode_sections = apply_filters('authormedia_get_shortcodes', array());
	?>
	<script type="text/javascript">
		function authormedia_insert_shortcode() {
			var active_item = jQuery('.authormedia-shortcode-section .shortcode-menu-item.active');
			var shortcode_full = active_item.data('shortcode');
			if(shortcode_full == '') {
				alert('<?php _e("Please select a shortcode.") ?>', 'authormedia');
				return;
			}

			shortcode_tag = shortcode_full.split('-')[0];

			var attrs = {};
			jQuery('#authormedia_shortcode_form_' + shortcode_full + ' .authormedia_shortcode_field').each(function(){
				if('checkbox' == jQuery(this).attr('type')) {
					attrs[jQuery(this).attr('name')] = jQuery(this).is(':checked');
				} else if('radio' == jQuery(this).attr('type')) {
					if(jQuery(this).is(':checked')) { attrs[jQuery(this).attr('name')] = jQuery(this).val(); }
				} else if(jQuery(this).val()) {
					attrs[jQuery(this).attr('name')] = jQuery(this).val();
				}
			});

			if(window.authormedia_shortcode_form_events) {
				window.authormedia_shortcode_form_events.trigger('pre-insert', shortcode_full, attrs);
				window.authormedia_shortcode_form_events.trigger('pre-insert:'+shortcode_full, attrs);
			}

			if(attrs["content"] > "") {
				var setcontent = attrs["content"];
				delete(attrs["content"]);
				shortcode = new wp.shortcode({
					tag: shortcode_tag,
					attrs: attrs,
					type: 'closed',
					content: setcontent
				});
			} else {
				shortcode = new wp.shortcode({
					tag: shortcode_tag,
					attrs: attrs,
					type: 'single'
				});
			}

			if(window.authormedia_shortcode_form_events) {
				window.authormedia_shortcode_form_events.trigger('insert', shortcode_full, shortcode);
				window.authormedia_shortcode_form_events.trigger('insert:'+shortcode_full, shortcode);
			}

			if(window.send_to_editor) {
				window.send_to_editor(shortcode.string());
			}
		}

		jQuery(document).ready(function() {
			jQuery('.shortcode-modal-close').on('click', function(e){
				e.preventDefault();
				tb_remove();
			});

			jQuery('.authormedia-shortcode-section .shortcode-menu-item').on('click', function() {
				jQuery('.authormedia-shortcode-section .shortcode-menu-item').removeClass('active');
				jQuery(this).addClass('active');
				jQuery('.authormedia_shortcode_form_atts').css('display', 'none');
				jQuery('#authormedia_shortcode_form_' + jQuery(this).data('shortcode')).css('display', 'block');
			});

			jQuery('.authormedia-shortcode-section-nav .nav-tab-wrapper a').on('click', function() {
				jQuery('.authormedia-shortcode-section-nav .nav-tab-wrapper a').removeClass('nav-tab-active');
				jQuery(this).addClass('nav-tab-active');
				jQuery('.authormedia-shortcode-section').css('display', 'none');
				jQuery('#authormedia_shortcode_section_' + jQuery(this).attr('data-shortcode-section') ).css('display', 'block');
			});
			jQuery('.authormedia-shortcode-section-nav .nav-tab-wrapper a')[0].click();

			window.authormedia_shortcode_form_events = _.extend({}, Backbone.Events);
		});
	</script>

	<div id="authormedia-insert-shortcode" style="display:none;">
		<div class="authormedia-insert-shortcode-container">
			<a class="media-modal-close shortcode-modal-close" href="#" title="<?php esc_attr_e('Close', 'authormedia'); ?>">
				<span class="media-modal-icon"></span>
			</a>
			<div class="authormedia-shortcode-section-nav">
				<h2 class="nav-tab-wrapper">
					<?php
						foreach($shortcode_sections as $section_id => $section) {
							 echo('<a href="#" class="nav-tab" data-shortcode-section="'.esc_attr($section_id).'">'.$section['name'].'</a>');
						}
					?>
				</h2>
			</div>

			<?php foreach($shortcode_sections as $section_id => $section) { ?>
				<?php $shortcodes = $section['shortcodes']; ?>
				<div class="media-modal-content authormedia-shortcode-section" id="authormedia_shortcode_section_<?php echo(esc_attr($section_id)); ?>">
					<div class="media-frame wp-core-ui">
						<div class="media-frame-menu">
							<div class="media-menu">
								<?php
									foreach ( $shortcodes as $shortcode => $atts ) {
										echo '<a href="#" class="media-menu-item shortcode-menu-item" data-shortcode="' . esc_attr( $shortcode ) . '">' . esc_html( $atts['title'] ) . "</a>";
									}
								?>
							</div>
						</div>
						<div class="media-frame-title">
							<h1><?php _e('Insert a Shortcode', 'authormedia'); ?></h1>
						</div>
						<div class="media-frame-router"></div>
						<div class="media-frame-content">
							<div id="authormedia_shortcode_form_intro" class="authormedia_shortcode_form_atts">
								<?php _e('To get started, select a shortcode from the list on the left.', 'authormedia'); ?>
							</div>
							<?php foreach ( $shortcodes as $shortcode => $atts ): ?>
							<div id="authormedia_shortcode_form_<?php echo $shortcode; ?>" class="authormedia_shortcode_form_atts" style="display:none">
								<?php if ( !empty($atts['description']) ) { ?>
									<div class="authormedia_shortcode_description">
										<?php echo esc_html($atts['description']); ?>
									</div>
								<?php } ?>
								<?php if ( empty($atts['settings']) ) { ?>
									<div style="margin:1em">This shortcode has no options, you can insert it directly.</div>
								<?php } else { ?>
									<?php foreach ( $atts['settings'] as $setting => $params ) {
										echo '<div style="margin:1em">';
										switch ( $params['type'] ) {
											case 'dropdown':
												global $_wp_additional_image_sizes;
												if ( ! empty($params['title']) ) echo "<label for='authormedia_{$shortcode}_field_$setting'>$params[title]</label><br>";
												if ( ! empty($params['choices']) ) {
													echo "<select class='authormedia_shortcode_field' id='authormedia_{$shortcode}_field_$setting' name='$setting' style='max-width:440px;'>";
													foreach ( $params['choices'] as $slug => $name ) {
														echo "<option value='$slug'>$name</option>";
													}
													echo "</select>";
												}
												if ( ! empty($params['description']) ) echo '<div class="description">' . $params['description'] . '</div>';
												break;
											case 'thumbsize':
												global $_wp_additional_image_sizes;
												if ( ! empty($params['title']) ) echo "<label for='authormedia_{$shortcode}_field_$setting'>$params[title]</label><br>";
												echo "<select class='authormedia_shortcode_field' id='authormedia_{$shortcode}_field_$setting' name='$setting'>";
												echo "<option value=''>(default)</option>";
												foreach ( $_wp_additional_image_sizes as $name => $atts ) {
													echo "<option value='$name'>$name ($atts[width] x $atts[height])</option>";
												}
												echo "</select>";
												if ( ! empty($params['description']) ) echo '<div class="description">' . $params['description'] . '</div>';
												break;
											case 'checkboxes':
												#!! we need to output a list of checkboxes and on saving, comma-delimit them
												break;
											case 'checkbox':
												echo "<input type='checkbox' class='authormedia_shortcode_field' id='authormedia_{$shortcode}_field_$setting' name='$setting' ".(empty($params['default']) ? '' : 'checked="checked"').">";
												if ( ! empty($params['title']) ) echo " <label for='authormedia_{$shortcode}_field_$setting'>$params[title]</label>";
												if ( ! empty($params['description']) ) echo '<div class="description">' . $params['description'] . '</div>';
												break;
											case 'radio':
												if ( ! empty($params['title']) ) echo $params['title'];
												if ( ! empty($params['choices']) ) {
													echo '<ul style="margin-left:2em">';
													foreach( $params['choices'] as $key => $value ) {
														echo "<li><input type='radio' class='authormedia_shortcode_field' id='authormedia_{$shortcode}_field_{$setting}_{$key}' name='$setting' value='$key'>";
														echo " <label for='authormedia_{$shortcode}_field_{$setting}_{$key}'>$value</label></li>";
													}
													echo '</ul>';
												}
												if ( ! empty($params['description']) ) echo '<div class="description">' . $params['description'] . '</div>';
												break;
											case 'content':
											case 'textarea':
												if ( ! empty($params['title']) ) {
													echo "<label for='authormedia_{$shortcode}_field_$setting'>$params[title]";
													if ( ! empty($params['default']) ) echo " <em>(default: $params[default])</em>";
													echo "</label><br>";
												}
												echo "<textarea class='authormedia_shortcode_field' id='authormedia_{$shortcode}_field_$setting' name='$setting' rows='5' cols='40'></textarea>";
												if ( ! empty($params['description']) ) echo '<div class="description">' . $params['description'] . '</div>';
												break;
											case 'text':
												if ( ! empty($params['title']) ) {
													echo "<label for='authormedia_{$shortcode}_field_$setting'>$params[title]";
													if ( ! empty($params['default']) ) echo " <em>(default: $params[default])</em>";
													echo "</label><br>";
												}
												echo "<input type='text' class='authormedia_shortcode_field' id='authormedia_{$shortcode}_field_$setting' name='$setting'>";
												if ( ! empty($params['description']) ) echo '<div class="description">' . $params['description'] . '</div>';
												break;
											case 'number':
												if ( ! empty($params['title']) ) {
													echo "<label for='authormedia_{$shortcode}_field_$setting'>$params[title]";
													if ( ! empty($params['default']) ) echo " <em>(default: $params[default])</em>";
													echo "</label><br>";
												}
												echo "<input type='text' class='authormedia_shortcode_field' id='authormedia_{$shortcode}_field_$setting' name='$setting'>";
												if ( ! empty($params['description']) ) echo '<div class="description">' . $params['description'] . '</div>';
												break;
											case '':
											default:
												if ( ! empty($params['title']) ) {
													echo "<label for='authormedia_shortcode_field_$setting'>$params[title]";
													if ( ! empty($params['default']) ) echo " <em>(default: $params[default])</em>";
													echo "</label><br>";
												}
												echo 'input type="' . $params['type'] . '" name="' . $setting . '"';
												if ( ! empty($params['description']) ) echo '<div class="description">' . $params['description'] . '</div>';
										}
										echo '</div>';
									} ?>
								<?php } ?>
								<?php if(!empty($atts['pre-insert'])) { ?>
									<script type="text/javascript">
										jQuery(document).ready(function() { window.authormedia_shortcode_form_events.on('pre-insert:<?php echo($shortcode); ?>', function(attrs) { <?php echo($atts['pre-insert']);?> }); });
									</script>
								<?php } ?>
							</div>
							<?php endforeach; ?>
						</div>
						<div class="media-frame-toolbar"><div class="media-toolbar">
							<div class="media-toolbar-secondary">
								<a class="button media-button button-large button-cancel" style="color:#bbb;" href="#" onclick="tb_remove(); return false;"><?php _e("Cancel", 'authormedia'); ?></a>
							</div>
							<div class="media-toolbar-primary">
								<input type="button" class="button media-button button-primary button-large button-insert" value="<?php _e('Insert Shortcode', 'authormedia'); ?>" onclick="authormedia_insert_shortcode();"/>
							</div>
						</div></div>
					</div>
				</div>
			<?php } ?>
		</div>
	</div>

	<?php
}
