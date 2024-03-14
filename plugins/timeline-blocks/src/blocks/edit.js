/**
 * External dependencies
 */

import get from 'lodash/get';
import isUndefined from 'lodash/isUndefined';
import pickBy from 'lodash/pickBy';
import moment from 'moment';
import classnames from 'classnames';
import { stringify } from 'querystringify';
import tb_styling from './tb_styling';

// Import all of our Text Options requirements.
import TypographyControl from "./components/typography";

const {
  Component,
  Fragment
} = wp.element;

const {
  __,
  sprintf
} = wp.i18n;

const {
  decodeEntities
} = wp.htmlEntities;

const {
  apiFetch
} = wp;

const {
  registerStore,
  withSelect,
} = wp.data;

const {
  PanelBody,
  Placeholder,
  QueryControls,
  RangeControl,
  SelectControl,
  Spinner,
  TextControl,
  ToggleControl,
  Toolbar,
  withAPIData,
} = wp.components;

const {
  InspectorControls,
  BlockAlignmentToolbar,
  BlockControls,
  ColorPalette,
} = wp.editor;

const MAX_POSTS_COLUMNS = 4;

class LatestPostsBlock extends Component {
  constructor() {
    super(...arguments);
    this.toggleDisplayPostDate = this.toggleDisplayPostDate.bind(this);
    this.toggleDisplayPostExcerpt = this.toggleDisplayPostExcerpt.bind(this);
    this.toggleDisplayPostAuthor = this.toggleDisplayPostAuthor.bind(this);
    this.toggleDisplayPostTag = this.toggleDisplayPostTag.bind(this);
    this.toggleDisplayPostCategory = this.toggleDisplayPostCategory.bind(this);
    this.toggleDisplayPostImage = this.toggleDisplayPostImage.bind(this);
    this.toggleDisplayPostLink = this.toggleDisplayPostLink.bind(this);
    this.toggleDisplayPostComments = this.toggleDisplayPostComments.bind(this);
    this.toggleDisplayPostSocialshare = this.toggleDisplayPostSocialshare.bind(this);
  }

  toggleDisplayPostDate() {
    const {
      displayPostDate
    } = this.props.attributes;
    const {
      setAttributes
    } = this.props;
    setAttributes({
      displayPostDate: !displayPostDate
    });
  }

  toggleDisplayPostExcerpt() {
    const {
      displayPostExcerpt
    } = this.props.attributes;
    const {
      setAttributes
    } = this.props;
    setAttributes({
      displayPostExcerpt: !displayPostExcerpt
    });
  }

  customizeWordsExcerpt() {
    const {
      wordsExcerpt
    } = this.props.attributes;
    const {
      setAttributes
    } = this.props;
    setAttributes({
      wordsExcerpt: !wordsExcerpt
    });
  }

  toggleDisplayPostAuthor() {
    const {
      displayPostAuthor
    } = this.props.attributes;
    const {
      setAttributes
    } = this.props;
    setAttributes({
      displayPostAuthor: !displayPostAuthor
    });
  }

  toggleDisplayPostTag() {
    const {
      displayPostTag
    } = this.props.attributes;
    const {
      setAttributes
    } = this.props;
    setAttributes({
      displayPostTag: !displayPostTag
    });
  }

  toggleDisplayPostCategory() {
    const {
      displayPostCategory
    } = this.props.attributes;
    const {
      setAttributes
    } = this.props;
    setAttributes({
      displayPostCategory: !displayPostCategory
    });
  }
  toggleDisplayPostComments() {
    const {
      displayPostComments
    } = this.props.attributes;
    const {
      setAttributes
    } = this.props;
    setAttributes({
      displayPostComments: !displayPostComments
    });
  }
  toggleDisplayPostImage() {
    const {
      displayPostImage
    } = this.props.attributes;
    const {
      setAttributes
    } = this.props;
    setAttributes({
      displayPostImage: !displayPostImage
    });
  }

