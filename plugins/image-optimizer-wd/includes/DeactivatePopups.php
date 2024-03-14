<?php

namespace TenWebPluginIO;

class DeactivatePopups
{
    public $linkFrom = '';
    private $deactivateDisconnect = array();
    private $deactivate = array();
    private $disconnectBoth = array();
    private $disconnectIO = array();

    public function __construct($linkFrom)
    {
        $this->setArgs($linkFrom);
        $this->enqueueScripts();
        $this->showPopup();
    }

    private function setArgs($linkFrom)
    {
        $this->linkFrom = $linkFrom;
        $main_difference_text = __('Optimization of unlimited images', 'tenweb-speed-optimizer');
        $plan_limitation = '';
        $dataIO = \TenWebPluginIO\Utils::getImagesData();
        if ((!empty($dataIO) && !empty($dataIO['limitation']) &&
            isset($dataIO['limitation']['plan_limit']))) {
            $plan_limitation = $dataIO['limitation']['plan_limit'];
        }
        if (\TenWebPluginIO\Utils::isFreeUser()) {
            $main_difference_text = sprintf(__('Optimization of %s images per month', 'tenweb-speed-optimizer'), $plan_limitation);
        }
        $this->deactivateDisconnect = array(
            'mainTitle' => __('Disconnect & deactivate Image Optimizer', 'tenweb-speed-optimizer'),
            'desc'      => __('By deactivating Image Optimize you will not be able to benefit from:', 'tenweb-speed-optimizer'),
            'argsList'  => array(
                __('Advanced image optimization', 'tenweb-speed-optimizer'),
                $main_difference_text,
                __('Optimization of media library', 'tenweb-speed-optimizer'),
            ),
            'buttons'   => array(
                'left'  => array(
                    'title' => __('Cancel', 'tenweb-speed-optimizer'),
                    'link'  => '#',
                    'class' => 'two-button-cancel',
                ),
                'right' => array(
                    'title' => __('Disconnect & deactivate', 'tenweb-speed-optimizer'),
                    'link'  => $this->getDeactivationLinks(true, true),
                    'class' => '',
                ),
            ),
        );
        $this->deactivate = array(
            'mainTitle' => __('Deactivate Image Optimizer', 'tenweb-speed-optimizer'),
            'desc'      => __('By deactivating Image Optimize you will not be able to benefit from:', 'tenweb-speed-optimizer'),
            'argsList'  => array(
                __('Advanced image optimization', 'tenweb-speed-optimizer'),
                $main_difference_text,
                __('Optimization of media library', 'tenweb-speed-optimizer'),
            ),
            'buttons'   => array(
                'left'  => array(
                    'title' => __('Cancel', 'tenweb-speed-optimizer'),
                    'link'  => '#',
                    'class' => 'two-button-cancel',
                ),
                'right' => array(
                    'title' => __('Deactivate', 'tenweb-speed-optimizer'),
                    'link'  => $this->getDeactivationLinks(false, true),
                    'class' => '',
                ),
            ),
        );
        $this->disconnectBoth = array(
            'mainTitle' => __('Disconnect website from 10Web', 'tenweb-speed-optimizer'),
            'desc'      => __('If you disconnect your website from 10Web, you will lose both the IO <br>and Speed Booster plugins, resulting in the loss of your website optimization. <br>If you no longer want to use this plugin, deactivate it from the plugins list.', 'tenweb-speed-optimizer'),
            'buttons'   => array(
                'left'  => array(
                    'title' => __('Cancel', 'tenweb-speed-optimizer'),
                    'link'  => '#',
                    'class' => 'two-button-cancel',
                ),
                'right' => array(
                    'title' => __('Disconnect', 'tenweb-speed-optimizer'),
                    'link'  => $this->getDeactivationLinks(true),
                    'class' => '',
                ),
            ),
        );
        $this->disconnectIO = array(
            'mainTitle' => __('Disconnect Image Optimizer', 'tenweb-speed-optimizer'),
            'desc'      => __('By disconnecting Image Optimize you will not be able to benefit from:', 'tenweb-speed-optimizer'),
            'argsList'  => array(
                __('Advanced image optimization', 'tenweb-speed-optimizer'),
                $main_difference_text,
                __('Optimization of media library', 'tenweb-speed-optimizer'),
            ),
            'buttons'   => array(
                'left'  => array(
                    'title' => __('Cancel', 'tenweb-speed-optimizer'),
                    'link'  => '#',
                    'class' => 'two-button-cancel',
                ),
                'right' => array(
                    'title' => __('Disconnect', 'tenweb-speed-optimizer'),
                    'link'  => $this->getDeactivationLinks(true, false),
                    'class' => '',
                ),
            ),
        );
    }

