<?php

require_once YOUZIFY_CORE . 'widgets/youzify-widgets/class-youzify-infos-boxes.php';

/**
 * Profile Structure Settings.
 */
function youzify_profile_structure_settings() {

    // Profile Structure Script
    wp_enqueue_script( 'youzify-profile-structure', YOUZIFY_ADMIN_ASSETS . 'js/youzify-profile-structure.min.js', array( 'jquery' ), false, true );

    wp_localize_script( 'youzify-profile-structure', 'Youzify_Profile_Structure', array(
		'show_wg' => __( 'Show Widget', 'youzify' ),
		'hide_wg' => __( 'Hide Widget', 'youzify' )
	) );

    /**
     * Install Widgets
     */

    if ( ! get_option( 'youzify_install_new_widgets_login' ) ) {

        $wgs = youzify_options( 'youzify_profile_sidebar_widgets' );

        if ( ! empty( $wgs ) && ! isset( $wgs['login'] ) ) {
	        $wgs = array( 'login' => 'visible' ) + $wgs;
	        update_option( 'youzify_profile_sidebar_widgets', $wgs );
        }

        update_option( 'youzify_install_new_widgets_login', 1 );

    }

    global $Youzify_Settings;

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Profile Columns Layouts', 'youzify' ),
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'id'    => 'youzify_profile_layout',
            'type'  => 'imgSelect',
            'available_opts' => array( 'youzify-left-sidebar', 'youzify-right-sidebar' ),
            'opts'  => array( 'youzify-left-sidebar', 'youzify-3columns', 'youzify-right-sidebar' )
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Profile Main Sidebar', 'youzify' ),
            'type'  => 'openBox',
            'class'	=> 'youzify-profile-main-sidebar hide-by-default'
        )
    );

    echo '<p>' . __( "Select which sidebar should appear on the profile tabs that do not support 3 columns, you still can keep 3 colmns layout but it's not recommended as some other tabs requires much more space.", 'youzify' ) . '</p>';

    $Youzify_Settings->get_field(
        array(
            'type'  => 'imgSelect',
            'id'    => 'youzify_profile_main_sidebar',
            'opts'  => array('youzify-left-sidebar', 'youzify-3columns', 'youzify-right-sidebar')
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Vertical Header Position', 'youzify' ),
            'opts'  => array( 'left' => __( 'Left', 'youzify' ), 'right' => __( 'Right', 'youzify' ) ),
            'desc'  => __( 'Set which side you wanna display the header, this option work only on vertical header layouts.', 'youzify' ),
            'id'    => 'youzify_profile_vertical_header_position',
            'type'  => 'select'
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

    // Profile Widgets
	echo '<div class="youzify-profile-structure youzify-cs-content" data-layout="'. youzify_option( 'youzify_profile_layout', 'profile-right-sidebar' ) .'">';

	// Left Sidebar.
	youzify_get_column_area( array(
		'option_id' => 'youzify_profile_left_sidebar_widgets',
		'class' => 'youzify-sidebar-wgs youzify-left-sidebar',
		'data' => 'left_sidebar_widgets',
		'title' => __( 'Left Sidebar Widgets', 'youzify' )
	) );

	// Main Column.
	youzify_get_column_area( array(
		'option_id' => 'youzify_profile_main_widgets',
		'class' => 'youzify-main-wgs',
		'data' => 'main_widgets',
		'title' => __( 'Main Widgets', 'youzify' )
	) );

	// Right Sidebar.
	youzify_get_column_area( array(
		'option_id' => 'youzify_profile_sidebar_widgets',
		'class' => 'youzify-sidebar-wgs youzify-right-sidebar',
		'data' => 'sidebar_widgets',
		'title' => __( 'Right Sidebar Widgets', 'youzify' )
	) );

	echo '<input type="hidden" name="youzify_profile_stucture" value="true">';

	echo '</div>';
}

/**
 * Get Sidebar Area
 */
function youzify_get_column_area( $options ) {

	// Get Current Main Widgets
	$widgets = youzify_options( $options['option_id'] );

	?>

	<div class="youzify-profile-wg <?php echo $options['class'] ?>">
		<div class="youzify-wgs-inner-content">
			<h2 class="youzify-profile-wg-title"><?php echo $options['title']; ?></h2>
			<ul class="youzify-draggable-area" data-widgets-type="<?php echo $options['data']; ?>"><?php

			if ( ! empty( $widgets ) ) {

			foreach ( $widgets as $widget_name => $visibility ) {

				if ( $widget_name == '0' ) {
					continue;
				}

				// Get Args.
				$args = youzify_get_profile_widget_args( $widget_name );

				if ( $args['id'] == 'ad' ) {
					$ads = youzify_option( 'youzify_ads' );
    				$args['name'] = sprintf( '%1s <span class="youzify-ad-flag">%2s</span>', $ads[ $widget_name ]['title'], __( 'ad', 'youzify' ) );
    			}

    			if ( $widget_name =='user_balance' || $widget_name == 'user_badges' ) {
    				$args['name'] = $args['name'] . '<span class="youzify-widget-label">' . __( 'MyCred', 'youzify' ) . '</span>';
    			}

    			if ( $widget_name =='gamipress_user_balance' || $widget_name == 'gamipress_user_badges' ) {
    				$args['name'] = $args['name'] . '<span class="youzify-widget-label">' . __( 'GamiPress', 'youzify' )  . '</span>';
    			}

				// Print Widget
				youzify_profile_structure_template( array(
					'icon_title' => ( 'visible' == $visibility ) ? __( 'Hide Widget', 'youzify' ) : __( 'Show Widget', 'youzify' ),
					'id'	=> $widget_name,
					'icon'	=> $args['icon'],
					'name'	=> $args['name'],
					'status' => $visibility,
					'class'	=> ( 'invisible' == $visibility ) ? 'youzify-hidden-wg' : '',
					'input_name' => "{$options['option_id']}[$widget_name]",
				) );
			}

			}

			?></ul>
		</div>
	</div>

	<?php

}

/**
 * Profile Structure Template.
 */
function youzify_profile_structure_template( $args ) {

	?>

	<li class="<?php echo $args['class']; ?>" data-widget-name="<?php echo $args['id']; ?>">
		<h3 data-hidden="<?php _e( 'Hidden', 'youzify' ); ?>">
			<i class="<?php echo $args['icon']; ?>"></i>
			<?php echo $args['name']; ?>
		</h3>
		<a class="youzify-hide-wg" title="<?php echo $args['icon_title']; ?>"></a>
		<input class="youzify_profile_widget" type="hidden" name="<?php echo $args['input_name']; ?>" value="<?php echo !empty( $args['status'] ) ? $args['status'] : 'visible'; ?>">
	</li>

	<?php
}