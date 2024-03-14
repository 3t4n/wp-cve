<?php
if ( ! defined( 'CP_AUTH_INCLUDE' ) ) {
	print 'Direct access not allowed.';
	exit;
}

// Required scripts
require_once CP_CALCULATEDFIELDSF_BASE_PATH . '/inc/cpcff_templates.inc.php';

// Corrects a conflict with W3 Total Cache
if ( function_exists( 'w3_instance' ) ) {
	try {
		$w3_config = w3_instance( 'W3_Config' );
		$w3_config->set( 'minify.html.enable', false );
	} catch ( Exception $err ) {
		error_log( $err->getMessage() );
	}
}

add_filter( 'style_loader_tag', array( 'CPCFF_AUXILIARY', 'complete_link_tag' ) );

wp_enqueue_style( 'cpcff_stylepublic', plugins_url( '/css/stylepublic.css', CP_CALCULATEDFIELDSF_MAIN_FILE_PATH ), array(), CP_CALCULATEDFIELDSF_VERSION );
wp_enqueue_style( 'cpcff_jquery_ui', plugins_url( '/vendors/jquery-ui/jquery-ui.min.css', CP_CALCULATEDFIELDSF_MAIN_FILE_PATH ), array(), CP_CALCULATEDFIELDSF_VERSION );
wp_enqueue_style( 'cpcff_jquery_ui_font', plugins_url('/vendors/jquery-ui/jquery-ui-1.12.icon-font.min.css', CP_CALCULATEDFIELDSF_MAIN_FILE_PATH), array(), CP_CALCULATEDFIELDSF_VERSION );

$cpcff_main = CPCFF_MAIN::instance();
$form_obj   = $cpcff_main->get_form( $id );

$form_data = $form_obj->get_option( 'form_structure', CP_CALCULATEDFIELDSF_DEFAULT_form_structure );
$form_data_serialized = serialize( $form_data );

if ( strpos( $form_data_serialized, 'select2' ) && ! wp_script_is( 'select2' ) && ! wp_script_is( 'select-2-js' ) ) {
	wp_enqueue_style( 'cpcff_select2_css', plugins_url( '/vendors/select2/select2.min.css', CP_CALCULATEDFIELDSF_MAIN_FILE_PATH ), array(), CP_CALCULATEDFIELDSF_VERSION );
	wp_enqueue_script( 'cpcff_select2_js', plugins_url( '/vendors/select2/select2.min.js', CP_CALCULATEDFIELDSF_MAIN_FILE_PATH ), array(), CP_CALCULATEDFIELDSF_VERSION, true );
}

if(strpos($form_data_serialized, 'fqrcode') && !wp_script_is('qrcode'))
{
    wp_enqueue_script( 'cpcff_qrcode_js', plugins_url('/vendors/qrcode/html5-qrcode.min.js', CP_CALCULATEDFIELDSF_MAIN_FILE_PATH), array(), CP_CALCULATEDFIELDSF_VERSION, true );
}

if(preg_match('/PDFPAGESNUMBER/i', $form_data_serialized) && !wp_script_is('cpcff_pdf_js'))
{
	wp_enqueue_script( 'cpcff_pdf_js', plugins_url('/vendors/pdf-js/pdf.min.js', CP_CALCULATEDFIELDSF_MAIN_FILE_PATH), array(), CP_CALCULATEDFIELDSF_VERSION, true );
	wp_add_inline_script('cpcff_pdf_js', 'pdfjsLib.GlobalWorkerOptions.workerSrc="'.esc_js(plugins_url('/vendors/pdf-js/pdf.worker.min.js', CP_CALCULATEDFIELDSF_MAIN_FILE_PATH)).'";', 'after');
}

