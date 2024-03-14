import { __ } from '@wordpress/i18n';
import icons from '../icon/icons.json';

const Items = ( areoi, attributes, onChange, attribute_label, attribute_key, active_key ) => {

    function Items() 
    {
        function addItem()
        {
            var items = [...attributes[attribute_key]];

            var new_item = {
                'id': items.length+1,
                'heading': null,
                'introduction': null,
                'include_read_more': false,
                'include_cta': false,
                'cta': null,
                'cta_size': null,
                'url': null,
                'opensInNewTab': null,
                'heading_color': null,
                'introduction_color': null,
                'cta_color': null,
                'background_color': null,
                'video': null,
                'image': null,
                'include_icon': null,
                'icon': null,
                'icon_style': null,
                'icon_size': null,
                'icon_search': null
            };
            items.push( new_item );
            
            onChange( attribute_key, items );

            onChange( active_key, (items.length-1).toString() )
        }

        function removeItem( index )
        {
            var items = [...attributes[attribute_key]];

            items.splice( index, 1 );
            
            onChange( attribute_key, items );
        }

        function onChangeItem( index, key, value ) 
        {
            var items = [...attributes[attribute_key]];
            items[index][key] = value;
            onChange( attribute_key, items );
        }

        const IconControl = areoi.compose.compose(
            wp.data.withSelect( function( select, props ) {
                var search = props.attributes['icon_search'];
                var icons = props.icons;
                var i = props.i;
                if ( search ) {
                    icons = icons.filter(icon => icon.includes(search));
                }
                return {
                    icons,
                    i
                }
            } ) )( function( props ) {
                
                var attributes = props.attributes;
                var icons = props.icons;
                var i = props.i;

                var icon_output = [];
                icons.forEach((icon) => {

                    var key = 'icon';

                    var new_output = 
                    <div 
                        onClick={ () => onChangeItem( i, 'icon', icon ) }
                        className={ 'areoi-icon-list-item' + ( attributes[key] == icon ? ' selected' : '' ) }
                    >
                        <i className={ icon }></i> 
                        { icon }
                    </div>
                    icon_output.push( new_output );
                });

                return (
                    <div class="areoi-icon-list">
                        { icon_output }
                    </div>
                );
            }

        );

        var output = [];

        var newOutput = (
            <div className="areoi-device-specific">
                <p><strong>{ attribute_label }</strong></p>
                <p>You can manage the content that will be displayed in each of your block { attribute_label }.</p>
                
                {
                    attributes[active_key] === "" &&
                    <areoi.components.Button variant="secondary" class="button" onClick={ () => addItem() }>Add Item</areoi.components.Button>
                }                

                {
                    attributes[active_key] !== "" &&
                    <areoi.components.Button variant="primary" class="button" onClick={ () => onChange( active_key, "" ) }>Save and Preview</areoi.components.Button>
                }
            </div>
        );
        output.push( newOutput );
        
        attributes[attribute_key].forEach( ( item, i ) => {

            var title = 'Item (' + (attributes[attribute_key][i].heading ? attributes[attribute_key][i].heading.substr( 0, 15 ) + '...' : item.id) + ')';

            let iconProps = {
                className: areoi.helper.GetClassNameStr( [
                    attributes[attribute_key][i].icon_style,
                    attributes[attribute_key][i].icon,
                ] ),
                style: { 'font-size': attributes[attribute_key][i].icon_size + 'px' }
            };

            if ( attributes[active_key] !== "" ) {

                if ( attributes[active_key] != i ) {
                    return;
                }

                if ( !attributes[attribute_key][i].hasOwnProperty( 'include_icon' ) ) {
                    onChangeItem( i, 'include_icon', false )
                    onChangeItem( i, 'icon', null )
                    onChangeItem( i, 'icon_style', null )
                    onChangeItem( i, 'icon_size', null )
                    onChangeItem( i, 'icon_search', null )
                }

                var newOutput = (
                    <>
                        <areoi.components.PanelBody title={ __( 'Content: ' + title ) } initialOpen={ true }>

                            <areoi.components.BaseControl label={ __( 'Heading' ) }>
                                <areoi.editor.RichText
                                    tagName={ 'h3' }
                                    inlineToolbar={ false }
                                    value={ attributes[attribute_key][i].heading }
                                    onChange={ ( value ) => onChangeItem( i, 'heading', value ) }
                                    placeholder={ __( 'Enter heading...' ) }
                                />
                            </areoi.components.BaseControl>

                            <areoi.components.BaseControl label={ __( 'Introduction' ) }>
                                <areoi.editor.RichText
                                    tagName={ 'div' }
                                    multiline='p'
                                    inlineToolbar={ false }
                                    value={ attributes[attribute_key][i].introduction }
                                    onChange={ ( value ) => onChangeItem( i, 'introduction', value ) }
                                    placeholder={ __( 'Add a short paragraph...' ) }
                                />
                            </areoi.components.BaseControl> 

                            <areoi.components.PanelRow>
                                <areoi.components.ToggleControl 
                                    label={ __( 'Include Read More' ) }
                                    checked={ attributes[attribute_key][i].include_read_more }
                                    onChange={ ( value ) => onChangeItem( i, 'include_read_more', value ) }
                                />
                            </areoi.components.PanelRow> 

                            <areoi.components.PanelRow>
                                <areoi.components.ToggleControl 
                                    label={ __( 'Include Call to Action' ) }
                                    checked={ attributes[attribute_key][i].include_cta }
                                    onChange={ ( value ) => onChangeItem( i, 'include_cta', value ) }
                                />
                            </areoi.components.PanelRow>

                            {
                                attributes[attribute_key][i].include_cta &&
                                <>
                                    <areoi.components.BaseControl label={ __( 'Call to Action' ) }>
                                        <areoi.editor.RichText
                                                tagName={ 'p' }
                                                inlineToolbar={ false }
                                                value={ attributes[attribute_key][i].cta }
                                                onChange={ ( value ) => onChangeItem( i, 'cta', value ) }
                                                placeholder={ __( 'Add a CTA...' ) }
                                            />
                                    </areoi.components.BaseControl> 

                                    <areoi.components.PanelRow>
                                        <areoi.components.SelectControl
                                            label="Call to Action Size"
                                            labelPosition="top"
                                            help={ __( 'Use the Bootstrap button utilities to change the size of the cta.' ) }
                                            value={ attributes[attribute_key][i].cta_size }
                                            options={ [
                                                { label: 'Small', value: 'btn-sm' },
                                                { label: 'Medium', value: 'btn-md' },
                                                { label: 'Large', value: 'btn-lg' },
                                            ] }
                                            onChange={ ( value ) => onChangeItem( i, 'cta_size', value ) }
                                        />
                                    </areoi.components.PanelRow>

                                    <div className="areoi-link-control">
                                        <label class="components-truncate components-text components-input-control__label">Call to Action URL</label>
                                        <areoi.editor.__experimentalLinkControl
                                            searchInputPlaceholder="Search here..."
                                            value={ {
                                                url: attributes[attribute_key][i].url,
                                                opensInNewTab: attributes[attribute_key][i].opensInNewTab
                                            } }
                                            onChange={ ( newUrl ) => {
                                                onChangeItem( i, 'url', newUrl.url )
                                                onChangeItem( i, 'opensInNewTab', newUrl.opensInNewTab )
                                            } }
                                            onRemove={ () => {
                                                onChangeItem( i, 'url', '' )
                                                onChangeItem( i, 'opensInNewTab', false )
                                            } }
                                        >
                                        </areoi.editor.__experimentalLinkControl>
                                    </div>
                                </>
                            }

                        </areoi.components.PanelBody>

                        <areoi.components.PanelBody title={ __( 'Colors: ' + title ) } initialOpen={ false }>

                            {
                                attributes[attribute_key][i].heading &&
                                <areoi.components.PanelRow>
                                    <areoi.components.SelectControl
                                        label={ __( 'Heading Color' ) }
                                        labelPosition="top"
                                        help={ __( 'Use the Bootstrap text color utilities to change the heading color.' ) }
                                        value={ attributes[attribute_key][i].heading_color }
                                        options={ [
                                            { label: 'Default', value: "" },
                                            { label: 'Primary', value: 'text-primary' },
                                            { label: 'Secondary', value: 'text-secondary' },
                                            { label: 'Success', value: 'text-success' },
                                            { label: 'Danger', value: 'text-danger' },
                                            { label: 'Warning', value: 'text-warning' },
                                            { label: 'Info', value: 'text-info' },
                                            { label: 'Light', value: 'text-light' },
                                            { label: 'Dark', value: 'text-dark' },
                                        ] }
                                        onChange={ ( value ) => onChangeItem( i, 'heading_color', value ) }
                                    />
                                </areoi.components.PanelRow>
                            }
                            {
                                attributes[attribute_key][i].introduction &&
                                <areoi.components.PanelRow>
                                    <areoi.components.SelectControl
                                        label={ __( 'Introduction Color' ) }
                                        labelPosition="top"
                                        help={ __( 'Use the Bootstrap text color utilities to change the introduction color.' ) }
                                        value={ attributes[attribute_key][i].introduction_color }
                                        options={ [
                                            { label: 'Default', value: "" },
                                            { label: 'Primary', value: 'text-primary' },
                                            { label: 'Secondary', value: 'text-secondary' },
                                            { label: 'Success', value: 'text-success' },
                                            { label: 'Danger', value: 'text-danger' },
                                            { label: 'Warning', value: 'text-warning' },
                                            { label: 'Info', value: 'text-info' },
                                            { label: 'Light', value: 'text-light' },
                                            { label: 'Dark', value: 'text-dark' },
                                        ] }
                                        onChange={ ( value ) => onChangeItem( i, 'introduction_color', value ) }
                                    />
                                </areoi.components.PanelRow>
                            }
                            {
                                attributes[attribute_key][i].cta &&
                                <areoi.components.PanelRow>
                                    <areoi.components.SelectControl
                                        label={ __( 'Call to Action Color' ) }
                                        labelPosition="top"
                                        help={ __( 'Use the Bootstrap text color utilities to change the cta color.' ) }
                                        value={ attributes[attribute_key][i].cta_color }
                                        options={ [
                                            { label: 'Default', value: "" },
                                            { label: 'Primary', value: 'btn-primary' },
                                            { label: 'Secondary', value: 'btn-secondary' },
                                            { label: 'Success', value: 'btn-success' },
                                            { label: 'Danger', value: 'btn-danger' },
                                            { label: 'Warning', value: 'btn-warning' },
                                            { label: 'Info', value: 'btn-info' },
                                            { label: 'Light', value: 'btn-light' },
                                            { label: 'Dark', value: 'btn-dark' },
                                        ] }
                                        onChange={ ( value ) => onChangeItem( i, 'cta_color', value ) }
                                    />
                                </areoi.components.PanelRow>
                            }

                            <areoi.components.PanelRow>
                                <areoi.components.SelectControl
                                    label={ __( 'Background Color' ) }
                                    labelPosition="top"
                                    help={ __( 'Use the Bootstrap bg color utilities to change the background color.' ) }
                                    value={ attributes[attribute_key][i].background_color }
                                    options={ [
                                        { label: 'Default', value: "" },
                                        { label: 'Primary', value: 'bg-primary' },
                                        { label: 'Secondary', value: 'bg-secondary' },
                                        { label: 'Success', value: 'bg-success' },
                                        { label: 'Danger', value: 'bg-danger' },
                                        { label: 'Warning', value: 'bg-warning' },
                                        { label: 'Info', value: 'bg-info' },
                                        { label: 'Light', value: 'bg-light' },
                                        { label: 'Dark', value: 'bg-dark' },
                                    ] }
                                    onChange={ ( value ) => onChangeItem( i, 'background_color', value ) }
                                />
                            </areoi.components.PanelRow>

                        </areoi.components.PanelBody>

                        <areoi.components.PanelBody title={ __( 'Media: ' + title ) } initialOpen={ false }>
                                    
                            { areoi.ItemMediaUpload( areoi, attributes, onChangeItem, 'Image', 'image', 'image', i, attribute_key ) }

                            { areoi.ItemMediaUpload( areoi, attributes, onChangeItem, 'Video', 'video', 'video', i, attribute_key ) }
                            
                            <areoi.components.PanelRow>
                                <areoi.components.ToggleControl 
                                    label={ __( 'Include Icon' ) }
                                    checked={ attributes[attribute_key][i].include_icon }
                                    onChange={ ( value ) => onChangeItem( i, 'include_icon', value ) }
                                />
                            </areoi.components.PanelRow> 

                            {
                                attributes[attribute_key][i].include_icon &&
                                <>
                                    <areoi.components.PanelRow className="areoi-panel-row">
                                        <areoi.components.SelectControl
                                            label="Style"
                                            labelPosition="top"
                                            help="Choose the colour of your icon from the available theme colours."
                                            value={ attributes[attribute_key][i].icon_style }
                                            options={ [
                                                { label: 'Default', value: 'text-primary' },
                                                { label: 'Primary', value: 'text-primary' },
                                                { label: 'Secondary', value: 'text-secondary' },
                                                { label: 'Success', value: 'text-success' },
                                                { label: 'Danger', value: 'text-danger' },
                                                { label: 'Warning', value: 'text-warning' },
                                                { label: 'Info', value: 'text-info' },
                                                { label: 'Light', value: 'text-light' },
                                                { label: 'Dark', value: 'text-dark' },
                                            ] }
                                            onChange={ ( value ) => onChangeItem( i, 'icon_style', value ) }
                                        />
                                    </areoi.components.PanelRow>

                                    <areoi.components.PanelRow className="areoi-panel-row">
                                        <areoi.components.SelectControl
                                            label="Size"
                                            labelPosition="top"
                                            help="Choose the size to diaply your icon. Eaxtra small is 12px, Small is 24px, Medium is 36px, Large is 48px, Extra Large is 60px and Extra Extra Large is 80px."
                                            value={ attributes[attribute_key][i].icon_size }
                                            options={ [
                                                { label: 'Extra Small', value: '12' },
                                                { label: 'Small', value: '24' },
                                                { label: 'Medium', value: '36' },
                                                { label: 'Large', value: '48' },
                                                { label: 'Extra Large', value: '60' },
                                                { label: 'Extra Extra Large', value: '80' },
                                            ] }
                                            onChange={ ( value ) => onChangeItem( i, 'icon_size', value ) }
                                        />
                                    </areoi.components.PanelRow>

                                    <div class="components-panel__row">
                                        <div className="components-base-control">
                                            <label>Icon</label>
                                            
                                            <areoi.components.TextControl className="areoi-icon-base-control"
                                                placeholder="Search Icons"
                                                labelPosition="top"
                                                help=""
                                                value={ attributes[attribute_key][i].icon_search }
                                                onChange={ ( value ) => onChangeItem( i, 'icon_search', value ) }
                                            />

                                            {
                                                attributes[attribute_key][i].icon && 
                                                <div className={ 'areoi-icon-list-item selected highlighted' }>
                                                    <i className={ attributes[attribute_key][i].icon }></i>
                                                    { attributes[attribute_key][i].icon }
                                                </div>
                                            }
                                        </div>
                                    </div>
                                    <IconControl attributes={attributes[attribute_key][i]} icons={icons} i={i} />
                                </>
                            }   

                        </areoi.components.PanelBody>

                    </>
                );
                output.push( newOutput );

            } else {
                var newOutput = (
                    <div class="item areoi-gallery-item">
                        <div class="areoi-galery-item-label">
                            <p>{ title }</p>
                            
                            <a href="#" onClick={ () => {
                                
                                onChange( active_key, i.toString() )

                            } }>Edit Item</a>

                            <a href="#" className="areoi-remove-link" onClick={ () => {

                                var items = [...attributes[attribute_key]];
                                items.splice( i, 1 );
                                
                                onChange( attribute_key, items )

                            } }>Remove Item</a>
                        </div>
                        <div class="areoi-galery-item-arrows">
                            <button onClick={ () => {

                                var items = [...attributes[attribute_key]];
                                var to = i;
                                var from = i-1;

                                if ( from < 0 ) {
                                    from = 0;
                                }
                                
                                items.splice(to, 0, items.splice(from, 1)[0]);
                                
                                onChange( attribute_key, items )

                            } }>
                                <svg width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" class="components-panel__arrow" aria-hidden="true" focusable="false"><path d="M6.5 12.4L12 8l5.5 4.4-.9 1.2L12 10l-4.5 3.6-1-1.2z"></path></svg>
                            </button>

                            <button onClick={ () => {

                                var items = [...attributes[attribute_key]];
                                var to = i;
                                var from = i+1;

                                if ( from < 0 ) {
                                    from = 0;
                                }
                                
                                items.splice(to, 0, items.splice(from, 1)[0]);
                                
                                onChange( attribute_key, items )

                            } }>
                                <svg width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" class="components-panel__arrow" aria-hidden="true" focusable="false"><path d="M17.5 11.6L12 16l-5.5-4.4.9-1.2L12 14l4.5-3.6 1 1.2z"></path></svg>
                            </button>
                        </div>
                    </div>
                );

                output.push( newOutput );
            }
        } );

        var newOutput = (
            <div className="areoi-device-specific">
                <strong>End { attribute_label }</strong>
            </div>
        );
        output.push( newOutput );

        return output;
    }

    return (
        <div className="areoi-gallery-container">
        <div className="areoi-gallery areoi-gallery-items">
                { Items() }
            </div>
        </div>
    );
}

export default Items;