<?php
/*
* Doc Help link 
*
*
*/

trait mpdProHelpLink
{
    public function link_pro_added()
    {
        if (get_option('mgppro_is_active') == 'yes') {
            return;
        }

        $this->start_controls_section(
            'mgpd_gopro',
            [
                'label' => esc_html__('Upgrade Pro | Start Only $24!!', 'magical-products-display'),
            ]
        );
        $this->add_control(
            'mgpd__pro',
            [
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => mpd_goprolink([
                    'title' => esc_html__('Get All Pro Features', 'elementor'),
                    'massage' => esc_html__('Unlock all pro featurs and widgets. Upgrade pro to fully recharge your WoooCommerce shop.', 'magical-products-display'),
                    'link' => 'https://wpthemespace.com/product/magical-products-display-pro/?add-to-cart=9177',
                ]),
            ]
        );
        $this->end_controls_section();
    }
}
