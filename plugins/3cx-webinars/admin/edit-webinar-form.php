<?php

// don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

function wp3cxw_admin_save_button( $post_id ) {
	static $button = '';

	if ( ! empty( $button ) ) {
		echo $button;
		return;
	}

	$nonce = wp_create_nonce( 'wp3cxw-save-webinar-form_' . $post_id );

	$onclick = sprintf(
		"this.form._wpnonce.value = '%s';"
		. " this.form.action.value = 'save';"
		. " return true;",
		$nonce );

	$button = sprintf(
		'<input type="submit" class="button-primary" name="wp3cxw-save" value="%1$s" onclick="%2$s" />',
		esc_attr( __( 'Save', '3cx-webinar' ) ),
		$onclick );

  $onclick = sprintf(
    "this.form._wpnonce.value = '%s';"
    . " this.form.action.value = 'clear_cache';"
    . " return true;",
    $nonce );    

  $button .= sprintf(
    '&nbsp;<input type="submit" class="button-primary" name="wp3cxw-clear_cache" value="%1$s" onclick="%2$s" />',
    esc_attr( __( 'Clear Cache', '3cx-webinar' ) ),
    $onclick );

  $onclick = sprintf(
    "this.form._wpnonce.value = '%s';"
    . " this.form.action.value = 'test_api';"
    . " return true;",
    $nonce );    

  $button .= sprintf(
    '&nbsp;<input type="submit" class="button-primary tcxtestbutton" name="wp3cxw-test_api" value="%1$s" onclick="%2$s" />',
    esc_attr( __( 'Test API Request', '3cx-webinar' ) ),
    $onclick );    
  
	echo $button;
}

?><div class="wrap">

<h1 class="wp-heading-inline"><?php
	if ( $post->initial() ) {
		echo esc_html( __( 'Add New Webinar Form', '3cx-webinar' ) );
	} else {
		echo esc_html( __( 'Edit Webinar Form', '3cx-webinar' ) );
	}
?></h1>

<?php
	if ( ! $post->initial() && current_user_can( 'wp3cxw_edit_webinar_forms' ) ) {
		echo sprintf( '<a href="%1$s" class="add-new-h2">%2$s</a>',
			esc_url( menu_page_url( 'wp3cxw-new', false ) ),
			esc_html( __( 'Add New', '3cx-webinar' ) ) );
	}
?>

<hr class="wp-header-end">

<?php do_action( 'wp3cxw_admin_warnings' ); ?>
<?php do_action( 'wp3cxw_admin_notices' ); ?>

<?php
if ( $post ) :

	if ( current_user_can( 'wp3cxw_edit_webinar_form', $post_id ) ) {
		$disabled = '';
	} else {
		$disabled = ' disabled="disabled"';
	}
?>

<form method="post" action="<?php echo esc_url( add_query_arg( array( 'post' => $post_id ), menu_page_url( 'wp3cxw', false ) ) ); ?>" id="wp3cxw-admin-form-element"<?php do_action( 'wp3cxw_post_edit_form_tag' ); ?>>
<?php
	if ( current_user_can( 'wp3cxw_edit_webinar_form', $post_id ) ) {
		wp_nonce_field( 'wp3cxw-save-webinar-form_' . $post_id );
	}
?>
<input type="hidden" id="post_ID" name="post_ID" value="<?php echo (int) $post_id; ?>" />
<input type="hidden" id="wp3cxw-locale" name="wp3cxw-locale" value="<?php echo esc_attr( $post->locale() ); ?>" />
<input type="hidden" id="hiddenaction" name="action" value="save" />
<input type="hidden" id="active-tab" name="active-tab" value="<?php echo isset( $_GET['active-tab'] ) ? (int) $_GET['active-tab'] : '0'; ?>" />

<div id="poststuff">
<div id="post-body" class="metabox-holder columns-2">
<div id="post-body-content">
<div id="titlediv">
<div id="titlewrap">
	<label class="screen-reader-text" id="title-prompt-text" for="title"><?php echo esc_html( __( 'Enter title here', '3cx-webinar' ) ); ?></label>
<?php
	$posttitle_atts = array(
		'type' => 'text',
		'name' => 'post_title',
		'size' => 30,
		'value' => $post->initial() ? '' : $post->title(),
		'id' => 'title',
		'spellcheck' => 'true',
		'autocomplete' => 'off',
		'disabled' =>
			current_user_can( 'wp3cxw_edit_webinar_form', $post_id ) ? '' : 'disabled',
	);

	echo sprintf( '<input %s />', wp3cxw_format_atts( $posttitle_atts ) );
?>
</div><!-- #titlewrap -->

<div class="inside">
<?php
	if ( ! $post->initial() ) :
?>
	<p class="description">
	<label for="wp3cxw-shortcode"><?php echo esc_html( __( "Copy this shortcode and paste it into your post, page, or text widget content:", '3cx-webinar' ) ); ?></label>
	<span class="shortcode wp-ui-highlight"><input type="text" id="wp3cxw-shortcode" onfocus="this.select();" readonly="readonly" class="large-text code" value="<?php echo esc_attr( $post->shortcode() ); ?>" /></span>
	</p>
<?php
	endif;
?>
</div>
</div><!-- #titlediv -->
</div><!-- #post-body-content -->

<div id="postbox-container-1" class="postbox-container">
<?php if ( current_user_can( 'wp3cxw_edit_webinar_form', $post_id ) ) : ?>
<?php endif; ?>

<div id="informationdiv" class="postbox">
<h3><?php echo esc_html( __( "Do you need help?", '3cx-webinar' ) ); ?></h3>
<div class="inside">
	<p><?php echo esc_html( __( "Check 3CX WebMeeting resources for further details.", '3cx-webinar' ) ); ?></p>
	<ol>
  <li><a href="https://www.3cx.com/phone-system/web-conferencing/" target="_blank"><?php echo __('3CX WebMeeting','3cx-webinar');?></a></li>
		<li><a href="https://www.3cx.com/community/forums/video-conferencing/" target="_blank"><?php echo __('Support Forum','3cx-webinar');?></a></li>
	</ol>
</div>
</div><!-- #informationdiv -->

</div><!-- #postbox-container-1 -->

<div id="postbox-container-2" class="postbox-container">
<div id="webinar-form-editor">

<?php

	$editor = new WP3CXW_Editor( $post );
	$panels = array();

	if ( current_user_can( 'wp3cxw_edit_webinar_form', $post_id ) ) {
		$panels = array(
			'config-panel' => array(
				'title' => __( 'Configuration', '3cx-webinar' ),
				'callback' => 'wp3cxw_editor_panel_config',
			)
		);
	}

	$panels = apply_filters( 'wp3cxw_editor_panels', $panels );

	foreach ( $panels as $id => $panel ) {
		$editor->add_panel( $id, $panel['title'], $panel['callback'] );
	}

	$editor->display();
?>
</div><!-- #webinar-form-editor -->

<?php if ( current_user_can( 'wp3cxw_edit_webinar_form', $post_id ) ) : ?>
<p class="submit"><?php wp3cxw_admin_save_button( $post_id ); ?></p>
<?php endif; ?>

</div><!-- #postbox-container-2 -->

</div><!-- #post-body -->
<br class="clear" />
</div><!-- #poststuff -->
</form>
<?php endif; ?>

</div><!-- .wrap -->

<?php

	do_action( 'wp3cxw_admin_footer', $post );
