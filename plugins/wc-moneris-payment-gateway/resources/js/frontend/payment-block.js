/*import { registerPaymentMethod } from "@woocommerce/blocks-registry";
import { decodeEntities } from "@wordpress/html-entities";
import { getSetting } from "@woocommerce/settings";
import { __ } from "@wordpress/i18n";

const settings = getSetting("moneris_data", {});

const defaultLabel = __("Moneris", "wpheka-gateway-moneris");

const label = decodeEntities(settings.title) || defaultLabel;

const Content = () => {
  return decodeEntities(settings.description || "");
};

const Label = (props) => {
  const { PaymentMethodLabel } = props.components;
  return <PaymentMethodLabel text={label} />;
};

const Wpheka_Moneris_Gateway = {
  name: "moneris",
  label: <Label />,
  content: <Content />,
  edit: <Content />,
  canMakePayment: () => true,
  ariaLabel: label,
  supports: {
    features: settings.supports,
  },
};

registerPaymentMethod(Wpheka_Moneris_Gateway);*/

import { registerPaymentMethod } from '@woocommerce/blocks-registry';
import Wpheka_Moneris_Gateway from './MonerisDirect';


if (Object.keys(Wpheka_Moneris_Gateway).length > 0) {
  registerPaymentMethod(Wpheka_Moneris_Gateway);
}
