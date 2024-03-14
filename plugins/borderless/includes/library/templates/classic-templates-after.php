<?php 

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( $theme == 'Aesir' ) {

  if (  $template['import_file_name'] === 'Business' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    $blog_page_id  = get_page_by_title( 'Blog' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    update_option( 'page_for_posts', $blog_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Gym' ) {
    
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    $blog_page_id  = get_page_by_title( 'Blog' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    update_option( 'page_for_posts', $blog_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Architects' ) {
    
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Creative' ) {
    
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Cafe' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    $top_menu = get_term_by( 'name', 'Top Menu', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      'top_menu' => $top_menu->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    $blog_page_id  = get_page_by_title( 'Blog' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    update_option( 'page_for_posts', $blog_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Church' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    $footer = get_term_by( 'name', 'Footer', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      'footer' => $footer->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    $blog_page_id  = get_page_by_title( 'Blog' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    update_option( 'page_for_posts', $blog_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Construction' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Cryptocurrency' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    $footer = get_term_by( 'name', 'Footer', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      'footer' => $footer->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    $blog_page_id  = get_page_by_title( 'Blog' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    update_option( 'page_for_posts', $blog_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Creative Studio' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    $blog_page_id  = get_page_by_title( 'Blog' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    update_option( 'page_for_posts', $blog_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Education' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    $side = get_term_by( 'name', 'Side Menu', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      'side' => $side->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    $blog_page_id  = get_page_by_title( 'Blog' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    update_option( 'page_for_posts', $blog_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Employment' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    $blog_page_id  = get_page_by_title( 'News' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    update_option( 'page_for_posts', $blog_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Financial' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    $footer = get_term_by( 'name', 'Footer', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      'footer' => $footer->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    $blog_page_id  = get_page_by_title( 'News' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    update_option( 'page_for_posts', $blog_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Fitness' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    $blog_page_id  = get_page_by_title( 'Blog' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    update_option( 'page_for_posts', $blog_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Restaurant' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Community' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    $footer = get_term_by( 'name', 'Footer', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      'footer' => $footer->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    $blog_page_id  = get_page_by_title( 'News' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    update_option( 'page_for_posts', $blog_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Hotel' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    $blog_page_id  = get_page_by_title( 'Blog' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    update_option( 'page_for_posts', $blog_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Writer Blog' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    $side = get_term_by( 'name', 'Side Menu', 'nav_menu' );
    $categories = get_term_by( 'Categories', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      'side' => $side->term_id,
      'page_builder'               => 'wpbakery',
      'categories'  => $categories->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    $blog_page_id  = get_page_by_title( 'Blog' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    update_option( 'page_for_posts', $blog_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'IT' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    $blog_page_id  = get_page_by_title( 'Blog' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    update_option( 'page_for_posts', $blog_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Startup' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    $copyright = get_term_by( 'name', 'Copyright Menu', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      'copyright' => $copyright->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    $blog_page_id  = get_page_by_title( 'Blog' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    update_option( 'page_for_posts', $blog_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Mechanic' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    $footer = get_term_by( 'name', 'Footer', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      'footer' => $footer->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Hospital' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    $footer = get_term_by( 'name', 'Footer', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      'footer' => $footer->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    $blog_page_id  = get_page_by_title( 'News' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    update_option( 'page_for_posts', $blog_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Minimalist Agency' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    $footer = get_term_by( 'name', 'Footer', 'nav_menu' );
    $footer_right = get_term_by( 'name', 'Footer Right', 'nav_menu' );
    $side_menu = get_term_by( 'name', 'Side Menu', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      'footer' => $footer->term_id,
      'footer_right' => $footer_right->term_id,
      'side_menu' => $side_menu->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    $blog_page_id  = get_page_by_title( 'Blog' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    update_option( 'page_for_posts', $blog_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Music' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    $footer = get_term_by( 'name', 'Footer', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      'footer' => $footer->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Graphic Designer' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main/Mobile', 'nav_menu' );
    $footer = get_term_by( 'name', 'Footer', 'nav_menu' );
    $left = get_term_by( 'name', 'Left', 'nav_menu' );
    $right = get_term_by( 'name', 'Right', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      'footer' => $footer->term_id,
      'left' => $left->term_id,
      'right' => $right->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Nonprofit' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    $footer = get_term_by( 'name', 'Footer', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      'footer' => $footer->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    $blog_page_id  = get_page_by_title( 'Blog' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    update_option( 'page_for_posts', $blog_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Creative Agency' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    $side = get_term_by( 'name', 'Side', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      'footer' => $footer->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Petshop' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    $blog_page_id  = get_page_by_title( 'Blog' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    update_option( 'page_for_posts', $blog_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Photographer' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    $footer = get_term_by( 'name', 'Footer', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      'footer' => $footer->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    $blog_page_id  = get_page_by_title( 'Blog' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    update_option( 'page_for_posts', $blog_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Politic' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    $footer = get_term_by( 'name', 'Footer', 'nav_menu' );
    $proposals = get_term_by( 'name', 'Proposals', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      'footer' => $footer->term_id,
      'proposals' => $proposals->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    $blog_page_id  = get_page_by_title( 'Blog' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    update_option( 'page_for_posts', $blog_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Agency' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    $footer = get_term_by( 'name', 'Footer', 'nav_menu' );
    $side = get_term_by( 'name', 'Side', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      'footer' => $footer->term_id,
      'side' => $side->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    $blog_page_id  = get_page_by_title( 'Blog' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    update_option( 'page_for_posts', $blog_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Real Estate' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    $blog_page_id  = get_page_by_title( 'Blog' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    update_option( 'page_for_posts', $blog_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Salon' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    $footer = get_term_by( 'name', 'Footer', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      'footer' => $footer->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    $blog_page_id  = get_page_by_title( 'Blog' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    update_option( 'page_for_posts', $blog_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Fashion Shop' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    $blog_page_id  = get_page_by_title( 'Blog' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    update_option( 'page_for_posts', $blog_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Digital Agency' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    $blog_page_id  = get_page_by_title( 'Blog' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    update_option( 'page_for_posts', $blog_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Sports' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    $blog_page_id  = get_page_by_title( 'Blog' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    update_option( 'page_for_posts', $blog_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Freelancer Designer' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    $one_page = get_term_by( 'name', 'One Page', 'nav_menu' );
    $side = get_term_by( 'name', 'Side', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      'one_page' => $one_page->term_id,
      'side' => $side->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Travel Blog' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    $categories = get_term_by( 'name', 'Categories', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      'page_builder'               => 'wpbakery',
      'categories'  => $one_page->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Wedding' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    $blog_page_id  = get_page_by_title( 'Blog' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    update_option( 'page_for_posts', $blog_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Wines' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    $footer = get_term_by( 'name', 'Footer', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      'footer' => $footer->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    $blog_page_id  = get_page_by_title( 'Blog' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    update_option( 'page_for_posts', $blog_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Web Studio' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    $footer = get_term_by( 'name', 'Footer', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      'footer' => $footer->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    $blog_page_id  = get_page_by_title( 'Blog' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    update_option( 'page_for_posts', $blog_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Psychology' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Veterinarian' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    $blog_page_id  = get_page_by_title( 'Blog' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    update_option( 'page_for_posts', $blog_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Barber' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    $footer = get_term_by( 'name', 'Footer', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      'footer' => $footer->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Dentist' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    $footer = get_term_by( 'name', 'Footer', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      'footer' => $footer->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    $blog_page_id  = get_page_by_title( 'Blog' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    update_option( 'page_for_posts', $blog_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Spa' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Bakery' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    $blog_page_id  = get_page_by_title( 'Journal' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    update_option( 'page_for_posts', $blog_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Nutritionist' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    $blog_page_id  = get_page_by_title( 'Journal' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    update_option( 'page_for_posts', $blog_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Lawyer' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    $blog_page_id  = get_page_by_title( 'Blog' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    update_option( 'page_for_posts', $blog_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Logistics' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Hosting' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Repair' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Oculist' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Biker' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Swimming Pool' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Coach' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    $footer = get_term_by( 'name', 'Footer', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      'footer' => $footer->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Data' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'ERP' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'School' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    $blog_page_id  = get_page_by_title( 'News & Events' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    update_option( 'page_for_posts', $blog_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Horse' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Farm' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Home' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Ice Cream' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Electrician' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Start' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Craftbeer' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Mall' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    $blog_page_id  = get_page_by_title( 'Events' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    update_option( 'page_for_posts', $blog_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Eco Food' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Honey' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Bar' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Lab' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Tea' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Model' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Car Specification' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Interior' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'HOME' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Animals' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Manicure' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Carpenter' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Consultant' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'HOME' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Mining' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Whiskey' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'HOME' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Pest Control' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Call Center' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Paintball' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'HOME' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Cleaner' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Shoes' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Eco Meat' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'HOME' );
    $blog_page_id  = get_page_by_title( 'OUR BLOG' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    update_option( 'page_for_posts', $blog_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Drone' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Garden' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'HOME' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Science' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    $blog_page_id  = get_page_by_title( 'News' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    update_option( 'page_for_posts', $blog_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Beauty' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    update_option( 'page_for_posts', $blog_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Jeweler' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Jeweler' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    update_option( 'page_for_posts', $blog_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Fire Brigade' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    update_option( 'page_for_posts', $blog_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Scooter Rental' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'HOME' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    update_option( 'page_for_posts', $blog_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Dance School' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'HOME' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    update_option( 'page_for_posts', $blog_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Fishing School' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    update_option( 'page_for_posts', $blog_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Driving' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'HOME' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    update_option( 'page_for_posts', $blog_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Industry Factory' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'HOME' );
    $blog_page_id  = get_page_by_title( 'BLOG' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    update_option( 'page_for_posts', $blog_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Rally Driver' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Marathon' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Start' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Funeral Home' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Boutique' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Boxing' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'HOME' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Aeroclub' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'HOME' );
    $blog_page_id  = get_page_by_title( 'NEWS AND EVENTS' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    update_option( 'page_for_posts', $blog_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Renovate' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Cakes' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Hello' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Taxi' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Start' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Xmas' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'START' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Language' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'HOME' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Pet' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Football' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'HOME' );
    $blog_page_id  = get_page_by_title( 'NEWS' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    update_option( 'page_for_posts', $blog_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Clothing' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'HOME' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Birthday' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Music School' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Fast Food' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'HOME' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Ceramic Store' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'HOME' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Astrology' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'HOME' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Pianist' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Florist' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Lingerie' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'HOME' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Food Truck' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'START' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Medical Shop' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Organic' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Glasses' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Artist' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Herbal' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'HOME' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Makeup' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Start' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Festival' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Start' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Catering' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Casino' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Start' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Marketing' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Underwater' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Model 2' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Charity' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Wedding 2' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Horse 2' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Church 2' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'About' );
    $blog_page_id  = get_page_by_title( 'News' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    update_option( 'page_for_posts', $blog_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Nursing Home 2' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Language 2' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'HOME' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Makeup 2' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Start' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Lingerie 2' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Psychologist' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'About' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Minimal Photography' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    $blog_page_id  = get_page_by_title( 'Blog' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    update_option( 'page_for_posts', $blog_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Jeweler Showcase' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'HOME' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Hairdresser' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Artist Minimal' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'HOME' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Fashion Retail' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Rattan Furniture' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'HOME' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Yoga Studio' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    
  } elseif (  $template['import_file_name'] === 'Optician' ) {
    
    // Assign menus to their locations.
    $primary = get_term_by( 'name', 'Main', 'nav_menu' );
    
    set_theme_mod( 'nav_menu_locations', [
      'primary' => $primary->term_id,
      ]
    );
    
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    
  }
  
}

elseif ( $theme == 'Architect' ) {
  // Assign menus to their locations.
  $main_menu = get_term_by( 'name', 'Main Menu', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', array(
    'primary' => $main_menu->term_id,
    )
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'Home' );
  //$blog_page_id  = get_page_by_title( 'Blog' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  //update_option( 'page_for_posts', $blog_page_id->ID );
}

elseif ( $theme == 'Beyond' ) {
  // Assign menus to their locations.
  $main_menu = get_term_by( 'name', 'Main Menu', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', array(
    'primary' => $main_menu->term_id,
    )
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'Home' );
  //$blog_page_id  = get_page_by_title( 'Blog' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  //update_option( 'page_for_posts', $blog_page_id->ID );
}

elseif ( $theme == 'Cafe' ) {
  // Assign menus to their locations.
  $main = get_term_by( 'name', 'Main', 'nav_menu' );
  $top_menu = get_term_by( 'name', 'Top Menu', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', array(
    'primary' => $main->term_id,
    'top_menu' => $top_menu->term_id,
    )
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'Home' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
}

elseif ( $theme == 'Church' ) {
  // Assign menus to their locations.
  $main = get_term_by( 'name', 'Main', 'nav_menu' );
  $footer = get_term_by( 'name', 'Footer', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', array(
    'primary' => $main->term_id,
    'footer' => $footer->term_id,
    )
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'Home' );
  $blog_page_id  = get_page_by_title( 'Blog' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  update_option( 'page_for_posts', $blog_page_id->ID );
}

elseif ( $theme == 'Construction' ) {
  // Assign menus to their locations.
  $main_menu = get_term_by( 'name', 'Main', 'nav_menu' );
  $footer = get_term_by( 'name', 'Footer', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', array(
    'primary' => $main_menu->term_id,
    'footer' => $footer->term_id,
    )
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'Home' );
  $blog_page_id  = get_page_by_title( 'Blog' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  update_option( 'page_for_posts', $blog_page_id->ID );
}

elseif ( $theme == 'Cryptocurrency' ) {
  // Assign menus to their locations.
  $main_menu = get_term_by( 'name', 'Main', 'nav_menu' );
  $footer_menu = get_term_by( 'name', 'Footer', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', array(
    'primary' => $main_menu->term_id,
    'footer' => $footer_menu->term_id,
    )
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'Home' );
  $blog_page_id  = get_page_by_title( 'Blog' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  update_option( 'page_for_posts', $blog_page_id->ID );
}

elseif ( $theme == 'Dark' ) {
  // Assign menus to their locations.
  $main_menu = get_term_by( 'name', 'Main', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', array(
    'primary' => $main_menu->term_id,
    )
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'Home' );
  $blog_page_id  = get_page_by_title( 'Blog' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  update_option( 'page_for_posts', $blog_page_id->ID );
}

elseif ( $theme == 'Edge' ) {
  // Assign menus to their locations.
  $main_menu = get_term_by( 'name', 'Main Menu', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', array(
    'primary' => $main_menu->term_id,
    )
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'Home' );
  $blog_page_id  = get_page_by_title( 'Blog' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  update_option( 'page_for_posts', $blog_page_id->ID );
}

elseif ( $theme == 'Education' ) {
  // Assign menus to their locations.
  $main = get_term_by( 'name', 'Main Menu', 'nav_menu' );
  $side = get_term_by( 'name', 'Side Menu', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', array(
    'primary' => $main->term_id,
    'side' => $side->term_id,
    )
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'Home' );
  $blog_page_id  = get_page_by_title( 'Blog' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  update_option( 'page_for_posts', $blog_page_id->ID );
}

elseif ( $theme == 'Employment' ) {
  // Assign menus to their locations.
  $main_menu = get_term_by( 'name', 'Main', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', array(
    'primary' => $main_menu->term_id,
    )
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'Home' );
  $blog_page_id  = get_page_by_title( 'Blog' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  update_option( 'page_for_posts', $blog_page_id->ID );
}

elseif ( $theme == 'Financial' ) {
  // Assign menus to their locations.
  $main = get_term_by( 'name', 'Main', 'nav_menu' );
  $footer = get_term_by( 'name', 'Footer', 'nav_menu' );
  $services = get_term_by( 'name', 'Services', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', array(
    'primary' => $main->term_id,
    'footer' => $footer->term_id,
    'services' => $services->term_id,
    )
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'Home' );
  $blog_page_id  = get_page_by_title( 'Blog' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  update_option( 'page_for_posts', $blog_page_id->ID );
}

elseif ( $theme == 'Fitness' ) {
  // Assign menus to their locations.
  $main_menu = get_term_by( 'name', 'Main Menu', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', array(
    'primary' => $main_menu->term_id,
    )
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'Home' );
  $blog_page_id  = get_page_by_title( 'Blog' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  update_option( 'page_for_posts', $blog_page_id->ID );
}

elseif ( $theme == 'Food' ) {
  // Assign menus to their locations.
  $main_menu = get_term_by( 'name', 'Main Menu', 'nav_menu' );
  $top_menu = get_term_by( 'name', 'Top Menu', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', array(
    'primary' => $main_menu->term_id,
    'top_menu' => $top_menu->term_id 
    )
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'Home' );
  //$blog_page_id  = get_page_by_title( 'Blog' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  //update_option( 'page_for_posts', $blog_page_id->ID );
}

elseif ( $theme == 'Forum' ) {
  // Assign menus to their locations.
  $main = get_term_by( 'name', 'Main', 'nav_menu' );
  $footer = get_term_by( 'name', 'Footer', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', array(
    'primary' => $main->term_id,
    'footer' => $footer->term_id,
    )
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'Home' );
  $blog_page_id  = get_page_by_title( 'Blog' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  update_option( 'page_for_posts', $blog_page_id->ID );
}

elseif ( $theme == 'Gym' ) {
  // Assign menus to their locations.
  $main_menu = get_term_by( 'name', 'Main Menu', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', array(
    'primary' => $main_menu->term_id,
    )
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'Home' );
  $blog_page_id  = get_page_by_title( 'Blog' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  update_option( 'page_for_posts', $blog_page_id->ID );
}

elseif ( $theme == 'Hotel' ) {
  // Assign menus to their locations.
  $main_menu = get_term_by( 'name', 'Main', 'nav_menu' );
  $footer_menu = get_term_by( 'name', 'Footer', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', array(
    'primary' => $main_menu->term_id,
    'footer' => $footer_menu->term_id,
    )
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'Home' );
  $blog_page_id  = get_page_by_title( 'Blog' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  update_option( 'page_for_posts', $blog_page_id->ID );
}

elseif ( $theme == 'Ink' ) {
  // Assign menus to their locations.
  $main_menu = get_term_by( 'name', 'Main Menu', 'nav_menu' );
  $side_menu = get_term_by( 'name', 'Side Menu', 'nav_menu' );
  $categories = get_term_by( 'name', 'Categories', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', array(
    'primary' => $main_menu->term_id,
    'side_menu' => $side_menu->term_id,
    'categories' => $categories->term_id 
    )
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'Home' );
  //$blog_page_id  = get_page_by_title( 'Blog' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  //update_option( 'page_for_posts', $blog_page_id->ID );
}

elseif ( $theme == 'IT' ) {
  // Assign menus to their locations.
  $main = get_term_by( 'name', 'Main', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', array(
    'primary' => $main->term_id,
    )
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'Home' );
  $blog_page_id  = get_page_by_title( 'Blog' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  update_option( 'page_for_posts', $blog_page_id->ID );
}

elseif ( $theme == 'Marvel' ) {
  // Assign menus to their locations.
  $main_menu = get_term_by( 'name', 'Main Menu', 'nav_menu' );
  $copyright_menu = get_term_by( 'name', 'Copyright Menu', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', array(
    'primary' => $main_menu->term_id,
    'copyright_menu' => $copyright_menu->term_id,
    )
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'Home' );
  //$blog_page_id  = get_page_by_title( 'Blog' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  //update_option( 'page_for_posts', $blog_page_id->ID );
}

elseif ( $theme == 'Mechanic' ) {
  // Assign menus to their locations.
  $main_menu = get_term_by( 'name', 'Main Menu', 'nav_menu' );
  $footer = get_term_by( 'name', 'Footer', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', array(
    'primary' => $main_menu->term_id,
    'footer' => $footer->term_id,
    )
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'Home' );
  //$blog_page_id  = get_page_by_title( 'Blog' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  //update_option( 'page_for_posts', $blog_page_id->ID );
}

elseif ( $theme == 'Medical' ) {
  // Assign menus to their locations.
  $main_menu = get_term_by( 'name', 'Main Menu', 'nav_menu' );
  $footer = get_term_by( 'name', 'Footer', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', array(
    'primary' => $main_menu->term_id,
    'footer' => $footer->term_id,
    )
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'Home' );
  //$blog_page_id  = get_page_by_title( 'Blog' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  //update_option( 'page_for_posts', $blog_page_id->ID );
}

elseif ( $theme == 'Minimalist' ) {
  // Assign menus to their locations.
  $main = get_term_by( 'name', 'Main Menu', 'nav_menu' );
  $side = get_term_by( 'name', 'Side Navigation', 'nav_menu' );
  $footer = get_term_by( 'name', 'Footer', 'nav_menu' );
  $fotter_right = get_term_by( 'name', 'Footer Right', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', array(
    'primary' => $main->term_id,
    'side' => $side->term_id,
    'footer' => $footer->term_id,
    'fotter_right' => $fotter_right->term_id,
    )
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'Home' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
}

elseif ( $theme == 'Music' ) {
  // Assign menus to their locations.
  $main_menu = get_term_by( 'name', 'Main', 'nav_menu' );
  $footer_menu = get_term_by( 'name', 'Footer', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', array(
    'primary' => $main_menu->term_id,
    'footer' => $footer_menu->term_id,
    )
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'Home' );
  //$blog_page_id  = get_page_by_title( 'Blog' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  //update_option( 'page_for_posts', $blog_page_id->ID );
}

elseif ( $theme == 'Nectar' ) {
  // Assign menus to their locations.
  $main_menu = get_term_by( 'name', 'Main / mobile', 'nav_menu' );
  $left = get_term_by( 'name', 'Left', 'nav_menu' );
  $right = get_term_by( 'name', 'Right', 'nav_menu' );
  $footer = get_term_by( 'name', 'Footer', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', array(
    'primary' => $main_menu->term_id,
    'left' => $left->term_id,
    'right' => $right->term_id,
    'footer' => $footer->term_id,
    )
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'Home' );
  //$blog_page_id  = get_page_by_title( 'Blog' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  //update_option( 'page_for_posts', $blog_page_id->ID );
}

elseif ( $theme == 'Nonprofit' ) {
  // Assign menus to their locations.
  $main_menu = get_term_by( 'name', 'Main', 'nav_menu' );
  $footer = get_term_by( 'name', 'Footer', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', array(
    'primary' => $main_menu->term_id,
    'footer' => $footer->term_id,
    )
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'Home' );
  $blog_page_id  = get_page_by_title( 'Blog' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  update_option( 'page_for_posts', $blog_page_id->ID );
}

elseif ( $theme == 'Peak' ) {
  // Assign menus to their locations.
  $main_menu = get_term_by( 'name', 'Main Menu', 'nav_menu' );
  $side_menu = get_term_by( 'name', 'Side', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', array(
    'primary' => $main_menu->term_id,
    'side_menu' => $side_menu->term_id 
    )
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'Home' );
  //$blog_page_id  = get_page_by_title( 'Blog' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  //update_option( 'page_for_posts', $blog_page_id->ID );
}

elseif ( $theme == 'Petshop' ) {
  // Assign menus to their locations.
  $main = get_term_by( 'name', 'Main', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', array(
    'primary' => $main->term_id,
    )
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'Home' );
  $blog_page_id  = get_page_by_title( 'Blog' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  update_option( 'page_for_posts', $blog_page_id->ID );
}

elseif ( $theme == 'Photography' ) {
  // Assign menus to their locations.
  $main = get_term_by( 'name', 'Main Menu', 'nav_menu' );
  $main = get_term_by( 'name', 'Footer Menu', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', array(
    'primary' => $main->term_id,
    'footer' => $footer->term_id,
    )
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'Home' );
  $blog_page_id  = get_page_by_title( 'Blog' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  update_option( 'page_for_posts', $blog_page_id->ID );
}

elseif ( $theme == 'Politic' ) {
  // Assign menus to their locations.
  $main = get_term_by( 'name', 'Main', 'nav_menu' );
  $footer = get_term_by( 'name', 'Footer', 'nav_menu' );
  $proposals = get_term_by( 'name', 'Proposals', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', array(
    'primary' => $main->term_id,
    'footer' => $footer->term_id,
    'proposals' => $proposals->term_id,
    )
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'Home' );
  $blog_page_id  = get_page_by_title( 'Blog' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  update_option( 'page_for_posts', $blog_page_id->ID );
}

elseif ( $theme == 'Rare' ) {
  // Assign menus to their locations.
  $main_menu = get_term_by( 'name', 'Main Menu', 'nav_menu' );
  $footer = get_term_by( 'name', 'Footer', 'nav_menu' );
  $side_navigation = get_term_by( 'Side Navigation', 'Main Menu', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', array(
    'primary' => $main_menu->term_id,
    'footer' => $footer->term_id,
    'side_navigation' => $side_navigation->term_id,
    )
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'Home' );
  //$blog_page_id  = get_page_by_title( 'Blog' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  //update_option( 'page_for_posts', $blog_page_id->ID );
}

elseif ( $theme == 'Realestate' ) {
  // Assign menus to their locations.
  $main = get_term_by( 'name', 'Main', 'nav_menu' );
  $footer = get_term_by( 'name', 'Footer', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', array(
    'primary' => $main->term_id,
    'footer' => $footer->term_id,
    )
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'Home' );
  $blog_page_id  = get_page_by_title( 'Blog' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  update_option( 'page_for_posts', $blog_page_id->ID );
}

elseif ( $theme == 'Resume' ) {
  // Assign menus to their locations.
  $main_menu = get_term_by( 'name', 'Main', 'nav_menu' );
  $portfolio_menu = get_term_by( 'name', 'Portfolio Item Page Menu', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', array(
    'primary' => $main_menu->term_id,
    'portfolio' => $portfolio_menu->term_id,
    )
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'Home' );
  //$blog_page_id  = get_page_by_title( 'Blog' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  //update_option( 'page_for_posts', $blog_page_id->ID );
}

elseif ( $theme == 'Salon' ) {
  // Assign menus to their locations.
  $main_menu = get_term_by( 'name', 'Main', 'nav_menu' );
  $footer_menu = get_term_by( 'name', 'Footer', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', array(
    'primary' => $main_menu->term_id,
    'footer' => $footer_menu->term_id,
    )
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'Home' );
  //$blog_page_id  = get_page_by_title( 'Blog' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  //update_option( 'page_for_posts', $blog_page_id->ID );
}

elseif ( $theme == 'Seller' ) {
  // Assign menus to their locations.
  $main_menu = get_term_by( 'name', 'Main Menu', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', array(
    'primary' => $main_menu->term_id,
    )
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'Home' );
  $blog_page_id  = get_page_by_title( 'Blog' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  update_option( 'page_for_posts', $blog_page_id->ID );
}

elseif ( $theme == 'Spark' ) {
  // Assign menus to their locations.
  $main_menu = get_term_by( 'name', 'Main Menu', 'nav_menu' );
  $side_menu = get_term_by( 'name', 'Side', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', array(
    'primary' => $main_menu->term_id,
    'side' => $side_menu->term_id 
    )
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'Home' );
  //$blog_page_id  = get_page_by_title( 'Blog' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  //update_option( 'page_for_posts', $blog_page_id->ID );
}

elseif ( $theme == 'Sport' ) {
  // Assign menus to their locations.
  $main_menu = get_term_by( 'name', 'Main Menu', 'nav_menu' );
  $one_page = get_term_by( 'name', 'One Page', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', array(
    'primary' => $main_menu->term_id,
    'one_page' => $one_page->term_id 
    )
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'Home' );
  //$blog_page_id  = get_page_by_title( 'Blog' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  //update_option( 'page_for_posts', $blog_page_id->ID );
}

elseif ( $theme == 'Stream' ) {
  // Assign menus to their locations.
  $main_menu = get_term_by( 'name', 'Main Menu', 'nav_menu' );
  $side_menu = get_term_by( 'name', 'Side', 'nav_menu' );
  $one_page = get_term_by( 'name', 'One Page', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', array(
    'primary' => $main_menu->term_id,
    'side_menu' => $side_menu->term_id,
    'one_page' => $one_page->term_id 
    )
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'Home' );
  //$blog_page_id  = get_page_by_title( 'Blog' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  //update_option( 'page_for_posts', $blog_page_id->ID );
}

elseif ( $theme == 'Traveler' ) {
  // Assign menus to their locations.
  $main_menu = get_term_by( 'name', 'Main', 'nav_menu' );
  $categories = get_term_by( 'name', 'Categories', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', array(
    'primary' => $main_menu->term_id,
    'categories' => $categories->term_id,
    )
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'Home' );
  //$blog_page_id  = get_page_by_title( 'Blog' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  //update_option( 'page_for_posts', $blog_page_id->ID );
}

elseif ( $theme == 'Visualmentor' ) {
  // Assign menus to their locations.
  $main = get_term_by( 'name', 'Main Menu', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', array(
    'primary' => $main->term_id,
    )
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'Home' );
  $blog_page_id  = get_page_by_title( 'Blog' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  update_option( 'page_for_posts', $blog_page_id->ID );
}

elseif ( $theme == 'Wedding' ) {
  // Assign menus to their locations.
  $main_menu = get_term_by( 'name', 'Main Menu', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', array(
    'primary' => $main_menu->term_id,
    )
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'Home' );
  $blog_page_id  = get_page_by_title( 'Blog' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  update_option( 'page_for_posts', $blog_page_id->ID );
}

elseif ( $theme == 'Winehouse' ) {
  // Assign menus to their locations.
  $main_menu = get_term_by( 'name', 'Main Menu', 'nav_menu' );
  $footer = get_term_by( 'name', 'Footer', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', array(
    'primary' => $main_menu->term_id,
    'footer' => $footer->term_id
    )
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'Home' );
  //$blog_page_id  = get_page_by_title( 'Blog' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  //update_option( 'page_for_posts', $blog_page_id->ID );
}

elseif ( $theme == 'Zenith' ) {
  // Assign menus to their locations.
  $main_menu = get_term_by( 'name', 'Main Menu', 'nav_menu' );
  $footer_menu = get_term_by( 'name', 'Footer Menu', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', array(
    'primary' => $main_menu->term_id,
    'footer' => $footer_menu->term_id 
    )
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'Home' );
  $blog_page_id  = get_page_by_title( 'Blog' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  update_option( 'page_for_posts', $blog_page_id->ID );
}