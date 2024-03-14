<?php



add_action('customize_register','nd_spt_customizer_nd_sport');
function nd_spt_customizer_nd_sport( $wp_customize ) {
  

	//ADD panel
	$wp_customize->add_panel( 'nd_spt_customizer_sports', array(
	  'title' => __( 'ND Sports Booking' ),
	  'capability' => 'edit_theme_options',
	  'theme_supports' => '',
	  'description' => __( 'Plugin Settings' ), // html tags such as <p>.
	  'priority' => 320, // Mixed with top-level-section hierarchy.
	) );


}



//get all options
foreach ( glob ( plugin_dir_path( __FILE__ ) . "*/index.php" ) as $file ){
  include_once realpath($file);
}
