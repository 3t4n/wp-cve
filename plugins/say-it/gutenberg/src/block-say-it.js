// Ressources :
// https://neliosoftware.com/blog/how-to-add-button-gutenberg-blocks/?nab=0
// https://awhitepixel.com/blog/wordpress-gutenberg-create-custom-block-part-2-register-block/

const { registerBlockType } = wp.blocks;
 
registerBlockType('davidmanson/sayit', {
	title: 'My first block',
	category: 'common',
	icon: 'smiley',
	description: 'Learning in progress',
	keywords: ['example', 'test'],
	edit: () => { 
		return <div>:)</div> 
	},
	save: () => { 
		return <div>:)</div> 
	}
});