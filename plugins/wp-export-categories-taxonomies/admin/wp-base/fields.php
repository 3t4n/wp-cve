<?php
	$options = get_option( $this->options_name );
	/* General Settings
	===========================================*/
	$this->settings['cats'] = array(
		'section' => 'general',
		'title'   => __( 'Export Categories?' , $this->plugin_slug),
		'type'    => 'checkbox',
		'std'     => array('yes'),
		'desc'    => __( 'Check to export all categories', $this->plugin_slug),
		'choices' => array( 'yes' => __('Yes', $this->plugin_slug))
	);


	
	$this->settings['tags'] = array(
		'section' => 'general',
		'title'   => __( 'Export Tags?' , $this->plugin_slug),
		'type'    => 'checkbox',
		'std'     => array('yes'),
		'desc'    => __( 'Check to export all tags', $this->plugin_slug),
		'choices' => array( 'yes' => __('Yes', $this->plugin_slug))
	);
	
	
	
	$taxs = get_taxonomies( array('_builtin'=> false));
	if( !empty( $taxs))
	{
		$this->settings['taxs'] = array(
			'section' => 'general',
			'title'   => __( 'Taxonomies' , $this->plugin_slug),
			'type'    => 'checkbox',
			'std'     => $taxs,
			'desc'    => __( 'Choose wich taxonomies you want to export', $this->plugin_slug),
			'choices' => $taxs
		);
	}

