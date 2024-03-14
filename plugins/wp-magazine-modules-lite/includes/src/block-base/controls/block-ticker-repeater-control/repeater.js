/**
 * Custom repeater control.
 * 
 */
const { Component } = wp.element;
const { __ } = wp.i18n;
const { escapeHTML } = wp.escapeHtml;
const { PanelBody, TextControl, Button } = wp.components;
const { MediaUpload, MediaUploadCheck  } = wp.blockEditor;
const ALLOWED_MEDIA_TYPES = [ 'image' ];

export default class RepeaterControl extends Component {
    constructor( props ) {
        super( ...arguments )
        const value = this.props.attributes.tickerRepeater
        this.state = {
            titles: value,
        }
    }

    onUpdateImage( newImage, key ) {
        if( newImage == undefined ) {
            return;
        }
        const { titles } = this.state
        const { setAttributes } = this.props
        titles[key].ticker_image = newImage.url
        let newtitles = JSON.parse( JSON.stringify( titles ) )
        setAttributes({ tickerRepeater: newtitles })
        this.setState( { titles: newtitles })
    };

    onRemoveImage( key ) {
        const { titles } = this.state
        const { setAttributes } = this.props
        titles[key].ticker_image = ''
        let newtitles = JSON.parse( JSON.stringify( titles ) )
        setAttributes({ tickerRepeater: newtitles })
        this.setState( { titles: newtitles })
    };

    onUpdate( newValue, key ) {
        if( newValue == undefined ) {
            return;
        }
        const { titles } = this.state
        const { setAttributes } = this.props
        titles[key].ticker_title = newValue
        let newtitles = JSON.parse( JSON.stringify( titles ) )
        setAttributes({ tickerRepeater: newtitles })
        this.setState( { titles: newtitles })
    }

    onAdd() {
        const { titles } = this.state
        const { setAttributes } = this.props
        titles.push({ticker_title:''})
        let newtitles = JSON.parse( JSON.stringify( titles ) )
        setAttributes({ tickerRepeater: newtitles })
        this.setState( { titles: newtitles })
    }

    onRemove( key ) {
        const { titles } = this.state
        const { setAttributes } = this.props
        titles.splice(key,1)
        let newtitles = JSON.parse( JSON.stringify( titles ) )
        setAttributes({ tickerRepeater: newtitles })
        this.setState( { titles: newtitles })
    }

    render() {
        const { titles } = this.state
        return (
            <div class="components-base-control">
                <div class="components-base-control__field">
                    <label class="components-base-control__label" for="inspector-repeater-control">{ escapeHTML( __( 'Ticker Content', 'wp-magazine-modules-lite' ) ) }</label>
                    <PanelBody title={ escapeHTML(__( 'Ticker titles', 'wp-magazine-modules-lite' )) } class="inspector-repeater-control">
                        { Array.isArray( titles ) &&
                            titles.map(( title, key ) => {
                                let fieldimageValue = titles[key].ticker_image;
                                let fieldValue = titles[key].ticker_title;
                                    return (
                                        <PanelBody title={ escapeHTML( __( 'Ticker ', 'wp-magazine-modules-lite' ) + ( key + 1 ) ) } initialOpen={ false }>
                                            <MediaUploadCheck>
                                                <MediaUpload
                                                    onSelect={ ( newImage ) => this.onUpdateImage( newImage, key ) }
                                                    allowedTypes={ ALLOWED_MEDIA_TYPES }
                                                    value={ fieldimageValue }
                                                    render={ ( { open } ) => (
                                                        <Button
                                                            className={ ! fieldimageValue ? 'editor-post-featured-image__toggle' : 'editor-post-featured-image__preview' }
                                                            onClick={ open }>
                                                            { !fieldimageValue && escapeHTML( __( 'Set image', 'wp-magazine-modules-lite' ) ) }
                                                            { ( !!fieldimageValue && !fieldimageValue ) && <Spinner /> }
                                                            {  ( fieldimageValue ) &&
                                                                <img src={ fieldimageValue } alt={ escapeHTML( __( 'Image', 'wp-magazine-modules-lite' ) ) } />
                                                            }
                                                        </Button>
                                                    ) }
                                            />
                                            </MediaUploadCheck>
                                            { fieldimageValue &&
                                                <MediaUploadCheck>
                                                    <MediaUpload
                                                        onSelect={ ( newImage ) => this.onUpdateImage( newImage, key ) }
                                                        allowedTypes={ ALLOWED_MEDIA_TYPES }
                                                        value={ fieldimageValue }
                                                        render={ ( { open } ) => (
                                                            <Button onClick={ open } isSecondary isLarge>
                                                                { escapeHTML( __( 'Replace image', 'wp-magazine-modules-lite' ) ) }
                                                            </Button>
                                                        ) }
                                                    />
                                                </MediaUploadCheck>
                                            }
                                            { fieldimageValue &&
                                                <MediaUploadCheck>
                                                    <Button onClick={ () => this.onRemoveImage( key ) } isLink isDestructive>
                                                        { escapeHTML( __( 'Remove image', 'wp-magazine-modules-lite' ) ) }
                                                    </Button>
                                                </MediaUploadCheck>
                                            }
                                            <TextControl
                                                label={ escapeHTML( __( 'Add title', 'wp-magazine-modules-lite' ) ) }
                                                value={ fieldValue }
                                                onChange={ ( newValue ) => this.onUpdate( newValue, key ) }
                                            />
                                            <Button isLink isDestructive onClick={ ( e ) => { this.onRemove(key) }}>
                                                { escapeHTML( __( 'Delete', 'wp-magazine-modules-lite' ) ) }
                                            </Button>
                                        </PanelBody>
                                    );
                            })
                        }
                        <Button isSecondary isSmall onClick={ ( e ) => { this.onAdd() }}>
                            { escapeHTML( __( 'Add new', 'wp-magazine-modules-lite' ) ) }
                        </Button>
                    </PanelBody>
                </div>
            </div>
        )
    }
}