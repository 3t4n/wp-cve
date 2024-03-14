<?php

/**
 * Glossary
 *
 * @package   Glossary
 * @author    Codeat <support@codeat.co>
 * @copyright 2020
 * @license   GPL 2.0+
 * @link      https://codeat.co
 */

namespace Glossary\Integrations\CMB_Fields;

use Glossary\Engine;

/**
 * CMB2 Number text field
 */
class CMB2_Post_Search_Field extends Engine\Base {

	/**
	 * Initialize the class.
	 *
	 * @return void
	 */
	public function initialize() {
		parent::initialize();

		\add_action( 'cmb2_render_post_search_text', array( $this, 'render' ), 10, 5 );
		\add_action( 'cmb2_post_search_field_add_find_posts_div', array( $this, 'add_find_posts_div' ) );
		\add_action( 'admin_init', array( $this, 'wp_ajax_find_posts' ) );
	}

	public function render( $field, $escaped_value, $object_id, $object_type, $field_type ) {
		$select_type = $field->args( 'select_type' );

		echo $field_type->input(// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			array(
				'data-posttype'   => $field->args( 'post_type' ),
				'data-selecttype' => 'radio' === $select_type ? 'radio' : 'checkbox', // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			'data-onlyone'        => $field->args( 'onlyone' ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			'autocomplete'        => 'off',
			'style'               => 'display:none',
		)
			);
		echo '<ul style="cursor:move">';

		if ( !empty( $field->escaped_value ) ) {
			$list = \explode( ',', $field->escaped_value );

			foreach ( $list as $value ) {
				echo '<li data-id="' , \trim( $value ) , '"><b>' , \__( 'Title', 'glossary-by-codeat' ) , ':</b> ' , \get_the_title( (string) $value );// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo '<div title="' , \__( 'Remove', 'glossary-by-codeat' ) , '" style="color: #999;margin: -0.1em 0 0 2px; cursor: pointer;" class="cmb-post-search-remove dashicons dashicons-no"></div>';// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo '</li>';
			}
		}

		echo '</ul>';

		// JS needed for modal
		// wp_enqueue_media();
		\wp_enqueue_script( 'jquery' );
		\wp_enqueue_script( 'wp-backbone' );
		\wp_enqueue_script( 'jquery-ui-sortable' );

		if ( !\is_admin() ) {
			// Will need custom styling!
			require_once ABSPATH . 'wp-admin/includes/template.php';
			\do_action( 'cmb2_post_search_field_add_find_posts_div' );
		}

		// markup needed for modal
		\add_action( 'admin_footer', 'find_posts_div' );

		$error = \__( 'An error has occurred. Please reload the page and try again.', 'glossary-by-codeat' );
		$find  = \__( 'Find Posts or Pages', 'glossary-by-codeat' );

