import { registerBlockType } from '@wordpress/blocks';
import json from './block.json';
import Edit from './edit';
import Save from './save';

const { name } = json;

registerBlockType(
	name,
	{
		icon: ( <svg xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="25 25 50 50"><path d="M32.5,70h35c1.381,0,2.5-1.119,2.5-2.5v-35c0-1.381-1.119-2.5-2.5-2.5h-35c-1.381,0-2.5,1.119-2.5,2.5v35 C30,68.881,31.119,70,32.5,70z M35,35h30v30H35V35z"/><path d="M56.982,42.607L47.5,52.089l-4.482-4.482c-0.976-0.977-2.56-0.977-3.535,0c-0.977,0.976-0.977,2.559,0,3.535l6.25,6.25 c0.488,0.488,1.128,0.732,1.768,0.732s1.28-0.244,1.768-0.732l11.25-11.25c0.977-0.976,0.977-2.559,0-3.535 C59.542,41.631,57.958,41.631,56.982,42.607z"/></svg> ),
		edit: Edit,
		save: Save
	}
);