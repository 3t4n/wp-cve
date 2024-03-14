<?php
/**
 * @copyright (C) 2021 - 2024 Holger Brandt IT Solutions
 * @license GPL2
*/

if(!defined('ABSPATH')){
    exit;
}

class WADA_View_UserDetails extends WADA_View_BaseForm
{
    const VIEW_IDENTIFIER = 'wada-user-details';
    public $item = null;
    public $userId;

    public function __construct($id = null){
        $this->viewHeadline = __('User details', 'wp-admin-audit');
        $this->parentHeadlineLink = admin_url('admin.php?page=wp-admin-audit-users');
        $this->parentHeadline =  __('Users', 'wp-admin-audit');
        if($id){
            $model = new WADA_Model_User($id, true);
            $this->item = $model->_data;
            if($this->item) {
                $this->viewHeadline = __('Users details', 'wp-admin-audit') . ' - ' . esc_html('#' . absint($this->item->user_id) . ' ' . $this->item->user_nicename);
            }else{
                $this->viewHeadline = __('Users details', 'wp-admin-audit') . ' - ' . esc_html('#' . absint($id));
            }
        }
        $this->userId = $id;
    }

    protected function handleFormSubmissions(){
        if(isset($_POST['submit'])){
            check_admin_referer(self::VIEW_IDENTIFIER);
        }
    }

    protected function displayForm(){
        WADA_Log::debug('View/User->displayForm item: '.print_r($this->item, true));
    ?>
        <div class="wrap">
            <?php $this->printHeadersAndBreadcrumb(); ?>
            <form id="<?php echo self::VIEW_IDENTIFIER; ?>" method="post">
                <?php wp_nonce_field(self::VIEW_IDENTIFIER); ?>
                <input type="hidden" name="page" value="<?php echo esc_attr($this->getCurrentPage()); ?>" />


                <div id="poststuff">
                    <div id="post-body" class="metabox-holder columns-1">
                        <div id="post-body-content">


                            <div id="postbox-container-1" class="postbox-container">
                                <div id="" class="">
                                    <div id="wada-user-details-data-header" class="postbox">
                                        <div class="inside">
                                            <div class="wada-user-details-data-container">
                                                <div class="wada-user-details-data-column">
                                                    <h3><?php _e('General data', 'wp-admin-audit'); ?></h3>
                                                    <?php (new WADA_Layout_UserOverview($this->item, $this->userId))->display(); ?>
                                                </div>
                                            </div>
                                            <div class="clear"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div id="postbox-container-2" class="postbox-container">
                                <div id="" class="">
                                    <div id="wada-user-details-events" class="postbox">
                                        <div class="inside">
                                            <div class="wada-user-details-events-container">

                                                <div class="wada-user-details-events-column">
                                                    <h3><?php _e('Last actions done by user', 'wp-admin-audit'); ?>
                                                        <span class="hTip" title="<?php _e('Events in which the user was the acting individual', 'wp-admin-audit'); ?>"><span class="dashicons dashicons-info"></span></span>
                                                    </h3>
                                                    <?php (new WADA_Layout_EventsList($this->item ? $this->item->lastActivityEvents : array()))->display(); ?>
                                                </div>
                                                <div class="wada-user-details-events-column">
                                                    <h3><?php _e('Last actions done to user account', 'wp-admin-audit'); ?>
                                                        <span class="hTip" title="<?php _e('Events in which the user account was the subject and the acting user could be someone else', 'wp-admin-audit'); ?>"><span class="dashicons dashicons-info"></span></span>
                                                    </h3>
                                                    <?php (new WADA_Layout_EventsList($this->item ? $this->item->lastSubjectEvents : array()))->display(); ?>
                                                </div>

                                            </div>
                                            <div class="clear"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>



            </form>
        </div>
    <?php
    }
}