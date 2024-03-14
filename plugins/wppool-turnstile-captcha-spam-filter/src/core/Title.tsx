import React, { FC } from 'react';

import './_title.scss';

type Props = {
	customClass?: string;
	children: any;
};

const Title: FC<Props> = ({ customClass, children }) => {
	return (
		<div className={`swptls-title ${customClass ? ` ${customClass}` : ``}`}>
			{children}
		</div>
	);
};

export default Title;