  toggleDisplayPostLink() {
    const {
      displayPostLink
    } = this.props.attributes;
    const {
      setAttributes
    } = this.props;
    setAttributes({
      displayPostLink: !displayPostLink
    });
  }
  toggleDisplayPostSocialshare() {
    const {
      displayPostSocialshare
    } = this.props.attributes;
    const {
      setAttributes
    } = this.props;
    setAttributes({
      displayPostSocialshare: !displayPostSocialshare
    });
  }
  customizeReadMoreText() {
    const {
      readMoreText
    } = this.props.attributes;
    const {
      setAttributes
    } = this.props;
    setAttributes({
      readMoreText: !readMoreText
    });
  }

 componentDidMount() {
    this.props.setAttributes( { block_id: this.props.clientId } )
    const $style = document.createElement( "style" )
    $style.setAttribute( "id", "tb-style-" + this.props.clientId )
    document.head.appendChild( $style ) 
  }

  render() {
    const {
      attributes,
      isSelected,
      categoriesList,
      setAttributes,
      latestPosts
    } = this.props;
    const {
      // Typography Settings
      block_id, 
      titleTag,
      titlefontSize,
      titleFontFamily,
      titleFontWeight,
      titleFontSubset,
      postmetafontSize,
      postexcerptfontSize,
      postctafontSize,
      metaFontFamily,
      metaFontSubset,
      metafontWeight,
      excerptFontFamily,
      excerptFontWeight,
      excerptFontSubset,
      ctaFontFamily,
      ctaFontSubset,
      ctafontWeight,
      socialSharefontSize,
      // Readmore Settings
      readmoreView,
      // Space Settings
      belowTitleSpace,
      belowImageSpace,
      belowexerptSpace,
      belowctaSpace,
      innerSpace,
      // Color Settings
      boxbgColor,
      titleColor,
      postmetaColor,
      postexcerptColor,
      postctaColor,
      socialShareColor,
      readmoreBgColor,
      timelineBgColor,
      timelineFgColor,
      // Post Settings
      layoutcount,
      displayPostDate,
      displayPostExcerpt,
      displayPostAuthor,
      displayPostComments,
      displayPostTag,
      displayPostCategory,
      displayPostImage,
      displayPostLink,
      displayPostSocialshare,
      align,
      columns,
      order,
      orderBy,
      categories,
      postsToShow,
      width,
      imageCrop,
      readMoreText,
      wordsExcerpt
    } = attributes;

    function createLevelControl( targetLevel ) {
      return {
          icon: 'shield',
          title: sprintf( __( 'Template %d' ), targetLevel ),
          default:1,
          isActive: targetLevel === layoutcount,
          onClick: () => setAttributes( { layoutcount: targetLevel } ),
          subscript: String( targetLevel ),
      };
    }

    // Thumbnail options
    const imageCropOptions = [
      { value: 'landscape', label: __('Landscape')},
      { value: 'square', label: __('Square')},
    ];
    const isLandscape = imageCrop === 'landscape';
    // Title tag options
    const tbtitletag = [
      { value: 'h1', label: __('H1') },
      { value: 'h2', label: __('H2') },
      { value: 'h3', label: __('H3') },
      { value: 'h4', label: __('H4') },
      { value: 'h5', label: __('H5') },
      { value: 'h6', label: __('H6') },
    ];
    //Readmore options
    const reamoreText = [
      { value: 'text-only', label: __('Text Only') },
      { value: 'tb-button', label: __('Button') },
    ];
    //Create title tag
    const Tag = attributes.titleTag;

    const inspectorControls = ( isSelected && ( <InspectorControls >
    <PanelBody title = { __('Template Selection') } >
      <Toolbar className="tb-timeline-template-selection" controls={ _.range( 1, 3 ).map( createLevelControl ) } />
    </PanelBody> 
    <PanelBody title = { __('Post Settings') } initialOpen={ false } >
    <QueryControls { ...{ order, orderBy } }
    numberOfItems = { postsToShow }
    categoriesList = { categoriesList }
    selectedCategoryId = { categories }
    onOrderChange = { (value) => setAttributes({ order: value }) }
    onOrderByChange = { (value) => setAttributes({ orderBy: value })}
    onCategoryChange = {(value) => setAttributes({ categories: '' !== value ? value : undefined }) }
    onNumberOfItemsChange = {(value) => setAttributes({ postsToShow: value }) } /> 
     <ToggleControl label = { __('Display Featured Image') } checked = { displayPostImage } onChange = { this.toggleDisplayPostImage } /> 
    { displayPostImage && <SelectControl label = { __('Featured Image Style') }
      options = { imageCropOptions } value = { imageCrop } onChange = {(value) => this.props.setAttributes({ imageCrop: value }) } /> } 
      <ToggleControl label = { __('Display Post Author') } checked = { displayPostAuthor } onChange = { this.toggleDisplayPostAuthor } /> 
      <ToggleControl label = { __('Display Post Tag') } checked = { displayPostTag } onChange = { this.toggleDisplayPostTag } /> 
      <ToggleControl label = { __('Display Post Category') } checked = { displayPostCategory } onChange = { this.toggleDisplayPostCategory}/> 
      <ToggleControl label = { __('Display Post Comment') } checked = { displayPostComments }  onChange = { this.toggleDisplayPostComments } /> 
      <ToggleControl label = { __('Display Post Date')} checked = { displayPostDate } onChange = { this.toggleDisplayPostDate } /> 
      <ToggleControl label = { __('Display Post Excerpt') } checked = { displayPostExcerpt } onChange = { this.toggleDisplayPostExcerpt }/> {
        displayPostExcerpt &&
          <TextControl label = { __('Number of words for Excerpt') } type = "text" value = { wordsExcerpt } onChange = { (value) => this.props.setAttributes({ wordsExcerpt: value }) } />
      }
      <ToggleControl label = { __('Display Social share Icon') } checked = { displayPostSocialshare } onChange = { this.toggleDisplayPostSocialshare } /> 
      </PanelBody> 

      <PanelBody title = {  __('Readmore Settings') } initialOpen={ false } >
          <ToggleControl label = { __('Display Read More Link') } 
                  checked = { displayPostLink }
                  onChange = { this.toggleDisplayPostLink  }
            /> 
          { displayPostLink &&
             <Fragment>
                 <TextControl label = { __('Customize Read More Link') }
                  type = "text"
                  value = { readMoreText }
                  onChange = { (value) => this.props.setAttributes({ readMoreText: value }) }
                />

               <SelectControl label = { __('Readmore View') }
                  options = { reamoreText }
                  value = {readmoreView}
                  onChange = { (value) => this.props.setAttributes({ readmoreView: value}) }
              />
             </Fragment>
          }
      </PanelBody>

      <PanelBody title={ __('Typography Settings')} initialOpen={ false }>

          <SelectControl label = { __('Select Title tag') }
              options = { tbtitletag }
              value = { titleTag }
              onChange = { (value) => this.props.setAttributes({ titleTag: value}) }
          />

          <RangeControl 
              label = { __('Title Fontsize') } value = { titlefontSize } 
              onChange = { (value) => setAttributes({ titlefontSize: value }) } 
              min = { 12 } 
              max = { 50 }
              beforeIcon="editor-textcolor" 
              allowReset
          />
          <TypographyControl
              label={ __( "Title Fontfamily" ) }
              attributes = { attributes }
              setAttributes = { setAttributes }
              fontFamily = { { value: titleFontFamily, label: __( "titleFontFamily" ) } }
              fontWeight = { { value: titleFontWeight, label: __( "titleFontWeight" ) } }
              fontSubset = { { value: titleFontSubset, label: __( "titleFontSubset" ) } }
          />  

          <hr class="tb-divider"/>
          <RangeControl 
              label = { __('Meta Fontsize') } value = { postmetafontSize } 
              onChange = { (value) => setAttributes({ postmetafontSize: value }) } 
              min = { 12 } 
              max = { 50 }
              beforeIcon="editor-textcolor" 
              allowReset
          />
          <TypographyControl
              label={ __( "Meta Fontfamily" ) }
              attributes = { attributes }
              setAttributes = { setAttributes }
              fontFamily = { { value: metaFontFamily, label: __( "metaFontFamily" ) } }
              fontWeight = { { value: metafontWeight, label: __( "metafontWeight" ) } }
              fontSubset = { { value: metaFontSubset, label: __( "metaFontSubset" ) } }
          />

          <hr class="tb-divider"/>
          <RangeControl 
              label = { __('Excerpt Fontsize') } value = { postexcerptfontSize } 
              onChange = { (value) => setAttributes({ postexcerptfontSize: value }) } 
              min = { 12 } 
              max = { 30 }
              beforeIcon="editor-textcolor" 
              allowReset
          />
          <TypographyControl
              label={ __( "Excerpt Fontfamily" ) }
              attributes = { attributes }
              setAttributes = { setAttributes }
              fontFamily = { { value: excerptFontFamily, label: __( "excerptFontFamily" ) } }
              fontWeight = { { value: excerptFontWeight, label: __( "excerptFontWeight" ) } }
              fontSubset = { { value: excerptFontSubset, label: __( "excerptFontSubset" ) } }
          />

          <hr class="tb-divider"/>
          <RangeControl 
              label = { __('Readmore Fontsize') } value = { postctafontSize } 
              onChange = { (value) => setAttributes({ postctafontSize: value }) } 
              min = { 12 } 
              max = { 50 }
              beforeIcon="editor-textcolor" 
              allowReset
          />
          <TypographyControl
              label={ __( "Readmore Fontfamily" ) }
              attributes = { attributes }
              setAttributes = { setAttributes }
              fontFamily = { { value: ctaFontFamily, label: __( "ctaFontFamily" ) } }
              fontWeight = { { value: ctafontWeight, label: __( "ctafontWeight" ) } }
              fontSubset = { { value: ctaFontSubset, label: __( "ctaFontSubset" ) } }
          />

          <hr class="tb-divider"/>
          <RangeControl 
              label = { __('Social Icon Fontsize') } value = { socialSharefontSize } 
              onChange = { (value) => setAttributes({ socialSharefontSize: value }) } 
              min = { 12 } 
              max = { 50 }
              beforeIcon="editor-textcolor" 
              allowReset
          />          
      </PanelBody>

      <PanelBody title={ __( 'Space Settings' )} initialOpen={ false } >

              <RangeControl 
                  label = { __('Inner Space') } value = { innerSpace } 
                  onChange = { (value) => setAttributes({ innerSpace: value }) } 
                  min = { 12 } 
                  max = { 100 }
                  beforeIcon="editor-textcolor" 
                  allowReset
              />
              <RangeControl 
                  label = { __('Below Image Space') } value = { belowImageSpace } 
                  onChange = { (value) => setAttributes({ belowImageSpace: value }) } 
                  min = { 12 } 
                  max = { 100 }
                  beforeIcon="arrow-down-alt" 
                  allowReset
              />
              <RangeControl 
                  label = { __('Below Title Space') } value = { belowTitleSpace } 
                  onChange = { (value) => setAttributes({ belowTitleSpace: value }) } 
                  min = { 12 } 
                  max = { 100 }
                  beforeIcon="arrow-down-alt" 
                  allowReset
              />
              <RangeControl 
                  label = { __('Below Exerpt Space') } value = { belowexerptSpace } 
                  onChange = { (value) => setAttributes({ belowexerptSpace: value }) } 
                  min = { 12 } 
                  max = { 100 }
                  beforeIcon="arrow-down-alt" 
                  allowReset
              /> 
              <RangeControl 
                  label = { __('Below Readmore Space') } value = { belowctaSpace } 
                  onChange = { (value) => setAttributes({ belowctaSpace: value }) } 
                  min = { 12 } 
                  max = { 100 }
                  beforeIcon="arrow-down-alt" 
                  allowReset
              />
      </PanelBody>

       <PanelBody title={ __( 'Color Settings' )} initialOpen={ false }>
        { layoutcount == '1' &&
           <Fragment>  
                <p className="tb-color-label">{ __( "Background Color" ) }<span className="components-base-control__label"><span className="component-color-indicator" style={{ backgroundColor: boxbgColor }} ></span></span></p>
                <ColorPalette
                    value={boxbgColor}
                    onChange={ ( colorValue ) => setAttributes( { boxbgColor: colorValue } )}
                    allowReset
                  />
           </Fragment> 
          }
          <p className="tb-color-label">{ __( "Title Color" ) }<span className="components-base-control__label"><span className="component-color-indicator" style={{ backgroundColor: titleColor }} ></span></span></p>
          <ColorPalette
              value={titleColor}
              onChange={ ( colorValue ) => setAttributes( { titleColor: colorValue } )}
              allowReset
            />

          <p className="tb-color-label">{ __( "Timeline Background Color" ) }<span className="components-base-control__label"><span className="component-color-indicator" style={{ backgroundColor: timelineBgColor }} ></span></span></p>  
          <ColorPalette
            value={ timelineBgColor}
            onChange={ ( colorValue ) => setAttributes( { timelineBgColor: colorValue } )}
            allowReset
          />

          { layoutcount == 2 && (
            <Fragment>
            <p className="tb-color-label">{ __( "Timeline Icon Foreground Color" ) }<span className="components-base-control__label"><span className="component-color-indicator" style={{ backgroundColor: timelineFgColor }} ></span></span></p>  
            <ColorPalette
              value={ timelineFgColor}
              onChange={ ( colorValue ) => setAttributes( { timelineFgColor: colorValue } )}
              allowReset>
            </ColorPalette>
            </Fragment>
          )}              

          <p className="tb-color-label">{ __( "Meta Color" ) }<span className="components-base-control__label"><span className="component-color-indicator" style={{ backgroundColor: postmetaColor }} ></span></span></p>  
          <ColorPalette
            value={ postmetaColor}
            onChange={ ( colorValue ) => setAttributes( { postmetaColor: colorValue } )}
            allowReset
          />

          <p className="tb-color-label">{ __( "Excerpt Color" ) }<span className="components-base-control__label"><span className="component-color-indicator" style={{ backgroundColor: postexcerptColor }} ></span></span></p>
          <ColorPalette
            value={ postexcerptColor}
            onChange={ ( colorValue ) => setAttributes( { postexcerptColor: colorValue } )}
            allowReset  
          />
           { readmoreView == 'text-only' &&
              <Fragment>
                 <p className="tb-color-label">{ __( "Readmore Color" ) }<span className="components-base-control__label"><span className="component-color-indicator" style={{ backgroundColor: postctaColor }} ></span></span></p>
                    <ColorPalette
                      value={ postctaColor}
                      onChange={ ( colorValue ) => setAttributes( { postctaColor: colorValue } )}
                      allowReset
                    />
              </Fragment> 
           }

           { readmoreView == 'tb-button' &&
                <Fragment>  
                <p className="tb-color-label">{ __( "Readmore text Color" ) }<span className="components-base-control__label"><span className="component-color-indicator" style={{ backgroundColor: postctaColor }} ></span></span></p>
                <ColorPalette
                  value={ postctaColor}
                  onChange={ ( colorValue ) => setAttributes( { postctaColor: colorValue } )}
                  allowReset
                />

                <p className="tb-color-label">{ __( "Readmore Background Color" ) }<span className="components-base-control__label"><span className="component-color-indicator" style={{ backgroundColor: readmoreBgColor }} ></span></span></p>
                <ColorPalette
                value={readmoreBgColor}
                onChange={( colorValue ) => setAttributes( { readmoreBgColor: colorValue } )}
                allowReset
              />
             </Fragment> 
            }
          <p className="tb-color-label">{ __( "Social Share Color" ) }<span className="components-base-control__label"><span className="component-color-indicator" style={{ backgroundColor: socialShareColor }} ></span></span></p>
          <ColorPalette
            value={ socialShareColor}
            onChange={ ( colorValue ) => setAttributes( { socialShareColor: colorValue } )}
            allowReset  
          />
          
    </PanelBody>
    </InspectorControls>
    ) );

    var plelement = document.getElementById( "tb-style-" + this.props.clientId )

    if( null != plelement && "undefined" != typeof plelement ) {
      plelement.innerHTML = tb_styling( this.props, "tb_post_layouts" )
    }

    const hasPosts = Array.isArray(latestPosts) && latestPosts.length;
    if (!hasPosts) {
      return ( <Fragment> {
          inspectorControls
        } 
        <Placeholder icon = "admin-post"
        label = {
          __('Timeline Block By Techeshta')
        } >
        {!Array.isArray(latestPosts) ?
          <
          Spinner / > : __('No posts found.')
        } </Placeholder> 
        </Fragment>
      );
    }

    // Removing posts from display should be instant.
    const displayPosts = latestPosts.length > postsToShow ? latestPosts.slice(0, postsToShow) : latestPosts;

        return ( <Fragment > {
            inspectorControls
          } 
          <BlockControls >
            <BlockAlignmentToolbar 
              value = { align }
              onChange = { (value) => { setAttributes({ align: value }); } }
              controls = {['center', 'wide']} />
          </BlockControls>
          
          <div id={ `tb_post_layouts-${ block_id }` } className = {  classnames(  this.props.className, `tb-timeline-template${ layoutcount }` ) } >
            {  layoutcount == 1 && ( <div className = {  `tb-timeline` } >
            { displayPosts.map((post, i) =>

                <article key={ i } className={ classnames( post.featured_image_src && displayPostImage ? 'has-thumb tb-items' : 'no-thumb tb-items','tb-timeline-item' )} >
                <div className={ `tb-timeline-content` }> 
                   <div class="tb-first-inner-wrap">
                    <div class="tb-blogpost-title ">
                      <Tag className={'tb-title'}>
                      <a href={ post.link } target="_blank" rel="bookmark" className={` tb-timeline-title tb-layout-1` }>{ decodeEntities( post.title.rendered.trim() ) || __( '(Untitled)' ) }</a>
                      </Tag>
                    </div>
                    {   displayPostImage && post.featured_image_src !== undefined && post.featured_image_src ? (
                        <div class="tb-image">
                        <a href={ post.link } target="_blank" rel="bookmark">
                            <img
                                    src={ isLandscape ? post.featured_image_src : post.featured_image_src_square }
                                    alt={ decodeEntities( post.title.rendered.trim() ) || __( '(Untitled)' ) }
                            />
                        </a>
                        </div>
                    ) : ( null) }
                    </div>
                    <div class="tb-timeline-second-content-wrap">
                    <div class="tb-content-wrap">
                    <div class="tb-timeline-byline">
                    { displayPostCategory && post.category_info && post.category_info.length !== 0 &&
                        <div class="tb-category-link-wraper">
                            <div class="tb-timeline-category-link" dangerouslySetInnerHTML={ { __html: post.category_info } } />
                        </div>
                    }
                    <div class="tb-timeline-metadatabox">
                    { displayPostAuthor && post.author_info.display_name &&
                        <div class="post-author"> <i class="fas fa-pencil-alt"></i>&nbsp;
                        <span class="tb-blogpost-author">
                            <a class="tb-timelinetext-link" target="_blank" href="{ post.author_info.author_link } "> { post.author_info.display_name } </a>
                        </span>
                        </div>
                    } 
                    { displayPostDate && post.date_gmt &&
                        <div class="mdate "><i class="fas fa-calendar-alt"></i>
                            <span> { moment( post.date_gmt ).local().format( 'MMMM, Y' )  }</span>
                        </div>
                    }
                    {  displayPostComments &&  post.comment_info &&
                        <div class="post-comments"><i class="fas fa-comment"></i>&nbsp;
                            { post.comment_info }
                        </div>
                    }
                    </div>
                    <div class="tb-timeline-text">
                        <div class="tb-blogpost-excerpt">
                        { displayPostExcerpt && 
                            <div dangerouslySetInnerHTML={ { __html: post.wordExcerpt_info.split(/\s+/).slice(0,wordsExcerpt).join(" ") } } />
                        }
                        {
                          displayPostLink && readmoreView == 'text-only' &&
                            <div className={`${readmoreView}`}> 
                              <a class = "tb-link " href = { post.link } target = "_blank" rel = "bookmark" > { readMoreText } </a>
                            </div>
                        }
                        {
                          displayPostLink && readmoreView == 'tb-button' &&
                              <div className = "tb-button-view">
                                <a className = {`${readmoreView}`} href = { post.link } target = "_blank" rel = "bookmark" > { readMoreText } </a>
                              </div>
                        }
                        </div> 
                        <div class="tb-timeline-bototm-wrap">
                        { displayPostTag && post.tags_info && post.tags_info.length !== 0 &&
                            <div class="tb-timeline-tags-wrap">
                            <div class="tb-timeline-post-tags" dangerouslySetInnerHTML={ { __html: post.tags_info } } />
                            </div>
                        }{ displayPostSocialshare &&
                        <div class="tb-social-wrap">
                            <div class="social-share-data" dangerouslySetInnerHTML={ { __html: post.social_share_info } } />
                        </div>
                        }
                        </div></div></div>
                        <div class="tb-clearfix"></div>
                    </div>
                    </div>
                    </div>
                </article>
              )} 
              </div>
            )}
         { layoutcount == 2 && ( <div className = {  `tb-timeline` } >
            { displayPosts.map((post, i) =>
                <article key={ i } className={ classnames( post.featured_image_src && displayPostImage ? 'has-thumb tb-items' : 'no-thumb tb-items','tb-timeline-item' )} >
                  <div class="timeline-icon">
                    <svg class="tb-svg-icon" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg"  x="0px" y="0px"
                  	     width="21px" height="20px" viewBox="0 0 21 20" enable-background="new 0 0 21 20">
                      <path fill="#FFFFFF" d="M19.998,6.766l-5.759-0.544c-0.362-0.032-0.676-0.264-0.822-0.61l-2.064-4.999
                      	c-0.329-0.825-1.5-0.825-1.83,0L7.476,5.611c-0.132,0.346-0.462,0.578-0.824,0.61L0.894,6.766C0.035,6.848-0.312,7.921,0.333,8.499
                      	l4.338,3.811c0.279,0.246,0.395,0.609,0.314,0.975l-1.304,5.345c-0.199,0.842,0.708,1.534,1.468,1.089l4.801-2.822
                      	c0.313-0.181,0.695-0.181,1.006,0l4.803,2.822c0.759,0.445,1.666-0.23,1.468-1.089l-1.288-5.345
                      	c-0.081-0.365,0.035-0.729,0.313-0.975l4.34-3.811C21.219,7.921,20.855,6.848,19.998,6.766z"/>
                    </svg>
                  </div>
                  <div className={ `tb-timeline-content` }> 
                    <div>
                      { displayPostImage && post.featured_image_src !== undefined && post.featured_image_src ? (
                        <div class="tb-image">
                          <a href={ post.link } target="_blank" rel="bookmark">
                            <img src={ isLandscape ? post.featured_image_src : post.featured_image_src_square }
                                 alt={ decodeEntities( post.title.rendered.trim() ) || __( '(Untitled)' ) } />
                          </a>
                        </div>
                      ) : ( null) }
                    </div>
                    <div class="tb-first-inner-wrap">
                    <div class="tb-content-wrap">
                      <div class="tb-blogpost-title">
                        <Tag className="tb-title">
                          <a href={ post.link } target="_blank" rel="bookmark" className={` tb-timeline-title` }>{ decodeEntities( post.title.rendered.trim() ) || __( '(Untitled)' ) }</a> 
                        </Tag>
                      </div>
                      { displayPostDate && post.date_gmt &&
                          <div class="mdate tb-inline "><i class="fas fa-calendar-alt"></i>
                              <span> { moment( post.date_gmt ).local().format( 'MMMM, Y' )  }</span>
                          </div>
                      }    
                      { displayPostAuthor && post.author_info.display_name &&
                          <div class="post-author tb-inline"> <i class="fas fa-pencil-alt"></i>&nbsp;
                          <span class="tb-blogpost-author">
                              <a class="tb-timelinetext-link" target="_blank" href="{ post.author_info.author_link } "> { post.author_info.display_name } </a>
                          </span>
                          </div>
                      }
                      {  displayPostComments &&  post.comment_info &&
                              <div class="post-comments tb-inline"> <i class="fas fa-comment"></i>&nbsp;
                                  { post.comment_info }
                          </div>
                      }
                      { displayPostCategory && post.category_info && post.category_info.length !== 0 &&
                        <div class="tb-category-link-wraper tb-inline">
                          <div class="tb-timeline-category-link" dangerouslySetInnerHTML={ { __html: post.category_info } } />
                        </div>
                      }
                      <div class="tb-timeline-second-content-wrap">
                      <div class="tb-timeline-byline">
                      <div class="tb-timeline-metadatabox">
                          <div class="tb-timeline-excerpt">
                          { displayPostExcerpt && 
                              <div class="tb-blogpost-excerpt" dangerouslySetInnerHTML={ { __html: post.wordExcerpt_info.split(/\s+/).slice(0,wordsExcerpt).join(" ") } } />
                          }
                          { displayPostLink && readmoreView == 'text-only' &&
                            <div className={`${readmoreView}`}> 
                              <a class = "tb-link" href = { post.link } target = "_blank" rel = "bookmark" > { readMoreText } </a>
                            </div>
                          }
                          {
                            displayPostLink && readmoreView == 'tb-button' &&
                              <div className = "tb-button-view">
                                <a className = {`${readmoreView}`} href = { post.link } target = "_blank" rel = "bookmark" > { readMoreText } </a>
                              </div>
                          }
                          </div> 
                          <div class="tb-timeline-bototm-wrap">
                          { displayPostTag && post.tags_info && post.tags_info.length !== 0 &&
                              <div class="tb-timeline-tags-wrap">
                              <div class="tb-timeline-post-tags" dangerouslySetInnerHTML={ { __html: post.tags_info } } />
                              </div>
                          }{ displayPostSocialshare &&
                          <div class="tb-social-wrap">
                              <div class="social-share-data" dangerouslySetInnerHTML={ { __html: post.social_share_info } } />
                          </div>
                          }
                          </div></div></div>
                          <div class="tb-clearfix"></div>
                      </div>
                    </div>
                    </div>
                  </div>
              </article>
              )} 
          </div>
           )}
        </div>
    </Fragment>
    );
    }
}

export default withSelect((select, props) => {
  const {
    postsToShow,
    order,
    orderBy,
    categories
  } = props.attributes;
  const {
    getEntityRecords
  } = select('core');
  const latestPostsQuery = pickBy({
    categories,
    order,
    orderby: orderBy,
    per_page: postsToShow,
  }, (value) => !isUndefined(value));
  const categoriesListQuery = {
    per_page: 100,
  };
  return {
    latestPosts: getEntityRecords('postType', 'post', latestPostsQuery),
    categoriesList: getEntityRecords('taxonomy', 'category', categoriesListQuery),
  };
})(LatestPostsBlock);
