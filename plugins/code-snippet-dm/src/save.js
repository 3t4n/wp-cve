/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-i18n/
 */
import { __ } from '@wordpress/i18n';

/**
 * React hook that is used to mark the block wrapper element.
 * It provides all the necessary props like the class name.
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-block-editor/#useBlockProps
 */
import { useBlockProps } from '@wordpress/block-editor';

/**
 * The save function defines the way in which the different attributes should
 * be combined into the final markup, which is then serialized by the block
 * editor into `post_content`.
 *
 * @see https://developer.wordpress.org/block-editor/developers/block-api/block-edit-save/#save
 *
 * @return {WPElement} Element to render.
 */
export default function save({ attributes }) { 
    let standardSnippetClass = 'dm-code-snippet ';

    let backgroundColorStatusClass = ' no-background';
    if(attributes.backgroundColorStatus) {
        backgroundColorStatusClass = ' default';
    }

    let backgroundColorMobileStatusClass = ' no-background-mobile';
    if(attributes.backgroundColorMobileStatus) {
        backgroundColorMobileStatusClass = '';
    }

    let wrapCodeClass = ' no-wrap';
    if(attributes.wrapCode) {
        wrapCodeClass = ' wrap';
    }

    let languageClass = ' language-' + attributes.language;

    let lineNumbersClass = ' no-line-numbers';
    if(attributes.lineNumbers) {
        lineNumbersClass = ' line-numbers';
    }

    let slimClass = ' dm-normal-version';
    if(attributes.slim) {
        slimClass = ' dm-slim-version';
    }
    

	return (
        <div className={ standardSnippetClass + attributes.theme + slimClass + backgroundColorStatusClass + backgroundColorMobileStatusClass} snippet-height={ attributes.maxHeight } style={{ backgroundColor:attributes.backgroundColor }}>
            <div class="control-language">
                <div class="dm-buttons">
                    <div class="dm-buttons-left">
                        <div class="dm-button-snippet red-button"></div>
                        <div class="dm-button-snippet orange-button"></div>
                        <div class="dm-button-snippet green-button"></div>
                    </div>
                    <div class="dm-buttons-right">
                        <a id="dm-copy-raw-code">
                        <span class="dm-copy-text">{ attributes.copyText }</span>
                        <span class="dm-copy-confirmed" style="display:none">{ attributes.afterCopyText }</span>
                        <span class="dm-error-message" style="display:none">Use a different Browser</span></a>
                    </div>
                </div>
                <pre className={ lineNumbersClass }><code id="dm-code-raw" className={ wrapCodeClass + languageClass }>{ attributes.content }</code></pre>
            </div>
        </div>
	);
}
