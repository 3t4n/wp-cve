
import { useAnchorRef } from '@wordpress/rich-text';
import { TextControl, Popover, Button } from '@wordpress/components';

import { sayit as settings } from './sayit-format';

function SayItPopoverUI({
	isActive,
	activeAttributes,
    value,
    contentRef,
    onChange,
    loading,
    setAttr,
    loadMP3
}){
    const anchorRef = useAnchorRef( { ref: contentRef, value, settings } );
    return (
        <Popover
            anchorRef = { anchorRef }
            position = 'bottom center'
        >
            <div style={{ width: '280px', padding: '20px' }}>
                <TextControl
                    placeholder='Speech Content'
                    label='speech content'
                    help='Contenu textuel à lire'
                    value={activeAttributes.content ? activeAttributes.content : ''}
                    onChange={ (newContent) => { setAttr('content', newContent) }}
                />
                <TextControl
                    placeholder='Preloaded MP3'
                    label='Preloaded MP3'
                    help='Mp3 à lire, automatiquement généré si absent'
                    value={activeAttributes.mp3file ? activeAttributes.mp3file : ''}
                    onChange={ (newMp3file) => { setAttr('mp3file', newMp3file) }}
                />
                <Button isSecondary isSmall isBusy={loading} onClick={loadMP3}>Preload MP3</Button>
            </div>
        </Popover>
    );
    
}

export default SayItPopoverUI;