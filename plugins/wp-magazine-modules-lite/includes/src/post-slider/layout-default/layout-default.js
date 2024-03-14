/**
 * Slider block layout default - editor preview
 */
import Slider from "react-slick";
const { Component } = wp.element;
const { __ } = wp.i18n;
const { escapeHTML } = wp.escapeHtml;
const { withSelect } = wp.data
const { decodeEntities } = wp.htmlEntities;
const { hasFilter, applyFilters } = wp.hooks

class SliderDefault extends Component {
    constructor( props ) {
        super(...arguments)
    }

    render() {
        const { imageSize, slidercontentOption, slidercontentType, sliderwordCount, sliderdateOption, sliderauthorOption , slidercategoryOption, slidercategoriesCount, slidertagsOption, slidertagsCount, slidercommentOption, sliderbuttonOption, sliderbuttonLabel, permalinkTarget, postFormatIcon, postMetaIcon, postButtonIcon, carouselType, carouselAuto, carouselDots, carouselControls, carouselLoop, carouselSpeed, carouselAutoplaySpeed, fallbackImage } = this.props.attributes
        const { sliderposts, authors } = this.props

        if( !sliderposts ) {
            return escapeHTML( __( 'Loading posts', 'wp-magazine-modules-lite' ) )
        }

        const hassliderPosts = Array.isArray(sliderposts) && sliderposts.length;
        
        const getPostAuthorName = ( author_id ) => {
            for( let author in authors ){
                if( authors[author].id === author_id ) {
                    return authors[author].name;
                }
            }
        };

        const getPostAuthorUrl = ( author_id ) => {
            for(let author in authors){
                if( authors[author].id === author_id ) {
                    return authors[author].link;
                }
            }
        };
        let sliderContent = null;
        if( !hassliderPosts ) {
            sliderContent =  escapeHTML( __( 'No posts found', 'wp-magazine-modules-lite' ) )
        } else {
            sliderContent = sliderposts.map( ( post, index ) => {
                let image_url = post.wpmagazine_modules_lite_featured_media_urls[imageSize]['0']
                if( typeof image_url === 'undefined' ) {
                    if( fallbackImage ) {
                        image_url = fallbackImage
                    } else {
                        image_url = BlocksBuildObject.defaultImage
                    }
                }
                let author_name
                let author_url
                if( typeof post.author !== 'undefined' ) {
                    author_name = getPostAuthorName( post.author )
                    author_url = getPostAuthorUrl( post.author )
                }
                let getcategoryids
                let getcategories
                if ( ( typeof post.categories !== 'undefined' ) && ( post.categories != null ) ) {
                    getcategoryids = post.categories.slice( 0, slidercategoriesCount )
                    getcategories = post.categories_names
                }
                
                let gettagids
                let gettags
                if (typeof post.tags !== 'undefined') {
                    gettagids = post.tags.slice( 0, slidertagsCount )
                    gettags = post.tags_names
                }
                let getformat
                if (typeof post.tags !== 'undefined') {
                    getformat = post.format
                }

                if( postFormatIcon ) {
                    getformat += ' cvmm-icon'
                }
                
                let getmetaIcon
                if( postMetaIcon ) {
                    getmetaIcon = " cvmm-meta-icon-show"
                } else {
                    getmetaIcon = " cvmm-meta-icon-hide"
                }

                let hascategories = Array.isArray(getcategoryids) && getcategoryids.length && slidercategoryOption;
                let hastags = Array.isArray(gettagids) && gettagids.length && slidertagsOption;
                
                return (
                    <article id={`post-${post.id}`} class={ `cvmm-post post-format--${getformat}` }>
                        <div class="cvmm-post-thumb">
                            <a href={post.link} target={ escapeHTML( permalinkTarget ) }><img src={image_url} alt={decodeEntities( post.title.rendered.trim() )}/></a>
                        </div>
                        <div className="cvmm-post-content-all-wrapper">
                            <h2 class="cvmm-post-title">
                                <a href={post.link} target={ escapeHTML( permalinkTarget ) }>
                                    { decodeEntities( post.title.rendered.trim() ) }
                                </a>
                            </h2>
                            <div class={ `cvmm-post-meta${getmetaIcon}` }>
                                { sliderdateOption &&
                                    <span class="cvmm-post-date cvmm-post-meta-item">
                                        <a href={ `${post.link}` } target={ escapeHTML( permalinkTarget ) }>{ moment( post.date_gmt ).local().format('MMMM DD, Y') }</a>
                                    </span>
                                }
                                { ( (typeof post.author !== 'undefined' ) && sliderauthorOption ) &&
                                    <span class="cvmm-post-author-name cvmm-post-meta-item"><a href={author_url} target={ escapeHTML( permalinkTarget ) } >{ author_name }</a></span>
                                }
                                { hascategories &&
                                    <span class="cvmm-post-cats-wrap cvmm-post-meta-item">
                                        { 
                                            getcategoryids.map( ( getcategoryid ) => {
                                                return <span class={ `cvmm-post-cat cvmm-cat-${getcategoryid}` }><a href={getcategories[getcategoryid].link} target={ escapeHTML( permalinkTarget ) } >{ getcategories[getcategoryid].name }</a></span>
                                            } )
                                        }
                                    </span>
                                }
                                { ( hastags == true ) &&
                                    <span class="cvmm-post-tags-wrap cvmm-post-meta-item">
                                        { 
                                            gettagids.map( ( gettagid ) => {
                                                return <span class="cvmm-post-tag"><a href={gettags[gettagid].link} target={ escapeHTML( permalinkTarget ) }>{ gettags[gettagid].name }</a></span>
                                            } )
                                        }
                                    </span>
                                }
                                { ( (typeof post.comments_number !== 'undefined' ) && slidercommentOption ) && 
                                    <span class="cvmm-post-comments-wrap cvmm-post-meta-item">
                                        <a href={ `${post.link}/#comments` } target={ escapeHTML( permalinkTarget ) }>
                                            { post.comments_number }
                                            <span class="cvmm-comment-txt">{ escapeHTML( __( 'Comments', 'wp-magazine-modules-lite' ) ) }</span>
                                        </a>
                                    </span>
                                }
                            </div>
                            { ( slidercontentOption === true && ( typeof post[slidercontentType] !== 'undefined' ) ) &&
                                <div class="cvmm-post-content" dangerouslySetInnerHTML={{ __html: post[slidercontentType].rendered.trim().split(' ').slice(0,sliderwordCount).join(' ') }} />
                            }
                            { hasFilter( 'CodevibrantSocialshareEditorHTML', 'codevibrant-socialshare' ) &&
                                applyFilters( 'CodevibrantSocialshareEditorHTML', this.props )
                            }
                            { ( sliderbuttonOption && sliderbuttonLabel ) &&
                                <div class="cvmm-read-more">
                                    <a href={post.link} target={ escapeHTML( permalinkTarget ) }>{ sliderbuttonLabel }
                                        { postButtonIcon &&
                                            <i class="fas fa-arrow-right"></i>
                                        }
                                    </a>
                                </div>
                            }
                        </div>    
                    </article>
                )
            });
        }

        const settings = {
            dots: carouselDots,
            infinite: carouselLoop,
            autoplay: carouselAuto,
            arrows: carouselControls,
            fade: carouselType,
            speed: carouselSpeed,
            autoplaySpeed: carouselAutoplaySpeed,
            slidesToShow: 1,
            slidesToScroll: 1,
            nextArrow: <span class="slickArrow prev-icon"><i class="fas fa-chevron-left"></i></span>,
            prevArrow: <span class="slickArrow next-icon"><i class="fas fa-chevron-right"></i></span>
        }
        return (
            <div class="cvmm-slider-block-main-content-wrap">
                <Slider {...settings} className={ `cvmm-slider-post-wrapper` }>{ sliderContent }</Slider>
            </div>
        );
    }
}

export default withSelect( ( select, props ) => {
    const { sliderpostCount, sliderposttype, sliderpostCategory, sliderorderBy, sliderorder } = props.attributes;
    const { getEntityRecords, getAuthors, getTaxonomies } = select( 'core' );
    let registeredCategories = getTaxonomies();
    var slidertaxonomy_name = [];
    let sliderrestBase  = null;
    if( registeredCategories ) {
        registeredCategories.map( (item ) => {
            if (item.types.includes(sliderposttype)){
                slidertaxonomy_name.push(item.slug);

                if (slidertaxonomy_name.length === 1) {
                    sliderrestBase = item.rest_base;
                }
            }
        });
    }

    const sliderPostsQuery = {
        order : sliderorder,
        orderby: sliderorderBy,
        per_page: sliderpostCount,
    };
    
    sliderPostsQuery[sliderrestBase] = sliderpostCategory;
    
    return {
        sliderposts: getEntityRecords( 'postType', sliderposttype, sliderPostsQuery ),
        authors: getAuthors(),
    };
} )( SliderDefault );