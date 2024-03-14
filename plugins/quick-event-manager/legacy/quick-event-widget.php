<?php

class qem_widget extends WP_Widget {

	function __construct() {
		parent::__construct(
			'qem_widget', // Base ID
			__( 'Quick Event List', 'quick-event-manager' ), // Name
			array( 'description' => __( 'Add an event list to your sidebar', 'quick-event-manager' ), ) // Args
		);
	}

	function form( $instance ) {
		$instance         = wp_parse_args( (array) $instance, array(
			'posts'            => '3',
			'size'             => 'small',
			'headersize'       => 'headtwo',
			'settings'         => '',
			'links'            => 'checked',
			'listlink'         => '',
			'listlinkanchor'   => 'See full event list',
			'listlinkurl'      => '',
			'vanillawidget'    => '',
			'usecategory'      => 'checked',
			'categorykeyabove' => 'checked',
			'categorykeybelow' => 'checked',
			'fields'           => ''
		) );
		$posts            = $instance['posts'];
		$size             = $instance['size'];
		$fields           = $instance['fields'];
		$headersize       = $instance['headersize'];
		$settings         = $instance['settings'];
		$vanillawidget    = $instance['vanillawidget'];
		$links            = $instance['links'];
		$listlink         = $instance['listlink'];
		$listlinkanchor   = $instance['listlinkanchor'];
		$listlinkurl      = $instance['listlinkurl'];
		$usecategory      = $instance['usecategory'];
		$categorykeyabove = $instance['categorykeyabove'];
		$categorykeybelow = $instance['categorykeybelow'];
		if ( isset( $instance['title'] ) ) {
			$title = $instance['title'];
		} else {
			$title = __( 'Event List', 'text_domain' );
		}
		?>
        <p><label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>">
				<?php esc_html_e( 'Title', 'quick-event-manager' ); ?>:</label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
                   name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text"
                   value="<?php echo esc_attr( $title ); ?>"/></p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'posts' ) ); ?>">
				<?php esc_html_e( 'Number of posts to display ', 'quick-event-manager' ); ?>
                :<input style="width:3em" id="<?php echo esc_attr( $this->get_field_id( 'posts' ) ); ?>"
                        name="<?php echo esc_attr( $this->get_field_name( 'posts' ) ); ?>" type="text"
                        value="<?php echo esc_attr( $posts ); ?>"/></label></p>
        <h3><?php esc_html_e( 'Calender Icon', 'quick-event-manager' ); ?></h3>
        <p><input type="checkbox" id="<?php echo esc_attr( $this->get_field_name( 'vanillawidget' ) ); ?>"
                  name="<?php echo esc_attr( $this->get_field_name( 'vanillawidget' ) ); ?>"
                  value="checked" <?php echo esc_attr( $vanillawidget ); ?>>
			<?php esc_html_e( 'Strip styling from date icon.', 'quick-event-manager' ); ?></p>
        <p><input type="radio" id="<?php echo esc_attr( $this->get_field_name( 'size' ) ); ?>"
                  name="<?php echo esc_attr( $this->get_field_name( 'size' ) ); ?>" value="small" <?php
			checked( $size, 'small' ); ?>> <?php esc_html_e( 'Small', 'quick-event-manager' ); ?><br>
            <input type="radio" id="<?php echo esc_attr( $this->get_field_name( 'size' ) ); ?>"
                   name="<?php echo esc_attr( $this->get_field_name( 'size' ) ); ?>"
                   value="medium" <?php checked( $size, 'medium' ); ?>>
			<?php esc_html_e( 'Medium', 'quick-event-manager' ); ?><br>
            <input type="radio" id="<?php echo esc_attr( $this->get_field_name( 'size' ) ); ?>"
                   name="<?php echo esc_attr( $this->get_field_name( 'size' ) ); ?>"
                   value="large" <?php checked( $size, 'large' ); ?>> <?php
			esc_html_e( 'Large', 'quick-event-manager' ); ?></p>
        <h3><?php esc_html_e( 'Event Title', 'quick-event-manager' ); ?></h3>
        <p><input type="radio" id="<?php echo esc_attr( $this->get_field_name( 'headersize' ) ); ?>"
                  name="<?php echo esc_attr( $this->get_field_name( 'headersize' ) ); ?>"
                  value="headtwo" <?php checked( $headersize, 'headtwo' ); ?>>
            H2 <input type="radio" id="<?php echo esc_attr( $this->get_field_name( 'headersize' ) ); ?>"
                      name="<?php echo esc_attr( $this->get_field_name( 'headersize' ) ); ?>"
                      value="headthree" <?php checked( $headersize, 'headthree' ); ?>> H3</p>
        <h3><?php esc_html_e( 'Fields', 'quick-event-manager' ); ?></h3>
        <p><?php esc_html_e( 'Enter the', 'quick-event-manager' ); ?> <a
                    href="options-general.php?page=<?php echo esc_attr( QUICK_EVENT_MANAGER_PLUGIN_NAME ); ?>&tab=settings"> <?php
				esc_html_e( 'field numbers you want to display. Enter -none- to hide all fields.', 'quick-event-manager' ); ?></a>

            <input class="widefat" type="text" id="<?php echo esc_attr( $this->get_field_name( 'fields' ) ); ?>"
                   name="<?php echo esc_attr( $this->get_field_name( 'fields' ) ); ?>"
                   value="<?php echo esc_attr( $fields ); ?>"></p>
        <h3><?php esc_html_e( 'Styling', 'quick-event-manager' ); ?></h3>
        <p><input type="checkbox" id="<?php echo esc_attr( $this->get_field_name( 'settings' ) ); ?>"
                  name="<?php echo esc_attr( $this->get_field_name( 'settings' ) ); ?>"
                  value="checked" <?php echo esc_attr( $settings ); ?>>
			<?php esc_html_e( 'Use plugin styles', 'quick-event-manager' ); ?> (<a
                    href="options-general.php?page=<?php echo esc_attr( QUICK_EVENT_MANAGER_PLUGIN_NAME ); ?>&tab=settings"><?php esc_html_e( 'View styles', 'quick-event-manager' ); ?></a>)
        </p>
        <h3><?php esc_html_e( 'Categories', 'quick-event-manager' ); ?></h3>
        <p><select id="<?php echo esc_attr( $this->get_field_id( 'category' ) ); ?>"
                   name="<?php echo esc_attr( $this->get_field_name( 'category' ) ); ?>" class="widefat"
                   style="width:100%;">
                <option value="0"><?php esc_html_e( 'All Categories', 'quick-event-manager' ); ?></option>
				<?php foreach ( get_terms( 'category', 'parent=0&hide_empty=0' ) as $term ) { ?>
                    <option <?php selected( $instance['category'], $term->term_id ); ?>
                            value="<?php echo esc_attr( $term->term_id ); ?>"><?php echo esc_attr( $term->name ); ?></option>
				<?php } ?>
            </select></p>
        <p><input type="checkbox" id="<?php echo esc_attr( $this->get_field_name( 'usecategory' ) ); ?>"
                  name="<?php echo esc_attr( $this->get_field_name( 'usecategory' ) ); ?>"
                  value="checked" <?php echo esc_attr( $usecategory ); ?>> <?php esc_html_e( 'Show category colours', 'quick-event-manager' ); ?>
        </p>
        <p><input type="checkbox" id="<?php echo esc_attr( $this->get_field_name( 'categorykeyabove' ) ); ?>"
                  name="<?php echo esc_attr( $this->get_field_name( 'categorykeyabove' ) ); ?>"
                  value="checked" <?php echo esc_attr( $categorykeyabove ); ?>> <?php esc_html_e( 'Show category key above list', 'quick-event-manager' ); ?>
        </p>
        <p><input type="checkbox" id="<?php echo esc_attr( $this->get_field_name( 'categorykeybelow' ) ); ?>"
                  name="<?php echo esc_attr( $this->get_field_name( 'categorykeybelow' ) ); ?>"
                  value="checked" <?php echo esc_attr( $categorykeybelow ); ?>> <?php esc_html_e( 'Show category key below list', 'quick-event-manager' ); ?>
        </p>
        <h3><?php esc_html_e( 'Links', 'quick-event-manager' ); ?></h3>
        <p><input type="checkbox" id="<?php echo esc_attr( $this->get_field_name( 'links' ) ); ?>"
                  name="<?php echo esc_attr( $this->get_field_name( 'links' ) ); ?>"
                  value="checked" <?php echo esc_attr( $links ); ?>> <?php
			esc_html_e( 'Show links to events', 'quick-event-manager' ); ?></p>
        <p><input type="checkbox" id="<?php echo esc_attr( $this->get_field_name( 'listlink' ) ); ?>"
                  name="<?php echo esc_attr( $this->get_field_name( 'listlink' ) ); ?>"
                  value="checked" <?php echo esc_attr( $listlink ); ?>> <?php
			esc_html_e( 'Link to Event List', 'quick-event-manager' ); ?></p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'listlinkanchor' ) ); ?>"><?php esc_html_e( 'Anchor text:', 'quick-event-manager' ); ?></label>
            <input class="widefat" type="text" id="<?php echo esc_attr( $this->get_field_name( 'listlinkanchor' ) ); ?>"
                   name="<?php echo esc_attr( $this->get_field_name( 'listlinkanchor' ) ); ?>"
                   value="<?php echo esc_attr( $listlinkanchor ); ?>"></p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'listlinkurl' ) ); ?>"><?php esc_html_e( 'URL of list page', 'quick-event-manager' ); ?>
                :</label>
            <input class="widefat" type="text" id="<?php echo esc_attr( $this->get_field_name( 'listlinkurl' ) ); ?>"
                   name="<?php echo esc_attr( $this->get_field_name( 'listlinkurl' ) ); ?>"
                   value="<?php echo esc_attr( $listlinkurl ); ?>"></p>
        <p><?php esc_html_e( 'All other options are changed on the ', 'quick-event-manager' ); ?> <a
                    href="options-general.php?page=<?php echo esc_attr( QUICK_EVENT_MANAGER_PLUGIN_NAME ); ?>"><?php esc_html_e( 'settings page', 'quick-event-manager' ); ?></a>.
        </p>
		<?php
	}

	function update( $new_instance, $old_instance ) {
		$instance                     = $old_instance;
		$instance['title']            = ( ! empty( $new_instance['title'] ) ) ? esc_html( $new_instance['title'] ) : '';
		$instance['posts']            = $new_instance['posts'];
		$instance['fields']           = $new_instance['fields'];
		$instance['size']             = $new_instance['size'];
		$instance['headersize']       = $new_instance['headersize'];
		$instance['settings']         = $new_instance['settings'];
		$instance['links']            = $new_instance['links'];
		$instance['listlink']         = $new_instance['listlink'];
		$instance['listlinkanchor']   = $new_instance['listlinkanchor'];
		$instance['listlinkurl']      = $new_instance['listlinkurl'];
		$instance['vanillawidget']    = $new_instance['vanillawidget'];
		$instance['category']         = $new_instance['category'];
		$instance['usecategory']      = $new_instance['usecategory'];
		$instance['categorykeyabove'] = $new_instance['categorykeyabove'];
		$instance['categorykeybelow'] = $new_instance['categorykeybelow'];

		return $instance;
	}

	function widget( $args, $instance ) {
		extract( $args, EXTR_SKIP );
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo qem_wp_kses_post( $args['before_widget'] );
		if ( ! empty( $title ) ) {
			echo qem_wp_kses_post( $args['before_title'] . $title . $args['after_title'] );
		}
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped --  qem_event_shortcode_esc function returns escaped outputs as used in multiple places shortcode, widget admin previews etc https://developer.wordpress.org/apis/security/escaping/#toc_4
		echo qem_event_shortcode_esc( $instance, 'widget' );
		echo qem_wp_kses_post( $args['after_widget'] );
	}
}

