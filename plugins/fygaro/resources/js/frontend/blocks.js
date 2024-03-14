
import { sprintf, __ } from '@wordpress/i18n';
import { registerPaymentMethod } from '@woocommerce/blocks-registry';
import { decodeEntities } from '@wordpress/html-entities';
import { getSetting } from '@woocommerce/settings';

const settings = getSetting( 'fygaro_data', {} );

const defaultLabel = __(
	'Credit/Debit Card',
	'woo-gutenberg-products-block'
);

const label = decodeEntities( settings.title ) || defaultLabel;
const fygaroIcon = settings.fygaroIcon || {};
const cardIcons = settings.cardIcons || [];

/**
 * Content component
 */
const Content = () => {
	return decodeEntities( settings.description || '' );
};

/**
 * Label component
 *
 * @param {*} props Props from payment API.
 */
const Label = () => {
	let renderCardIcons;
	if (cardIcons.length) {
		const fygaroIconContainerStyle = {
			display: "inline-block",
			position: "relative",
			marginLeft: "17px",
			padding: "4px 8px",
			height: "22px",
			backgroundColor: "#fff",
			border: "solid thin rgba(0,0,0,.15)",
			borderRadius: "4px",
			zIndex: "100"
		};

		const cardIconContainerStyle = {
			display: "flex",
			marginTop: "-17px",
			padding: "14px 14px 12px",
			border: "solid thin rgba(0,0,0,.15)",
			borderRadius: "16px",
			flexFlow: "row wrap",
			justifyContent: "flex-start",
			alignItems: "center",
			rowGap: "8px",
			columnGap: "4px"
		};

		renderCardIcons = (
			<div style={{marginTop: ".6rem"}}>
				<div style={fygaroIconContainerStyle}>
					<img
						src={fygaroIcon.src}
						alt={fygaroIcon.alt}
						style={{
							verticalAlign: "top"
						}}
						ref={(node) => {
						    if (node) {
						        node.style.setProperty("height", "100%", "important");
						    }
						}}
					/>
				</div>
				<div style={cardIconContainerStyle}>

					{cardIcons.map((cardIcon, i) => {
						let height;
						let iconStyle;
						if (cardIcon.isBankLogo) {
							height = "16px";
						} else {
							height = "24px";
							iconStyle = {
								border: "solid thin rgba(0,0,0,.1)",
								borderRadius: "4px"
							};
						}

						return (
							<img
								key={cardIcon.id}
								src={cardIcon.src}
								alt={cardIcon.alt}
								style={iconStyle}
								ref={(node) => {
								    if (node) {
								        node.style.setProperty("height", height, "important");
								    }
								}}
							/>
						);
					})}
				</div>
			</div>
		);
	}

	return (
		<div className="wc-block-components-payment-method-label">
			{label}
			{renderCardIcons}
		</div>
	);
};

/**
 * Fygaro payment method config object.
 */
const Fygaro = {
	name: "fygaro",
	label: <Label />,
	content: <Content />,
	edit: <Content />,
	canMakePayment: () => true,
	ariaLabel: label,
	supports: {
		features: settings.supports,
	},
};

registerPaymentMethod( Fygaro );