if ( ! empty( $form_data ) ) {
	if ( isset( $form_data[1] ) && is_object( $form_data[1] ) ) {
		$form_data[1] = (array) $form_data[1];
	}
	if ( isset( $form_data[1] ) && isset( $form_data[1][0] ) ) {
		if ( ! empty( $form_data[1][0]->formtemplate ) ) {
			CPCFF_TEMPLATES::enqueue_template_resources( $form_data[1][0]->formtemplate );
		}

		if ( ! empty( $form_data[1][0]->customstyles ) ) {
			print '<style>' . wp_strip_all_tags( $form_data[1][0]->customstyles ) . '</style>'; // phpcs:ignore WordPress.Security.EscapeOutput
		}
	}
	$form_data[1]['formid'] = 'cp_calculatedfieldsf_pform_' . CPCFF_MAIN::$form_counter;
	?>
<form name="<?php echo esc_attr( $form_data[1]['formid'] ); ?>" id="<?php echo esc_attr( $form_data[1]['formid'] ); ?>" action="?" method="post" enctype="multipart/form-data" class="cff-form <?php
if ( ! empty( $form_data[1][0] ) && ! empty( $form_data[1][0]->persistence ) ) {
	echo ' persist-form';
}
if ( ! empty( $atts ) && ! empty( $atts['class'] ) ) {
	echo ' ' . esc_attr( $atts['class'] );
}
?>" <?php
	// If the form shortcode was configured to be opened into an iframe
	if ( isset( $_REQUEST['cff-form-target'] ) ) {
		$cff_form_target = sanitize_text_field( wp_unslash( $_REQUEST['cff-form-target'] ) );
		if ( ! empty( $cff_form_target ) ) {
			print ' target="' . esc_attr( $cff_form_target ) . '"';
		}
	}

	// Direction
	if( property_exists( $form_data[1][0], 'direction' ) ) {
		print ' dir="' . esc_attr( $form_data[1][0]->direction ) . '"';
	}
?>>
<input type="hidden" name="cp_calculatedfieldsf_pform_psequence" value="_<?php echo esc_attr( CPCFF_MAIN::$form_counter ); ?>" />
<input type="hidden" name="cp_calculatedfieldsf_id" value="<?php echo esc_attr( $id ); ?>" /><pre style="display:none !important;"><script type="text/javascript">form_structure_<?php echo esc_js( CPCFF_MAIN::$form_counter ); ?>=<?php print str_replace( array( "\n", "\r" ), ' ', ( ( version_compare( CP_CFF_PHPVERSION, '5.3.0' ) >= 0 ) ? json_encode( $form_data, JSON_HEX_QUOT | JSON_HEX_TAG ) : json_encode( $form_data ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>;</script></pre>
<div id="fbuilder">
	<?php
	if (
			! empty( $form_data ) &&
			! empty( $form_data[1] ) &&
			! empty( $form_data[1][0] ) &&
			! empty( $form_data[1][0]->loading_animation )
		) {
		print '<div class="cff-processing-form"></div>';
	}
	?>
	<div id="fbuilder_<?php echo esc_attr( CPCFF_MAIN::$form_counter ); ?>">
		<div id="formheader_<?php echo esc_attr( CPCFF_MAIN::$form_counter ); ?>"></div>
		<div id="fieldlist_<?php echo esc_attr( CPCFF_MAIN::$form_counter ); ?>"></div>
		<div class="clearer"></div>
	</div>
</div>
<div class="clearer"></div>
</form>
	<?php
	// If the form shortcode was configured to be opened into an iframe adjust iframe size
	if ( isset( $_REQUEST['cff-form-target'] ) ):
	?>
	<style>.cff-form{width:100%;overflow-x:auto;box-sizing: border-box;}</style>
	<pre style="display:none;"><code><script>
		window.addEventListener('load', function(){
			try{
				(new ResizeObserver(function() {
					frameElement.height = fbuilderjQuery('form').outerHeight()+40;
				})).observe(fbuilderjQuery('form')[0]);
			} catch(err){}
		});
	</script></code></pre>
	<?php
	endif;
}