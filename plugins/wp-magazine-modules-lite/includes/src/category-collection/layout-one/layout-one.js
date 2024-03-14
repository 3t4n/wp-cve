/**
 * Post Category Collection block layout one - editor preview
 */
const { Component } = wp.element;
const { CheckboxControl } = wp.components
const { __ } = wp.i18n;
const { escapeHTML } = wp.escapeHtml;
const { withSelect } = wp.data
const { decodeEntities } = wp.htmlEntities;

class CategoryCollectionOne extends Component {
    constructor( props ) {
        super(...arguments)
    }

    render() {
        const { blockCategories, titleOption, fallbackImage, descOption, catcountOption, permalinkTarget, blockColumn, postMargin } = this.props.attributes
        const { setAttributes, categoriesList } = this.props
        const hasselectedCategories = Array.isArray(blockCategories) && blockCategories.length
        
        if( !hasselectedCategories ) {
            const hascategoriesList = Array.isArray(categoriesList) && categoriesList.length
            if( hascategoriesList ) {
                const allCategories = [];
                categoriesList.forEach( ( category ) => {
                    allCategories.push({ label: category.name + ' (' + category.count + ')', value: category.id });
                });
                let postCategoryCheckboxes = [];
                if( Array.isArray( allCategories ) && allCategories.length ) {
                    allCategories.forEach( ( category, index  ) => {
                        postCategoryCheckboxes.push( 
                            <CheckboxControl
                                key = { index }
                                label = { category.label }
                                value = { category.value }
                                checked = { blockCategories.includes( category.value ) }
                                onChange = { ( checkboxValue ) => {
                                        let data = blockCategories
                                        if( checkboxValue ) {
                                            data = data.concat( category.value )
                                            setAttributes( {
                                                blockCategories : data
                                            })
                                        } else {
                                            data.splice( data.indexOf(category.value), 1 )
                                            var newdata = JSON.parse( JSON.stringify( data ) )
                                            setAttributes( {
                                                blockCategories : newdata
                                            })
                                        }
                                    }
                                }
                            />
                        )
                    });
                }
                return (
                    <div class="wpmagazine-modules-post-choose-multicheckbox-control">
                        <label class="components-base-control__label">{ escapeHTML( __( 'Post Categories:', 'wp-magazine-modules-lite' ) ) }</label>
                        <div className={ "wpmagazine-modules-post-choose-multicheckbox-control__content" }>
                            { postCategoryCheckboxes }
                        </div>
                    </div>
                );
            } else {
                return escapeHTML( __( "Non empty categories doesnot exists", "wp-magazine-modules" ) );
            }
        }

        const { catsContent } = this.props

        const content = catsContent.map( ( catContent ) => {
            let image_url = catContent.cat_postimage
            let cat_id = catContent.cat_id
            let cat_link = catContent.cat_link
            let cat_title = catContent.cat_name
            let cat_desc = catContent.cat_desc
            let cat_count = catContent.cat_count
            if( typeof image_url === 'undefined' ) {
                if( fallbackImage ) {
                    image_url = fallbackImage
                } else {
                    image_url = BlocksBuildObject.defaultImage
                }
            }
            return (
                <div id={ `cat-${cat_id}` } className={ "cvmm-category" }>
                    <div class="cvmm-cat-thumb">
                        <a href={ cat_link } target={ permalinkTarget }>
                            <img src={ image_url } alt={ decodeEntities( cat_title ) }/>
                        </a>
                    </div>
                    <div className="cvmm-cat-content-all-wrapper">
                        { titleOption &&
                            <h2 class={ `cvmm-cat-title cvmm-cat-${cat_id}` }>
                                <a href={ `${cat_link}` } target={ permalinkTarget }>
                                    { decodeEntities( cat_title ) }
                                </a>
                            </h2>
                        }
                        { catcountOption &&
                            <span class={ `cvmm-cat-count cvmm-cat-${cat_id}` }>
                                { cat_count }
                            </span>
                        }
                        { descOption &&
                            <div class="cvmm-cat-content">
                                { decodeEntities( cat_desc ) }
                            </div>
                        }
                    </div>
                </div>
            );
        });
        let postClass;
        if( postMargin ) {
            postClass = `cvmm-post--imagemargin column--${blockColumn}`
        } else {
            postClass = `cvmm-post-no--imagemargin column--${blockColumn}`
        }

        return <div className={ `cvmm-cats-wrapper ${postClass}` }>{ content }</div>
    }
}

export default withSelect( ( select, props ) => {
    const { imageSize, blockCategories } = props.attributes;
    const { getEntityRecords } = select( 'core' );
    let blockCategoriesContent = [];
    blockCategories.map( ( catid ) => {
        let singlecatContent = []
        /*let catQuery = {
            include: catid
        }*/
        let catContent = getEntityRecords( 'taxonomy', 'category' );
        let selectedCategoryContent = {};
        if( Array.isArray( catContent ) && catContent.length ) {
            catContent.map( ( catContentsingle ) => { 
                if( catContentsingle.id === catid ) {
                    selectedCategoryContent = catContentsingle;
                }
            });
        }
        if( ! _.isEmpty( selectedCategoryContent ) ) {
            singlecatContent.cat_id = selectedCategoryContent.id
            singlecatContent.cat_name = selectedCategoryContent.name
            singlecatContent.cat_desc = selectedCategoryContent.description
            singlecatContent.cat_link = selectedCategoryContent.link
            singlecatContent.cat_count = selectedCategoryContent.count
        }
        const PostsQuery = {
            per_page: 1,
            categories: catid
        };
        let catPost = getEntityRecords( 'postType', 'post', PostsQuery )
        if( Array.isArray(catPost) && catPost.length ) {
            singlecatContent.cat_postid  =catPost[0].id
            singlecatContent.cat_postimage  =catPost[0].wpmagazine_modules_lite_featured_media_urls[imageSize][0]
        }
        if( Array.isArray(singlecatContent) ) {
            blockCategoriesContent.push( singlecatContent )
        }
    })

    const taxonomyQuery = {
        hide_empty: true,
        per_page: 100
    }
    return {
        categoriesList: getEntityRecords( 'taxonomy', 'category', taxonomyQuery ),
        catsContent: blockCategoriesContent,
    };
} )( CategoryCollectionOne );