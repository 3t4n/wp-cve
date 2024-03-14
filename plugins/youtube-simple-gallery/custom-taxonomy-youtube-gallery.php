<?php
add_action( 'init', 'register_taxonomy_categoria_videos' );
function register_taxonomy_categoria_videos() {
    $labels = array(
	    'name' => __( 'Categorias', 'youtube-simple-gallery' ),
	    'singular_name' => __( 'Categoria', 'youtube-simple-gallery' ),
	    'search_items' => __( 'Todas as Categorias', 'youtube-simple-gallery' ),
	    'popular_items' => __( 'Categorias Popular', 'youtube-simple-gallery' ),
	    'all_items' => __( 'Todas Categorias', 'youtube-simple-gallery' ),
	    'parent_item' => __( 'Parent Categoria', 'youtube-simple-gallery' ),
	    'parent_item_colon' => __( 'Parent Categoria:', 'youtube-simple-gallery' ),
	    'edit_item' => __( 'Editar Categoria', 'youtube-simple-gallery' ),
	    'update_item' => __( 'Atualizar Categoria', 'youtube-simple-gallery' ),
	    'add_new_item' => __( 'Adicionar Nova Categoria', 'youtube-simple-gallery' ),
	    'new_item_name' => __( 'Nova Categoria', 'youtube-simple-gallery' ),
	    'add_or_remove_items' => __( 'Add or remove categorias', 'youtube-simple-gallery' ),
	    'choose_from_most_used' => __( 'Choose from the most used categorias', 'youtube-simple-gallery' ),
	    'menu_name' => __( 'Categorias', 'youtube-simple-gallery' ),
    );
    $args = array(
	    'labels' => $labels,
	    'public' => true,
	    'show_in_nav_menus' => true,
	    'show_ui' => true,
	    'show_tagcloud' => true,
	    'show_admin_column' => true,
	    'hierarchical' => true,
	    'rewrite' => true,
	    'query_var' => true
    );
register_taxonomy( 'youtube-videos', array('youtube-gallery'), $args );
}


