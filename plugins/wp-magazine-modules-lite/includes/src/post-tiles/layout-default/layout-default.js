/**
 * Post Tiles block layout default - editor preview
 */
import Slider from "react-slick";

const { Component } = wp.element;
const { __ } = wp.i18n;
const { escapeHTML } = wp.escapeHtml;
const { withSelect } = wp.data
const { decodeEntities } = wp.htmlEntities;
const { hasFilter, applyFilters } = wp.hooks

class PostTilesDefault extends Component {
    constructor( props ) {
        super(...arguments)
    }

    render() {
        const { featuredSectionOption, slidercontentOption, slidercontentType, sliderwordCount, sliderdateOption, sliderauthorOption , slidercategoryOption, slidercategoriesCount, slidertagsOption, slidertagsCount, slidercommentOption, sliderbuttonOption, sliderbuttonLabel, featuredcontentOption, featuredcontentType, featuredwordCount, featureddateOption, featuredauthorOption , featuredcategoryOption, featuredcategoriesCount, featuredtagsOption, featuredtagsCount, featuredcommentOption, permalinkTarget, postFormatIcon, postMetaIcon, postButtonIcon, carouselType, carouselAuto, carouselDots, carouselControls, carouselLoop, carouselSpeed, fallbackImage, sliderImageSize, sliderImagePosition, sliderImageDimension, featuredImageSize, featuredImagePosition, featuredImageDimension } = this.props.attributes
        const { sliderposts, featuredposts, authors } = this.props

        if( !sliderposts ) {
            return escapeHTML( __( 'Loading posts', 'wp-magazine-modules-lite' ) )
        }

        const hassliderPosts = Array.isArray(sliderposts) && sliderposts.length;
        const hasfeaturedPosts = Array.isArray(featuredposts) && featuredposts.length;
        
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
                let image_url = post.wpmagazine_modules_lite_featured_media_urls[sliderImageDimension][0]
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
                            <a href={post.link} target={ escapeHTML( permalinkTarget ) } style={{ backgroundImage: "url(" + image_url + ")", backgroundSize: sliderImageSize, backgroundPosition: sliderImagePosition }}></a>
                        </div>
                        <div class="cvmm-post-content-all-wrapper">
                            { hascategories &&
                                <span class="cvmm-post-cats-wrap cvmm-post-meta-item">
                                    { 
                                        getcategoryids.map( ( getcategoryid ) => {
                                            return <span class={ `cvmm-post-cat cvmm-cat-${getcategoryid}` }><a href={getcategories[getcategoryid].link} target={ escapeHTML( permalinkTarget ) } >{ getcategories[getcategoryid].name }</a></span>
                                        } )
                                    }
                                </span>
                            }
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


        // Featured content
        let featuredContent;
        if( !featuredSectionOption ) {
            featuredContent = null;
        } else if( !hasfeaturedPosts) {
            featuredContent =  escapeHTML( __( 'No posts found', 'wp-magazine-modules-lite' ) )
        } else {
            featuredContent = featuredposts.map( ( post, index ) => {
                let image_url = post.wpmagazine_modules_lite_featured_media_urls[featuredImageDimension]['0']
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
                    getcategoryids = post.categories.slice( 0, featuredcategoriesCount )
                    getcategories = post.categories_names
                }
                
                let gettagids
                let gettags
                if (typeof post.tags !== 'undefined') {
                    gettagids = post.tags.slice( 0, featuredtagsCount )
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

                let hascategories = Array.isArray(getcategoryids) && getcategoryids.length && featuredcategoryOption;
                let hastags = Array.isArray(gettagids) && gettagids.length && featuredtagsOption;
                
                return (
                    <article id={`post-${post.id}`} class={ `cvmm-post post-format--${getformat}` }>
                        <div class="cvmm-post-thumb">
                            <a href={post.link} target={ escapeHTML( permalinkTarget ) } style={{ backgroundImage: "url(" + image_url + ")", backgroundSize: featuredImageSize, backgroundPosition: featuredImagePosition }}></a>
                        </div>
                        <div class="cvmm-post-content-all-wrapper">
                            { hascategories &&
                                <span class="cvmm-post-cats-wrap cvmm-post-meta-item">
                                    { 
                                        getcategoryids.map( ( getcategoryid ) => {
                                            return <span class={ `cvmm-post-cat cvmm-cat-${getcategoryid}` }><a href={getcategories[getcategoryid].link} target={ escapeHTML( permalinkTarget ) } >{ getcategories[getcategoryid].name }</a></span>
                                        } )
                                    }
                                </span>
                            }
                            <h2 class="cvmm-post-title">
                                <a href={post.link} target={ escapeHTML( permalinkTarget ) }>
                                    { decodeEntities( post.title.rendered.trim() ) }
                                </a>
                            </h2>
                            <div class={ `cvmm-post-meta${getmetaIcon}` }>
                                { featureddateOption &&
                                    <span class="cvmm-post-date cvmm-post-meta-item">
                                        <a href={ `${post.link}` } target={ escapeHTML( permalinkTarget ) }>{ moment( post.date_gmt ).local().format('MMMM DD, Y') }</a>
                                    </span>
                                }
                                { ( (typeof post.author !== 'undefined' ) && featuredauthorOption ) &&
                                    <span class="cvmm-post-author-name cvmm-post-meta-item"><a href={author_url} target={ escapeHTML( permalinkTarget ) } >{ author_name }</a></span>
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
                                { ( (typeof post.comments_number !== 'undefined' ) && featuredcommentOption ) && 
                                    <span class="cvmm-post-comments-wrap cvmm-post-meta-item">
                                        <a href={ `${post.link}/#comments` } target={ escapeHTML( permalinkTarget ) }>
                                            { post.comments_number }
                                            <span class="cvmm-comment-txt">{ escapeHTML( __( 'Comments', 'wp-magazine-modules-lite' ) ) }</span>
                                        </a>
                                    </span>
                                }
                            </div>
                            { ( featuredcontentOption === true && ( typeof post[featuredcontentType] !== 'undefined' ) ) &&
                                <div class="cvmm-post-content" dangerouslySetInnerHTML={{ __html: post[featuredcontentType].rendered.trim().split(' ').slice(0,featuredwordCount).join(' ') }} />
                            }
                            { hasFilter( 'CodevibrantSocialshareEditorHTML', 'codevibrant-socialshare' ) &&
                                applyFilters( 'CodevibrantSocialshareEditorHTML', this.props )
                            }
                        </div>
                    </article>
                )
            });
        }

        let postClass;
        if( featuredSectionOption ) {
            postClass = `cvmm-post-tiles-partial-width`
        } else {
            postClass = `cvmm-post-tiles-full-width`
        }

        const settings = {
            dots: carouselDots,
            infinite: carouselLoop,
            autoplay: carouselAuto,
            arrows: carouselControls,
            fade: carouselType,
            speed: carouselSpeed,
            slidesToShow: 1,
            slidesToScroll: 1
        }
        return (
            <div class="cvmm-post-tiles-block-main-content-wrap">
                <Slider {...settings} className={ `cvmm-post-tiles-slider-post-wrapper ${postClass}` }>{ sliderContent }</Slider>
                { featuredContent &&
                    <div className="cvmm-featured-post-wrapper">{ featuredContent }</div>
                }
            </div>
        );
    }
}

export default withSelect( ( select, props ) => {
    const { sliderpostCount, sliderposttype, sliderpostCategory, sliderorderBy, sliderorder, featuredposttype, featuredpostCategory, featuredorderBy, featuredorder } = props.attributes;
    const { getEntityRecords, getAuthors, getTaxonomies } = select( 'core' );
    let registeredCategories = getTaxonomies();
    var slidertaxonomy_name = [];
    var featuredtaxonomy_name = [];
    let sliderrestBase  = null;
    let featuredrestBase  = null;

    if( registeredCategories ) {
        registeredCategories.map( (item ) => {
            if (item.types.includes(sliderposttype)){
                slidertaxonomy_name.push(item.slug);

                if (slidertaxonomy_name.length === 1) {
                    sliderrestBase = item.rest_base;
                }
            }

            if (item.types.includes(featuredposttype)){
                featuredtaxonomy_name.push(item.slug);

                if (featuredtaxonomy_name.length === 1) {
                    featuredrestBase = item.rest_base;
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
    
    //Featured posts
    const featuredPostsQuery = {
        order : featuredorder,
        orderby: featuredorderBy,
        per_page: 2,
    };
    
    featuredPostsQuery[featuredrestBase] = featuredpostCategory;
    
    return {
        sliderposts: getEntityRecords( 'postType', sliderposttype, sliderPostsQuery ),
        featuredposts: getEntityRecords( 'postType', featuredposttype, featuredPostsQuery ),
        authors: getAuthors(),
    };
} )( PostTilesDefault );