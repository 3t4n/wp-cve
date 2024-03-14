import React, { FC } from 'react';
import HeadwayWidget from '@headwayapp/react-widget';

import './_headway.scss';

const Headway: FC = () => {
	const HW_CONFIG_ACCOUNT = 'J4Zl2x';

	return (
		<HeadwayWidget account={HW_CONFIG_ACCOUNT}>
			<span className="ect-changelogs">What’s new? 😍</span>
		</HeadwayWidget>
	);
};

export default Headway;
