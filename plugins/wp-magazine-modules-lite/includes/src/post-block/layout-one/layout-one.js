/**
 * "Post Block" block layout one - editor preview
 */
const { Component } = wp.element;
const { __ } = wp.i18n;
const { escapeHTML } = wp.escapeHtml;
const { withSelect } = wp.data
const { decodeEntities } = wp.htmlEntities;
const { hasFilter, applyFilters } = wp.hooks

class PostBlockOne extends Component {
    constructor( props ) {
        super(...arguments)
    }

    getStructuresContent( content ) {
        let i, structuredContent = [];
        for( i=0 ; i < content.length; i++ ) {
            if( ( i % 4 ) == 0 ) {
                structuredContent.push( <div class="cvmm-post-block-main-post-wrap">{ content[i] }</div> )
            } else {
                if( content[i+2] ) {
                    structuredContent.push( <div class="cvmm-post-block-trailing-post-wrap">
                        { 
                            content[i]
                        }
                        {
                            content[i+1]
                        }
                        {
                            content[i+2]
                        }
                        </div>
                    )
                    i = i + 2;
                } else if( content[i+1] ) {
                    structuredContent.push( <div class="cvmm-post-block-trailing-post-wrap">
                        { 
                            content[i]
                        }
                        {
                            content[i+1]
                        }
                        </div>
                    )
                    i++;
                } else {
                    structuredContent.push( <div class="cvmm-post-block-trailing-post-wrap">{ content[i] }</div> )
                }
            }
        }
        return structuredContent;
    }

    render() {
        const { imageSize, fallbackImage, contentOption, contentType, wordCount, dateOption, authorOption , categoryOption, categoriesCount, tagsOption, tagsCount, commentOption, permalinkTarget, buttonOption, buttonLabel, postFormatIcon, postMetaIcon, postButtonIcon } = this.props.attributes
        const { posts, authors } = this.props

        if( !posts ) {
            return escapeHTML( __( 'Loading posts', 'wp-magazine-modules-lite' ) )
        }

        const hasPosts = Array.isArray(posts) && posts.length;
        if( !hasPosts ) {
            return escapeHTML( __( 'No posts found', 'wp-magazine-modules-lite' ) )
        }
        
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
        const content = posts.map( ( post, index ) => {
            let cvmmImageSize
            if( ( index % 4 ) == 0 ) {
                cvmmImageSize = 'full'
            } else {
                cvmmImageSize = imageSize
            }
            let image_url = post.wpmagazine_modules_lite_featured_media_urls[cvmmImageSize]['0']
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
                getcategoryids = post.categories.slice( 0, categoriesCount )
                getcategories = post.categories_names
            }
            
            let gettagids
            let gettags
            if (typeof post.tags !== 'undefined') {
                gettagids = post.tags.slice( 0, tagsCount )
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

            let hascategories = Array.isArray(getcategoryids) && getcategoryids.length && categoryOption;
            let hastags = Array.isArray(gettagids) && gettagids.length && tagsOption;
            
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
                            { hascategories &&
                                <span class="cvmm-post-cats-wrap cvmm-post-meta-item">
                                    { 
                                        getcategoryids.map( ( getcategoryid ) => {
                                            return <span class={ `cvmm-post-cat cvmm-cat-${getcategoryid}` }><a href={getcategories[getcategoryid].link} target={ escapeHTML( permalinkTarget ) } >{ getcategories[getcategoryid].name }</a></span>
                                        } )
                                    }
                                </span>
                            }
                            { dateOption &&
                                <span class="cvmm-post-date cvmm-post-meta-item">
                                    <a href={ `${post.link}` } target={ escapeHTML( permalinkTarget ) }>{ moment( post.date_gmt ).local().format('MMMM DD, Y') }</a>
                                </span>
                            }
                            { ( (typeof post.author !== 'undefined' ) && authorOption ) &&
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
                            { ( (typeof post.comments_number !== 'undefined' ) && commentOption ) && 
                                <span class="cvmm-post-comments-wrap cvmm-post-meta-item">
                                    <a href={ `${post.link}/#comments` } target={ escapeHTML( permalinkTarget ) }>
                                        { post.comments_number }
                                        <span class="cvmm-comment-txt">{ escapeHTML( __( 'Comments', 'wp-magazine-modules-lite' ) ) }</span>
                                    </a>
                                </span>
                            }
                        </div>
                        { ( contentOption === true && ( typeof post[contentType] !== 'undefined' ) ) &&
                            <div class="cvmm-post-content" dangerouslySetInnerHTML={{ __html: post[contentType].rendered.trim().split(' ').slice(0,wordCount).join(' ') }} />
                        }
                        { hasFilter( 'CodevibrantSocialshareEditorHTML', 'codevibrant-socialshare' ) &&
                            applyFilters( 'CodevibrantSocialshareEditorHTML', this.props )
                        }
                        { ( buttonOption && buttonLabel ) &&
                            <div class="cvmm-read-more">
                                <a href={post.link} target={ escapeHTML( permalinkTarget ) }>{ buttonLabel }
                                    { postButtonIcon &&
                                        <i class="fas fa-arrow-right"></i>
                                    }
                                </a>
                            </div>
                        }
                    </div>{ /* .cvmm-post-content-all-wrapper */ }
                </article>
            )
        });

        return <div className={ `cvmm-post-wrapper` }>{ this.getStructuresContent( content ) }</div>
    }
}

export default withSelect( ( select, props ) => {
    const { postCount, posttype, postCategory, orderBy, order } = props.attributes;
    const { getEntityRecords, getAuthors, getTaxonomies } = select( 'core' );
    let registeredCategories = getTaxonomies();
    var taxonomy_name = [];
    let restBase = null;

    if( registeredCategories ) {
        registeredCategories.map( (item ) => {
            if (item.types.includes(posttype)){
                taxonomy_name.push(item.slug);

                if (taxonomy_name.length === 1) {
                    restBase = item.rest_base;
                }
            }
        });
    }

    const PostsQuery = {
        order : order,
        orderby: orderBy,
        per_page: postCount,
    };
    
    PostsQuery[restBase] = postCategory;
    
    return {
        posts: getEntityRecords( 'postType', posttype, PostsQuery ),
        authors: getAuthors(),
    };
} )( PostBlockOne );