		$search_id           = \str_replace( '-', '_', $field->args( 'id' ) );
		$search_id_sanitized = \str_replace( '_', '-', \sanitize_html_class( $field->args( 'id' ) ) );
		?>
		<script type="text/javascript">
		jQuery(document).ready(function ($) {
			'use strict';

			var l10n = {
			'error': '<?php echo \esc_js( $error ); ?>',
				'find': '<?php echo \esc_js( $find ); ?>'
	};

	var SearchView<?php echo $search_id; // phpcs:ignore ?> = window.Backbone.View.extend({
	el: '#find-posts',
		overlaySet: false,
		$overlay: false,
		$idInput: false,
		$checked: false,
		$checkedLabel: false,
		events: {
		'keypress .find-box-search :input': 'maybeStartSearch',
			'keyup #find-posts-input': 'escClose',
			'click #find-posts-submit': 'selectPost',
			'click #find-posts-search': 'send',
			'click #find-posts-close': 'close'
	},
		initialize: function () {
			this.$spinner = this.$el.find('.find-box-search .spinner');
			this.$input = this.$el.find('#find-posts-input');
			this.$response = this.$el.find('#find-posts-response');
			this.$overlay = $('#find-posts-ui-find-overlay');

			this.listenTo(this, 'open', this.open);
			this.listenTo(this, 'close', this.close);
	},
		escClose: function (evt) {
			if (evt.which && 27 === evt.which) {
				this.close();
	}
	},
		close: function () {
			this.$overlay.hide();
			this.$el.hide();
	},
		open: function () {
			this.$response.html('');

			this.$el.show();

			this.$input.focus();

			if (!jQuery('.ui-find-overlay').length) {
				$('body').append('<div id="find-posts-ui-find-overlay" class="ui-find-overlay"></div>');
	}
	this.$overlay = $('#find-posts-ui-find-overlay');

	this.$overlay.show();

	// Pull some results up by default
	this.send();

	return false;
	},
		maybeStartSearch: function (evt) {
			if (13 === evt.which) {
				this.send();
				return false;
	}
	},
		send: function () {

			var search = this;
			search.$spinner.show();

			$.ajax(ajaxurl, {
			type: 'POST',
				dataType: 'json',
				data: {
				ps: search.$input.val(),
					action: 'find_posts',
					cmb2_post_search: true,
					post_search_cpt: search.postType,
					_ajax_nonce: $('.find-box-search:visible input[name="_ajax_nonce"]').val()
	}
	}).always(function () {

		search.$spinner.hide();

	}).done(function (response) {

		if (!response.success) {
			search.$response.text(l10n.error);
	}

	var data = response.data;

	if ('checkbox' === search.selectType) {
		data = data.replace(/type="radio"/gi, 'type="checkbox"');
	}

	search.$response.html(data);

	}).fail(function () {
		search.$response.text(l10n.error);
	});
	},
		selectPost: function (evt) {
			evt.preventDefault();

			this.$checked = $('#find-posts-response input[type="' + this.selectType + '"]:checked');

			var checked = this.$checked.map(function () {
				return this.value;
	}).get();

	if (!checked.length) {
		this.close();
		return;
	}

	var label = [];
	$.each(checked, function (index, value) {
		label.push($('#find-posts-response label[for="found-' + value + '"]').html());
	});
	this.$checkedLabel = label;
	this.handleSelected(checked);
	},
		handleSelected: function (checked) {
			var existing = this.$idInput.val();
			var search = window.cmb2_post_search<?php echo $search_id; // phpcs:ignore ?>;
			if (search.$idInput.data('onlyone')) {
				$('.cmb-type-post-search-text.cmb2-id-<?php echo $search_id_sanitized; // phpcs:ignore ?> ul').empty();
				existing = '';
	}
	existing = existing ? existing + ', ' : '';
	var newids = checked.join(', ');
	var ids = existing + newids;
	this.$idInput.val(ids);
	var labels = this.$checkedLabel;
	if (newids.indexOf(',') !== -1) {
		ids = newids.split(',');
		$.each(ids, function (index, value) {
			var cleaned = value.trim().toString();
			if ($('.cmb-type-post-search-text.cmb2-id-<?php echo $search_id_sanitized; // phpcs:ignore ?> ul li[data-id="' + cleaned + '"]').length === 0) {
				$('.cmb-type-post-search-text.cmb2-id-<?php echo $search_id_sanitized; // phpcs:ignore ?> ul').append('<li data-id="' + cleaned + '"><b><?php _e( 'Title' ); ?>:</b> ' + labels[index] + '<div title="<?php _e( 'Remove' ); ?>" style="color: #999;margin: -0.1em 0 0 2px; cursor: pointer;" class="cmb-post-search-remove dashicons dashicons-no"></div></li>');
	}
	});
	} else {
		if ($('.cmb-type-post-search-text.cmb2-id-<?php echo $search_id_sanitized; // phpcs:ignore ?> ul li[data-id="' + newids + '"]').length === 0) {
			$('.cmb-type-post-search-text.cmb2-id-<?php echo $search_id_sanitized; // phpcs:ignore ?> ul').append('<li data-id="' + newids + '"><b><?php _e( 'Title' ); ?>:</b> ' + this.$checkedLabel[0] + '<div title="<?php _e( 'Remove' ); ?>" style="color: #999;margin: -0.1em 0 0 2px; cursor: pointer;" class="cmb-post-search-remove dashicons dashicons-no"></div></li>');
	}
	}

	this.close();
	}

	});

	window.cmb2_post_search<?php echo $search_id; ?> = new SearchView<?php echo $search_id; // phpcs:ignore ?>();

