/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-i18n/
 */
import {__} from '@wordpress/i18n';

/**
 * React hook that is used to mark the block wrapper element.
 * It provides all the necessary props like the class name.
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-block-editor/#useBlockProps
 */
import {useBlockProps} from '@wordpress/block-editor';

import {Placeholder, TextControl} from '@wordpress/components';

/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * Those files can contain any CSS code that gets applied to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
import './editor.scss';

const fdsusIcon = wp.element.createElement('svg', {width: 24, height: 24},
    wp.element.createElement('path', {d: " M 8.11 20 C 7.288 19.778 6.027 21.278 5.041 22.056 C 6.301 23 7.781 23.667 9.315 24 L 9.315 23.944 C 9.205 22.5 9.918 20.5 8.11 20 Z  M 0 12.167 C 0 15.389 1.26 18.333 3.288 20.5 C 5.151 18 7.288 15.944 10.411 14.778 C 11.836 14.278 13.096 12.833 14.082 11.5 C 15.671 9.222 16.986 6.722 18.411 4.333 C 19.342 3.611 20.274 4.833 19.671 6.056 C 19.178 7.333 18.795 8.611 18.192 9.833 C 16.438 13.111 15.562 16.556 15.89 20.222 C 16 21.667 16.274 22.944 15.781 23.722 C 20.548 22.111 24 17.556 24 12.167 C 24 5.444 18.63 0 12 0 C 5.37 0 0 5.444 0 12.167 Z  M 5.808 11.167 C 5.918 9.389 7.288 8.111 8.932 8.222 C 10.521 8.333 11.89 9.833 11.781 11.444 C 11.726 13.111 10.247 14.444 8.603 14.333 C 6.904 14.278 5.753 12.889 5.808 11.167 Z "})
);

import {ToggleControl} from '@wordpress/components';
import {useState} from '@wordpress/element';

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/developers/block-api/block-edit-save/#edit
 *
 * @return {WPElement} Element to render.
 */
export default function Edit({attributes, isSelected, setAttributes}) {
    const {RawHTML} = wp.element;
    const [hasListTitleIsCategory, setHasListTitleIsCategory] = useState(false);

    return (
        <div {...useBlockProps()}>
            <Placeholder
                label="Sign-up Sheets Block"
                instructions="Fill out the fields below to generate your individual sheet or listing.  All fields are optional."
                icon={fdsusIcon}
            >
                <TextControl
                    label="Sheet ID (leave blank to output listing)"
                    value={attributes.id}
                    type="number"
                    help="If blank, fields below can be used to customize the listing display."
                    onChange={(val) => setAttributes({id: val})}
                    className="fdsus-field-id"
                />
                <TextControl
                    label="List Title (leave blank to default to 'Current Sign-up Sheets')"
                    value={attributes.listTitle}
                    onChange={(val) => setAttributes({listTitle: val})}
                />
                <TextControl
                    label="Category Slug (display list of sheets within a specific category)"
                    value={attributes.categorySlug}
                    onChange={(val) => setAttributes({categorySlug: val})}
                    className="fdsus-pro-field"
                />
                <ToggleControl
                    label="Use Category as List Title (only used if Category Slug is entered)"
                    help={
                        attributes.hasListTitleIsCategory
                            ? 'Has category as list title.'
                            : 'Does not have category as list title.'
                    }
                    checked={attributes.hasListTitleIsCategory}
                    onChange={() => {
                        setAttributes({hasListTitleIsCategory: !attributes.hasListTitleIsCategory});
                    }}
                    className="fdsus-pro-field"
                />
            </Placeholder>
        </div>
    );
}
