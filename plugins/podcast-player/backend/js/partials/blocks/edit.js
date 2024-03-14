const { __ } = wp.i18n;
const { Component, Fragment } = wp.element;
const {
	MediaUpload,
	PanelColorSettings
} = wp.editor;
const { apiFetch } = wp;
const {
	Dashicon,
	SelectControl,
	PanelBody,
	Button,
	Disabled,
	Placeholder,
	RangeControl,
	TextControl,
	TextareaControl,
	ToggleControl,
	Toolbar
} = wp.components;
const ServerSideRender = wp.serverSideRender;
const { BlockControls, InspectorControls } = wp.blockEditor

import MultipleCheckboxControl from './mcc';
import EpisodeCheckboxControl from './ecc';

class PodcastPlayer extends Component {
	constructor() {
		super( ...arguments );

		let chkEditing = ( ! this.props.attributes.feedURL && 'feed' === this.props.attributes.fetchMethod ) || ( ! this.props.attributes.audioSrc && 'link' === this.props.attributes.fetchMethod );

		this.state = {
			editing: chkEditing,
			fontFamilies: [],
			postTypes: [],
			taxonomies: [],
			termsList: [],
			episodeList: [],
			seasonList: [],
			categoryList: [],
			feedIndex: [],
		};

		const mejsSettings = window.ppmejsSettings || {};
		this.isPremium = mejsSettings.isPremium;
		this.fetching = false;
		this.toggleAttribute = this.toggleAttribute.bind( this );
		this.onSubmitURL = this.onSubmitURL.bind( this );
	}

	apiDataFetch(data, path) {
		if (this.fetching) {
			setTimeout( this.apiDataFetch.bind(this, data, path), 200 );
			return;
		}
		this.fetching = true;
		apiFetch( {
			path: '/podcastplayer/v1/' + path,
		} )
		.then( ( items ) => {
			let itemsList = Object.keys(items);
			itemsList = itemsList.map(item => {
				return {
					label: items[item],
					value: item,
				};
			});
			this.setState({ [data]: itemsList });
			this.fetching = false;
		} )
		.catch( (error) => {
			this.setState({ [data]: [] });
			this.fetching = false;
			console.log(error);
		} );
	}

	componentDidMount() {
		this.apiDataFetch('feedIndex', 'fIndex');
		if (!this.isPremium) return;
		const {attributes} = this.props;
		const {postType, fetchMethod} = attributes;
		this.apiDataFetch('postTypes', 'posttypes');
		this.apiDataFetch('fontFamilies', 'fontfamily');
		if ('link' !== fetchMethod) { this.updateElist(); }
		if ('feed' === fetchMethod) {
			this.updateSlist();
			this.updateCatlist();
		}
		if (postType) {
			this.updateTaxonomy();
			this.updateTerms();
		}
	}

	componentDidUpdate( prevProps ) {
		if (!this.isPremium) return;
		const { postType: oldPostType, taxonomy: oldTaxonomy, fontFamily: oldFontFamily, terms: oldTerms, sortBy: oldSortBy, filterBy: oldFilterBy, fetchMethod: oldfetchMethod, feedURL: oldFeedURL, catlist: oldCatlist, slist: oldSlist } = prevProps.attributes;
		const { postType, taxonomy, fontFamily, terms, sortBy, filterBy, fetchMethod, feedURL, slist, catlist } = this.props.attributes;
		if (oldPostType !== postType) { this.updateTaxonomy() }
		if (oldTaxonomy !== taxonomy) { this.updateTerms() }
		if (oldFontFamily !== fontFamily) { this.updateFonts() }
		if (oldTaxonomy !== taxonomy || oldTerms !== terms || oldSortBy !== sortBy || oldFilterBy !== filterBy || oldfetchMethod !== fetchMethod || oldFeedURL !== feedURL || oldCatlist !== catlist || oldSlist !== slist) {
			this.updateElist();
		}
		if ((oldfetchMethod !== fetchMethod && 'feed' === fetchMethod) || oldFeedURL !== feedURL) {
			this.updateSlist();
			this.updateCatlist();
		}
	}

	updateTaxonomy() {
		const { attributes } = this.props;
		const { postType } = attributes;
		if (!postType) {
			this.setState( { taxonomies: [], termsList: [] } );
		} else {
			this.apiDataFetch('taxonomies', 'taxonomies/' + postType);
		}
	}

	updateTerms() {
		const { attributes } = this.props;
		const { taxonomy } = attributes;
		if (!taxonomy) {
			this.setState( { termsList: [] } );
		} else {
			this.apiDataFetch('termsList', 'terms/' + taxonomy);
		}
	}

	updateElist() {
		const { attributes } = this.props;
		const { fetchMethod, feedURL, postType, taxonomy, terms, sortBy, filterBy, slist, catlist } = attributes;
		if ('feed' === fetchMethod && '' !== feedURL) {
			let   str = '';
			const seasons = slist ? slist.filter(Boolean) : false;
			const cats = catlist ? catlist.filter(Boolean) : false;
			if (seasons && seasons.length) {
				str += '&seasons=' + seasons.join();
			}
			if (cats && cats.length) {
				str += '&categories=' + cats.join();
			}
			this.apiDataFetch('episodeList', 'fElist?feedURL=' + encodeURIComponent(feedURL) + str);
		} else if ('post' === fetchMethod) {
			let str = '';
			if (taxonomy && terms && terms.length) {
				str += '&taxonomy=' + taxonomy + '&terms=' + terms.join();
			}
			if (sortBy) str += '&sortBy=' + sortBy;
			if (filterBy) str += '&filterBy=' + filterBy;
			this.apiDataFetch('episodeList', 'pElist?postType=' + postType + str);
		} else {
			this.setState( { episodeList: [] } );
		}
	}

