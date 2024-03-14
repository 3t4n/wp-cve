/**
 * Includes the settings of general tab.
 * 
 */
import RepeaterControl from '../../block-base/controls/block-ticker-repeater-control/repeater'

const { Component, Fragment } = wp.element;
const { __ } = wp.i18n;
const { escapeHTML } = wp.escapeHtml;
const { PanelBody, TextControl, SelectControl, ToggleControl, RangeControl } = wp.components;
const { withSelect } = wp.data

class GeneralInspector extends Component {
    constructor( props ) {
        super( ...arguments )
    }

    render() {
        const { blockTitle, blockTitleLayout, blockTitleAlign, tickerCaption, contentType, postCategory, postCount, permalinkTarget, marqueeDirection, marqueeDuration, marqueeStart } = this.props.attributes
        const { setAttributes, categoriesList } = this.props

        const hascategoriesList = Array.isArray(categoriesList) && categoriesList.length

        const allCategories = [];
        if( hascategoriesList ) {
            allCategories.push({ label: escapeHTML( __( 'All', 'wp-magazine-modules-lite' ) ), value: '' });
            categoriesList.forEach( ( category ) => {
                allCategories.push({ label: category.name + ' (' + category.count + ')', value: category.id });
            });
        } else {
            allCategories.push({ label: escapeHTML( __( 'All', 'wp-magazine-modules-lite' ) ), value: '' });
        }

        return (
            <Fragment>
                <PanelBody title={ escapeHTML( __( 'Basic Settings', 'wp-magazine-modules-lite' ) ) }>
                    <TextControl
                        label={ escapeHTML( __( 'Block Title', 'wp-magazine-modules-lite' ) ) }
                        value={ blockTitle }
                        placeholder={ escapeHTML( __( 'Add title here..', 'wp-magazine-modules-lite' ) ) }
                        onChange={ ( newblockTitle ) => setAttributes( { blockTitle: newblockTitle } ) }
                    />
                    { blockTitle &&
                        <SelectControl
                            label = { escapeHTML( __( 'Block Title Layout', 'wp-magazine-modules-lite' ) ) }
                            value={ blockTitleLayout }
                            options={ [
                                { value: 'default', label: 'Default' },
                                { value: 'one', label: 'One' },
                                { value: 'two', label: 'Two' },
                                { value: 'three', label: 'Three ( pro )', disabled: true },
                                { value: 'four', label: 'Four ( pro )', disabled: true },
                                { value: 'five', label: 'Five ( pro )', disabled: true }
                            ] }
                            onChange={ ( newblockTitleLayout ) => setAttributes( { blockTitleLayout: newblockTitleLayout } ) }
                        />
                    }
                    { blockTitle &&
                        <SelectControl
                            label={ escapeHTML( __( 'Text Align', 'wp-magazine-modules-lite' ) ) }
                            value={ blockTitleAlign }
                            options={ [
                                { value: 'left', label: escapeHTML( __( 'Left', 'wp-magazine-modules-lite' ) ) },
                                { value: 'center', label: escapeHTML( __( 'Center', 'wp-magazine-modules-lite' ) ) },
                                { value: 'right', label: escapeHTML( __( 'Right', 'wp-magazine-modules-lite' ) ) }
                            ] }
                            onChange={ ( newblockTitleAlign ) => setAttributes( { blockTitleAlign: newblockTitleAlign } ) }
                        />
                    }
                    <TextControl
                        label={ escapeHTML( __( 'Caption', 'wp-magazine-modules-lite' ) ) }
                        value={ tickerCaption }
                        placeholder={ escapeHTML( __( 'Add caption here..', 'wp-magazine-modules-lite' ) ) }
                        onChange={ ( newtickerCaption ) => setAttributes( { tickerCaption: newtickerCaption } ) }
                    />
                    <SelectControl
                        label = { escapeHTML( __( 'Content Type', 'wp-magazine-modules-lite' ) ) }
                        value={ contentType }
                        options={ [
                            { value: 'post', label: 'Post' },
                            { value: 'custom', label: 'Custom ( pro )', disabled: true }
                        ] }
                        onChange={ ( newcontentType ) => setAttributes( { contentType: newcontentType } ) }
                    />
                    { ( contentType == 'post' ) &&
                        <SelectControl
                            label = { escapeHTML( __( 'Category', 'wp-magazine-modules-lite' ) ) }
                            value={ postCategory }
                            options={ allCategories }
                            onChange={ ( newpostCategory ) => setAttributes( { postCategory: newpostCategory } ) }
                        />
                    }
                    { ( contentType == 'post' ) &&
                        <RangeControl
                            label={ escapeHTML( __( 'Post Count ( pro )', 'wp-magazine-modules-lite' ) ) }
                            value={ postCount }
                            onChange={ ( newpostCount ) => setAttributes( { postCount: newpostCount } ) }
                            min={ 1 }
                            max={ 6 }
                        />
                    }
                    { ( contentType == 'custom' ) &&
                        <RepeaterControl { ...this.props } />
                    }
                </PanelBody>
                <PanelBody title={ escapeHTML( __( 'Extra Settings', 'wp-magazine-modules-lite' ) ) } initialOpen = { false }>
                    <SelectControl
                        label = { escapeHTML( __( 'Links open in', 'wp-magazine-modules-lite' ) ) }
                        value={ permalinkTarget }
                        options={ [
                            { value: '_self', label: 'Same Tab' },
                            { value: '_blank', label: 'New Tab' }
                        ] }
                        onChange={ ( newpermalinkTarget ) => setAttributes( { permalinkTarget: newpermalinkTarget } ) }
                    />
                </PanelBody>
                
                <PanelBody title={ escapeHTML( __( 'Marquee Options', 'wp-magazine-modules-lite' ) ) } initialOpen = { false }>
                    <SelectControl
                        label = { escapeHTML( __( 'Direction', 'wp-magazine-modules-lite' ) ) }
                        value={ marqueeDirection }
                        options={ [
                            { value: 'left', label: 'Left' },
                            { value: 'right', label: 'Right' }
                        ] }
                        onChange={ ( newmarqueeDirection ) => setAttributes( { marqueeDirection: newmarqueeDirection } ) }
                    />
                    <TextControl
                        label={ escapeHTML( __( 'Speed ( pro )', 'wp-magazine-modules-lite' ) ) }
                        type="number"
                        min={ 1000 }
                        max={ 20000 }
                        step={ 100 }
                        value={ marqueeDuration }
                        disabled = { true }
                        onChange={ ( newmarqueeDuration ) => setAttributes( { marqueeDuration: newmarqueeDuration } ) }
                    />
                    <TextControl
                        label={ escapeHTML( __( 'Delay before start ( pro )', 'wp-magazine-modules-lite' ) ) }
                        type="number"
                        min={ 500 }
                        max={ 3000 }
                        step={ 100 }
                        disabled = { true }
                        value={ marqueeStart }
                        onChange={ ( newmarqueeStart ) => setAttributes( { marqueeStart: newmarqueeStart } ) }
                    />
                </PanelBody>
            </Fragment>
        )
    }
}

export default withSelect( ( select, props ) => {
    const { getEntityRecords } = select( 'core' );
    const categoryQuery = {
        hide_empty: true,
        per_page: 100
    }
    return {
        categoriesList: getEntityRecords( 'taxonomy', 'category', categoryQuery ),
    };
} )( GeneralInspector );