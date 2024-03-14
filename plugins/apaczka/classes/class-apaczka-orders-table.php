<?php

class apaczkaOrdersTable
{

    /**
     * @var WC_Order
     */
    private $order;

    /**
     * @var array
     */
    private $apaczka_meta;

    /**
     * @var string
     */
    private $order_number;

    /**
     * @var string
     */
    private $service;

    public function __construct()
    {
        add_action('manage_shop_order_posts_custom_column',
            [$this, 'customize_order_column'], 10, 1);
        add_filter('manage_edit-shop_order_columns',
            [$this, 'add_order_column'], 10, 1);
    }

    public function customize_order_column($column)
    {
        global $the_order;
        $this->order = $the_order;

        if (false === $this->order instanceof WC_Order) {
            return;
        }

        $apaczka_meta = get_post_meta($this->order->get_id(), '_apaczka');

        if ( isset($apaczka_meta[ 0 ][ 1 ][ 'apaczka_order_number' ]) ) {
            $this->apaczka_meta = $apaczka_meta[ 0 ][ 1 ];
            $this->service = $apaczka_meta[ 0 ][ 1 ][ 'service' ];
            $this->order_number = $apaczka_meta[ 0 ][ 1 ][ 'apaczka_order_number' ];
        }


        if ( $column === 'apaczka' ) {
            // do stuff, ex: get_post_meta( $post->ID, 'key', true );
            echo $this->get_buttons();
        }
    }

    public function add_order_column($columns)
    {
        //unset($columns['order_total']); // remove Total column
        //unset($columns['order_date']); // remove Date column
        // now it is time to add a custom one
        $columns[ 'apaczka' ] = __('Apaczka', 'apaczka');

        return $columns;
    }

    private function get_buttons()
    {
        $btns = $this->get_status_label();
        $btns .= $this->get_print_button();
        $btns .= $this->get_tracking_button();

        return sprintf(
            '<span class="apaczka_order_extra_buttons">%s</span>', $btns
        );
    }

    /**
     * @return string
     */
    private function get_status_label()
    {
        if ( true === $this->is_created() ) {
            $title = __('Przesyłka została utworzona', 'apaczka');
            $class = ' apaczka-success';
        } else {
            $title = __('Przesyłka nie została utworzona', 'apaczka');
            $class = '';
        }

        return sprintf(
            '
                    <span 
                    title="%s"
                    class="dashicons dashicons-yes%s"></span>'
            , $title
            , $class
        );
    }

    /**
     * @return string
     */
    private function get_print_button()
    {
        if ( true === $this->is_created() ) {
            $title = __('Drukuj etykietę', 'apaczka');
            $class = '';
            $url = $this->get_label_url();
            $class_a = '';
        } else {
            return '';
        }

        return sprintf(
            '<a href="%s" target="_blank" class="%s">
                    <span 
                    title="%s" 
                    class="dashicons dashicons-media-spreadsheet%s"></span>
                    </a>',
            $url,
            $class_a,
            $title,
            $class
        );
    }

    /**
     * @return string
     */
    private function get_label_url()
    {
        return admin_url('admin-ajax.php?action=apaczka&apaczka_action=get_waybill&security='
            . wp_create_nonce('apaczka_ajax_nonce')
            . '&apaczka_order_id='
            . $this->apaczka_meta[ 'apaczka_order' ][ 'id' ]);
    }

    /**
     * @return string
     */
    private function get_tracking_button()
    {
        $url = $this->get_tracking_url();


        if ( null !== $url ) {
            $class = '';
            $class_a = '';
            return sprintf(
                '<a class="%s" target="_blank" href="%s"><span
            title="%s" 
            class="dashicons dashicons-admin-site%s"></span>
            </a>',
                $class_a,
                $url
                , __('Śledź przesyłkę', 'apaczka'),
                $class
            );
        }
        return '';
    }