	updateSlist() {
		const { attributes } = this.props;
		const { fetchMethod, feedURL } = attributes;
		if ('feed' === fetchMethod && '' !== feedURL) {
			this.apiDataFetch('seasonList', 'fSlist?feedURL=' + feedURL);
		} else {
			this.setState( { seasonList: [] } );
		}
	}

	updateCatlist() {
		const { attributes } = this.props;
		const { fetchMethod, feedURL } = attributes;
		if ('feed' === fetchMethod && '' !== feedURL) {
			this.apiDataFetch('categoryList', 'fcatlist?feedURL=' + feedURL);
		} else {
			this.setState( { categoryList: [] } );
		}
	}

	updateFonts() {
		const { fontFamily } = this.props.attributes;
		const { fontFamilies } = this.state;
		if (fontFamily) {
			const family = fontFamilies.filter(font => fontFamily === font.value);
			if (family.length) {
				const font = family[0].label;
				const fontName = font.split( ' ' ).join( '+' );
				if ( 0 === jQuery( 'link#podcast-player-fonts-css-temp' ).length ) {
					const gfontUrl = '//fonts.googleapis.com/css?family=' + fontName;
					const gfontlink = jQuery( '<link>', {
						id: 'podcast-player-fonts-css-temp',
						href: gfontUrl,
						rel: 'stylesheet',
						type: 'text/css'
					} );
					jQuery( 'link:last' ).after( gfontlink );
				} else {
					const elem = jQuery('link#podcast-player-fonts-css-temp');
					const href = elem.attr('href');
					elem.attr( 'href', href + '%7C' +fontName );
				}
			}
		}
	}

	toggleAttribute( propName ) {
		return () => {
			const value = this.props.attributes[ propName ];
			const { setAttributes } = this.props;

			setAttributes( { [ propName ]: ! value } );
		};
	}

	onSubmitURL( event ) {
		event.preventDefault();

		const { fetchMethod, feedURL, audioSrc } = this.props.attributes;
		if ( 'feed' === fetchMethod ) {
			if ( feedURL ) {
				this.setState( { editing: false } );
			}
		} else if ( 'link' === fetchMethod ) {
			if ( audioSrc ) {
				this.setState( { editing: false } );
			}
		}
	}

	navMenuSelect() {
		let ppData = window.podcastPlayerData.menu || {};
		ppData = Array.from(ppData);
		ppData.push( { label: '- Select Menu -', value: '' } );
		return ppData.map( ( item ) => {
			return {
				label: item.label,
				value: item.value,
			};
		} );
	}

