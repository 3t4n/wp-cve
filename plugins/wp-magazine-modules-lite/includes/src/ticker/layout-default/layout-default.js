/**
 * Ticker block default - editor preview
 * 
 */
const { Component } = wp.element;
const { __ } = wp.i18n;
const { escapeHTML } = wp.escapeHtml;
const { decodeEntities } = wp.htmlEntities;
const { withSelect } = wp.data

class TickerDefault extends Component {
    constructor( props ) {
        super(...arguments)
    }

    render() {
        const { tickerCaption, contentType, tickerRepeater, permalinkTarget, marqueeDirection, marqueeDuration, marqueeStart } = this.props.attributes
        const { posts } = this.props
        let tickerContent;
        const hasPosts = Array.isArray(posts) && posts.length;

        switch( contentType ) {
            case 'custom': tickerContent = tickerRepeater.map( ( ticker, index ) => {
                                return (
                                    <div class="cvmm-ticker-single-title">
                                        { decodeEntities( ticker.ticker_title ) }
                                    </div>
                                )
                            })
                            break;
            default: if( !hasPosts ) {
                        tickerContent = <div class="cvmm-ticker-single-title">{ escapeHTML( __( 'No posts found', 'wp-magazine-modules-lite' ) ) }</div>
                    }
                    if( hasPosts ) {
                        tickerContent = posts.map( ( post, index ) => {
                            return (
                                <div class="cvmm-ticker-single-title">
                                    <a href={ post.link } target={ permalinkTarget }>{ decodeEntities( post.title.rendered.trim() ) }</a>
                                </div>
                            )
                        })
                    }
                    break;
        }

        return(
            <div class="cvmm-ticker-wrapper">
                { tickerCaption &&
                    <span class="cvmm-ticker-caption">{ escapeHTML( tickerCaption ) }</span>
                }
                <div  className={ `cvmm-ticker-content` }>
                    <marquee scrollamount={ ( marqueeDuration/50000 ) } scrolldelay={ marqueeStart } direction={ marqueeDirection }><div class="js-marquee">{ tickerContent }</div></marquee>
                </div>
            </div>
        );
    }
}

export default withSelect( ( select, props ) => {
    const { postCount, postCategory } = props.attributes;
    const { getEntityRecords } = select( 'core' );

    const PostsQuery = {
        status: 'publish',
        per_page: postCount,
    };
    
    PostsQuery['categories'] = postCategory;
    
    return {
        posts: getEntityRecords( 'postType', 'post', PostsQuery )
    };
} )( TickerDefault );