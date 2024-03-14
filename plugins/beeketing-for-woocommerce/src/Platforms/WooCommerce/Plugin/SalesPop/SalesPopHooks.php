<?php

namespace BeeketingConnect_beeketing_woocommerce\Platforms\WooCommerce\Plugin\SalesPop;

use BeeketingConnect_beeketing_woocommerce\Platforms\WooCommerce\Plugin\Hooks;

class SalesPopHooks extends Hooks
{
    public function registerAdminHooks()
    {
        parent::registerAdminHooks();

        // custom logic
        add_filter( 'plugin_row_meta', array( $this, 'add_row_meta_plugin' ), 10, 2 );

        add_filter( 'plugin_action_links', array( $this, 'add_action_plugin' ), 10, 2 );
    }

    /**
     * add 'Docs' and  'Discovery Beeketing' link in plugin meta
     * @param $links
     * @param $file
     * @return array
     */
    public function add_row_meta_plugin( $links, $file ) {

        if ( $this->pluginConfig->getPluginBaseName() === $file ) {
            $row_meta = array(
                'docs'    => '<a target="_blank" href="' . esc_url( apply_filters( 'beeketing_docs_url', 'http://support.beeketing.com/support/solutions/folders/24000001954' ) )
                    . '" aria-label="' . esc_attr__( 'View beeketing documentation', 'beeketing' ) . '">' . esc_html__( 'Docs', 'beeketing' ) . '</a>',
                'discovery' => '<a target="_blank" href="' . esc_url( apply_filters( 'beeketing_apidocs_url',
                        '/wp-admin/plugin-install.php?tab=plugin-information&plugin=beeketing-for-woocommerce' ) )
                    . '" aria-label="' . esc_attr__( 'View beeketing', 'beeketing' )
                    . '">' . esc_html__( 'Discovery Beeketing', 'beeketing' ) . '</a>',
            );
            return array_merge( $links, $row_meta );
        }
        return (array) $links;
    }

    /**
     * add 'Migrate to Beeketing' link in plugin action
     * @param $links
     * @param $file
     * @return array
     */
    function add_action_plugin( $links, $file )
    {
        if ( $this->pluginConfig->getPluginBaseName() === $file ) {
            $row_meta = array(
                'docs'    => '<a target="_blank" href="' . esc_url( apply_filters( 'beeketing_docs_url', 'https://beeketing.freshdesk.com/support/solutions/articles/24000022887-why-should-i-migrate-from-sales-pop-plugin-to-beeketing-plugin-' ) )
                    . '" aria-label="' . esc_attr__( 'View beeketing documentation', 'beeketing' ) . '">' . esc_html__( 'Migrate to Beeketing', 'beeketing' ) . '</a>',

            );
            return array_merge($row_meta, $links );
        }
        return (array) $links;
    }

}
