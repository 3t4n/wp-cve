(function(wp){

	"use strict";

	const { registerBlockType   } = wp.blocks;
	const { createElement,
	        useState,
	        useEffect,
	        Fragment,
	        Component,
	        render              } = wp.element;
	const { useBlockProps,
	        BlockControls,
	        RichText,
	        InnerBlocks,
	        InspectorControls,
	        UseInnerBlocksProps } = wp.blockEditor;
	const { SelectControl,
	        __experimentalUnitControl: UnitControl,
	        TextControl,
	        RangeControl,
	        ToggleControl,
	        RadioControl,
	        PanelBody,
	        PanelRow,
	        Button,
	        Toolbar,
	        ToolbarButton       } = wp.components;
	const { __, sprintf         } = wp.i18n;


	const icon = createElement(
		"svg",
		{
			xmlns   : "http://www.w3.org/2000/svg",
			viewBox : "0 0 448 512",
		},
		createElement(
			"path",
			{
				d: "M224.1 141c-63.6 0-114.9 51.3-114.9 114.9s51.3 114.9 114.9 114.9S339 319.5 339 255.9 287.7 141 224.1 141z" +
				   "m0 189.6c-41.1 0-74.7-33.5-74.7-74.7s33.5-74.7 74.7-74.7 74.7 33.5 74.7 74.7-33.6 74.7-74.7 74.7z" +
				   "m146.4-194.3c0 14.9-12 26.8-26.8 26.8-14.9 0-26.8-12-26.8-26.8s12-26.8 26.8-26.8 26.8 12 26.8 26.8z" +
				   "m76.1 27.2c-1.7-35.9-9.9-67.7-36.2-93.9-26.2-26.2-58-34.4-93.9-36.2-37-2.1-147.9-2.1-184.9 0-35.8 1.7-67.6 9.9-93.9 36.1" +
				   "s-34.4 58-36.2 93.9c-2.1 37-2.1 147.9 0 184.9 1.7 35.9 9.9 67.7 36.2 93.9s58 34.4 93.9 36.2" +
				   "c37 2.1 147.9 2.1 184.9 0 35.9-1.7 67.7-9.9 93.9-36.2 26.2-26.2 34.4-58 36.2-93.9 2.1-37 2.1-147.8 0-184.8z" +
				   "M398.8 388c-7.8 19.6-22.9 34.7-42.6 42.6-29.5 11.7-99.5 9-132.1 9s-102.7 2.6-132.1-9" +
				   "c-19.6-7.8-34.7-22.9-42.6-42.6-11.7-29.5-9-99.5-9-132.1s-2.6-102.7 9-132.1c7.8-19.6 22.9-34.7 42.6-42.6 29.5-11.7 99.5-9 132.1-9" +
				   "s102.7-2.6 132.1 9c19.6 7.8 34.7 22.9 42.6 42.6 11.7 29.5 9 99.5 9 132.1s2.7 102.7-9 132.1z",
			}
		)
	);


	registerBlockType( "combine-social-photos/simple-grid", {

		icon: icon,

		edit: ( { attributes, setAttributes } ) => {

			const REST_API_NAMESPACE = window.$stillbe?.rest.namespace || '';

			const [ authUser,  setAuthUser  ] = useState( {} );
			const [ users,     setUsers     ] = useState( [] );

			const [ apiType,   setApiType   ] = useState( null );
			const [ igName,    setIgName    ] = useState( "" );

			const [ convBtn,   setConvBtn   ] = useState( {
				label    : __("Start Convertion", "still-be-combine-social-photos"),
				disabled : apiType !== "Graph API",
			} );

			// Get IG Users
			useEffect( () => {

				wp.apiFetch({ path: `${REST_API_NAMESPACE}/user` })

				.then(json => {
					if(!json.ok && json.data){
						Promise.reject(json.message || __("An error has occured.", "still-be-combine-social-photos"));
					}
					const newUsers  = [];
					const newIusers = [];
					for(const id in json.data){
						if(json.data[id].active){
							newUsers.push({
								label: json.data[id].name,
								value: id,
								api  : json.data[id].api,
							});
						}
						if(id == attributes.id){
							setIgName(json.data[id].name);
							setApiType(json.data[id].api);
						}
					}
					setUsers([{ label: __("-- Select User --", "still-be-combine-social-photos"), value: 0 }, ...newUsers]);
				})

				.catch(console.error);

			}, [ attributes.id ] );


			useEffect( () => {

				setConvBtn( {
					label    : __("Start Convertion", "still-be-combine-social-photos"),
					disabled : apiType !== "Graph API",
				} );

			}, [ apiType, attributes.hashtag ] );


			useEffect( () => {

				setAttributes({
					hasOutlineShadow: !/\bis-style-simple\b/i.test(attributes.className),
				});

			}, [ attributes.className ] );


			const convertHashtag = () => {

				const convBtnObj = Object.assign({}, convBtn);

				convBtnObj.label    = __("Converting...", "still-be-combine-social-photos");
				convBtnObj.disabled = true;
				setConvBtn(convBtnObj);

				wp.apiFetch({
					path   : `${REST_API_NAMESPACE}/hashtag/convert`,
					method : "POST",
					data   : {
						hashtag: attributes.hashtag,
						user_id: attributes.id,
					},
				})

				.then(json => {
					if(!json.ok){
						console.error(json);
						Promise.reject(json.message || __("An error has occured.", "still-be-combine-social-photos"));
						return;
					}
					convBtnObj.label = __("Converted!!", "still-be-combine-social-photos");
					setConvBtn({...convBtnObj});
					setAttributes({hashtagId: json.hashtag_id});
				})

				.catch($reason => {
					alert($reason.message);
					convBtnObj.label    = __("Start Convertion", "still-be-combine-social-photos");
					convBtnObj.disabled = false;
					setConvBtn({...convBtnObj});
				});

			};



			const usersProps = ({
				label    : __("Select IG User", "still-be-combine-social-photos"),
				options  : users,
				value    : attributes.id,
				onChange : value => {
					setAttributes({id: parseInt(value, 10)});
					for(const user of users){
						if(value == user.value){
							setIgName(user.label);
							setApiType(user.api);
							break;
						}
					}
				},
			});

			const authUserProps = ({
				variant : "secondary",
				text    : __("Link another account", "still-be-combine-social-photos"),
				onClick : openAuthWindow.bind(null, $id => setAttributes({id: parseInt($id, 10)})),
			});

			const gettingTypeProps = ({
				label    : __("Type of Getting Posts", "still-be-combine-social-photos"),
				selected : attributes.gettingType,
				options  : [
					{ label: __("My Own Recent Posts",                     "still-be-combine-social-photos"), value: "own"                },
					{ label: __("Other User's Recent Posts",               "still-be-combine-social-photos"), value: "business_discovery" },
					{ label: __("Recent Posts with Hashtag (within 24hr)", "still-be-combine-social-photos"), value: "hashtag_recent"     },
					{ label: __("Most Popular Posts with Hashtag",         "still-be-combine-social-photos"), value: "hashtag_top"        },
				],
				onChange : value => setAttributes({gettingType: value}),
			});


			/**
			 * Business Discovery
			 * 
			 */

			const businessDicoveryProps = ({
				label    : __("Other User's Username", "still-be-combine-social-photos"),
				disabled : apiType !== "Graph API",
				value    : attributes.businessDiscovery,
				onChange : value => setAttributes({businessDiscovery: String(value)}),
				help     : __("To get your own posts, leave blank. Get posts from another user. If you do not find them, check if the user you specified is not a professional account (BUSINESS or MEDIA CREATOR) or if the user name has been changed.", "still-be-combine-social-photos"),
			});


			/**
			 * Hashtag
			 * 
			 */

			const hashtagProps = ({
				label    : __("Hashtag", "still-be-combine-social-photos"),
				disabled : apiType !== "Graph API",
				value    : attributes.hashtag,
				onChange : value => setAttributes({hashtag: String(value).trim().toLowerCase()}),
				help     : __("The hashtag must be converted to an ID in Instagram, with a conversion frequency limit of 30 times per 7 days. ", "still-be-combine-social-photos") +
				           " " || __("To search by hashtag IDs converted by other users, please allow data to be sent to the Still BE server from the settings screen.", "still-be-combine-social-photos"),
			});

			const convertHashtagProps = ({
				variant : "secondary",
				disabled: convBtn.disabled,
				text    : convBtn.label,
				onClick : convertHashtag,
			});

			const hashtagIdProps = ({
				disabled  : true,
				value     : attributes.hashtagId,
				className : "display-none",
			});


			/**
			 * Video Option
			 * 
			 */

			const displayingVideoProps = ({
				label    : __("Displaying Video", "still-be-combine-social-photos"),
				selected : attributes.displayingVideo,
				options  : [
					{ label: __("Thumbnail", "still-be-combine-social-photos"), value: "thumbnail" },
					{ label: __("Ignore",    "still-be-combine-social-photos"), value: "ignore"    },
					{ label: __("Autoplay",  "still-be-combine-social-photos"), value: "autoplay"  },
				],
				onChange : value => setAttributes({displayingVideo: value}),
			});


			/**
			 * Outline Gap
			 * 
			 */

			const outlineGapProps = ({
				label : __("Outline Gap (PC)", "still-be-combine-social-photos"),
				value : attributes.outlineGap,
				units : [
					{ value: 'em',  label: 'em',  default: 0.5 },
					{ value: 'rem', label: 'rem', default: 0.4 },
					{ value: 'px',  label: 'px',  default: 8   },
					{ value: 'vw',  label: 'vw',  default: 1   },
				],
				onChange : value => setAttributes({outlineGap: value}),
			});

			const outlineGapTabletProps = ({
				label : __("Outline Gap (Tablet)", "still-be-combine-social-photos"),
				value : attributes.outlineGapTablet,
				units : [
					{ value: 'em',  label: 'em',  default: 0.5 },
					{ value: 'rem', label: 'rem', default: 0.4 },
					{ value: 'px',  label: 'px',  default: 8   },
					{ value: 'vw',  label: 'vw',  default: 1   },
				],
				onChange : value => setAttributes({outlineGapTablet: value}),
			});

			const outlineGapSpProps = ({
				label : __("Outline Gap (SP)", "still-be-combine-social-photos"),
				value : attributes.outlineGapSp,
				units : [
					{ value: 'em',  label: 'em',  default: 0.5 },
					{ value: 'rem', label: 'rem', default: 0.4 },
					{ value: 'px',  label: 'px',  default: 8   },
					{ value: 'vw',  label: 'vw',  default: 1   },
				],
				onChange : value => setAttributes({outlineGapSp: value}),
			});


			/**
			 * Grid Size
			 *  for PC
			 */

			const colsProps = ({
				label : __("Columns", "still-be-combine-social-photos"),
				value : attributes.columns,
				min   : 1,
				max   : 9,
				initialPosition : 3,
				onChange : value => setAttributes({columns: parseInt(value, 10)}),
			});

			const rowsProps = ({
				label : __("Rows", "still-be-combine-social-photos"),
				value : attributes.rows,
				min   : 1,
				max   : 9,
				initialPosition : 3,
				onChange : value => setAttributes({rows: parseInt(value, 10)}),
			});

			const aspectProps = ({
				label : __("Aspect Ratio", "still-be-combine-social-photos"),
				value : Math.log10(attributes.aspect),
				min   : -Math.log10(4),
				max   : +Math.log10(4),
				step  : 0.001,
				initialPosition    : 0,
				allowReset         : true,
				resetFallbackValue : 0,
				withInputField     : false,
				showTooltip        : false,
				onChange : value => setAttributes({aspect: (~~(Math.pow(10, value * 1) * 1000 + 0.5)) / 1000}),
			});

			const aspectValueProps = ({
				label : "",
				value : attributes.aspect,
				readOnly: true,
				style : {
					width      : "64px",
					margin     : "-8px 0 0 16px",
					textAlign  : "center",
					background : "transparent",
				},
			});

			const gapProps = ({
				label : __("Gap", "still-be-combine-social-photos"),
				value : attributes.gap,
				units : [
					{ value: 'em',  label: 'em',  default: 0.5 },
					{ value: 'rem', label: 'rem', default: 0.4 },
					{ value: 'px',  label: 'px',  default: 8   },
					{ value: 'vw',  label: 'vw',  default: 1   },
				],
				onChange : value => setAttributes({gap: value}),
			});


			/**
			 * Grid Size
			 *  for Tablet
			 */

			const colsTabletProps = ({
				label : __("Columns", "still-be-combine-social-photos"),
				value : attributes.columnsTablet,
				min   : 1,
				max   : 9,
				initialPosition : 3,
				onChange : value => setAttributes({columnsTablet: parseInt(value, 10)}),
			});

			const rowsTabletProps = ({
				label : __("Rows", "still-be-combine-social-photos"),
				value : attributes.rowsTablet,
				min   : 1,
				max   : 9,
				initialPosition : 3,
				onChange : value => setAttributes({rowsTablet: parseInt(value, 10)}),
			});

			const aspectTabletProps = ({
				label : __("Aspect Ratio", "still-be-combine-social-photos"),
				value : Math.log10(attributes.aspectTablet),
				min   : -Math.log10(4),
				max   : +Math.log10(4),
				step  : 0.001,
				initialPosition    : 0,
				allowReset         : true,
				resetFallbackValue : 0,
				withInputField     : false,
				showTooltip        : false,
				onChange : value => setAttributes({aspectTablet: (~~(Math.pow(10, value * 1) * 1000 + 0.5)) / 1000}),
			});

			const aspectTabletValueProps = ({
				label : "",
				value : attributes.aspectTablet,
				readOnly: true,
				style : {
					width      : "64px",
					margin     : "-8px 0 0 16px",
					textAlign  : "center",
					background : "transparent",
				},
			});

			const gapTabletProps = ({
				label : __("Gap", "still-be-combine-social-photos"),
				value : attributes.gapTablet,
				units : [
					{ value: 'em',  label: 'em',  default: 0.5 },
					{ value: 'rem', label: 'rem', default: 0.4 },
					{ value: 'px',  label: 'px',  default: 8   },
					{ value: 'vw',  label: 'vw',  default: 1   },
				],
				onChange : value => setAttributes({gapTablet: value}),
			});


			/**
			 * Grid Size
			 *  for SP
			 */

			const colsSpProps = ({
				label : __("Columns", "still-be-combine-social-photos"),
				value : attributes.columnsSp,
				min   : 1,
				max   : 9,
				initialPosition : 2,
				onChange : value => setAttributes({columnsSp: parseInt(value, 10)}),
			});

			const rowsSpProps = ({
				label : __("Rows", "still-be-combine-social-photos"),
				value : attributes.rowsSp,
				min   : 1,
				max   : 9,
				initialPosition : 3,
				onChange : value => setAttributes({rowsSp: parseInt(value, 10)}),
			});

			const aspectSpProps = ({
				label : __("Aspect Ratio", "still-be-combine-social-photos"),
				value : Math.log10(attributes.aspectSp),
				min   : -Math.log10(4),
				max   : +Math.log10(4),
				step  : 0.001,
				initialPosition    : 0,
				allowReset         : true,
				resetFallbackValue : 0,
				withInputField     : false,
				showTooltip        : false,
				onChange : value => setAttributes({aspectSp: (~~(Math.pow(10, value * 1) * 1000 + 0.5)) / 1000}),
			});

			const aspectSpValueProps = ({
				label : "",
				value : attributes.aspectSp,
				readOnly: true,
				style : {
					width      : "64px",
					margin     : "-8px 0 0 16px",
					textAlign  : "center",
					background : "transparent",
				},
			});

			const gapSpProps = ({
				label : __("Gap", "still-be-combine-social-photos"),
				value : attributes.gapSp,
				units : [
					{ value: 'em',  label: 'em',  default: 0.5 },
					{ value: 'rem', label: 'rem', default: 0.4 },
					{ value: 'px',  label: 'px',  default: 8   },
					{ value: 'vw',  label: 'vw',  default: 1   },
				],
				onChange : value => setAttributes({gapSp: value}),
			});


			/**
			 * Shadow Outline
			 * 
			 */

			const hasOutlineShadowProps = ({
				label    : __("Show Ouline Shadow", "still-be-combine-social-photos"),
				checked  : attributes.hasOutlineShadow,
				onChange : value => setAttributes({hasOutlineShadow: Boolean(value)}),
			});


			/**
			 * Link Target
			 * 
			 */

			const linkTargetProps = ({
				label    : __("Link Target", "still-be-combine-social-photos"),
				selected : attributes.linkTarget,
				options  : [
					{ label: __("Open in the Same Tab",   "still-be-combine-social-photos"), value: "_self"             },
					{ label: __("Open in a New Tab",      "still-be-combine-social-photos"), value: "_blank"            },
					{ label: __("Open in a Modal Window", "still-be-combine-social-photos"), value: "stillbe-modal-win" },
				],
				onChange : value => setAttributes({linkTarget: value}),
			});


			/**
			 * Post Information Position
			 * 
			 */

			const infoPositionProps = ({
				label    : __("Position", "still-be-combine-social-photos"),
				disabled : attributes.className === "is-style-simple",
				selected : attributes.infoPosition,
				options  : [
					{ label: __("Hover on Image", "still-be-combine-social-photos"), value: "inner" },
					{ label: __("Below an Image", "still-be-combine-social-photos"), value: "below" },
					{ label: __("Right of Image", "still-be-combine-social-photos"), value: "right" },
					{ label: __("Left of Image",  "still-be-combine-social-photos"), value: "left"  },
				],
				onChange : value => setAttributes({infoPosition: value}),
				help     : __("If there is not enough width available to place information beside the image, it will be placed below.",  "still-be-combine-social-photos"),
			});


			/**
			 * Caption
			 * 
			 */

			const captionProps = ({
				label    : __("Show Caption", "still-be-combine-social-photos"),
				disabled : attributes.className === "is-style-simple",
				checked  : attributes.isShowCaption,
				onChange : value => setAttributes({isShowCaption: Boolean(value)}),
			});

			const captionRowsProps = ({
				label : __("Caption Rows", "still-be-combine-social-photos"),
				value : attributes.captionRows,
				min   : 1,
				max   : 9,
				disabled: attributes.className === "is-style-simple" || !attributes.isShowCaption,
				initialPosition : 4,
				onChange : value => setAttributes({captionRows: parseInt(value, 10)}),
			});


			/**
			 * Header
			 * 
			 */

			const headerProps = ({
				label   : __("Show Header", "still-be-combine-social-photos"),
				disabled: attributes.className === "is-style-simple",
				checked : attributes.isShowHeader,
				onChange : value => setAttributes({isShowHeader: Boolean(value)}),
			});

			const headerPositionProps = ({
				label    : __("Position", "still-be-combine-social-photos"),
				disabled : attributes.className === "is-style-simple" || !attributes.isShowHeader,
				selected : attributes.headerPosition,
				options  : [
					{ label: __("Left",   "still-be-combine-social-photos"), value: "left"   },
					{ label: __("Center", "still-be-combine-social-photos"), value: "center" },
					{ label: __("Right",  "still-be-combine-social-photos"), value: "right"  },
				],
				onChange : value => setAttributes({headerPosition: value}),
			});


			/**
			 * Footer
			 * 
			 */

			const footerProps = ({
				label   : __("Show Footer", "still-be-combine-social-photos"),
				disabled: attributes.className === "is-style-simple",
				checked : attributes.isShowFooter,
				onChange : value => setAttributes({isShowFooter: Boolean(value)}),
			});

			const footerPositionProps = ({
				label    : __("Position", "still-be-combine-social-photos"),
				disabled : attributes.className === "is-style-simple" || !attributes.isShowFooter,
				selected : attributes.footerPosition,
				options  : [
					{ label: __("Left",   "still-be-combine-social-photos"), value: "left"   },
					{ label: __("Center", "still-be-combine-social-photos"), value: "center" },
					{ label: __("Right",  "still-be-combine-social-photos"), value: "right"  },
				],
				onChange : value => setAttributes({footerPosition: value}),
			});


			/**
			 * Follow & Followers
			 * 
			 */

			const followsProps = ({
				label   : __("Show Follows Count", "still-be-combine-social-photos"),
				disabled: apiType !== "Graph API",
				checked : attributes.isShowFollows,
				onChange : value => setAttributes({isShowFollows: Boolean(value)}),
			});

			const followersProps = ({
				label   : __("Show Followers Count", "still-be-combine-social-photos"),
				disabled: apiType !== "Graph API",
				checked : attributes.isShowFollowers,
				onChange : value => setAttributes({isShowFollowers: Boolean(value)}),
			});


			/**
			 * Impressions
			 * 
			 */

			const impressionsProps = ({
				label   : __("Show Impressions", "still-be-combine-social-photos"),
				disabled: apiType !== "Graph API",
				checked : attributes.isShowImpressions,
				onChange : value => setAttributes({isShowImpressions: Boolean(value)}),
			});


			/**
			 * Author
			 * 
			 */

			const authorProps = ({
				label   : __("Show Author", "still-be-combine-social-photos"),
				checked : attributes.isShowAuthor,
				onChange : value => setAttributes({isShowAuthor: Boolean(value)}),
			});


			/**
			 * Post Time
			 * 
			 */

			const timeProps = ({
				label   : __("Show Post Time", "still-be-combine-social-photos"),
				checked : attributes.isShowTime,
				onChange : value => setAttributes({isShowTime: Boolean(value)}),
			});


			/**
			 * Highlight
			 *  for PC
			 */

			const showHighlightProps = ({
				label   : __("Highlight a First Post", "still-be-combine-social-photos"),
				checked : attributes.isHighlight,
				onChange : value => setAttributes({isHighlight: Boolean(value)}),
			});

			const highlightSizeProps = ({
				label : __("Size", "still-be-combine-social-photos"),
				value : attributes.highlightSize,
				min   : 2,
				max   : Math.min(attributes.columns, attributes.rows),
				disabled: !attributes.isHighlight || attributes.columns < 2 || attributes.rows < 2,
				initialPosition : 2,
				onChange : value => setAttributes({highlightSize: parseInt(value, 10)}),
			});

			const highlightTopProps = ({
				label : __("Position Top", "still-be-combine-social-photos"),
				value : attributes.highlightTop,
				min   : 1,
				max   : attributes.rows - attributes.highlightSize + 1,
				disabled: !attributes.isHighlight || attributes.rows <= attributes.highlightSize,
				initialPosition : 1,
				onChange : value => setAttributes({highlightTop: parseInt(value, 10)}),
			});

			const highlightLeftProps = ({
				label : __("Position Left", "still-be-combine-social-photos"),
				value : attributes.highlightLeft,
				min   : 1,
				max   : attributes.columns - attributes.highlightSize + 1,
				disabled: !attributes.isHighlight || attributes.columns <= attributes.highlightSize,
				initialPosition : 1,
				onChange : value => setAttributes({highlightLeft: parseInt(value, 10)}),
			});


			/**
			 * Highlight
			 *  for Tablet
			 */

			const showHighlightTabletProps = ({
				label   : __("Highlight a First Post", "still-be-combine-social-photos"),
				checked : attributes.isHighlightTablet,
				onChange : value => setAttributes({isHighlightTablet: Boolean(value)}),
			});

			const highlightSizeTabletProps = ({
				label : __("Size", "still-be-combine-social-photos"),
				value : attributes.highlightSizeTablet,
				min   : 2,
				max   : Math.min(attributes.columnsTablet, attributes.rowsTablet),
				disabled: !attributes.isHighlightTablet || attributes.columnsTablet < 2 || attributes.rowsTablet < 2,
				initialPosition : 2,
				onChange : value => setAttributes({highlightSizeTablet: parseInt(value, 10)}),
			});

			const highlightTopTabletProps = ({
				label : __("Position Top", "still-be-combine-social-photos"),
				value : attributes.highlightTopTablet,
				min   : 1,
				max   : attributes.rowsTablet - attributes.highlightSizeTablet + 1,
				disabled: !attributes.isHighlightTablet || attributes.rowsTablet <= attributes.highlightSizeTablet,
				initialPosition : 1,
				onChange : value => setAttributes({highlightTopTablet: parseInt(value, 10)}),
			});

			const highlightLeftTabletProps = ({
				label : __("Position Left", "still-be-combine-social-photos"),
				value : attributes.highlightLeftTablet,
				min   : 1,
				max   : attributes.columnsTablet - attributes.highlightSizeTablet + 1,
				disabled: !attributes.isHighlightTablet || attributes.columnsTablet <= attributes.highlightSizeTablet,
				initialPosition : 1,
				onChange : value => setAttributes({highlightLeftTablet: parseInt(value, 10)}),
			});


			/**
			 * Highlight
			 *  for SP
			 */

			const showHighlightSpProps = ({
				label   : __("Highlight a First Post", "still-be-combine-social-photos"),
				checked : attributes.isHighlightSp,
				onChange : value => setAttributes({isHighlightSp: Boolean(value)}),
			});

			const highlightSizeSpProps = ({
				label : __("Size", "still-be-combine-social-photos"),
				value : attributes.highlightSizeSp,
				min   : 2,
				max   : Math.min(attributes.columnsSp, attributes.rowsSp),
				disabled: !attributes.isHighlightSp || attributes.columnsSp < 2 || attributes.rowsSp < 2,
				initialPosition : 2,
				onChange : value => setAttributes({highlightSizeSp: parseInt(value, 10)}),
			});

			const highlightTopSpProps = ({
				label : __("Position Top", "still-be-combine-social-photos"),
				value : attributes.highlightTopSp,
				min   : 1,
				max   : attributes.rowsSp - attributes.highlightSizeSp + 1,
				disabled: !attributes.isHighlightSp || attributes.rowsSp <= attributes.highlightSizeSp,
				initialPosition : 1,
				onChange : value => setAttributes({highlightTopSp: parseInt(value, 10)}),
			});

			const highlightLeftSpProps = ({
				label : __("Position Left", "still-be-combine-social-photos"),
				value : attributes.highlightLeftSp,
				min   : 1,
				max   : attributes.columnsSp - attributes.highlightSizeSp + 1,
				disabled: !attributes.isHighlightSp || attributes.columnsSp <= attributes.highlightSizeSp,
				initialPosition : 1,
				onChange : value => setAttributes({highlightLeftSp: parseInt(value, 10)}),
			});


			/**
			 * Hover Effect
			 * 
			 */

			const hoverEffectBlurProps = ({
				label    : __("Frosted Glass Effect", "still-be-combine-social-photos"),
			//	disabled : attributes.infoPosition !== "inner" || !(attributes.isShowAuthor || attributes.isShowTime || attributes.isShowCaption || attributes.isShowImpressions),
				checked  : attributes.hoverEffectBlur,
				onChange : value => setAttributes({hoverEffectBlur: Boolean(value)}),
			});

			const hoverEffectTiltProps = ({
				label    : __("Tilt Effect", "still-be-combine-social-photos"),
			//	disabled : attributes.infoPosition !== "inner" || !(attributes.isShowAuthor || attributes.isShowTime || attributes.isShowCaption || attributes.isShowImpressions),
				checked  : attributes.hoverEffectTilt,
				onChange : value => setAttributes({hoverEffectTilt: Boolean(value)}),
			});

			const hoverOverlayColorProps = ({
				label    : __("Overlay Color", "still-be-combine-social-photos"),
			//	disabled : attributes.infoPosition !== "inner" || !(attributes.isShowAuthor || attributes.isShowTime || attributes.isShowCaption || attributes.isShowImpressions),
				selected : attributes.hoverOverlayColor,
				options  : [
					{ label: __("Dark",  "still-be-combine-social-photos"), value: "dark"  },
					{ label: __("Light", "still-be-combine-social-photos"), value: "light" },
				],
				onChange : value => setAttributes({hoverOverlayColor: value}),
			});


			/**
			 * Wrapper Elements
			 * 
			 */

			const wrapperProps = useBlockProps({
				className: "sb-csp-simple-grid-wrapper editor",
			});

			const initialWrapperProps = useBlockProps({
				className: "sb-csp-simple-grid-init-wrapper",
			});

			const noteProps = ({
				className: "sb-csp-simple-grid-note",
			});

			const hiddenProps = useBlockProps({
				className: "display-none",
			});


			/**
			 * Output Editor Elements
			 * 
			 */

			return createElement(
				Fragment,
				useBlockProps(),
				// Side Panel
				createElement(
					InspectorControls,
					useBlockProps(),
					createElement(
						PanelBody,
						{ title: __("Instagram User", "still-be-combine-social-photos") },
						createElement(
							SelectControl,
							usersProps
						),
						createElement(
							Button,
							authUserProps
						)
					),
					apiType !== "Graph API" ? null : createElement(
						PanelBody,
						{ title: __("Advanced Getting Posts", "still-be-combine-social-photos") },
						createElement(
							RadioControl,
							gettingTypeProps
						),
						createElement(
							TextControl,
							businessDicoveryProps
						),
						createElement(
							TextControl,
							hashtagProps
						),
						createElement(
							Button,
							convertHashtagProps
						),
						createElement(
							"p",
							noteProps,
							__("Please click the 'Start Convertion' button when you set or change the hashtag as there is a limit to the number of times it can be converted.", "still-be-combine-social-photos")
						),
						createElement(
							TextControl,
							hashtagIdProps
						)
					),
					createElement(
						PanelBody,
						{ title: __("Video Option", "still-be-combine-social-photos") },
						createElement(
							RadioControl,
							displayingVideoProps
						),
						createElement(
							"p",
							noteProps,
							__("Video thumbnails cannot be used for posts other than your own due to Instagram specifications.", "still-be-combine-social-photos") +
							__("Therefore, you may consider changing the option if you want to display other user's posts or posts with hashtag.", "still-be-combine-social-photos")
						),
					),	
					createElement(
						PanelBody,
						{ title: __("Outline Gap", "still-be-combine-social-photos") },
						createElement(
							PanelRow,
							{},
							createElement(
								UnitControl,
								outlineGapProps
							)
						),
						createElement(
							PanelRow,
							{},
							createElement(
								UnitControl,
								outlineGapTabletProps
							)
						),
						createElement(
							PanelRow,
							{},
							createElement(
								UnitControl,
								outlineGapSpProps
							)
						)
					),
					createElement(
						PanelBody,
						{ title: __("Grid Size (PC)", "still-be-combine-social-photos") },
						createElement(
							PanelRow,
							{},
							createElement(
								RangeControl,
								colsProps
							)
						),
						createElement(
							PanelRow,
							{},
							createElement(
								RangeControl,
								rowsProps
							)
						),
						createElement(
							PanelRow,
							{},
							createElement(
								RangeControl,
								aspectProps
							),
							createElement(
								TextControl,
								aspectValueProps
							)
						),
						createElement(
							PanelRow,
							{},
							createElement(
								UnitControl,
								gapProps
							)
						)
					),
					createElement(
						PanelBody,
						{ title: __("Grid Size (Tablet)", "still-be-combine-social-photos"), initialOpen: false },
						createElement(
							PanelRow,
							{},
							createElement(
								RangeControl,
								colsTabletProps
							)
						),
						createElement(
							PanelRow,
							{},
							createElement(
								RangeControl,
								rowsTabletProps
							)
						),
						createElement(
							PanelRow,
							{},
							createElement(
								RangeControl,
								aspectTabletProps
							),
							createElement(
								TextControl,
								aspectTabletValueProps
							)
						),
						createElement(
							PanelRow,
							{},
							createElement(
								UnitControl,
								gapTabletProps
							)
						)
					),
					createElement(
						PanelBody,
						{ title: __("Grid Size (SP)", "still-be-combine-social-photos"), initialOpen: false },
						createElement(
							PanelRow,
							{},
							createElement(
								RangeControl,
								colsSpProps
							)
						),
						createElement(
							PanelRow,
							{},
							createElement(
								RangeControl,
								rowsSpProps
							)
						),
						createElement(
							PanelRow,
							{},
							createElement(
								RangeControl,
								aspectSpProps
							),
							createElement(
								TextControl,
								aspectSpValueProps
							)
						),
						createElement(
							PanelRow,
							{},
							createElement(
								UnitControl,
								gapSpProps
							)
						)
					),
					createElement(
						PanelBody,
						{ title: __("Highlight (PC)", "still-be-combine-social-photos") },
						createElement(
							PanelRow,
							{},
							createElement(
								ToggleControl,
								showHighlightProps
							)
						),
						createElement(
							PanelRow,
							{},
							createElement(
								RangeControl,
								highlightSizeProps
							)
						),
						createElement(
							PanelRow,
							{},
							createElement(
								RangeControl,
								highlightTopProps
							)
						),
						createElement(
							PanelRow,
							{},
							createElement(
								RangeControl,
								highlightLeftProps
							)
						)
					),
					createElement(
						PanelBody,
						{ title: __("Highlight (Tablet)", "still-be-combine-social-photos"), initialOpen: false },
						createElement(
							PanelRow,
							{},
							createElement(
								ToggleControl,
								showHighlightTabletProps
							)
						),
						createElement(
							PanelRow,
							{},
							createElement(
								RangeControl,
								highlightSizeTabletProps
							)
						),
						createElement(
							PanelRow,
							{},
							createElement(
								RangeControl,
								highlightTopTabletProps
							)
						),
						createElement(
							PanelRow,
							{},
							createElement(
								RangeControl,
								highlightLeftTabletProps
							)
						)
					),
					createElement(
						PanelBody,
						{ title: __("Highlight (SP)", "still-be-combine-social-photos"), initialOpen: false },
						createElement(
							PanelRow,
							{},
							createElement(
								ToggleControl,
								showHighlightSpProps
							)
						),
						createElement(
							PanelRow,
							{},
							createElement(
								RangeControl,
								highlightSizeSpProps
							)
						),
						createElement(
							PanelRow,
							{},
							createElement(
								RangeControl,
								highlightTopSpProps
							)
						),
						createElement(
							PanelRow,
							{},
							createElement(
								RangeControl,
								highlightLeftSpProps
							)
						)
					),
					createElement(
						PanelBody,
						{ title: __("Show Outline Shadow", "still-be-combine-social-photos") },
						createElement(
							PanelRow,
							{},
							createElement(
								ToggleControl,
								hasOutlineShadowProps
							)
						)
					),
					createElement(
						PanelBody,
						{ title: __("Instagram Post Where to Open", "still-be-combine-social-photos") },
						createElement(
							PanelRow,
							{},
							createElement(
								RadioControl,
								linkTargetProps
							)
						)
					),
					createElement(
						PanelBody,
						{ title: __("Post Information Position", "still-be-combine-social-photos") },
						createElement(
							PanelRow,
							{},
							createElement(
								RadioControl,
								infoPositionProps
							)
						)
					),
					createElement(
						PanelBody,
						{ title: __("Post Caption", "still-be-combine-social-photos") },
						attributes.className !== "is-style-simple" ? null : createElement(
							"p",
							noteProps,
							__("Not available when the block style is 'simple'.", "still-be-combine-social-photos")
						),
						createElement(
							PanelRow,
							{},
							createElement(
								ToggleControl,
								captionProps
							)
						),
						createElement(
							PanelRow,
							{},
							createElement(
								RangeControl,
								captionRowsProps
							)
						)
					),
					createElement(
						PanelBody,
						{ title: __("Author & Post Time", "still-be-combine-social-photos") },
						createElement(
							PanelRow,
							{},
							createElement(
								ToggleControl,
								authorProps
							)
						),
						createElement(
							PanelRow,
							{},
							createElement(
								ToggleControl,
								timeProps
							)
						)
					),
					createElement(
						PanelBody,
						{ title: __("Header", "still-be-combine-social-photos") },
						attributes.className !== "is-style-simple" ? null : createElement(
							"p",
							noteProps,
							__("Not available when the block style is 'simple'.", "still-be-combine-social-photos")
						),
						createElement(
							PanelRow,
							{},
							createElement(
								ToggleControl,
								headerProps
							)
						),
						createElement(
							PanelRow,
							{},
							createElement(
								RadioControl,
								headerPositionProps
							)
						)
					),
					createElement(
						PanelBody,
						{ title: __("Footer", "still-be-combine-social-photos") },
						attributes.className !== "is-style-simple" ? null : createElement(
							"p",
							noteProps,
							__("Not available when the block style is 'simple'.", "still-be-combine-social-photos")
						),
						createElement(
							PanelRow,
							{},
							createElement(
								ToggleControl,
								footerProps
							)
						),
						createElement(
							PanelRow,
							{},
							createElement(
								RadioControl,
								footerPositionProps
							)
						)
					),
					createElement(
						PanelBody,
						{ title: __("Show Additional Informations", "still-be-combine-social-photos") },
						apiType === "Graph API" ? null : createElement(
							"p",
							noteProps,
							__("Additional informations are available in only Instagram Graph API.", "still-be-combine-social-photos")
						),
						...([ followsProps, followersProps, impressionsProps ].map( $elemProps => {
							return createElement(
								PanelRow,
								{},
								createElement(
									ToggleControl,
									$elemProps
								)
							);
						}))
					),
					createElement(
						PanelBody,
						{ title: __("Hover Effect", "still-be-combine-social-photos") },
						createElement(
							PanelRow,
							{},
							createElement(
								ToggleControl,
								hoverEffectBlurProps
							)
						),
						createElement(
							PanelRow,
							{},
							createElement(
								ToggleControl,
								hoverEffectTiltProps
							)
						),
						createElement(
							PanelRow,
							{},
							createElement(
								RadioControl,
								hoverOverlayColorProps
							)
						)
					)
				),
				// Preview
				createElement(
					"div", wrapperProps,
					[
						...( attributes.id ? [
							createElement(
								wp.serverSideRender, {
									block      : "combine-social-photos/simple-grid",
									attributes : attributes,
								}
							)
						] : [
							createElement(
								"div",
								initialWrapperProps,
								[
									createElement(
										SelectControl,
										usersProps
									),
									createElement(
										Button,
										authUserProps
									)
								]
							)
						] ),
						!attributes.id || attributes.linkTarget !== "stillbe-modal-win" ? null : createElement(
							"div",
							{ className: "sb-csp-modal-cta" },
							[
								createElement( "b", {}, __("CTA in a Modal Window", "still-be-combine-social-photos") ),
								createElement( "p", {}, [
									__("You can set what you want to display in the modal window.", "still-be-combine-social-photos"),
									__("It is placed below the caption.", "still-be-combine-social-photos"),
								] ),
								createElement( InnerBlocks ),
							]
						)
					]
				)
			);

		},

		save: ( { attributes } ) => {

			if(attributes.linkTarget === "stillbe-modal-win"){
				return createElement("aside", { className: "sb-csp-modal-cta" }, createElement(InnerBlocks.Content));
			}

			return "<!-- combine-social-photos/simple-grid -->";

		},

	});


})(window.wp);
