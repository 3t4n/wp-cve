<?php

namespace OXIIMAEADDONS\Classes;

/**
 * Description of Admin
 *
 * @author biplo
 */
class Admin {

    public $license;
    public $status;

    /**
     * Constructor of Oxilab tabs Home Page
     *
     * @since 9.3.0
     */
    public function __construct() {
        $this->admin();
        $this->enqueue_scripts();
        $this->Render();
    }

    public function admin() {

        $this->license = get_option('oxi_image_addons_key');
        $this->status  = get_option('oxi-hover-effects-addons-version');
    }

    public function enqueue_scripts() {
        wp_enqueue_style('oxi-admin-css', OXIIMAEADDONS_URL . 'assets/admin.css', array(), OXIIMAEADDONS_PLUGIN_VERSION, 'all');
        wp_enqueue_script('oxi-admin-common', OXIIMAEADDONS_URL . 'assets/admin.js', array('jquery'), OXIIMAEADDONS_PLUGIN_VERSION, true);
        wp_localize_script('oxi-admin-common', 'oxiadmincommon', array(
                'url'   => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('oxi-nonce')
        ));
    }

    public function Render() {
        ?>
        <div class="wrap">
                <form method="post">
                        <br>
                        <br>
                        <h2><?php _e('License Activation'); ?></h2>
                        <p>Activate your copy to get direct plugin updates and official support.</p>
                        <table class="form-table" role="presentation">
                                <tbody>
                                        <tr>
                                                <th scope="row">
                                                        <label for="oxi_image_addons_key">License Key</label>
                                                </th>
                                                <td class="valid">
                                                        <input type="text" class="regular-text" id="oxi_image_addons_key"
                                                               name="oxi_image_addons_key"
                                                               value="<?php echo ($this->status == 'valid' && empty($this->license)) ? '****************************************' : esc_attr($this->license); ?>">
                                                        <span class="oxi-addons-settings-connfirmation oxi_image_addons_license_massage">
                                                            <?php
                                                            if ($this->status == 'valid' && empty($this->license)) :
                                                                echo '<span class="oxi-confirmation-success"></span>';
                                                            elseif ($this->status == 'valid' && !empty($this->license)) :
                                                                echo '<span class="oxi-confirmation-success"></span>';
                                                            elseif (!empty($this->license)) :
                                                                echo '<span class="oxi-confirmation-failed"></span>';
                                                            else :
                                                                echo '<span class="oxi-confirmation-blank"></span>';
                                                            endif;
                                                            ?>
                                                        </span>
                                                        <span class="oxi-addons-settings-connfirmation oxi_image_addons_license_text">
                                                                <?php
                                                                if ($this->status == 'valid' && empty($this->license)) :
                                                                    echo '<span class="oxi-addons-settings-massage">Pre Active</span>';
                                                                elseif ($this->status == 'valid' && !empty($this->license)) :
                                                                    echo '<span class="oxi-addons-settings-massage">Active</span>';
                                                                elseif (!empty($this->license)) :
                                                                    echo '<span class="oxi-addons-settings-massage">' . esc_html($this->status) . '</span>';
                                                                else :
                                                                    echo '<span class="oxi-addons-settings-massage"></span>';
                                                                endif;
                                                                ?>
                                                        </span>
                                                </td>
                                        </tr>
                                </tbody>
                        </table>

                </form>

        </div>
        <?php
    }

}
