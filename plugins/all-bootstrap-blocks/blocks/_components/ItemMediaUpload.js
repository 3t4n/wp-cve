const ItemMediaUpload = ( areoi, attributes, onChangeItem, label, type, key, index, attribute_key ) => {
    return (
        <areoi.components.PanelRow className="areoi-panel-row">
            
            <label>{ label }</label>
            
            <areoi.editor.MediaUploadCheck>
                <areoi.editor.MediaUpload
                    label="Image"
                    allowedTypes={ [type] }
                    onSelect={ ( val ) => onChangeItem( index, key, val ) }
                    value={ attributes[attribute_key][index][key] ? attributes[attribute_key][index][key].id : null }
                    render={({ open}) => (
                        <areoi.components.Button 
                            className={ 'editor-post-featured-image__toggle areoi-components-button-img' }
                            onClick={ open }
                        >
                            { !attributes[attribute_key][index][key] && areoi.__('Choose ' + label, 'awp') }

                            { type == 'image' && attributes[attribute_key][index][key] != undefined && 
                                <areoi.components.ResponsiveWrapper
                                    naturalWidth={ attributes[attribute_key][index][key].width }
                                    naturalHeight={ attributes[attribute_key][index][key].height }
                                >
                                    <img src={attributes[attribute_key][index][key].url} />
                                </areoi.components.ResponsiveWrapper>
                            }

                            { type == 'video' && attributes[attribute_key][index][key] != undefined && 
                                <video>
                                    <source src={ attributes[attribute_key][index][key].url } />
                                </video>
                            }

                        </areoi.components.Button>
                    )}
                />
            </areoi.editor.MediaUploadCheck>

            {attributes[attribute_key][index][key] && 
                <areoi.editor.MediaUploadCheck>
                    <areoi.components.Button onClick={ ( image ) => onChangeItem( index, key, null ) } isLink isDestructive>
                        {areoi.__('Remove ' + label, 'awp')}
                    </areoi.components.Button>
                </areoi.editor.MediaUploadCheck>
            }

        </areoi.components.PanelRow>
    );
}

export default ItemMediaUpload;