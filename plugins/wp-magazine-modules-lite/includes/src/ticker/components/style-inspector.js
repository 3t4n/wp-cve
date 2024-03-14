/**
 * Includes the settings of style tab.
 * 
 */
import ConvertGoogleFontVariant from '../../block-base/block-base';
import googlefonts from '../../block-base/googlefonts.json';

const { Component, Fragment } = wp.element;
const { __ } = wp.i18n;
const { escapeHTML } = wp.escapeHtml;
const { PanelBody, Button, SelectControl, ToggleControl, RangeControl, ColorPalette } = wp.components;

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
        const { blockLayout, blockPrimaryColor, blockHoverColor, typographyOption, blockTitleFontFamily, blockTitleFontSize, blockTitleFontStyle, blockTitleTextTransform, blockTitleTextDecoration, blockTitleColor, blockTitleLineHeight, blockTitleBorderColor, captionTextAlign, captionFontFamily, captionFontSize, captionFontStyle, captionTextTransform, captionTextDecoration, captionBackgroundColor, captionFontColor, captionHoverColor, captionlineHeight, contentTextAlign, contentFontFamily, contentFontSize, contentFontStyle, contentTextTransform, contentTextDecoration, contentFontColor, contentHoverColor, contentlineHeight } = this.props.attributes;
        let { blockTitleFontWeight, captionFontWeight, contentFontWeight } = this.props.attributes;
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
                            <Button className={ `${( blockLayout == 'layout-default') ? "isActive" : "" }` } onClick={ ( e ) => setAttributes( { blockLayout: 'layout-default' } ) } ><img src={ BlocksBuildObject.tickerLayoutDefault } /></Button>
                            <Button className={ `${( blockLayout == 'layout-one') ? "isActive" : "" }` } onClick={ ( e ) => setAttributes( { blockLayout: 'layout-one' } ) }><img src={ BlocksBuildObject.tickerLayoutOne } /></Button>
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
                    <PanelBody className="cvmm-editor-component-sub-panel_body" title={ escapeHTML( __( 'Caption', 'wp-magazine-modules-lite' ) ) } initialOpen = { false } >
                        <SelectControl
                            label={ escapeHTML( __( 'Text Align', 'wp-magazine-modules-lite' ) ) }
                            value={ captionTextAlign }
                            options={ [
                                { value: 'left', label: escapeHTML( __( 'Left', 'wp-magazine-modules-lite' ) ) },
                                { value: 'center', label: escapeHTML( __( 'Center', 'wp-magazine-modules-lite' ) ) },
                                { value: 'right', label: escapeHTML( __( 'Right', 'wp-magazine-modules-lite' ) ) }
                            ] }
                            onChange={ ( newcaptionTextAlign ) => setAttributes( { captionTextAlign: newcaptionTextAlign } ) }
                        />
                        <SelectControl
                            label={ escapeHTML( __( 'Font Family', 'wp-magazine-modules-lite' ) ) }
                            value={ captionFontFamily }
                            options={ googleFontsOptions }
                            onChange={ ( newcaptionFontFamily ) => setAttributes( { captionFontFamily: newcaptionFontFamily, captionFontWeight: setfontWeight( newcaptionFontFamily, google_fonts )[0].value } ) }
                        />
                        { captionFontFamily &&
                            <SelectControl
                                label={ escapeHTML( __( 'Font Weight', 'wp-magazine-modules-lite' ) ) }
                                value={ captionFontWeight }
                                options={ setfontWeight( captionFontFamily, google_fonts ) }
                                onChange={ ( newcaptionFontWeight ) => setAttributes( { captionFontWeight: newcaptionFontWeight } ) }
                            />
                        }
                        <RangeControl
                            label={ escapeHTML( __( 'Font Size', 'wp-magazine-modules-lite' ) ) }
                            value={ captionFontSize }
                            onChange={ ( newcaptionFontSize ) => setAttributes( { captionFontSize: newcaptionFontSize } ) }
                            min={ 1 }
                            max={ 200 }
                            allowReset={ true }
                            initialPosition = { 0 }
                        />
                        <SelectControl
                            label={ escapeHTML( __( 'Font Style', 'wp-magazine-modules-lite' ) ) }
                            value={ captionFontStyle }
                            options={ [
                                { value: 'initial', label: escapeHTML( __( 'Default', 'wp-magazine-modules-lite' ) ) },
                                { value: 'normal', label: escapeHTML( __( 'Normal', 'wp-magazine-modules-lite' ) ) },
                                { value: 'italic', label: escapeHTML( __( 'Italic', 'wp-magazine-modules-lite' ) ) },
                                { value: 'oblique', label: escapeHTML( __( 'Oblique', 'wp-magazine-modules-lite' ) ) }
                            ] }
                            onChange={ ( newcaptionFontStyle ) => setAttributes( { captionFontStyle: newcaptionFontStyle } ) }
                        />
                        <SelectControl
                            label={ escapeHTML( __( 'Text Transform', 'wp-magazine-modules-lite' ) ) }
                            value={ captionTextTransform }
                            options={ [
                                { value: 'none', label: escapeHTML( __( 'Default', 'wp-magazine-modules-lite' ) ) },
                                { value: 'uppercase', label: escapeHTML( __( 'Uppercase', 'wp-magazine-modules-lite' ) ) },
                                { value: 'lowercase', label: escapeHTML( __( 'Lowercase', 'wp-magazine-modules-lite' ) ) },
                                { value: 'capitalize', label: escapeHTML( __( 'Capitalize', 'wp-magazine-modules-lite' ) ) }
                            ] }
                            onChange={ ( newcaptionTextTransform ) => setAttributes( { captionTextTransform: newcaptionTextTransform } ) }
                        />
                        <SelectControl
                            label={ escapeHTML( __( 'Text Decoration', 'wp-magazine-modules-lite' ) ) }
                            value={ captionTextDecoration }
                            options={ [
                                { value: 'none', label: escapeHTML( __( 'None', 'wp-magazine-modules-lite' ) ) },
                                { value: 'underline', label: escapeHTML( __( 'Underline', 'wp-magazine-modules-lite' ) ) },
                                { value: 'line-through', label: escapeHTML( __( 'Line-through', 'wp-magazine-modules-lite' ) ) },
                                { value: 'overline', label: escapeHTML( __( 'Overline', 'wp-magazine-modules-lite' ) ) }
                            ] }
                            onChange={ ( newcaptionTextDecoration ) => setAttributes( { captionTextDecoration: newcaptionTextDecoration } ) }
                        />
                        <div class="cvmm-caption-color-wrap">
                            <label>{ escapeHTML( __( 'Background Color', 'wp-magazine-modules-lite' ) ) }</label>
                            <ColorPalette
                                colors={colors}
                                value={ captionBackgroundColor }
                                onChange={ ( newcaptionBackgroundColor ) => setAttributes( { captionBackgroundColor: newcaptionBackgroundColor } ) }
                            />
                            <label>{ escapeHTML( __( 'Font Color', 'wp-magazine-modules-lite' ) ) }</label>
                            <ColorPalette
                                colors={colors}
                                value={ captionFontColor }
                                onChange={ ( newcaptionFontColor ) => setAttributes( { captionFontColor: newcaptionFontColor } ) }
                            />
                            <label>{ escapeHTML( __( 'Hover Color', 'wp-magazine-modules-lite' ) ) }</label>
                            <ColorPalette
                                colors={colors}
                                value={ captionHoverColor }
                                onChange={ ( newcaptionHoverColor ) => setAttributes( { captionHoverColor: newcaptionHoverColor } ) }
                            />
                        </div>
                        <RangeControl
                            label={ escapeHTML( __( 'Line Height', 'wp-magazine-modules-lite' ) ) }
                            value={ captionlineHeight }
                            onChange={ ( newcaptionlineHeight ) => setAttributes( { captionlineHeight: newcaptionlineHeight } ) }
                            step={ 0.1 }
                            min={ 0.1 }
                            max={ 10 }
                            allowReset={ true }
                        />
                    </PanelBody>
                    <PanelBody className="cvmm-editor-component-sub-panel_body" title={ escapeHTML( __( 'Content', 'wp-magazine-modules-lite' ) ) } initialOpen = { false } >
                        <SelectControl
                            label={ escapeHTML( __( 'Text Align', 'wp-magazine-modules-lite' ) ) }
                            value={ contentTextAlign }
                            options={ [
                                { value: 'left', label: escapeHTML( __( 'Left', 'wp-magazine-modules-lite' ) ) },
                                { value: 'center', label: escapeHTML( __( 'Center', 'wp-magazine-modules-lite' ) ) },
                                { value: 'right', label: escapeHTML( __( 'Right', 'wp-magazine-modules-lite' ) ) }
                            ] }
                            onChange={ ( newcontentTextAlign ) => setAttributes( { contentTextAlign: newcontentTextAlign } ) }
                        />
                        <SelectControl
                            label={ escapeHTML( __( 'Font Family', 'wp-magazine-modules-lite' ) ) }
                            value={ contentFontFamily }
                            options={ googleFontsOptions }
                            onChange={ ( newcontentFontFamily ) => setAttributes( { contentFontFamily: newcontentFontFamily, contentFontWeight: setfontWeight( newcontentFontFamily, google_fonts )[0].value } ) }
                        />
                        { contentFontFamily &&
                            <SelectControl
                                label={ escapeHTML( __( 'Font Weight', 'wp-magazine-modules-lite' ) ) }
                                value={ contentFontWeight }
                                options={ setfontWeight( contentFontFamily, google_fonts ) }
                                onChange={ ( newcontentFontWeight ) => setAttributes( { contentFontWeight: newcontentFontWeight } ) }
                            />
                        }
                        <RangeControl
                            label={ escapeHTML( __( 'Font Size', 'wp-magazine-modules-lite' ) ) }
                            value={ contentFontSize }
                            onChange={ ( newcontentFontSize ) => setAttributes( { contentFontSize: newcontentFontSize } ) }
                            min={ 1 }
                            max={ 200 }
                            allowReset={ true }
                            initialPosition = { 0 }
                        />
                        <SelectControl
                            label={ escapeHTML( __( 'Font Style', 'wp-magazine-modules-lite' ) ) }
                            value={ contentFontStyle }
                            options={ [
                                { value: 'initial', label: escapeHTML( __( 'Default', 'wp-magazine-modules-lite' ) ) },
                                { value: 'normal', label: escapeHTML( __( 'Normal', 'wp-magazine-modules-lite' ) ) },
                                { value: 'italic', label: escapeHTML( __( 'Italic', 'wp-magazine-modules-lite' ) ) },
                                { value: 'oblique', label: escapeHTML( __( 'Oblique', 'wp-magazine-modules-lite' ) ) }
                            ] }
                            onChange={ ( newcontentFontStyle ) => setAttributes( { contentFontStyle: newcontentFontStyle } ) }
                        />
                        <SelectControl
                            label={ escapeHTML( __( 'Text Transform', 'wp-magazine-modules-lite' ) ) }
                            value={ contentTextTransform }
                            options={ [
                                { value: 'none', label: escapeHTML( __( 'Default', 'wp-magazine-modules-lite' ) ) },
                                { value: 'uppercase', label: escapeHTML( __( 'Uppercase', 'wp-magazine-modules-lite' ) ) },
                                { value: 'lowercase', label: escapeHTML( __( 'Lowercase', 'wp-magazine-modules-lite' ) ) },
                                { value: 'capitalize', label: escapeHTML( __( 'Capitalize', 'wp-magazine-modules-lite' ) ) }
                            ] }
                            onChange={ ( newcontentTextTransform ) => setAttributes( { contentTextTransform: newcontentTextTransform } ) }
                        />
                        <SelectControl
                            label={ escapeHTML( __( 'Text Decoration', 'wp-magazine-modules-lite' ) ) }
                            value={ contentTextDecoration }
                            options={ [
                                { value: 'none', label: escapeHTML( __( 'None', 'wp-magazine-modules-lite' ) ) },
                                { value: 'underline', label: escapeHTML( __( 'Underline', 'wp-magazine-modules-lite' ) ) },
                                { value: 'line-through', label: escapeHTML( __( 'Line-through', 'wp-magazine-modules-lite' ) ) },
                                { value: 'overline', label: escapeHTML( __( 'Overline', 'wp-magazine-modules-lite' ) ) }
                            ] }
                            onChange={ ( newcontentTextDecoration ) => setAttributes( { contentTextDecoration: newcontentTextDecoration } ) }
                        />
                        <div class="cvmm-content-color-wrap">
                            <label>{ escapeHTML( __( 'Font Color', 'wp-magazine-modules-lite' ) ) }</label>
                            <ColorPalette
                                colors={colors}
                                value={ contentFontColor }
                                onChange={ ( newcontentFontColor ) => setAttributes( { contentFontColor: newcontentFontColor } ) }
                            />
                            <label>{ escapeHTML( __( 'Hover Color', 'wp-magazine-modules-lite' ) ) }</label>
                            <ColorPalette
                                colors={colors}
                                value={ contentHoverColor }
                                onChange={ ( newcontentHoverColor ) => setAttributes( { contentHoverColor: newcontentHoverColor } ) }
                            />
                        </div>
                        <RangeControl
                            label={ escapeHTML( __( 'Line Height', 'wp-magazine-modules-lite' ) ) }
                            value={ contentlineHeight }
                            onChange={ ( newcontentlineHeight ) => setAttributes( { contentlineHeight: newcontentlineHeight } ) }
                            step={ 0.1 }
                            min={ 0.1 }
                            max={ 10 }
                            allowReset={ true }
                        />
                    </PanelBody>
                </PanelBody>
            </Fragment>
        )
    }
}