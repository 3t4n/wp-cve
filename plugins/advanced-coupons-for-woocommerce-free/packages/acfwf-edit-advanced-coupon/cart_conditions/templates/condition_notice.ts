/**
 * Condition notice template.
 *
 * @since 4.5.4
 * @page Admin Edit Coupon
 * @section Cart Conditions
 * */

// Localize the script with new data
declare var jQuery: any;
declare var acfw_edit_coupon: any;

// Interfaces Conditions.
interface IConditions {
  key: any;
}

// Interfaces Data.
interface IData {
  type: string;
  fields: [
    {
      type: string;
    }
  ];
}

/**
 * Register Trigger - Remove all premium button
 *
 * @since 4.5.4
 * */
function register_trigger_remove_all_premium() {
  (<any>window).acfw_edit_coupon_triggers = (<any>window).acfw_edit_coupon_triggers || {};
  (<any>window).acfw_edit_coupon_triggers.remove_all_premium_condition = () => {
    // Remove all premium conditions.
    jQuery(`.condition-field-premium .remove-condition-field`).each(function () {
      // @ts-ignore
      jQuery(this).trigger('click');
    });

    // Remove notice.
    jQuery(`.condition-notice`).remove();

    // Remove empty condition group.
    jQuery(`.empty-conditions`).each(function () {
      // @ts-ignore
      jQuery(this).closest(`.condition-group`).find(`.remove-condition-group`).trigger('click');
    });
  };
}

/**
 * Extract premium cart conditions from cart condition data.
 *
 * @since 4.5.4
 * @param conditions IConditions - Cart conditions object.
 * @param data IData - Cart condition data that is stored in database.
 * */
const extract_premium_cart_conditions = (conditions: IConditions, data: IData[]): {} => {
  const { cart_condition_premium_notice } = acfw_edit_coupon;
  if (!cart_condition_premium_notice) return {};
  let premium: any = {};
  for (const [index, row] of Object.entries(data)) {
    if (row.type === 'group_logic') continue;
    for (let [index, field] of Object.entries(row.fields)) {
      // Find condition object by key.
      let condition = Object.entries(conditions).find(([key, condition]) => condition.key === field.type);

      // Check if the condition exists & premium.
      if (condition) {
        let [key, value] = condition;
        let { label_premium } = cart_condition_premium_notice;
        if (value.title.includes(label_premium)) {
          premium[key] = value;
        }
      }
    }
  }
  return premium;
};

/**
 * Generate markup for premium cart condition notice.
 *
 * @since 4.5.4
 * */
const premium_cart_condition_notice = (conditions: IConditions, premium_cart_conditions: any): string => {
  const { cart_condition_premium_notice } = acfw_edit_coupon;

  let premium: any = [];
  let markup = '';
  markup = `<div class="condition-notice">`;

  // Generate Remove All Button.
  markup += `<a id="remove-all-premium-condition" href="javascript:void(0);" onclick="acfw_edit_coupon_triggers.remove_all_premium_condition()"><i class="dashicons dashicons-trash"></i></a>`;

  // Generate logo markup.
  markup += `<img src="${cart_condition_premium_notice.logo_img}">`;

  // Generate list of premium cart conditions.
  let { label_premium } = cart_condition_premium_notice;
  Object.keys(premium_cart_conditions).map((key) => {
    let value = premium_cart_conditions[key];
    premium.push(`<b>${value.title.replace(` (${label_premium})`, '')}</b>`);
  });

  // Generate notice markup.
  cart_condition_premium_notice.label.map((label: string) => {
    label = premium.length && label.includes('{{premium}}') ? label.replace('{{premium}}', premium.join(', ')) : label;
    markup += `<p>${label}</p>`;
  });

  // Generate button markup.
  markup += `<div class="action-wrap">`;
  cart_condition_premium_notice.actions.map((action: any) => {
    let external = !action.url.includes('action=') ? "target='_blank'" : '';
    markup += `<a href="${action.url}" ${external} class="action-button primary">${action.label}</a>`;
  });
  markup += `</div>`;

  markup += `</div>`;

  return markup;
};

/**
 * Return condition notice template markup.
 *
 * @since 4.5.4
 * @param data any[] - Cart condition data that is stored in database.
 */
export default function condition_notice_template(data: []): string {
  const { cart_condition_fields } = acfw_edit_coupon;
  let markup = '';

  // If there's cart condition data, check if there's premium cart condition.
  if (data.length) {
    // Check if there's any premium cart condition.
    let premium_cart_conditions = extract_premium_cart_conditions(cart_condition_fields, data);

    // If there's premium cart condition, show notice.
    if (Object.keys(premium_cart_conditions).length) {
      markup += premium_cart_condition_notice(cart_condition_fields, premium_cart_conditions);

      // Register trigger to remove all premium button.
      register_trigger_remove_all_premium();
    }
  }

  return markup;
}
