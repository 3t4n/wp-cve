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

/**
 * The save function defines the way in which the different attributes should
 * be combined into the final markup, which is then serialized by the block
 * editor into `post_content`.
 *
 * @see https://developer.wordpress.org/block-editor/developers/block-api/block-edit-save/#save
 *
 * @return {WPElement} Element to render.
 */
export default function save({attributes}) {
    const blockProps = useBlockProps.save();
    const {RawHTML} = wp.element;
    var myShortcode = '[sign_up_sheet' +
        ((attributes.id && attributes.id.length) ? ' id="' + attributes.id + '"' : '') +
        ((attributes.listTitle && attributes.listTitle.length) ? ' list_title="' + attributes.listTitle + '"' : '') +
        ((attributes.categorySlug && attributes.categorySlug.length) ? ' category_slug="' + attributes.categorySlug + '"' : '') +
        ((attributes.hasListTitleIsCategory) ? ' list_title_is_category="' + attributes.hasListTitleIsCategory + '"' : '') +
        ']';

    return <div{...blockProps}>
        <RawHTML>{myShortcode}</RawHTML>
    </div>;
}
