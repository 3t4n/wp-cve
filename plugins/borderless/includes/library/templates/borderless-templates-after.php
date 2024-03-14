<?php 

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( $template['import_file_name'] == 'Plant Shop' ) {
      
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
  
} elseif ( $template['import_file_name'] == 'Yoga' ) {
  
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
  
} elseif ( $template['import_file_name'] == 'Business' ) {
  
  $primary = get_term_by( 'name', 'Main', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', [
    'primary' => $primary->term_id,
    ]
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'Home' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  
} elseif ( $template['import_file_name'] == 'Winery' ) {
  
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
  
} elseif ( $template['import_file_name'] == 'Nutritionist' ) {
  
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
  
} elseif ( $template['import_file_name'] == 'Dentist' ) {
  
  $primary = get_term_by( 'name', 'Main', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', [
    'primary' => $primary->term_id,
    ]
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'Home' );
  $blog_page_id  = get_page_by_title( 'Latest News' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  update_option( 'page_for_posts', $blog_page_id->ID );
  
} elseif ( $template['import_file_name'] == 'Salon' ) {
  
  $primary = get_term_by( 'name', 'Main', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', [
    'primary' => $primary->term_id,
    ]
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'Home' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  
} elseif ( $template['import_file_name'] == 'Electrician' ) {
  
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
  
} elseif ( $template['import_file_name'] == 'Mechanic' ) {
  
  $primary = get_term_by( 'name', 'Main', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', [
    'primary' => $primary->term_id,
    ]
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'Mechanic' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  
} elseif ( $template['import_file_name'] == 'Nursing Home' ) {
  
  $primary = get_term_by( 'name', 'Main', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', [
    'primary' => $primary->term_id,
    ]
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'Home' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  
} elseif ( $template['import_file_name'] == 'Fashion' ) {
  
  $primary = get_term_by( 'name', 'Main', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', [
    'primary' => $primary->term_id,
    ]
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'Home' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  
} elseif ( $template['import_file_name'] == 'Tea' ) {
  
  $primary = get_term_by( 'name', 'Main', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', [
    'primary' => $primary->term_id,
    ]
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'Home' );
  $blog_page_id  = get_page_by_title( 'Tea Shop' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  update_option( 'page_for_posts', $blog_page_id->ID );
  
} elseif ( $template['import_file_name'] == 'Flower' ) {
  
  $primary = get_term_by( 'name', 'Main', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', [
    'primary' => $primary->term_id,
    ]
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'Home' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  
} elseif ( $template['import_file_name'] == 'Pizza' ) {
  
  $primary = get_term_by( 'name', 'Main', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', [
    'primary' => $primary->term_id,
    ]
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'HOME' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  
} elseif ( $template['import_file_name'] == 'Pest Control' ) {
  
  $primary = get_term_by( 'name', 'Main', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', [
    'primary' => $primary->term_id,
    ]
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'Home' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  
} elseif ( $template['import_file_name'] == 'Wedding' ) {
  
  $primary = get_term_by( 'name', 'Main', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', [
    'primary' => $primary->term_id,
    ]
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'Home' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  
} elseif ( $template['import_file_name'] == 'Lingerie' ) {
  
  $primary = get_term_by( 'name', 'Main', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', [
    'primary' => $primary->term_id,
    ]
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'Home' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  
} elseif ( $template['import_file_name'] == 'Music School' ) {
  
  $primary = get_term_by( 'name', 'Main', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', [
    'primary' => $primary->term_id,
    ]
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'Home' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  
} elseif ( $template['import_file_name'] == 'Pianist' ) {
  
  $primary = get_term_by( 'name', 'Main', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', [
    'primary' => $primary->term_id,
    ]
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'Home' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  
} elseif ( $template['import_file_name'] == 'Manicure' ) {
  
  $primary = get_term_by( 'name', 'Main', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', [
    'primary' => $primary->term_id,
    ]
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'Home' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  
} elseif ( $template['import_file_name'] == 'Ice Cream' ) {
  
  $primary = get_term_by( 'name', 'Main', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', [
    'primary' => $primary->term_id,
    ]
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'Home' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  
} elseif ( $template['import_file_name'] == 'Home' ) {
  
  $primary = get_term_by( 'name', 'Main', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', [
    'primary' => $primary->term_id,
    ]
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'Home' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  
} elseif ( $template['import_file_name'] == 'Aromatherapy' ) {
  
  $primary = get_term_by( 'name', 'Main', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', [
    'primary' => $primary->term_id,
    ]
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'Home' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  
} elseif ( $template['import_file_name'] == 'Fire Brigade' ) {
  
  $primary = get_term_by( 'name', 'Main', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', [
    'primary' => $primary->term_id,
    ]
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'Home' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  
} elseif ( $template['import_file_name'] == 'Makeup' ) {
  
  $primary = get_term_by( 'name', 'Main', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', [
    'primary' => $primary->term_id,
    ]
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'Home' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  
} elseif ( $template['import_file_name'] == 'Barber' ) {
  
  $primary = get_term_by( 'name', 'Main', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', [
    'primary' => $primary->term_id,
    ]
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'Home' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  
} elseif ( $template['import_file_name'] == 'Eco Food' ) {
  
  $primary = get_term_by( 'name', 'Main', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', [
    'primary' => $primary->term_id,
    ]
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'Home' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  
} elseif ( $template['import_file_name'] == 'Massage' ) {
  
  $primary = get_term_by( 'name', 'Main', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', [
    'primary' => $primary->term_id,
    ]
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'Home' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  
} elseif ( $template['import_file_name'] == 'Shoes' ) {
  
  $primary = get_term_by( 'name', 'Main', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', [
    'primary' => $primary->term_id,
    ]
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'Home' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  
} elseif ( $template['import_file_name'] == 'Oculist' ) {
  
  $primary = get_term_by( 'name', 'Main', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', [
    'primary' => $primary->term_id,
    ]
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'Home' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  
} elseif ( $template['import_file_name'] == 'Carpenter' ) {
  
  $primary = get_term_by( 'name', 'Main', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', [
    'primary' => $primary->term_id,
    ]
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'Home' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  
} elseif ( $template['import_file_name'] == 'Honey' ) {
  
  $primary = get_term_by( 'name', 'Main', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', [
    'primary' => $primary->term_id,
    ]
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'Home' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  
} elseif ( $template['import_file_name'] == 'Cleaner' ) {
  
  $primary = get_term_by( 'name', 'Main', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', [
    'primary' => $primary->term_id,
    ]
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'Home' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  
} elseif ( $template['import_file_name'] == 'Scooter Rental' ) {
  
  $primary = get_term_by( 'name', 'Main', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', [
    'primary' => $primary->term_id,
    ]
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'Home' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  
} elseif ( $template['import_file_name'] == 'Barman' ) {
  
  $primary = get_term_by( 'name', 'Main', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', [
    'primary' => $primary->term_id,
    ]
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'Home' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  
} elseif ( $template['import_file_name'] == 'Model' ) {
  
  $primary = get_term_by( 'name', 'Main', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', [
    'primary' => $primary->term_id,
    ]
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'Home' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  
} elseif ( $template['import_file_name'] == 'Charity' ) {
  
  $primary = get_term_by( 'name', 'Main', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', [
    'primary' => $primary->term_id,
    ]
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'Home' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  
} elseif ( $template['import_file_name'] == 'Church' ) {
  
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
  
} elseif ( $template['import_file_name'] == 'Horse' ) {
  
  $primary = get_term_by( 'name', 'Main', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', [
    'primary' => $primary->term_id,
    ]
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'Home' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  
} elseif ( $template['import_file_name'] == 'Paintball' ) {
  
  $primary = get_term_by( 'name', 'Main', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', [
    'primary' => $primary->term_id,
    ]
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'HOME' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  
} elseif ( $template['import_file_name'] == 'Organic' ) {
  
  $primary = get_term_by( 'name', 'Main', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', [
    'primary' => $primary->term_id,
    ]
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'HOME' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  
} elseif ( $template['import_file_name'] == 'Renovate' ) {
  
  $primary = get_term_by( 'name', 'Main', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', [
    'primary' => $primary->term_id,
    ]
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'HOME' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  
} elseif ( $template['import_file_name'] == 'Rally Driver' ) {
  
  $primary = get_term_by( 'name', 'Main', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', [
    'primary' => $primary->term_id,
    ]
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'Home' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  
} elseif ( $template['import_file_name'] == 'Artist Minimal' ) {
  
  $primary = get_term_by( 'name', 'Main', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', [
    'primary' => $primary->term_id,
    ]
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'HOME' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  
} elseif ( $template['import_file_name'] == 'Jewelry Showcase' ) {
  
  $primary = get_term_by( 'name', 'Main', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', [
    'primary' => $primary->term_id,
    ]
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'Home' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  
} elseif ( $template['import_file_name'] == 'Yoga Studio' ) {
  
  $primary = get_term_by( 'name', 'Main', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', [
    'primary' => $primary->term_id,
    ]
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'Home' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  
} elseif ( $template['import_file_name'] == 'Rattan Furniture' ) {
  
  $primary = get_term_by( 'name', 'Main', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', [
    'primary' => $primary->term_id,
    ]
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'HOME' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  
} elseif ( $template['import_file_name'] == 'Perfume Store' ) {
  
  $primary = get_term_by( 'name', 'Main', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', [
    'primary' => $primary->term_id,
    ]
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'Home' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  
} elseif ( $template['import_file_name'] == 'Hairdresser' ) {
  
  $primary = get_term_by( 'name', 'Main', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', [
    'primary' => $primary->term_id,
    ]
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'HOME' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  
} elseif ( $template['import_file_name'] == 'Shoe Shop' ) {
  
  $primary = get_term_by( 'name', 'Main', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', [
    'primary' => $primary->term_id,
    ]
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'Home' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  
} elseif ( $template['import_file_name'] == 'Pharmacy' ) {
  
  $primary = get_term_by( 'name', 'Main', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', [
    'primary' => $primary->term_id,
    ]
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'HOME' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  
} elseif ( $template['import_file_name'] == 'Pasta' ) {
  
  $primary = get_term_by( 'name', 'Main', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', [
    'primary' => $primary->term_id,
    ]
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'HOME' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  
} elseif ( $template['import_file_name'] == 'Chocolate' ) {
  
  $primary = get_term_by( 'name', 'Main', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', [
    'primary' => $primary->term_id,
    ]
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'Home' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  
} elseif ( $template['import_file_name'] == 'Floristry' ) {
  
  $primary = get_term_by( 'name', 'Main', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', [
    'primary' => $primary->term_id,
    ]
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'Home' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  
} elseif ( $template['import_file_name'] == 'Crossfit' ) {
  
  $primary = get_term_by( 'name', 'Main', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', [
    'primary' => $primary->term_id,
    ]
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'Home' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  
} elseif ( $template['import_file_name'] == 'Ballet' ) {
  
  $primary = get_term_by( 'name', 'Main', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', [
    'primary' => $primary->term_id,
    ]
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'Home' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  
} elseif ( $template['import_file_name'] == 'Architect' ) {
  
  $primary = get_term_by( 'name', 'Main', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', [
    'primary' => $primary->term_id,
    ]
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'HOME' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  
} elseif ( $template['import_file_name'] == 'Antique Shop' ) {
  
  $primary = get_term_by( 'name', 'Main', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', [
    'primary' => $primary->term_id,
    ]
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'Home' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  
} elseif ( $template['import_file_name'] == 'Biker Club' ) {
  
  $primary = get_term_by( 'name', 'Main', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', [
    'primary' => $primary->term_id,
    ]
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'Home' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  
} elseif ( $template['import_file_name'] == 'Security' ) {
  
  $primary = get_term_by( 'name', 'Main', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', [
    'primary' => $primary->term_id,
    ]
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'Home' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  
} elseif ( $template['import_file_name'] == 'Resort' ) {
  
  $primary = get_term_by( 'name', 'Main', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', [
    'primary' => $primary->term_id,
    ]
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'Home' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  
} elseif ( $template['import_file_name'] == 'Modeling Agency' ) {
  
  $primary = get_term_by( 'name', 'Main', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', [
    'primary' => $primary->term_id,
    ]
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'Home' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  
} elseif ( $template['import_file_name'] == 'Influencer' ) {
  
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
  
} elseif ( $template['import_file_name'] == 'Mining' ) {
  
  $primary = get_term_by( 'name', 'Main', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', [
    'primary' => $primary->term_id,
    ]
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'Home' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  
} elseif ( $template['import_file_name'] == 'Manicure 2' ) {
  
  $primary = get_term_by( 'name', 'Main', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', [
    'primary' => $primary->term_id,
    ]
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'Home' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  
} elseif ( $template['import_file_name'] == 'Cakes' ) {
  
  $primary = get_term_by( 'name', 'Main', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', [
    'primary' => $primary->term_id,
    ]
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'Home' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  
} elseif ( $template['import_file_name'] == 'Fresh Vegetables' ) {
  
  $primary = get_term_by( 'name', 'Main', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', [
    'primary' => $primary->term_id,
    ]
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'Home' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  
} elseif ( $template['import_file_name'] == 'Car' ) {
  
  $primary = get_term_by( 'name', 'Main', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', [
    'primary' => $primary->term_id,
    ]
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'Home' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  
} elseif ( $template['import_file_name'] == 'Psychologist' ) {
  
  $primary = get_term_by( 'name', 'Main', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', [
    'primary' => $primary->term_id,
    ]
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'Home' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  
} elseif ( $template['import_file_name'] == 'Lawyer' ) {
  
  $primary = get_term_by( 'name', 'Main', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', [
    'primary' => $primary->term_id,
    ]
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'Home' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  
} elseif ( $template['import_file_name'] == 'Photo' ) {
  
  $primary = get_term_by( 'name', 'Main', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', [
    'primary' => $primary->term_id,
    ]
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'Home' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  
} elseif ( $template['import_file_name'] == 'Herbal' ) {
  
  $primary = get_term_by( 'name', 'Main', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', [
    'primary' => $primary->term_id,
    ]
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'Home' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  
} elseif ( $template['import_file_name'] == 'Newborn Photo Shoot' ) {
  
  $primary = get_term_by( 'name', 'Main', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', [
    'primary' => $primary->term_id,
    ]
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'Home' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  
} elseif ( $template['import_file_name'] == 'Bakery' ) {
  
  $primary = get_term_by( 'name', 'Main', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', [
    'primary' => $primary->term_id,
    ]
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'Home' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  
} elseif ( $template['import_file_name'] == 'Fashion Agency' ) {
  
  $primary = get_term_by( 'name', 'Main', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', [
    'primary' => $primary->term_id,
    ]
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'Home' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  
} elseif ( $template['import_file_name'] == 'Winery 2' ) {
  
  $primary = get_term_by( 'name', 'Main', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', [
    'primary' => $primary->term_id,
    ]
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'Home' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  
} elseif ( $template['import_file_name'] == 'Food Truck' ) {
  
  $primary = get_term_by( 'name', 'Main', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', [
    'primary' => $primary->term_id,
    ]
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'Home' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  
} elseif ( $template['import_file_name'] == 'Eco Food 2' ) {
  
  $primary = get_term_by( 'name', 'Main', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', [
    'primary' => $primary->term_id,
    ]
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'Home' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  
} elseif ( $template['import_file_name'] == 'Extreme Sports' ) {
  
  $primary = get_term_by( 'name', 'Main', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', [
    'primary' => $primary->term_id,
    ]
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'Home' );
  $blog_page_id  = get_page_by_title( 'Latest News' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  update_option( 'page_for_posts', $blog_page_id->ID );
  
} elseif ( $template['import_file_name'] == 'Landscaping' ) {
  
  $primary = get_term_by( 'name', 'Main', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', [
    'primary' => $primary->term_id,
    ]
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'Home' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  
} elseif ( $template['import_file_name'] == 'Animal Shelter' ) {
  
  $primary = get_term_by( 'name', 'Main', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', [
    'primary' => $primary->term_id,
    ]
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'Home' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  
} elseif ( $template['import_file_name'] == 'Massage 2' ) {
  
  $primary = get_term_by( 'name', 'Main', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', [
    'primary' => $primary->term_id,
    ]
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'HOME' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  
} elseif ( $template['import_file_name'] == 'Cottage' ) {
  
  $primary = get_term_by( 'name', 'Main', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', [
    'primary' => $primary->term_id,
    ]
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'Home' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  
} elseif ( $template['import_file_name'] == 'Cleaner 2' ) {
  
  $primary = get_term_by( 'name', 'Main', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', [
    'primary' => $primary->term_id,
    ]
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'Home' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  
} elseif ( $template['import_file_name'] == 'Stylist' ) {
  
  $primary = get_term_by( 'name', 'Main', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', [
    'primary' => $primary->term_id,
    ]
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'Home' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  
} elseif ( $template['import_file_name'] == 'Vet' ) {
  
  $primary = get_term_by( 'name', 'Main', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', [
    'primary' => $primary->term_id,
    ]
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'Home' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  
} elseif ( $template['import_file_name'] == 'Biker' ) {
  
  $primary = get_term_by( 'name', 'Main', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', [
    'primary' => $primary->term_id,
    ]
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'HOME' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  
} elseif ( $template['import_file_name'] == 'Wedding Dresses' ) {
  
  $primary = get_term_by( 'name', 'Main', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', [
    'primary' => $primary->term_id,
    ]
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'Home' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  
} elseif ( $template['import_file_name'] == 'Hairdresser 2' ) {
  
  $primary = get_term_by( 'name', 'Main', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', [
    'primary' => $primary->term_id,
    ]
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'Home' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  
} elseif ( $template['import_file_name'] == 'Upholsterer' ) {
  
  $primary = get_term_by( 'name', 'Main', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', [
    'primary' => $primary->term_id,
    ]
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'Start' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  
} elseif ( $template['import_file_name'] == 'Construction' ) {
  
  $primary = get_term_by( 'name', 'Main', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', [
    'primary' => $primary->term_id,
    ]
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'HOME' );
  $blog_page_id  = get_page_by_title( 'ARTICLES' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  update_option( 'page_for_posts', $blog_page_id->ID );
  
} elseif ( $template['import_file_name'] == 'Landscaper' ) {
  
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
  
} elseif ( $template['import_file_name'] == 'Interior Design' ) {
  
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
  
} elseif ( $template['import_file_name'] == 'Billiard' ) {
  
  $primary = get_term_by( 'name', 'Main', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', [
    'primary' => $primary->term_id,
    ]
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'HOME' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  
} elseif ( $template['import_file_name'] == 'Model 2' ) {
  
  $primary = get_term_by( 'name', 'Main', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', [
    'primary' => $primary->term_id,
    ]
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'Home' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  
} elseif ( $template['import_file_name'] == 'Optics' ) {
  
  $primary = get_term_by( 'name', 'Main', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', [
    'primary' => $primary->term_id,
    ]
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'Home' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  
} elseif ( $template['import_file_name'] == 'Internet' ) {
  
  $primary = get_term_by( 'name', 'Main', 'nav_menu' );
  
  set_theme_mod( 'nav_menu_locations', [
    'primary' => $primary->term_id,
    ]
  );
  
  // Assign front page and posts page (blog page).
  $front_page_id = get_page_by_title( 'Start' );
  
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  
} elseif ( $template['import_file_name'] == 'Taxi' ) {
  
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
  
} elseif ( $template['import_file_name'] == 'Pregnancy' ) {
  
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
  
} 