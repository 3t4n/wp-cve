<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
wp_enqueue_script('iris');
?>
<?php
$majesticsupport_js ="
jQuery(document).ready(function () {
        jQuery('select#overduetypeid').change(function(){
            changevalue();
        });
        changevalue();
        function changevalue()
        {
            var isselect = jQuery('select#overduetypeid').val();
            if(isselect == 1){
                jQuery('span.ticket_overdue_type_text').html(\"". esc_html(__('Days', 'majestic-support'))."\");
            }else{
                jQuery('span.ticket_overdue_type_text').html(\"". esc_html(__('Hours', 'majestic-support'))."\");
            }
        }

        jQuery.validate();
    });

";
wp_add_inline_script('majestic-support-cmain-js',$majesticsupport_js);
?>  

<?php
    $dayshours = array(
    (object) array('id' => '1', 'text' => esc_html(__('Days', 'majestic-support'))),
    (object) array('id' => '2', 'text' => esc_html(__('Hours', 'majestic-support')))
    );
?>
<div id="msadmin-wrapper">
    <div id="msadmin-leftmenu">
        <?php  MJTC_includer::MJTC_getClassesInclude('msadminsidemenu'); ?>
    </div>
    <div id="msadmin-data">
        <?php MJTC_includer::MJTC_getModel('majesticsupport')->getPageTitle('addpriority'); ?>
        <div id="msadmin-data-wrp">
            <form class="msadmin-form" method="post" action="<?php echo esc_url(wp_nonce_url(admin_url("?page=majesticsupport_priority&task=savepriority"),"save-priority")); ?>">
                <div class="mjtc-form-wrapper">
                    <div class="mjtc-form-title"><?php echo esc_html(__('Priority', 'majestic-support')); ?>&nbsp;<span style="color: red;" >*</span></div>
                    <div class="mjtc-form-value"><?php echo wp_kses(MJTC_formfield::MJTC_text('priority', isset(majesticsupport::$_data[0]->priority) ? majesticsupport::$_data[0]->priority : '', array('class' => 'inputbox mjtc-form-input-field', 'data-validation' => 'required')), MJTC_ALLOWED_TAGS) ?></div>
                </div>
                <div class="mjtc-form-wrapper">
                    <div class="mjtc-form-title"><?php echo esc_html(__('Color', 'majestic-support')); ?>&nbsp;<span style="color: red;" >*</span></div>
                    <div class="mjtc-form-value"><?php echo wp_kses(MJTC_formfield::MJTC_text('prioritycolor', isset(majesticsupport::$_data[0]->prioritycolour) ? majesticsupport::$_data[0]->prioritycolour : '', array('class' => 'inputbox mjtc-form-input-field', 'data-validation' => 'required', 'autocomplete' => 'off')), MJTC_ALLOWED_TAGS); ?></div>
                </div>
                <?php if(in_array('overdue', majesticsupport::$_active_addons)){ ?>
                    <div class="mjtc-form-wrapper">
                        <div class="mjtc-form-title"><?php echo esc_html(__('Ticket Overdue Interval Type', 'majestic-support')) ?></div>
                        <div class="mjtc-form-value"><?php echo wp_kses(MJTC_formfield::MJTC_select('overduetypeid', $dayshours , (isset(majesticsupport::$_data[0]->overduetypeid) ? majesticsupport::$_data[0]->overduetypeid : '' ), '',array('class' => 'inputbox mjtc-form-select-field')), MJTC_ALLOWED_TAGS)?></div>
                    </div>
                    <div class="mjtc-form-wrapper">
                        <div class="mjtc-form-title"><?php echo esc_html(__('Ticket Overdue', 'majestic-support')) ?>&nbsp;<span style="color: red;" >*</span></div>
                        <div class="mjtc-form-value"><?php echo wp_kses(MJTC_formfield::MJTC_text('overdueinterval', isset(majesticsupport::$_data[0]->overdueinterval) ? majesticsupport::$_data[0]->overdueinterval : '', array('class' => 'inputbox mjtc-form-input-field', 'data-validation' => 'required')), MJTC_ALLOWED_TAGS) ?><span class="ticket_overdue_type_text" ><?php echo isset(majesticsupport::$_data[0]->overduetypeid) ? esc_html(majesticsupport::$_data[0]->overduetypeid) : '' ?></span></div>
                    </div>
                <?php } ?>
                <div class="mjtc-form-wrapper">
                    <div class="mjtc-form-title"><?php echo esc_html(__('Public', 'majestic-support')); ?></div>
                    <div class="mjtc-form-value"><?php echo wp_kses(MJTC_formfield::MJTC_radiobutton('ispublic', array('1' => esc_html(__('Yes', 'majestic-support')), '0' => esc_html(__('No', 'majestic-support'))), isset(majesticsupport::$_data[0]->ispublic) ? majesticsupport::$_data[0]->ispublic : '1', array('class' => 'radiobutton')), MJTC_ALLOWED_TAGS); ?></div>
                </div>
                <div class="mjtc-form-wrapper">
                    <div class="mjtc-form-title"><?php echo esc_html(__('Default', 'majestic-support')); ?></div>
                    <div class="mjtc-form-value"><?php echo wp_kses(MJTC_formfield::MJTC_radiobutton('isdefault', array('1' => esc_html(__('Yes', 'majestic-support')), '0' => esc_html(__('No', 'majestic-support'))), isset(majesticsupport::$_data[0]->isdefault) &&  majesticsupport::$_data[0]->isdefault == 1 ? 1 : 0, array('class' => 'radiobutton')), MJTC_ALLOWED_TAGS); ?></div>
                </div>
                <div class="mjtc-form-wrapper">
                    <div class="mjtc-form-title"><?php echo esc_html(__('Status', 'majestic-support')); ?></div>
                    <div class="mjtc-form-value"><?php echo wp_kses(MJTC_formfield::MJTC_radiobutton('status', array('1' => esc_html(__('Enabled', 'majestic-support')), '0' => esc_html(__('Disabled', 'majestic-support'))), isset(majesticsupport::$_data[0]->status) ? majesticsupport::$_data[0]->status : '1', array('class' => 'radiobutton')), MJTC_ALLOWED_TAGS); ?></div>
                </div>
                <?php echo wp_kses(MJTC_formfield::MJTC_hidden('id', isset(majesticsupport::$_data[0]->id) ? majesticsupport::$_data[0]->id : '' ), MJTC_ALLOWED_TAGS); ?>
                <?php echo wp_kses(MJTC_formfield::MJTC_hidden('ordering', isset(majesticsupport::$_data[0]->ordering) ? majesticsupport::$_data[0]->ordering : '' ), MJTC_ALLOWED_TAGS); ?>
                <?php echo wp_kses(MJTC_formfield::MJTC_hidden('action', 'priority_savepriority'), MJTC_ALLOWED_TAGS); ?>
                <?php echo wp_kses(MJTC_formfield::MJTC_hidden('form_request', 'majesticsupport'), MJTC_ALLOWED_TAGS); ?>
                <?php echo wp_kses(MJTC_formfield::MJTC_hidden('uid', MJTC_includer::MJTC_getObjectClass('user')->MJTC_uid()), MJTC_ALLOWED_TAGS); ?>
                <div class="mjtc-form-button">
                    <?php echo wp_kses(MJTC_formfield::MJTC_submitbutton('save', esc_html(__('Save Priority', 'majestic-support')), array('class' => 'button mjtc-form-save')), MJTC_ALLOWED_TAGS); ?>
                </div>
            </form>
        </div>
        <?php
        $majesticsupport_js ="
            jQuery(document).ready(function () {
                jQuery('input#prioritycolor').iris({
                    color: jQuery('input#prioritycolor').val(),
                    onShow: function (colpkr) {
                        jQuery(colpkr).fadeIn(500);
                        return false;
                    },
                    onHide: function (colpkr) {
                        jQuery(colpkr).fadeOut(500);
                        return false;
                    },
                    onChange: function (hsb, hex, rgb) {
                        jQuery('input#prioritycolor').css('backgroundColor', '#' + hex).val('#' + hex);
                    }
                });
                jQuery(document).click(function (e) {
                    if (!jQuery(e.target).is('.colour-picker, .iris-picker, .iris-picker-inner')) {
                        jQuery('#prioritycolor').iris('hide');
                    }
                });
                jQuery('#prioritycolor').click(function (event) {
                    jQuery('#prioritycolor').iris('hide');
                    jQuery(this).iris('show');
                    return false;
                });
            });

        ";
        wp_add_inline_script('majestic-support-cmain-js',$majesticsupport_js);
        ?>  
    </div>
</div>
