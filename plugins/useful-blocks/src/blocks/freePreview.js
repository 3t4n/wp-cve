import { __ } from '@wordpress/i18n';
import { textDomain, isPro } from '@blocks/config';

/**
 * Custom Component
 */
export default function ({ description, children }) {
	return (
		<>
			{!isPro ? (
				<div className='pb-free-noticeBox'>
					<a
						href='https://ponhiro.com/useful-blocks/#download-link'
						target='_blank'
						rel='noreferrer noopener'
					>
						{__('In the Pro version,', textDomain)}
					</a>
					{description || ''}
					<div className='pb-free-ctrlPreview'>{children}</div>
				</div>
			) : (
				children
			)}
		</>
	);
}
