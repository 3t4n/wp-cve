<?php
/*
Plugin Name: Amministrazione Aperta
Plugin URI: https://wordpress.org/plugins/amministrazione-aperta/
Description: Software per la pubblicazione di concessioni (sovvenzioni, contributi, sussidi e vantaggi economici) e incarichi, anche in formato open data, come richiesto dal D.Lgs 33/2013.
Version: 3.8.2
Author: Marco Milesi
Author Email: milesimarco@outlook.com
Author URI: https://www.marcomilesi.com
License:
Copyright 2013 Marco Milesi (milesimarco@outlook.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.
This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*/

include_once 'settings.php';
include_once 'tablegen.php';

add_action('init', function() {
    $labels = array(
        'name' => _x('Contributi & Concessioni', 'spesa'),
        'singular_name' => _x('Spesa', 'spesa'),
        'add_new' => _x('Nuova voce', 'spesa'),
        'add_new_item' => _x('Nuovo Contributo o Concessione', 'spesa'),
        'edit_item' => _x('Modifica Spesa', 'spesa'),
        'new_item' => _x('Nuova Spesa', 'spesa'),
        'view_item' => _x('Visualizza Spesa', 'spesa'),
        'search_items' => _x('Cerca Spesa', 'spesa'),
        'not_found' => _x('Nessun elemento trovato', 'spesa'),
        'not_found_in_trash' => _x('Nessun elemento trovato', 'spesa'),
        'parent_item_colon' => _x('Parent Spesa:', 'spesa'),
        'menu_name' => _x('Concessioni', 'spesa')
    );
    $args   = array(
        'labels' => $labels,
        'hierarchical' => false,
        'description' => '',
        'supports' => array('title', 'post_tag', 'editor'),
        'public' => true,
		'show_ui' => true,
		'menu_position' => 38,
        'menu_icon' => 'dashicons-welcome-learn-more',
		'show_in_menu' => true,
        'show_in_nav_menus' => false,
        'publicly_queryable' => true,
        'exclude_from_search' => false,
        'has_archive' => false,
        'query_var' => false,
        'can_export' => true,
        'rewrite' => false,
        'capability_type' => 'post'
    );
    register_post_type('spesa', $args);
	
	
		$labels = array(
        'name' => _x('Incarichi & Consulenze', 'aa_incarichi'),
        'singular_name' => _x('Incarichi', 'aa_incarichi'),
        'add_new' => _x('Nuova voce', 'aa_incarichi'),
        'add_new_item' => _x('Nuovo Incarico o Consulenza', 'aa_incarichi'),
        'edit_item' => _x('Modifica Incarico', 'aa_incarichi'),
        'new_item' => _x('Nuova Spesa', 'aa_incarichi'),
        'view_item' => _x('Visualizza Spesa', 'aa_incarichi'),
        'search_items' => _x('Cerca Spesa', 'aa_incarichi'),
        'not_found' => _x('Nessun elemento trovato', 'aa_incarichi'),
        'not_found_in_trash' => _x('Nessun elemento trovato', 'aa_incarichi'),
        'parent_item_colon' => _x('Parent Spesa:', 'aa_incarichi'),
        'menu_name' => _x('Incarichi', 'aa_incarichi')
    );
    $args   = array(
        'labels' => $labels,
        'hierarchical' => false,
        'description' => '',
        'supports' => array('title', 'post_tag', 'editor'),
        'public' => true,
		'show_ui' => true,
		'menu_position' => 39,
		'menu_icon' => 'dashicons-businessman',
		'show_in_menu' => true,
        'show_in_nav_menus' => false,
        'publicly_queryable' => true,
        'exclude_from_search' => false,
        'has_archive' => false,
        'query_var' => false,
        'can_export' => true,
        'rewrite' => false,
        'capability_type' => 'post'
    );
    register_post_type('incarico', $args);
	
	//TASSONOMIA TIPI INCARICO
	$args = array( 
			'labels' => array( 
                'name' => _x( 'Tipo Incarico', 'tipo_incarico' ),
            ),
			'public' => true,
			'show_ui' => true,
			'show_in_nav_menus' => false,
			'show_tagcloud' => false,
			'show_admin_column' => true,
			'hierarchical' => true,
			'capabilities' => array('manage_terms' => 'utentealieno','edit_terms'   => 'utentealieno','delete_terms' => 'utentealieno'),
			'rewrite' => true,
			'query_var' => true
		);
		register_taxonomy( 'tipo_incarico', array('incarico'), $args );
	if(!term_exists('Incarichi conferiti o autorizzati ai propri dipendenti', 'tipo_incarico')) {
		wp_insert_term('Incarichi conferiti o autorizzati ai propri dipendenti', 'tipo_incarico');
	}
	if(!term_exists('Incarichi conferiti a dipendenti di altra Amministrazione', 'tipo_incarico')) {
		wp_insert_term('Incarichi conferiti a dipendenti di altra Amministrazione', 'tipo_incarico');
	}
	if(!term_exists('Incarichi conferiti a soggetti estranei alla Pubblica Amministrazione', 'tipo_incarico')) {
		wp_insert_term('Incarichi conferiti a soggetti estranei alla Pubblica Amministrazione', 'tipo_incarico');
	}
	if(term_exists('Incarichi conferiti o autorizzati a dipendenti di altra Amministrazione', 'tipo_incarico')) {
		$id_1 = get_term_by('name', 'Incarichi conferiti o autorizzati a dipendenti di altra Amministrazione', 'tipo_incarico');
		wp_delete_term($id_1->term_id, 'tipo_incarico');
	}
	if(term_exists('Incarichi conferiti o autorizzati a soggetti estranei alla Pubblica Amministrazione', 'tipo_incarico')) {
		$id_2 = get_term_by('name', 'Incarichi conferiti o autorizzati a soggetti estranei alla Pubblica Amministrazione', 'tipo_incarico');
		wp_delete_term($id_2->term_id, 'tipo_incarico');
	}
});

