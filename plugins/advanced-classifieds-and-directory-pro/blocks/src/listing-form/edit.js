/**
 * Import block dependencies
 */
import ServerSideRender from '@wordpress/server-side-render';

import { useBlockProps } from '@wordpress/block-editor';

import { Disabled } from '@wordpress/components';

import { 
	useEffect,
	useRef
} from '@wordpress/element';

import { doAction } from '@wordpress/hooks';

/**
 * Describes the structure of the block in the context of the editor.
 * This represents what the editor will render when the block is used.
 *
 * @return {WPElement} Element to render.
 */
export default function Edit() {
	const mounted = useRef();	
	useEffect(() => {
		if ( ! mounted.current ) {
			// Do componentDidMount logic
			mounted.current = true;
		} else {
			// Do componentDidUpdate logic
			doAction( 'acadp_init_listing_form' );
		}
	});

	return (
		<>
			<div { ...useBlockProps() }>
				<Disabled>
					<ServerSideRender block="acadp/listing-form" />
				</Disabled>	
			</div>					
		</>
	);
}
