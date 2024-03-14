declare var jQuery: any;
var $: any = jQuery;

/**
 * Toggle field events script.
 * Used by Roles Restriction and other similar modules.
 *
 * @since 1.0.0
 */
export default function toggle_fields_events() {
  const $module_block: any = $('.toggle-enable-fields');

  $module_block.on('change', '.toggle-trigger-field', toggle_disable_enable_fields);
  $module_block.find('.toggle-trigger-field').trigger('change');
}

/**
 * Toggle disable/enable fields.
 *
 * @since 1.0.0
 */
function toggle_disable_enable_fields() {
  // @ts-ignore
  const $button: any = $(this),
    $module_block = $button.closest('.toggle-enable-fields'),
    $fields = $module_block.find('select,textarea,input:not(.toggle-trigger-field)'),
    toggle = !$button.prop('checked');

  $fields.prop('disabled', toggle);

  if (toggle) $fields.closest('.form-field').addClass('disabled');
  else $fields.closest('.form-field').removeClass('disabled');
}
