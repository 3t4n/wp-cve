import { registerBlockType } from '@wordpress/blocks';
import { sliderProIcon } from './icons';
import edit from './edit';
import save from './save';
import metadata from './block.json';

registerBlockType( metadata, {
	icon: sliderProIcon,
	edit,
	save,
});
