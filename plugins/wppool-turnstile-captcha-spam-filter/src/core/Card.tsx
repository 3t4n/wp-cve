import { FC } from 'react';

type Props = {
	colored?: boolean;
	children: React.ReactNode;
	customClass?: string;
};

import './_card.scss';

const Card: FC<Props> = ({ colored, children, customClass }) => {
	let classes = `swptls-card`;

	if (colored) {
		classes += ' colored-bg';
	}

	if (customClass) {
		classes += ' ' + customClass;
	}

	return <div className={classes}>{children}</div>;
};

export default Card;