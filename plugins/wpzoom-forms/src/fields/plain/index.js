import { registerBlockType } from '@wordpress/blocks';
import json from './block.json';
import Edit from './edit';
import Save from './save';

const { name } = json;

registerBlockType(
	name,
	{
		icon: ( <svg xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 100 100"><path d="M15,58.75c-0.69,0-1.25,0.56-1.25,1.25s0.56,1.25,1.25,1.25h10c0.69,0,1.25-0.56,1.25-1.25s-0.56-1.25-1.25-1.25h-3.75 v-17.5H25c0.69,0,1.25-0.56,1.25-1.25s-0.56-1.25-1.25-1.25H15c-0.69,0-1.25,0.56-1.25,1.25s0.56,1.25,1.25,1.25h3.75v17.5H15z"/><path d="M92.5,30h-85C6.119,30,5,31.119,5,32.5v35C5,68.881,6.119,70,7.5,70h85c1.381,0,2.5-1.119,2.5-2.5v-35 C95,31.119,93.881,30,92.5,30z M90,65H10V35h80V65z"/></svg> ),
		edit: Edit,
		save: Save
	}
);