class qem_calendar_widget extends WP_Widget {

	function __construct() {
		parent::__construct(
			'qem_calendar_widget', // Base ID
			esc_html__( 'Quick Event Calendar', 'quick-event-manager' ), // Name
			array( 'description' => esc_html__( 'Add an event calendar to your sidebar', 'quick-event-manager' ), ) // Args
		);
	}

	function form( $instance ) {
		$instance         = wp_parse_args( (array) $instance, array(
			'eventlength'      => '12',
			'smallicon'        => 'trim',
			'unicode'          => '\263A',
			'categorykeybelow' => '',
			'categorykeyabove' => '',
			'header'           => 'h2',
			'headerstyle'      => '',
		) );
		$smallicon        = $instance['smallicon'];
		$header           = $instance['header'];
		${$smallicon}     = 'checked';
		${$header}        = 'checked';
		$categorykeybelow = $instance['categorykeybelow'];
		$categorykeyabove = $instance['categorykeyabove'];
		$unicode          = $instance['unicode'];
		$headerstyle      = $instance['headerstyle'];
		if ( isset( $instance['title'] ) ) {
			$title = $instance['title'];
		} else {
			$title = esc_html__( 'Event Calendar', 'quick-event-manager' );
		}
		?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"> <?php esc_html_e( 'Title:', 'quick-event-manager' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
                   name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text"
                   value="<?php echo esc_attr( $title ); ?>"/></p>
        <p><input type="checkbox" id="<?php echo esc_attr( $this->get_field_name( 'categorykeyabove' ) ); ?>"
                  name="<?php echo esc_attr( $this->get_field_name( 'categorykeyabove' ) ); ?>"
                  value="checked" <?php echo esc_attr( $categorykeyabove ); ?>> <?php esc_html_e( 'Show category key above list', 'quick-event-manager' ); ?>
        </p>
        <p><input type="checkbox" id="<?php echo esc_attr( $this->get_field_name( 'categorykeybelow' ) ); ?>"
                  name="<?php echo esc_attr( $this->get_field_name( 'categorykeybelow' ) ); ?>"
                  value="checked" <?php echo esc_attr( $categorykeybelow ); ?>> <?php esc_html_e( 'Show category key below list', 'quick-event-manager' ); ?>
        </p>
        <h3><?php esc_html_e( 'Month and Date Style', 'quick-event-manager' ); ?></h3>
        <p>
            <input type="radio" id="<?php echo esc_html( $this->get_field_name( 'header' ) ); ?>"
                   name="<?php echo esc_attr( $this->get_field_name( 'header' ) ); ?>"
                   value="h2" <?php echo esc_attr( $h2 ); ?>> H2&nbsp;
            <input type="radio" id="<?php echo esc_attr( $this->get_field_name( 'header' ) ); ?>"
                   name="<?php echo esc_attr( $this->get_field_name( 'header' ) ); ?>"
                   value="h3" <?php echo esc_attr( $h3 ); ?>> H3&nbsp;
            <input type="radio" id="<?php echo esc_attr( $this->get_field_name( 'header' ) ); ?>"
                   name="<?php echo esc_attr( $this->get_field_name( 'header' ) ); ?>"
                   value="h4" <?php echo esc_attr( $h4 ); ?>> H4
        </p>
        <p><?php esc_html_e( 'Header CSS:', 'quick-event-manager' ); ?></p>
        <p><input class="widefat" type="text" id="<?php echo esc_attr( $this->get_field_name( 'headerstyle' ) ); ?>"
                  name="<?php echo esc_attr( $this->get_field_name( 'headerstyle' ) ); ?>"
                  value="<?php echo esc_attr( $headerstyle ); ?>"/>
        <h3><?php esc_html_e( 'Event Symbol', 'quick-event-manager' ); ?></h3>
        <p><?php esc_html_e( 'If there is no room on narrow sidebars for the full calendar details select an alternate
            symbol below:', 'quick-event-manager' ); ?></p>
        <p>
            <input type="radio" id="<?php echo esc_attr( $this->get_field_name( 'smallicon' ) ); ?>"
                   name="<?php echo esc_attr( $this->get_field_name( 'smallicon' ) ); ?>"
                   value="trim" <?php echo esc_attr( $trim ); ?>> <?php
			esc_html_e( 'Event name', 'quick-event-manager' ); ?><br/>
            <input type="radio" id="<?php echo esc_attr( $this->get_field_name( 'smallicon' ) ); ?>"
                   name="<?php echo esc_attr( $this->get_field_name( 'smallicon' ) ); ?>"
                   value="arrow" <?php echo esc_attr( $arrow ); ?>>
            &#9654;<br/>
            <input type="radio" id="<?php echo esc_attr( $this->get_field_name( 'smallicon' ) ); ?>"
                   name="<?php echo esc_attr( $this->get_field_name( 'smallicon' ) ); ?>"
                   value="box" <?php echo esc_attr( $box ); ?>>
            &#9633;<br/>
            <input type="radio" id="<?php echo esc_attr( $this->get_field_name( 'smallicon' ) ); ?>"
                   name="<?php echo esc_attr( $this->get_field_name( 'smallicon' ) ); ?>"
                   value="square " <?php echo esc_attr( $square ); ?>>
            &#9632;<br/>
            <input type="radio" id="<?php echo esc_attr( $this->get_field_name( 'smallicon' ) ); ?>"
                   name="<?php echo esc_attr( $this->get_field_name( 'smallicon' ) ); ?>"
                   value="asterix" <?php echo esc_attr( $asterix ); ?>>
            &#9733;<br/>
            <input type="radio" id="<?php echo esc_attr( $this->get_field_name( 'smallicon' ) ); ?>"
                   name="<?php echo esc_attr( $this->get_field_name( 'smallicon' ) ); ?>"
                   value="blank" <?php echo esc_attr( $blank ); ?>> <?php
			esc_html_e( 'Blank', 'quick-event-manager' ); ?><br/>
            <input type="radio" id="<?php echo esc_attr( $this->get_field_name( 'smallicon' ) ); ?>"
                   name="<?php echo esc_attr( $this->get_field_name( 'smallicon' ) ); ?>"
                   value="other" <?php echo esc_attr( $other ); ?>> <?php
			esc_html_e( ' Other (enter escaped', 'quick-event-manager' );
			?> <a href="http://www.fileformat.info/info/unicode/char/search.htm"
                  target="blank"><?php esc_html_e( 'unicode', 'quick-event-manager' );
				?></a><?php esc_html_e( 'or hex code below)', 'quick-event-manager' ); ?><br/>
            <input type="text" id="<?php echo esc_attr( $this->get_field_name( 'unicode' ) ); ?>"
                   name="<?php echo esc_attr( $this->get_field_name( 'unicode' ) ); ?>"
                   value="<?php echo esc_attr( $unicode ); ?>"/></p>
        <p><?php esc_html_e( 'All other options are changed on the ', 'quick-event-manager' ); ?> <a
                    href="options-general.php?page=<?php echo esc_attr( QUICK_EVENT_MANAGER_PLUGIN_NAME ); ?>">
				<?php esc_html_e( 'settings page', 'quick-event-manager' ); ?></a>.
        </p>
		<?php
	}

	function update( $new_instance, $old_instance ) {
		$instance                     = $old_instance;
		$instance['title']            = ( ! empty( $new_instance['title'] ) ) ? esc_html( $new_instance['title'] ) : '';
		$instance['smallicon']        = $new_instance['smallicon'];
		$instance['header']           = $new_instance['header'];
		$instance['unicode']          = $new_instance['unicode'];
		$instance['categorykeyabove'] = $new_instance['categorykeyabove'];
		$instance['categorykeybelow'] = $new_instance['categorykeybelow'];
		$instance['headerstyle']      = $new_instance['headerstyle'];
		$instance['widget']           = 'widget';

		return $instance;
	}

	function widget( $args, $instance ) {
		extract( $args, EXTR_SKIP );
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo qem_wp_kses_post( $args['before_widget'] );
		if ( ! empty( $title ) ) {
			echo qem_wp_kses_post( $args['before_title'] . $title . $args['after_title'] );
		}


		$arr       = array(
			'arrow'            => '\25B6',
			'square'           => '\25A0',
			'box'              => '\20DE',
			'asterix'          => '\2605',
			'blank'            => ' ',
			'categorykeyabove' => '',
			'categorykeybelow' => '',
		);
		$smallicon = '';
		foreach ( $arr as $item => $key ) {
			if ( $item == $instance['smallicon'] ) {
				$smallicon = '#qem-calendar-widget .qemtrim span {display:none;}#qem-calendar-widget .qemtrim:after{content:"' . $key . '";font-size:150%;text-align:center}';
			}
		}
		if ( $instance['headerstyle'] ) {
			$headerstyle = '#qem-calendar-widget ' . $instance['header'] . '{' . $instance['headerstyle'] . '}';
		}
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- wp_strip_all_tags is a WP security function, qem_show_calendar_esc returns escaped data https://developer.wordpress.org/apis/security/escaping/#toc_4
		echo '<div id="qem-calendar-widget"><style>' . wp_strip_all_tags( $smallicon . ' ' . $headerstyle ) . '</style>' . qem_show_calendar_esc( $instance ) . '</div>' . "\r\n";

		echo qem_wp_kses_post( $args['after_widget'] );
	}
}

function add_qem_widget() {
	return register_widget( 'qem_widget' );
}

function add_qem_calendar_widget() {
	return register_widget( 'qem_calendar_widget' );
}
