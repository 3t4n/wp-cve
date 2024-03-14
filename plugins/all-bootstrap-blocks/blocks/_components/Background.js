const Background = ( areoi, attributes, onChange ) => {
    return (
        <areoi.components.PanelBody title={ 'Background' } initialOpen={ false }>
            <areoi.components.PanelRow className={ 'areoi-panel-row ' + ( !attributes.background_display ? 'areoi-panel-row-no-border' : '' ) }>
                <areoi.components.ToggleControl 
                    label={ 'Display Background' }
                    help="Toggle on to display a background and all available background options."
                    checked={ attributes.background_display }
                    onChange={ ( value ) => onChange( 'background_display', value ) }
                />
            </areoi.components.PanelRow>

            { attributes.background_display &&
                <>
                    {
                        Object.keys( attributes ).indexOf( 'background_horizontal_align' ) !== -1 &&
                        <areoi.components.PanelRow className="areoi-panel-row">
                            <areoi.components.SelectControl
                                label="Horizontal Align"
                                labelPosition="top"
                                help="Align the background to the left of the strip, in the center or to the right. This will be applied for all devices."
                                value={ attributes.background_horizontal_align }
                                options={ [
                                    { label: 'Left', value: 'justify-content-start' },
                                    { label: 'Center', value: 'justify-content-center' },
                                    { label: 'Right', value: 'justify-content-end' }
                                ] }
                                onChange={ ( val ) => onChange( 'background_horizontal_align', val ) }
                            />
                        </areoi.components.PanelRow>
                    }

                    {
                        Object.keys( attributes ).indexOf( 'background_utility' ) !== -1 &&
                        <areoi.components.PanelRow className="areoi-panel-row">
                            <areoi.components.SelectControl
                                label="Color (Utility Class)"
                                labelPosition="top"
                                help="Use the Bootstrap background utilities to change the background. This will override any colors added via the color picker."
                                value={ attributes.background_utility }
                                options={ [
                                    { label: 'None', value: '' },
                                    { label: 'Primary', value: 'bg-primary' },
                                    { label: 'Secondary', value: 'bg-secondary' },
                                    { label: 'Success', value: 'bg-success' },
                                    { label: 'Danger', value: 'bg-danger' },
                                    { label: 'Warning', value: 'bg-warning' },
                                    { label: 'Info', value: 'bg-info' },
                                    { label: 'Light', value: 'bg-light' },
                                    { label: 'Dark', value: 'bg-dark' },
                                    { label: 'Body', value: 'bg-body' },
                                ] }
                                onChange={ ( value ) => onChange( 'background_utility', value ) }
                            />

                        </areoi.components.PanelRow>
                    }

                    {
                        ( Object.keys( attributes ).indexOf( 'background_utility' ) === -1 || !attributes.background_utility ) &&
                        areoi.ColorPicker( areoi, attributes, onChange, 'background_color', 'Color' )
                    }

                    { areoi.MediaUpload( areoi, attributes, onChange, 'Image', 'image', 'background_image' ) }

                    { areoi.MediaUpload( areoi, attributes, onChange, 'Video', 'video', 'background_video' ) }

                    <areoi.components.ToggleControl 
                        label={ 'Display Overlay' }
                        help="Toggle on to add an overlay over the top of any image or video added to the background."
                        checked={ attributes.background_display_overlay }
                        onChange={ ( value ) => onChange( 'background_display_overlay', value ) }
                    />

                    { attributes.background_display_overlay &&
                        areoi.ColorPicker( areoi, attributes, onChange, 'background_overlay', 'Overlay' )
                    }                    
                </>
            }
        </areoi.components.PanelBody>
    );
}

export default Background;