<?php
/**
 * @copyright (C) 2021 - 2024 Holger Brandt IT Solutions
 * @license GPL2
*/

if(!defined('ABSPATH')){
    exit;
}

class WADA_View_NotificationWizard extends WADA_View_BaseForm
{
    const VIEW_IDENTIFIER = 'wada-notification-wizard';
    public $id;
    public $item = null;
    protected $redirectBackToList = false;

    public function __construct($id = 0) {
        $this->id = $id;
        $this->viewHeadline = __('Add notification', 'wp-admin-audit');
        $this->parentHeadlineLink = admin_url('admin.php?page=wp-admin-audit-notifications');
        $this->parentHeadline =  __('Notifications', 'wp-admin-audit');
        if($id){
            $this->viewHeadline = __('Edit notification', 'wp-admin-audit');
            $model = new WADA_Model_Notification($id);
            $this->item = $model->_data;
        }
        add_action('admin_footer', array($this, 'loadJavascriptActions'));
        WADA_ScriptUtils::loadSmartWizard();
        WADA_ScriptUtils::loadSelect2();
        WADA_ScriptUtils::loadSelectize();
    }

    protected function handleFormSubmissions(){
        if(isset($_POST['submit'])){
            check_admin_referer(self::VIEW_IDENTIFIER);
            WADA_Log::debug('NotificationWizard->submit post: '.print_r($_POST, true));

            $notificationId = array_key_exists('id', $_POST) ? intval($_POST['id']) : 0;

            try {
                $timezoneTime = new DateTime('now', wp_timezone());
                $timezoneTime = $timezoneTime->format('Y-m-d H:i:s');
            }catch(Exception $e){
                $timezoneTime = '';
            }
            $name = array_key_exists('name', $_POST) ? sanitize_text_field($_POST['name']) : ($timezoneTime.' ' .__('Notification'));
            $active = array_key_exists('active', $_POST) ? intval($_POST['active']) : 0;

            $notification = new stdClass();
            $notification->id = $notificationId;
            $notification->active = $active;
            $notification->name = $name;

            $triggers = array();
            $triggerType = array_key_exists('trigger_type', $_POST) ? strtolower(sanitize_text_field($_POST['trigger_type'])) : '';
            if($triggerType === 'sensor' || array_key_exists('selected_sensors', $_POST)){
                $sensorIdArray = array_key_exists('selected_sensors', $_POST) ? array_map('intval', $_POST['selected_sensors']) : array();
                foreach($sensorIdArray AS $triggerId){
                    $trigger = new stdClass();
                    $trigger->trigger_type = 'sensor';
                    $trigger->trigger_id = $triggerId;
                    $trigger->trigger_str_id = '';
                    $triggers[] = $trigger;
                }
            }
            if($triggerType === 'severity' || array_key_exists('severity_levels', $_POST)){
                $severityLevelArr = array_key_exists('severity_levels', $_POST) ? array_map('intval', $_POST['severity_levels']) : array();
                foreach($severityLevelArr AS $triggerId){
                    $trigger = new stdClass();
                    $trigger->trigger_type = 'severity';
                    $trigger->trigger_id = $triggerId;
                    $trigger->trigger_str_id = '';
                    $triggers[] = $trigger;
                }
            }

            $emailWpUserRole = array_key_exists('email_wp_user_role', $_POST) ? array_map('sanitize_text_field', $_POST['email_wp_user_role']) : array();

            global $wp_roles;
            if ( ! isset( $wp_roles ) ) {
                $wp_roles = new WP_Roles();
            }
            $rolesNames = $wp_roles->get_names();
            $rolesNames = array_keys($rolesNames);
            WADA_Log::debug('rolesNames: '.print_r($rolesNames, true));

            $emailWpUserRole = array_filter($emailWpUserRole, function($value) use ($rolesNames) { return in_array($value, $rolesNames); });
            WADA_Log::debug('filtered emailWpUserRole: '.print_r($emailWpUserRole, true));

            $emailWpUserIds = array_key_exists('email_wp_user', $_POST) ? array_map('intval', $_POST['email_wp_user']) : array();
            WADA_Log::debug('emailWpUserIds: '.print_r($emailWpUserIds, true));

            $emailAdditionalRecips = array_key_exists('email_additional', $_POST) ? strtolower(sanitize_text_field($_POST['email_additional'])) : '';
            $emailAdditionalRecips = explode(',', $emailAdditionalRecips);
            $emailAdditionalRecips = is_array($emailAdditionalRecips) ? $emailAdditionalRecips : array();
            WADA_Log::debug('emailAdditionalRecips: '.print_r($emailAdditionalRecips, true));

            $integrationLogsnag = array_key_exists('integration_logsnag', $_POST);

            $targets = array();
            foreach($emailWpUserRole AS $role){
                $target = new stdClass();
                $target->channel_type = 'email';
                $target->target_type = 'wp_role';
                $target->target_id = '';
                $target->target_str_id = $role;
                $targets[] = $target;
            }

            foreach($emailWpUserIds AS $userId){
                $target = new stdClass();
                $target->channel_type = 'email';
                $target->target_type = 'wp_user';
                $target->target_id = $userId;
                $target->target_str_id = '';
                $targets[] = $target;
            }

            foreach($emailAdditionalRecips AS $addRecipEmail){
                $addRecipEmail = trim($addRecipEmail);
                if(!empty($addRecipEmail) && filter_var($addRecipEmail, FILTER_VALIDATE_EMAIL) !== false) {
                    $target = new stdClass();
                    $target->channel_type = 'email';
                    $target->target_type = 'email';
                    $target->target_id = '';
                    $target->target_str_id = $addRecipEmail;
                    $targets[] = $target;
                }
            }

            if($integrationLogsnag){
                $target = new stdClass();
                $target->channel_type = 'logsnag';
                $target->target_type = 'logsnag';
                $target->target_id = '';
                $target->target_str_id = '';
                $targets[] = $target;
            }

            WADA_Log::debug('notification triggers: '.print_r($triggers, true));
            WADA_Log::debug('notification targets: '.print_r($targets, true));
            $notification->triggers = $triggers;
            $notification->targets = $targets;

            $notificationModel = new WADA_Model_Notification();
            $res = $notificationModel->store($notification);

            if($res !== false){
                $this->enqueueMessage(__('Notification saved', 'wp-admin-audit'), 'success');
                $model = new WADA_Model_Notification($res); // $res has the notification ID
                $this->item = $model->_data;
                WADA_Log::debug('After saving, updated notification: '.print_r($this->item, true));
                if($notificationId == 0) { // for new notifications, redirect back to list view
                    $this->redirectBackToList = true;
                }
            }else{
                $this->enqueueMessage(__('There was a problem saving the notification', 'wp-admin-audit'), 'error');
            }
        }
    }

