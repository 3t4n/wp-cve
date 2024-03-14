<div>
    <p>
        <label for="<?php echo $this->get_field_id( 'query' ); ?>"><?php _e( 'Query', 'getyourguide-widget' ); ?>:
            <input class="widefat" id="<?php echo $this->get_field_id( 'query' ); ?>" name="<?php echo $this->get_field_name( 'query' ); ?>" type="text" value="<?php echo esc_attr( $query ); ?>" />
            <span class="description"><?php _e('A location, attraction, or activity. E.g. "Paris", "Eiffel Tower", or "Walking Tour"', 'getyourguide-widget'); ?></span>
        </label>
    </p>
    <p>
        <label for="<?php echo $this->get_field_id( 'number_of_items' ); ?>"><?php _e( 'Number of Items', 'getyourguide-widget' ); ?>:
            <input class="widefat" id="<?php echo $this->get_field_id( 'number_of_items' ); ?>" name="<?php echo $this->get_field_name( 'number_of_items' ); ?>" type="text" value="<?php echo esc_attr( $number_of_items ); ?>" />
        </label>
    </p>
    <p>
        <label for="<?php echo $this->get_field_id( 'campaign_param' ); ?>"><?php _e( 'Campaign Parameter', 'getyourguide-widget' ); ?>:
            <input class="widefat" id="<?php echo $this->get_field_id( 'campaign_param' ); ?>" name="<?php echo $this->get_field_name( 'campaign_param' ); ?>" type="text" value="<?php echo esc_attr( $campaign_param ); ?>" />
            <span class="description"><?php _e('An optional parameter to track the display area. E.g. "Header" or "Sidebar"', 'getyourguide-widget'); ?></span>
        </label>
    </p>
</div>