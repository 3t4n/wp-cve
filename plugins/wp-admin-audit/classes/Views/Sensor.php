<?php
/**
 * @copyright (C) 2021 - 2024 Holger Brandt IT Solutions
 * @license GPL2
*/

if(!defined('ABSPATH')){
    exit;
}

class WADA_View_Sensor extends WADA_View_BaseForm
{
    const VIEW_IDENTIFIER = 'wada-event-sensor';
    public $item = null;

    public function __construct($id = null){
        $this->viewHeadline = __('Sensor details', 'wp-admin-audit');
        $this->parentHeadlineLink = admin_url('admin.php?page=wp-admin-audit-settings&tab=tab-sensors');
        $this->parentHeadline =  __('Sensors', 'wp-admin-audit');
        if($id){
            $model = new WADA_Model_Sensor($id);
            $this->item = $model->_data;
            $this->viewHeadline = __('Sensor details', 'wp-admin-audit').' - '. esc_html('#'.absint($this->item->id) . ' '.$this->item->name);
        }
    }

    protected function saveFormSubmission(){
        WADA_Log::debug('saveFormSubmission: '.print_r($_POST, true));
        WADA_Log::debug('saveFormSubmission: '.print_r($this, true));
        $this->item->active = isset($_POST['active']) ? absint($_POST['active']) : 0;
        $this->item->severity = isset($_POST['severity']) ? absint($_POST['severity']) : WADA_Model_Sensor::SEVERITY_MEDIUM;
        $model = new WADA_Model_Sensor();
        WADA_Log::debug('saveFormSubmission: '.print_r($this->item, true));
        $res = $model->store($this->item);
        if($res !== false){
            $this->enqueueMessage(__('Sensor saved', 'wp-admin-audit'), 'success');
        }else{
            WADA_Log::error('Sensors->saveFormSubmission item: '.print_r($this->item, true));
            WADA_Log::error('Sensors->saveFormSubmission model: '.print_r($model, true));
            $this->enqueueMessage(__('Something went wrong', 'wp-admin-audit').': '.$model->_last_error, 'error');
        }

        $settingIds = WADA_Layout_SensorSettingsBase::getSensorSettingsLayout($this->item)->getSettingIds(); // TODO maybe rename to getSensorOption or getSensorOptionIds
        if(is_array($settingIds) && count($settingIds)){
           // TODO new approach to save sensor settings (use sensor options table!)
        }

    }

    protected function handleFormSubmissions(){
        if(isset($_POST['submit'])){
            check_admin_referer(self::VIEW_IDENTIFIER);
            $this->saveFormSubmission();
        }
    }

    protected function displayForm(){
        $eventCategories = WADA_Model_Sensor::getEventCategories();
        $eventCat = array_key_exists($this->item->event_category, $eventCategories) ? $eventCategories[$this->item->event_category] : $this->item->event_category;
        $extensionText = $sensorActiveHint = '';
        $isExtension = false;
        $canSensorBeActivated = true;
        if($this->item->extension_id){
            $isExtension = true;
            $extensionText = '<p>'.esc_html(sprintf(__('This sensor is part of the %s extension.', 'wp-admin-audit'), $this->item->extension_name)).'</p>';
            if(!$this->item->extension_active){
                $canSensorBeActivated = false;
                $sensorActiveHint = ' <span class="wada-error">'.sprintf(__('The extension "%s" is no longer active, therefore the sensor cannot be activated', 'wp-admin-audit'), $this->item->extension_name).'</span>';

            }
        }

        WADA_Log::debug('View/Sensor->displayForm item: '.print_r($this->item, true));
    ?>
        <div class="wrap">
            <?php $this->printHeadersAndBreadcrumb(); ?>
            <h4><?php echo esc_html($eventCat . ' / ' . $this->item->event_group_name); ?></h4>
            <p><?php echo esc_html($this->item->description); ?></p>
            <?php echo $extensionText; ?>
            <form id="<?php echo self::VIEW_IDENTIFIER; ?>" method="post">
                <?php wp_nonce_field(self::VIEW_IDENTIFIER); ?>
                <input type="hidden" name="page" value="<?php echo esc_attr($this->getCurrentPage()); ?>" />
                <table class="form-table">
                    <tbody>
                    <?php WADA_HtmlUtils::boolToggleField('active', __('Active', 'wp-admin-audit'), $this->item->active, array_merge(array('render_as_table_row' => true, 'input_class' => 'sensor-status-toggle', 'html_suffix' => $sensorActiveHint), $canSensorBeActivated ? array() : array('disabled' => true))); ?>
                    <?php WADA_HtmlUtils::selectField('severity', __('Severity', 'wp-admin-audit'), $this->item->severity, WADA_Model_Sensor::getSeverityLevels(), array(), false, array('render_as_table_row' => true)); ?>
                    <?php WADA_Layout_SensorSettingsBase::getSensorSettingsLayout($this->item)->display(); ?>
                    </tbody>
                </table>
                <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="<?php esc_attr_e('Save changes', 'wp-admin-audit'); ?>"></p>
            </form>
        </div>
    <?php
    }
}