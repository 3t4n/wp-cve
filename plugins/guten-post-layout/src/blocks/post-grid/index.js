import edit from './components/edit';
import icons from "../../assets/icons";

const { __ } = wp.i18n;
const { registerBlockType } = wp.blocks;

import './style.scss';
import './editor.scss';

registerBlockType( 'guten-post-layout/post-grid', {
    title: __('Post Layout'),
    description: !window.gpl_admin.has_pro && (<a href='https://gutendev.com/downloads/guten-post-layout-pro/' target="_blank" className={'gpl-update-pro'}><img src={'../wp-content/plugins/guten-post-layout/src/assets/img/upgrade-pro.png'}/></a>),
    icon: {src: icons.gpl_icon_free},
    category: 'guten-post-layout',
	supports: {
		customClassName: false,
	},
    keywords: [
        __('Post'),
        __('News'),
        __('Grid'),
    ],
    getEditWrapperProps( { postBlockWidth } ) {
        if ( 'wide' === postBlockWidth || 'full' === postBlockWidth ) {
            return { 'data-align': postBlockWidth };
        }
    },
    edit,
    save() {
        // Rendering in PHP
        return null;
    },

});
