<?php
class TrustindexTestimonialsPostEditor {

public function __construct() {
}

public static function init() {
self::add_actions();
}

public static function add_actions() {
add_action( 'add_meta_boxes_wpt-testimonial', array( __CLASS__, 'add_meta_boxes' ) );
add_filter('hidden_meta_boxes', array( __CLASS__, 'hide_comment' ), 10, 2);
add_action( 'save_post_wpt-testimonial', array( __CLASS__, 'save_details' ) );
add_action( 'wp_ajax_wpttst_edit_rating', array( __CLASS__, 'edit_rating' ) );
add_filter( 'wp_insert_post_data', array( __CLASS__, 'prevent_shortcode' ), 10, 2 );
}

public static function hide_comment($hidden, $screen) {
$post_type= $screen->id;
if ($post_type == 'wpt-testimonial')
{
$hidden[]= 'commentstatusdiv';
$hidden[]= 'commentsdiv';
}
return $hidden;
}

public static function add_meta_boxes() {
add_meta_box(
'details',
esc_html_x( 'Client Details', 'post editor', 'testimonial-widgets' ),
array( __CLASS__, 'meta_options' ),
'wpt-testimonial',
'normal',
'high'
);
}

public static function meta_options() {
global $post, $pagenow;
$post = wpttst_get_post( $post );
$fields = wpttst_get_custom_fields();
$is_new = ( 'post-new.php' == $pagenow );
 wp_nonce_field ( plugin_basename(__FILE__), 'wpttst_metabox_nonce');
?>
<?php do_action( 'wpttst_before_client_fields_table' ); ?>
 <table class="options">
 <tr>
 <td colspan="2">
 <p><?php esc_html_x( 'To add a photo or logo, use the Reviewer\'s photo option.', 'post editor', 'testimonial-widgets' ); ?></p>
 </td>
 </tr>
<?php
do_action( 'wpttst_before_client_fields' );
foreach ( $fields as $key => $field ) {
if ( 'category' == strtok( $field['input_type'], '-' ) ) {
continue;
}
?>
 <tr>
 <th>
 <label for="<?php echo esc_attr( $field['name'] ); ?>">
<?php echo apply_filters( 'wpttst_l10n', $field['label'], 'testimonial-widgets-form-fields', $field['name'] . ' : label' ); ?>
 </label>
 </th>
 <td>
 <div class="<?php echo esc_attr( $field['input_type'] ); ?>">
<?php self::meta_option( $field, $post, $is_new ); ?>
 </div>
 </td>
 </tr>
<?php
}
do_action( 'wpttst_after_client_fields' );
?>
 </table>
<?php
do_action( 'wpttst_after_client_fields_table' );
}

public static function meta_option( $field, $post, $is_new ) {
if ( isset( $field['action_input'] ) && $field['action_input'] ) {
self::meta_option__action( $field, $post, $is_new );
}
else {
switch ( $field['input_type'] ) {
case 'rating' :
self::meta_option__rating( $field, $post, $is_new );
break;
case 'url' :
self::meta_option__url( $field, $post, $is_new );
break;
case 'checkbox' :
self::meta_option__checkbox( $field, $post, $is_new );
break;
case 'shortcode' :
self::meta_option__shortcode( $field, $post, $is_new );
break;
case 'textarea' :
self::meta_option__textarea( $field, $post, $is_new );
break;
default :
self::meta_option__text( $field, $post, $is_new );
}
}
}

private static function meta_option__action( $field, $post, $is_new ) {
if ( isset( $field['action_input'] ) && $field['action_input'] ) {
do_action( $field['action_input'], $field, $post->{$field['name']} );
}
}

private static function meta_option__text( $field, $post, $is_new ) {
printf( '<input id="%2$s" type="%1$s" class="custom-input" name="custom[%2$s]" value="%3$s">', esc_attr( $field['input_type'] ), esc_attr( $field['name'] ), esc_attr( $post->{$field['name']} ) );
}

private static function meta_option__textarea( $field, $post, $is_new ) {
printf(
'<textarea id="%1$s" name="custom[%1$s]" class="custom-input">%2$s</textarea>',
esc_attr( $field['name'] ),
wp_kses_post( $post->{$field['name']} )
);
}

private static function meta_option__url( $field, $post, $is_new ) {
?>
 <div class="input-url">
<?php printf( '<input id="%2$s" type="%1$s" class="custom-input" name="custom[%2$s]" value="%3$s" size="">', esc_attr( $field['input_type'] ), esc_html( $field['name'] ), esc_attr( $post->{$field['name']} ) ); ?>
 </div>
 <div class="input-links">
 <div class="input-nofollow">
 <label for="custom_nofollow"><code>rel="nofollow"</code></label>
 <select id="custom_nofollow" name="custom[nofollow]">
 <option value="default" <?php selected( $post->nofollow, 'default' ); ?>><?php esc_html_e( 'default', 'testimonial-widgets' ); ?></option>
 <option value="yes" <?php selected( $post->nofollow, 'yes' ); ?>><?php esc_html_e( 'yes', 'testimonial-widgets' ); ?></option>
 <option value="no" <?php selected( $post->nofollow, 'no' ); ?>><?php esc_html_e( 'no', 'testimonial-widgets' ); ?></option>
 </select>
 </div>
 <div class="input-noopener">
 <label for="custom_noopener"><code>rel="noopener"</code></label>
 <select id="custom_noopener" name="custom[noopener]">
 <option value="default" <?php selected( $post->noopener, 'default' ); ?>><?php esc_html_e( 'default', 'testimonial-widgets' ); ?></option>
 <option value="yes" <?php selected( $post->noopener, 'yes' ); ?>><?php esc_html_e( 'yes', 'testimonial-widgets' ); ?></option>
 <option value="no" <?php selected( $post->noopener, 'no' ); ?>><?php esc_html_e( 'no', 'testimonial-widgets' ); ?></option>
 </select>
 </div>
 <div class="input-noreferrer">
 <label for="custom_noreferrer"><code>rel="noreferrer"</code></label>
 <select id="custom_noopener" name="custom[noreferrer]">
 <option value="default" <?php selected( $post->noreferrer, 'default' ); ?>><?php esc_html_e( 'default', 'testimonial-widgets' ); ?></option>
 <option value="yes" <?php selected( $post->noreferrer, 'yes' ); ?>><?php esc_html_e( 'yes', 'testimonial-widgets' ); ?></option>
 <option value="no" <?php selected( $post->noreferrer, 'no' ); ?>><?php esc_html_e( 'no', 'testimonial-widgets' ); ?></option>
 </select>
 </div>
<?php
}

private static function meta_option__checkbox( $field, $post, $is_new ) {
printf( '<input id="%2$s" type="%1$s" class="custom-input" name="custom[%2$s]" value="%3$s" %4$s>', esc_attr( $field['input_type'] ), esc_attr( $field['name'] ), 1, checked( $post->{$field['name']}, 1, false ) );
}

private static function meta_option__rating( $field, $post, $is_new ) {
$rating = get_post_meta( $post->ID, $field['name'], true );
if ( ! $rating || $is_new ) {
$rating = 5;
}
?>
 <div class="edit-rating-box hide-if-no-js" data-field="<?php echo esc_attr( $field['name'] ); ?>">
<?php wp_nonce_field( 'editrating', "edit-{$field['name']}-nonce", false ); ?>
 <input type="hidden" class="current-rating" value="<?php echo esc_attr( $rating ); ?>">
 <!-- form -->
 <div class="rating-form">
 <span class="inner">
 <?php wpttst_star_rating_form( $field, $rating, 'in-metabox', true, 'custom' ); ?>
 </span>
 </div>
 </div>
<?php
}

public static function meta_option__shortcode( $field, $post, $is_new ) {
 $shortcode = str_replace( array( '[', ']' ), array( '', '' ), $field['shortcode_on_display'] );
 if ( shortcode_exists( $shortcode ) ) {
 echo do_shortcode( esc_attr($field['shortcode_on_display']) );
 } else {
 echo '<div class="custom-input not-found">' . sprintf( esc_html__( 'shortcode %s not found', 'testimonial-widgets' ), '<code>' . esc_html($field['shortcode_on_display']) . '</code>' ) . '</div>';
 }
}

public static function save_details() {
if ( ! isset( $_POST['custom'] ) || !wp_verify_nonce( $_POST['wpttst_metabox_nonce'], plugin_basename(__FILE__))) {
return;
}
$post_id = absint( sanitize_text_field($_POST['post_ID']) );
if (is_array($_POST['custom']))
{
$custom = array_map( 'sanitize_text_field', $_POST['custom']);
}
else
{
$custom = null;
}
$custom_fields = wpttst_get_custom_fields();
$checkboxes = array();
foreach ( $custom_fields as $key => $field ) {
if ( 'checkbox' == $field['input_type'] ) {
$checkboxes[ $key ] = 0;
}
}
if ( $checkboxes ) {
$custom = array_merge( $checkboxes, $custom );
}
$custom_fields['nofollow']['input_type'] = '';
$custom_fields['noopener']['input_type'] = '';
 $custom_fields['noreferrer']['input_type'] = '';
 
foreach ( $custom as $key => $value ) {
 $action = 'update';
 $sanitized_value = '';
 if ( isset( $custom_fields[ $key ] ) ) {
if ( 'rating' == $custom_fields[ $key ]['input_type'] && !$value ) {
$action = 'delete';
}
}
if ( isset($custom_fields[ $key ]['input_type']) && 'text' == $custom_fields[ $key ]['input_type'] ) {
$sanitized_value = wp_filter_post_kses( $value );
}elseif ( isset($custom_fields[ $key ]['input_type']) && 'email' == $custom_fields[ $key ]['input_type'] ) {
$sanitized_value = sanitize_email( $value );
}elseif ( isset($custom_fields[ $key ]['input_type']) && 'url' == $custom_fields[ $key ]['input_type'] ) {
$sanitized_value = esc_url_raw( $value );
}else{
$sanitized_value = sanitize_text_field( $value );
}
if ( 'update' == $action ) {
update_post_meta( $post_id, $key, $sanitized_value );
}
else {
delete_post_meta( $post_id, $key );
}
}
}

public static function prevent_shortcode( $data, $postarr ) {
 if ( 'wpt-testimonial' == $data['post_type'] ) {
 $data['post_content'] = preg_replace( "/\[testimonial_view (.*)\]/", '', $data['post_content'] );
 }
 return $data;
}

public static function edit_rating() {
$post_id = isset( $_POST['post_id'] ) ? intval(sanitize_text_field($_POST['post_id'])) : 0;
$rating = isset( $_POST['rating'] ) ? intval(sanitize_text_field($_POST['rating'])) : 0;
$name = isset( $_POST['field_name'] ) ? sanitize_text_field($_POST['field_name']) : 'rating';
if ($rating == 0)
{
$rating = 5;
}
check_ajax_referer( 'editrating', 'editratingnonce' );
if ( $post_id ) {
if ( $rating ) {
update_post_meta( $post_id, $name, $rating );
} else {
delete_post_meta( $post_id, $name );
}
}
}
}
TrustindexTestimonialsPostEditor::init();
