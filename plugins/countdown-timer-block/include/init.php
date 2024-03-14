<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
function fd_ctfg_block_assets() { 
	wp_enqueue_script('jquery');  
	// Styles.
	wp_enqueue_style(
		'countdown_timer_block-cgb-style-css', // Handle.
		plugins_url( 'dist/blocks.style.build.css', dirname( __FILE__ ) ), 
		array('wp-editor') 		
	);	
	// Clock CSS.	
	wp_enqueue_style('fdcountcss',plugins_url( 'build/assets/css/fdwpflipclock.css', dirname( __FILE__ ) ), array() );
	// Clock Scripts.	  	
	wp_register_script('fdcountscript', plugins_url( 'build/assets/js/fdwpflip.js', dirname( __FILE__ ) ), array('jquery'),'1.0.0', true); 
	wp_enqueue_script('fdcountscript');	
	wp_register_script(	'fdtimerblocks',	plugins_url( 'build/assets/js/fdwpflipclock.js', dirname( __FILE__ ) ),	array('jquery'), '1.0.0',true);
	wp_enqueue_script('fdtimerblocks');
}
add_action( 'enqueue_block_assets', 'fd_ctfg_block_assets' );


/**
 * Enqueue WordPress Page Builder block assets for backend editor.
 * 
 */
function fd_ctfg_block_editor_assets() { 	
	// Styles.
	wp_enqueue_style(
		'countdown_timer-cgb-block-editor-css', 
		plugins_url( 'dist/blocks.editor.build.css', dirname( __FILE__ ) ), 
		array( 'wp-edit-blocks' ) 		
	);	
	wp_localize_script(
            'countdown_timer-cgb-block-js',
            'PremiumBlocksSettings',
            array(
				'defaultAuthImg'    => FD_CTIMER_BLOCKURL . 'build/assets/img/clock4.png'                
			)
        );
}
add_action( 'enqueue_block_editor_assets', 'fd_ctfg_block_editor_assets' );

function fd_ctfg_block_localize_script() {    
	wp_register_script(
		'countdown_timer-cgb-block-js', 
		plugins_url( '/dist/blocks.build.js', dirname( __FILE__ ) ), 
		array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor' ), 	
		true
	);
	
    register_block_type( 'cgb/fdcountdown-timer', array(
        'editor_script' => 'countdown_timer-cgb-block-js',		
    ) );
}
add_action( 'init', 'fd_ctfg_block_localize_script' );