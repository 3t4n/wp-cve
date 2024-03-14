import React from 'react'
import { Tooltip as ReactTooltip } from 'react-tooltip';


import { infoIconWithQuestionMark } from '../icons';

const Tooltip = ({ content }) => {
	const randomId = `app-help-${Date.now()}-${Math.floor(Math.random() * 1000)}`;

	return (
		<><ReactTooltip
			anchorId={`app-help-${randomId}`}
			content={content}
			// style={{ maxWidth: '500px', lineHeight: '1.5', wordBreak: 'break-word' }}
			style={{ maxWidth: '300px', lineHeight: '1.5' }}
		/>
			<div className='swptls-tooltip' id={`app-help-${randomId}`}>
				{infoIconWithQuestionMark}
			</div></>
	)
}

export default Tooltip