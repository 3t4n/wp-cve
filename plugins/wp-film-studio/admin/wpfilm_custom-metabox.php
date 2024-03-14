<?php

/**
* Get Custom Post Type 
*/
function wpfilm_get_posttype_options($argument) {
    $get_post_args = array(
        'post_type' => $argument,
        'posts_per_page'	=> -1,
    );
    $options = array();
    array_push( $options, esc_html__( '--- Select ---', 'wpfilm-studio' ) );
    foreach ( get_posts( $get_post_args ) as $post ) {
        $title = get_the_title( $post->ID );
        $options[$post->ID] =  $title;
    }
    return $options;
}

/**
* Start Meta fields
*/
add_filter( 'cmb2_init', 'wpfilm_metaboxes' );
function wpfilm_metaboxes() {
	$prefix = '_wpfilm_';

	//===================================
	//Movie Metaboxes
	//===================================
		$movie = new_cmb2_box( array(
			'id'            => $prefix . 'movie',
			'title'         => esc_html__( 'Movie Option', 'wpfilm-studio' ),
			'object_types'  => array( 'wpfilm_movie'), // Post type
			'priority'   => 'high',
			) );


		$movie->add_field( array(
			'name'       => esc_html__( 'Movie Banner Image', 'wpfilm-studio' ),
			'desc'       => esc_html__( 'Upload  Movie Banner Image', 'wpfilm-studio' ),
			'id'         => $prefix . 'movie_banner_img',
			'type'       => 'file',
			) );
		$movie->add_field( array(
			'name'       => esc_html__( 'Publish Date', 'wpfilm-studio' ),
			'desc'       => esc_html__( 'insert Publisht Date here', 'wpfilm-studio' ),
			'id'         => $prefix . 'publish_date',
			'type'       => 'text',
			) );

		$movie->add_field( array(
			'name'       => esc_html__( 'Movie Duration', 'wpfilm-studio' ),
			'desc'       => esc_html__( 'insert Movie Duration here', 'wpfilm-studio' ),
			'id'         => $prefix . 'movie_duration',
			'type'       => 'text',
			) );

		$movie->add_field( array(
 				'id' 			=> $prefix . 'select_trailer',
			    'name' 			=> esc_html__( 'Select Trailer', 'wpfilm-studio' ),
			    'desc' 			=> esc_html__( 'Select the Trailer  For this movie', 'wpfilm-studio' ),
			    'type' 			=> 'select',
			    'options' 		=> wpfilm_get_posttype_options('wpfilm_trailer'),
			) );

		$moviegrop = $movie->add_field( array(
			'id'          => $prefix . 'moviedetails',
			'type'        => 'group',
			'description' => esc_html__( 'Add Item for Movie Details', 'wpfilm-studio' ),
			'options'     => array(
				'group_title'   => esc_html__( 'Movie Details {#}', 'wpfilm-studio' ),
				'add_button'    => esc_html__( 'Add Another Item', 'wpfilm-studio' ),
				'remove_button' => esc_html__( 'Remove Item', 'wpfilm-studio' ),
				'sortable'      => true, // beta
				),
			) );
		$movie->add_group_field( $moviegrop, array(
			'name'       => esc_html__( 'Details Title', 'wpfilm-studio' ),
			'desc'       => esc_html__( 'Details Title', 'wpfilm-studio' ),
			'id'         => $prefix .'movie_d_title',
			'type'       => 'text',
			) );
		$movie->add_group_field( $moviegrop, array(
			'name'       => esc_html__( 'Details Content', 'wpfilm-studio' ),
			'desc'       => esc_html__( 'Details Content here', 'wpfilm-studio' ),
			'id'  => $prefix .'movie_d_content',
			'type' => 'text',
			) );

	//===================================
	//Trailer Metaboxes
	//===================================  
	$trailer = new_cmb2_box( array(
		'id'            => $prefix . 'trailer',
		'title'         => __( 'Trailer Metaboxes', 'wpfilm-studio' ),
		'object_types'  => array( 'wpfilm_trailer', ), // Post type
		'priority'   => 'high',
	) );
	   $trailer->add_field( array(
		'name'       => esc_html__( 'Trailer Url', 'wpfilm-studio' ),
		'desc'       => esc_html__( 'insert video link. ex-https://youtu.be/OJ9ejTy4J98', 'wpfilm-studio' ),
		'id'         => $prefix . 'trailer_video',
		'type'       => 'text_url',
	   ) );		  
		$trailer->add_field( array(
			'name'       => esc_html__( 'Trailer Duration', 'wpfilm-studio' ),
			'desc'       => esc_html__( 'Insert Trailer Duration', 'wpfilm-studio' ),
			'id'         => $prefix . 'trailer_duration',
			'type'       => 'text',
		) );





	$campaign = new_cmb2_box( array(
		'id'            => $prefix . 'campaign',
		'title'         => esc_html__( 'Campaign Option', 'wpfilm-studio' ),
		'object_types'  => array( 'wpcampaign', ), // Post type
		'priority'   => 'high',
	) );
		$campaign->add_field( array(
			'name'       => esc_html__( 'Short Description', 'wpfilm-studio' ),
			'desc'       => esc_html__( 'Insert Description Here', 'wpfilm-studio' ),
			'id'         => $prefix . 'campaign_short_des',
			'type'       => 'textarea_small',
			'default' 		=> ''
			) );
			$campaign->add_field( array(
				'name'       => esc_html__( 'Venue Title', 'wpfilm-studio' ),
				'desc'       => esc_html__( 'Location label', 'wpfilm-studio' ),
				'id'         => $prefix . 'campaign_location_title',
				'type'       => 'text',
				'default'    => 'Venue',
			) );
			$campaign->add_field( array(
				'name'       => esc_html__( 'Loaction Details', 'wpfilm-studio' ),
				'desc'       => esc_html__( 'Insert  loaction here', 'wpfilm-studio' ),
				'id'         => $prefix . 'campaign_loaction_details',
				'type'       => 'text',
				'default'	 =>'Mariyana National Park, Chicago',
			) );
			$campaign->add_field( array(
				'name'       => esc_html__( 'Campaign Details Title', 'wpfilm-studio' ),
				'desc'       => esc_html__( 'Insert  Campaign Details here', 'wpfilm-studio' ),
				'id'         => $prefix . 'campaign_details_title',
				'type'       => 'text',
				'default'	 =>'Campaign Detials',
			) );
			$campaign->add_field( array(
				'name'       => esc_html__( 'Date Title', 'wpfilm-studio' ),
				'desc'       => esc_html__( 'Date label', 'wpfilm-studio' ),
				'id'         => $prefix . 'campaign_date_title',
				'type'       => 'text',
				'default'	 =>'Date:',
			) );			
			$campaign->add_field( array(
				'name'       => esc_html__( 'Campaign Date', 'wpfilm-studio' ),
				'desc'       => esc_html__( 'Campaign Date', 'wpfilm-studio' ),
				'id'         => $prefix . 'campaign_date',
				'type'       => 'text_date',
                'date_format' => 'Y/m/j'
			) );			
			$campaign->add_field( array(
				'name'       => esc_html__( 'Time Title', 'wpfilm-studio' ),
				'desc'       => esc_html__( 'Time label', 'wpfilm-studio' ),
				'id'         => $prefix . 'campaign_time_title',
				'type'       => 'text',
				'default'	 =>'Time:',
			) );			
			$campaign->add_field( array(
				'name'       => esc_html__( 'Time', 'wpfilm-studio' ),
				'desc'       => esc_html__( 'Campaign Time', 'wpfilm-studio' ),
				'id'         => $prefix . 'campaign_time',
				'type'       => 'text',
				'default'	 =>'03.00 pm to 06.00 pm',
			) );

			$campaign->add_field( array(
				'name'       => esc_html__( 'Map Api', 'wpfilm-studio' ),
				'desc'       => esc_html__( 'Map Api', 'wpfilm-studio' ),
				'id'         => $prefix . 'campaign_map',
				'type'       => 'text',
				'default'	 =>'AIzaSyCGM-62ap9R-huo50hJDn05j3x-mU9151Y',
			) );
			$campaign->add_field( array(
				'name'       => esc_html__( 'Map lat', 'wpfilm-studio' ),
				'desc'       => esc_html__( 'Map lat', 'wpfilm-studio' ),
				'id'         => $prefix . 'campaign_map_lat',
				'type'       => 'text',
				'default'	 =>'40.6700',
			) );
			$campaign->add_field( array(
				'name'       => esc_html__( 'Map Lng', 'wpfilm-studio' ),
				'desc'       => esc_html__( 'Map Lng', 'wpfilm-studio' ),
				'id'         => $prefix . 'campaign_map_lng',
				'type'       => 'text',
				'default'	 =>'73.9400',
			) );

			$campaign->add_field( array(
				'name'       => esc_html__( 'Website Title', 'wpfilm-studio' ),
				'desc'       => esc_html__( 'Website label', 'wpfilm-studio' ),
				'id'         => $prefix . 'campaign_website_title',
				'type'       => 'text',
				'default'	 =>'Website: ',
			) );
			$campaign->add_field( array(
				'name'       => esc_html__( 'Website Name', 'wpfilm-studio' ),
				'desc'       => esc_html__( 'Website Name', 'wpfilm-studio' ),
				'id'         => $prefix . 'campaign_website',
				'type'       => 'text',
				'default'	 =>'www.ftageserv.com',
			) );
			$campaign->add_field( array(
				'name'       => esc_html__( 'Phone Title', 'wpfilm-studio' ),
				'desc'       => esc_html__( 'Phone title Lebel', 'wpfilm-studio' ),
				'id'         => $prefix . 'campaign_phone_title',
				'type'       => 'text',
				'default'	 =>'Phone:',
			) );
			$campaign->add_field( array(
				'name'       => esc_html__( 'Phone Number', 'wpfilm-studio' ),
				'desc'       => esc_html__( 'Phone Number', 'wpfilm-studio' ),
				'id'         => $prefix . 'campaign_phone',
				'type'       => 'text',
				'default'	 =>' +88012745839',
			) );

			$campaign->add_field( array(
				'name'       => esc_html__( 'Organizer Title', 'wpfilm-studio' ),
				'desc'       => esc_html__( 'Organizer Title', 'wpfilm-studio' ),
				'id'         => $prefix . 'campaign_organizer_title',
				'type'       => 'text',
				'default'	 =>'Organizer',
			) );


			$group_field_id = $campaign->add_field( array(
				'id'          => $prefix . 'name_list_campaign',
				'type'        => 'group',
				'description' => esc_html__( 'Add First Entry', 'wpfilm-studio' ),
				'options'     => array(
					'group_title'   => esc_html__( 'Organizer Detials {#}', 'wpfilm-studio' ), 
					'add_button'    => esc_html__( 'Add Another Entry', 'wpfilm-studio' ),
					'remove_button' => esc_html__( 'Remove Entry', 'wpfilm-studio' ),
					'sortable'      => true, // beta
				),
			) );

			$campaign->add_group_field( $group_field_id, array(
				'name'       => esc_html__( 'Enter Title Name', 'wpfilm-studio' ),
				'id'         => 'campaign_name',
				'desc'       => esc_html__( 'insert title here', 'wpfilm-studio' ),
				'type'       => 'text',
			) );
			$campaign->add_group_field( $group_field_id, array(
				'name'       => esc_html__( 'Enter Details', 'wpfilm-studio' ),
				'id'         => 'campaign_add',
				'desc'       => esc_html__( 'insert Info Here', 'wpfilm-studio' ),
				'type'       => 'text',
			) );


}