    protected function displayForm(){
        $userSearchNonce = wp_create_nonce('wada_user_search');
        $severityLevels = WADA_Model_Sensor::getSeverityLevels(true);
        $sensors = WADA_Model_Sensor::getAllSensors(true, array(), array('event_group', 'name'));
        $userCountRes = count_users();
        WADA_Log::debug('NotificationWizard request: '.print_r($_REQUEST, true));
        $collapsibleSectionHide = $this->id ? '' : 'display:none;';
        WADA_Log::debug('Notification item: '.print_r($this->item, true));
        $displayNameAndActiveOnTop = ($this->id > 0);
    ?>
        <div class="wrap">
            <?php $this->printHeadersAndBreadcrumb(); ?>
            <form id="<?php echo self::VIEW_IDENTIFIER; ?>" method="post">
                <div class="wada-wizard">
                    <!-- SmartWizard html -->
                    <div id="smartwizard">
                        <?php if($this->id == 0): ?>
                        <ul class="nav">
                            <li class="nav-item">
                                <a class="nav-link" href="#step-1">
                                    <div class="num">1</div>
                                    <?php _e('Trigger type', 'wp-admin-audit'); ?>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#step-2">
                                    <span class="num">2</span>
                                    <?php _e('Trigger choice', 'wp-admin-audit'); ?>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#step-3">
                                    <span class="num">3</span>
                                    <?php _e('Recipients', 'wp-admin-audit'); ?>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link " href="#step-4">
                                    <span class="num">4</span>
                                    <?php _e('Summary', 'wp-admin-audit'); ?>
                                </a>
                            </li>
                        </ul>
                        <?php endif; ?>

                        <?php if($displayNameAndActiveOnTop): ?>
                            <label for="name">
                                <span class="wada-input-label"><?php _e('Notification name', 'wp-admin-audit'); ?></span>
                                <input id="name" name="name" value="<?php echo $this->id ? esc_attr($this->item->name) : '' ?>" class="large-text" />
                            </label>
                            <label for="active">
                                <span class="wada-input-label"><?php _e('Active', 'wp-admin-audit'); ?></span>
                                <?php WADA_HtmlUtils::boolToggleField('active', __('Active', 'wp-admin-audit'), $this->id ? $this->item->active : 1, array('render_as_table_row' => false, 'input_class' => 'sensor-status-toggle', 'omit_label' => true)); ?>
                            </label>
                        <?php endif; ?>

                        <div class="tab-content">
                            <?php if($this->id == 0): ?>
                            <div id="step-1" class="tab-pane" role="tabpanel" aria-labelledby="step-1">
                                <div class="wada-step-header">
                                    <h2><?php _e('Send notifications for:', 'wp-admin-audit'); ?></h2>
                                </div>
                                <div class="wada-step-content">
                                    <ul class="wada-button-list">
                                        <li>
                                            <div class="">
                                                <div class="wada-super-button trigger-choice" data-triggertype="severity">
                                                    <?php esc_attr_e('All events of a severity level', 'wp-admin-audit'); ?>
                                                    <div class="wada-point wada-point-right wada-point-arrow-right"></div>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="">
                                                <div class="wada-super-button trigger-choice" data-triggertype="sensor">
                                                    <?php esc_attr_e('Specific events', 'wp-admin-audit'); ?>
                                                    <div class="wada-point wada-point-right wada-point-arrow-right"></div>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                    <input type="hidden" id="trigger_type" name="trigger_type" value="" />
                                </div>
                            </div>
                            <?php endif; ?>
                            <div id="step-2" class="tab-pane" role="tabpanel" aria-labelledby="step-2">
                                <div class="wada-step-header">
                                    <h2><?php _e('The following triggers the notification:', 'wp-admin-audit'); ?></h2>
                                </div>
                                <div id="step-2-msg-top" class="wada-wizard-msg"></div>
                                <div id="trigger_choice_severity">
                                    <label for="severity_levels">
                                        <span class="wada-input-label"><?php _e('Select one or multiple severity levels', 'wp-admin-audit'); ?></span>
                                        <select id="severity_levels" name="severity_levels[]" multiple="multiple">
                                            <?php
                                            foreach($severityLevels AS $severityLevel => $severityName){
                                                $selected = '';
                                                if($this->id > 0 && count($this->item->triggers)){
                                                    foreach($this->item->triggers AS $triggerObj){
                                                        if($triggerObj->trigger_type === 'severity' && $triggerObj->trigger_id == $severityLevel){
                                                            $selected = 'selected="selected"';
                                                        }
                                                    }
                                                }
                                                echo '<option value="'.esc_attr($severityLevel).'" '.$selected.'>'.esc_html($severityName).'</option>';
                                            }
                                            ?>
                                        </select>
                                    </label>
                                </div>

                                <div id="trigger_choice_sensor">
                                    <label for="selected_sensors">
                                        <span class="wada-input-label"><?php _e('Select one or multiple event sensors', 'wp-admin-audit'); ?></span>
                                        <select id="selected_sensors" name="selected_sensors[]" multiple="multiple">
                                            <?php
                                            $lastOptGroup = null;
                                            foreach($sensors AS $sensor){
                                                $selected = '';
                                                if($this->id > 0 && count($this->item->triggers)){
                                                    foreach($this->item->triggers AS $triggerObj){
                                                        if($triggerObj->trigger_type === 'sensor' && $triggerObj->trigger_id == $sensor['id']){
                                                            $selected = 'selected="selected"';
                                                        }
                                                    }
                                                }
                                                if($lastOptGroup !== $sensor['event_group']){
                                                    if(!is_null($lastOptGroup)){
                                                        echo '</optgroup>';
                                                    }
                                                    echo '<optgroup label="'.esc_attr($sensor['event_group']).'">';
                                                    $lastOptGroup = $sensor['event_group'];
                                                }
                                                echo '<option value="'.esc_attr($sensor['id']).'" '.$selected.'>'.esc_html($sensor['name']).'</option>';
                                            }
                                            echo '</optgroup>'; // close last group
                                            ?>
                                        </select>
                                    </label>
                                </div>

                                <div id="step-2-msg" class="wada-wizard-msg"></div>

                            </div>
                            <div id="step-3" class="tab-pane" role="tabpanel" aria-labelledby="step-3">
                                <div class="wada-step-header">
                                    <h2><?php _e('Send email notification to:', 'wp-admin-audit'); ?></h2>
                                </div>
                                <div id="step-3-msg-top" class="wada-wizard-msg"></div>
                                <div id="target-email-container">
                                    <ul class="wada-button-list">
                                        <li>
                                            <div class="wada-slider">
                                                <?php if($this->id == 0): ?>
                                                <div class="wada-slider-header">
                                                    <div class="wada-point wada-point-left wada-point-plus"></div>
                                                    <?php _e('WordPress user role', 'wp-admin-audit'); ?>
                                                </div>
                                                <?php endif; ?>
                                                <div class="wada-slider-body" style="<?php echo $collapsibleSectionHide; ?>">
                                                    <label for="email_wp_user_role">
                                                        <span class="wada-input-label"><?php _e('Select one or multiple WordPress user roles', 'wp-admin-audit'); ?></span>
                                                        <select id="email_wp_user_role" name="email_wp_user_role[]" multiple="multiple">
                                                            <?php
                                                            global $wp_roles;
                                                            if ( ! isset( $wp_roles ) ) {
                                                                $wp_roles = new WP_Roles();
                                                            }
                                                            $roles = $wp_roles->get_names();
                                                            foreach($roles AS $role => $roleName){
                                                                $selected = '';
                                                                if($this->id > 0 && count($this->item->targets)) {
                                                                    foreach ($this->item->targets as $targetObj) {
                                                                        if($targetObj->channel_type === 'email'
                                                                            && $targetObj->target_type == 'wp_role'
                                                                            && $targetObj->target_str_id == $role){
                                                                            $selected = 'selected="selected"';
                                                                        }
                                                                    }
                                                                }
                                                                echo '<option value="'.esc_attr($role).'" '.$selected.'>'.esc_html($roleName).'</option>';
                                                            }
                                                            ?>
                                                        </select>
                                                    </label>
                                                    <div id="wp-user-role-msg" class="wada-wizard-msg">
                                                        <?php _e('#users in scope with the current selection:'); ?> <span id="email_wp_user_role_user_cr"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="wada-slider">
                                                <?php if($this->id == 0): ?>
                                                <div class="wada-slider-header">
                                                    <div class="wada-point wada-point-left wada-point-plus"></div>
                                                    <?php _e('Individual WordPress users', 'wp-admin-audit'); ?>
                                                </div>
                                                <?php endif; ?>
                                                <div class="wada-slider-body" style="<?php echo $collapsibleSectionHide; ?>">
                                                    <label for="email_wp_user">
                                                        <span class="wada-input-label"><?php _e('Select one or multiple WordPress users', 'wp-admin-audit'); ?></span>
                                                        <select id="email_wp_user" name="email_wp_user[]" multiple="multiple">
                                                            <?php
                                                            if($this->id > 0 && count($this->item->targets)){
                                                                foreach($this->item->targets AS $targetObj){
                                                                    if($targetObj->channel_type === 'email' && $targetObj->target_type === 'wp_user'){
                                                                        WADA_Log::debug('wp-user match: '.print_r($targetObj, true));
                                                                        $name = '#' . $targetObj->target_id . ' ' . get_user_option('display_name', $targetObj->target_id);
                                                                        WADA_Log::debug('wp-user name: '.$name.', value: '.esc_attr($targetObj->target_id));
                                                                        echo '<option value="'.esc_attr($targetObj->target_id).'" selected="selected">'.esc_html($name).'</option>';
                                                                    }
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </label>
                                                    <input type="hidden" id="user-search-nonce" value="<?php echo $userSearchNonce; ?>" />
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="wada-slider">
                                                <?php if($this->id == 0): ?>
                                                <div class="wada-slider-header">
                                                    <div class="wada-point wada-point-left wada-point-plus"></div>
                                                    <?php _e('Other email addresses', 'wp-admin-audit'); ?>
                                                </div>
                                                <?php endif; ?>
                                                <div class="wada-slider-body" style="<?php echo $collapsibleSectionHide; ?>">
                                                    <label for="email_additional">
                                                        <span class="wada-input-label"><?php _e('Add additional email addresses if needed', 'wp-admin-audit'); ?></span>
                                                        <?php
                                                            $additionalEmails = array();
                                                            if($this->id > 0 && count($this->item->targets)){
                                                                foreach($this->item->targets AS $targetObj){
                                                                    if($targetObj->channel_type === 'email' && $targetObj->target_type === 'email'){
                                                                        $additionalEmails[] = $targetObj->target_str_id;
                                                                    }
                                                                }
                                                            }
                                                            $additionalEmails = implode(',', $additionalEmails);
                                                        ?>
                                                        <input id="email_additional" name="email_additional" value="<?php echo esc_attr($additionalEmails); ?>" placeholder="<?php esc_attr_e('Enter or copy/paste (comma-separated) email addresses here', 'wp-admin-audit'); ?>" class="large-text" />
                                                    </label>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                                <h2><?php _e('Send notification via integration:', 'wp-admin-audit'); ?></h2>
                                <div id="target-integrations-container">
                                    <ul class="wada-button-list">
                                        <li>
                                            <div class="wada-slider">
                                                <?php
                                                $integEnabled = WADA_Settings::isIntegrationForLogsnagEnabled();
                                                $integHeaderClass = $integEnabled ? '' : 'wada-slider-deactivated';
                                                $integTitle = $integEnabled ? '' : __('Integration not enabled. Please go to the WP Admin Audit settings >> Integrations to set it up.', 'wp-admin-audit');
                                                ?>
                                                <?php if($this->id == 0): ?>
                                                    <div class="wada-slider-header <?php echo $integHeaderClass; ?>" title="<?php echo $integTitle; ?>">
                                                        <div class="wada-point wada-point-left wada-point-plus"></div>
                                                        <?php echo 'Logsnag'; ?>
                                                    </div>
                                                <?php endif; ?>
                                                <div class="wada-slider-body" style="<?php echo $collapsibleSectionHide; ?>">
                                                    <?php if($integEnabled):
                                                        ?>
                                                        <label for="integration_logsnag">
                                                            <span class="notification-target-container" style="width:75%">
                                                                <span class="wada-input-label"><?php echo sprintf(__('Send notification via %s', 'wp-admin-audit'), 'Logsnag'); ?></span>
                                                                <?php
                                                                $targetIsUsed = false;
                                                                if($this->id > 0 && count($this->item->targets)){
                                                                    foreach($this->item->targets AS $targetObj){
                                                                        if($targetObj->channel_type === 'logsnag'){
                                                                            $targetIsUsed = true;
                                                                        }
                                                                    }
                                                                }
                                                                $checked = $targetIsUsed ? 'checked="checked"' : '';
                                                                $label = sprintf(__('Sent to project %s', 'wp-admin-audit'), WADA_Settings::getIntegrationForLogsnagProject());
                                                                WADA_HtmlUtils::boolToggleField('integration_logsnag', $label, $targetIsUsed, array('render_as_table_row' => false, 'input_class' => 'sensor-status-toggle'));
                                                                ?>
                                                            </span>
                                                        </label>

                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>

                                <div id="step-3-msg" class="wada-wizard-msg"></div>
                            </div>
                            <div id="step-4" class="tab-pane" role="tabpanel" aria-labelledby="step-4">
                                <?php wp_nonce_field(self::VIEW_IDENTIFIER); ?>
                                <input type="hidden" name="id" value="<?php echo $this->id; ?>" />
                                <input type="hidden" name="page" value="<?php echo esc_attr($this->getCurrentPage()); ?>" />
                                <?php if(!$displayNameAndActiveOnTop): ?>
                                <label for="name">
                                    <span class="wada-input-label"><?php _e('Notification name', 'wp-admin-audit'); ?></span>
                                    <input id="name" name="name" value="<?php echo $this->id ? esc_attr($this->item->name) : '' ?>" class="large-text" />
                                </label>
                                <label for="active">
                                    <span class="wada-input-label"><?php _e('Active', 'wp-admin-audit'); ?></span>
                                    <?php WADA_HtmlUtils::boolToggleField('active', __('Active', 'wp-admin-audit'), $this->id ? $this->item->active : 1, array('render_as_table_row' => false, 'input_class' => 'sensor-status-toggle', 'omit_label' => true)); ?>
                                </label>
                                <?php endif; ?>
                                <div class="wada-step-header">
                                    <h2><?php _e('Summary', 'wp-admin-audit'); ?></h2>
                                </div>
                                <div id="wada-wizard-summary">
                                    <input type="hidden" id="summary-total-trigger-cr" name="summary-total-trigger-cr" value="" />
                                    <table class="form-table notification-wizard-summary-table">
                                        <tr>
                                            <th id="summary-trigger-type-label"><?php _e('Trigger type', 'wp-admin-audit'); ?></th>
                                            <td id="summary-trigger-type"></td>
                                        </tr>
                                        <tr>
                                            <th><?php _e('Triggers', 'wp-admin-audit'); ?></th>
                                            <td id="summary-triggers"></td>
                                        </tr>
                                        <tr>
                                            <th><?php _e('Email recipients', 'wp-admin-audit'); ?></th>
                                            <td>&nbsp;</td>
                                            <th><?php _e('#recipients', 'wp-admin-audit'); ?></th>
                                        </tr>
                                        <tr>
                                            <td><?php _e('User roles', 'wp-admin-audit'); ?></td>
                                            <td id="summary-email-wp-user-role">&nbsp;</td>
                                            <td id="summary-email-wp-user-role-user-cr">&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td><?php _e('WordPress users', 'wp-admin-audit'); ?></td>
                                            <td id="summary-email-wp-user">&nbsp;</td>
                                            <td id="summary-email-wp-user-cr">&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td><?php _e('Additional recipients', 'wp-admin-audit'); ?></td>
                                            <td id="summary-email-additional">&nbsp;</td>
                                            <td id="summary-email-additional-cr">&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td>&nbsp;</td>
                                            <th style="text-align:right;">&nbsp<?php _e('#Total recipients', 'wp-admin-audit'); ?></th>
                                            <th id="summary-total-recipients-cr">&nbsp;</th>
                                        </tr>
                                        <tr>
                                            <th><?php _e('Integrations', 'wp-admin-audit'); ?></th>
                                            <td id="summary-integrations">&nbsp;</td>
                                            <td>&nbsp;</td>
                                        </tr>
                                    </table>
                                    <?php if($this->id > 0): ?>
                                    <input id="submitBtn" name="submit" type="submit" class="button button-primary" value="<?php esc_attr_e('Save notification', 'wp-admin-audit'); ?>" />
                                    <?php endif; ?>
                                </div>

                            </div>
                        </div>

                        <!-- Include optional progressbar HTML -->
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>



                </div>
            </form>
        </div>
    <?php
    }

    function loadJavascriptActions(){ ?>
    <script type="text/javascript">
        <?php
        // after saving new notifications, we forward to list view via JavaScript
        if($this->redirectBackToList):
        $message = urlencode(__('Notification created', 'wp-admin-audit'));
        $redirectUrl = admin_url('admin.php?page=wp-admin-audit-notifications&msg='.$message.'&mt=success');
        ?>
        window.location.replace("<?php echo esc_js($redirectUrl); ?>");
        <?php endif; ?>

        let userCountArray = <?php echo json_encode(count_users()); ?>;
        jQuery(document).ready(function() {

            // define FINISH BUTTON
            var btnFinish = jQuery('<button name="submit" type="submit"></button>').text('<?php echo esc_js(__('Save notification', 'wp-admin-audit')); ?>').addClass('btn sw-btn-fnsh sw-btn');

            if(<?php echo intval($this->id); ?> == 0)
            {
                // for new notifications (id == 0)
                // we run it as a wizard

                // SmartWizard initialize
                jQuery('#smartwizard').smartWizard({
                    selected: 0, // Initial selected step, 0 = first step
                    theme: 'default', // theme for the wizard, related css need to include for other than default theme
                    justified: true, // Nav menu justification. true/false
                    autoAdjustHeight: false, // Automatically adjust content height
                    backButtonSupport: true, // Enable the back button support
                    enableUrlHash: true, // Enable selection of the step based on url hash
                    transition: {
                        animation: 'slideSwing', // Animation effect on navigation, none|fade|slideHorizontal|slideVertical|slideSwing|css(Animation CSS class also need to specify)
                        speed: '400', // Animation speed. Not used if animation is 'css'
                        easing: '', // Animation easing. Not supported without a jQuery easing plugin. Not used if animation is 'css'
                        prefixCss: '', // Only used if animation is 'css'. Animation CSS prefix
                        fwdShowCss: '', // Only used if animation is 'css'. Step show Animation CSS on forward direction
                        fwdHideCss: '', // Only used if animation is 'css'. Step hide Animation CSS on forward direction
                        bckShowCss: '', // Only used if animation is 'css'. Step show Animation CSS on backward direction
                        bckHideCss: '', // Only used if animation is 'css'. Step hide Animation CSS on backward direction
                    },
                    toolbar: {
                        position: 'bottom', // none|top|bottom|both
                        showNextButton: true, // show/hide a Next button
                        showPreviousButton: true, // show/hide a Previous button
                        extraHtml: btnFinish // Extra html to show on toolbar
                    },
                    anchor: {
                        enableNavigation: true, // Enable/Disable anchor navigation
                        enableNavigationAlways: false, // Activates all anchors clickable always
                        enableDoneState: false, // Add done state on visited steps
                        markPreviousStepsAsDone: true, // When a step selected by url hash, all previous steps are marked done
                        unDoneOnBackNavigation: false, // While navigate back, done state will be cleared
                        enableDoneStateNavigation: false // Enable/Disable the done state navigation
                    },
                    keyboard: {
                        keyNavigation: true, // Enable/Disable keyboard navigation(left and right keys are used if enabled)
                        keyLeft: [37], // Left key code
                        keyRight: [39] // Right key code
                    },
                    lang: { // Language variables for button
                        next: '<?php echo esc_js(__('Next', 'wp-admin-audit')); ?>',
                        previous: '<?php echo esc_js(__('Previous', 'wp-admin-audit')); ?>'
                    },
                    disabledSteps: [], // Array Steps disabled
                    errorSteps: [], // Array Steps error
                    warningSteps: [], // Array Steps warning
                    hiddenSteps: [], // Hidden steps
                    getContent: null // Callback function for content loading
                });


                jQuery("#smartwizard").on("leaveStep", function(e, anchorObject, currentStepIndex, nextStepIndex, stepDirection) {
                    let leaveStep = true;
                    if(currentStepIndex === 1 && nextStepIndex === 2){ // going from trigger choice to next step
                        let triggerType = jQuery('#trigger_type').val();
                        let nrSelectedTriggers = 0;
                        let triggers;
                        if(triggerType === 'severity'){
                            triggers = jQuery('#severity_levels').select2('data');
                        }else{
                            triggers = jQuery('#selected_sensors').select2('data');
                        }
                        nrSelectedTriggers = triggers.length;
                        if(nrSelectedTriggers < 1){
                            jQuery('#step-2-msg').html('<?php echo esc_js(__('Please select at least one item', 'wp-admin-audit')); ?>').addClass('wada-error');
                            leaveStep = false;
                        }else{
                            jQuery('#step-2-msg').html('').removeClass('wada-error');
                        }
                    }
                    if(nextStepIndex === 3){ // when going to last page
                        if(jQuery('#name').val().length === 0){
                            jQuery('#name').val(createNotificationName());
                        }
                    }
                    updateSummaryPage();

                    return leaveStep;
                });

                jQuery("#smartwizard").on("showStep", function(e, anchorObject, stepIndex, stepDirection, stepPosition) {
                    // stepIndex from 0 to x
                    // stepDirection forward | backward
                    // stepPosition first | middle | last
                    if(stepIndex === 0){
                        jQuery('.sw .toolbar').hide(); // hide prev/next button on first step (since we select it with custom buttons)
                    }else{
                        jQuery('.sw .toolbar').show();
                        if (stepPosition === 'last') {
                            jQuery('.sw .toolbar button.sw-btn-next').hide();
                            jQuery('.sw .toolbar button.sw-btn-fnsh').show();
                        }else{
                            jQuery('.sw .toolbar button.sw-btn-next').show();
                            jQuery('.sw .toolbar button.sw-btn-fnsh').hide();
                        }
                    }
                });

                jQuery('.trigger-choice').on('click', function(e){
                    let triggerType = jQuery(e.target).data('triggertype');
                    jQuery('#trigger_type').val(triggerType);
                    jQuery('#smartwizard').smartWizard("next");
                    if(triggerType === 'severity'){
                        jQuery('#trigger_choice_severity').show();
                        jQuery('#trigger_choice_sensor').hide();
                    }else{
                        jQuery('#trigger_choice_severity').hide();
                        jQuery('#trigger_choice_sensor').show();
                    }
                    updateSummaryPage();
                });

                jQuery('.wada-slider-header').on('click', function(e){
                    jQuery(e.target).parent().children('.wada-slider-body').toggle();
                });

            }else{
                // for existing notifications (id > 0)
                // no wizard look needed
                jQuery('form#<?php echo self::VIEW_IDENTIFIER; ?>').on('submit', function(e){
                    console.log('submit');
                    let totalTriggers = parseInt(jQuery('#summary-total-trigger-cr').val());
                    let totalTargets = parseInt(jQuery('#summary-total-recipients-cr').text());

                    if(totalTriggers <= 0){
                        jQuery('#step-2-msg-top').html('<?php echo esc_js(__('Please select at least one item', 'wp-admin-audit')); ?>').addClass('wada-error');
                    }else{
                        jQuery('#step-2-msg-top').html('').removeClass('wada-error');
                    }
                    if(totalTargets <= 0){
                        jQuery('#step-3-msg-top').html('<?php echo esc_js(__('Please select at least one item', 'wp-admin-audit')); ?>').addClass('wada-error');
                    }else{
                        jQuery('#step-3-msg-top').html('').removeClass('wada-error');
                    }

                    if(totalTriggers <= 0 || totalTargets <= 0){
                        e.preventDefault();
                    }

                });

            }


            // select2 initialize
            jQuery('#severity_levels').select2({
                width: '75%',
                closeOnSelect: false,
                multiple: true,
                dropdownAutoWidth: true
            }).on('change', function(e) {
                updateSummaryPage();
            });

            // Setup for custom dropdownAdapter to enable dedicated search field + have closeOnSelect disabled/false
            var Utils = jQuery.fn.select2.amd.require('select2/utils');
            var Dropdown = jQuery.fn.select2.amd.require('select2/dropdown');
            var DropdownSearch = jQuery.fn.select2.amd.require('select2/dropdown/search');
            //var CloseOnSelect = jQuery.fn.select2.amd.require('select2/dropdown/closeOnSelect');
            var CloseOnSelect = function(){return false;};
            var AttachBody = jQuery.fn.select2.amd.require('select2/dropdown/attachBody');
            var dropdownAdapter = Utils.Decorate(Utils.Decorate(Utils.Decorate(Dropdown, DropdownSearch), CloseOnSelect), AttachBody);

            // select2 initialize
            jQuery('#selected_sensors').select2({
                width: '75%',
                closeOnSelect: false,
                multiple: true,
                dropdownAdapter: dropdownAdapter,
                dropdownAutoWidth: true
            }).on('select2:opening select2:closing', function (event) {
                //Disable original search (https://select2.org/searching#multi-select)
                var searchfield = jQuery(this).parent().find('.select2-search__field');
                searchfield.prop('disabled', true);
            }).on('change', function(e) {
                updateSummaryPage();
            });

            // select2 initialize
            jQuery('#email_wp_user_role').select2({
                width: '75%',
                closeOnSelect: false,
                multiple: true,
                dropdownAutoWidth: true
            }).on('change', function(e) {
                let userCr = calculateUserCountInRoles(jQuery(e.target).val());
                jQuery('#email_wp_user_role_user_cr').html(userCr);
                updateSummaryPage();
            });

            // select2 initialize
            jQuery('#email_wp_user').select2({
                width: '75%',
                closeOnSelect: false,
                multiple: true,
                dropdownAdapter: dropdownAdapter,
                dropdownAutoWidth: true,
                ajax: {
                    url: ajaxurl, // AJAX URL is predefined in WordPress admin
                    dataType: 'json',
                    delay: 250, // delay in ms while typing when to perform a AJAX search
                    data: function (params) {
                        return {
                            _wpnonce: jQuery('#user-search-nonce').val(),
                            action: '_wada_ajax_user_search',
                            s: params.term, // search query
                            exclude: jQuery('#email_wp_user').val()
                        };
                    },
                    processResults: function( response ) {
                        let options = [];
                        if(response && response.success){
                            // data is the array of arrays, and each of them contains ID and the Label of the option
                            jQuery.each( response.users, function( index, user ) { // do not forget that "index" is just auto incremented value
                                options.push( { id: user.ID, text: user.select_option  } );
                            });
                        }
                        return {
                            results: options
                        };
                    },
                    cache: true
                },
                minimumInputLength: 3 // the minimum of symbols to input before perform a search
            }).on('select2:opening select2:closing', function (event) {
                //Disable original search (https://select2.org/searching#multi-select)
                var searchfield = jQuery(this).parent().find('.select2-search__field');
                searchfield.prop('disabled', true);
                searchfield.prop('inputmode','none');
            }).on('change', function(e) {
                updateSummaryPage();
            });

            var REGEX_EMAIL = "([a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@" + "(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?)";

            jQuery("#email_additional").selectize({
                persist: false,
                maxItems: null,
                valueField: "email",
                labelField: "name",
                searchField: ["name", "email"],
                options: [],
                //options: [{ email: "brian@thirdroute.com", name: "Brian Reavis" }, { email: "nikola@tesla.com", name: "Nikola Tesla" }, { email: "someone@gmail.com" }],
                render: {
                    item: function (item, escape) {
                        return "<div>" + (item.name ? '<span class="name">' + escape(item.name) + "</span>" : "") + (item.email ? '<span class="email">' + escape(item.email) + "</span>" : "") + "</div>";
                    },
                    option: function (item, escape) {
                        var label = item.name || item.email;
                        var caption = item.name ? item.email : null;
                        return "<div>" + '<span class="label">' + escape(label) + "</span>" + (caption ? '<span class="caption">' + escape(caption) + "</span>" : "") + "</div>";
                    },
                },
                createFilter: function (input) {
                    var match, regex;

                    // email@address.com
                    regex = new RegExp("^" + REGEX_EMAIL + "$", "i");
                    match = input.match(regex);
                    if (match) return !this.options.hasOwnProperty(match[0]);

                    // name <email@address.com>
                    regex = new RegExp("^([^<]*)\<" + REGEX_EMAIL + "\>$", "i");
                    match = input.match(regex);
                    if (match) return !this.options.hasOwnProperty(match[2]);

                    return false;
                },
                create: function (input) {
                    if (new RegExp("^" + REGEX_EMAIL + "$", "i").test(input)) {
                        return { email: input };
                    }
                    var match = input.match(new RegExp("^([^<]*)\<" + REGEX_EMAIL + "\>$", "i"));
                    if (match) {
                        return {
                            email: match[2],
                            name: jQuery.trim(match[1]),
                        };
                    }
                    alert("Invalid email address.");
                    return false;
                },
            }).on('change', function(e) {
                updateSummaryPage();
            });


            jQuery('#email_wp_user_role').change(); // trigger change event for init
            updateSummaryPage(); // initially

        });

        function calculateUserCountInRoles(userRoleArray){
            let userCr = 0;
            if(userCountArray && 'avail_roles' in userCountArray){
                userRoleArray.forEach(role => {
                    if(role in userCountArray.avail_roles){
                        userCr += userCountArray.avail_roles[role];
                    }
                });
            }
            return userCr;
        }

        function createNotificationName(){
            let triggerType = jQuery('#trigger_type').val();
            let triggers, triggerNames;
            if(triggerType === 'severity'){
                triggers = jQuery('#severity_levels').select2('data');
                triggerNames = triggers.map(function(elem){return elem.text;}).join("/");
            }
            if(triggerType === 'sensor'){
                triggers = jQuery('#selected_sensors').select2('data');
                triggerNames = triggers.map(function(elem){return elem.text;}).join("/");
                let sensorCount = triggers.length;
                if(sensorCount > 3){
                    triggerNames = triggers.slice(0,3).map(function(elem){return elem.text;}).join("/");
                    triggerNames = triggerNames + " (+" + (sensorCount-3) + ")";
                }
            }
            if(triggerType !== 'severity' && triggerType !== 'sensor'){
                triggerNames = '';
            }
            let currDate = new Date()
            triggerNames = "[" + currDate.toISOString().split('T')[0] + "] " + triggerNames;
            return triggerNames;
        }

        function updateSummaryPage(){
            let triggerType = jQuery('#trigger_type').val();
            let triggers, triggerNames, triggerCount;

            jQuery('#summary-trigger-type').show();
            jQuery('#summary-trigger-type-label').show();
            if(triggerType === 'severity'){
                jQuery('#summary-trigger-type').html('<?php echo esc_js(__('Severity levels', 'wp-admin-audit')); ?>');
                triggers = jQuery('#severity_levels').select2('data');
                triggerCount = triggers.length;
                triggerNames = triggers.map(function(elem){return elem.text;}).join(", ");
            }
            if(triggerType === 'sensor'){
                jQuery('#summary-trigger-type').html('<?php echo esc_js(__('Sensor / event types', 'wp-admin-audit')); ?>');
                triggers = jQuery('#selected_sensors').select2('data');
                triggerCount = triggers.length;
                triggerNames = triggers.map(function(elem){return elem.text;}).join(", ");
            }

            if(triggerType !== 'severity' && triggerType !== 'sensor'){
                jQuery('#summary-trigger-type').hide();
                jQuery('#summary-trigger-type-label').hide();
                triggers = jQuery('#severity_levels').select2('data');
                triggerCount = triggers.length;
                triggerNames = '<strong><?php echo esc_js(__('Severity levels', 'wp-admin-audit')); ?>:</strong> ' + triggers.map(function(elem){return elem.text;}).join(", ");
                triggers = jQuery('#selected_sensors').select2('data');
                triggerCount = triggerCount + triggers.length;
                triggerNames += '<br/><strong><?php echo esc_js(__('Sensor / event types', 'wp-admin-audit')); ?>:</strong> ' + triggers.map(function(elem){return elem.text;}).join(", ");
            }

            let emailWpUserRoles = jQuery('#email_wp_user_role').select2('data');
            let emailWpUserRoleNames =  emailWpUserRoles.map(function(elem){return elem.text;}).join(", ");
            let recipientsInWpUserRoles = parseInt(jQuery('#email_wp_user_role_user_cr').html());

            let emailWpUsers = jQuery('#email_wp_user').select2('data');
            let emailWpUserNames =  emailWpUsers.map(function(elem){return elem.text;}).join(", ");

            let additionalEmailStr = jQuery("#email_additional").val().trim();
            let nrAdditionalEmail = 0;
            if(additionalEmailStr.length > 0) {
                nrAdditionalEmail = additionalEmailStr.split(',').length;
            }
            additionalEmailStr = additionalEmailStr.replaceAll(',', ', '); // make result visual a little nicer

            let totalIntegrationsStr = '';
            if(jQuery('#integration_logsnag').is(':checked')){
                totalIntegrationsStr += 'Logsnag';
            }

            let totalRecipients = recipientsInWpUserRoles + emailWpUsers.length + nrAdditionalEmail;

            jQuery('#summary-total-trigger-cr').val(triggerCount);
            jQuery('#summary-triggers').html(triggerNames);
            jQuery('#summary-email-wp-user-role').text(emailWpUserRoleNames);
            jQuery('#summary-email-wp-user-role-user-cr').text(recipientsInWpUserRoles);
            jQuery('#summary-email-wp-user').text(emailWpUserNames);
            jQuery('#summary-email-wp-user-cr').text(emailWpUsers.length);
            jQuery('#summary-email-additional').text(additionalEmailStr);
            jQuery('#summary-email-additional-cr').text(nrAdditionalEmail);
            jQuery('#summary-integrations').text(totalIntegrationsStr);
            jQuery('#summary-total-recipients-cr').text(totalRecipients);
        }

    </script>
    <?php
    }


    function userSearchAjaxResponse(){
        WADA_Log::debug('userSearchAjaxResponse');
        WADA_Log::debug('userSearchAjaxResponse GET: '.print_r($_GET, true));
        check_ajax_referer('wada_user_search');

        $res = true;
        $users = array();

        $searchTerm = array_key_exists('s', $_GET) ? sanitize_text_field($_GET['s']) : null;
        if($searchTerm) {

            $exclude = array_key_exists('exclude', $_GET) ? array_map('intval', $_GET['exclude']) : array();
            $exclude = array_filter($exclude, function($value) { return !is_null($value) && $value !== '' && $value > 0; });
            WADA_Log::debug('userSearchAjaxResponse exclude: '.print_r($exclude, true));

            // search with LIKE *searchterm* wildcards (to search as "contains")
            if($searchTerm[0] !== '*'){
                $searchTerm = '*'.$searchTerm;
            }
            if($searchTerm[strlen($searchTerm)-1] !== '*'){
                $searchTerm = $searchTerm.'*';
            }
            $user_query = new WP_User_Query(array(
                    'search' => $searchTerm,
                    'search_columns' => array( 'user_login', 'user_email', 'user_nicename', 'user_url' ),
                    'number' => 50 // limit to 50 results
            ));

            $userRes = $user_query->get_results();
            WADA_Log::debug('userSearchAjaxResponse userRes: '.print_r($userRes, true));
            foreach($userRes AS $user){

                if(!in_array($user->ID, $exclude)) {
                    $userObj = new stdClass();
                    $userObj->ID = $user->ID;
                    $userObj->user_login = $user->user_login;
                    $userObj->user_nicename = $user->user_nicename;
                    $userObj->user_email = $user->user_email;
                    $userObj->display_name = $user->display_name;
                    $userObj->select_option = '#' . $user->ID . ' ' . $user->display_name . ' (' . $user->user_login . ') <' . $user->user_email . '>';
                    $users[] = $userObj;
                }
            }
        }
        WADA_Log::debug('userSearchAjaxResponse users: '.print_r($users, true));

        $response = array('success' => $res, 'users' => $users);
        die( json_encode( $response ) );
    }

}