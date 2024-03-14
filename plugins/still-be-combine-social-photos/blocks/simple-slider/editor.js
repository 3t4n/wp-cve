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


	registerBlockType( "combine-social-photos/simple-slider", {

		icon: icon,

		edit: ( { attributes, setAttributes, clientId } ) => {

			const REST_API_NAMESPACE = window.$stillbe?.rest.namespace || '';

			const [ authUser,  setAuthUser  ] = useState( {} );
			const [ users,     setUsers     ] = useState( [] );

			const [ apiType,   setApiType   ] = useState( null );
			const [ igName,    setIgName    ] = useState( "" );

			const [ convBtn,   setConvBtn   ] = useState( {
				label    : __("Start Convertion", "still-be-combine-social-photos"),
				disabled : apiType !== "Graph API",
			} );

			// Observe a Fetch API
			window.fetch = new Proxy(window.fetch, {
				apply(fetch, that, args){
					const result = fetch.apply(that, args);
					result.then($res => {
						if($res.url.indexOf("block-renderer/combine-social-photos/simple-slider") < 0){
							return;
						}
						if(!/complete|loaded/.test(document.readyState)){
							return;
						}
						setTimeout(window.__stillbe.func.setSimpleSliders, 50);
					});
					return result;
				}
			});

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
							setAttributes({isShowImpressions: user.api === "Graph API" && attributes.isShowImpressions});
							break;
						}
					}
				},
			});

			const authUserProps = ({
				variant : "secondary",
				text    : __("Link another account", "still-be-combine-social-photos"),
				onClick : window?.openAuthWindow?.bind(null, $id => setAttributes({id: parseInt($id, 10)})),
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
				help     : __("To get other user's posts, enter an Username in Instagram. If you do not find them, check if the user you specified is not a professional account (BUSINESS or MEDIA CREATOR) or if the user name has been changed.", "still-be-combine-social-photos"),
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
			 * Slide Size
			 * 
			 */

			const baseWidthProps = ({
				label : __("Base Width [px]", "still-be-combine-social-photos"),
				value : attributes.baseWidth,
				min   : 100,
				max   : 900,
				step  : 10,
				initialPosition : 300,
				onChange : value => setAttributes({baseWidth: parseInt(value, 10)}),
				help  : __("If images are not extended to fit the container size, this value will be the max width of the images.", "still-be-combine-social-photos"),
			});

			const minWidthProps = ({
				label : __("Min Width [px]", "still-be-combine-social-photos"),
				value : attributes.minWidth,
				min   : 100,
				max   : attributes.baseWidth,
				step  : 10,
				initialPosition : 200,
				onChange : value => setAttributes({minWidth: parseInt(value, 10)}),
				help  : __("This value is the min width limit for the images.", "still-be-combine-social-photos"),
			});

			const minColsProps = ({
				label : __("Min Columns", "still-be-combine-social-photos"),
				value : attributes.minColumns,
				min   : 1,
				max   : 5,
				initialPosition : 2,
				onChange : value => setAttributes({minCols: parseInt(value, 10)}),
				help  : __("This is the min columns to be kept on narrow screen, such as smartphones. It takes priority over the above min width.", "still-be-combine-social-photos"),
			});


			/**
			 * Number of Slides
			 * 
			 */

			const columnsProps = ({
				label : __("Columns", "still-be-combine-social-photos"),
				value : attributes.columns,
				min   : 1,
				max   : Math.min(50, ~~(100 / attributes.rows)),
				initialPosition : 10,
				onChange : value => setAttributes({columns: parseInt(value, 10)}),
			});

			const rowsProps = ({
				label : __("Rows", "still-be-combine-social-photos"),
				value : attributes.rows,
				min   : 1,
				max   : Math.min(5, ~~(100 / attributes.columns)),
				initialPosition : 1,
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


			/**
			 * Gap
			 * 
			 */

			const gapColumnsProps = ({
				label : __("Gap Columns", "still-be-combine-social-photos"),
				value : attributes.gapColumns || "2.0em",
				units : [
					{ value: 'em',  label: 'em',  default: 2.0 },
					{ value: 'rem', label: 'rem', default: 1.6 },
					{ value: 'px',  label: 'px',  default: 32  },
					{ value: '%',   label: '%',   default: 2   },
				],
				onChange : value => setAttributes({gapColumns: value}),
			});

			const gapRowsProps = ({
				label : __("Gap Rows", "still-be-combine-social-photos"),
				value : attributes.gapRows || "4.0em",
				units : [
					{ value: 'em',  label: 'em',  default: 4.0 },
					{ value: 'rem', label: 'rem', default: 3.2 },
					{ value: 'px',  label: 'px',  default: 64  },
					{ value: '%',   label: '%',   default: 4   },
				],
				onChange : value => setAttributes({gapRows: value}),
				help : /%/.test(attributes.gapRows) ? __("The % unit is unstable when window resized, so please test carefully it thoroughly before use.", "still-be-combine-social-photos") : "",
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
			 * Fit to Container Size
			 * 
			 */

			const fitToContainerProps = ({
				label    : __("Fit Container", "still-be-combine-social-photos"),
				checked  : attributes.isFitToContainer,
				onChange : value => setAttributes({isFitToContainer: Boolean(value)}),
				help     : __("Stretch the images width to fit the container size.", "still-be-combine-social-photos"),
			});


			/**
			 * Caption
			 * 
			 */

			const showCaptionProps = ({
				label   : __("Show Caption", "still-be-combine-social-photos"),
				checked : attributes.isShowCaption,
				onChange : value => setAttributes({isShowCaption: Boolean(value)}),
			});

			const captionRowsProps = ({
				label : __("Caption Rows", "still-be-combine-social-photos"),
				value : attributes.captionRows,
				min   : 1,
				max   : 9,
				disabled: !attributes.isShowCaption,
				initialPosition : 4,
				onChange : value => setAttributes({captionRows: parseInt(value, 10)}),
			});

			const captionPositionProps = ({
				label    : __("Caption Position", "still-be-combine-social-photos"),
				disabled : !attributes.isShowCaption,
				selected : attributes.captionPosition,
				options  : [
					{ label: __("Hover on Image", "still-be-combine-social-photos"), value: "in"  },
					{ label: __("Below an Image", "still-be-combine-social-photos"), value: "out" },
				],
				onChange : value => setAttributes({captionPosition: value}),
			});


			/**
			 * Impressions
			 * 
			 */

			const showImpressionsProps = ({
				label   : __("Show Impressions", "still-be-combine-social-photos"),
				disabled: attributes.className === "is-style-simple" || apiType !== "Graph API",
				checked : apiType === "Graph API" && attributes.isShowImpressions,
				onChange : value => setAttributes({isShowImpressions: apiType === "Graph API" && Boolean(value)}),
			});

			const impressionsPositionProps = ({
				label    : __("Impression Position", "still-be-combine-social-photos"),
				disabled : apiType !== "Graph API" || !attributes.isShowImpressions,
				selected : attributes.impressionsPosition,
				options  : [
					{ label: __("Hover on Image", "still-be-combine-social-photos"), value: "in"  },
					{ label: __("Below an Image", "still-be-combine-social-photos"), value: "out" },
				],
				onChange : value => setAttributes({impressionsPosition: value}),
			});


			/**
			 * Author
			 * 
			 */

			const showAuthorProps = ({
				label    : __("Show Author", "still-be-combine-social-photos"),
				checked : attributes.isShowAuthor,
				onChange : value => setAttributes({isShowAuthor: Boolean(value)}),
			});

			const authorPositionProps = ({
				label    : __("Author Position", "still-be-combine-social-photos"),
				disabled : !attributes.isShowAuthor,
				selected : attributes.authorPosition,
				options  : [
					{ label: __("Hover on Image", "still-be-combine-social-photos"), value: "in"  },
					{ label: __("Below an Image", "still-be-combine-social-photos"), value: "out" },
				],
				onChange : value => setAttributes({authorPosition: value}),
			});


			/**
			 * Post Time
			 * 
			 */

			const showTimeProps = ({
				label    : __("Post Time", "still-be-combine-social-photos"),
				checked : attributes.isShowTime,
				onChange : value => setAttributes({isShowTime: Boolean(value)}),
			});

			const timePositionProps = ({
				label    : __("Post Time Position", "still-be-combine-social-photos"),
				disabled : !attributes.isShowTime,
				selected : attributes.timePosition,
				options  : [
					{ label: __("Hover on Image", "still-be-combine-social-photos"), value: "in"  },
					{ label: __("Below an Image", "still-be-combine-social-photos"), value: "out" },
				],
				onChange : value => setAttributes({timePosition: value}),
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
			 * Hover Effect
			 * 
			 */

			const hoverEffectBlurProps = ({
				label    : __("Frosted Glass Effect", "still-be-combine-social-photos"),
				disabled : !(attributes.isShowAuthor || attributes.isShowTime || attributes.isShowCaption || attributes.isShowImpressions),
				checked  : attributes.hoverEffectBlur,
				onChange : value => setAttributes({hoverEffectBlur: Boolean(value)}),
			});

			const hoverEffectTiltProps = ({
				label    : __("Tilt Effect", "still-be-combine-social-photos"),
				disabled : !(attributes.isShowAuthor || attributes.isShowTime || attributes.isShowCaption || attributes.isShowImpressions),
				checked  : attributes.hoverEffectTilt,
				onChange : value => setAttributes({hoverEffectTilt: Boolean(value)}),
			});


			/**
			 * Scrolling
			 * 
			 */

			const scrollDurationTimeProps = ({
				label : __("Scroll Duration Time [ms]", "still-be-combine-social-photos"),
				value : attributes.scrollDurationTime,
				min   : 50,
				max   : 2000,
				step  : 50,
				initialPosition : 300,
				onChange : value => setAttributes({scrollDurationTime: parseInt(value, 10)}),
				help  : __("Sets the duration time required for horizontal scrolling. Longer setting scrolls slowly.", "still-be-combine-social-photos"),
			});

			const scrollEasingFunctionProps = ({
				label    : __("Scroll Easing Function", "still-be-combine-social-photos"),
				selected : attributes.scrollEasingFunction,
				options  : [
					{ label: __("Linear",       "still-be-combine-social-photos"), value: "1"  },
					{ label: __("InOutSine",    "still-be-combine-social-photos"), value: "2"  },
					{ label: __("InOutQuad",    "still-be-combine-social-photos"), value: "3"  },
					{ label: __("InOutCubic",   "still-be-combine-social-photos"), value: "5"  },
					{ label: __("OutBounce",    "still-be-combine-social-photos"), value: "4"  },
					{ label: __("Cubic-bezier", "still-be-combine-social-photos"), value: "99" },
				],
				onChange : value => setAttributes({scrollEasingFunction: value}),
			});

			const cubicBezierX1Props = ({
				label : "Cubic-bezier; X1",
				value : attributes.cubicBezierX1,
				min   : 0,
				max   : 1,
				step  : 0.01,
				onChange : value => setAttributes({cubicBezierX1: value}),
			});
			const cubicBezierY1Props = ({
				label : "Cubic-bezier; Y1",
				value : attributes.cubicBezierY1,
				min   : -2,
				max   : 3,
				step  : 0.01,
				onChange : value => setAttributes({cubicBezierY1: value}),
			});
			const cubicBezierX2Props = ({
				label : "Cubic-bezier; X2",
				value : attributes.cubicBezierX2,
				min   : 0,
				max   : 1,
				step  : 0.01,
				onChange : value => setAttributes({cubicBezierX2: value}),
			});
			const cubicBezierY2Props = ({
				label : "Cubic-bezier; Y2",
				value : attributes.cubicBezierY2,
				min   : -2,
				max   : 2,
				step  : 0.01,
				onChange : value => setAttributes({cubicBezierY2: value}),
			});


			/**
			 * Exclude Navagation Buttons
			 * 
			 */

			const excludeNavigationsProps = ({
				label    : __("Exclude Navigation Buttons", "still-be-combine-social-photos"),
				checked  : attributes.excludeNavigations,
				onChange : value => setAttributes({excludeNavigations: Boolean(value)}),
			});


			/**
			 * Wrapper Elements
			 * 
			 */

			const wrapperProps = useBlockProps({
				className: "sb-csp-simple-slider-wrapper editor",
			});

			const initialWrapperProps = useBlockProps({
				className: "sb-csp-simple-slider-init-wrapper",
			});

			const noteProps = ({
				className: "sb-csp-simple-slider-note",
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
						{ title: __("Layout", "still-be-combine-social-photos") },
						createElement(
							PanelRow,
							{},
							createElement(
								RangeControl,
								baseWidthProps
							)
						),
						createElement(
							PanelRow,
							{},
							createElement(
								RangeControl,
								minWidthProps
							)
						),
						createElement(
							PanelRow,
							{},
							createElement(
								RangeControl,
								minColsProps
							)
						),
						createElement(
							PanelRow,
							{},
							createElement(
								RangeControl,
								columnsProps
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
								gapColumnsProps
							)
						),
						createElement(
							PanelRow,
							{},
							createElement(
								UnitControl,
								gapRowsProps
							)
						)
						,
						createElement(
							PanelRow,
							{},
							createElement(
								ToggleControl,
								fitToContainerProps
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
						{ title: __("Caption", "still-be-combine-social-photos") },
						createElement(
							PanelRow,
							{},
							createElement(
								ToggleControl,
								showCaptionProps
							)
						),
						createElement(
							PanelRow,
							{},
							createElement(
								RangeControl,
								captionRowsProps
							)
						),
						createElement(
							PanelRow,
							{},
							createElement(
								RadioControl,
								captionPositionProps
							)
						)
					),
					createElement(
						PanelBody,
						{ title: __("Author", "still-be-combine-social-photos") },
						createElement(
							PanelRow,
							{},
							createElement(
								ToggleControl,
								showAuthorProps
							)
						),
						createElement(
							PanelRow,
							{},
							createElement(
								RadioControl,
								authorPositionProps
							)
						)
					),
					createElement(
						PanelBody,
						{ title: __("Post Time", "still-be-combine-social-photos") },
						createElement(
							PanelRow,
							{},
							createElement(
								ToggleControl,
								showTimeProps
							)
						),
						createElement(
							PanelRow,
							{},
							createElement(
								RadioControl,
								timePositionProps
							)
						)
					),
					createElement(
						PanelBody,
						{ title: __("Impressions", "still-be-combine-social-photos") },
						apiType === "Graph API" ? null : (
							createElement(
								"p",
								noteProps,
								__("Additional informations are available in only Instagram Graph API.", "still-be-combine-social-photos")
							)
						),
						createElement(
							PanelRow,
							{},
							createElement(
								ToggleControl,
								showImpressionsProps
							)
						),
						createElement(
							PanelRow,
							{},
							createElement(
								RadioControl,
								impressionsPositionProps
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
						)
					),
					createElement(
						PanelBody,
						{ title: __("Scrolling", "still-be-combine-social-photos") },
						createElement(
							PanelRow,
							{},
							createElement(
								RangeControl,
								scrollDurationTimeProps
							)
						),
						createElement(
							PanelRow,
							{},
							createElement(
								RadioControl,
								scrollEasingFunctionProps
							)
						),
						attributes.scrollEasingFunction != 99 ? null : createElement(
							PanelRow,
							{},
							createElement(
								RangeControl,
								cubicBezierX1Props
							)
						),
						attributes.scrollEasingFunction != 99 ? null : createElement(
							PanelRow,
							{},
							createElement(
								RangeControl,
								cubicBezierY1Props
							)
						),
						attributes.scrollEasingFunction != 99 ? null : createElement(
							PanelRow,
							{},
							createElement(
								RangeControl,
								cubicBezierX2Props
							)
						),
						attributes.scrollEasingFunction != 99 ? null : createElement(
							PanelRow,
							{},
							createElement(
								RangeControl,
								cubicBezierY2Props
							)
						)
					),
					createElement(
						PanelBody,
						{ title: __("Exclude Navigation Buttons", "still-be-combine-social-photos") },
						createElement(
							"p",
							noteProps,
							__("Place the \"Next\" and \"Previous\" buttons outside of the element so that they do not cover the post. It may not be displayed if it extends beyond the container area. If they do not appear, please check your theme settings.", "still-be-combine-social-photos")
						),
						createElement(
							PanelRow,
							{},
							createElement(
								ToggleControl,
								excludeNavigationsProps
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
									block      : "combine-social-photos/simple-slider",
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

			return "<!-- combine-social-photos/simple-slider -->";

		},

	});


	/**
	 *  Set Simple Sliders
	 * 
	 *   @param  Selector of the Slider Containers
	 *   @return void
	 */
	const setSimpleSliders = function($containerSelector){

		const Slider = window.__stillbe?.class?.Slider;

		if(!Slider){
			console.error("Class 'Slider' is not found....");
			return;
		}

		const containerClass = Array.from($containerSelector && document.querySelectorAll($containerSelector) || []);

		const setContainers = containerClass.map($t => {
			for(const slider of sliders){
				if($t === slider.target){
					return null;
				}
			}
			return {
				target   : $t,
				instance : new Slider($t, "sb-csp-simple-slider-root"),
			};
		}).filter(Boolean);

		sliders.push(...setContainers);

	};


	const sliders = [];


	// Global for Editor
	window.__stillbe = window.__stillbe || {};
	window.__stillbe.func = window.__stillbe.func || {};
	window.__stillbe.func.setSimpleSliders = setSimpleSliders.bind(null, ".sb-csp-ig-simple-slider .ig-feed-list");

	// Sliders
	window.__stillbe.vars         = window.__stillbe.vars || {};
	window.__stillbe.vars.sliders = sliders;


	if(/complete|loaded|interactive/.test(document.readyState)){
		// readyState = interactive is just before 'DOMContentLoaded' event
		window.__stillbe.func.setSimpleSliders();
	} else{
		window.addEventListener("DOMContentLoaded", window.__stillbe.func.setSimpleSliders, false);
	}


})(window.wp);
