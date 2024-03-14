import { registerBlockType } from '@wordpress/blocks';
import json from './block.json';
import Edit from './edit';
import Save from './save';

const { name } = json;

registerBlockType(
	name,
	{
		icon: ( <svg xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 32 32"><path d="M17.56,31a1,1,0,0,1-.71-.29L1.29,15.15A1,1,0,0,1,1,14.44V4.2A3.2,3.2,0,0,1,4.2,1H14.44a1,1,0,0,1,.71.29L30.71,16.85a1, 1,0,0,1,0,1.41L18.26,30.71A1,1,0,0,1,17.56,31ZM3,14,17.56,28.59l11-11L14,3H4.2A1.2,1.2,0,0,0,3,4.2ZM8.41,8.41A2,2,0,1, 0,7,9,2,2,0,0,0,8.41,8.41ZM22.36,19.54a1,1,0,0,0,0-1.41l-9.9-9.9A1,1,0,0,0,11,9.64l9.9,9.9a1,1,0,0,0,1.41,0Zm-4.95.71a1, 1,0,0,0,0-1.41L9.64,11a1,1,0,0,0-1.41,1.41L16,20.24a1,1,0,0,0,1.41,0Z"/></svg> ),
		edit: Edit,
		save: Save
	}
);