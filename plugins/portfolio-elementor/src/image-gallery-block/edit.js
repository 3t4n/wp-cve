import { __ } from '@wordpress/i18n';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import './editor.scss';

// Additional Imports
import { ServerSideRender } from '@wordpress/editor';
import { SelectControl, Button, PanelBody, FormTokenField, ToggleControl, RangeControl, TextControl, ColorPicker, BaseControl, TextareaControl } from '@wordpress/components';
import { MediaUpload, MediaUploadCheck, URLInput } from '@wordpress/block-editor';
import { useState, useEffect, RawHTML } from '@wordpress/element'; 
import { useInstanceId } from "@wordpress/compose";
import { withSelect, useSelect  } from '@wordpress/data';
import { compose } from '@wordpress/compose';

export default function Edit( { attributes, setAttributes  } ) {
    
    const blockProps = useBlockProps();

    // ===== Is PRO version?
    const { isProVersion } = powerfolioBlockData;

    // ====== Columns
    const columnOptionsArray = Object.entries(powerfolioBlockData.columnOptions).map(([value, label]) => ({
        value,
        label,
    }));

    // ====== Columns Mobile
    const columnMobileOptionsArray = Object.entries(powerfolioBlockData.columnMobileOptions).map(([value, label]) => ({
        value,
        label,
    }));

    // ======= Hover
    const hoverOptionsArray = Object.entries(powerfolioBlockData.hoverOptions).map(([value, label]) => ({
        value,
        label,
    }));       

    // ======= Style
    const [selectedStyle, setSelectedStyle] = useState(attributes.style);

    const styleOptionsArray = Object.entries(powerfolioBlockData.styleOptions).map(([value, label]) => ({
        value,
        label,
    }));   

    // ======= Link to
    const linkToOptionsArray = Object.entries(powerfolioBlockData.linkToOptions).map(([value, label]) => ({
        value,
        label,
    }));    

    // ======= Image Gallery
    const ImageWithUrlInput = ({ imageId, imageCustomUrls, setAttributes }) => {
        const [isOpen, setIsOpen] = useState(false);
        const image = useSelect((select) => select('core').getMedia(imageId), [imageId]);
    
        if (!image) {
            return <div>Loading...</div>;
        }
    
        return (
            <PanelBody
                key={imageId}
                title={`Image ${imageId}`}
                isOpen={isOpen}
                onToggle={() => setIsOpen(!isOpen)}
            >
                <img src={image.source_url} alt={image.alt_text} style={{ width: '100%', height: 'auto' }} />
                <CustomUrlInput
                    imageId={imageId}
                    imageCustomUrls={imageCustomUrls}
                    setAttributes={setAttributes}
                />
            </PanelBody>
        );
    };
        

    // ======= Image Gallery: URLs
    const [activePanel, setActivePanel] = useState();

    const CustomUrlInput = ({ imageId, imageCustomUrls, setAttributes }) => {
        const [url, setUrl] = useState(imageCustomUrls[imageId] || '');
    
        useEffect(() => {
            const newImageCustomUrls = {
                ...imageCustomUrls,
                [imageId]: url,
            };
            setAttributes({ imageCustomUrls: newImageCustomUrls });
        }, [url]);
    
        return (
            <label>
                {__('Custom URL:', 'powerfolio')}
                <URLInput value={url} onChange={(newUrl) => setUrl(newUrl)} />
            </label>
        );
    };
    
    

    return (
        <div {...blockProps}>
            <InspectorControls>   
                
                <PanelBody title="Image Gallery Settings">

                    <MediaUpload
                        onSelect={(images) => {
                            const imageIds = images.map((image) => image.id);
                            const newImageCustomUrls = { ...attributes.imageCustomUrls };
                        
                            // Add new image IDs and remove deleted image IDs from custom URLs
                            Object.keys(newImageCustomUrls).forEach((key) => {
                                if (!imageIds.includes(parseInt(key))) {
                                    delete newImageCustomUrls[key];
                                }
                            });
                        
                            imageIds.forEach((id) => {
                                if (!newImageCustomUrls.hasOwnProperty(id)) {
                                    newImageCustomUrls[id] = '';
                                }
                            });
                        
                            setAttributes({ imageIds, imageCustomUrls: newImageCustomUrls });
                        }}
                        allowedTypes={['image']}
                        multiple
                        gallery
                        value={attributes.imageIds}
                        render={({ open }) => (
                            <Button onClick={open} isPrimary>
                                {attributes.imageIds.length === 0
                                    ? __('Add Images', 'powerfolio')
                                    : __('Edit Gallery', 'powerfolio')}
                            </Button>
                        )}
                    />

                    {attributes.imageIds.map((imageId, index) => {
                        const image = wp.media.attachment(imageId);
                   
                        const imageUrl = image.get('url');
                        return (
                            <PanelBody key={imageId} title={__('Image', 'powerfolio') + ' ' + (index + 1)} initialOpen={false}>
                                <img src={imageUrl} alt="" />
                                <TextControl
                                    label={__('Item Title', 'powerfolio')}
                                    value={attributes.listTitle[imageId] || ''}
                                    onChange={(newTitle) => {
                                        const newListTitle = {
                                            ...attributes.listTitle,
                                            [imageId]: newTitle,
                                        };
                                        setAttributes({ listTitle: newListTitle });
                                    }}
                                />

                                <TextControl
                                    label={__('Item Tag (to use with filter)', 'powerfolio')}
                                    value={attributes.imageTags[imageId] || ''}
                                    onChange={(newText) => {
                                        if (imageId) {
                                            const newImageTags = {
                                                ...attributes.imageTags,
                                                [imageId]: newText,
                                            };
                                            setAttributes({ imageTags: newImageTags });
                                        }
                                    }}
                                />

                                <TextareaControl
                                    label={__('Item Description/Short Text', 'powerfolio')}
                                    value={attributes.listContent[imageId] || ''}
                                    onChange={(newContent) => {
                                        if (imageId) {
                                            const newListContent = {
                                                ...attributes.listContent,
                                                [imageId]: newContent,
                                            };
                                            setAttributes({ listContent: newListContent });
                                        }
                                    }}
                                />

                                {isProVersion ? (
                                    <>

                                    <SelectControl
                                        label={__('Image links to', 'powerfolio')}
                                        value={attributes.linkTo[imageId] || 'image'}
                                        options={[
                                            { value: 'image', label: __('Image (with lightbox)', 'powerfolio') },
                                            { value: 'link', label: __('Custom URL', 'powerfolio') },
                                        ]}
                                        onChange={(newLinkTo) => {
                                            const newLinkToValues = {
                                                ...attributes.linkTo,
                                                [imageId]: newLinkTo,
                                            };
                                            setAttributes({ linkTo: newLinkToValues });
                                        }}
                                    />

                                    <URLInput
                                        label={__('Item Custom Link', 'powerfolio')}
                                        value={attributes.imageCustomUrls[imageId] || ''}
                                        onChange={(newUrl) => {
                                            const newImageCustomUrls = {
                                                ...attributes.imageCustomUrls,
                                                [imageId]: newUrl,
                                            };
                                            setAttributes({ imageCustomUrls: newImageCustomUrls });
                                        }}
                                    />     

                                
                                    </>
                                ) : (
                                    <>
                                    </>
                                )}
                                                           
                            </PanelBody>
                        );
                    })}
                    {isProVersion ? (
                        <>
                        </>
                    ) : (
                        <RawHTML>{powerfolioBlockData.upgradeMessage}</RawHTML>
                    )} 

                </PanelBody>                           

                <PanelBody title={__('Layout & Customization', 'powerfolio')} initialOpen={true}>                    
                    {/* Style */}
                    <SelectControl
                        label={__('Style', 'powerfolio')}
                        value={attributes.style}
                        options={styleOptionsArray}
                        onChange={(newStyle) => {
                            setAttributes({ style: newStyle });
                            setSelectedStyle(newStyle);
                        }}
                    />

                    {/* Hover */}
                    <SelectControl
                        label={__('CSS Effect on Hover', 'powerfolio')}
                        value={attributes.hover}
                        options={hoverOptionsArray}
                        onChange={(newHover) => setAttributes({ hover: newHover })}
                    />

                    {/* Link To */}
                    <SelectControl
                        label={__('Link To', 'powerfolio')}
                        value={attributes.linkto}
                        options={linkToOptionsArray}
                        onChange={(newLinkTo) => setAttributes({ linkto: newLinkTo })}
                    />

                    {/* Columns */}
                    { (selectedStyle === 'box' || selectedStyle === 'masonry') && (
                        <SelectControl
                            label={__('Columns', 'powerfolio')}
                            value={attributes.columns}
                            options={columnOptionsArray}
                            onChange={(newColumns) => setAttributes({ columns: newColumns })}
                        />
                    )}

                    {/* Margin */}
                    { (selectedStyle === 'box' || selectedStyle === 'masonry' || selectedStyle === 'grid_builder') && (
                        <ToggleControl
                            label={__('Use item margin?', 'powerfolio')}
                            checked={attributes.margin}
                            onChange={(newMargin) => setAttributes({ margin: newMargin })}
                        />
                    )}                    

                    {/* PRO VERSION ONLY */}  
                    {isProVersion ? (
                        <>

                        {/* Columns Mobile */}   
                        <SelectControl
                            label={__('Columns Mobile', 'powerfolio')}
                            value={attributes.columns_mobile}
                            options={columnMobileOptionsArray}
                            onChange={(newColumnsMobile) => setAttributes({ columns_mobile: newColumnsMobile })}
                        />

                        {/* Additional Margin */}    
                        {attributes.margin && (
                            <RangeControl
                                label={__('Additional Margin (px)', 'powerfolio')}
                                value={attributes.margin_size}
                                onChange={(newMarginSize) => setAttributes({ margin_size: newMarginSize })}
                                min={0}
                                max={20}
                                step={1}
                            />
                        )}

                        {/* Zoom Effect */}   
                        <ToggleControl
                            label={__('Zoom Effect', 'powerfolio')}
                            checked={attributes.zoom_effect}
                            onChange={(newZoomEffect) => setAttributes({ zoom_effect: newZoomEffect })}
                        />                        

                        {/* Hide Item Title */}   
                        <ToggleControl
                            label={__('Hide Item Title', 'powerfolio')}
                            checked={attributes.item_hide_title}
                            onChange={(newItemHideTitle) => setAttributes({ item_hide_title: newItemHideTitle })}
                        />

                        {/* Hide Item Category */}   
                        <ToggleControl
                            label={__('Hide Item Category', 'powerfolio')}
                            checked={attributes.hide_item_category}
                            onChange={(newHideItemCategory) => setAttributes({ hide_item_category: newHideItemCategory })}
                        />

                        {/* Box Height */}
                        { (selectedStyle === 'box' || selectedStyle === 'specialgrid5' || selectedStyle === 'specialgrid6') && (
                            <RangeControl
                                label={__('Box Height (px)', 'powerfolio')}
                                value={attributes.box_height}
                                onChange={(newBoxHeight) => setAttributes({ box_height: newBoxHeight })}
                                min={10}
                                max={800}
                                step={10}
                            />
                        )}    

                        {/* Text Transform */}
                        <SelectControl
                            label={__('Text Transform', 'powerfolio')}
                            value={attributes.text_transform}
                            options={[
                                { value: '', label: __('None', 'powerfolio') },
                                { value: 'uppercase', label: __('UPPERCASE', 'powerfolio') },
                                { value: 'lowercase', label: __('lowercase', 'powerfolio') },
                                { value: 'capitalize', label: __('Capitalize', 'powerfolio') },
                            ]}
                            onChange={(newTextTransform) => setAttributes({ text_transform: newTextTransform })}
                        />

                        {/* Text Align */}
                        <SelectControl
                            label={__('Text Align', 'powerfolio')}
                            value={attributes.text_align}
                            options={[
                                { label: __('Center', 'powerfolio'), value: 'center' },
                                { label: __('Left', 'powerfolio'), value: 'left' },
                                { label: __('Right', 'powerfolio'), value: 'right' },
                            ]}
                            onChange={(value) => {
                                setAttributes({ text_align: value });
                            }}
                        />

                        {/* Border Radius */}
                        <RangeControl
                            label={__("Border Radius", "powerfolio")}
                            value={attributes.borderRadius}
                            min={0}
                            max={100}
                            onChange={(borderRadius) => setAttributes({ borderRadius })}
                        />

                        {/* Border Size */}
                        <RangeControl
                            label={__('Item: Border Size', 'powerfolio')}
                            value={attributes.border_size}
                            onChange={(value) => setAttributes({ border_size: value })}
                            min={0}
                            max={40}
                        />

                       
                        </>
                    ) : (
                        <RawHTML>{powerfolioBlockData.upgradeMessage}</RawHTML>
                    )} 

                </PanelBody>

                <PanelBody title={__('Category Filter Options', 'powerfolio')} initialOpen={false}>
                    <ToggleControl
                        label={__('Show Filter', 'powerfolio')}
                        checked={attributes.showfilter}
                        onChange={(newShowFilter) => setAttributes({ showfilter: newShowFilter })}
                    />

                    <ToggleControl
                        label={__('Show All Button', 'powerfolio')}
                        checked={attributes.showallbtn}
                        onChange={(newShowAllBtn) => setAttributes({ showallbtn: newShowAllBtn })}
                    />
                      
                    {isProVersion ? (
                        <>
                        <TextControl
                            label={__('Customize "All" button text', 'powerfolio')}
                            value={attributes.tax_text}
                            onChange={(newTaxText) => setAttributes({ tax_text: newTaxText })}
                        />  
                        <SelectControl
                            label={__('Filter: Text Transform', 'powerfolio')}
                            value={attributes.filter_text_transform}
                            options={[
                                { label: __('None', 'powerfolio'), value: '' },
                                { label: __('UPPERCASE', 'powerfolio'), value: 'uppercase' },
                                { label: __('lowercase', 'powerfolio'), value: 'lowercase' },
                                { label: __('Capitalize', 'powerfolio'), value: 'capitalize' },
                            ]}
                            onChange={(filter_text_transform) =>
                                setAttributes({ filter_text_transform })
                            }
                        />
                        <RangeControl
                            label={__('Filter: Border Radius', 'powerfolio')}
                            value={attributes.filter_border_radius}
                            onChange={(filter_border_radius) =>
                                setAttributes({ filter_border_radius })
                            }
                            min={0}
                            max={50}
                        />  
                        </>     
                    ) : (
                        <RawHTML>{powerfolioBlockData.upgradeMessage}</RawHTML>
                    )}           
                </PanelBody>

                <PanelBody title={__('Colors', 'powerfolio')} initialOpen={false}> 

                    {/* Hover */}
                    <PanelBody title={__('Item: Background Color on Hover', 'powerfolio')} initialOpen={false}>
                        <ColorPicker
                            label={__('Item: Background Color on Hover', 'powerfolio')}
                            color={attributes.bgColor}
                            onChangeComplete={(newColor) => setAttributes({ bgColor: newColor.hex })}
                        />                           
                    </PanelBody>                    


                    <PanelBody title={__('Filter: Background Color', 'powerfolio')} initialOpen={false}>
                        <ColorPicker
                            label={__('Filter: Background Color', 'powerfolio')}
                            color={attributes.filter_bgcolor}
                            onChangeComplete={(value) =>
                                setAttributes({ filter_bgcolor: value.hex })
                            }
                        />

                    </PanelBody>
                    <PanelBody title={__('Filter: Background Color (active item)', 'powerfolio')} initialOpen={false}>                           
                        <ColorPicker
                            label={__('Filter: Background Color (active item)', 'powerfolio')}
                            color={attributes.filter_bgcolor_active}
                            onChangeComplete={(value) =>
                                setAttributes({ filter_bgcolor_active: value.hex })
                            }
                        />
                    </PanelBody>

                    {/* Other PanelBody components */}
                    {isProVersion ? (
                        <>
                            {/* Border Color */}
                        <PanelBody title={__('Item: Border Color', 'powerfolio')} initialOpen={false}> 
                            <ColorPicker
                                label={__('Item: Border Color', 'powerfolio')}
                                color={attributes.item_bordercolor}
                                onChangeComplete={(value) =>
                                    setAttributes({ item_bordercolor: value.hex })
                                }
                            />
                        </PanelBody>
                        </>
                    ) : (
                        <RawHTML>{powerfolioBlockData.upgradeMessage}</RawHTML>
                    )}     
                    </PanelBody>                      
               
               
            </InspectorControls>

            <ServerSideRender
                block="powerfolio/image-gallery-block"
                attributes={{
                    hover: attributes.hover,
                    columns: attributes.columns,
                    postsperpage: attributes.postsperpage,
                    type: attributes.type,
                    showfilter: attributes.showfilter,
                    showallbtn: attributes.showallbtn,
                    tax_text: attributes.tax_text,
                    style: attributes.style,
                    margin: attributes.margin,
                    linkto: attributes.linkto,
                    bgColor: attributes.bgColor,
                    margin_size: attributes.margin_size,
                    box_height: attributes.box_height,
                    text_transform: attributes.text_transform,
                    text_align: attributes.text_align,
                    borderRadius: attributes.borderRadius,
                    border_size: attributes.border_size,
                    item_bordercolor: attributes.item_bordercolor,
                    filter_bgcolor: attributes.filter_bgcolor,
                    filter_bgcolor_active: attributes.filter_bgcolor_active,
                    filter_text_transform: attributes.filter_text_transform,
                    filter_border_radius: attributes.filter_border_radius,
                    imageIds: attributes.imageIds,
                    imageCustomUrls: attributes.imageCustomUrls,
                    imageTags: attributes.imageTags,
                    listTitle: attributes.listTitle,
                    listContent: attributes.listContent,
                    linkTo: attributes.linkTo
                }}
            />
        </div>
    );
}