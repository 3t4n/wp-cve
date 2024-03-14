<?php

/**
 * Glossary
 *
 * @package   Glossary
 * @author    Codeat <support@codeat.co>
 * @copyright 2020
 * @license   GPL 3.0+
 * @link      https://codeat.co
 */
namespace Glossary\Integrations;

use  Glossary\Engine ;
/**
 * All the CMB related code.
 */
class CMB extends Engine\Base
{
    /**
     * Initialize class.
     *
     * @since 2.0
     * @return bool
     */
    public function initialize()
    {
        parent::initialize();
        require_once GT_PLUGIN_ROOT . 'vendor/cmb2/init.php';
        \add_filter( 'multicheck_posttype_posttypes', array( $this, 'hide_glossary' ) );
        \add_action(
            'cmb2_save_options-page_fields',
            array( $this, 'permalink_alert' ),
            9999,
            4
        );
        return true;
    }
    
    /**
     * Hide glossary post type from settings
     *
     * @param array $cpts The cpts.
     * @return array
     */
    public function hide_glossary( array $cpts )
    {
        unset( $cpts['attachment'] );
        return $cpts;
    }
    
    /**
     * Prompt a reminder to flush the pernalink
     *
     * @param string $object_id CMB Object ID.
     * @param string $cmb_id    CMB ID.
     * @param string $updated   Status.
     * @param array  $object    The CMB object.
     * @return void
     */
    public function permalink_alert(
        $object_id,
        $cmb_id,
        $updated,
        $object
    )
    {
        //phpcs:ignore
        if ( $cmb_id !== GT_SETTINGS . '_options' ) {
            return;
        }
        \wpdesk_wp_notice( \__( 'You must flush the permalink if you changed the slug, go on Settings->Permalink and press Save changes!', GT_TEXTDOMAIN ), 'updated' );
    }

}