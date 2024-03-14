/**
 * Custom repeater control.
 * 
 */
const { MediaUpload, MediaUploadCheck  } = wp.blockEditor;
const ALLOWED_MEDIA_TYPES = [ 'image' ];
const { Component } = wp.element;
const { __ } = wp.i18n;
const { escapeHTML } = wp.escapeHtml;
const { PanelBody, TextControl, TextareaControl, Button, DateTimePicker } = wp.components;

export default class TimelineRepeaterControl extends Component {
    constructor( props ) {
        super( ...arguments )
        const value = this.props.attributes.timelineRepeater
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
        titles[key].timeline_image = newImage.url
        let newtimeline = JSON.parse( JSON.stringify( titles ) )
        setAttributes({ timelineRepeater: newtimeline })
        this.setState( { titles: newtimeline })
    };

    onRemoveImage( key ) {
        const { titles } = this.state
        const { setAttributes } = this.props
        titles[key].timeline_image = ''
        let newtimeline = JSON.parse( JSON.stringify( titles ) )
        setAttributes({ timelineRepeater: newtimeline })
        this.setState( { titles: newtimeline })
    };

    onUpdateDate( newValue, key ) {
        if( newValue == undefined ) {
            return;
        }
        const { titles } = this.state
        const { setAttributes } = this.props
        titles[key].timeline_date = newValue
        let newtimeline = JSON.parse( JSON.stringify( titles ) )
        setAttributes({ timelineRepeater: newtimeline })
        this.setState( { titles: newtimeline })
    }

    onUpdateTitle( newValue, key ) {
        if( newValue == undefined ) {
            return;
        }
        const { titles } = this.state
        const { setAttributes } = this.props
        titles[key].timeline_title = newValue
        let newtimeline = JSON.parse( JSON.stringify( titles ) )
        setAttributes({ timelineRepeater: newtimeline })
        this.setState( { titles: newtimeline })
    }

    onUpdateDesc( newValue, key ) {
        if( newValue == undefined ) {
            return;
        }
        const { titles } = this.state
        const { setAttributes } = this.props
        titles[key].timeline_desc = newValue
        let newtimeline = JSON.parse( JSON.stringify( titles ) )
        setAttributes({ timelineRepeater: newtimeline })
        this.setState( { titles: newtimeline })
    }

    onAdd() {
        const { titles } = this.state
        const { setAttributes } = this.props
        titles.push({ timeline_date:'', timeline_title:'', timeline_desc:'' })
        let newtimeline = JSON.parse( JSON.stringify( titles ) )
        setAttributes({ timelineRepeater: newtimeline })
        this.setState( { titles: newtimeline })
    }

    onRemove( key ) {
        const { titles } = this.state
        const { setAttributes } = this.props
        titles.splice(key,1)
        let newtimeline = JSON.parse( JSON.stringify( titles ) )
        setAttributes({ timelineRepeater: newtimeline })
        this.setState( { titles: newtimeline })
    }

    render() {
        const { titles } = this.state
        return (
            <div class="components-base-control">
                <div class="components-base-control__field">
                    <label class="components-base-control__label" for="inspector-repeater-control">{ escapeHTML( __( 'Timeline Content', 'wp-magazine-modules-lite' ) ) }</label>
                    <PanelBody title={ escapeHTML(__( 'Timeline', 'wp-magazine-modules-lite' )) } class="inspector-repeater-control">
                        { Array.isArray( titles ) &&
                            titles.map(( title, key ) => {
                                let timelineImage = titles[key].timeline_image;
                                let timelineDate = titles[key].timeline_date;
                                let timelineTitle = titles[key].timeline_title;
                                let timelineDesc = titles[key].timeline_desc;
                                    return (
                                        <PanelBody title={ escapeHTML( __( 'Timeline Item', 'wp-magazine-modules-lite' ) + ( key + 1 ) ) } initialOpen={ false }>
                                            <MediaUploadCheck>
                                                <MediaUpload
                                                    onSelect={ ( newImage ) => this.onUpdateImage( newImage, key ) }
                                                    allowedTypes={ ALLOWED_MEDIA_TYPES }
                                                    value={ timelineImage }
                                                    render={ ( { open } ) => (
                                                        <Button
                                                            className={ ! timelineImage ? 'editor-post-featured-image__toggle' : 'editor-post-featured-image__preview' }
                                                            onClick={ open }>
                                                            { !timelineImage && escapeHTML( __( 'Set image', 'wp-magazine-modules-lite' ) ) }
                                                            { ( !!timelineImage && !timelineImage ) && <Spinner /> }
                                                            {  ( timelineImage ) &&
                                                                <img src={ timelineImage } alt={ escapeHTML( __( 'Image', 'wp-magazine-modules-lite' ) ) } />
                                                            }
                                                        </Button>
                                                    ) }
                                            />
                                            </MediaUploadCheck>
                                            { timelineImage &&
                                                <MediaUploadCheck>
                                                    <MediaUpload
                                                        onSelect={ ( newImage ) => this.onUpdateImage( newImage, key ) }
                                                        allowedTypes={ ALLOWED_MEDIA_TYPES }
                                                        value={ timelineImage }
                                                        render={ ( { open } ) => (
                                                            <Button onClick={ open } isSecondary isLarge>
                                                                { escapeHTML( __( 'Replace image', 'wp-magazine-modules-lite' ) ) }
                                                            </Button>
                                                        ) }
                                                    />
                                                </MediaUploadCheck>
                                            }
                                            { timelineImage &&
                                                <MediaUploadCheck>
                                                    <Button onClick={ () => this.onRemoveImage( key ) } isLink isDestructive>
                                                        { escapeHTML( __( 'Remove image', 'wp-magazine-modules-lite' ) ) }
                                                    </Button>
                                                </MediaUploadCheck>
                                            }
                                            <DateTimePicker
                                                currentDate={ timelineDate }
                                                onChange={ ( newValue ) => this.onUpdateDate( newValue, key ) }
                                            />
                                            <TextControl
                                                label={ escapeHTML( __( 'Title', 'wp-magazine-modules-lite' ) ) }
                                                value={ timelineTitle }
                                                onChange={ ( newValue ) => this.onUpdateTitle( newValue, key ) }
                                            />
                                            <TextareaControl
                                                label={ escapeHTML( __( 'Description', 'wp-magazine-modules-lite' ) ) }
                                                value={ timelineDesc }
                                                onChange={ ( newValue ) => this.onUpdateDesc( newValue, key ) }
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