	$('.cmb-type-post-search-text.cmb2-id-<?php echo $search_id_sanitized; // phpcs:ignore ?> .cmb-th label').after('<div title="' + l10n.find + '" style="position:relative;left:30%;color: #999;cursor: pointer;" class="dashicons dashicons-search"></div>');

	$('.cmb-type-post-search-text.cmb2-id-<?php echo $search_id_sanitized; // phpcs:ignore ?> .cmb-th .dashicons-search').on('click', openSearch);

	function openSearch(evt) {
		var search = window.cmb2_post_search<?php echo $search_id; // phpcs:ignore ?>;
		search.$idInput = $(evt.currentTarget).parents('.cmb-type-post-search-text.cmb2-id-<?php echo $search_id_sanitized; // phpcs:ignore ?>').find('.cmb-td input[type="text"]');
		search.postType = search.$idInput.data('posttype');
		search.selectType = 'radio' === search.$idInput.data('selecttype') ? 'radio' : 'checkbox';

		search.trigger('open');
	}

	$('.cmb-type-post-search-text').on('click', '.cmb-post-search-remove', function () {
		var ids = $('.cmb-type-post-search-text.cmb2-id-<?php echo $search_id_sanitized; // phpcs:ignore ?>').find('.cmb-td input[type="text"]').val();
		var $choosen = $(this);
		if (ids.indexOf(',') !== -1) {
			ids = ids.split(',');
			var loopids = ids.slice(0);
			$.each(loopids, function (index, value) {
				var cleaned = value.trim().toString();
				if (String($choosen.parent().data('id')) === cleaned) {
					$choosen.parent().remove();
					ids.splice(index, 1);
	}
	});
	$('.cmb-type-post-search-text.cmb2-id-<?php echo $search_id_sanitized; // phpcs:ignore ?>').find('.cmb-td input[type="text"]').val(ids.join(','));
	} else {
		$choosen.parent().remove();
		$('.cmb-type-post-search-text.cmb2-id-<?php echo $search_id_sanitized; // phpcs:ignore ?>').find('.cmb-td input[type="text"]').val('');
	}
	});

	$(".cmb-type-post-search-text.cmb2-id-<?php echo $search_id_sanitized; // phpcs:ignore ?> ul").sortable({
	update: function (event, ui) {
		var ids = [];
		var search = window.cmb2_post_search<?php echo $field->args( 'id' ); // phpcs:ignore?>;
		if (search.$idInput.data('onlyone')) {
			$('.cmb-type-post-search-text.cmb2-id-<?php echo $search_id_sanitized; // phpcs:ignore ?> ul li:first').each(function (index, value) {
				ids.push($(this).data('id'));
	});
	} else {
		$('.cmb-type-post-search-text.cmb2-id-<?php echo $search_id_sanitized; // phpcs:ignore ?> ul li').each(function (index, value) {
			ids.push($(this).data('id'));
	});
	}
	$('.cmb-type-post-search-text.cmb2-id-<?php echo $search_id_sanitized; // phpcs:ignore ?>').find('.cmb-td input[type="text"]').val(ids.join(', '));
	}
	});

	});
	</script>
		<?php
	}

	/**
	 * Add the find posts div via a hook so we can relocate it manually
	 */
	public function add_find_posts_div() {
		\add_action( 'wp_footer', 'find_posts_div' );
	}

	/**
	 * Check to see if we have a post type set and, if so, add the
	 * pre_get_posts action to set the queried post type
	 */
	public function wp_ajax_find_posts() {
		if (
			!\defined( 'DOING_AJAX' ) || !DOING_AJAX || !isset( $_POST[ 'cmb2_post_search' ], $_POST[ 'action' ], $_POST[ 'post_search_cpt' ] ) || 'find_posts' !== $_POST[ 'action' ] || empty( $_POST[ 'post_search_cpt' ] )//phpcs:ignore WordPress.Security.NonceVerification
		) {
			return;
		}

		\add_action( 'pre_get_posts', array( $this, 'set_post_type' ) );
	}

	/**
	 * Set the post type via pre_get_posts
	 *
	 * @param object $query  The query instance.
	 */
	public function set_post_type( $query ) {
		$query->set( 'post_type', \esc_attr( $_POST[ 'post_search_cpt' ] ) ); //phpcs:ignore WordPress.Security
	}

}
