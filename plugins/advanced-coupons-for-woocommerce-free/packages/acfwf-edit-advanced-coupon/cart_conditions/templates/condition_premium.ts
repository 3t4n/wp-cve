declare var acfw_edit_coupon: any;
const { cart_condition_fields } = acfw_edit_coupon;

/**
 * Return condition premium template markup.
 *
 * @since 4.5.4
 *
 * @param data
 * @param type
 */
export default function condition_premium_template(
  data: any,
  type: string
): string {
  const field_key: string = type.replace(/-/g, "_");
  const { title } = cart_condition_fields[field_key];
  const { cart_condition_field_premium_notice } = acfw_edit_coupon;

  return `
  <div class="${type} condition-field condition-field-premium" data-type="${type}" data-premium='${JSON.stringify(
    data
  )}'>
      <a class="remove-condition-field" href="javascript:void(0);"><i class="dashicons dashicons-trash"></i></a>
      <h3 class="condition-field-title">${title}</h3>
      <div class="field-control">
          ${cart_condition_field_premium_notice.label}
      </div>
  </div>
  `;
}
