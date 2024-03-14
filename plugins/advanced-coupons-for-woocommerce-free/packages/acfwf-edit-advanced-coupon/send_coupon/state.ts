import { validateEmail } from '../helper';
import { reRenderSection } from './templates/modalMarkup';

declare var jQuery: any;
declare var lodash: any;

const $ = jQuery;
const formState = new Map();

/**
 * Initialize the send coupon form state.
 *
 * @since 4.5.3
 */
export function initFormState(section = 'send_coupon_to') {
  formState.set('section', section);
  formState.set('send_to', 'user');
  formState.set('user_id', '0');
  formState.set('name', '');
  formState.set('email', '');
  formState.set('create_account', '');
}

/**
 * Update state from input.
 * Triggered when an form input value is changed.
 *
 * @since 4.5.3
 */
export function updateStateFromInput() {
  // @ts-ignore
  const $input = $(this);
  const key = $input.data('key');
  let value = $input.val() ? $input.val().toString() : '';

  switch (key) {
    case 'user':
      const $selected = $input.find('option:selected');
      formState.set('user_id', value);
      formState.set('name', lodash.first($selected.text().split(' (')));
      formState.set('email', lodash.last($selected.text().split('&ndash; ')).replace(')', ''));
      break;

    case 'email':
      $input.removeClass('error');

      if (validateEmail(value) || value === '') {
        formState.set(key, value);
        formState.set('user_id', '0'); // clear the user_id value.
      } else {
        formState.set(key, ''); // clear email value.
        $input.addClass('error');
      }
      break;

    case 'create_account':
      value = $input.is(':checked') ? value : '';
      formState.set(key, value);
      break;

    default:
      formState.set(key, value);
  }

  reRenderSection('confirm_and_send', formState.get('section') === 'confirm_and_send');
}

export default formState;
