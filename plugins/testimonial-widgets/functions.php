<?php

function wpttst_get_post( $post ) {
$custom = get_post_custom( $post->ID );
$fields = wpttst_get_custom_fields();
foreach ( $fields as $key => $field ) {
$name = $field['name'];
if ( 'featured_image' == $name ) {
$post->thumbnail_id = get_post_thumbnail_id( $post->ID );
}
else {
if ( isset( $custom[ $name ] ) ) {
$post->$name = $custom[ $name ][0];
}
else {
$post->$name = '';
}
}
}
return $post;
}

function wpttst_get_custom_fields() {
$all_fields = array();
$forms = get_option( 'wpttst_custom_forms' );
if ( ! $forms ) {
return $all_fields;
}
$fields = $forms[1]['fields'];
if ( ! $fields ) {
return $all_fields;
}
foreach ( $fields as $field ) {
if ( 'post' != $field['record_type'] ) {
$all_fields[ $field['name'] ] = $field;
}
}
foreach ( $forms as $form ) {
$custom_fields = array();
$fields = $form['fields'];
foreach ( $fields as $field ) {
if ( 'post' != $field['record_type'] ) {
$custom_fields[ $field['name'] ] = $field;
}
}
$all_fields = array_merge( $all_fields, $custom_fields );
}
return $all_fields;
}
function wpttst_get_thumbnail( $size = null ) {
if (! is_admin() ) {
return '';
}
$size = array(50,50);

$id = get_the_ID();
$img = '';
if ( has_post_thumbnail( $id ) ) {
$img = get_the_post_thumbnail( $id, $size );

} else {
}

return apply_filters( 'wpttst_thumbnail_img', $img, $id, $size );
}
function wpttst_get_field( $field, $args = array() ) {
if ( ! $field ) {
return '';
}

global $post;

switch ( $field ) {
case 'truncated' :
$html = wpmtst_truncate( $post->post_content, $args['char_limit'] );
break;
default :
$html = get_post_meta( $post->ID, $field, true );

}

return $html;
}
function wpttst_get_all_fields() {
$forms = get_option( 'wpttst_custom_forms' );
$all_fields = array();


$fields = $forms[1]['fields'];
foreach ( $fields as $field ) {
$all_fields[ $field['name'] ] = $field;
}
foreach ( $forms as $form ) {
$custom_fields = array();
$fields = $form['fields'];
foreach ( $fields as $field ) {
$custom_fields[ $field['name'] ] = $field;
}
$all_fields = array_merge( $all_fields, $custom_fields );
}

return $all_fields;
}
?>