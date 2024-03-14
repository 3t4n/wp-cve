
import { registerPaymentMethod, registerExpressPaymentMethod } from '@woocommerce/blocks-registry';
import { decodeEntities } from '@wordpress/html-entities';
import { getSetting } from '@woocommerce/settings';
import ApplePay from './applePay.jsx';
import { useFetch } from './Helpers.js';


let cache = {};
const settings = getSetting('hyperpay_blocks_data', {});

console.log("settings" , settings)

/**
 * Label component
 *
 * @param {*} props Props from payment API.
 */
const Label = (props) => {

	const { PaymentMethodLabel, PaymentMethodIcons } = props.components;


	return <div style={{ display: 'flex', justifyContent: 'space-between', width: "100%", paddingRight: 5, paddingLeft: 5 }}>
		<PaymentMethodLabel text={decodeEntities(props.setting.title)} />
		<PaymentMethodIcons icons={props.setting.icon.map((brand, index) => ({ id: props.setting.title + index, src: brand }))} />
	</div>
};

const Content = (props) => {
	const { PaymentMethodLabel } = props.components;

	return <>
		{props.setting.description &&
			<div style={{ display: 'flex', justifyContent: 'space-between', width: "100%", paddingRight: 5, paddingLeft: 5 }}>
				<PaymentMethodLabel text={props.setting.description} />
			</div>
		}
	</>
};



const memoizedCanMakePayment = async (data, setting) => {
	let fetching = false
	let country = data.billingAddress.country
	let currency_code = data.cartTotals.currency_code
	let total_price = data.cartTotals.total_price

	let key = `${setting.name}_${country}_${currency_code}_${total_price}`

	if (key in cache) {
		return cache[key];
	} else if (fetching) {
		return
	} else {
		fetching = true
		let res = await useFetch("hyperpay_can_make_payment&payment_method=" + setting.name , settings.site_url)
		.post("",data)
		
		setting.description = res.data.description
		cache[key] = res.data.canMakePayment;
		return cache[key]

	}
}


settings.blocks.forEach(setting => {
	let gateWay = {
		name: setting.name,
		label: <Label setting={setting} />,
		content: <Content setting={setting} />,
		edit: <div>{decodeEntities(setting.description)}</div>,
		canMakePayment: async (data) => {
			if (setting.isDynamicCheck) {
				return memoizedCanMakePayment(data, setting)
			}

			return true
		},
		ariaLabel: decodeEntities(setting.title),
		supports: {
			features: setting.supports,
		},
	};

	registerPaymentMethod(gateWay);
})

settings.express.forEach(setting => {
	const gateWay = {
		name: setting.name,
		content: <ApplePay setting={setting} />,
		edit: <img style={{ maxHeight:50,maxWidth:238 }} src={setting.action_button}/>,
		canMakePayment: () => !!window.ApplePaySession,
		ariaLabel: decodeEntities(setting.title),
		paymentMethodId: setting.name,
		supports: {
			features: settings.supports,
		},
	};

	registerExpressPaymentMethod(gateWay);
})

