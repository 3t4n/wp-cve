<?php
namespace FormInteg\IZCRMEF\Core\Util;

/**
 * Class handling plugin deactivation.
 *
 * @since 1.0.0
 * @access private
 * @ignore
 */
final class Deactivation
{
    /**
     * Registers functionality through WordPress hooks.
     *
     * @since 1.0.0
     */
    public function register()
    {
        add_action('izcrmef_deactivation', [$this, 'deactive']);
    }

    public function deactive()
    {
    }
}
