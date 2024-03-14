/**
 * BLOCK: Famous Blog Block Page Grid
 */

// Import block dependencies and components
import classnames from 'classnames';
import edit from './edit';

// Import CSS
import './styles/style.scss';
import './styles/editor.scss';

// Components
const { __, setLocaleData } = wp.i18n;

// Extend component
const {Component} = wp.element;

// Register block controls
const {registerBlockType} = wp.blocks;

// Register alignments
const validAlignments = ['center', 'wide'];

export const name = 'core/latest-posts';
import icons from '../icons/icons';

// Register the block
registerBlockType('timeline-blocks/tb-timeline-blocks', {
    title: __('Timeline Block'),
    description: __('Showcase your posts with a beautiful timeline template.'),
    icon: icons.timeline,
    category: 'timeline-blocks',
    keywords: [
        __('post'),
        __('timeline block'),
        __('timeline'),
    ],

    getEditWrapperProps(attributes) {
        const {align} = attributes;
        if (-1 !== validAlignments.indexOf(align)) {
            return {'data-align': align};
        }
    },

    edit,

    // Render via PHP
    save() {
        return null;
    },
});