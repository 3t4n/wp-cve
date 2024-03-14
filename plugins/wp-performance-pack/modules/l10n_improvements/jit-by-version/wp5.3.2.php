<?php
/**
 * JIT localization of scripts using LabelsObjects
 * A modified copy of wp_default_scripts from wp-includes/script-loader.php
 *
 * @author Björn Ahrens
 * @since 2.0.0
 */
 
function wp_jit_default_scripts( &$scripts ) {
	$suffix     = wp_scripts_get_suffix();
	$dev_suffix = wp_scripts_get_suffix( 'dev' );
	$guessurl   = site_url();

	if ( ! $guessurl ) {
		$guessed_url = true;
		$guessurl    = wp_guess_url();
	}

	$scripts->base_url        = $guessurl;
	$scripts->content_url     = defined( 'WP_CONTENT_URL' ) ? WP_CONTENT_URL : '';
	$scripts->default_version = get_bloginfo( 'version' );
	$scripts->default_dirs    = array( '/wp-admin/js/', '/wp-includes/js/' );

	$scripts->add( 'utils', "/wp-includes/js/utils$suffix.js" );
	did_action( 'init' ) && $scripts->localize(
		'utils',
		'userSettings',
		array(
			'url'    => (string) SITECOOKIEPATH,
			'uid'    => (string) get_current_user_id(),
			'time'   => (string) time(),
			'secure' => (string) ( 'https' === parse_url( site_url(), PHP_URL_SCHEME ) ),
		)
	);

	$scripts->add( 'common', "/wp-admin/js/common$suffix.js", array( 'jquery', 'hoverIntent', 'utils' ), false, 1 );
	did_action( 'init' ) && $scripts->localize(
		'common',
		'commonL10n',
		new LabelsObject( '__', array(
			'warnDelete'   => "You are about to permanently delete these items from your site.\nThis action cannot be undone.\n 'Cancel' to stop, 'OK' to delete.",
			'dismiss'      => 'Dismiss this notice.',
			'collapseMenu' => 'Collapse Main menu',
			'expandMenu'   => 'Expand Main menu',
		) )
	);

	$scripts->add( 'wp-sanitize', "/wp-includes/js/wp-sanitize$suffix.js", array(), false, 1 );

	$scripts->add( 'sack', "/wp-includes/js/tw-sack$suffix.js", array(), '1.6.1', 1 );

	$scripts->add( 'quicktags', "/wp-includes/js/quicktags$suffix.js", array(), false, 1 );
	did_action( 'init' ) && $scripts->localize(
		'quicktags',
		'quicktagsL10n',
		new LabelsObject( '__', array(
			'closeAllOpenTags'      => 'Close all open tags',
			'closeTags'             => 'close tags',
			'enterURL'              => 'Enter the URL',
			'enterImageURL'         => 'Enter the URL of the image',
			'enterImageDescription' => 'Enter a description of the image',
			'textdirection'         => 'text direction',
			'toggleTextdirection'   => 'Toggle Editor Text Direction',
			'dfw'                   => 'Distraction-free writing mode',
			'strong'                => 'Bold',
			'strongClose'           => 'Close bold tag',
			'em'                    => 'Italic',
			'emClose'               => 'Close italic tag',
			'link'                  => 'Insert link',
			'blockquote'            => 'Blockquote',
			'blockquoteClose'       => 'Close blockquote tag',
			'del'                   => 'Deleted text (strikethrough)',
			'delClose'              => 'Close deleted text tag',
			'ins'                   => 'Inserted text',
			'insClose'              => 'Close inserted text tag',
			'image'                 => 'Insert image',
			'ul'                    => 'Bulleted list',
			'ulClose'               => 'Close bulleted list tag',
			'ol'                    => 'Numbered list',
			'olClose'               => 'Close numbered list tag',
			'li'                    => 'List item',
			'liClose'               => 'Close list item tag',
			'code'                  => 'Code',
			'codeClose'             => 'Close code tag',
			'more'                  => 'Insert Read More tag',
		) )
	);

	$scripts->add( 'colorpicker', "/wp-includes/js/colorpicker$suffix.js", array( 'prototype' ), '3517m' );

	$scripts->add( 'editor', "/wp-admin/js/editor$suffix.js", array( 'utils', 'jquery' ), false, 1 );

	$scripts->add( 'clipboard', "/wp-includes/js/clipboard$suffix.js", array(), false, 1 );

	// Back-compat for old DFW. To-do: remove at the end of 2016.
	$scripts->add( 'wp-fullscreen-stub', "/wp-admin/js/wp-fullscreen-stub$suffix.js", array(), false, 1 );

	$scripts->add( 'wp-ajax-response', "/wp-includes/js/wp-ajax-response$suffix.js", array( 'jquery' ), false, 1 );
	did_action( 'init' ) && $scripts->localize(
		'wp-ajax-response',
		'wpAjax',
		new LabelsObject( '__', array(
			'noPerm' => 'Sorry, you are not allowed to do that.',
			'broken' => 'Something went wrong.',
		) )
	);

	$scripts->add( 'wp-api-request', "/wp-includes/js/api-request$suffix.js", array( 'jquery' ), false, 1 );
	// `wpApiSettings` is also used by `wp-api`, which depends on this script.
	did_action( 'init' ) && $scripts->localize(
		'wp-api-request',
		'wpApiSettings',
		array(
			'root'          => esc_url_raw( get_rest_url() ),
			'nonce'         => ( wp_installing() && ! is_multisite() ) ? '' : wp_create_nonce( 'wp_rest' ),
			'versionString' => 'wp/v2/',
		)
	);

	$scripts->add( 'wp-pointer', "/wp-includes/js/wp-pointer$suffix.js", array( 'jquery-ui-widget', 'jquery-ui-position' ), '20111129a', 1 );
	did_action( 'init' ) && $scripts->localize(
		'wp-pointer',
		'wpPointerL10n',
		new LabelsObject( '__', array(
			'dismiss' => 'Dismiss',
		) )
	);

	$scripts->add( 'autosave', "/wp-includes/js/autosave$suffix.js", array( 'heartbeat' ), false, 1 );

	$scripts->add( 'heartbeat', "/wp-includes/js/heartbeat$suffix.js", array( 'jquery', 'wp-hooks' ), false, 1 );
	did_action( 'init' ) && $scripts->localize(
		'heartbeat',
		'heartbeatSettings',
		/**
		 * Filters the Heartbeat settings.
		 *
		 * @since 3.6.0
		 *
		 * @param array $settings Heartbeat settings array.
		 */
		apply_filters( 'heartbeat_settings', array() )
	);

	$scripts->add( 'wp-auth-check', "/wp-includes/js/wp-auth-check$suffix.js", array( 'heartbeat' ), false, 1 );
	did_action( 'init' ) && $scripts->localize(
		'wp-auth-check',
		'authcheckL10n',
		new LabelsObject( '__', array(
			'beforeunload' => 'Your session has expired. You can log in again from this page or go to the login page.',

			/**
			 * Filters the authentication check interval.
			 *
			 * @since 3.6.0
			 *
			 * @param int $interval The interval in which to check a user's authentication.
			 *                      Default 3 minutes in seconds, or 180.
			 */
			'interval'     => array( 'apply_filters', 'wp_auth_check_interval', 3 * MINUTE_IN_SECONDS ),
		) )
	);

	$scripts->add( 'wp-lists', "/wp-includes/js/wp-lists$suffix.js", array( 'wp-ajax-response', 'jquery-color' ), false, 1 );

	// WordPress no longer uses or bundles Prototype or script.aculo.us. These are now pulled from an external source.
	$scripts->add( 'prototype', 'https://ajax.googleapis.com/ajax/libs/prototype/1.7.1.0/prototype.js', array(), '1.7.1' );
	$scripts->add( 'scriptaculous-root', 'https://ajax.googleapis.com/ajax/libs/scriptaculous/1.9.0/scriptaculous.js', array( 'prototype' ), '1.9.0' );
	$scripts->add( 'scriptaculous-builder', 'https://ajax.googleapis.com/ajax/libs/scriptaculous/1.9.0/builder.js', array( 'scriptaculous-root' ), '1.9.0' );
	$scripts->add( 'scriptaculous-dragdrop', 'https://ajax.googleapis.com/ajax/libs/scriptaculous/1.9.0/dragdrop.js', array( 'scriptaculous-builder', 'scriptaculous-effects' ), '1.9.0' );
	$scripts->add( 'scriptaculous-effects', 'https://ajax.googleapis.com/ajax/libs/scriptaculous/1.9.0/effects.js', array( 'scriptaculous-root' ), '1.9.0' );
	$scripts->add( 'scriptaculous-slider', 'https://ajax.googleapis.com/ajax/libs/scriptaculous/1.9.0/slider.js', array( 'scriptaculous-effects' ), '1.9.0' );
	$scripts->add( 'scriptaculous-sound', 'https://ajax.googleapis.com/ajax/libs/scriptaculous/1.9.0/sound.js', array( 'scriptaculous-root' ), '1.9.0' );
	$scripts->add( 'scriptaculous-controls', 'https://ajax.googleapis.com/ajax/libs/scriptaculous/1.9.0/controls.js', array( 'scriptaculous-root' ), '1.9.0' );
	$scripts->add( 'scriptaculous', false, array( 'scriptaculous-dragdrop', 'scriptaculous-slider', 'scriptaculous-controls' ) );

	// not used in core, replaced by Jcrop.js
	$scripts->add( 'cropper', '/wp-includes/js/crop/cropper.js', array( 'scriptaculous-dragdrop' ) );

	// jQuery
	$scripts->add( 'jquery', false, array( 'jquery-core', 'jquery-migrate' ), '1.12.4-wp' );
	$scripts->add( 'jquery-core', '/wp-includes/js/jquery/jquery.js', array(), '1.12.4-wp' );
	$scripts->add( 'jquery-migrate', "/wp-includes/js/jquery/jquery-migrate$suffix.js", array(), '1.4.1' );

	// full jQuery UI
	$scripts->add( 'jquery-ui-core', "/wp-includes/js/jquery/ui/core$dev_suffix.js", array( 'jquery' ), '1.11.4', 1 );
	$scripts->add( 'jquery-effects-core', "/wp-includes/js/jquery/ui/effect$dev_suffix.js", array( 'jquery' ), '1.11.4', 1 );

	$scripts->add( 'jquery-effects-blind', "/wp-includes/js/jquery/ui/effect-blind$dev_suffix.js", array( 'jquery-effects-core' ), '1.11.4', 1 );
	$scripts->add( 'jquery-effects-bounce', "/wp-includes/js/jquery/ui/effect-bounce$dev_suffix.js", array( 'jquery-effects-core' ), '1.11.4', 1 );
	$scripts->add( 'jquery-effects-clip', "/wp-includes/js/jquery/ui/effect-clip$dev_suffix.js", array( 'jquery-effects-core' ), '1.11.4', 1 );
	$scripts->add( 'jquery-effects-drop', "/wp-includes/js/jquery/ui/effect-drop$dev_suffix.js", array( 'jquery-effects-core' ), '1.11.4', 1 );
	$scripts->add( 'jquery-effects-explode', "/wp-includes/js/jquery/ui/effect-explode$dev_suffix.js", array( 'jquery-effects-core' ), '1.11.4', 1 );
	$scripts->add( 'jquery-effects-fade', "/wp-includes/js/jquery/ui/effect-fade$dev_suffix.js", array( 'jquery-effects-core' ), '1.11.4', 1 );
	$scripts->add( 'jquery-effects-fold', "/wp-includes/js/jquery/ui/effect-fold$dev_suffix.js", array( 'jquery-effects-core' ), '1.11.4', 1 );
	$scripts->add( 'jquery-effects-highlight', "/wp-includes/js/jquery/ui/effect-highlight$dev_suffix.js", array( 'jquery-effects-core' ), '1.11.4', 1 );
	$scripts->add( 'jquery-effects-puff', "/wp-includes/js/jquery/ui/effect-puff$dev_suffix.js", array( 'jquery-effects-core', 'jquery-effects-scale' ), '1.11.4', 1 );
	$scripts->add( 'jquery-effects-pulsate', "/wp-includes/js/jquery/ui/effect-pulsate$dev_suffix.js", array( 'jquery-effects-core' ), '1.11.4', 1 );
	$scripts->add( 'jquery-effects-scale', "/wp-includes/js/jquery/ui/effect-scale$dev_suffix.js", array( 'jquery-effects-core', 'jquery-effects-size' ), '1.11.4', 1 );
	$scripts->add( 'jquery-effects-shake', "/wp-includes/js/jquery/ui/effect-shake$dev_suffix.js", array( 'jquery-effects-core' ), '1.11.4', 1 );
	$scripts->add( 'jquery-effects-size', "/wp-includes/js/jquery/ui/effect-size$dev_suffix.js", array( 'jquery-effects-core' ), '1.11.4', 1 );
	$scripts->add( 'jquery-effects-slide', "/wp-includes/js/jquery/ui/effect-slide$dev_suffix.js", array( 'jquery-effects-core' ), '1.11.4', 1 );
	$scripts->add( 'jquery-effects-transfer', "/wp-includes/js/jquery/ui/effect-transfer$dev_suffix.js", array( 'jquery-effects-core' ), '1.11.4', 1 );

	$scripts->add( 'jquery-ui-accordion', "/wp-includes/js/jquery/ui/accordion$dev_suffix.js", array( 'jquery-ui-core', 'jquery-ui-widget' ), '1.11.4', 1 );
	$scripts->add( 'jquery-ui-autocomplete', "/wp-includes/js/jquery/ui/autocomplete$dev_suffix.js", array( 'jquery-ui-menu', 'wp-a11y' ), '1.11.4', 1 );
	$scripts->add( 'jquery-ui-button', "/wp-includes/js/jquery/ui/button$dev_suffix.js", array( 'jquery-ui-core', 'jquery-ui-widget' ), '1.11.4', 1 );
	$scripts->add( 'jquery-ui-datepicker', "/wp-includes/js/jquery/ui/datepicker$dev_suffix.js", array( 'jquery-ui-core' ), '1.11.4', 1 );
	$scripts->add( 'jquery-ui-dialog', "/wp-includes/js/jquery/ui/dialog$dev_suffix.js", array( 'jquery-ui-resizable', 'jquery-ui-draggable', 'jquery-ui-button', 'jquery-ui-position' ), '1.11.4', 1 );
	$scripts->add( 'jquery-ui-draggable', "/wp-includes/js/jquery/ui/draggable$dev_suffix.js", array( 'jquery-ui-mouse' ), '1.11.4', 1 );
	$scripts->add( 'jquery-ui-droppable', "/wp-includes/js/jquery/ui/droppable$dev_suffix.js", array( 'jquery-ui-draggable' ), '1.11.4', 1 );
	$scripts->add( 'jquery-ui-menu', "/wp-includes/js/jquery/ui/menu$dev_suffix.js", array( 'jquery-ui-core', 'jquery-ui-widget', 'jquery-ui-position' ), '1.11.4', 1 );
	$scripts->add( 'jquery-ui-mouse', "/wp-includes/js/jquery/ui/mouse$dev_suffix.js", array( 'jquery-ui-core', 'jquery-ui-widget' ), '1.11.4', 1 );
	$scripts->add( 'jquery-ui-position', "/wp-includes/js/jquery/ui/position$dev_suffix.js", array( 'jquery' ), '1.11.4', 1 );
	$scripts->add( 'jquery-ui-progressbar', "/wp-includes/js/jquery/ui/progressbar$dev_suffix.js", array( 'jquery-ui-core', 'jquery-ui-widget' ), '1.11.4', 1 );
	$scripts->add( 'jquery-ui-resizable', "/wp-includes/js/jquery/ui/resizable$dev_suffix.js", array( 'jquery-ui-mouse' ), '1.11.4', 1 );
	$scripts->add( 'jquery-ui-selectable', "/wp-includes/js/jquery/ui/selectable$dev_suffix.js", array( 'jquery-ui-mouse' ), '1.11.4', 1 );
	$scripts->add( 'jquery-ui-selectmenu', "/wp-includes/js/jquery/ui/selectmenu$dev_suffix.js", array( 'jquery-ui-menu' ), '1.11.4', 1 );
	$scripts->add( 'jquery-ui-slider', "/wp-includes/js/jquery/ui/slider$dev_suffix.js", array( 'jquery-ui-mouse' ), '1.11.4', 1 );
	$scripts->add( 'jquery-ui-sortable', "/wp-includes/js/jquery/ui/sortable$dev_suffix.js", array( 'jquery-ui-mouse' ), '1.11.4', 1 );
	$scripts->add( 'jquery-ui-spinner', "/wp-includes/js/jquery/ui/spinner$dev_suffix.js", array( 'jquery-ui-button' ), '1.11.4', 1 );
	$scripts->add( 'jquery-ui-tabs', "/wp-includes/js/jquery/ui/tabs$dev_suffix.js", array( 'jquery-ui-core', 'jquery-ui-widget' ), '1.11.4', 1 );
	$scripts->add( 'jquery-ui-tooltip', "/wp-includes/js/jquery/ui/tooltip$dev_suffix.js", array( 'jquery-ui-core', 'jquery-ui-widget', 'jquery-ui-position' ), '1.11.4', 1 );
	$scripts->add( 'jquery-ui-widget', "/wp-includes/js/jquery/ui/widget$dev_suffix.js", array( 'jquery' ), '1.11.4', 1 );

	// Strings for 'jquery-ui-autocomplete' live region messages
	did_action( 'init' ) && $scripts->localize(
		'jquery-ui-autocomplete',
		'uiAutocompleteL10n',
		new LabelsObject( '__', array(
			'noResults'    => 'No results found.',
			/* translators: Number of results found when using jQuery UI Autocomplete. */
			'oneResult'    => '1 result found. Use up and down arrow keys to navigate.',
			/* translators: %d: Number of results found when using jQuery UI Autocomplete. */
			'manyResults'  => '%d results found. Use up and down arrow keys to navigate.',
			'itemSelected' => 'Item selected.',
		) )
	);

	// deprecated, not used in core, most functionality is included in jQuery 1.3
	$scripts->add( 'jquery-form', "/wp-includes/js/jquery/jquery.form$suffix.js", array( 'jquery' ), '4.2.1', 1 );

	// jQuery plugins
	$scripts->add( 'jquery-color', '/wp-includes/js/jquery/jquery.color.min.js', array( 'jquery' ), '2.1.2', 1 );
	$scripts->add( 'schedule', '/wp-includes/js/jquery/jquery.schedule.js', array( 'jquery' ), '20m', 1 );
	$scripts->add( 'jquery-query', '/wp-includes/js/jquery/jquery.query.js', array( 'jquery' ), '2.1.7', 1 );
	$scripts->add( 'jquery-serialize-object', '/wp-includes/js/jquery/jquery.serialize-object.js', array( 'jquery' ), '0.2', 1 );
	$scripts->add( 'jquery-hotkeys', "/wp-includes/js/jquery/jquery.hotkeys$suffix.js", array( 'jquery' ), '0.0.2m', 1 );
	$scripts->add( 'jquery-table-hotkeys', "/wp-includes/js/jquery/jquery.table-hotkeys$suffix.js", array( 'jquery', 'jquery-hotkeys' ), false, 1 );
	$scripts->add( 'jquery-touch-punch', '/wp-includes/js/jquery/jquery.ui.touch-punch.js', array( 'jquery-ui-widget', 'jquery-ui-mouse' ), '0.2.2', 1 );

	// Not used any more, registered for backward compatibility.
	$scripts->add( 'suggest', "/wp-includes/js/jquery/suggest$suffix.js", array( 'jquery' ), '1.1-20110113', 1 );

	// Masonry v2 depended on jQuery. v3 does not. The older jquery-masonry handle is a shiv.
	// It sets jQuery as a dependency, as the theme may have been implicitly loading it this way.
	$scripts->add( 'imagesloaded', '/wp-includes/js/imagesloaded.min.js', array(), '3.2.0', 1 );
	$scripts->add( 'masonry', '/wp-includes/js/masonry.min.js', array( 'imagesloaded' ), '3.3.2', 1 );
	$scripts->add( 'jquery-masonry', "/wp-includes/js/jquery/jquery.masonry$dev_suffix.js", array( 'jquery', 'masonry' ), '3.1.2b', 1 );

	$scripts->add( 'thickbox', '/wp-includes/js/thickbox/thickbox.js', array( 'jquery' ), '3.1-20121105', 1 );
	did_action( 'init' ) && $scripts->localize(
		'thickbox',
		'thickboxL10n',
		new LabelsObject( '__', array(
			'next'             => 'Next &gt;',
			'prev'             => '&lt; Prev',
			'image'            => 'Image',
			'of'               => 'of',
			'close'            => 'Close',
			'noiframes'        => 'This feature requires inline frames. You have iframes disabled or your browser does not support them.',
			'loadingAnimation' => array( 'includes_url', 'js/thickbox/loadingAnimation.gif' ),
		) )
	);

	$scripts->add( 'jcrop', '/wp-includes/js/jcrop/jquery.Jcrop.min.js', array( 'jquery' ), '0.9.12' );

	$scripts->add( 'swfobject', '/wp-includes/js/swfobject.js', array(), '2.2-20120417' );

	// Error messages for Plupload.
	$uploader_l10n = new LabelsObject( '__', array(
		'queue_limit_exceeded'      => 'You have attempted to queue too many files.',
		/* translators: %s: File name. */
		'file_exceeds_size_limit'   => '%s exceeds the maximum upload size for this site.',
		'zero_byte_file'            => 'This file is empty. Please try another.',
		'invalid_filetype'          => 'Sorry, this file type is not permitted for security reasons.',
		'not_an_image'              => 'This file is not an image. Please try another.',
		'image_memory_exceeded'     => 'Memory exceeded. Please try another smaller file.',
		'image_dimensions_exceeded' => 'This is larger than the maximum size. Please try another.',
		'default_error'             => 'An error occurred in the upload. Please try again later.',
		'missing_upload_url'        => 'There was a configuration error. Please contact the server administrator.',
		'upload_limit_exceeded'     => 'You may only upload 1 file.',
		'http_error'                => 'Unexpected response from the server. The file may have been uploaded successfully. Check in the Media Library or reload the page.',
		'http_error_image'          => 'Post-processing of the image failed. If this is a photo or a large image, please scale it down to 2500 pixels and upload it again.',
		'upload_failed'             => 'Upload failed.',
		/* translators: 1: Opening link tag, 2: Closing link tag. */
		'big_upload_failed'         => 'Please try uploading this file with the %1$sbrowser uploader%2$s.',
		/* translators: %s: File name. */
		'big_upload_queued'         => '%s exceeds the maximum upload size for the multi-file uploader when used in your browser.',
		'io_error'                  => 'IO error.',
		'security_error'            => 'Security error.',
		'file_cancelled'            => 'File canceled.',
		'upload_stopped'            => 'Upload stopped.',
		'dismiss'                   => 'Dismiss',
		'crunching'                 => 'Crunching&hellip;',
		'deleted'                   => 'moved to the trash.',
		/* translators: %s: File name. */
		'error_uploading'           => '&#8220;%s&#8221; has failed to upload.',
	) );

	$scripts->add( 'moxiejs', "/wp-includes/js/plupload/moxie$suffix.js", array(), '1.3.5' );
	$scripts->add( 'plupload', "/wp-includes/js/plupload/plupload$suffix.js", array( 'moxiejs' ), '2.1.9' );
	// Back compat handles:
	foreach ( array( 'all', 'html5', 'flash', 'silverlight', 'html4' ) as $handle ) {
		$scripts->add( "plupload-$handle", false, array( 'plupload' ), '2.1.1' );
	}

	$scripts->add( 'plupload-handlers', "/wp-includes/js/plupload/handlers$suffix.js", array( 'plupload', 'jquery' ) );
	did_action( 'init' ) && $scripts->localize( 'plupload-handlers', 'pluploadL10n', $uploader_l10n );

	$scripts->add( 'wp-plupload', "/wp-includes/js/plupload/wp-plupload$suffix.js", array( 'plupload', 'jquery', 'json2', 'media-models' ), false, 1 );
	did_action( 'init' ) && $scripts->localize( 'wp-plupload', 'pluploadL10n', $uploader_l10n );

	// keep 'swfupload' for back-compat.
	$scripts->add( 'swfupload', '/wp-includes/js/swfupload/swfupload.js', array(), '2201-20110113' );
	$scripts->add( 'swfupload-all', false, array( 'swfupload' ), '2201' );
	$scripts->add( 'swfupload-handlers', "/wp-includes/js/swfupload/handlers$suffix.js", array( 'swfupload-all', 'jquery' ), '2201-20110524' );
	did_action( 'init' ) && $scripts->localize( 'swfupload-handlers', 'swfuploadL10n', $uploader_l10n );

	$scripts->add( 'comment-reply', "/wp-includes/js/comment-reply$suffix.js", array(), false, 1 );

	$scripts->add( 'json2', "/wp-includes/js/json2$suffix.js", array(), '2015-05-03' );
	did_action( 'init' ) && $scripts->add_data( 'json2', 'conditional', 'lt IE 8' );

	$scripts->add( 'underscore', "/wp-includes/js/underscore$dev_suffix.js", array(), '1.8.3', 1 );
	$scripts->add( 'backbone', "/wp-includes/js/backbone$dev_suffix.js", array( 'underscore', 'jquery' ), '1.4.0', 1 );

	$scripts->add( 'wp-util', "/wp-includes/js/wp-util$suffix.js", array( 'underscore', 'jquery' ), false, 1 );
	did_action( 'init' ) && $scripts->localize(
		'wp-util',
		'_wpUtilSettings',
		array(
			'ajax' => array(
				'url' => admin_url( 'admin-ajax.php', 'relative' ),
			),
		)
	);

	$scripts->add( 'wp-backbone', "/wp-includes/js/wp-backbone$suffix.js", array( 'backbone', 'wp-util' ), false, 1 );

	$scripts->add( 'revisions', "/wp-admin/js/revisions$suffix.js", array( 'wp-backbone', 'jquery-ui-slider', 'hoverIntent' ), false, 1 );

	$scripts->add( 'imgareaselect', "/wp-includes/js/imgareaselect/jquery.imgareaselect$suffix.js", array( 'jquery' ), false, 1 );

	$scripts->add( 'mediaelement', false, array( 'jquery', 'mediaelement-core', 'mediaelement-migrate' ), '4.2.13-9993131', 1 );
	$scripts->add( 'mediaelement-core', "/wp-includes/js/mediaelement/mediaelement-and-player$suffix.js", array(), '4.2.13-9993131', 1 );
	$scripts->add( 'mediaelement-migrate', "/wp-includes/js/mediaelement/mediaelement-migrate$suffix.js", array(), false, 1 );

	did_action( 'init' ) && $scripts->add_inline_script(
		'mediaelement-core',
		sprintf(
			'var mejsL10n = %s;',
			wp_json_encode(
				array(
					'language' => strtolower( strtok( determine_locale(), '_-' ) ),
					'strings'  => array(
						'mejs.install-flash'       => __( 'You are using a browser that does not have Flash player enabled or installed. Please turn on your Flash player plugin or download the latest version from https://get.adobe.com/flashplayer/' ),
						'mejs.fullscreen-off'      => __( 'Turn off Fullscreen' ),
						'mejs.fullscreen-on'       => __( 'Go Fullscreen' ),
						'mejs.download-video'      => __( 'Download Vvvvvvideo' ),
						'mejs.fullscreen'          => __( 'Fullscreen' ),
						'mejs.time-jump-forward'   => array( __( 'Jump forward 1 second' ), __( 'Jump forward %1 seconds' ) ),
						'mejs.loop'                => __( 'Toggle Loop' ),
						'mejs.play'                => __( 'Play' ),
						'mejs.pause'               => __( 'Pause' ),
						'mejs.close'               => __( 'Close' ),
						'mejs.time-slider'         => __( 'Time Slider' ),
						'mejs.time-help-text'      => __( 'Use Left/Right Arrow keys to advance one second, Up/Down arrows to advance ten seconds.' ),
						'mejs.time-skip-back'      => array( __( 'Skip back 1 second' ), __( 'Skip back %1 seconds' ) ),
						'mejs.captions-subtitles'  => __( 'Captions/Subtitles' ),
						'mejs.captions-chapters'   => __( 'Chapters' ),
						'mejs.none'                => __( 'None' ),
						'mejs.mute-toggle'         => __( 'Mute Toggle' ),
						'mejs.volume-help-text'    => __( 'Use Up/Down Arrow keys to increase or decrease volume.' ),
						'mejs.unmute'              => __( 'Unmute' ),
						'mejs.mute'                => __( 'Mute' ),
						'mejs.volume-slider'       => __( 'Volume Slider' ),
						'mejs.video-player'        => __( 'Video Player' ),
						'mejs.audio-player'        => __( 'Audio Player' ),
						'mejs.ad-skip'             => __( 'Skip ad' ),
						'mejs.ad-skip-info'        => array( __( 'Skip in 1 second' ), __( 'Skip in %1 seconds' ) ),
						'mejs.source-chooser'      => __( 'Source Chooser' ),
						'mejs.stop'                => __( 'Stop' ),
						'mejs.speed-rate'          => __( 'Speed Rate' ),
						'mejs.live-broadcast'      => __( 'Live Broadcast' ),
						'mejs.afrikaans'           => __( 'Afrikaans' ),
						'mejs.albanian'            => __( 'Albanian' ),
						'mejs.arabic'              => __( 'Arabic' ),
						'mejs.belarusian'          => __( 'Belarusian' ),
						'mejs.bulgarian'           => __( 'Bulgarian' ),
						'mejs.catalan'             => __( 'Catalan' ),
						'mejs.chinese'             => __( 'Chinese' ),
						'mejs.chinese-simplified'  => __( 'Chinese (Simplified)' ),
						'mejs.chinese-traditional' => __( 'Chinese (Traditional)' ),
						'mejs.croatian'            => __( 'Croatian' ),
						'mejs.czech'               => __( 'Czech' ),
						'mejs.danish'              => __( 'Danish' ),
						'mejs.dutch'               => __( 'Dutch' ),
						'mejs.english'             => __( 'English' ),
						'mejs.estonian'            => __( 'Estonian' ),
						'mejs.filipino'            => __( 'Filipino' ),
						'mejs.finnish'             => __( 'Finnish' ),
						'mejs.french'              => __( 'French' ),
						'mejs.galician'            => __( 'Galician' ),
						'mejs.german'              => __( 'German' ),
						'mejs.greek'               => __( 'Greek' ),
						'mejs.haitian-creole'      => __( 'Haitian Creole' ),
						'mejs.hebrew'              => __( 'Hebrew' ),
						'mejs.hindi'               => __( 'Hindi' ),
						'mejs.hungarian'           => __( 'Hungarian' ),
						'mejs.icelandic'           => __( 'Icelandic' ),
						'mejs.indonesian'          => __( 'Indonesian' ),
						'mejs.irish'               => __( 'Irish' ),
						'mejs.italian'             => __( 'Italian' ),
						'mejs.japanese'            => __( 'Japanese' ),
						'mejs.korean'              => __( 'Korean' ),
						'mejs.latvian'             => __( 'Latvian' ),
						'mejs.lithuanian'          => __( 'Lithuanian' ),
						'mejs.macedonian'          => __( 'Macedonian' ),
						'mejs.malay'               => __( 'Malay' ),
						'mejs.maltese'             => __( 'Maltese' ),
						'mejs.norwegian'           => __( 'Norwegian' ),
						'mejs.persian'             => __( 'Persian' ),
						'mejs.polish'              => __( 'Polish' ),
						'mejs.portuguese'          => __( 'Portuguese' ),
						'mejs.romanian'            => __( 'Romanian' ),
						'mejs.russian'             => __( 'Russian' ),
						'mejs.serbian'             => __( 'Serbian' ),
						'mejs.slovak'              => __( 'Slovak' ),
						'mejs.slovenian'           => __( 'Slovenian' ),
						'mejs.spanish'             => __( 'Spanish' ),
						'mejs.swahili'             => __( 'Swahili' ),
						'mejs.swedish'             => __( 'Swedish' ),
						'mejs.tagalog'             => __( 'Tagalog' ),
						'mejs.thai'                => __( 'Thai' ),
						'mejs.turkish'             => __( 'Turkish' ),
						'mejs.ukrainian'           => __( 'Ukrainian' ),
						'mejs.vietnamese'          => __( 'Vietnamese' ),
						'mejs.welsh'               => __( 'Welsh' ),
						'mejs.yiddish'             => __( 'Yiddish' ),
					),
				)
			)
		),
		'before'
	);

	$scripts->add( 'mediaelement-vimeo', '/wp-includes/js/mediaelement/renderers/vimeo.min.js', array( 'mediaelement' ), '4.2.13-9993131', 1 );
	$scripts->add( 'wp-mediaelement', "/wp-includes/js/mediaelement/wp-mediaelement$suffix.js", array( 'mediaelement' ), false, 1 );
	$mejs_settings = array(
		'pluginPath'  => includes_url( 'js/mediaelement/', 'relative' ),
		'classPrefix' => 'mejs-',
		'stretching'  => 'responsive',
	);
	did_action( 'init' ) && $scripts->localize(
		'mediaelement',
		'_wpmejsSettings',
		/**
		 * Filters the MediaElement configuration settings.
		 *
		 * @since 4.4.0
		 *
		 * @param array $mejs_settings MediaElement settings array.
		 */
		apply_filters( 'mejs_settings', $mejs_settings )
	);

	$scripts->add( 'wp-codemirror', '/wp-includes/js/codemirror/codemirror.min.js', array(), '5.29.1-alpha-ee20357' );
	$scripts->add( 'csslint', '/wp-includes/js/codemirror/csslint.js', array(), '1.0.5' );
	$scripts->add( 'esprima', '/wp-includes/js/codemirror/esprima.js', array(), '4.0.0' );
	$scripts->add( 'jshint', '/wp-includes/js/codemirror/fakejshint.js', array( 'esprima' ), '2.9.5' );
	$scripts->add( 'jsonlint', '/wp-includes/js/codemirror/jsonlint.js', array(), '1.6.2' );
	$scripts->add( 'htmlhint', '/wp-includes/js/codemirror/htmlhint.js', array(), '0.9.14-xwp' );
	$scripts->add( 'htmlhint-kses', '/wp-includes/js/codemirror/htmlhint-kses.js', array( 'htmlhint' ) );
	$scripts->add( 'code-editor', "/wp-admin/js/code-editor$suffix.js", array( 'jquery', 'wp-codemirror', 'underscore' ) );
	$scripts->add( 'wp-theme-plugin-editor', "/wp-admin/js/theme-plugin-editor$suffix.js", array( 'wp-util', 'wp-sanitize', 'jquery', 'jquery-ui-core', 'wp-a11y', 'underscore' ) );
	did_action( 'init' ) && $scripts->add_inline_script(
		'wp-theme-plugin-editor',
		sprintf(
			'wp.themePluginEditor.l10n = %s;',
			wp_json_encode(
				array(
					'saveAlert' => __( 'The changes you made will be lost if you navigate away from this page.' ),
					'saveError' => __( 'Something went wrong. Your change may not have been saved. Please try again. There is also a chance that you may need to manually fix and upload the file over FTP.' ),
					'lintError' => array(
						/* translators: %d: Error count. */
						'singular' => _n( 'There is %d error which must be fixed before you can update this file.', 'There are %d errors which must be fixed before you can update this file.', 1 ),
						/* translators: %d: Error count. */
						'plural'   => _n( 'There is %d error which must be fixed before you can update this file.', 'There are %d errors which must be fixed before you can update this file.', 2 ), // @todo This is lacking, as some languages have a dedicated dual form. For proper handling of plurals in JS, see #20491.
					),
				)
			)
		)
	);

	$scripts->add( 'wp-playlist', "/wp-includes/js/mediaelement/wp-playlist$suffix.js", array( 'wp-util', 'backbone', 'mediaelement' ), false, 1 );

	$scripts->add( 'zxcvbn-async', "/wp-includes/js/zxcvbn-async$suffix.js", array(), '1.0' );
	did_action( 'init' ) && $scripts->localize(
		'zxcvbn-async',
		'_zxcvbnSettings',
		array(
			'src' => empty( $guessed_url ) ? includes_url( '/js/zxcvbn.min.js' ) : $scripts->base_url . '/wp-includes/js/zxcvbn.min.js',
		)
	);

	$scripts->add( 'password-strength-meter', "/wp-admin/js/password-strength-meter$suffix.js", array( 'jquery', 'zxcvbn-async' ), false, 1 );
	did_action( 'init' ) && $scripts->localize(
		'password-strength-meter',
		'pwsL10n',
		new LabelsObject( array(
			'unknown'  => array( '_x', 'Password strength unknown', 'password strength' ),
			'short'    => array( '_x', 'Very weak', 'password strength' ),
			'bad'      => array( '_x', 'Weak', 'password strength' ),
			'good'     => array( '_x', 'Medium', 'password strength' ),
			'strong'   => array( '_x', 'Strong', 'password strength' ),
			'mismatch' => array( '_x', 'Mismatch', 'password mismatch' ),
		) )
	);

	$scripts->add( 'user-profile', "/wp-admin/js/user-profile$suffix.js", array( 'jquery', 'password-strength-meter', 'wp-util' ), false, 1 );
	did_action( 'init' ) && $scripts->localize(
		'user-profile',
		'userProfileL10n',
		new LabelsObject( '__', array(
			'warn'     => 'Your new password has not been saved.',
			'warnWeak' => 'Confirm use of weak password',
			'show'     => 'Show',
			'hide'     => 'Hide',
			'cancel'   => 'Cancel',
			'ariaShow' => array( 'esc_attr__', 'Show password' ),
			'ariaHide' => array( 'esc_attr__', 'Hide password' ),
		) )
	);

	$scripts->add( 'language-chooser', "/wp-admin/js/language-chooser$suffix.js", array( 'jquery' ), false, 1 );

	$scripts->add( 'user-suggest', "/wp-admin/js/user-suggest$suffix.js", array( 'jquery-ui-autocomplete' ), false, 1 );

	$scripts->add( 'admin-bar', "/wp-includes/js/admin-bar$suffix.js", array( 'hoverintent-js' ), false, 1 );

	$scripts->add( 'wplink', "/wp-includes/js/wplink$suffix.js", array( 'jquery', 'wp-a11y' ), false, 1 );
	did_action( 'init' ) && $scripts->localize(
		'wplink',
		'wpLinkL10n',
		new LabelsObject( '__', array(
			'title'          => 'Insert/edit link',
			'update'         => 'Update',
			'save'           => 'Add Link',
			'noTitle'        => '(no title)',
			'noMatchesFound' => 'No results found.',
			'linkSelected'   => 'Link selected.',
			'linkInserted'   => 'Link inserted.',
		) )
	);

	$scripts->add( 'wpdialogs', "/wp-includes/js/wpdialog$suffix.js", array( 'jquery-ui-dialog' ), false, 1 );

	$scripts->add( 'word-count', "/wp-admin/js/word-count$suffix.js", array(), false, 1 );

	$scripts->add( 'media-upload', "/wp-admin/js/media-upload$suffix.js", array( 'thickbox', 'shortcode' ), false, 1 );

	$scripts->add( 'hoverIntent', "/wp-includes/js/hoverIntent$suffix.js", array( 'jquery' ), '1.8.1', 1 );

	// JS-only version of hoverintent (no dependencies).
	$scripts->add( 'hoverintent-js', '/wp-includes/js/hoverintent-js.min.js', array(), '2.2.1', 1 );

	$scripts->add( 'customize-base', "/wp-includes/js/customize-base$suffix.js", array( 'jquery', 'json2', 'underscore' ), false, 1 );
	$scripts->add( 'customize-loader', "/wp-includes/js/customize-loader$suffix.js", array( 'customize-base' ), false, 1 );
	$scripts->add( 'customize-preview', "/wp-includes/js/customize-preview$suffix.js", array( 'wp-a11y', 'customize-base' ), false, 1 );
	$scripts->add( 'customize-models', '/wp-includes/js/customize-models.js', array( 'underscore', 'backbone' ), false, 1 );
	$scripts->add( 'customize-views', '/wp-includes/js/customize-views.js', array( 'jquery', 'underscore', 'imgareaselect', 'customize-models', 'media-editor', 'media-views' ), false, 1 );
	$scripts->add( 'customize-controls', "/wp-admin/js/customize-controls$suffix.js", array( 'customize-base', 'wp-a11y', 'wp-util', 'jquery-ui-core' ), false, 1 );
	did_action( 'init' ) && $scripts->localize(
		'customize-controls',
		'_wpCustomizeControlsL10n',
		new LabelsObject( '__', array(
			'activate'                => 'Activate &amp; Publish',
			'save'                    => 'Save &amp; Publish', // @todo Remove as not required.
			'publish'                 => 'Publish',
			'published'               => 'Published',
			'saveDraft'               => 'Save Draft',
			'draftSaved'              => 'Draft Saved',
			'updating'                => 'Updating',
			'schedule'                => array( '_x', 'Schedule', 'customizer changeset action/button label' ),
			'scheduled'               => array( '_x', 'Scheduled', 'customizer changeset status' ),
			'invalid'                 => 'Invalid',
			'saveBeforeShare'         => 'Please save your changes in order to share the preview.',
			'futureDateError'         => 'You must supply a future date to schedule.',
			'saveAlert'               => 'The changes you made will be lost if you navigate away from this page.',
			'saved'                   => 'Saved',
			'cancel'                  => 'Cancel',
			'close'                   => 'Close',
			'action'                  => 'Action',
			'discardChanges'          => 'Discard changes',
			'cheatin'                 => 'Something went wrong.',
			'notAllowedHeading'       => 'You need a higher level of permission.',
			'notAllowed'              => 'Sorry, you are not allowed to customize this site.',
			'previewIframeTitle'      => 'Site Preview',
			'loginIframeTitle'        => 'Session expired',
			'collapseSidebar'         => array( '_x', 'Hide Controls', 'label for hide controls button without length constraints' ),
			'expandSidebar'           => array( '_x', 'Show Controls', 'label for hide controls button without length constraints' ),
			'untitledBlogName'        => '(Untitled)',
			'unknownRequestFail'      => 'Looks like something&#8217;s gone wrong. Wait a couple seconds, and then try again.',
			'themeDownloading'        => 'Downloading your new theme&hellip;',
			'themePreviewWait'        => 'Setting up your live preview. This may take a bit.',
			'revertingChanges'        => 'Reverting unpublished changes&hellip;',
			'trashConfirm'            => 'Are you sure you want to discard your unpublished changes?',
			/* translators: %s: Display name of the user who has taken over the changeset in customizer. */
			'takenOverMessage'        => '%s has taken over and is currently customizing.',
			/* translators: %s: URL to the Customizer to load the autosaved version. */
			'autosaveNotice'          => 'There is a more recent autosave of your changes than the one you are previewing. <a href="%s">Restore the autosave</a>',
			'videoHeaderNotice'       => 'This theme doesn&#8217;t support video headers on this page. Navigate to the front page or another page that supports video headers.',
			// Used for overriding the file types allowed in plupload.
			'allowedFiles'            => 'Allowed Files',
			'customCssError'          => new LabelsObject( array(
				/* translators: %d: Error count. */
				'singular' => array( '_n', 'There is %d error which must be fixed before you can save.', 'There are %d errors which must be fixed before you can save.', 1 ),
				/* translators: %d: Error count. */
				'plural'   => array( '_n', 'There is %d error which must be fixed before you can save.', 'There are %d errors which must be fixed before you can save.', 2 ), // @todo This is lacking, as some languages have a dedicated dual form. For proper handling of plurals in JS, see #20491.
			) ),
			'pageOnFrontError'        => 'Homepage and posts page must be different.',
			'saveBlockedError'        => new LabelsObject( array(
				/* translators: %s: Number of invalid settings. */
				'singular' => array( '_n', 'Unable to save due to %s invalid setting.', 'Unable to save due to %s invalid settings.', 1 ),
				/* translators: %s: Number of invalid settings. */
				'plural'   => array( '_n', 'Unable to save due to %s invalid setting.', 'Unable to save due to %s invalid settings.', 2 ), // @todo This is lacking, as some languages have a dedicated dual form. For proper handling of plurals in JS, see #20491.
			) ),
			'scheduleDescription'     => 'Schedule your customization changes to publish ("go live") at a future date.',
			'themePreviewUnavailable' => 'Sorry, you can&#8217;t preview new themes when you have changes scheduled or saved as a draft. Please publish your changes, or wait until they publish to preview new themes.',
			'themeInstallUnavailable' => sprintf(
				/* translators: %s: URL to Add Themes admin screen. */
				__( 'You won&#8217;t be able to install new themes from here yet since your install requires SFTP credentials. For now, please <a href="%s">add themes in the admin</a>.' ),
				esc_url( admin_url( 'theme-install.php' ) )
			),
			'publishSettings'         => 'Publish Settings',
			'invalidDate'             => 'Invalid date.',
			'invalidValue'            => 'Invalid value.',
		) )
	);
	$scripts->add( 'customize-selective-refresh', "/wp-includes/js/customize-selective-refresh$suffix.js", array( 'jquery', 'wp-util', 'customize-preview' ), false, 1 );

	$scripts->add( 'customize-widgets', "/wp-admin/js/customize-widgets$suffix.js", array( 'jquery', 'jquery-ui-sortable', 'jquery-ui-droppable', 'wp-backbone', 'customize-controls' ), false, 1 );
	$scripts->add( 'customize-preview-widgets', "/wp-includes/js/customize-preview-widgets$suffix.js", array( 'jquery', 'wp-util', 'customize-preview', 'customize-selective-refresh' ), false, 1 );

	$scripts->add( 'customize-nav-menus', "/wp-admin/js/customize-nav-menus$suffix.js", array( 'jquery', 'wp-backbone', 'customize-controls', 'accordion', 'nav-menu', 'wp-sanitize' ), false, 1 );
	$scripts->add( 'customize-preview-nav-menus', "/wp-includes/js/customize-preview-nav-menus$suffix.js", array( 'jquery', 'wp-util', 'customize-preview', 'customize-selective-refresh' ), false, 1 );

	$scripts->add( 'wp-custom-header', "/wp-includes/js/wp-custom-header$suffix.js", array( 'wp-a11y' ), false, 1 );

	$scripts->add( 'accordion', "/wp-admin/js/accordion$suffix.js", array( 'jquery' ), false, 1 );

	$scripts->add( 'shortcode', "/wp-includes/js/shortcode$suffix.js", array( 'underscore' ), false, 1 );
	$scripts->add( 'media-models', "/wp-includes/js/media-models$suffix.js", array( 'wp-backbone' ), false, 1 );
	did_action( 'init' ) && $scripts->localize(
		'media-models',
		'_wpMediaModelsL10n',
		array(
			'settings' => array(
				'ajaxurl' => admin_url( 'admin-ajax.php', 'relative' ),
				'post'    => array( 'id' => 0 ),
			),
		)
	);

	$scripts->add( 'wp-embed', "/wp-includes/js/wp-embed$suffix.js" );

	// To enqueue media-views or media-editor, call wp_enqueue_media().
	// Both rely on numerous settings, styles, and templates to operate correctly.
	$scripts->add( 'media-views', "/wp-includes/js/media-views$suffix.js", array( 'utils', 'media-models', 'wp-plupload', 'jquery-ui-sortable', 'wp-mediaelement', 'wp-api-request', 'wp-a11y', 'wp-i18n' ), false, 1 );
	$scripts->add( 'media-editor', "/wp-includes/js/media-editor$suffix.js", array( 'shortcode', 'media-views' ), false, 1 );
	$scripts->add( 'media-audiovideo', "/wp-includes/js/media-audiovideo$suffix.js", array( 'media-editor' ), false, 1 );
	$scripts->add( 'mce-view', "/wp-includes/js/mce-view$suffix.js", array( 'shortcode', 'jquery', 'media-views', 'media-audiovideo' ), false, 1 );

	$scripts->add( 'wp-api', "/wp-includes/js/wp-api$suffix.js", array( 'jquery', 'backbone', 'underscore', 'wp-api-request' ), false, 1 );

	if ( is_admin() ) {
		$scripts->add( 'admin-tags', "/wp-admin/js/tags$suffix.js", array( 'jquery', 'wp-ajax-response' ), false, 1 );
		did_action( 'init' ) && $scripts->localize(
			'admin-tags',
			'tagsl10n',
			new LabelsObject( '__', array(
				'noPerm' => 'Sorry, you are not allowed to do that.',
				'broken' => 'Something went wrong.',
			) )
		);

		$scripts->add( 'admin-comments', "/wp-admin/js/edit-comments$suffix.js", array( 'wp-lists', 'quicktags', 'jquery-query' ), false, 1 );
		did_action( 'init' ) && $scripts->localize(
			'admin-comments',
			'adminCommentsL10n',
			new LabelsObject( array(
				'hotkeys_highlight_first' => isset( $_GET['hotkeys_highlight_first'] ),
				'hotkeys_highlight_last'  => isset( $_GET['hotkeys_highlight_last'] ),
				'replyApprove'            => array( '__', 'Approve and Reply' ),
				'reply'                   => array( '__', 'Reply' ),
				'warnQuickEdit'           => array( '__', "Are you sure you want to edit this comment?\nThe changes you made will be lost." ),
				'warnCommentChanges'      => array( '__', "Are you sure you want to do this?\nThe comment changes you made will be lost." ),
				'docTitleComments'        => array( '__', 'Comments' ),
				/* translators: %s: Comments count. */
				'docTitleCommentsCount'   => array( '__', 'Comments (%s)' ),
			) )
		);

		$scripts->add( 'xfn', "/wp-admin/js/xfn$suffix.js", array( 'jquery' ), false, 1 );

		$scripts->add( 'postbox', "/wp-admin/js/postbox$suffix.js", array( 'jquery-ui-sortable' ), false, 1 );
		did_action( 'init' ) && $scripts->localize(
			'postbox',
			'postBoxL10n',
			new LabelsObject( '__', array(
				'postBoxEmptyString' => 'Drag boxes here',
			) )
		);

		$scripts->add( 'tags-box', "/wp-admin/js/tags-box$suffix.js", array( 'jquery', 'tags-suggest' ), false, 1 );

		$scripts->add( 'tags-suggest', "/wp-admin/js/tags-suggest$suffix.js", array( 'jquery-ui-autocomplete', 'wp-a11y' ), false, 1 );
		did_action( 'init' ) && $scripts->localize(
			'tags-suggest',
			'tagsSuggestL10n',
			new LabelsObject( '__', array(
				'tagDelimiter' => array( '_x', ',', 'tag delimiter' ),
				'removeTerm'   => 'Remove term:',
				'termSelected' => 'Term selected.',
				'termAdded'    => 'Term added.',
				'termRemoved'  => 'Term removed.',
			) )
		);

		$scripts->add( 'post', "/wp-admin/js/post$suffix.js", array( 'suggest', 'wp-lists', 'postbox', 'tags-box', 'underscore', 'word-count', 'wp-a11y', 'wp-sanitize' ), false, 1 );
		did_action( 'init' ) && $scripts->localize(
			'post',
			'postL10n',
			new LabelsObject( '__', array(
				'ok'                 => 'OK',
				'cancel'             => 'Cancel',
				'publishOn'          => 'Publish on:',
				'publishOnFuture'    => 'Schedule for:',
				'publishOnPast'      => 'Published on:',
				/* translators: 1: Month, 2: Day, 3: Year, 4: Hour, 5: Minute. */
				'dateFormat'         => '%1$s %2$s, %3$s at %4$s:%5$s',
				'showcomm'           => 'Show more comments',
				'endcomm'            => 'No more comments found.',
				'publish'            => 'Publish',
				'schedule'           => array( '_x', 'Schedule', 'post action/button label' ),
				'update'             => 'Update',
				'savePending'        => 'Save as Pending',
				'saveDraft'          => 'Save Draft',
				'private'            => 'Private',
				'public'             => 'Public',
				'publicSticky'       => 'Public, Sticky',
				'password'           => 'Password Protected',
				'privatelyPublished' => 'Privately Published',
				'published'          => 'Published',
				'saveAlert'          => 'The changes you made will be lost if you navigate away from this page.',
				'savingText'         => 'Saving Draft&#8230;',
				'permalinkSaved'     => 'Permalink saved',
			) )
		);

		$scripts->add( 'editor-expand', "/wp-admin/js/editor-expand$suffix.js", array( 'jquery', 'underscore' ), false, 1 );

		$scripts->add( 'link', "/wp-admin/js/link$suffix.js", array( 'wp-lists', 'postbox' ), false, 1 );

		$scripts->add( 'comment', "/wp-admin/js/comment$suffix.js", array( 'jquery', 'postbox' ) );
		$scripts->add_data( 'comment', 'group', 1 );
		did_action( 'init' ) && $scripts->localize(
			'comment',
			'commentL10n',
			new LabelsObject( '__', array(
				'submittedOn' => 'Submitted on:',
				/* translators: 1: Month, 2: Day, 3: Year, 4: Hour, 5: Minute. */
				'dateFormat'  => '%1$s %2$s, %3$s at %4$s:%5$s',
			) )
		);

		$scripts->add( 'admin-gallery', "/wp-admin/js/gallery$suffix.js", array( 'jquery-ui-sortable' ) );

		$scripts->add( 'admin-widgets', "/wp-admin/js/widgets$suffix.js", array( 'jquery-ui-sortable', 'jquery-ui-draggable', 'jquery-ui-droppable' ), false, 1 );
		did_action( 'init' ) && $scripts->add_inline_script(
			'admin-widgets',
			sprintf(
				'wpWidgets.l10n = %s;',
				wp_json_encode(
					array(
						'save'      => __( 'Save' ),
						'saved'     => __( 'Saved' ),
						'saveAlert' => __( 'The changes you made will be lost if you navigate away from this page.' ),
					)
				)
			)
		);

		$scripts->add( 'media-widgets', "/wp-admin/js/widgets/media-widgets$suffix.js", array( 'jquery', 'media-models', 'media-views', 'wp-api-request' ) );
		$scripts->add_inline_script( 'media-widgets', 'wp.mediaWidgets.init();', 'after' );

		$scripts->add( 'media-audio-widget', "/wp-admin/js/widgets/media-audio-widget$suffix.js", array( 'media-widgets', 'media-audiovideo' ) );
		$scripts->add( 'media-image-widget', "/wp-admin/js/widgets/media-image-widget$suffix.js", array( 'media-widgets' ) );
		$scripts->add( 'media-gallery-widget', "/wp-admin/js/widgets/media-gallery-widget$suffix.js", array( 'media-widgets' ) );
		$scripts->add( 'media-video-widget', "/wp-admin/js/widgets/media-video-widget$suffix.js", array( 'media-widgets', 'media-audiovideo', 'wp-api-request' ) );
		$scripts->add( 'text-widgets', "/wp-admin/js/widgets/text-widgets$suffix.js", array( 'jquery', 'backbone', 'editor', 'wp-util', 'wp-a11y' ) );
		$scripts->add( 'custom-html-widgets', "/wp-admin/js/widgets/custom-html-widgets$suffix.js", array( 'jquery', 'backbone', 'wp-util', 'jquery-ui-core', 'wp-a11y' ) );

		$scripts->add( 'theme', "/wp-admin/js/theme$suffix.js", array( 'wp-backbone', 'wp-a11y', 'customize-base' ), false, 1 );

		$scripts->add( 'inline-edit-post', "/wp-admin/js/inline-edit-post$suffix.js", array( 'jquery', 'tags-suggest', 'wp-a11y' ), false, 1 );
		did_action( 'init' ) && $scripts->localize(
			'inline-edit-post',
			'inlineEditL10n',
			new LabelsObject( array(
				'error'      => array( '__', 'Error while saving the changes.' ),
				'ntdeltitle' => array( '__', 'Remove From Bulk Edit' ),
				'notitle'    => array( '__', '(no title)' ),
				'comma'      => trim( _x( ',', 'tag delimiter' ) ),
				'saved'      => array( '__', 'Changes saved.' ),
			) )
		);

		$scripts->add( 'inline-edit-tax', "/wp-admin/js/inline-edit-tax$suffix.js", array( 'jquery', 'wp-a11y' ), false, 1 );
		did_action( 'init' ) && $scripts->localize(
			'inline-edit-tax',
			'inlineEditL10n',
			new LabelsObject( '__', array(
				'error' => 'Error while saving the changes.',
				'saved' => 'Changes saved.',
			) )
		);

		$scripts->add( 'plugin-install', "/wp-admin/js/plugin-install$suffix.js", array( 'jquery', 'jquery-ui-core', 'thickbox' ), false, 1 );
		did_action( 'init' ) && $scripts->localize(
			'plugin-install',
			'plugininstallL10n',
			new LabelsObject( '__', array(
				'plugin_information' => 'Plugin:',
				'plugin_modal_label' => 'Plugin details',
				'ays'                => 'Are you sure you want to install this plugin?',
			) )
		);

		$scripts->add( 'site-health', "/wp-admin/js/site-health$suffix.js", array( 'clipboard', 'jquery', 'wp-util', 'wp-a11y', 'wp-i18n' ), false, 1 );
		$scripts->set_translations( 'site-health' );

		$scripts->add( 'privacy-tools', "/wp-admin/js/privacy-tools$suffix.js", array( 'jquery' ), false, 1 );
		did_action( 'init' ) && $scripts->localize(
			'privacy-tools',
			'privacyToolsL10n',
			new LabelsObject( '__', array(
				'noDataFound'     => 'No personal data was found for this user.',
				'foundAndRemoved' => 'All of the personal data found for this user was erased.',
				'noneRemoved'     => 'Personal data was found for this user but was not erased.',
				'someNotRemoved'  => 'Personal data was found for this user but some of the personal data found was not erased.',
				'removalError'    => 'An error occurred while attempting to find and erase personal data.',
				'emailSent'       => 'The personal data export link for this user was sent.',
				'noExportFile'    => 'No personal data export file was generated.',
				'exportError'     => 'An error occurred while attempting to export personal data.',
			) )
		);

		$scripts->add( 'updates', "/wp-admin/js/updates$suffix.js", array( 'jquery', 'wp-util', 'wp-a11y', 'wp-sanitize' ), false, 1 );
		did_action( 'init' ) && $scripts->localize(
			'updates',
			'_wpUpdatesSettings',
			array(
				'ajax_nonce' => wp_create_nonce( 'updates' ),
				'l10n'       => new LabelsObject( array(
					/* translators: %s: Search query. */
					'searchResults'            => array( '__', 'Search results for &#8220;%s&#8221;' ),
					'searchResultsLabel'       => array( '__', 'Search Results' ),
					'noPlugins'                => array( '__', 'You do not appear to have any plugins available at this time.' ),
					'noItemsSelected'          => array( '__', 'Please select at least one item to perform this action on.' ),
					'updating'                 => array( '__', 'Updating...' ), // No ellipsis.
					'pluginUpdated'            => array( '_x', 'Updated!', 'plugin' ),
					'themeUpdated'             => array( '_x', 'Updated!', 'theme' ),
					'update'                   => array( '__', 'Update' ),
					'updateNow'                => array( '__', 'Update Now' ),
					/* translators: %s: Plugin name and version. */
					'pluginUpdateNowLabel'     => array( '_x', 'Update %s now', 'plugin' ),
					'updateFailedShort'        => array( '__', 'Update Failed!' ),
					/* translators: %s: Error string for a failed update. */
					'updateFailed'             => array( '__', 'Update Failed: %s' ),
					/* translators: %s: Plugin name and version. */
					'pluginUpdatingLabel'      => array( '_x', 'Updating %s...', 'plugin' ), // No ellipsis.
					/* translators: %s: Plugin name and version. */
					'pluginUpdatedLabel'       => array( '_x', '%s updated!', 'plugin' ),
					/* translators: %s: Plugin name and version. */
					'pluginUpdateFailedLabel'  => array( '_x', '%s update failed', 'plugin' ),
					/* translators: Accessibility text. */
					'updatingMsg'              => array( '__', 'Updating... please wait.' ), // No ellipsis.
					/* translators: Accessibility text. */
					'updatedMsg'               => array( '__', 'Update completed successfully.' ),
					/* translators: Accessibility text. */
					'updateCancel'             => array( '__', 'Update canceled.' ),
					'beforeunload'             => array( '__', 'Updates may not complete if you navigate away from this page.' ),
					'installNow'               => array( '__', 'Install Now' ),
					/* translators: %s: Plugin name. */
					'pluginInstallNowLabel'    => array( '_x', 'Install %s now', 'plugin' ),
					'installing'               => array( '__', 'Installing...' ),
					'pluginInstalled'          => array( '_x', 'Installed!', 'plugin' ),
					'themeInstalled'           => array( '_x', 'Installed!', 'theme' ),
					'installFailedShort'       => array( '__', 'Installation Failed!' ),
					/* translators: %s: Error string for a failed installation. */
					'installFailed'            => array( '__', 'Installation failed: %s' ),
					/* translators: %s: Plugin name and version. */
					'pluginInstallingLabel'    => array( '_x', 'Installing %s...', 'plugin' ), // no ellipsis
					/* translators: %s: Theme name and version. */
					'themeInstallingLabel'     => array( '_x', 'Installing %s...', 'theme' ), // no ellipsis
					/* translators: %s: Plugin name and version. */
					'pluginInstalledLabel'     => array( '_x', '%s installed!', 'plugin' ),
					/* translators: %s: Theme name and version. */
					'themeInstalledLabel'      => array( '_x', '%s installed!', 'theme' ),
					/* translators: %s: Plugin name and version. */
					'pluginInstallFailedLabel' => array( '_x', '%s installation failed', 'plugin' ),
					/* translators: %s: Theme name and version. */
					'themeInstallFailedLabel'  => array( '_x', '%s installation failed', 'theme' ),
					'installingMsg'            => array( '__', 'Installing... please wait.' ),
					'installedMsg'             => array( '__', 'Installation completed successfully.' ),
					/* translators: %s: Activation URL. */
					'importerInstalledMsg'     => array( '__', 'Importer installed successfully. <a href="%s">Run importer</a>' ),
					/* translators: %s: Theme name. */
					'aysDelete'                => array( '__', 'Are you sure you want to delete %s?' ),
					/* translators: %s: Plugin name. */
					'aysDeleteUninstall'       => array( '__', 'Are you sure you want to delete %s and its data?' ),
					'aysBulkDelete'            => array( '__', 'Are you sure you want to delete the selected plugins and their data?' ),
					'aysBulkDeleteThemes'      => array( '__', 'Caution: These themes may be active on other sites in the network. Are you sure you want to proceed?' ),
					'deleting'                 => array( '__', 'Deleting...' ),
					/* translators: %s: Error string for a failed deletion. */
					'deleteFailed'             => array( '__', 'Deletion failed: %s' ),
					'pluginDeleted'            => array( '_x', 'Deleted!', 'plugin' ),
					'themeDeleted'             => array( '_x', 'Deleted!', 'theme' ),
					'livePreview'              => array( '__', 'Live Preview' ),
					'activatePlugin'           => is_network_admin() ? __( 'Network Activate' ) : __( 'Activate' ),
					'activateTheme'            => is_network_admin() ? __( 'Network Enable' ) : __( 'Activate' ),
					/* translators: %s: Plugin name. */
					'activatePluginLabel'      => is_network_admin() ? array( '_x', 'Network Activate %s', 'plugin' ) : array( '_x', 'Activate %s', 'plugin' ),
					/* translators: %s: Theme name. */
					'activateThemeLabel'       => is_network_admin() ? array( '_x', 'Network Activate %s', 'theme' ) : array( '_x', 'Activate %s', 'theme' ),
					'activateImporter'         => array( '__', 'Run Importer' ),
					/* translators: %s: Importer name. */
					'activateImporterLabel'    => array( '__', 'Run %s' ),
					'unknownError'             => array( '__', 'Something went wrong.' ),
					'connectionError'          => array( '__', 'Connection lost or the server is busy. Please try again later.' ),
					'nonceError'               => array( '__', 'An error has occurred. Please reload the page and try again.' ),
					/* translators: %s: Number of plugins. */
					'pluginsFound'             => array( '__', 'Number of plugins found: %d' ),
					'noPluginsFound'           => array( '__', 'No plugins found. Try a different search.' ),
				) ),
			)
		);

		$scripts->add( 'farbtastic', '/wp-admin/js/farbtastic.js', array( 'jquery' ), '1.2' );

		$scripts->add( 'iris', '/wp-admin/js/iris.min.js', array( 'jquery-ui-draggable', 'jquery-ui-slider', 'jquery-touch-punch' ), '1.0.7', 1 );
		$scripts->add( 'wp-color-picker', "/wp-admin/js/color-picker$suffix.js", array( 'iris' ), false, 1 );
		did_action( 'init' ) && $scripts->localize(
			'wp-color-picker',
			'wpColorPickerL10n',
			new LabelsObject( '__', array(
				'clear'            => 'Clear',
				'clearAriaLabel'   => 'Clear color',
				'defaultString'    => 'Default',
				'defaultAriaLabel' => 'Select default color',
				'pick'             => 'Select Color',
				'defaultLabel'     => 'Color value',
			) )
		);

		$scripts->add( 'dashboard', "/wp-admin/js/dashboard$suffix.js", array( 'jquery', 'admin-comments', 'postbox', 'wp-util', 'wp-a11y' ), false, 1 );

		$scripts->add( 'list-revisions', "/wp-includes/js/wp-list-revisions$suffix.js" );

		$scripts->add( 'media-grid', "/wp-includes/js/media-grid$suffix.js", array( 'media-editor' ), false, 1 );
		$scripts->add( 'media', "/wp-admin/js/media$suffix.js", array( 'jquery' ), false, 1 );
		did_action( 'init' ) && $scripts->localize(
			'media',
			'attachMediaBoxL10n',
			new LabelsObject( '__', array(
				'error' => 'An error has occurred. Please reload the page and try again.',
			) )
		);

		$scripts->add( 'image-edit', "/wp-admin/js/image-edit$suffix.js", array( 'jquery', 'json2', 'imgareaselect' ), false, 1 );
		did_action( 'init' ) && $scripts->localize(
			'image-edit',
			'imageEditL10n',
			new LabelsObject( '__', array(
				'error' => 'Could not load the preview image. Please reload the page and try again.',
			) )
		);

		$scripts->add( 'set-post-thumbnail', "/wp-admin/js/set-post-thumbnail$suffix.js", array( 'jquery' ), false, 1 );
		did_action( 'init' ) && $scripts->localize(
			'set-post-thumbnail',
			'setPostThumbnailL10n',
			new LabelsObject( '__', array(
				'setThumbnail' => 'Use as featured image',
				'saving'       => 'Saving...', // no ellipsis
				'error'        => 'Could not set that as the thumbnail image. Try a different attachment.',
				'done'         => 'Done',
			) )
		);

		/*
		 * Navigation Menus: Adding underscore as a dependency to utilize _.debounce
		 * see https://core.trac.wordpress.org/ticket/42321
		 */
		$scripts->add( 'nav-menu', "/wp-admin/js/nav-menu$suffix.js", array( 'jquery-ui-sortable', 'jquery-ui-draggable', 'jquery-ui-droppable', 'wp-lists', 'postbox', 'json2', 'underscore' ) );
		did_action( 'init' ) && $scripts->localize(
			'nav-menu',
			'navMenuL10n',
			new LabelsObject( '__', array(
				'noResultsFound' => 'No results found.',
				'warnDeleteMenu' => "You are about to permanently delete this menu. \n 'Cancel' to stop, 'OK' to delete.",
				'saveAlert'      => 'The changes you made will be lost if you navigate away from this page.',
				'untitled'       => array( '_x', '(no label)', 'missing menu item navigation label' ),
			) )
		);

		$scripts->add( 'custom-header', '/wp-admin/js/custom-header.js', array( 'jquery-masonry' ), false, 1 );
		$scripts->add( 'custom-background', "/wp-admin/js/custom-background$suffix.js", array( 'wp-color-picker', 'media-views' ), false, 1 );
		$scripts->add( 'media-gallery', "/wp-admin/js/media-gallery$suffix.js", array( 'jquery' ), false, 1 );

		$scripts->add( 'svg-painter', '/wp-admin/js/svg-painter.js', array( 'jquery' ), false, 1 );
	}
}
