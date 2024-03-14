<?php
/*Form Tag Generator*/

add_action( 'wpcf7_admin_init', 'wpcf7_selct_multiselct_add_tag_generator', 15 );

function wpcf7_selct_multiselct_add_tag_generator(){
    $tag_generator = WPCF7_TagGenerator::get_instance();
    $tag_generator->add( 'selct_multiselct', __( 'Select and Multi-Select', 'yb-sml' ),
		'wpcf7_tag_generator_selct_multiselct' );
}

function wpcf7_tag_generator_selct_multiselct( $contact_form, $args = '' ){
    $args = wp_parse_args( $args, array() );
    $type = $args['id'];

    $description = __( "Generate a Select and Multi-Select Field based on Select2 ", 'yb-sml' );

?>

<div class="control-box">
<fieldset>
<legend><?php echo esc_html( $description ); ?></legend>
<table class="form-table">
<tbody>
	<tr>
    <th scope="row"><?php echo esc_html( __( 'Field type', 'contact-form-7' ) ); ?></th>
    <td>
		<fieldset>
		<legend class="screen-reader-text"><?php echo esc_html( __( 'Field type', 'contact-form-7' ) ); ?></legend>
		<label><input type="checkbox" name="required" /> <?php echo esc_html( __( 'Required field', 'contact-form-7' ) ); ?></label>
		</fieldset>
	</td>
    </tr>

    <tr>
	<th scope="row"><label for="<?php echo esc_attr( $args['content'] . '-name' ); ?>"><?php echo esc_html( __( 'Name', 'contact-form-7' ) ); ?></label></th>
	<td><input type="text" name="name" class="tg-name oneline" id="<?php echo esc_attr( $args['content'] . '-name' ); ?>" /></td>
	</tr>

    <tr>
	<th scope="row"><?php echo esc_html( __( 'Options', 'contact-form-7' ) ); ?></th>
	<td>
		<fieldset>
            <legend class="screen-reader-text"><?php echo esc_html( __( 'Options', 'contact-form-7' ) ); ?></legend>
            <textarea name="values" class="values" id="<?php echo esc_attr( $args['content'] . '-values' ); ?>"></textarea>
            <label for="<?php echo esc_attr( $args['content'] . '-values' ); ?>"><span class="description"><?php echo esc_html( __( "One option per line.", 'contact-form-7' ) ); ?></span></label><br />
            <label><input type="checkbox" name="multiple" class="option" /> <?php echo esc_html( __( 'Allow multiple selections', 'contact-form-7' ) ); ?></label><br />
		</fieldset>
	</td>
    </tr>

	<tr>
	<th scope="row"><label for="<?php echo esc_attr( $args['content'] . '-placeholder' ); ?>"><?php echo esc_html( __( 'Placeholder (Optional), Separated by hypens', 'yb-sml' ) ); ?></label></th>
	<td><input type="text" name="placeholder" class="placeholdervalue oneline option" id="<?php echo esc_attr( $args['content'] . '-placeholder' ); ?>"/></td>
	</tr>

	<tr>
	<th scope="row"><label for="<?php echo esc_attr( $args['content'] . '-size' ); ?>"><?php echo esc_html( __( 'Size', 'yb-sml' ) ); ?></label></th>
	<td><input type="text" name="size" class="sizevalue oneline option" id="<?php echo esc_attr( $args['content'] . '-size' ); ?>" placeholder="400px (Size in px or %)"/></td>
	</tr>

	<tr>
	<th scope="row"><label for="<?php echo esc_attr( $args['content'] . '-id' ); ?>"><?php echo esc_html( __( 'Id attribute', 'yb-sml' ) ); ?></label></th>
	<td><input type="text" name="id" class="idvalue oneline option" id="<?php echo esc_attr( $args['content'] . '-id' ); ?>" /></td>
	</tr>

    <tr>
	<th scope="row"><label for="<?php echo esc_attr( $args['content'] . '-class' ); ?>"><?php echo esc_html( __( 'Class attribute', 'yb-sml' ) ); ?></label></th>
	<td><input type="text" name="class" class="classvalue oneline option" id="<?php echo esc_attr( $args['content'] . '-class' ); ?>" /></td>
	</tr>
</tbody>
</table>
</fieldset>
</div>

<div class="insert-box">
	<input type="text" name="<?php echo $type; ?>" class="tag code" readonly="readonly" onfocus="this.select()" />

	<div class="submitbox">
	<input type="button" class="button button-primary insert-tag" value="<?php echo esc_attr( __( 'Insert Tag', 'contact-form-7' ) ); ?>" />
	</div>

	<br class="clear" />
	<p class="description mail-tag"><label for="<?php echo esc_attr( $args['content'] . '-mailtag' ); ?>"><?php echo sprintf( esc_html( __( "To use the value input through this field in a mail field, you need to insert the corresponding mail-tag (%s) into the field on the Mail tab.", 'contact-form-7' ) ), '<strong><span class="mail-tag"></span></strong>' ); ?><input type="text" class="mail-tag code hidden" readonly="readonly" id="<?php echo esc_attr( $args['content'] . '-mailtag' ); ?>" /></label></p>
</div>
<?php 
}
?>
