import { useState } from '@wordpress/element';
import { registerFormatType, toggleFormat, applyFormat, getTextContent, slice } from '@wordpress/rich-text';
import { RichTextToolbarButton, RichTextShortcut } from '@wordpress/block-editor';


import SayItPopoverUI from './sayit-inline';

/**
 * Block constants
 */
const name = 'sayit/sayit-inline';


export const sayit = {
    name,
    title: 'Say It!',
    tagName: 'span',
    className: 'sayit',
    attributes: {
        mp3file: 'data-mp3-file',
        content: 'data-say-content',
    },
    edit: ( { value, contentRef, onChange, isActive, activeAttributes } ) => {

        const [loading, setLoading] = useState(false);

        /*
         * Helper to update only one attributes
         */
        const setAttr = (attribute, newValue) => {
            let newattributes = {...activeAttributes};
            newattributes[attribute] = newValue;
            onChange( applyFormat(
                value,
                {
                    type: 'sayit/sayit-inline',
                    attributes: newattributes
                }
            ) );
        }

        /*
         * Load mp3 from ajax method
         */
        const loadMP3 = async val => {
            setLoading(true);
            wp.ajax.post( "sayit_mp3", {words: activeAttributes.content} )
            .done(function(response) {
                setLoading(false);
                setAttr('mp3file', response.mp3);
            });
        };

        /*
         * Toggle the format
         */
        const onToggle = () => {
            let textContent = getTextContent(slice(value));
            onChange(
                toggleFormat( value, {
                    type: 'sayit/sayit-inline',
                    attributes: {
                        content: textContent
                    }
                })
            )
        }

        return (
            <>
                <RichTextShortcut
					type="primaryShift"
					character="s"
					onUse={ onToggle }
				/>
				<RichTextToolbarButton
					icon="admin-comments"
                    title="Say it !"
					onClick={ onToggle }
					isActive={ isActive }
					shortcutType="primaryShift"
					shortcutCharacter="s"
				/>
                { isActive && (
                    <SayItPopoverUI             
                        isActive={isActive}
                        activeAttributes={activeAttributes}
                        value={value}
                        contentRef={contentRef}
                        onChange={onChange}
                        loading={loading}
                        setAttr={setAttr}
                        loadMP3={loadMP3}
                    />
                ) }
            </>
        )
    },
};

function registerFormats () {
	[
		sayit,
	].forEach( ( { name, ...settings } ) => registerFormatType( name, settings ) );
};
registerFormats();