	render() {
		const {
			feedURL,
			sortBy,
			filterBy,
			number,
			offset,
			teaserText,
			excerptLength,
			excerptUnit,
			podcastMenu,
			mainMenuItems,
			coverImage,
			description,
			accentColor,
			displayStyle,
			aspectRatio,
			cropMethod,
			gridColumns,
			fetchMethod,
			postType,
			taxonomy,
			terms,
			podtitle,
			audioSrc,
			audioTitle,
			audioLink,
			headerDefault,
			listDefault,
			hideHeader,
			hideTitle,
			hideCover,
			hideDesc,
			hideSubscribe,
			hideSearch,
			hideAuthor,
			hideContent,
			hideLoadmore,
			hideDownload,
			ahideDownload,
			hideSocial,
			hideFeatured,
			ahideSocial,
			audioMsg,
			playFreq,
			msgStart,
			msgTime,
			msgText,
			bgColor,
			txtColor,
			fontFamily,
			appleSub,
			googleSub,
			spotifySub,
			breakerSub,
			castboxSub,
			castroSub,
			iheartSub,
			amazonSub,
			overcastSub,
			pocketcastsSub,
			podcastaddictSub,
			podchaserSub,
			radiopublicSub,
			soundcloudSub,
			stitcherSub,
			tuneinSub,
			youtubeSub,
			bullhornSub,
			podbeanSub,
			playerfmSub,
			elist,
			slist,
			catlist,
			edisplay,
		} = this.props.attributes;
		const { postTypes, taxonomies, termsList, episodeList, seasonList, categoryList, fontFamilies, feedIndex } = this.state;
		const { setAttributes } = this.props;
		const navMenu = this.navMenuSelect();
		const styles  = window.podcastPlayerData.style || { label: 'Default', value: '' };
		const ifStyleSupport = (style, item) => {
			const supported = window.podcastPlayerData.stSup || false;
			if ('undefined' === typeof displayStyle || ! supported) return false; 
			return supported[style] ? supported[style].includes(item) : false;
		}
		const aspectOptions = [
			{ value: '', label: __( 'No Cropping', 'podcast-player' ) },
			{ value: 'land1', label: __( 'Landscape (4:3)', 'podcast-player' ) },
			{ value: 'land2', label: __( 'Landscape (3:2)', 'podcast-player' ) },
			{ value: 'port1', label: __( 'Portrait (3:4)', 'podcast-player' ) },
			{ value: 'port2', label: __( 'Portrait (2:3)', 'podcast-player' ) },
			{ value: 'wdscrn', label: __( 'Widescreen (16:9)', 'podcast-player' ) },
			{ value: 'squr', label: __( 'Square (1:1)', 'podcast-player' ) },
		];
		const cropOptions = [
			{ value: 'topleftcrop', label: __( 'Top Left Cropping', 'podcast-player' ) },
			{ value: 'topcentercrop', label: __( 'Top Center Cropping', 'podcast-player' ) },
			{ value: 'centercrop', label: __( 'Center Cropping', 'podcast-player' ) },
			{ value: 'bottomcentercrop', label: __( 'Bottom Center Cropping', 'podcast-player' ) },
			{ value: 'bottomleftcrop', label: __( 'Bottom Left Cropping', 'podcast-player' ) },
		];
		const onFetchChange = (value) => {
			setAttributes( { fetchMethod: value, elist: [''], slist: [''], catlist: [''], edisplay: '' } );
			if ('post' === value) {
				this.setState( { editing: false } );
			} else {
				this.setState( { editing: true } );
			}
		}
		const onChangePostType = value => {
			setAttributes({ terms: [] });
			setAttributes({ taxonomy: '' });
			setAttributes({ postType: value });
		};
		const onChangeTaxonomy = value => {
			setAttributes({ terms: [] });
			setAttributes({ taxonomy: value });
		};
		const termCheckChange = (value) => {
			const index = terms.indexOf(value);
			if (-1 === index) {
				setAttributes({ terms: [...terms, value] });
			} else {
				setAttributes({ terms: terms.filter(term => term !== value) });
			}
		};
		const elistCheckChange = (value) => {
			const index = elist.indexOf(value);
			if (-1 === index) {
				if ('' === value) {
					setAttributes({ elist: [value] });
				} else {
					setAttributes({ elist: [...elist, value] });
				}
			} else {
				if ('' === value) {
					setAttributes({ elist: [] });
				} else {
					setAttributes({ elist: elist.filter(episode => episode !== value) });
				}
			}
		};
		const slistCheckChange = (value) => {
			const index = slist.indexOf(value);
			if (-1 === index) {
				if ('' === value) {
					setAttributes({ slist: [value] });
				} else {
					setAttributes({ slist: [...slist, value] });
				}
			} else {
				if ('' === value) {
					setAttributes({ slist: [] });
				} else {
					setAttributes({ slist: slist.filter(season => season !== value) });
				}
			}
		};
		const catlistCheckChange = (value) => {
			const index = catlist.indexOf(value);
			if (-1 === index) {
				if ('' === value) {
					setAttributes({ catlist: [value] });
				} else {
					setAttributes({ catlist: [...catlist, value] });
				}
			} else {
				if ('' === value) {
					setAttributes({ catlist: [] });
				} else {
					setAttributes({ catlist: catlist.filter(category => category !== value) });
				}
			}
		};
		const onHourChange = e => {
			const hr = e.target.value;
			const min = msgTime[1] ? msgTime[1] : 0;
			const sec = msgTime[2] ? msgTime[2] : 0;
			setAttributes({ msgTime: [hr, min, sec] });
		};
		const onMinuteChange = e => {
			const min = e.target.value;
			const hr  = msgTime[0] ? msgTime[0] : 0;
			const sec = msgTime[2] ? msgTime[2] : 0;
			setAttributes({ msgTime: [hr, min, sec] });
		};
		const onSecondChange = e => {
			const sec = e.target.value;
			const hr  = msgTime[0] ? msgTime[0] : 0;
			const min = msgTime[1] ? msgTime[1] : 0;
			setAttributes({ msgTime: [hr, min, sec] });
		};

		if ( this.state.editing ) {
			return (
				<Fragment>
					<Placeholder
						icon="rss"
						label="RSS"
					>
						<form onSubmit={ this.onSubmitURL }>
							{
								!! ('feed' === fetchMethod && feedIndex && Array.isArray( feedIndex ) && feedIndex.length) &&
								<div style={{ width : "100%" }}>
								<SelectControl
									value={ feedURL }
									onChange={ ( value ) => setAttributes( { feedURL: value, elist: [''], slist: [''], catlist: [''], edisplay: '' } ) }
									options={ feedIndex }
									style={{ maxWidth: "none" }}
								/>
								<span style={{ width: "100%", textAlign: "center", marginBottom: "10px", display: "block" }}>OR</span>
								</div>
							}
							{
								'feed' === fetchMethod &&
								<div style={{ width : "100%" }}>
								<TextControl
									placeholder={ __( 'Enter URL here…', 'podcast-player' ) }
									value={ feedURL }
									onChange={ ( value ) => setAttributes( { feedURL: value, elist: [''], slist: [''], catlist: [''], edisplay: '' } ) }
									className={ 'components-placeholder__input' }
								/>
								</div>
							}
							{
								'link' === fetchMethod &&
								<TextControl
									placeholder={ __( 'Enter Audio/Video Link (i.e, mp3, ogg, m4a etc.)', 'podcast-player' ) }
									value={ audioSrc }
									onChange={ ( value ) => setAttributes( { audioSrc: value } ) }
									className={ 'components-placeholder__input' }
								/>
							}
							<Button type="submit" style={{ backgroundColor: "#f7f7f7" }}>
								{ __( 'Show Podcast', 'podcast-player' ) }
							</Button>
						</form>
					</Placeholder>
					<InspectorControls>
					{
						!! this.isPremium &&
						<PanelBody initialOpen={ true } title={ __( 'Setup Fetching Method', 'podcast-player' ) }>
							<SelectControl
								label={ __( 'Fetch Podcast Episodes', 'podcast-player' ) }
								value={ fetchMethod }
								onChange={ onFetchChange }
								options={ [
									{ value: 'feed', label: __( 'from Feed', 'podcast-player' ) },
									{ value: 'post', label: __( 'from Post', 'podcast-player' ) },
									{ value: 'link', label: __( 'from Audio/Video URL', 'podcast-player' ) },
								] }
							/>
						</PanelBody>
					}
					</InspectorControls>
				</Fragment>
			);
		}

		const toolbarControls = [
			{
				icon: 'edit',
				title: __( 'Edit RSS URL', 'podcast-player' ),
				onClick: () => this.setState( { editing: true } ),
			},
		];

		return (
			<Fragment>
				<BlockControls>
					<Toolbar controls={ toolbarControls } />
				</BlockControls>
				<InspectorControls>
					{
						!! this.isPremium &&
						<PanelBody initialOpen={ true } title={ __( 'Setup Fetching Method', 'podcast-player' ) }>
							<SelectControl
								label={ __( 'Fetch Podcast Episodes', 'podcast-player' ) }
								value={ fetchMethod }
								onChange={ onFetchChange }
								options={ [
									{ value: 'feed', label: __( 'from Feed', 'podcast-player' ) },
									{ value: 'post', label: __( 'from Post', 'podcast-player' ) },
									{ value: 'link', label: __( 'from Audio/Video URL', 'podcast-player' ) },
								] }
							/>
							{
								(postTypes && 'post' === fetchMethod) &&
								<SelectControl
									label={ __( 'Select Post Type', 'podcast-player' ) }
									value={ postType }
									options={ postTypes }
									onChange={ (value) => onChangePostType(value) }
								/>
							}
							{
								(postType && !! taxonomies.length && 'post' === fetchMethod) &&
								<SelectControl
									label={ __( 'Get items by Taxonomy', 'podcast-player' ) }
									value={ taxonomy }
									options={ taxonomies }
									onChange={ ( value ) => onChangeTaxonomy(value) }
								/>
							}
							{
								(!! termsList.length && 'post' === fetchMethod) &&
								<MultipleCheckboxControl
									listItems={ termsList }
									selected={ terms }
									onItemChange={ termCheckChange }
									label = { __( 'Select Taxonomy Terms', 'podcast-player' ) }
								/>
							}
							{
								'link' === fetchMethod &&
								<TextControl
									label={ __( 'Episode Title', 'podcast-player' ) }
									value={ audioTitle }
									onChange={ ( value ) => setAttributes( { audioTitle: value } ) }
								/>
							}
							{
								'link' === fetchMethod &&
								<TextControl
									label={ __( 'Podcast episode link for social sharing (optional)', 'podcast-player' ) }
									value={ audioLink }
									onChange={ ( value ) => setAttributes( { audioLink: value } ) }
								/>
							}
							{
								'link' === fetchMethod &&
								<ToggleControl
									label={ __( 'Hide Episode Download Link', 'podcast-player' ) }
									checked={ !! ahideDownload }
									onChange={ ( value ) => setAttributes( { ahideDownload: value } ) }
								/>
							}
							{
								'link' === fetchMethod &&
								<ToggleControl
									label={ __( 'Hide Social Share Links', 'podcast-player' ) }
									checked={ !! ahideSocial }
									onChange={ ( value ) => setAttributes( { ahideSocial: value } ) }
								/>
							}
						</PanelBody>
					}
					{
						<PanelBody initialOpen={ false } title={ __( 'Change Podcast Content', 'podcast-player' ) }>
							{
								this.isPremium && 'post' === fetchMethod &&
								<TextControl
									label={ __( 'Podcast Title', 'podcast-player' ) }
									value={ podtitle }
									onChange={ ( value ) => setAttributes( { podtitle: value } ) }
								/>
							}
							<MediaUpload
								onSelect={ ( media ) => setAttributes( { coverImage: media.url } ) }
								type="image"
								value={ coverImage }
								render={ ( { open } ) => (
									<Button className="pp-cover-btn" onClick={ open }>
										{ ! coverImage ?
											<div className="no-image">
												<Dashicon icon="format-image" />
												{ __( 'Upload Cover Image', 'podcast-player' ) }
											</div> :
											<img
												className="ppe-cover-image"
												src={ coverImage }
												alt={ __( 'Cover Image', 'podcast-player' ) }
											/>
										}
									</Button>
								) }
							>
							</MediaUpload>
							{
								coverImage &&
								<Button className="remove-pp-cover" onClick={ () => setAttributes( { coverImage: '' } ) }>
									{ __( 'Remove Cover Image', 'podcast-player' ) }
								</Button>
							}
							<TextareaControl
								label={ __( 'Brief Description', 'podcast-player' ) }
								help={ __( 'Change Default Podcast Description', 'podcast-player' ) }
								value={ description }
								onChange={ ( value ) => setAttributes( { description: value } ) }
							/>
							{
								'link' !== fetchMethod &&
								<RangeControl
									label={ __( 'Number of episodes to show at a time', 'podcast-player' ) }
									value={ number }
									onChange={ ( value ) => setAttributes( { number: value } ) }
									min={ 1 }
									max={ 1000 }
								/>
							}
							{
								'link' !== fetchMethod &&
								<RangeControl
									label={ __( 'Number of episodes to skip from the beginning', 'podcast-player' ) }
									value={ offset }
									onChange={ ( value ) => setAttributes( { offset: value } ) }
									min={ 0 }
									max={ 1000 }
								/>
							}
							{
								ifStyleSupport(displayStyle, 'excerpt') && 'link' !== fetchMethod &&
								<SelectControl
									label={ __( 'Teaser Text', 'podcast-player' ) }
									value={ teaserText }
									onChange={ ( value ) => setAttributes( { teaserText: value } ) }
									options={ [
										{ value: '', label: __( 'Show Excerpt', 'podcast-player' ) },
										{ value: 'full', label: __( 'Show Full Content', 'podcast-player' ) },
										{ value: 'none', label: __( 'Do not Show Teaser Text', 'podcast-player' ) },
									] }
								/>
							}
							{
								ifStyleSupport(displayStyle, 'excerpt') && '' === teaserText && 'link' !== fetchMethod &&
								<RangeControl
									label={ __( 'Excerpt Length', 'podcast-player' ) }
									value={ excerptLength }
									onChange={ ( value ) => setAttributes( { excerptLength: value } ) }
									min={ 0 }
									max={ 200 }
								/>
							}
							{
								ifStyleSupport(displayStyle, 'excerpt') && '' === teaserText && 'link' !== fetchMethod &&
								<SelectControl
									label={ __( 'Excerpt Length Unit', 'podcast-player' ) }
									value={ excerptUnit }
									onChange={ ( value ) => setAttributes( { excerptUnit: value } ) }
									options={ [
										{ value: '', label: __( 'Number of words', 'podcast-player' ) },
										{ value: 'char', label: __( 'Number of characters', 'podcast-player' ) },
									] }
								/>
							}
						</PanelBody>
					}
					{
						<PanelBody initialOpen={ false } title={ __( 'Subscription Buttons', 'podcast-player' ) }>
							{
								( ! podcastMenu || ! mainMenuItems ) &&
								<TextControl
									label={ __( 'Add a Podcast Subscription link', 'podcast-player' ) }
									placeholder={ __( 'Apple Subscription Link', 'podcast-player' ) }
									value={ appleSub }
									onChange={ ( value ) => setAttributes( { appleSub: value } ) }
								/>
							}
							{
								( ! podcastMenu || ! mainMenuItems ) &&
								<TextControl
									label={ __( 'Add a Podcast Subscription link', 'podcast-player' ) }
									placeholder={ __( 'Google Subscription Link', 'podcast-player' ) }
									value={ googleSub }
									onChange={ ( value ) => setAttributes( { googleSub: value } ) }
								/>
							}
							{
								( ! podcastMenu || ! mainMenuItems ) &&
								<TextControl
									label={ __( 'Add a Podcast Subscription link', 'podcast-player' ) }
									placeholder={ __( 'Spotify Subscription Link', 'podcast-player' ) }
									value={ spotifySub }
									onChange={ ( value ) => setAttributes( { spotifySub: value } ) }
								/>
							}
							{
								( ! podcastMenu || ! mainMenuItems ) &&
								<TextControl
									label={ __( 'Add a Podcast Subscription link', 'podcast-player' ) }
									placeholder={ __( 'Breaker Subscription Link', 'podcast-player' ) }
									value={ breakerSub }
									onChange={ ( value ) => setAttributes( { breakerSub: value } ) }
								/>
							}
							{
								( ! podcastMenu || ! mainMenuItems ) &&
								<TextControl
									label={ __( 'Add a Podcast Subscription link', 'podcast-player' ) }
									placeholder={ __( 'Castbox Subscription Link', 'podcast-player' ) }
									value={ castboxSub }
									onChange={ ( value ) => setAttributes( { castboxSub: value } ) }
								/>
							}
							{
								( ! podcastMenu || ! mainMenuItems ) &&
								<TextControl
									label={ __( 'Add a Podcast Subscription link', 'podcast-player' ) }
									placeholder={ __( 'Castro Subscription Link', 'podcast-player' ) }
									value={ castroSub }
									onChange={ ( value ) => setAttributes( { castroSub: value } ) }
								/>
							}
							{
								( ! podcastMenu || ! mainMenuItems ) &&
								<TextControl
									label={ __( 'Add a Podcast Subscription link', 'podcast-player' ) }
									placeholder={ __( 'iHeart Radio Subscription Link', 'podcast-player' ) }
									value={ iheartSub }
									onChange={ ( value ) => setAttributes( { iheartSub: value } ) }
								/>
							}
							{
								( ! podcastMenu || ! mainMenuItems ) &&
								<TextControl
									label={ __( 'Add a Podcast Subscription link', 'podcast-player' ) }
									placeholder={ __( 'Amazon Music Subscription Link', 'podcast-player' ) }
									value={ amazonSub }
									onChange={ ( value ) => setAttributes( { amazonSub: value } ) }
								/>
							}
							{
								( ! podcastMenu || ! mainMenuItems ) &&
								<TextControl
									label={ __( 'Add a Podcast Subscription link', 'podcast-player' ) }
									placeholder={ __( 'Overcast Subscription Link', 'podcast-player' ) }
									value={ overcastSub }
									onChange={ ( value ) => setAttributes( { overcastSub: value } ) }
								/>
							}
							{
								( ! podcastMenu || ! mainMenuItems ) &&
								<TextControl
									label={ __( 'Add a Podcast Subscription link', 'podcast-player' ) }
									placeholder={ __( 'Pocket Casts Subscription Link', 'podcast-player' ) }
									value={ pocketcastsSub }
									onChange={ ( value ) => setAttributes( { pocketcastsSub: value } ) }
								/>
							}
							{
								( ! podcastMenu || ! mainMenuItems ) &&
								<TextControl
									label={ __( 'Add a Podcast Subscription link', 'podcast-player' ) }
									placeholder={ __( 'Podcast Addict Subscription Link', 'podcast-player' ) }
									value={ podcastaddictSub }
									onChange={ ( value ) => setAttributes( { podcastaddictSub: value } ) }
								/>
							}
							{
								( ! podcastMenu || ! mainMenuItems ) &&
								<TextControl
									label={ __( 'Add a Podcast Subscription link', 'podcast-player' ) }
									placeholder={ __( 'Podchaser Subscription Link', 'podcast-player' ) }
									value={ podchaserSub }
									onChange={ ( value ) => setAttributes( { podchaserSub: value } ) }
								/>
							}
							{
								( ! podcastMenu || ! mainMenuItems ) &&
								<TextControl
									label={ __( 'Add a Podcast Subscription link', 'podcast-player' ) }
									placeholder={ __( 'Radio Public Subscription Link', 'podcast-player' ) }
									value={ radiopublicSub }
									onChange={ ( value ) => setAttributes( { radiopublicSub: value } ) }
								/>
							}
							{
								( ! podcastMenu || ! mainMenuItems ) &&
								<TextControl
									label={ __( 'Add a Podcast Subscription link', 'podcast-player' ) }
									placeholder={ __( 'SoundCloud Subscription Link', 'podcast-player' ) }
									value={ soundcloudSub }
									onChange={ ( value ) => setAttributes( { soundcloudSub: value } ) }
								/>
							}
							{
								( ! podcastMenu || ! mainMenuItems ) &&
								<TextControl
									label={ __( 'Add a Podcast Subscription link', 'podcast-player' ) }
									placeholder={ __( 'Stitcher Subscription Link', 'podcast-player' ) }
									value={ stitcherSub }
									onChange={ ( value ) => setAttributes( { stitcherSub: value } ) }
								/>
							}
							{
								( ! podcastMenu || ! mainMenuItems ) &&
								<TextControl
									label={ __( 'Add a Podcast Subscription link', 'podcast-player' ) }
									placeholder={ __( 'Tune In Subscription Link', 'podcast-player' ) }
									value={ tuneinSub }
									onChange={ ( value ) => setAttributes( { tuneinSub: value } ) }
								/>
							}
							{
								( ! podcastMenu || ! mainMenuItems ) &&
								<TextControl
									label={ __( 'Add a Podcast Subscription link', 'podcast-player' ) }
									placeholder={ __( 'YouTube Subscription Link', 'podcast-player' ) }
									value={ youtubeSub }
									onChange={ ( value ) => setAttributes( { youtubeSub: value } ) }
								/>
							}
							{
								( ! podcastMenu || ! mainMenuItems ) &&
								<TextControl
									label={ __( 'Add a Podcast Subscription link', 'podcast-player' ) }
									placeholder={ __( 'Bull Horn Subscription Link', 'podcast-player' ) }
									value={ bullhornSub }
									onChange={ ( value ) => setAttributes( { bullhornSub: value } ) }
								/>
							}
							{
								( ! podcastMenu || ! mainMenuItems ) &&
								<TextControl
									label={ __( 'Add a Podcast Subscription link', 'podcast-player' ) }
									placeholder={ __( 'Podbean Subscription Link', 'podcast-player' ) }
									value={ podbeanSub }
									onChange={ ( value ) => setAttributes( { podbeanSub: value } ) }
								/>
							}
							{
								( ! podcastMenu || ! mainMenuItems ) &&
								<TextControl
									label={ __( 'Add a Podcast Subscription link', 'podcast-player' ) }
									placeholder={ __( 'PlayerFM Subscription Link', 'podcast-player' ) }
									value={ playerfmSub }
									onChange={ ( value ) => setAttributes( { playerfmSub: value } ) }
								/>
							}
							<SelectControl
								label={ __( 'Podcast Subscription Menu', 'podcast-player' ) }
								value={ podcastMenu }
								onChange={ ( value ) => setAttributes( { podcastMenu: value } ) }
								options={ navMenu }
							/>
							{
								( !! podcastMenu && !! mainMenuItems ) &&
								<RangeControl
									label={ __( 'Number of Primary Subscription Links', 'podcast-player' ) }
									value={ mainMenuItems }
									onChange={ ( value ) => setAttributes( { mainMenuItems: value } ) }
									min={ 0 }
									max={ 20 }
								/>
							}
						</PanelBody>
					}
					{
						'link' !== fetchMethod &&
						<PanelBody initialOpen={ false } title={ __( 'Show/Hide Player Items', 'podcast-player' ) }>
							{
								(!displayStyle || 'legacy' === displayStyle || 'modern' === displayStyle ) &&
								<ToggleControl
									label={ __( 'Show Podcast Header by Default', 'podcast-player' ) }
									checked={ !! headerDefault }
									onChange={ ( value ) => setAttributes( { headerDefault: value } ) }
								/>
							}
							{
								(!displayStyle || 'legacy' === displayStyle || 'modern' === displayStyle) &&
								<ToggleControl
									label={ __( 'Show episodes list by default on mini player.', 'podcast-player' ) }
									checked={ !! listDefault }
									onChange={ ( value ) => setAttributes( { listDefault: value } ) }
								/>
							}
							<ToggleControl
								label={ __( 'Hide Podcast Header', 'podcast-player' ) }
								checked={ !! hideHeader }
								onChange={ ( value ) => setAttributes( { hideHeader: value } ) }
							/>
							{
								!hideHeader &&
								<ToggleControl
									label={ __( 'Hide cover image', 'podcast-player' ) }
									checked={ !! hideCover }
									onChange={ ( value ) => setAttributes( { hideCover: value } ) }
								/>
							}
							{
								!hideHeader &&
								<ToggleControl
									label={ __( 'Hide Podcast Title', 'podcast-player' ) }
									checked={ !! hideTitle }
									onChange={ ( value ) => setAttributes( { hideTitle: value } ) }
								/>
							}
							{
								!hideHeader &&
								<ToggleControl
									label={ __( 'Hide Podcast Description', 'podcast-player' ) }
									checked={ !! hideDesc }
									onChange={ ( value ) => setAttributes( { hideDesc: value } ) }
								/>
							}
							{
								!hideHeader &&
								<ToggleControl
									label={ __( 'Hide Custom menu', 'podcast-player' ) }
									checked={ !! hideSubscribe }
									onChange={ ( value ) => setAttributes( { hideSubscribe: value } ) }
								/>
							}
							<ToggleControl
								label={ __( 'Hide Podcast Search', 'podcast-player' ) }
								checked={ !! hideSearch }
								onChange={ ( value ) => setAttributes( { hideSearch: value } ) }
							/>
							<ToggleControl
								label={ __( 'Hide Episode Author/Podcaster Name', 'podcast-player' ) }
								checked={ !! hideAuthor }
								onChange={ ( value ) => setAttributes( { hideAuthor: value } ) }
							/>
							{
								'feed' === fetchMethod &&
								<ToggleControl
									label={ __( 'Hide Episode Text Content/Transcript', 'podcast-player' ) }
									checked={ !! hideContent }
									onChange={ ( value ) => setAttributes( { hideContent: value } ) }
								/>
							}
							<ToggleControl
								label={ __( 'Hide Load More Episodes Button', 'podcast-player' ) }
								checked={ !! hideLoadmore }
								onChange={ ( value ) => setAttributes( { hideLoadmore: value } ) }
							/>
							<ToggleControl
								label={ __( 'Hide Episode Download Link', 'podcast-player' ) }
								checked={ !! hideDownload }
								onChange={ ( value ) => setAttributes( { hideDownload: value } ) }
							/>
							<ToggleControl
								label={ __( 'Hide Social Share Links', 'podcast-player' ) }
								checked={ !! hideSocial }
								onChange={ ( value ) => setAttributes( { hideSocial: value } ) }
							/>
							<ToggleControl
								label={ __( 'Hide Episodes Featured Image', 'podcast-player' ) }
								checked={ !! hideFeatured }
								onChange={ ( value ) => setAttributes( { hideFeatured: value } ) }
							/>
						</PanelBody>
					}
					<PanelBody initialOpen={ false } title={ __( 'Podcast Player Styling', 'podcast-player' ) }>
						<SelectControl
							label={ __( 'Podcast Player Display Style', 'podcast-player' ) }
							value={ displayStyle }
							onChange={ ( value ) => setAttributes( { displayStyle: value } ) }
							options={ styles }
						/>
						{
							ifStyleSupport(displayStyle, 'thumbnail') &&
							<SelectControl
								label={ __( 'Thumbnail Cropping', 'podcast-player' ) }
								value={ aspectRatio }
								onChange={ ( aspectRatio ) => setAttributes( { aspectRatio } ) }
								options={ aspectOptions }
							/>
						}
						{
							(ifStyleSupport(displayStyle, 'thumbnail') && aspectRatio) &&
							<SelectControl
								label={ __( 'Thumbnail Cropping Position', 'podcast-player' ) }
								value={ cropMethod }
								onChange={ ( cropMethod ) => setAttributes( { cropMethod } ) }
								options={ cropOptions }
							/>
						}
						{
							ifStyleSupport(displayStyle, 'grid') &&
							<RangeControl
								label={ __( 'Grid Columns', 'podcast-player' ) }
								value={ gridColumns }
								onChange={ ( value ) => setAttributes( { gridColumns: value } ) }
								min={ 1 }
								max={ 6 }
							/>
						}
						{
							!!this.isPremium &&
							<SelectControl
								label={ __( 'Select Font Family', 'podcast-player' ) }
								value={ fontFamily }
								options={ fontFamilies }
								onChange={ ( value ) => setAttributes( { fontFamily: value } ) }
							/>
						}
						{
							(!!this.isPremium && ifStyleSupport(displayStyle, 'txtcolor')) &&
							<SelectControl
								label={ __( 'Text Color Scheme', 'podcast-player' ) }
								value={ txtColor }
								options={ [
									{ value: '', label: __( 'Dark Text', 'podcast-player' ) },
									{ value: 'ltext', label: __( 'Light Text', 'podcast-player' ) },
								] }
								onChange={ ( value ) => setAttributes( { txtColor: value } ) }
							/>
						}
					</PanelBody>
					<PanelColorSettings
						title={ __( 'Podcast Player Color Scheme', 'podcast-player' ) }
						initialOpen={ false }
						colorSettings={ [
							{
								value: accentColor,
								onChange: ( value ) => setAttributes( { accentColor: value } ),
								label: __( 'Accent Color', 'podcast-player' ),
							},
							...( !!this.isPremium && ifStyleSupport(displayStyle, 'bgcolor') ? [ {
								value: bgColor,
								onChange: ( value ) => setAttributes( { bgColor: value } ),
								label: __( 'Player Background Color', 'podcast-player' ),
							} ] : [] ),
						] }
					>
					</PanelColorSettings>
					{
						'link' !== fetchMethod &&
						<PanelBody initialOpen={ false } title={ __( 'Sort & Filter Options', 'podcast-player' ) }>
							<SelectControl
								label={ __( 'Sort Podcast Episodes By', 'podcast-player' ) }
								value={ sortBy }
								onChange={ ( value ) => setAttributes( { sortBy: value } ) }
								options={ [
									{ value: 'sort_title_desc', label: __( 'Title Descending', 'podcast-player' ) },
									{ value: 'sort_title_asc', label: __( 'Title Ascending', 'podcast-player' ) },
									{ value: 'sort_date_desc', label: __( 'Date Descending', 'podcast-player' ) },
									{ value: 'sort_date_asc', label: __( 'Date Ascending', 'podcast-player' ) },
									{ value: 'no_sort', label: __( 'Do Not Sort', 'podcast-player' ) },
									{ value: 'reverse_sort', label: __( 'Reverse Sort', 'podcast-player' ) },
								] }
							/>
							<TextControl
								label={ __( 'Show episodes only if title contains following', 'podcast-player' ) }
								value={ filterBy }
								onChange={ ( value ) => setAttributes( { filterBy: value } ) }
							/>
							{
								(1 < seasonList.length && 'feed' === fetchMethod) &&
								<EpisodeCheckboxControl
									listItems={ seasonList }
									selected={ slist }
									onItemChange={ slistCheckChange }
									label = { __( 'Select Seasons to be displayed', 'podcast-player' ) }
								/>
							}
							{
								(1 < categoryList.length && 'feed' === fetchMethod) &&
								<EpisodeCheckboxControl
									listItems={ categoryList }
									selected={ catlist }
									onItemChange={ catlistCheckChange }
									label = { __( 'Select Categories to be displayed', 'podcast-player' ) }
								/>
							}
							{
								(!! episodeList.length && 'link' !== fetchMethod) &&
								<EpisodeCheckboxControl
									listItems={ episodeList }
									selected={ elist }
									onItemChange={ elistCheckChange }
									label = { __( 'Select Episodes to be displayed', 'podcast-player' ) }
								/>
							}
							{
								(!! episodeList.length && 'link' !== fetchMethod && !! elist.filter(Boolean).length) &&
								<SelectControl
									label={ __( 'Show or Hide above selected episodes', 'podcast-player' ) }
									value={ edisplay }
									onChange={ ( value ) => setAttributes( { edisplay: value } ) }
									options={ [
										{ value: '', label: __( 'Show above selected episodes', 'podcast-player' ) },
										{ value: 'hide', label: __( 'Hide above selected episodes', 'podcast-player' ) },
									] }
								/>
							}
						</PanelBody>
					}
					{
						!!this.isPremium &&
						<PanelBody initialOpen={ false } title={ __( 'Custom Audio Message', 'podcast-player' ) }>
							<TextControl
								label={ __( 'Enter URL of mp3 audio file to be played', 'podcast-player' ) }
								value={ audioMsg }
								onChange={ ( value ) => setAttributes( { audioMsg: value } ) }
							/>
							<RangeControl
								label={ __( 'Replay Frequency', 'podcast-player' ) }
								help={ __( 'After how many episodes the audio should be replayed', 'podcast-player' ) }
								value={ playFreq }
								onChange={ ( value ) => setAttributes( { playFreq: value } ) }
								min={ 0 }
								max={ 100 }
							/>
							<SelectControl
								label={ __( 'When to start playing the audio message', 'podcast-player' ) }
								value={ msgStart }
								onChange={ ( value ) => setAttributes( { msgStart: value } ) }
								options={ [
									{ value: 'start', label: __( 'Start of the Episode', 'podcast-player' ) },
									{ value: 'end', label: __( 'End of the Episode', 'podcast-player' ) },
									{ value: 'custom', label: __( 'Custom Time', 'podcast-player' ) },
								] }
							/>
							{
								( msgStart && 'custom' === msgStart ) &&
								<div className="components-base-control">
									<label className="components-base-control__label">{ __( 'Start playing audio at (time in hh:mm:ss)' ) }</label>
									<div className="components-datetime__time-field components-datetime__time-field-time">
										<input
											className="components-datetime__time-field-hours-input"
											type="number"
											step={ 1 }
											min={ 0 }
											max={ 10 }
											value={ msgTime[0] }
											onChange={ onHourChange }
										/>
										<span
											className="components-datetime__time-separator"
											aria-hidden="true">:</span>
										<input
											className="components-datetime__time-field-hours-input"
											type="number"
											step={ 1 }
											min={ 0 }
											max={ 59 }
											value={ msgTime[1] }
											onChange={ onMinuteChange }
										/>
										<span
											className="components-datetime__time-separator"
											aria-hidden="true">:</span>
										<input
											className="components-datetime__time-field-hours-input"
											type="number"
											step={ 1 }
											min={ 0 }
											max={ 59 }
											value={ msgTime[2] }
											onChange={ onSecondChange }
										/>
									</div>
								</div>
							}
							<TextControl
								label={ __( 'Message to be displayed while playing audio.', 'podcast-player' ) }
								value={ msgText }
								onChange={ ( value ) => setAttributes( { msgText: value } ) }
							/>
						</PanelBody>
					}
				</InspectorControls>
				<Disabled>
					<ServerSideRender
						block="podcast-player/podcast-player"
						attributes={ this.props.attributes }
					/>
				</Disabled>
			</Fragment>
		);
	}
}

export default PodcastPlayer;