add_action('admin_init', function() {
    include(plugin_dir_path(__FILE__) . 'fields_spese.php');
    include(plugin_dir_path(__FILE__) . 'fields_incarichi.php');

    $arraya_v = get_plugin_data ( __FILE__ );
    $nuova_versione = $arraya_v['Version'];
    $versione_attuale = get_option('aa_version_number');
    if ($versione_attuale == '') {
        update_option( 'aa_version_number', $nuova_versione );
    } else if (version_compare($versione_attuale, $nuova_versione, '<') == '1') {
        update_option( 'aa_version_number', $nuova_versione );
    }

});

if(!(function_exists('wpgov_register_taxonomy_areesettori'))){
add_action( 'init', 'wpgov_register_taxonomy_areesettori' );

    function wpgov_register_taxonomy_areesettori() {

        $labels = array(
            'name' => _x( 'Uffici - Settori - Centri di costo', 'areesettori' ),
            'singular_name' => _x( 'Settore - Centro di costo', 'areesettori' ),
            'search_items' => _x( 'Cerca in Settori - Centri di costo', 'areesettori' ),
            'popular_items' => _x( 'Settori - Centri di costo Più usati', 'areesettori' ),
            'all_items' => _x( 'Tutti i Centri di costo', 'areesettori' ),
            'parent_item' => _x( 'Parent Settore - Centro di costo', 'areesettori' ),
            'parent_item_colon' => _x( 'Parent Settore - Centro di costo:', 'areesettori' ),
            'edit_item' => _x( 'Modifica Settore - Centro di costo', 'areesettori' ),
            'update_item' => _x( 'Aggiorna Settore - Centro di costo', 'areesettori' ),
            'add_new_item' => _x( 'Aggiungi Nuovo Settore - Centro di costo', 'areesettori' ),
            'new_item_name' => _x( 'Nuovo Settore - Centro di costo', 'areesettori' ),
            'separate_items_with_commas' => _x( 'Separate settori - centri di costo with commas', 'areesettori' ),
            'add_or_remove_items' => _x( 'Add or remove settori - centri di costo', 'areesettori' ),
            'choose_from_most_used' => _x( 'Choose from the most used settori - centri di costo', 'areesettori' ),
            'menu_name' => _x( 'Uffici & Settori', 'areesettori' ),
        );

        $args = array(
            'labels' => $labels,
            'public' => true,
            'show_in_nav_menus' => false,
            'show_ui' => true,
            'show_tagcloud' => false,
            'show_admin_column' => true,
            'hierarchical' => true,
            'rewrite' => true,
            'query_var' => true
        );
        register_taxonomy( 'areesettori', array('incarico', 'spesa',  'avcp', 'amm-trasparente'), $args );
    }
}

add_filter('enter_title_here', function($title) {
    $screen = get_current_screen();
    if ('spesa' == $screen->post_type) {
        $title = 'Inserire la ragione del contributo o concessione';
    } else if ('incarico' == $screen->post_type) {
        $title = 'Inserire la ragione dell\'incarico o Progetto';
    }
    return $title;
});


