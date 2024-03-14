/* Table of Contents

* jQuery triggers
* mtphr_member_archive
* mtphr_member_title

*/

jQuery( document ).ready( function($) {
	
	var $button = $('.mtphr-shortcodes-modal-submit'); 
	

	/* --------------------------------------------------------- */
	/* !Helper functions - 1.1.0 */
	/* --------------------------------------------------------- */
	
	function html_encode(value){
	  if(value) {
	  	return $('<div />').text(value).html();
	  } else {
	    return '';
	  }
	}
	 
	function html_decode(value) {
	  if(value) {
	  	return $('<div />').html(value).text();
	  } else {
	  	return '';
	  }
	}
	

	/* --------------------------------------------------------- */
	/* !Shortcode generator initialize - 1.1.8 */
	/* --------------------------------------------------------- */

	$('body').on('mtphr_shortcode_generator_init', function() {

		var $container = jQuery('.mtphr-shortcode-gen'),
				shortcode = $container.children('input.shortcode').val();

		switch( shortcode ) {
			case 'mtphr_member_archive':
				mtphr_shortcode_generate_mtphr_member_archive_init( $container );
				break;
			case 'mtphr_member_title':
				mtphr_shortcode_generate_mtphr_member_title_init( $container );
				break;
			case 'mtphr_member_contact_info':
				mtphr_shortcode_generate_mtphr_member_contact_info_init( $container );
				break;
			case 'mtphr_member_social_sites':
				mtphr_shortcode_generate_mtphr_member_social_sites_init( $container );
				break;
			case 'mtphr_member_twitter':
				mtphr_shortcode_generate_mtphr_member_twitter_init( $container );
				break;
		}
	});

	/* --------------------------------------------------------- */
	/* !Shortcode generator trigger - 1.1.0 */
	/* --------------------------------------------------------- */

	$('body').on('mtphr_shortcode_generator_value', function() {

		var $container = jQuery('.mtphr-shortcode-gen'),
				shortcode = $container.children('input.shortcode').val();

		switch( shortcode ) {
			case 'mtphr_member_archive':
				mtphr_shortcode_generate_mtphr_member_archive_value( $container );
				break;
			case 'mtphr_member_title':
				mtphr_shortcode_generate_mtphr_member_title_value( $container );
				break;
			case 'mtphr_member_contact_info':
				mtphr_shortcode_generate_mtphr_member_contact_info_value( $container );
				break;
			case 'mtphr_member_social_sites':
				mtphr_shortcode_generate_mtphr_member_social_sites_value( $container );
				break;
			case 'mtphr_member_twitter':
				mtphr_shortcode_generate_mtphr_member_twitter_value( $container );
				break;
		}
	});



	/* --------------------------------------------------------- */
	/* !mtphr_member_archive init - 1.1.0 */
	/* --------------------------------------------------------- */

	function mtphr_shortcode_generate_mtphr_member_archive_init( $container ) {

		var $taxonomy = $container.find('select[name="taxonomy"]'),
				$tax = $container.find('.mtphr-shortcode-gen-taxonomy'),
				$tax_fields = $container.find('.mtphr-shortcode-gen-taxonomy-fields').hide(),
				$terms = $container.find('.mtphr-shortcode-gen-terms');
				
				
		// Taxonomy change
		$taxonomy.live('change', function() {
		
			if( $(this).val() == '' ) {
				$tax_fields.hide();
			} else {
			
				var data = {
					action: 'mtphr_shortcode_gen_tax_change',
					taxonomy: $(this).val(),
					security: mtphr_shortcodes_generator_vars.security
				};
				jQuery.post( ajaxurl, data, function( response ) {
					$terms.html(response);
				});
				$tax_fields.show();
			}
		});
		
		// Trigger the sorting
		$('.mtphr-shortcode-gen-rearranger').sortable( {
			items: '.mtphr-ui-multi-check'
		});	
		
		setTimeout(function() {
			$button.removeAttr('disabled');
		}, 1000);
	
		$button.removeAttr('disabled');
	}

	/* --------------------------------------------------------- */
	/* !mtphr_member_archive value - 1.1.0 */
	/* --------------------------------------------------------- */

	function mtphr_shortcode_generate_mtphr_member_archive_value( $container ) {

		var $insert = $container.children('input.shortcode-insert'),
				att_posts_per_page = $container.find('input[name="posts_per_page"]').val(),
				att_columns = $container.find('select[name="columns"]').val(),
				att_orderby = $container.find('select[name="orderby"]').val(),
				att_order = $container.find('select[name="order"]').val(),
				att_excerpt_length = $container.find('input[name="excerpt_length"]').val(),
				att_excerpt_more = $container.find('input[name="excerpt_more"]').val(),
				att_more_link = $container.find('input[name="more_link"]').is(':checked'),
				att_taxonomy = $container.find('select[name="taxonomy"]').val(),
				$terms = $container.find('.mtphr-shortcode-gen-term-select'),
				att_operator = $container.find('select[name="operator"]').val(),
				$assets = $container.find('.mtphr-shortcode-gen-assets'),
				value = '[mtphr_members_archive';

		if( att_more_link && att_excerpt_more != '' ) {
			att_excerpt_more = '{'+att_excerpt_more+'}';
		}

		if( att_posts_per_page != '' && att_posts_per_page != 6 ) { value += ' posts_per_page="'+parseInt(att_posts_per_page)+'"'; }
		if( att_columns != '' && att_columns != 3 ) { value += ' columns="'+parseInt(att_columns)+'"'; }
		if( att_orderby != 'menu_order' ) { value += ' orderby="'+att_orderby+'"'; }
		if( att_order != 'DESC' ) { value += ' order="'+att_order+'"'; }
		if( att_excerpt_length != '' ) { value += ' excerpt_length="'+att_excerpt_length+'"'; }
		if( att_excerpt_more != '' ) { value += ' excerpt_more="'+att_excerpt_more+'"'; }
			
		if( att_taxonomy == 'mtphr_member_category' ) {

			// Create the term list	
			var term_list = ''
			$terms.each( function( index ) {
				if( $(this).is(':checked') ) {
					term_list += $(this).val()+',';
				}
			});
			term_list = term_list.substr(0, term_list.length-1);
		
			if( att_taxonomy == 'mtphr_member_category' ) { value += ' categories="'+term_list+'"'; }
			if( att_operator != 'IN' ) { value += ' operator="'+att_operator+'"'; }
		}
		
		if( $assets.length > 0 ) {
			
			// Create the term list	
			var asset_list = ''
			$assets.each( function( index ) {
				if( $(this).is(':checked') ) {
					asset_list += $(this).val()+',';
				}
			});
			asset_list = asset_list.substr(0, asset_list.length-1);
			if( asset_list != '' && asset_list != 'thumbnail,name,info,social,title,excerpt' ) { value += ' assets="'+asset_list+'"'; }
		}
		
		value += ']';

		$insert.val( value );
	}
	
	
	
	/* --------------------------------------------------------- */
	/* !mtphr_member_title init - 1.1.0 */
	/* --------------------------------------------------------- */

	function mtphr_shortcode_generate_mtphr_member_title_init( $container ) {

		var $button = $('.mtphr-shortcode-gen-insert-button'),
				$id = $container.find('select[name="id"]');

		$button.show();
	}

	/* --------------------------------------------------------- */
	/* !mtphr_member value - 1.1.0 */
	/* --------------------------------------------------------- */

	function mtphr_shortcode_generate_mtphr_member_title_value( $container ) {

		var $insert = $container.children('input.shortcode-insert'),
				att_id = $container.find('select[name="id"]').val(),
				att_element = $container.find('input[name="element"]').val(),
				att_before = $container.find('input[name="before"]').val(),
				att_after = $container.find('input[name="after"]').val(),
				att_class = $container.find('input[name="class"]').val(),
				value = '[mtphr_member_title';

		if( att_id != '' ) { value += ' id="'+parseInt(att_id)+'"'; }
		if( att_element != '' ) { value += ' element="'+att_element+'"'; }
		if( att_before != '' ) { value += ' before="'+html_encode(att_before)+'"'; }
		if( att_after != '' ) { value += ' after="'+html_encode(att_after)+'"'; }
		if( att_class != '' ) { value += ' class="'+att_class+'"'; }
		value += ']';

		$insert.val( value );
	}
	
	
	
	/* --------------------------------------------------------- */
	/* !mtphr_member_contact_info init - 1.1.0 */
	/* --------------------------------------------------------- */

	function mtphr_shortcode_generate_mtphr_member_contact_info_init( $container ) {

		var $button = $('.mtphr-shortcode-gen-insert-button'),
				$title = $container.find('input[name="title"]'),
				$title_element = $container.find('.mtphr-shortcode-gen-attribute-title_element').hide();

		$title.live('keyup', function() {
			if( $(this).val() == '' ) {
				$title_element.slideUp();
			} else {
				$title_element.slideDown();
			}
		});

		$button.show();
	}

	/* --------------------------------------------------------- */
	/* !mtphr_member_contact_info value - 1.1.0 */
	/* --------------------------------------------------------- */

	function mtphr_shortcode_generate_mtphr_member_contact_info_value( $container ) {

		var $insert = $container.children('input.shortcode-insert'),
				att_id = $container.find('select[name="id"]').val(),
				att_title = $container.find('input[name="title"]').val(),
				att_title_element_visible = $container.find('input[name="title_element"]').is(':visible');
				att_title_element = $container.find('input[name="title_element"]').val(),
				att_class = $container.find('input[name="class"]').val(),
				value = '[mtphr_member_contact_info';

		if( att_id != '' ) { value += ' id="'+parseInt(att_id)+'"'; }
		if( att_title != '' ) { value += ' title="'+att_title+'"'; }
		if( att_title_element != '' && att_title_element_visible ) { value += ' title_element="'+att_title_element+'"'; }
		if( att_class != '' ) { value += ' class="'+att_class+'"'; }
		value += ']';

		$insert.val( value );
	}
	
	
	
	/* --------------------------------------------------------- */
	/* !mtphr_member_social_sites init - 1.1.0 */
	/* --------------------------------------------------------- */

	function mtphr_shortcode_generate_mtphr_member_social_sites_init( $container ) {

		var $button = $('.mtphr-shortcode-gen-insert-button'),
				$title = $container.find('input[name="title"]'),
				$title_element = $container.find('.mtphr-shortcode-gen-attribute-title_element').hide();

		$title.live('keyup', function() {
			if( $(this).val() == '' ) {
				$title_element.slideUp();
			} else {
				$title_element.slideDown();
			}
		});

		$button.show();
	}

	/* --------------------------------------------------------- */
	/* !mtphr_member_social_sites value - 1.1.0 */
	/* --------------------------------------------------------- */

	function mtphr_shortcode_generate_mtphr_member_social_sites_value( $container ) {

		var $insert = $container.children('input.shortcode-insert'),
				att_id = $container.find('select[name="id"]').val(),
				att_title = $container.find('input[name="title"]').val(),
				att_title_element_visible = $container.find('input[name="title_element"]').is(':visible');
				att_title_element = $container.find('input[name="title_element"]').val(),
				att_class = $container.find('input[name="class"]').val(),
				value = '[mtphr_member_social_sites';

		if( att_id != '' ) { value += ' id="'+parseInt(att_id)+'"'; }
		if( att_title != '' ) { value += ' title="'+att_title+'"'; }
		if( att_title_element != '' && att_title_element_visible ) { value += ' title_element="'+att_title_element+'"'; }
		if( att_class != '' ) { value += ' class="'+att_class+'"'; }
		value += ']';

		$insert.val( value );
	}
	
	
	
	/* --------------------------------------------------------- */
	/* !mtphr_member_twitter init - 1.1.0 */
	/* --------------------------------------------------------- */

	function mtphr_shortcode_generate_mtphr_member_twitter_init( $container ) {

		var $button = $('.mtphr-shortcode-gen-insert-button'),
				$title = $container.find('input[name="title"]'),
				$title_element = $container.find('.mtphr-shortcode-gen-attribute-title_element').hide();

		$title.live('keyup', function() {
			if( $(this).val() == '' ) {
				$title_element.slideUp();
			} else {
				$title_element.slideDown();
			}
		});

		$button.show();
	}

	/* --------------------------------------------------------- */
	/* !mtphr_member_twitter value - 1.1.0 */
	/* --------------------------------------------------------- */

	function mtphr_shortcode_generate_mtphr_member_twitter_value( $container ) {

		var $insert = $container.children('input.shortcode-insert'),
				att_id = $container.find('select[name="id"]').val(),
				att_title = $container.find('input[name="title"]').val(),
				att_title_element_visible = $container.find('input[name="title_element"]').is(':visible');
				att_title_element = $container.find('input[name="title_element"]').val(),
				att_limit = $container.find('input[name="limit"]').val(),
				att_image = $container.find('input[name="image"]:checked').val(),
				att_class = $container.find('input[name="class"]').val(),
				value = '[mtphr_member_twitter';

		if( att_id != '' ) { value += ' id="'+parseInt(att_id)+'"'; }
		if( att_title != '' ) { value += ' title="'+att_title+'"'; }
		if( att_title_element != '' && att_title_element_visible ) { value += ' title_element="'+att_title_element+'"'; }
		if( att_limit != '' ) { value += ' limit="'+parseInt(att_limit)+'"'; }
		if( att_image != '' ) {
			if( att_image == 'image' ) { value += ' image="true"'; }
			if( att_image == 'avatar' ) { value += ' avatar="true"'; }
		}
		if( att_class != '' ) { value += ' class="'+att_class+'"'; }
		value += ']';

		$insert.val( value );
	}
	
	
	


});