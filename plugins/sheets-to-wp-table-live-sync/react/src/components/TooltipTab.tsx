import React from 'react'
import { Tooltip as ReactTooltip } from 'react-tooltip';


import { infoIconWithQuestionMark } from '../icons';

const Tooltip = ({ content }) => {
	// const randomId = Math.floor(Math.random() * (1 - 1000 + 1)) + 1;
	const randomId = `app-help-${Date.now()}-${Math.floor(Math.random() * 1000)}`;

	return (
		<><ReactTooltip
			anchorId={`app-help-${randomId}`}
			content={content}
		/>
			<div className='swptls-tooltip' id={`app-help-${randomId}`}>
				{infoIconWithQuestionMark}
			</div></>
	)
}

export default Tooltip