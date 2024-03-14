<?php
$is_multiple = false;
if ( isset( $_REQUEST['multiple'] ) && $_REQUEST['multiple'] == true ) {
	$is_multiple = true;
}
?>
<html>
<head></head>
<body>
<form action="" id="iframe_form" method="post" enctype="multipart/form-data">
	<?php $file_size = ( $field['max_fileuploading_size'] == 'auto' ) ? 'auto' : $field['max_fileuploading_size'] * 1048576; ?>
	<input type="hidden" name="fd-callback" />
	<input type="file" id="fileselect" <?php echo ( $is_multiple ) ? 'multiple' : ''; ?> name="fileselect" class="original" style="position: absolute; cursor: pointer; top: 0px; width: 75px; height: 35px; left: 0px; z-index: 100; opacity: 0; filter:alpha(opacity=0);" />
	<input type="hidden" value="<?php echo esc_attr( $file_size ); ?>" id="file_size_<?php echo esc_attr( $field['field_key'] ); ?>" name="file_size_<?php echo esc_attr( $field['field_key'] ); ?>" >
</form>
</body>
</html>