    private function get_tracking_url()
    {
        $track_url_ups_pl
            = 'http://wwwapps.ups.com/WebTracking/processInputRequest?loc=pl_PL&tracknum=%s';
        $track_url_dpd_pl
            = 'https://tracktrace.dpd.com.pl/parcelDetails?p1=%s&ID_kat=3&ID=33&Mark=18&przycisk=Wyszukaj';
        $track_url_dhl_pl = 'https://sprawdz.dhl.com.pl/szukaj.aspx?m=0&sn=%s';
        $track_url_poczta_polska
            = 'http://emonitoring.poczta-polska.pl/?numer=%s';
        $track_url_fedex_pl
            = 'https://poland.fedex.com/domestic-shipping/pub/tracktrace.do?packageId=%s';
        $track_url_inpost_pl
            = 'https://inpost.pl/sledzenie-przesylek?number=%s';
        $track_url_geis_kex
            = 'http://tt.etlogistik.com/TrackAndTrace/ZasilkaDetail.aspx?id=%s';
        $track_url_tnt_pl
            = 'https://www.tnt.com/express/pl_pl/site/shipping-tools/tracking.html?utm_redirect=legacy_track&respCountry=pl&respLang=pl&navigation=1&page=1&sourceID=1&sourceCountry=ww&requesttype=GEN&searchType=CON&cons=%s';
        $order_number = $this->order_number;

        switch ($this->service) {
            case apaczkaApi::SERVICE_UPS_K_STANDARD;
                return sprintf($track_url_ups_pl, $order_number);
                break;

            case apaczkaApi::SERVICE_UPS_K_EX_SAV;
                return sprintf($track_url_ups_pl, $order_number);
                break;

            case apaczkaApi::SERVICE_UPS_K_EX;
                return sprintf($track_url_ups_pl, $order_number);
                break;

            case apaczkaApi::SERVICE_UPS_K_EXP_PLUS;
                return sprintf($track_url_ups_pl, $order_number);
                break;

            case apaczkaApi::SERVICE_DPD_CLASSIC;
                return sprintf($track_url_dpd_pl, $order_number);
                break;

            case apaczkaApi::SERVICE_DHLSTD;
                return sprintf($track_url_dhl_pl, $order_number);
                break;

            case apaczkaApi::SERVICE_DHL09;
                return sprintf($track_url_dhl_pl, $order_number);

            case apaczkaApi::SERVICE_DHL1722;
                return sprintf($track_url_dhl_pl, $order_number);

            case apaczkaApi::SERVICE_UPS_Z_STANDARD;
                return sprintf($track_url_ups_pl, $order_number);

            case apaczkaApi::SERVICE_UPS_Z_EX_SAV;
                return sprintf($track_url_ups_pl, $order_number);

            case apaczkaApi::SERVICE_UPS_Z_EX;
                return sprintf($track_url_ups_pl, $order_number);

            case apaczkaApi::SERVICE_APACZKA_DE;
                return null;

            case apaczkaApi::SERVICE_UPS_Z_EXPEDITED;
                return sprintf($track_url_ups_pl, $order_number);

            case apaczkaApi::SERVICE_TNT;
                return sprintf($track_url_tnt_pl, $order_number);

            case apaczkaApi::SERVICE_FEDEX;
                return sprintf($track_url_fedex_pl, $order_number);

            case apaczkaApi::SERVICE_KEX_EXPRESS;
                return null;

            case apaczkaApi::SERVICE_POCZTA_POLSKA;
                return sprintf($track_url_poczta_polska, $order_number);

            case apaczkaApi::SERVICE_POCZTA_POLSKA_E24;
                return sprintf($track_url_poczta_polska, $order_number);

            case apaczkaApi::SERVICE_SIODEMKA_STD;
                return null;

            case apaczkaApi::SERVICE_DPD_CLASSIC_FOREIGN;
                return sprintf($track_url_dpd_pl, $order_number);

            case apaczkaApi::SERVICE_TNT_Z;
                return sprintf($track_url_tnt_pl, $order_number);

            case apaczkaApi::SERVICE_UPS_Z;
                return sprintf($track_url_ups_pl, $order_number);

            case apaczkaApi::SERVICE_INPOST;
                return sprintf($track_url_inpost_pl, $order_number);
        }
    }

    /**
     * @return bool
     */
    private function is_created()
    {
        return null !== $this->apaczka_meta;
    }
}



/**
 * http://wwwapps.ups.com/WebTracking/processInputRequest?loc=pl_PL&tracknum={tracking_number}
 * https://tracktrace.dpd.com.pl/parcelDetails?p1={tracking_numer}&ID_kat=3&ID=33&Mark=18&przycisk=Wyszukaj
 * https://sprawdz.dhl.com.pl/szukaj.aspx?m=0&sn={tracking_number}
 * http://emonitoring.poczta-polska.pl/?numer={tracking_number}
 * https://poland.fedex.com/domestic-shipping/pub/tracktrace.do?packageId={tracking_number}
 * https://inpost.pl/sledzenie-przesylek?number={tracking_number}
 * http://tt.etlogistik.com/TrackAndTrace/ZasilkaDetail.aspx?id={tracking_number}
 * https://www.tnt.com/express/pl_pl/site/shipping-tools/tracking.html?utm_redirect=legacy_track&respCountry=pl&respLang=pl&navigation=1&page=1&sourceID=1&sourceCountry=ww&requesttype=GEN&searchType=CON&cons={tracking_number}
 */