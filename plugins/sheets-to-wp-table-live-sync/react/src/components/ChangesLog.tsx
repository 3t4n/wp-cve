import { FC } from 'react';
import HeadwayWidget from '@headwayapp/react-widget';

import { RedHeart } from '../icons';
import { getStrings } from './../Helpers';

import '../styles/_changes_log.scss';

const ChangesLog: FC = () => {
	const HW_CONFIG_ACCOUNT = '7kPL5J';

	return (
		<HeadwayWidget account={HW_CONFIG_ACCOUNT}>
			<div className="whats-new">
				<div className="icon">{RedHeart}</div>
				<p>{getStrings('db-headway')}</p>
			</div>
		</HeadwayWidget>
	);
};

export default ChangesLog;