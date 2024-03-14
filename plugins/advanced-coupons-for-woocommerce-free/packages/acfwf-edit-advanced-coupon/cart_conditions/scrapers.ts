declare var jQuery: any;
declare var acfw_edit_coupon: any;

const $: any = jQuery;
const { cart_condition_premium_notice, cart_condition_fields } = acfw_edit_coupon;

/**
 * Scrape condition group data.
 *
 * @since 1.0.0
 */
export function scrape_condition_group_data(condition_group: Element): any {
  const condition_fields: NodeList = condition_group.querySelectorAll('.condition-set,.condition-field');
  let fields: any[] = [];

  if (condition_group.classList.contains('condition-group-logic'))
    return {
      type: 'group_logic',
      value: condition_group.querySelector('select')?.value as string,
    };

  condition_fields.forEach((condition_field) =>
    fields.push(scrape_condition_field_data(condition_field as HTMLElement))
  );

  return fields.length ? { type: 'group', fields: fields } : null;
}

/**
 * Scrape condition field data.
 *
 * @since 1.0.0
 */
export function scrape_condition_field_data(condition_field: HTMLElement): any {
  const { type } = condition_field.dataset;
  let field_data: any = { type: type, data: null };
  let temp: any, temp2: any, temp3: any, temp4: any;

  switch (type) {
    case 'logic':
      temp = condition_field.querySelector('select.condition-logic');
      field_data.data = temp.value;
      break;

    case 'product-category':
      temp = condition_field.querySelector('.condition-value');
      temp2 = condition_field.querySelector('.condition-select');
      temp3 = condition_field.querySelector('.condition-quantity');
      field_data.data = {
        condition: $(temp2).val(),
        value: $(temp).val(),
        quantity: $(temp3).val(),
      };
      break;

    case 'customer-logged-in-status':
    case 'customer-user-role':
    case 'disallowed-customer-user-role':
      temp = condition_field.querySelector('.condition-value');
      field_data.data = $(temp).val();
      break;

    case 'cart-quantity':
      temp = condition_field.querySelector('.condition-select');
      temp2 = condition_field.querySelector('.condition-value');
      field_data.data = {
        condition: $(temp).val(),
        value: parseInt($(temp2).val()),
      };
      break;

    case 'cart-subtotal':
      const includeTax = <HTMLInputElement>condition_field.querySelector('input.condition-include-tax');
      temp = condition_field.querySelector('.condition-select');
      temp2 = condition_field.querySelector('.condition-value');
      field_data.data = {
        condition: $(temp).val(),
        value: $(temp2).val(),
        include_tax: includeTax.checked ? 'yes' : 'no',
      };
      break;

    default:
      const field_key = type?.replace(/-/g, '_') as string;
      if (cart_condition_fields[field_key]) {
        const { scraper_callback, title } = cart_condition_fields[field_key];
        if (scraper_callback) field_data.data = scraper_callback(condition_field);

        // Handle Premium Conditions.
        if (cart_condition_premium_notice) {
          let { label_premium } = cart_condition_premium_notice;
          if (title && title.includes(label_premium)) {
            const { premium } = condition_field.dataset;
            field_data.data = premium ? JSON.parse(premium) : '';
          }
        }
      }
      break;
  }

  return field_data;
}