    private function enqueueScripts()
    {
        wp_register_style('iowd-open-sans', 'https://fonts.googleapis.com/css?family=Open+Sans:300,400,500,600,700,800&display=swap');
        wp_enqueue_style(TENWEBIO_PREFIX . '_deactivate_popup',
            TENWEBIO_URL . '/assets/css/deactivate_popup.css', array('iowd-open-sans'), TENWEBIO_VERSION);
        //wp_enqueue_style(TENWEBIO_PREFIX . 'main_css',TENWEBIO_URL . '/assets/css/main.css', array('iowd-open-sans'), TENWEBIO_VERSION);
        wp_enqueue_script(TENWEBIO_PREFIX . '_deactivate_popup', TENWEBIO_URL . '/assets/js/deactivate_popup.js', array('jquery'), TENWEBIO_VERSION);
        wp_localize_script(TENWEBIO_PREFIX . '_deactivate_popup', 'iowd_deactivation', array(
            'basename' => TENWEBIO_MAIN_FILE,
        ));
    }

    private function popupView($args)
    {
        ?>
        <div class="iowd-deactivate-popup">
            <div class="iowd-deactivate-popup-body">
                <?php if (isset($args['mainTitle'])) { ?>
                    <div class="iowd-deactivate-popup-title">
                        <?php echo esc_html($args['mainTitle']); ?>
                    </div>
                <?php } ?>
                <div class="iowd-deactivate-popup-content">
                    <?php if (isset($args['desc'])) { ?>
                        <p><?php echo wp_kses_post($args['desc']); ?></p>
                    <?php }
                    if (isset($args['argsList'])) { ?>
                        <div class="iowd-deactivate-popup-list">
                            <?php
                            foreach ($args['argsList'] as $listItem) {
                                echo '<p>' . esc_html($listItem) . '</p>';
                            }
                            ?>
                        </div>
                    <?php } ?>
                </div>
                <?php if (isset($args['buttons'])) { ?>
                    <div class="iowd-deactivate-popup-button-container">
                        <?php if (isset($args['buttons']['left'])) { ?>
                            <a href="<?php echo esc_url($args['buttons']['left']['link']); ?>"
                               class="<?php esc_attr_e($args['buttons']['left']['class']); ?> iowd-button iowd-button-small iowd-button-cancel">
                                <?php echo esc_html($args['buttons']['left']['title']); ?>
                            </a>
                        <?php } ?>
                        <?php if (isset($args['buttons']['right'])) { ?>
                            <a href="<?php echo esc_url($args['buttons']['right']['link']); ?>"
                               class="<?php esc_attr_e($args['buttons']['right']['class']); ?> iowd-button iowd-button-medium iowd-button-deactivate">
                                <?php echo esc_html($args['buttons']['right']['title']); ?>
                            </a>
                        <?php } ?>
                    </div>
                <?php } ?>
                <img src="<?php echo esc_url(TENWEBIO_URL .'/assets/img/close.svg'); ?>" alt="Close"
                     class="iowd-close-img"/>
            </div>
        </div>
        <?php
    }

    private function showPopup()
    {
        $boosterPluginStatus = \TenWebPluginIO\Utils::getBoosterStatus();
        if ($this->linkFrom == 'iowd_main_page') {
            if ($boosterPluginStatus == 2) {
                $this->popupView($this->disconnectBoth);
            } else {
                $this->popupView($this->disconnectIO);
            }
        } else {
            if ($boosterPluginStatus == 2) {
                $this->popupView($this->deactivate);
            } else {
                $this->popupView($this->deactivateDisconnect);
            }
        }
    }

    /**
     * @return string
     */
    private function getDeactivationLinks($disconnect = false, $deactivate = false)
    {
        $iowd_disconnect_nonce = wp_create_nonce("iowd_disconnect_nonce");
        $query_args = array();
        if ($deactivate) {
            $query_args['action'] = 'deactivate';
            $query_args['plugin'] = TENWEBIO_MAIN_FILE;
            $query_args['_wpnonce'] = wp_create_nonce('deactivate-plugin_' . TENWEBIO_MAIN_FILE);
        }
        if ($disconnect) {
            $query_args['iowd_disconnect'] = 1;
            $query_args['iowd_disconnect_nonce'] = $iowd_disconnect_nonce;
        }
        $url = !empty($query_args) ? add_query_arg($query_args, admin_url('plugins.php')) : '#';

        return $url;
    }
}