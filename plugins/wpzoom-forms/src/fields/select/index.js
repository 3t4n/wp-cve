import { registerBlockType } from '@wordpress/blocks';
import json from './block.json';
import Edit from './edit';
import Save from './save';

const { name } = json;

registerBlockType(
	name,
	{
		icon: ( <svg xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 100 100"><path d="M92.5,30h-85C6.119,30,5,31.119,5,32.5v35C5,68.881,6.119,70,7.5,70h85c1.381,0,2.5-1.119,2.5-2.5v-35 C95,31.119,93.881,30,92.5,30z M90,65H10V35h80V65z"/><path d="M76.616,53.384c0.244,0.244,0.564,0.366,0.884,0.366s0.64-0.122,0.884-0.366l5-5c0.357-0.357,0.464-0.895,0.271-1.362 c-0.193-0.467-0.649-0.771-1.155-0.771h-10c-0.505,0-0.961,0.305-1.155,0.771c-0.193,0.467-0.086,1.005,0.271,1.362L76.616,53.384z"/></svg> ),
		edit: Edit,
		save: Save
	}
);