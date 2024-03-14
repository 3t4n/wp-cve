/**
 * Includes the settings of style tab.
 * 
 */
import ConvertGoogleFontVariant from '../../block-base/block-base';
import googlefonts from '../../block-base/googlefonts.json';

const { Component, Fragment } = wp.element;
const { __ } = wp.i18n;
const { escapeHTML } = wp.escapeHtml;
const { PanelBody, TextControl, SelectControl, ToggleControl, RangeControl, ColorPalette, Button } = wp.components;

export default class StyleInspector extends Component {
    constructor( props ) {
        super( ...arguments );
        this.state = {
            google_fonts: []
        }
    }

    componentDidMount() {
        if( Array.isArray( googlefonts ) && googlefonts.length ) {
            this.setState({ google_fonts: googlefonts })
        }
    }

    render() {
        const { blockLayout, blockPrimaryColor, blockHoverColor, typographyOption, blockTitleFontFamily, blockTitleFontSize, blockTitleFontStyle, blockTitleTextTransform, blockTitleTextDecoration, blockTitleColor, blockTitleLineHeight, blockTitleBorderColor, titleTextAlign, titleFontFamily, titleFontSize, titleFontStyle, titleTextTransform, titleTextDecoration, titleFontColor, titleHoverColor, titlelineHeight, descTextAlign, descFontFamily, descFontSize, descFontStyle, descTextTransform, descTextDecoration, descFontColor, desclineHeight, button1TextAlign, button1FontFamily, button1FontSize, button1TextTransform, button1FontColor, button1HoverColor, button1BackgroundColor, button1BackgroundHoverColor, button1PaddingTop, button1PaddingRight, button1PaddingBottom, button1PaddingLeft, button1BorderType, button1BorderWeight, button1BorderColor, button1BorderHoverColor, button2TextAlign, button2FontFamily, button2FontSize, button2TextTransform, button2FontColor, button2HoverColor, button2BackgroundColor, button2BackgroundHoverColor, button2PaddingTop, button2PaddingRight, button2PaddingBottom, button2PaddingLeft, button2BorderType, button2BorderWeight, button2BorderColor, button2BorderHoverColor } = this.props.attributes;
        let { blockTitleFontWeight, titleFontWeight, descFontWeight, button1FontWeight, button2FontWeight } = this.props.attributes;
        const { setAttributes } = this.props
        
        const google_fonts = this.state.google_fonts
        let googleFontsOptions = google_fonts.map( ( google_font, fontindex ) => {
            return { value: google_font.family, label: google_font.family }
        })

        const colors = [
            { color: '#26C6DA' },
            { color: '#D32F2F' },
            { color: '#2196F3' },
            { color: '#43A047' },
            { color: '#F4511E' },
        ];
        
        function setfontWeight( FontFamily, google_fonts ) {
            let i;
            let googleFontWeight;
            for( i=0; i<google_fonts.length; i++  ) {
                if( google_fonts[i].family === FontFamily ) {
                    const variants = google_fonts[i].variants
                    googleFontWeight = variants.map( ( variant ) => {
                        let converted_variant = ConvertGoogleFontVariant( variant )
                        return { value: converted_variant, label: converted_variant }
                    })
                    break;
                }
            }
            return googleFontWeight;
        }

        return (
            <Fragment>
                <PanelBody title={ escapeHTML( __( 'Layout Settings', 'wp-magazine-modules-lite' ) ) }>
                    <div className="cvmm-layout-button-group">
                        <div>
                            <label>{ escapeHTML( __( 'Layouts', 'wp-magazine-modules-lite' ) ) }</label>
                        </div>
                        <div>
                            <Button className={ `${( blockLayout == 'layout-default') ? "isActive" : "" }` } onClick={ ( e ) => setAttributes( { blockLayout: 'layout-default' } ) } ><img src={ BlocksBuildObject.bannerLayoutDefault } /></Button>
                            <Button className={ `${( blockLayout == 'layout-one') ? "isActive" : "" }` } onClick={ ( e ) => setAttributes( { blockLayout: 'layout-one' } ) }><img src={ BlocksBuildObject.bannerLayoutOne } /></Button>
                            
                        </div>
                    </div>
                </PanelBody>
                <PanelBody title={ escapeHTML( __( 'Color Settings', 'wp-magazine-modules-lite' ) ) } initialOpen = { false } >
                    <div className="wpmagazine-modules-lite-color-seetings-tab">
                        <label>{ escapeHTML( __( 'Primary Color', 'wp-magazine-modules-lite' ) ) }</label>
                        <ColorPalette
                            colors={colors}
                            value={ blockPrimaryColor }
                            onChange={ ( newblockPrimaryColor ) => setAttributes( { blockPrimaryColor: newblockPrimaryColor } ) }
                        />
                        <label>{ escapeHTML( __( 'Hover Color', 'wp-magazine-modules-lite' ) ) }</label>
                        <ColorPalette
                            colors={colors}
                            value={ blockHoverColor }
                            onChange={ ( newblockHoverColor ) => setAttributes( { blockHoverColor: newblockHoverColor } ) }
                        />
                    </div>
                </PanelBody>
                <PanelBody title={ escapeHTML( __( 'Typography Settings', 'wp-magazine-modules-lite' ) ) } initialOpen = { false } >
                    <ToggleControl
                        label={ escapeHTML( __( 'Inherit default from plugin typography', 'wp-magazine-modules-lite' ) ) }
                        checked={ typographyOption }
                        onChange={ ( newtypographyOption ) => setAttributes( { typographyOption: newtypographyOption } ) }
                    />
                    <PanelBody className="cvmm-editor-component-sub-panel_body" title={ escapeHTML( __( 'Block Title', 'wp-magazine-modules-lite' ) ) } initialOpen = { false } >
                        <SelectControl
                            label={ escapeHTML( __( 'Font Family', 'wp-magazine-modules-lite' ) ) }
                            value={ blockTitleFontFamily }
                            options={ googleFontsOptions }
                            onChange={ ( newblockTitleFontFamily ) => setAttributes( { blockTitleFontFamily: newblockTitleFontFamily,blockTitleFontWeight: setfontWeight( newblockTitleFontFamily, google_fonts )[0].value } ) }
                        />
                        { blockTitleFontFamily &&
                            <SelectControl
                                label={ escapeHTML( __( 'Font Weight', 'wp-magazine-modules-lite' ) ) }
                                value={ blockTitleFontWeight }
                                options={ setfontWeight( blockTitleFontFamily, google_fonts ) }
                                onChange={ ( newblockTitleFontWeight ) => setAttributes( { blockTitleFontWeight: newblockTitleFontWeight } ) }
                            />
                        }
                        <RangeControl
                            label={ escapeHTML( __( 'Font Size', 'wp-magazine-modules-lite' ) ) }
                            value={ blockTitleFontSize }
                            onChange={ ( newblockTitleFontSize ) => setAttributes( { blockTitleFontSize: newblockTitleFontSize } ) }
                            min={ 1 }
                            max={ 200 }
                            allowReset={ true }
                            initialPosition = { 0 }
                        />
                        <SelectControl
                            label={ escapeHTML( __( 'Font Style', 'wp-magazine-modules-lite' ) ) }
                            value={ blockTitleFontStyle }
                            options={ [
                                { value: 'initial', label: escapeHTML( __( 'Default', 'wp-magazine-modules-lite' ) ) },
                                { value: 'normal', label: escapeHTML( __( 'Normal', 'wp-magazine-modules-lite' ) ) },
                                { value: 'italic', label: escapeHTML( __( 'Italic', 'wp-magazine-modules-lite' ) ) },
                                { value: 'oblique', label: escapeHTML( __( 'Oblique', 'wp-magazine-modules-lite' ) ) }
                            ] }
                            onChange={ ( newblockTitleFontStyle ) => setAttributes( { blockTitleFontStyle: newblockTitleFontStyle } ) }
                        />
                        <SelectControl
                            label={ escapeHTML( __( 'Text Transform', 'wp-magazine-modules-lite' ) ) }
                            value={ blockTitleTextTransform }
                            options={ [
                                { value: 'none', label: escapeHTML( __( 'Default', 'wp-magazine-modules-lite' ) ) },
                                { value: 'uppercase', label: escapeHTML( __( 'Uppercase', 'wp-magazine-modules-lite' ) ) },
                                { value: 'lowercase', label: escapeHTML( __( 'Lowercase', 'wp-magazine-modules-lite' ) ) },
                                { value: 'capitalize', label: escapeHTML( __( 'Capitalize', 'wp-magazine-modules-lite' ) ) }
                            ] }
                            onChange={ ( newblockTitleTextTransform ) => setAttributes( { blockTitleTextTransform: newblockTitleTextTransform } ) }
                        />
                        <SelectControl
                            label={ escapeHTML( __( 'Text Decoration', 'wp-magazine-modules-lite' ) ) }
                            value={ blockTitleTextDecoration }
                            options={ [
                                { value: 'none', label: escapeHTML( __( 'None', 'wp-magazine-modules-lite' ) ) },
                                { value: 'underline', label: escapeHTML( __( 'Underline', 'wp-magazine-modules-lite' ) ) },
                                { value: 'line-through', label: escapeHTML( __( 'Line-through', 'wp-magazine-modules-lite' ) ) },
                                { value: 'overline', label: escapeHTML( __( 'Overline', 'wp-magazine-modules-lite' ) ) }
                            ] }
                            onChange={ ( newblockTitleTextDecoration ) => setAttributes( { blockTitleTextDecoration: newblockTitleTextDecoration } ) }
                        />
                        <div class="cvmm-block-title-color-wrap">
                            <label>{ escapeHTML( __( 'Font Color', 'wp-magazine-modules-lite' ) ) }</label>
                            <ColorPalette
                                colors={colors}
                                value={ blockTitleColor }
                                onChange={ ( newblockTitleColor ) => setAttributes( { blockTitleColor: newblockTitleColor } ) }
                            />
                            <label>{ escapeHTML( __( 'Border Color', 'wp-magazine-modules-lite' ) ) }</label>
                            <ColorPalette
                                colors={colors}
                                value={ blockTitleBorderColor }
                                onChange={ ( newblockTitleBorderColor ) => setAttributes( { blockTitleBorderColor: newblockTitleBorderColor } ) }
                            />
                        </div>
                        <RangeControl
                            label={ escapeHTML( __( 'Line Height', 'wp-magazine-modules-lite' ) ) }
                            value={ blockTitleLineHeight }
                            onChange={ ( newblockTitleLineHeight ) => setAttributes( { blockTitleLineHeight: newblockTitleLineHeight } ) }
                            step={ 0.1 }
                            min={ 0.1 }
                            max={ 10 }
                            allowReset={ true }
                        />
                    </PanelBody>
                    <PanelBody className="cvmm-editor-component-sub-panel_body" title={ escapeHTML( __( 'Title', 'wp-magazine-modules-lite' ) ) } initialOpen = { false } >
                        <SelectControl
                            label={ escapeHTML( __( 'Text Align', 'wp-magazine-modules-lite' ) ) }
                            value={ titleTextAlign }
                            options={ [
                                { value: 'left', label: escapeHTML( __( 'Left', 'wp-magazine-modules-lite' ) ) },
                                { value: 'center', label: escapeHTML( __( 'Center', 'wp-magazine-modules-lite' ) ) },
                                { value: 'right', label: escapeHTML( __( 'Right', 'wp-magazine-modules-lite' ) ) }
                            ] }
                            onChange={ ( newtitleTextAlign ) => setAttributes( { titleTextAlign: newtitleTextAlign } ) }
                        />
                        <SelectControl
                            label={ escapeHTML( __( 'Font Family', 'wp-magazine-modules-lite' ) ) }
                            value={ titleFontFamily }
                            options={ googleFontsOptions }
                            onChange={ ( newtitleFontFamily ) => setAttributes( { titleFontFamily: newtitleFontFamily, titleFontWeight: setfontWeight( newtitleFontFamily, google_fonts )[0].value } ) }
                        />
                        { titleFontFamily &&
                            <SelectControl
                                label={ escapeHTML( __( 'Font Weight', 'wp-magazine-modules-lite' ) ) }
                                value={ titleFontWeight }
                                options={ setfontWeight( titleFontFamily, google_fonts ) }
                                onChange={ ( newtitleFontWeight ) => setAttributes( { titleFontWeight: newtitleFontWeight } ) }
                            />
                        }
                        <RangeControl
                            label={ escapeHTML( __( 'Font Size', 'wp-magazine-modules-lite' ) ) }
                            value={ titleFontSize }
                            onChange={ ( newtitleFontSize ) => setAttributes( { titleFontSize: newtitleFontSize } ) }
                            min={ 1 }
                            max={ 200 }
                            allowReset={ true }
                            initialPosition = { 0 }
                        />
                        <SelectControl
                            label={ escapeHTML( __( 'Font Style', 'wp-magazine-modules-lite' ) ) }
                            value={ titleFontStyle }
                            options={ [
                                { value: 'initial', label: escapeHTML( __( 'Default', 'wp-magazine-modules-lite' ) ) },
                                { value: 'normal', label: escapeHTML( __( 'Normal', 'wp-magazine-modules-lite' ) ) },
                                { value: 'italic', label: escapeHTML( __( 'Italic', 'wp-magazine-modules-lite' ) ) },
                                { value: 'oblique', label: escapeHTML( __( 'Oblique', 'wp-magazine-modules-lite' ) ) }
                            ] }
                            onChange={ ( newtitleFontStyle ) => setAttributes( { titleFontStyle: newtitleFontStyle } ) }
                        />
                        <SelectControl
                            label={ escapeHTML( __( 'Text Transform', 'wp-magazine-modules-lite' ) ) }
                            value={ titleTextTransform }
                            options={ [
                                { value: 'none', label: escapeHTML( __( 'Default', 'wp-magazine-modules-lite' ) ) },
                                { value: 'uppercase', label: escapeHTML( __( 'Uppercase', 'wp-magazine-modules-lite' ) ) },
                                { value: 'lowercase', label: escapeHTML( __( 'Lowercase', 'wp-magazine-modules-lite' ) ) },
                                { value: 'capitalize', label: escapeHTML( __( 'Capitalize', 'wp-magazine-modules-lite' ) ) }
                            ] }
                            onChange={ ( newtitleTextTransform ) => setAttributes( { titleTextTransform: newtitleTextTransform } ) }
                        />
                        <SelectControl
                            label={ escapeHTML( __( 'Text Decoration', 'wp-magazine-modules-lite' ) ) }
                            value={ titleTextDecoration }
                            options={ [
                                { value: 'none', label: escapeHTML( __( 'None', 'wp-magazine-modules-lite' ) ) },
                                { value: 'underline', label: escapeHTML( __( 'Underline', 'wp-magazine-modules-lite' ) ) },
                                { value: 'line-through', label: escapeHTML( __( 'Line-through', 'wp-magazine-modules-lite' ) ) },
                                { value: 'overline', label: escapeHTML( __( 'Overline', 'wp-magazine-modules-lite' ) ) }
                            ] }
                            onChange={ ( newtitleTextDecoration ) => setAttributes( { titleTextDecoration: newtitleTextDecoration } ) }
                        />
                        <div class="cvmm-title-color-wrap">
                            <label>{ escapeHTML( __( 'Font Color', 'wp-magazine-modules-lite' ) ) }</label>
                            <ColorPalette
                                colors={colors}
                                value={ titleFontColor }
                                onChange={ ( newtitleFontColor ) => setAttributes( { titleFontColor: newtitleFontColor } ) }
                            />
                            <label>{ escapeHTML( __( 'Hover Color', 'wp-magazine-modules-lite' ) ) }</label>
                            <ColorPalette
                                colors={colors}
                                value={ titleHoverColor }
                                onChange={ ( newtitleHoverColor ) => setAttributes( { titleHoverColor: newtitleHoverColor } ) }
                            />
                        </div>
                        <RangeControl
                            label={ escapeHTML( __( 'Line Height', 'wp-magazine-modules-lite' ) ) }
                            value={ titlelineHeight }
                            onChange={ ( newtitlelineHeight ) => setAttributes( { titlelineHeight: newtitlelineHeight } ) }
                            step={ 0.1 }
                            min={ 0.1 }
                            max={ 10 }
                            allowReset={ true }
                        />
                    </PanelBody>

                    <PanelBody className="cvmm-editor-component-sub-panel_body" title={ escapeHTML( __( 'Description', 'wp-magazine-modules-lite' ) ) } initialOpen = { false } >
                        <SelectControl
                            label={ escapeHTML( __( 'Text Align', 'wp-magazine-modules-lite' ) ) }
                            value={ descTextAlign }
                            options={ [
                                { value: 'left', label: escapeHTML( __( 'Left', 'wp-magazine-modules-lite' ) ) },
                                { value: 'center', label: escapeHTML( __( 'Center', 'wp-magazine-modules-lite' ) ) },
                                { value: 'right', label: escapeHTML( __( 'Right', 'wp-magazine-modules-lite' ) ) }
                            ] }
                            onChange={ ( newdescTextAlign ) => setAttributes( { descTextAlign: newdescTextAlign } ) }
                        />
                        <SelectControl
                            label={ escapeHTML( __( 'Font Family', 'wp-magazine-modules-lite' ) ) }
                            value={ descFontFamily }
                            options={ googleFontsOptions }
                            onChange={ ( newdescFontFamily ) => setAttributes( { descFontFamily: newdescFontFamily, descFontWeight: setfontWeight( newdescFontFamily, google_fonts )[0].value } ) }
                        />
                        { descFontFamily &&
                            <SelectControl
                                label={ escapeHTML( __( 'Font Weight', 'wp-magazine-modules-lite' ) ) }
                                value={ descFontWeight }
                                options={ setfontWeight( descFontFamily, google_fonts ) }
                                onChange={ ( newdescFontWeight ) => setAttributes( { descFontWeight: newdescFontWeight } ) }
                            />
                        }
                        <RangeControl
                            label={ escapeHTML( __( 'Font Size', 'wp-magazine-modules-lite' ) ) }
                            value={ descFontSize }
                            onChange={ ( newdescFontSize ) => setAttributes( { descFontSize: newdescFontSize } ) }
                            min={ 1 }
                            max={ 200 }
                            allowReset={ true }
                            initialPosition = { 0 }
                        />
                        <SelectControl
                            label={ escapeHTML( __( 'Font Style', 'wp-magazine-modules-lite' ) ) }
                            value={ descFontStyle }
                            options={ [
                                { value: 'initial', label: escapeHTML( __( 'Default', 'wp-magazine-modules-lite' ) ) },
                                { value: 'normal', label: escapeHTML( __( 'Normal', 'wp-magazine-modules-lite' ) ) },
                                { value: 'italic', label: escapeHTML( __( 'Italic', 'wp-magazine-modules-lite' ) ) },
                                { value: 'oblique', label: escapeHTML( __( 'Oblique', 'wp-magazine-modules-lite' ) ) }
                            ] }
                            onChange={ ( newdescFontStyle ) => setAttributes( { descFontStyle: newdescFontStyle } ) }
                        />
                        <SelectControl
                            label={ escapeHTML( __( 'Text Transform', 'wp-magazine-modules-lite' ) ) }
                            value={ descTextTransform }
                            options={ [
                                { value: 'none', label: escapeHTML( __( 'Default', 'wp-magazine-modules-lite' ) ) },
                                { value: 'uppercase', label: escapeHTML( __( 'Uppercase', 'wp-magazine-modules-lite' ) ) },
                                { value: 'lowercase', label: escapeHTML( __( 'Lowercase', 'wp-magazine-modules-lite' ) ) },
                                { value: 'capitalize', label: escapeHTML( __( 'Capitalize', 'wp-magazine-modules-lite' ) ) }
                            ] }
                            onChange={ ( newdescTextTransform ) => setAttributes( { descTextTransform: newdescTextTransform } ) }
                        />
                        <SelectControl
                            label={ escapeHTML( __( 'Text Decoration', 'wp-magazine-modules-lite' ) ) }
                            value={ descTextDecoration }
                            options={ [
                                { value: 'none', label: escapeHTML( __( 'None', 'wp-magazine-modules-lite' ) ) },
                                { value: 'underline', label: escapeHTML( __( 'Underline', 'wp-magazine-modules-lite' ) ) },
                                { value: 'line-through', label: escapeHTML( __( 'Line-through', 'wp-magazine-modules-lite' ) ) },
                                { value: 'overline', label: escapeHTML( __( 'Overline', 'wp-magazine-modules-lite' ) ) }
                            ] }
                            onChange={ ( newdescTextDecoration ) => setAttributes( { descTextDecoration: newdescTextDecoration } ) }
                        />
                        <ColorPalette
                            colors={colors}
                            value={ descFontColor }
                            onChange={ ( newdescFontColor ) => setAttributes( { descFontColor: newdescFontColor } ) }
                        />
                        <RangeControl
                            label={ escapeHTML( __( 'Line Height', 'wp-magazine-modules-lite' ) ) }
                            value={ desclineHeight }
                            onChange={ ( newdesclineHeight ) => setAttributes( { desclineHeight: newdesclineHeight } ) }
                            step={ 0.1 }
                            min={ 0.1 }
                            max={ 10 }
                            allowReset={ true }
                        />
                    </PanelBody>

                    <PanelBody className="cvmm-editor-component-sub-panel_body" title={ escapeHTML( __( 'Button One', 'wp-magazine-modules-lite' ) ) } initialOpen = { false } >
                        <SelectControl
                            label={ escapeHTML( __( 'Text Align', 'wp-magazine-modules-lite' ) ) }
                            value={ button1TextAlign }
                            options={ [
                                { value: 'left', label: escapeHTML( __( 'Left', 'wp-magazine-modules-lite' ) ) },
                                { value: 'center', label: escapeHTML( __( 'Center', 'wp-magazine-modules-lite' ) ) },
                                { value: 'right', label: escapeHTML( __( 'Right', 'wp-magazine-modules-lite' ) ) }
                            ] }
                            onChange={ ( newbutton1TextAlign ) => setAttributes( { button1TextAlign: newbutton1TextAlign  } ) }
                        />
                        <SelectControl
                            label={ escapeHTML( __( 'Font Family', 'wp-magazine-modules-lite' ) ) }
                            value={ button1FontFamily }
                            options={ googleFontsOptions }
                            onChange={ ( newbutton1FontFamily ) => setAttributes( { button1FontFamily: newbutton1FontFamily, button1FontWeight: setfontWeight( newbutton1FontFamily, google_fonts )[0].value } ) }
                        />
                        { button1FontFamily &&
                            <SelectControl
                                label={ escapeHTML( __( 'Font Weight', 'wp-magazine-modules-lite' ) ) }
                                value={ button1FontWeight }
                                options={ setfontWeight( button1FontFamily, google_fonts ) }
                                onChange={ ( newbutton1FontWeight ) => setAttributes( { button1FontWeight: newbutton1FontWeight } ) }
                            />
                        }
                        <RangeControl
                            label={ escapeHTML( __( 'Font Size', 'wp-magazine-modules-lite' ) ) }
                            value={ button1FontSize }
                            onChange={ ( newbutton1FontSize ) => setAttributes( { button1FontSize: newbutton1FontSize } ) }
                            min={ 1 }
                            max={ 200 }
                            allowReset={ true }
                            initialPosition = { 0 }
                        />
                        <SelectControl
                            label={ escapeHTML( __( 'Text Transform', 'wp-magazine-modules-lite' ) ) }
                            value={ button1TextTransform }
                            options={ [
                                { value: 'none', label: escapeHTML( __( 'Default', 'wp-magazine-modules-lite' ) ) },
                                { value: 'uppercase', label: escapeHTML( __( 'Uppercase', 'wp-magazine-modules-lite' ) ) },
                                { value: 'lowercase', label: escapeHTML( __( 'Lowercase', 'wp-magazine-modules-lite' ) ) },
                                { value: 'capitalize', label: escapeHTML( __( 'Capitalize', 'wp-magazine-modules-lite' ) ) }
                            ] }
                            onChange={ ( newbutton1TextTransform ) => setAttributes( { button1TextTransform: newbutton1TextTransform } ) }
                        />
                        <label for={button1FontColor}>{ escapeHTML( __( 'Font Color', 'wp-magazine-modules-lite' ) ) }</label>
                        <ColorPalette
                            colors={colors}
                            value={ button1FontColor }
                            onChange={ ( newbutton1FontColor ) => setAttributes( { button1FontColor: newbutton1FontColor } ) }
                        />
                        <label for={button1HoverColor}>{ escapeHTML( __( 'Hover Font Color', 'wp-magazine-modules-lite' ) ) }</label>
                        <ColorPalette
                            colors={colors}
                            value={ button1HoverColor }
                            onChange={ ( newbutton1HoverColor ) => setAttributes( { button1HoverColor: newbutton1HoverColor } ) }
                        />
                        <label for={button1BackgroundColor}>{ escapeHTML( __( 'Background Color', 'wp-magazine-modules-lite' ) ) }</label>
                        <ColorPalette
                            colors={colors}
                            value={ button1BackgroundColor }
                            onChange={ ( newbutton1BackgroundColor ) => setAttributes( { button1BackgroundColor: newbutton1BackgroundColor } ) }
                        />
                        <label for={button1BackgroundHoverColor}>{ escapeHTML( __( 'Background Hover Color', 'wp-magazine-modules-lite' ) ) }</label>
                        <ColorPalette
                            colors={colors}
                            value={ button1BackgroundHoverColor }
                            onChange={ ( newbutton1BackgroundHoverColor ) => setAttributes( { button1BackgroundHoverColor: newbutton1BackgroundHoverColor } ) }
                        />
                        <div class="cvmm-padding-control-wrap">
                            <label for="button-padding">{ escapeHTML( __( 'Padding', 'wp-magazine-modules-lite' ) ) }</label>
                            <TextControl
                                label={ escapeHTML( __( 'Top', 'wp-magazine-modules-lite' ) ) }
                                type="number"
                                min={ 0 }
                                max={ 100 }
                                value={ button1PaddingTop }
                                onChange={ ( newbutton1PaddingTop ) => setAttributes( { button1PaddingTop: newbutton1PaddingTop } ) }
                            />
                            <TextControl
                                label={ escapeHTML( __( 'Right', 'wp-magazine-modules-lite' ) ) }
                                type="number"
                                min={ 0 }
                                max={ 100 }
                                value={ button1PaddingRight }
                                onChange={ ( newbutton1PaddingRight ) => setAttributes( { button1PaddingRight: newbutton1PaddingRight } ) }
                            />
                            <TextControl
                                label={ escapeHTML( __( 'Bottom', 'wp-magazine-modules-lite' ) ) }
                                type="number"
                                min={ 0 }
                                max={ 100 }
                                value={ button1PaddingBottom }
                                onChange={ ( newbutton1PaddingBottom ) => setAttributes( { button1PaddingBottom: newbutton1PaddingBottom } ) }
                            />
                            <TextControl
                                label={ escapeHTML( __( 'Left', 'wp-magazine-modules-lite' ) ) }
                                type="number"
                                min={ 0 }
                                max={ 100 }
                                value={ button1PaddingLeft }
                                onChange={ ( newbutton1PaddingLeft ) => setAttributes( { button1PaddingLeft: newbutton1PaddingLeft } ) }
                            />
                        </div>
                        <SelectControl
                            label={ escapeHTML( __( 'Border Type', 'wp-magazine-modules-lite' ) ) }
                            value={ button1BorderType }
                            options={ [
                                { value: 'none', label: 'None' },
                                { value: 'solid', label: 'Solid' },
                                { value: 'dotted', label: 'Dotted' },
                                { value: 'dashed', label: 'Dashed' }
                            ] }
                            onChange={ ( newbutton1BorderType ) => setAttributes( { button1BorderType: newbutton1BorderType } ) }
                        />
                        <TextControl
                            label={ escapeHTML( __( 'Border Weight', 'wp-magazine-modules-lite' ) ) }
                            type="number"
                            min={ 0 }
                            max={ 10 }
                            value={ button1BorderWeight }
                            onChange={ ( newbutton1BorderWeight ) => setAttributes( { button1BorderWeight: newbutton1BorderWeight } ) }
                        />
                        <label for={button1BorderColor}>{ escapeHTML( __( 'Border Color', 'wp-magazine-modules-lite' ) ) }</label>
                        <ColorPalette
                            colors={colors}
                            value={ button1BorderColor }
                            onChange={ ( newbutton1BorderColor ) => setAttributes( { button1BorderColor: newbutton1BorderColor } ) }
                        />
                        <label for={button1BorderHoverColor}>{ escapeHTML( __( 'Border Hover Color', 'wp-magazine-modules-lite' ) ) }</label>
                        <ColorPalette
                            colors={colors}
                            value={ button1BorderHoverColor }
                            onChange={ ( newbutton1BorderHoverColor ) => setAttributes( { button1BorderHoverColor: newbutton1BorderHoverColor } ) }
                        />
                    </PanelBody>

                    <PanelBody className="cvmm-editor-component-sub-panel_body" title={ escapeHTML( __( 'Button Two', 'wp-magazine-modules-lite' ) ) } initialOpen = { false } >
                        <SelectControl
                            label={ escapeHTML( __( 'Text Align', 'wp-magazine-modules-lite' ) ) }
                            value={ button2TextAlign }
                            options={ [
                                { value: 'left', label: escapeHTML( __( 'Left', 'wp-magazine-modules-lite' ) ) },
                                { value: 'center', label: escapeHTML( __( 'Center', 'wp-magazine-modules-lite' ) ) },
                                { value: 'right', label: escapeHTML( __( 'Right', 'wp-magazine-modules-lite' ) ) }
                            ] }
                            onChange={ ( newbutton2TextAlign ) => setAttributes( { button2TextAlign: newbutton2TextAlign  } ) }
                        />
                        <SelectControl
                            label={ escapeHTML( __( 'Font Family', 'wp-magazine-modules-lite' ) ) }
                            value={ button2FontFamily }
                            options={ googleFontsOptions }
                            onChange={ ( newbutton2FontFamily ) => setAttributes( { button2FontFamily: newbutton2FontFamily, button2FontWeight: setfontWeight( newbutton2FontFamily, google_fonts )[0].value } ) }
                        />
                        { button2FontFamily &&
                            <SelectControl
                                label={ escapeHTML( __( 'Font Weight', 'wp-magazine-modules-lite' ) ) }
                                value={ button2FontWeight }
                                options={ setfontWeight( button2FontFamily, google_fonts ) }
                                onChange={ ( newbutton2FontWeight ) => setAttributes( { button2FontWeight: newbutton2FontWeight } ) }
                            />
                        }
                        <RangeControl
                            label={ escapeHTML( __( 'Font Size', 'wp-magazine-modules-lite' ) ) }
                            value={ button2FontSize }
                            onChange={ ( newbutton2FontSize ) => setAttributes( { button2FontSize: newbutton2FontSize } ) }
                            min={ 1 }
                            max={ 200 }
                            allowReset={ true }
                            initialPosition = { 0 }
                        />
                        <SelectControl
                            label={ escapeHTML( __( 'Text Transform', 'wp-magazine-modules-lite' ) ) }
                            value={ button2TextTransform }
                            options={ [
                                { value: 'none', label: escapeHTML( __( 'Default', 'wp-magazine-modules-lite' ) ) },
                                { value: 'uppercase', label: escapeHTML( __( 'Uppercase', 'wp-magazine-modules-lite' ) ) },
                                { value: 'lowercase', label: escapeHTML( __( 'Lowercase', 'wp-magazine-modules-lite' ) ) },
                                { value: 'capitalize', label: escapeHTML( __( 'Capitalize', 'wp-magazine-modules-lite' ) ) }
                            ] }
                            onChange={ ( newbutton2TextTransform ) => setAttributes( { button2TextTransform: newbutton2TextTransform } ) }
                        />
                        <label for={button2FontColor}>{ escapeHTML( __( 'Font Color', 'wp-magazine-modules-lite' ) ) }</label>
                        <ColorPalette
                            colors={colors}
                            value={ button2FontColor }
                            onChange={ ( newbutton2FontColor ) => setAttributes( { button2FontColor: newbutton2FontColor } ) }
                        />
                        <label for={button2HoverColor}>{ escapeHTML( __( 'Hover Font Color', 'wp-magazine-modules-lite' ) ) }</label>
                        <ColorPalette
                            colors={colors}
                            value={ button2HoverColor }
                            onChange={ ( newbutton2HoverColor ) => setAttributes( { button2HoverColor: newbutton2HoverColor } ) }
                        />
                        <label for={button2BackgroundColor}>{ escapeHTML( __( 'Background Color', 'wp-magazine-modules-lite' ) ) }</label>
                        <ColorPalette
                            colors={colors}
                            value={ button2BackgroundColor }
                            onChange={ ( newbutton2BackgroundColor ) => setAttributes( { button2BackgroundColor: newbutton2BackgroundColor } ) }
                        />
                        <label for={button2BackgroundHoverColor}>{ escapeHTML( __( 'Background Hover Color', 'wp-magazine-modules-lite' ) ) }</label>
                        <ColorPalette
                            colors={colors}
                            value={ button2BackgroundHoverColor }
                            onChange={ ( newbutton2BackgroundHoverColor ) => setAttributes( { button2BackgroundHoverColor: newbutton2BackgroundHoverColor } ) }
                        />
                        <div class="cvmm-padding-control-wrap">
                            <label for="button-padding">{ escapeHTML( __( 'Padding', 'wp-magazine-modules-lite' ) ) }</label>
                            <TextControl
                                label={ escapeHTML( __( 'Top', 'wp-magazine-modules-lite' ) ) }
                                type="number"
                                min={ 0 }
                                max={ 100 }
                                value={ button2PaddingTop }
                                onChange={ ( newbutton2PaddingTop ) => setAttributes( { button2PaddingTop: newbutton2PaddingTop } ) }
                            />
                            <TextControl
                                label={ escapeHTML( __( 'Right', 'wp-magazine-modules-lite' ) ) }
                                type="number"
                                min={ 0 }
                                max={ 100 }
                                value={ button2PaddingRight }
                                onChange={ ( newbutton2PaddingRight ) => setAttributes( { button2PaddingRight: newbutton2PaddingRight } ) }
                            />
                            <TextControl
                                label={ escapeHTML( __( 'Bottom', 'wp-magazine-modules-lite' ) ) }
                                type="number"
                                min={ 0 }
                                max={ 100 }
                                value={ button2PaddingBottom }
                                onChange={ ( newbutton2PaddingBottom ) => setAttributes( { button2PaddingBottom: newbutton2PaddingBottom } ) }
                            />
                            <TextControl
                                label={ escapeHTML( __( 'Left', 'wp-magazine-modules-lite' ) ) }
                                type="number"
                                min={ 0 }
                                max={ 100 }
                                value={ button2PaddingLeft }
                                onChange={ ( newbutton2PaddingLeft ) => setAttributes( { button2PaddingLeft: newbutton2PaddingLeft } ) }
                            />
                        </div>
                        <SelectControl
                            label={ escapeHTML( __( 'Border Type', 'wp-magazine-modules-lite' ) ) }
                            value={ button2BorderType }
                            options={ [
                                { value: 'none', label: 'None' },
                                { value: 'solid', label: 'Solid' },
                                { value: 'dotted', label: 'Dotted' },
                                { value: 'dashed', label: 'Dashed' }
                            ] }
                            onChange={ ( newbutton2BorderType ) => setAttributes( { button2BorderType: newbutton2BorderType } ) }
                        />
                        <TextControl
                            label={ escapeHTML( __( 'Border Weight', 'wp-magazine-modules-lite' ) ) }
                            type="number"
                            min={ 0 }
                            max={ 10 }
                            value={ button2BorderWeight }
                            onChange={ ( newbutton2BorderWeight ) => setAttributes( { button2BorderWeight: newbutton2BorderWeight } ) }
                        />
                        <label for={button2BorderColor}>{ escapeHTML( __( 'Border Color', 'wp-magazine-modules-lite' ) ) }</label>
                        <ColorPalette
                            colors={colors}
                            value={ button2BorderColor }
                            onChange={ ( newbutton2BorderColor ) => setAttributes( { button2BorderColor: newbutton2BorderColor } ) }
                        />
                        <label for={button2BorderHoverColor}>{ escapeHTML( __( 'Border Hover Color', 'wp-magazine-modules-lite' ) ) }</label>
                        <ColorPalette
                            colors={colors}
                            value={ button2BorderHoverColor }
                            onChange={ ( newbutton2BorderHoverColor ) => setAttributes( { button2BorderHoverColor: newbutton2BorderHoverColor } ) }
                        />
                    </PanelBody>
                </PanelBody>
            </Fragment>
        )
    }
}