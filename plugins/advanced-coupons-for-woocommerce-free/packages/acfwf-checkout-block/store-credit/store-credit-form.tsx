// Import Library.
import config from '../config.json';
import { getSetting } from '../../shared/library/BlockIntegration';
import { store } from '../../shared/library/StoreAPI';

// Global variables.
declare var acfwfObj: any;
declare var wp: any;
declare var jQuery: any;

/**
 * Redeem store credit.
 * - This function is called when the user clicks on the redeem button.
 * - The props are passed from the StoreCreditForm component because React State needs to run under JSX Element.
 *
 * @since 4.5.9
 *
 * @param {object} props Component props.
 * - @param {function} props.setButtonDisabled Set button disabled.
 * - @param {string} props.amount Amount to redeem.
 * - @param {function} props.setAmount Set amount.
 * - @param {string} props.redeem_nonce Redeem nonce.
 * */
const SubmitStoreCredit = (props: any) => {
  const { adminAjaxUrl } = acfwfObj;
  const { dummyUpdateCart } = acfwfObj.wc;
  const { setButtonDisabled, amount, setAmount, redeem_nonce } = props;
  const { dispatch } = wp.data;
  setButtonDisabled(true);

  // Redeem store credit.
  jQuery.post(
    adminAjaxUrl,
    {
      action: 'acfwf_redeem_store_credits',
      amount,
      wpnonce: redeem_nonce,
      is_cart_checkout_block: true,
    },
    function (response: any) {
      dummyUpdateCart();
      setAmount('');

      // Show store credit notice.
      dispatch('core/notices').createNotice(response.status, response.message, {
        type: 'snackbar',
        context: 'wc/checkout',
      });

      // Debounce event.
      setTimeout(() => {
        setButtonDisabled(false);
      }, 200);
    }
  );
};

/**
 * Store Credit Form component.
 *
 * @since 4.5.9
 *
 * @return {JSX.Element} Accordion component.
 * */
export default function () {
  const { Accordion } = acfwfObj.components;
  const { useState } = wp.element;

  // Get the settings from the integration interface.
  const { caret_img_src, store_credits } = getSetting(config.integration);
  const { button_text, redeem_nonce, hide_store_credits_on_zero_balance } = store_credits;
  const { balance_text: balance_text_detail, instructions, placeholder } = store_credits.labels;
  const { toggle_text } = store_credits.labels;

  // Reactive states.
  const [amount, setAmount] = useState('');
  const [ButtonDisabled, setButtonDisabled] = useState(false);

  // Get data from Store API.
  const { acfwf_block } = store.getCartData().extensions;
  if (!acfwf_block || !acfwf_block.store_credits) return null; // Wait until the data is loaded, this is due to Store API is asynchronous.
  const { balance, balance_text } = acfwf_block.store_credits;

  // Return null if customer has no balance and hide_store_credits_on_zero_balance is set to yes.
  if (!balance && 'yes' === hide_store_credits_on_zero_balance) {
    return null;
  }

  // Return the component.
  return (
    <div className="acfwf-components acfw-checkout-ui-block">
      <Accordion title={toggle_text} caret_img_src={caret_img_src}>
        <div>
          <p className="acfw-store-credit-user-balance">
            <div
              dangerouslySetInnerHTML={{
                __html: balance_text_detail.replace('%s', `<strong>${balance_text}</strong>`),
              }}
            />
          </p>

          <p className="acfw-store-credit-instructions">{instructions}</p>

          <div
            id="acfw_redeem_store_credit"
            className="acfw-redeem-store-credit-form-field acfw-checkout-form-button-field "
          >
            <p className="form-row form-row-first acfw-form-control-wrapper acfw-col-left-half wfacp-input-form">
              <label htmlFor="coupon_code"></label>
              <input
                type="text"
                className="input-text wc_input_price "
                value={amount}
                placeholder={placeholder}
                onChange={(e) => {
                  setAmount(e.target.value);
                }}
              />
            </p>
            <p className="form-row form-row-last acfw-col-left-half acfw_coupon_btn_wrap">
              <label className="acfw-form-control-label">&nbsp;</label>
              <button
                type="button"
                className="button alt"
                onClick={() =>
                  SubmitStoreCredit({
                    setButtonDisabled,
                    amount,
                    setAmount,
                    redeem_nonce,
                  })
                }
                disabled={ButtonDisabled}
              >
                {button_text}
              </button>
            </p>
          </div>
        </div>
      </Accordion>
    </div>
  );
}
