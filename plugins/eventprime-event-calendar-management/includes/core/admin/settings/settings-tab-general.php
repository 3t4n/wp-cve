
<div class="ep-setting-tab-content">
    <input type="hidden" name="em_setting_type" value="general_tab_settings">
    <ul class="subsubsub">
        <?php
        $active_sub_tab = isset( $_GET['sub_tab'] ) && array_key_exists( $_GET['sub_tab'], $this->ep_get_general_settings_sub_tabs() ) ? $_GET['sub_tab'] : 'regular';
        foreach ( $this->ep_get_general_settings_sub_tabs() as $sub_tab_id => $sub_tab_name ) {
            $sub_tab_url = add_query_arg( 
                array(
                    'sub_tab' => $sub_tab_id,
                )
            );
            $sub_active = $active_sub_tab == $sub_tab_id ? 'current' : '';

            echo '<li><a href="' . esc_url( $sub_tab_url ) . '" title="' . esc_attr( $sub_tab_name ) . '" class="' . $sub_active . '">';
                echo esc_html( $sub_tab_name );
            echo '</a> | </li>';
        }?>
    </h3>
    <?php 
    $this->ep_get_settings_general_tabs_content( $active_sub_tab ); ?>
</ul>
