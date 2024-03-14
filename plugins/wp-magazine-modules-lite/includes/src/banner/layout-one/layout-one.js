/**
 * Banner layout one - preview
 */
const { Component, Fragment } = wp.element;
const { __ } = wp.i18n;
const { escapeHTML } = wp.escapeHtml;
const { withSelect } = wp.data

class BannerOne extends Component {
    constructor( props ) {
        super( ...arguments )
    }

    render() {
        const { contentType, bannerImage, titleOption, bannerTitle, bannerTitleLink, descOption, bannerDesc, button1Option, button1Label, button1Link, button2Option, button2Label, button2Link, permalinkTarget } = this.props.attributes
        const { pageContent } = this.props
        let title, description, imageUrl, banner_title_link;
        switch( contentType ) {
            case 'page': if( !pageContent ) {
                            return escapeHTML( __( 'Loading page', 'wp-magazine-modules-lite' ) );
                        } else if( Array.isArray( pageContent ) && pageContent.length ) {
                            title = pageContent[0].title.rendered;
                            banner_title_link = pageContent[0].link;
                            description = pageContent[0].content.rendered;
                            imageUrl = pageContent[0].wpmagazine_modules_lite_featured_media_urls.full[0];
                        } else {
                            return escapeHTML( pageContent );
                        }
                        break;
            default:    title = bannerTitle;
                        banner_title_link = bannerTitleLink
                        description = bannerDesc;
                        imageUrl = bannerImage;
                    break;
        }
        return (
            <Fragment>
                <div class="cvmm-banner-content-wrap">
                    <div class="cvmm-banner-content">
                        { imageUrl &&
                            <figure class="cvmm-banner-thumb">
                                <img src={ imageUrl } />
                            </figure>
                        }
                        <div class="cvmm-banner-meta-wrap">
                            { titleOption &&
                                <h2 class="cvmm-banner-title"><a href={ `${banner_title_link}` } target={ `${permalinkTarget}` }>{ escapeHTML( title ) }</a></h2>
                            }
                            { descOption &&
                                <div class="cvmm-banner-desc" dangerouslySetInnerHTML={ { __html: description.trim() } } />
                            }
                            { ( button1Option || button2Option ) &&
                                <div class="banner-button-wrap">
                                    { button1Option &&
                                        <a href={ escapeHTML( button1Link ) } target={ `${permalinkTarget}` } class="cvmm-banner-button-one">{ escapeHTML( button1Label ) }</a>
                                    }
                                    { button2Option &&
                                        <a href={ escapeHTML( button2Link ) } target={ `${permalinkTarget}` } class="cvmm-banner-button-two">{ escapeHTML( button2Label ) }</a>
                                    }
                                </div>
                            }
                        </div>{/*cvmm-banner-meta-wrap*/}
                    </div>
                </div>
            </Fragment>
        );
    }
}

export default withSelect( ( select, props ) => {
    const { getEntityRecords } = select( 'core' );
    const { bannerPage } = props.attributes
    let content;
    if( bannerPage ) {
        const pageQuery = {
            status: 'publish',
            slug: bannerPage
        }

        content = getEntityRecords( 'postType', 'page', pageQuery )
    } else {
        content = escapeHTML( __( 'Select a page', 'wp-magazine-modules-lite' ) );
    }
    return {
        pageContent: content,
    };
} )( BannerOne );