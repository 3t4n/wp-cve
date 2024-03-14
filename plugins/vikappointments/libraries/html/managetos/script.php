<?php
/** 
 * @package     VikAppointments - Libraries
 * @subpackage  html.managetos
 * @author      E4J s.r.l.
 * @copyright   Copyright (C) 2021 E4J s.r.l. All Rights Reserved.
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link        https://vikwp.com
 */

// No direct access
defined('ABSPATH') or die('No script kiddies please!');

JHtml::fetch('vaphtml.assets.toast', 'bottom-center');

$field = $displayData['field'];

$vik = VAPApplication::getInstance();

// render inspector to manage ToS fields
echo JHtml::fetch(
    'vaphtml.inspector.render',
    'tos-inspector-' . $field['id'],
    array(
        'title'       => JText::translate('VAPMAINTITLEEDITCUSTOMF'),
        'closeButton' => true,
        'keyboard'    => false,
        'footer'      => '<button type="button" class="btn btn-success" id="tos-save-' . $field['id'] . '">' . JText::translate('JAPPLY') . '</button>',
        'width'       => 400,
    ),
    JLayoutHelper::render('html.managetos.modal', $displayData)
);

JText::script('VAPCONNECTIONLOSTERROR');
?>

<script>
    (function($) {
        'use strict';

        $(function() {
            // get ToS table row
            var tr = $('input[name="cid[]"][value="<?php echo (int) $field['id']; ?>"]').closest('tr');

            // get column that contains the field name
            var nameTD = tr.children().eq(2);

            // wrap name within a div
            nameTD.html('<div class="td-pull-left"> ' + nameTD.html() + ' </div>');

            // create edit button
            var editButton = $('<a href="javascript:void(0)" class="td-pull-right no-underline"><i class="fas fa-pen-square big"></i></a>');

            // register click event
            editButton.on('click', function() {
                // open inspector
                vapOpenInspector('tos-inspector-<?php echo (int) $field['id']; ?>');
            });

            // append edit button
            nameTD.append(editButton);

            // register save event
            $('#tos-save-<?php echo (int) $field['id']; ?>').on('click', function() {
                // get form containing the field value
                var form = $('form#tos-form-<?php echo (int) $field['id']; ?>');

                // make save request
                UIAjax.do(
                    // request end-point
                    'admin-ajax.php?action=vikappointments&task=customf.savetosajax',
                    // serialize form
                    form.serialize(),
                    // successful response
                    (data) => {
                        if (typeof data === 'string') {
                            data = JSON.parse(data);
                        }

                        // update name within table column
                        nameTD.find('.td-primary').html(data.name);

                        // auto-close inspector on successful save
                        vapCloseInspector('tos-inspector-<?php echo (int) $field['id']; ?>');
                    },
                    // failure
                    (error) => {
                        if (!error.responseText) {
                            // use default connection lost error
                            error.responseText = Joomla.JText._('VAPCONNECTIONLOSTERROR');
                        }

                        // raise error
                        ToastMessage.enqueue({
                            text: error.responseText,
                            status: 0,
                        });
                    }
                );
            });
        });
    })(jQuery);
</script>