function ammap_func($shortcode_attributes) {
    $shortcode_attributes = shortcode_atts(
        array(
            'anno' => 'all',
            'grafico' => '0',
            'incarico' => '0', // 0= tutti i tipi di incarico || 1= incarichi ai propri dipendenti || 2= incarichi a dipendenti altra pa || 3= incarichi esterni
            'tipo' => 'spesa' // "spesa" (default) / "incarico"
        ), $shortcode_attributes
    );
    wp_enqueue_script( 'ammap-tablegen-js' );
    wp_enqueue_script( 'ammap-tablegen-excellent-js' );

    ob_start();
    
    ammap_tablegen( $shortcode_attributes );
    $atshortcode = ob_get_clean();
    return $atshortcode;
}
add_shortcode('ammap', 'ammap_func');
add_shortcode('aa', 'ammap_func');

add_action('admin_init', function() {
    register_setting( 'aa_options_group', 'aa_disabilita_visauomatica_allegati', 'intval');
} );

add_action( 'wp_enqueue_scripts', function() {
    wp_register_script( 'ammap-tablegen-excellent-js',  plugin_dir_url(__FILE__).'js/excellentexport.min.js', array(), null, true );
    wp_register_script( 'ammap-tablegen-js',  plugin_dir_url(__FILE__).'js/table.js', array(), null, true );
} );

add_filter('the_content', function($content) {
    global $post;
    if ( $post->post_type == 'spesa') {
        $content .= esc_html( get_post_meta(get_the_ID(), 'ammap_wysiwyg', true) ). '</b><br>';
        $content .= 'Importo: <b>€ ' . esc_html( get_post_meta(get_the_ID(), 'ammap_importo', true) ). '</b><br>';
        $content .= 'Beneficiario: <b>' . esc_html( get_post_meta(get_the_ID(), 'ammap_beneficiario', true) ). '</b><br>';
        $content .= 'Dati Fiscali: <b>' . esc_html( get_post_meta(get_the_ID(), 'ammap_fiscale', true) ). '</b><br>';
        $content .= 'Norma: <b>' . esc_html( get_post_meta(get_the_ID(), 'ammap_norma', true) ). '</b><br>';
        $content .= 'Modalità: <b>' . esc_html( get_post_meta(get_the_ID(), 'ammap_assegnazione', true) ). '</b><br>';
        $content .= 'Responsabile: <b>' . esc_html( get_post_meta(get_the_ID(), 'ammap_responsabile', true) ). '</b><br>';
        $content .= 'Determina: <b>' . esc_html( get_post_meta(get_the_ID(), 'ammap_determina', true) ). '</b><br>';
        $content .= 'Data: <b>' . esc_html( date("d/m/Y", strtotime( get_post_meta(get_the_ID(), 'ammap_data', true) ) ) ). '</b><br>';
    } else if ( $post->post_type == 'incarico') {
        $content .= esc_html( get_post_meta(get_the_ID(), 'ammap_wysiwyg', true) ). '</b><br>';
        $content .= 'Soggetto Percettore: <b> ' . esc_html( get_post_meta(get_the_ID(), 'ammap_beneficiario', true) ). '</b><br>';
        $content .= 'Compenso Lordo Previsto: <b>€ ' . esc_html( get_post_meta(get_the_ID(), 'ammap_importo_previsto', true) ). '</b><br>';
        $content .= 'Compenso Lordo Erogato: <b>€ ' . esc_html( get_post_meta(get_the_ID(), 'ammap_importo', true) ). '</b><br>';
        if ( get_post_meta(get_the_ID(), 'ammap_data_incarico', true) ) {
            $content .= 'Data incarico: <b>' . esc_html( date("d/m/Y", strtotime( get_post_meta(get_the_ID(), 'ammap_data_incarico', true ) ) ) ). '</b><br>';
        }
        if ( get_post_meta(get_the_ID(), 'ammap_protocollo', true) ) {
            $content .= 'Numero protocollo: <b>' . esc_html( get_post_meta(get_the_ID(), 'ammap_protocollo', true) ). '</b><br>';
        }
        $content .= 'Data inizio: <b>' . esc_html( date("d/m/Y", strtotime( get_post_meta(get_the_ID(), 'ammap_data_inizio', true ) ) ) ). '</b><br>';
        $content .= 'Data fine: <b>' . esc_html( date("d/m/Y", strtotime( get_post_meta(get_the_ID(), 'ammap_data_fine', true ) ) ) ). '</b><br>';
    }

    return $content;
} );

add_action( 'admin_init', function() {
    global $current_user;
    $user_id = $current_user->ID;
    if ( isset($_GET['aa_nag_ignore']) && '0' == $_GET['aa_nag_ignore'] ) {
            add_user_meta($user_id, 'aa_ignore_notice', 'true', true);
    }
} );