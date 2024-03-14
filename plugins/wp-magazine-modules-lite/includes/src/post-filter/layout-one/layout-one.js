/**
 * Post Filter block layout one - editor preview
 */
const { Component } = wp.element;
const { __ } = wp.i18n;
const { escapeHTML } = wp.escapeHtml;
const { withSelect } = wp.data
const { decodeEntities } = wp.htmlEntities;
const { hasFilter, applyFilters } = wp.hooks

class PostFilterOne extends Component {
    constructor( props ) {
        super(...arguments)
        const { postCategory } =  this.props.attributes
        this.state = {
            activeTerm: postCategory['0']
        }
    }

    setActiveCat( termid ) {
        this.setState({
            activeTerm: termid
        })
    }

    removeUndefined( el ) {
        if( el != 'null' || el != 'undefined' ) {
            return el;
        }
    }

    getStructuresContent( content, postCount ) {
        if( !( Array.isArray(content) && content.length ) ) {
            return escapeHTML( __( 'Loading posts', 'wp-magazine-modules-lite' ) )
        }
        content = ( content.filter( this.removeUndefined ) ).slice( 0, postCount )
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
        const { blockTitle, blockTitleLayout, postCount, fallbackImage, contentOption, contentType, wordCount, thumbOption, titleOption, dateOption, authorOption , categoryOption, categoriesCount, tagsOption, tagsCount, commentOption, permalinkTarget, buttonOption, buttonLabel, postFormatIcon, postMetaIcon, postButtonIcon, blockColumn, postMargin, postCategory } = this.props.attributes
        const { posts, authors, termsQuery } = this.props
        
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
            let image_url = post.wpmagazine_modules_lite_featured_media_urls.full['0']
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
            let getformat = "standard"
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
            
            let currentTerm = this.state.activeTerm
            if( currentTerm ) {
                let postShow = post.categories.includes( currentTerm )
                if( !postShow ) {
                    return null;
                }
            }

            return (
                <article id={`post-${post.id}`} key={ index } class={ `cvmm-post post-format--${getformat}`}>
                    { thumbOption &&
                        <div class="cvmm-post-thumb">
                            <a href={post.link} target={ escapeHTML( permalinkTarget ) }><img src={image_url} alt={decodeEntities( post.title.rendered.trim() )}/></a>
                        </div>
                    }
                    <div className="cvmm-post-content-all-wrapper">
                        { hascategories &&
                            <span class="cvmm-post-cats-wrap cvmm-post-meta-item">
                                { 
                                    getcategoryids.map( ( getcategoryid ) => {
                                        return <span class={ `cvmm-post-cat cvmm-cat-${getcategoryid}` }><a href={getcategories[getcategoryid].link} target={ escapeHTML( permalinkTarget ) } >{ getcategories[getcategoryid].name }</a></span>
                                    } )
                                }
                            </span>
                        }
                        { titleOption &&
                            <h2 class="cvmm-post-title">
                                <a href={post.link} target={ escapeHTML( permalinkTarget ) }>
                                    { decodeEntities( post.title.rendered.trim() ) }
                                </a>
                            </h2>
                        }
                        <div class={ `cvmm-post-meta${getmetaIcon}` }>
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
                    </div>
                </article>
            )
        });

        let postClass;
        if( postMargin ) {
            postClass = `cvmm-post--imagemargin column--${blockColumn}`
        } else {
            postClass = `cvmm-post-no--imagemargin column--${blockColumn}`
        }
        return (
            <div class="cvmm-title-posts-main-wrapper">
                <div class="cvmm-post-filter-cat-title-wrapper">
                    { blockTitle &&
                        <h2 className={ `cvmm-block-title layout--${blockTitleLayout}` }><span>{ blockTitle }</span></h2>
                    }
                    <ul class="cvmm-term-titles-wrap">
                        { termsQuery &&
                            termsQuery.map( ( term, index ) => {
                                let activeCat = "";
                                if( term.id == this.state.activeTerm ) {
                                    activeCat = " active"
                                }
                                return(
                                    <li id={ `term-${term.id}` } class={ `single-term-title${activeCat}`  } onClick={ () => { this.setActiveCat( term.id ) } }>
                                        { escapeHTML( term.name ) }
                                    </li>
                                )
                            })
                        }
                    </ul>
                </div> 
                <div className={ `cvmm-post-wrapper ${postClass}` }>
                    { this.getStructuresContent( content, postCount ) }
                </div>
            </div>
        );
    }
}

export default withSelect( ( select, props ) => {
    const { posttype, postCategory, orderBy, order } = props.attributes;
    const { getEntityRecords, getAuthors, getTaxonomies } = select( 'core' );
    let registeredCategories = getTaxonomies();
    var taxonomy_name = [];
    let restBase = null;
    if( registeredCategories ) {
        registeredCategories.map( (item ) => {
            if (item.types.includes(posttype)) {
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
        per_page: -1,
        status: 'publish'
    };
    PostsQuery[restBase] = postCategory;
    let listofterms = getEntityRecords( 'taxonomy', taxonomy_name[0], { hide_empty: true, include: postCategory, per_page: 100 } )
    return {
        posts: getEntityRecords( 'postType', posttype, PostsQuery ),
        authors: getAuthors(),
        termsQuery: listofterms
    };
} )( PostFilterOne );