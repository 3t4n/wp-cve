<?php
namespace BipPages;

function create_page( $title, $content = '' ) {
  $page_id = \post_exists( $title );

  if ( empty( $page_id ) ) {
    // create page with bip post_type
    $page_args = array(
      'post_title'    => wp_strip_all_tags( $title ),
      'post_content'  => $content,
      'post_status'   => 'publish',
      'post_author'   => get_current_user_id(),
      'post_type'     => 'bip',
    );

    $page_id = wp_insert_post( $page_args, true );
  } else {
    $page_args = array(
      'ID' => $page_id,
      'post_type' => 'bip'
    );

    $page_id = wp_update_post( $page_args, true );
  }

  return $page_id;
}

function create_functional_page( $title, $content = '' ) {
  $page = get_page_by_title( $title, OBJECT, 'bip' );

  if ( $page && get_post_status( $page ) != 'trash' ) {
    $page_id = $page->ID;
  } elseif ( get_post_status( $page ) == 'trash' ) {
    $new_page = wp_untrash_post( $page->ID );
    $page_id = $new_page->ID;
  } else {
    $page_id = create_page( $title, $content );
  }

  return $page_id;
}

function create_main_page() {
  $title = __( 'BIP Main Page', 'bip-pages' );

  $main_page_id = create_functional_page( $title, get_main_page_default_content() );

  if ( !is_wp_error( $main_page_id ) ) {
    update_option( 'bip_pages_main_page_id', $main_page_id );
  }
}

function create_instructions_page() {
  $title = __( 'BIP usage manual', 'bip-pages' );

  // Polish only for now
  $instructions = file_get_contents( __DIR__ . '/boilerplate-text/bip-usage-manual-pl.txt' );

  $instruction_page_id = create_functional_page( $title, $instructions );

  if ( !is_wp_error( $instruction_page_id ) ) {
    update_option( 'bip_pages_instruction_id', $instruction_page_id );
  }
}

add_action('admin_init', __NAMESPACE__ . '\post_activation_flow');
function post_activation_flow() {
  if( is_admin() && get_option('Activated_Plugin') == 'bip-pages' ) {
    flush_rewrite_rules(); // we're adding a new page type slug
    delete_option('Activated_Plugin');
    wp_redirect( Settings\get_settings_url( ['plugin-activated' => 1] ) );

    set_transient( 'bip-pages-activation-msg', true, 5 );
  }
}

function activation_notice() {
    if( get_transient( 'bip-pages-activation-msg' ) ){
        ?>
        <div class="notice updated is-dismissible">
            <p>
              <?= esc_html__( 'BIP Pages plugin has been activated. Use the settings page below to configure your main page.', 'bip-pages' ) ?>
            </p>
        </div>
        <div class="notice updated is-dismissible">
            <p>
              <?= esc_html__( 'BIP Pages: your main page and BIP instructions page have been created automatically.', 'bip-pages' ) ?>
            </p>
        </div>
        <?php

        delete_transient( 'bip-pages-activation-msg' );
    }
}
add_action( 'admin_notices', __NAMESPACE__ . '\activation_notice' );
