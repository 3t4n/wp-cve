/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-i18n/
 */
import { __ } from '@wordpress/i18n';
import apiFetch from '@wordpress/api-fetch';
import { Button, RadioControl, FormToggle, CheckboxControl, SelectControl, TextControl } from '@wordpress/components';
import { useCallback, useState } from '@wordpress/element';
import { isURL } from '@wordpress/url';
import classNames from 'classnames';
import { get } from 'lodash';
import getLayout from './utils/getLayout.js';

/**
 * React hook that is used to mark the block wrapper element.
 * It provides all the necessary props like the class name.
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-block-editor/#useBlockProps
 */
import { useBlockProps } from '@wordpress/block-editor';

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

const Edit = ({ attributes, setAttributes }) => {
    const hasOverriddenDefaults = Boolean(
        attributes.layout ||
        attributes.target ||
        attributes.no_img ||
        attributes.no_title ||
        attributes.no_desc ||
        attributes.max_title_chars ||
        attributes.max_desc_chars
    );

    const resetAttributesToDefaults = () => {
        setAttributes({
            layout: '',
            target: '',
            no_img: '',
            no_title: '',
            no_desc: '',
            max_title_chars: '',
            max_desc_chars: ''
        });
    };

    // State Variables
    const [overridesVisible, setOverridesVisible] = useState(hasOverriddenDefaults);
    const [saving, setSaving] = useState(false);
    const [editingBlock, setEditingBlock] = useState(true);
    const [errorOnSave, setErrorOnSave] = useState(null);

    // Fetch the URL on Save
    const handleSaveButton = (event) => {
        if (attributes.hashMd5) {
            toggleEditMode();
            return;
        }

        if (!attributes.hashMd5 && isURL(attributes.url)) {
            setSaving(true);
            return apiFetch({
                url: `../?rest_route=/${zwtWPLinkPreviewerGlobals.restNamespace}/url`,
                method: 'POST',
                data: { url: attributes.url }
            }).then(( response ) => {
                setSaving(false);
                for (const [key, value] of Object.entries(response)) {
                    setAttributes({ [key]: value });
                }

                if (errorOnSave) {
                    setErrorOnSave(null);
                }

                toggleEditMode();
            }).catch(() => {
                setErrorOnSave('Could not fetch URL');
                setSaving(false);
            });
        }
    };

    // UI Controls
    const toggleEditMode = useCallback(() => setEditingBlock(!editingBlock), [editingBlock]);
    const toggleOverrides = useCallback(() => {
        if (overridesVisible) {
            resetAttributesToDefaults();
        }
        setOverridesVisible(!overridesVisible);
    }, [overridesVisible]);
    const getLayoutRadioValue = () => attributes.layout || zwtWPLinkPreviewerGlobals.layout;

    // Util functions to handle saving particular attribute data types
    const setBooleanAttribute = useCallback((attribute, value) => {
        // boolean saving is a little weird, string seems to be safer
        setAttributes({ [attribute]: value ? 'T' : null });
    }, []);
    const setNumberAttribute = useCallback((attribute, value) => {
        if(value.match(/^\d+$/) || !value) {
            setAttributes({ [attribute]: value});
        }
    }, []);

    // Attribute setters
    const handleURLChange = (value) => {
        if (!attributes.hashMd5 && !saving) {
            /*
                N.B. editing a URL poses a lot of complexity due to how the
                records are stored and the piecemeal updating of the block
                attributes. If a user wants to change the URL preview, they
                will need to delete the block and add a new one.

                This "if" block means that once the URL has been saved and we
                have the hashMd5 value, the URL cannot be changed. We also want
                to prohibit the user from modifying the URL between clicking
                "Save" and the receipt of the REST response.

                If we want to support changing the URL, this could be done via
                some kind of "Reset Block" button that would clear all of the
                block's attributes.
            */
            setAttributes({url: value});
        }
    };
    const setLayoutDefault = () => {
        setAttributes({layout: ''});
    };
    const setLayoutFull = () => {
        // if the plugin is already set to use full, then use the global
        setAttributes({layout: zwtWPLinkPreviewerGlobals.layout === 'full' ? '' : 'full' });
    };
    const setLayoutCompact = () => {
        // if the plugin is already set to use compact, then use the global
        setAttributes({layout: zwtWPLinkPreviewerGlobals.layout === 'compact' ? '' : 'compact'});
    };
    const handleTargetChange = (value) => {
        setAttributes({target: value});
    };
    const setNoImg = (value) => {
        setBooleanAttribute('no_img', value);
    };
    const setNoTitle = (value) => {
        setBooleanAttribute('no_title', value);
    };
    const setNoDesc = (value) => {
        setBooleanAttribute('no_desc', value);
    };
    const setMaxTitleChars = (value) => {
        setNumberAttribute('max_title_chars', value);
    };
    const setMaxDescChars = (value) => {
        setNumberAttribute('max_desc_chars', value);
    };

    // Render the edit layout
    return (
        <div { ...useBlockProps() }>
            { (!attributes.hashMd5 || editingBlock) && (
                <div className="zwt-wp-link-prev-edit">
                    {attributes.hashMd5 ? (<p className='url'><strong>URL: </strong>{attributes.url}</p>) : (<TextControl
                        type="text"
                        label={__('URL', 'beautiful-link-preview')}
                        onChange={handleURLChange}
                        value={attributes.url}
                    />)}
                    <div className="overrideToggle">
                        <FormToggle
                            checked={overridesVisible}
                            onChange={toggleOverrides}
                            disabled={!isURL(attributes.url)}
                        />
                        <p onMouseUp={isURL(attributes.url) ? toggleOverrides : null}>Override Plugin Settings</p>
                    </div>
                    <div className={classNames('overrides', {
                        hiddenOverrides: !overridesVisible
                    })}>
                        <div className="controlGroup">
                            <div className="layoutButtons">
                                <p>Layout:</p>
                                <RadioControl
                                    selected={getLayoutRadioValue()}
                                    options={[
                                        { label: 'Full', value: 'full' },
                                        { label: 'Compact', value: 'compact' },
                                    ]}
                                    onChange={(value) => {
                                        value === 'full' ? setLayoutFull() : setLayoutCompact();
                                    }}
                                />
                            </div>
                            <div className="hideBoxes">
                                <p>Hide Elements:</p>
                                <div className="boxes">
                                    <CheckboxControl
                                        label={__('Image', 'beautiful-link-preview')}
                                        checked={Boolean(attributes.no_img)}
                                        onChange={setNoImg}
                                    />
                                    <CheckboxControl
                                        label={__('Title', 'beautiful-link-preview')}
                                        checked={Boolean(attributes.no_title)}
                                        onChange={setNoTitle}
                                    />
                                    <CheckboxControl
                                        label={__('Description', 'beautiful-link-preview')}
                                        checked={Boolean(attributes.no_desc)}
                                        onChange={setNoDesc}
                                    />
                                </div>
                            </div>
                            <div className="textControlBoxes">
                                <div className="maxChars">
                                    <p>Max Characters:</p>
                                    <div className="fields">
                                        <TextControl
                                            type="text"
                                            label={__('Title', 'beautiful-link-preview')}
                                            onChange={setMaxTitleChars}
                                            value={attributes.max_title_chars ? parseInt(attributes.max_title_chars) : ''}
                                        />
                                        <TextControl
                                            type="text"
                                            label={__('Description', 'beautiful-link-preview')}
                                            onChange={setMaxDescChars}
                                            value={attributes.max_desc_chars ? parseInt(attributes.max_desc_chars): ''}
                                        />
                                    </div>
                                </div>
                                <div className="targetControls">
                                    <p>Link Target:</p>
                                    <div className="targetSelect">
                                        <SelectControl
                                            value={attributes.target}
                                            options={get(zwtWPLinkPreviewerGlobals, 'targetOptions', []).map(option => {
                                                return {
                                                    label: option,
                                                    value: option,
                                                };
                                            })}
                                            onChange={(value) => handleTargetChange(value)}
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    { errorOnSave && (
                        <div className="error"><p>{errorOnSave}</p></div>
                    )}
                    <Button className={classNames('save-button', {
                        disabled: !attributes.url
                    })} variant="primary" onMouseUp={(!saving && attributes.url) ? handleSaveButton : null}>
                        {!saving ? 'Save' : '....'}
                    </Button>
                </div>
            ) }
            { attributes.hashMd5 && (
                <div>
                    {!editingBlock && (<Button className="edit-button" variant="primary" onMouseUp={toggleEditMode}>
                        Edit
                    </Button>)}
                    {getLayout({
                        imgURLStub: `../?rest_route=`,
                        ...attributes
                    })}
                </div>
            ) }
        </div>
    );
}

export default Edit;
