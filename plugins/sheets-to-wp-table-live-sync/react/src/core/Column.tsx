import './_column.scss';

type PropTypes = {
	firstXs?: boolean;
	lastXs?: boolean;
	firstSm?: boolean;
	lastSm?: boolean;
	firstMd?: boolean;
	lastMd?: boolean;
	firstLg?: boolean;
	lastLg?: boolean;
	firstXl?: boolean;
	lastXl?: boolean;
	xsOffset?: string;
	smOffset?: string;
	mdOffset?: string;
	lgOffset?: string;
	xlOffset?: string;
	xs?: string;
	sm?: string;
	md?: string;
	lg?: string;
	xl?: string;
	textXs?: string;
	textSm?: string;
	textMd?: string;
	textLg?: string;
	textXl?: string;
	alignSelf?: string;
	customClass?: string;
	children: React.ReactNode;
};

const Column = ( {
	firstXs,
	lastXs,
	firstSm,
	lastSm,
	firstMd,
	lastMd,
	firstLg,
	lastLg,
	firstXl,
	lastXl,
	xsOffset,
	smOffset,
	mdOffset,
	lgOffset,
	xlOffset,
	xs,
	sm,
	md,
	lg,
	xl,
	textXs,
	textSm,
	textMd,
	textLg,
	textXl,
	alignSelf,
	customClass,
	children,
}: PropTypes ) => {
	const classes = () => {
		let c = 'swptls-col';
		c += firstXs ? ' first-xs' : lastXs ? ' last-xs' : '';
		c += firstSm ? ' first-sm' : lastSm ? ' last-sm' : '';
		c += firstMd ? ' first-md' : lastMd ? ' last-md' : '';
		c += firstLg ? ' first-lg' : lastLg ? ' last-lg' : '';
		c += firstXl ? ' first-xl' : lastXl ? ' last-xl' : '';
		c += xsOffset ? ' col-xs-offset-' + xsOffset : '';
		c += smOffset ? ' col-sm-offset-' + smOffset : '';
		c += mdOffset ? ' col-md-offset-' + mdOffset : '';
		c += lgOffset ? ' col-lg-offset-' + lgOffset : '';
		c += xlOffset ? ' col-xl-offset-' + xlOffset : '';
		c += xs ? ' col-xs-' + xs : '';
		c += sm ? ' col-sm-' + sm : '';
		c += md ? ' col-md-' + md : '';
		c += lg ? ' col-lg-' + lg : '';
		c += xl ? ' col-xl-' + xl : '';
		c += textXs ? ' text-xs-' + textXs : '';
		c += textSm ? ' text-sm-' + textSm : '';
		c += textMd ? ' text-md-' + textMd : '';
		c += textLg ? ' text-lg-' + textLg : '';
		c += textXl ? ' text-xl-' + textXl : '';
		c += alignSelf ? ' align-self-' + alignSelf : '';
		return c;
	};
	return (
		<div className={ `${ classes() } ${ customClass ? customClass : '' }` }>
			{ children }
		</div>
	);
};

export default Column;
