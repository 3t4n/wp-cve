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
import { useBlockProps, PlainText, BlockControls, InspectorControls, PanelColorSettings } from '@wordpress/block-editor';
import { PanelBody, PanelRow, ToggleControl, TextControl, SelectControl  } from '@wordpress/components';

/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * Those files can contain any CSS code that gets applied to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
import './editor.scss';

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/developers/block-api/block-edit-save/#edit
 *
 * @return {WPElement} Element to render.
 */
export default function Edit({ attributes, setAttributes }) {
    const blockProps = useBlockProps();

    const onChangeMaxHeight = ( newMaxHeight ) => {
        setAttributes( { maxHeight: newMaxHeight === undefined ? '' : newMaxHeight } )
    }

    const onChangeCopyText = ( newCopyText ) => {
        setAttributes( { copyText: newCopyText === undefined ? '' : newCopyText } )
    }

    const onChangeAfterCopyText = ( newAfterCopyText ) => {
        setAttributes( { afterCopyText: newAfterCopyText === undefined ? '' : newAfterCopyText } )
    }

    const colorSamples = [
        {
            name: 'Eton Blue',
            slug: 'eton-blue',
            color: '#7FC8A9'
        },
        {
            name: 'Lavender',
            slug: 'lavender',
            color: '#E6E6FA'
        },
        {
            name: 'Purple Blueish',
            slug: 'purple-blueish',
            color: '#5E69FF'
        },
        {
            name: 'Fuchsia Pink',
            slug: 'fuchsia-pink',
            color: '#FB8CFF'
        },
        {
            name: 'Pale Pink',
            slug: 'pale-pink',
            color: '#FCD8D4'
        },
        {
            name: 'Pastel Gray',
            slug: 'pastel-gray',
            color: '#C9D8B6'
        },
        {
            name: 'Water',
            slug: 'water',
            color: '#D4F8FC'
        }
    ];

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
        <>
            <InspectorControls>
                <PanelColorSettings 
					title={ __( 'Color settings', 'code-snippet-block-dm' ) }
					initialOpen={ false }
					colorSettings={ [
						{
                            colors: colorSamples,
                            value: attributes.backgroundColor,
                            onChange: ( val ) => {
                            setAttributes( { backgroundColor: val } );
                            },
                            label: __( 'Background color', 'code-snippet-block-dm' ),
						}
					] }
				/>
                <PanelBody 
                    title={ __( 'Layout', 'code-snippet-block-dm' )}
                    initialOpen={true}
                >
                    <PanelRow>
                        <fieldset>
                            <SelectControl
                                label="Theme"
                                value={ attributes.theme }
                                options={ [
                                    { label: 'Dark', value: 'dark' },
                                    { label: 'Light', value: 'light' },
                                ] }
                                onChange={ ( val ) => {
                                    setAttributes( { theme: val } );
                                } }
                                __nextHasNoMarginBottom
                            />
                        </fieldset>
                    </PanelRow>
                    <PanelRow>
                        <fieldset>
                            <ToggleControl
                                label="Slim Version"
                                help={
                                    attributes.slim
                                        ? 'Yes'
                                        : 'No'
                                }
                                checked={ attributes.slim }
                                onChange={ (val) => {
                                    setAttributes( { slim: val } );
                                } }
                            />
                        </fieldset>
                    </PanelRow>
                    <PanelRow>
                        <fieldset>
                            <ToggleControl
                                label="Line Numbers"
                                help={
                                    attributes.lineNumbers
                                        ? 'Yes'
                                        : 'No'
                                }
                                checked={ attributes.lineNumbers }
                                onChange={ (val) => {
                                    setAttributes( { lineNumbers: val } );
                                } }
                            />
                        </fieldset>
                    </PanelRow>
                    <PanelRow>
                        <fieldset>
                            <ToggleControl
                                label="Enable background"
                                help={
                                    attributes.backgroundColorStatus
                                        ? 'Yes'
                                        : 'No'
                                }
                                checked={ attributes.backgroundColorStatus }
                                onChange={ (val) => {
                                    setAttributes( { backgroundColorStatus: val } );
                                } }
                            />
                        </fieldset>
                    </PanelRow>
                    <PanelRow>
                        <fieldset>
                            <ToggleControl
                                label="Enable background on mobile"
                                help={
                                    attributes.backgroundColorMobileStatus
                                        ? 'Yes'
                                        : 'No'
                                }
                                checked={ attributes.backgroundColorMobileStatus }
                                onChange={ (val) => {
                                    setAttributes({ backgroundColorMobileStatus: val });
                                } }
                            />
                        </fieldset>
                    </PanelRow>
                </PanelBody>
                <PanelBody 
                    title={ __( 'Details', 'code-snippet-block-dm' )}
                    initialOpen={true}
                >
                    <PanelRow>
                        <fieldset>
                            <SelectControl
                                label="Language"
                                value={ attributes.language }
                                options={ [
                                    { label: 'C-Like', value: 'clike' },
                                    { label: 'CSS', value: 'css' },
                                    { label: 'HTML/Markup', value: 'markup' },
                                    { label: 'JavaScript', value: 'javascript' },
                                    { label: 'Perl', value: 'perl' },
                                    { label: 'PHP', value: 'php' },
                                    { label: 'Python', value: 'python' },
                                    { label: 'Ruby', value: 'ruby' },
                                    { label: 'SQL', value: 'sql' },
                                    { label: 'TypeScript', value: 'typescript' },
                                    { label: 'Bash/Shell', value: 'shell' },
                                ] }
                                onChange={ ( val ) => {
                                    setAttributes( { language: val } );
                                } }
                                __nextHasNoMarginBottom
                            />
                        </fieldset>
                    </PanelRow>
                    <PanelRow>
                        <fieldset>
                            <ToggleControl
                                label="Wrap Code"
                                help={
                                    attributes.wrapCode
                                        ? 'Yes'
                                        : 'No'
                                }
                                checked={ attributes.wrapCode }
                                onChange={ (val) => {
                                    setAttributes( { wrapCode: val } );
                                } }
                            />
                        </fieldset>
                    </PanelRow>
                    <PanelRow>
                        <fieldset>
                            <TextControl
                                label={__( 'Max Height', 'code-snippet-block-dm' )}
                                value={ attributes.maxHeight }
                                onChange={ onChangeMaxHeight }
                                help={ __( 'Set max height in PX (300px) for the code snippet. Leave empty if not needed.', 'code-snippet-block-dm' )}
                            />
                        </fieldset>
                    </PanelRow>
                    <PanelRow>
                        <fieldset>
                            <TextControl
                                label={__( 'Copy Text', 'code-snippet-block-dm' )}
                                value={ attributes.copyText }
                                onChange={ onChangeCopyText }
                                help={ __( 'Add your copy button text', 'code-snippet-block-dm' )}
                            />
                        </fieldset>
                    </PanelRow>
                    <PanelRow>
                        <fieldset>
                            <TextControl
                                label={__( 'After Copy Text', 'code-snippet-block-dm' )}
                                value={ attributes.afterCopyText }
                                onChange={ onChangeAfterCopyText }
                                help={ __( 'Add your copy button confirmation text', 'code-snippet-block-dm' )}
                            />
                        </fieldset>
                    </PanelRow>
                </PanelBody>
			</InspectorControls>

            <BlockControls group="block">
            </BlockControls>

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
                            <span class="dm-copy-confirmed" style={{display: 'none' }}>{ attributes.afterCopyText }</span>
                            <span class="dm-error-message" style={{display: 'none' }}>Use a different Browser</span></a>
                        </div>
                    </div>
                    <pre className={ lineNumbersClass }>
                        <code id="dm-code-raw" className={ wrapCodeClass + languageClass }>
                            <PlainText
                                { ...blockProps }

                                value={ attributes.content }
                                onChange={ ( newContent ) => setAttributes( { content: newContent } ) }
                            />
                        </code>
                    </pre>
                </div>
            </div>


        </>
    );
}
