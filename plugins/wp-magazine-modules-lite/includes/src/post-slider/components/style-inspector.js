/**
 * Style Tab Inspector controls wrapper controls.
 * 
 */
import ConvertGoogleFontVariant from '../../block-base/block-base';
import googlefonts from '../../block-base/googlefonts.json';

const { Component, Fragment } = wp.element;
const { __ } = wp.i18n;
const { escapeHTML } = wp.escapeHtml;
const { PanelBody, SelectControl, ToggleControl, RangeControl, ColorPalette, TextControl, Button } = wp.components;

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
        const { blockLayout, postFormatIcon, postMetaIcon, postButtonIcon, imageSize, blockPrimaryColor, blockHoverColor, typographyOption, blockTitleFontFamily, blockTitleFontSize, blockTitleFontStyle, blockTitleTextTransform, blockTitleTextDecoration, blockTitleColor, blockTitleLineHeight, blockTitleBorderColor, titleTextAlign, titleFontFamily, titleFontSize, titleFontStyle, titleTextTransform, titleTextDecoration, titleFontColor, titleHoverColor, titlelineHeight, metaTextAlign, metaFontFamily, metaFontSize, metaFontStyle, metaTextTransform, metaTextDecoration, metaFontColor, metaHoverColor, metalineHeight, descTextAlign, descFontFamily, descFontSize, descFontStyle, descTextTransform, descTextDecoration, descFontColor, desclineHeight, buttonTextAlign, buttonFontFamily, buttonFontSize, buttonTextTransform, buttonFontColor, buttonHoverColor, buttonBackgroundColor, buttonBackgroundHoverColor, buttonPaddingTop, buttonPaddingRight, buttonPaddingBottom, buttonPaddingLeft, buttonBorderType, buttonBorderWeight, buttonBorderColor, buttonBorderHoverColor } = this.props.attributes;
        let { blockTitleFontWeight, titleFontWeight, metaFontWeight, descFontWeight, buttonFontWeight  } = this.props.attributes;
        const { setAttributes } = this.props;

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
                <PanelBody title={ escapeHTML( __( 'Layout Settings', 'wp-magazine-modules-lite' ) ) } initialOpen = { false }>
                    <div className="cvmm-layout-button-group">
                        <div>
                            <label>{ escapeHTML( __( 'Layouts', 'wp-magazine-modules-lite' ) ) }</label>
                        </div>
                        <div>
                            <Button className={ `${( blockLayout == 'layout-default') ? "isActive" : "" }` } onClick={ ( e ) => setAttributes( { blockLayout: 'layout-default' } ) } ><img src={ BlocksBuildObject.sliderLayoutDefault } /></Button>
                            <Button className={ `${( blockLayout == 'layout-one') ? "isActive" : "" }` } onClick={ ( e ) => setAttributes( { blockLayout: 'layout-one' } ) }><img src={ BlocksBuildObject.sliderLayoutOne } /></Button>
                        </div>
                    </div>
                    <ToggleControl
                        label={ escapeHTML( __( 'Show post format icon', 'wp-magazine-modules-lite' ) ) }
                        checked={ postFormatIcon }
                        onChange={ ( newpostFormatIcon ) => setAttributes( { postFormatIcon: newpostFormatIcon } ) }
                    />
                    <ToggleControl
                        label={ escapeHTML( __( 'Show post meta before icon', 'wp-magazine-modules-lite' ) ) }
                        checked={ postMetaIcon }
                        onChange={ ( newpostMetaIcon ) => setAttributes( { postMetaIcon: newpostMetaIcon } ) }
                    />
                    <ToggleControl
                        label={ escapeHTML( __( 'Show read more button icon', 'wp-magazine-modules-lite' ) ) }
                        checked={ postButtonIcon }
                        onChange={ ( newpostButtonIcon ) => setAttributes( { postButtonIcon: newpostButtonIcon } ) }
                    />
                    <PanelBody title={ escapeHTML( __( 'Image Settings', 'wp-magazine-modules-lite' ) ) } initialOpen = { false } >
                        <SelectControl
                            label={ escapeHTML( __( 'Image Size', 'wp-magazine-modules-lite' ) ) }
                            value={ imageSize }
                            options={ [
                                { value: 'full', label: escapeHTML( __( 'Full', 'wp-magazine-modules-lite' ) ) },
                                { value: 'cvmm-large', label: escapeHTML( __( 'Large', 'wp-magazine-modules-lite' ) ) },
                                { value: 'cvmm-medium-square', label: escapeHTML( __( 'Medium Square', 'wp-magazine-modules-lite' ) ) },
                                { value: 'cvmm-portrait', label: escapeHTML( __( 'Portrait', 'wp-magazine-modules-lite' ) ) },
                                { value: 'cvmm-medium-plus', label: escapeHTML( __( 'Medium Plus', 'wp-magazine-modules-lite' ) ) },
                                { value: 'cvmm-medium', label: escapeHTML( __( 'Medium', 'wp-magazine-modules-lite' ) ) },
                                { value: 'thumbnail', label: escapeHTML( __( 'Thumbnail', 'wp-magazine-modules-lite' ) ) },
                                { value: 'cvmm-small', label: escapeHTML( __( 'Small', 'wp-magazine-modules-lite' ) ) },
                            ] }
                            onChange={ ( newimageSize ) => setAttributes( { imageSize: newimageSize } ) }
                        />
                    </PanelBody>
                </PanelBody>
                <PanelBody title={ escapeHTML( __( 'Color Settings', 'wp-magazine-modules-lite' ) ) } initialOpen = { false } >
                    <div className="wpmagazine-modules-color-seetings-tab">
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

                    <PanelBody className="cvmm-editor-component-sub-panel_body" title={ escapeHTML( __( 'Meta', 'wp-magazine-modules-lite' ) ) } initialOpen = { false } >
                        <SelectControl
                            label={ escapeHTML( __( 'Text Align', 'wp-magazine-modules-lite' ) ) }
                            value={ metaTextAlign }
                            options={ [
                                { value: 'left', label: escapeHTML( __( 'Left', 'wp-magazine-modules-lite' ) ) },
                                { value: 'center', label: escapeHTML( __( 'Center', 'wp-magazine-modules-lite' ) ) },
                                { value: 'right', label: escapeHTML( __( 'Right', 'wp-magazine-modules-lite' ) ) }
                            ] }
                            onChange={ ( newmetaTextAlign ) => setAttributes( { metaTextAlign: newmetaTextAlign } ) }
                        />
                        <SelectControl
                            label={ escapeHTML( __( 'Font Family', 'wp-magazine-modules-lite' ) ) }
                            value={ metaFontFamily }
                            options={ googleFontsOptions }
                            onChange={ ( newmetaFontFamily ) => setAttributes( { metaFontFamily: newmetaFontFamily, metaFontWeight: setfontWeight( newmetaFontFamily, google_fonts )[0].value } ) }
                        />
                        { metaFontFamily &&
                            <SelectControl
                                label={ escapeHTML( __( 'Font Weight', 'wp-magazine-modules-lite' ) ) }
                                value={ metaFontWeight }
                                options={ setfontWeight( metaFontFamily, google_fonts ) }
                                onChange={ ( newmetaFontWeight ) => setAttributes( { metaFontWeight: newmetaFontWeight } ) }
                            />
                        }
                        <RangeControl
                            label={ escapeHTML( __( 'Font Size', 'wp-magazine-modules-lite' ) ) }
                            value={ metaFontSize }
                            onChange={ ( newmetaFontSize ) => setAttributes( { metaFontSize: newmetaFontSize } ) }
                            min={ 1 }
                            max={ 200 }
                            allowReset={ true }
                            initialPosition = { 0 }
                        />
                        <SelectControl
                            label={ escapeHTML( __( 'Font Style', 'wp-magazine-modules-lite' ) ) }
                            value={ metaFontStyle }
                            options={ [
                                { value: 'initial', label: escapeHTML( __( 'Default', 'wp-magazine-modules-lite' ) ) },
                                { value: 'normal', label: escapeHTML( __( 'Normal', 'wp-magazine-modules-lite' ) ) },
                                { value: 'italic', label: escapeHTML( __( 'Italic', 'wp-magazine-modules-lite' ) ) },
                                { value: 'oblique', label: escapeHTML( __( 'Oblique', 'wp-magazine-modules-lite' ) ) }
                            ] }
                            onChange={ ( newmetaFontStyle ) => setAttributes( { metaFontStyle: newmetaFontStyle } ) }
                        />
                        <SelectControl
                            label={ escapeHTML( __( 'Text Transform', 'wp-magazine-modules-lite' ) ) }
                            value={ metaTextTransform }
                            options={ [
                                { value: 'none', label: escapeHTML( __( 'Default', 'wp-magazine-modules-lite' ) ) },
                                { value: 'uppercase', label: escapeHTML( __( 'Uppercase', 'wp-magazine-modules-lite' ) ) },
                                { value: 'lowercase', label: escapeHTML( __( 'Lowercase', 'wp-magazine-modules-lite' ) ) },
                                { value: 'capitalize', label: escapeHTML( __( 'Capitalize', 'wp-magazine-modules-lite' ) ) }
                            ] }
                            onChange={ ( newmetaTextTransform ) => setAttributes( { metaTextTransform: newmetaTextTransform } ) }
                        />
                        <SelectControl
                            label={ escapeHTML( __( 'Text Decoration', 'wp-magazine-modules-lite' ) ) }
                            value={ metaTextDecoration }
                            options={ [
                                { value: 'none', label: escapeHTML( __( 'None', 'wp-magazine-modules-lite' ) ) },
                                { value: 'underline', label: escapeHTML( __( 'Underline', 'wp-magazine-modules-lite' ) ) },
                                { value: 'line-through', label: escapeHTML( __( 'Line-through', 'wp-magazine-modules-lite' ) ) },
                                { value: 'overline', label: escapeHTML( __( 'Overline', 'wp-magazine-modules-lite' ) ) }
                            ] }
                            onChange={ ( newmetaTextDecoration ) => setAttributes( { metaTextDecoration: newmetaTextDecoration } ) }
                        />
                        <div class="cvmm-meta-color">
                            <label>{ escapeHTML( __( 'Font Color', 'wp-magazine-modules-lite' ) ) }</label>
                            <ColorPalette
                                colors={colors}
                                value={ metaFontColor }
                                onChange={ ( newmetaFontColor ) => setAttributes( { metaFontColor: newmetaFontColor } ) }
                            />
                            <label>{ escapeHTML( __( 'Hover Color', 'wp-magazine-modules-lite' ) ) }</label>
                            <ColorPalette
                                colors={colors}
                                value={ metaHoverColor }
                                onChange={ ( newmetaHoverColor ) => setAttributes( { metaHoverColor: newmetaHoverColor } ) }
                            />
                        </div>
                        <RangeControl
                            label={ escapeHTML( __( 'Line Height', 'wp-magazine-modules-lite' ) ) }
                            value={ metalineHeight }
                            onChange={ ( newmetalineHeight ) => setAttributes( { metalineHeight: newmetalineHeight } ) }
                            step={ 0.1 }
                            min={ 0.1 }
                            max={ 10 }
                            allowReset={ true }
                        />
                    </PanelBody>

                    <PanelBody className="cvmm-editor-component-sub-panel_body" title={ escapeHTML( __( 'Content/Excerpt', 'wp-magazine-modules-lite' ) ) } initialOpen = { false } >
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

                    <PanelBody className="cvmm-editor-component-sub-panel_body" title={ escapeHTML( __( 'Button', 'wp-magazine-modules-lite' ) ) } initialOpen = { false } >
                        <SelectControl
                            label={ escapeHTML( __( 'Text Align', 'wp-magazine-modules-lite' ) ) }
                            value={ buttonTextAlign }
                            options={ [
                                { value: 'left', label: escapeHTML( __( 'Left', 'wp-magazine-modules-lite' ) ) },
                                { value: 'center', label: escapeHTML( __( 'Center', 'wp-magazine-modules-lite' ) ) },
                                { value: 'right', label: escapeHTML( __( 'Right', 'wp-magazine-modules-lite' ) ) }
                            ] }
                            onChange={ ( newbuttonTextAlign ) => setAttributes( { buttonTextAlign: newbuttonTextAlign  } ) }
                        />
                        <SelectControl
                            label={ escapeHTML( __( 'Font Family', 'wp-magazine-modules-lite' ) ) }
                            value={ buttonFontFamily }
                            options={ googleFontsOptions }
                            onChange={ ( newbuttonFontFamily ) => setAttributes( { buttonFontFamily: newbuttonFontFamily, buttonFontWeight: setfontWeight( newbuttonFontFamily, google_fonts )[0].value } ) }
                        />
                        { buttonFontFamily &&
                            <SelectControl
                                label={ escapeHTML( __( 'Font Weight', 'wp-magazine-modules-lite' ) ) }
                                value={ buttonFontWeight }
                                options={ setfontWeight( buttonFontFamily, google_fonts ) }
                                onChange={ ( newbuttonFontWeight ) => setAttributes( { buttonFontWeight: newbuttonFontWeight } ) }
                            />
                        }
                        <RangeControl
                            label={ escapeHTML( __( 'Font Size', 'wp-magazine-modules-lite' ) ) }
                            value={ buttonFontSize }
                            onChange={ ( newbuttonFontSize ) => setAttributes( { buttonFontSize: newbuttonFontSize } ) }
                            min={ 1 }
                            max={ 200 }
                            allowReset={ true }
                            initialPosition = { 0 }
                        />
                        <SelectControl
                            label={ escapeHTML( __( 'Text Transform', 'wp-magazine-modules-lite' ) ) }
                            value={ buttonTextTransform }
                            options={ [
                                { value: 'none', label: escapeHTML( __( 'Default', 'wp-magazine-modules-lite' ) ) },
                                { value: 'uppercase', label: escapeHTML( __( 'Uppercase', 'wp-magazine-modules-lite' ) ) },
                                { value: 'lowercase', label: escapeHTML( __( 'Lowercase', 'wp-magazine-modules-lite' ) ) },
                                { value: 'capitalize', label: escapeHTML( __( 'Capitalize', 'wp-magazine-modules-lite' ) ) }
                            ] }
                            onChange={ ( newbuttonTextTransform ) => setAttributes( { buttonTextTransform: newbuttonTextTransform } ) }
                        />
                        <label for={buttonFontColor}>{ escapeHTML( __( 'Font Color', 'wp-magazine-modules-lite' ) ) }</label>
                        <ColorPalette
                            colors={colors}
                            value={ buttonFontColor }
                            onChange={ ( newbuttonFontColor ) => setAttributes( { buttonFontColor: newbuttonFontColor } ) }
                        />
                        <label for={buttonHoverColor}>{ escapeHTML( __( 'Hover Font Color', 'wp-magazine-modules-lite' ) ) }</label>
                        <ColorPalette
                            colors={colors}
                            value={ buttonHoverColor }
                            onChange={ ( newbuttonHoverColor ) => setAttributes( { buttonHoverColor: newbuttonHoverColor } ) }
                        />
                        <label for={buttonBackgroundColor}>{ escapeHTML( __( 'Background Color', 'wp-magazine-modules-lite' ) ) }</label>
                        <ColorPalette
                            colors={colors}
                            value={ buttonBackgroundColor }
                            onChange={ ( newbuttonBackgroundColor ) => setAttributes( { buttonBackgroundColor: newbuttonBackgroundColor } ) }
                        />
                        <label for={buttonBackgroundHoverColor}>{ escapeHTML( __( 'Background Hover Color', 'wp-magazine-modules-lite' ) ) }</label>
                        <ColorPalette
                            colors={colors}
                            value={ buttonBackgroundHoverColor }
                            onChange={ ( newbuttonBackgroundHoverColor ) => setAttributes( { buttonBackgroundHoverColor: newbuttonBackgroundHoverColor } ) }
                        />
                        <div class="cvmm-padding-control-wrap">
                            <label for="button-padding">{ escapeHTML( __( 'Padding', 'wp-magazine-modules-lite' ) ) }</label>
                            <TextControl
                                label={ escapeHTML( __( 'Top', 'wp-magazine-modules-lite' ) ) }
                                type="number"
                                min={ 0 }
                                max={ 100 }
                                value={ buttonPaddingTop }
                                onChange={ ( newbuttonPaddingTop ) => setAttributes( { buttonPaddingTop: newbuttonPaddingTop } ) }
                            />
                            <TextControl
                                label={ escapeHTML( __( 'Right', 'wp-magazine-modules-lite' ) ) }
                                type="number"
                                min={ 0 }
                                max={ 100 }
                                value={ buttonPaddingRight }
                                onChange={ ( newbuttonPaddingRight ) => setAttributes( { buttonPaddingRight: newbuttonPaddingRight } ) }
                            />
                            <TextControl
                                label={ escapeHTML( __( 'Bottom', 'wp-magazine-modules-lite' ) ) }
                                type="number"
                                min={ 0 }
                                max={ 100 }
                                value={ buttonPaddingBottom }
                                onChange={ ( newbuttonPaddingBottom ) => setAttributes( { buttonPaddingBottom: newbuttonPaddingBottom } ) }
                            />
                            <TextControl
                                label={ escapeHTML( __( 'Left', 'wp-magazine-modules-lite' ) ) }
                                type="number"
                                min={ 0 }
                                max={ 100 }
                                value={ buttonPaddingLeft }
                                onChange={ ( newbuttonPaddingLeft ) => setAttributes( { buttonPaddingLeft: newbuttonPaddingLeft } ) }
                            />
                        </div>
                        <SelectControl
                            label={ escapeHTML( __( 'Border Type', 'wp-magazine-modules-lite' ) ) }
                            value={ buttonBorderType }
                            options={ [
                                { value: 'none', label: 'None' },
                                { value: 'solid', label: 'Solid' },
                                { value: 'dotted', label: 'Dotted' },
                                { value: 'dashed', label: 'Dashed' }
                            ] }
                            onChange={ ( newbuttonBorderType ) => setAttributes( { buttonBorderType: newbuttonBorderType } ) }
                        />
                        <TextControl
                            label={ escapeHTML( __( 'Border Weight', 'wp-magazine-modules-lite' ) ) }
                            type="number"
                            min={ 0 }
                            max={ 10 }
                            value={ buttonBorderWeight }
                            onChange={ ( newbuttonBorderWeight ) => setAttributes( { buttonBorderWeight: newbuttonBorderWeight } ) }
                        />
                        <label for={buttonBorderColor}>{ escapeHTML( __( 'Border Color', 'wp-magazine-modules-lite' ) ) }</label>
                        <ColorPalette
                            colors={colors}
                            value={ buttonBorderColor }
                            onChange={ ( newbuttonBorderColor ) => setAttributes( { buttonBorderColor: newbuttonBorderColor } ) }
                        />
                        <label for={buttonBorderHoverColor}>{ escapeHTML( __( 'Border Hover Color', 'wp-magazine-modules-lite' ) ) }</label>
                        <ColorPalette
                            colors={colors}
                            value={ buttonBorderHoverColor }
                            onChange={ ( newbuttonBorderHoverColor ) => setAttributes( { buttonBorderHoverColor: newbuttonBorderHoverColor } ) }
                        />
                    </PanelBody>
                </PanelBody>
            </Fragment>
        )
    }
}