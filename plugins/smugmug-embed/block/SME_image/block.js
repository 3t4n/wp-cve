(function ( blocks, editor, components, i18n, element) {
  var __ = i18n.__
  var el = element.createElement
  var registerBlockType = blocks.registerBlockType
  var RichText = editor.RichText
  var BlockControls = editor.BlockControls
  var MediaUpload = editor.MediaUpload
  var InspectorControls = editor.InspectorControls
  var ImageSizeControl =editor.__experimentalImageSizeControl
  var PanelBody = components.PanelBody
  var ResizableBox = components.ResizableBox
  var TextControl = components.TextControl
  var TextareaControl = components.TextareaControl
  var VideoEdit = components.VideoEdit
  var Placeholder = components.Placeholder
  var Button = components.Button
  var Image = blocks.Image
  var BlockAlignmentToolbar =  editor.BlockAlignmentToolbar
  var IconButton = components.IconButton
  var SelectControl = components.SelectControl
  var DropDownMenu = components.DropdownMenu
  var DropDown = components.Dropdown
  var MenuGroup = components.MenuGroup
  var MenuItemsChoice  = components.MenuItemsChoice 
  var MenuItem  = components.MenuItem
  var ToggleControl = components.ToggleControl
  registerBlockType('smugmugembed/block', { // The name of our block. Must be a string with prefix. Example: my-plugin/my-custom-block.
    title: __('Embed image from SmugMug'), // The title of our block.
    description: __('Add an image directly from SmugMug'), // The description of our block.
    icon: 'format-image', // Dashicon icon for our block. Custom icons can be added using inline SVGs.
    category: 'common', // The category of the block.
    supports: {
      //align: ['left','center','right'],
      //alignWide: true,
multiple:false,
      className: true,
	  customClassName: true,
	  lightBlockWrapper: false,
	  anchor: true,
  
    },
  getEditWrapperProps: function getEditWrapperProps(attributes) {
    var align = attributes.alignment,
        width = attributes.width;

    if ('left' === align || 'center' === align || 'right' === align || 'wide' === align || 'full' === align) {
      return {
        'data-align': align,
        'data-resized': !!width
      };
    }
  },    attributes: { // Necessary for saving block content.
      loadWidth: {
		  type:'number',
	  },
      alignment: {
		  type:'string',
		  default:'center'
	  },	  
	  loadHeight: {
		  type:'number',
	  },
	  width: {
		  type:'number',
	  },
	  height: {
		  type:'number',
	  },
	 
      mediaID: {
        type: 'number'
      },
      mediaURL: {
        type: 'string',
      },
	  captionFocused: {
		  type:'boolean'
	  },
	  caption: {
		  type:'string'
	  },
	  alt: {
		  type:'string'
	  },
	  title: {
		  type:'string'
	  },
	  isVideo: {
		  type:'boolean'
	  },
	  embedVideoUrls: {
		  type:'array'
	  },
	  videoURL: {
		  type:'string'
	  },
	  loop: {
		  type:'boolean'
	  },
	  muted: {
		  type:'boolean'
	  },
	  controls: {
		  type:'boolean'
	  },
	  playsInline: {
		  type:'boolean'
	  },
	  preload: {
		  type:'string'
	  },
	  autoplay: {
		  type:'boolean'
	  },
	  openNewWindow: {
		  type:'boolean'
	  },
	  linkTarget: {
		  type:'string',
		  default:'none'
	  },
	  linkTargets: {
		  type:'array'
	  },
	  defaultnewwindow: {
		  type:'boolean'
	  }
    },

	edit: function (props) {

		var DEFAULT_SIZE_SLUG = 'large';
		var attributes = props.attributes
		var alignment = props.attributes.alignment
		var url = attributes.url,
			muted=attributes.muted ||false,
			controls = attributes.controls || false,
			playsInline = attributes.playsInline || false,
			preload = attributes.preload || 'none',
			alt = attributes.alt,
			caption = attributes.caption,
			id = attributes.id,
			href = attributes.href,
			rel = attributes.rel,
			linkClass = attributes.linkClass,
			linkDestination = attributes.linkDestination,
			width = attributes.width,
			height = attributes.height,
			linkTarget = attributes.linkTarget,
			linkTargets = attributes.linkTargets,
			sizeSlug = attributes.sizeSlug,
			imageWidth=attributes.imageWidth || attributes.width,
			imageHeight=attributes.imageHeight || attributes.height,
			is_focused=props.isSelected,
			loop = attributes.loop || false,
			autoplay = attributes.autoplay ||false;
			openNewWindow=attributes.openNewWindow ;
			defaultnewwindow=attributes.defaultnewwindow ;
		var isLargeViewport = wp.data.select( 'core/viewport' ).isViewportMatch('medium');
		
		var onSelectImage = function (mediaurl) {
		return props.setAttributes({
			mediaURL: mediaurl,
			mediaID: 0
			})
		}


      function onChangeURL (newURL) {
        props.setAttributes({ mediaURL: newURL })
      }		

	  function onChangeImageSize(sizes) {
		  if (('width' in sizes && isNaN(sizes.width)) || ('height' in sizes && isNaN(sizes.height))) { 
			props.setAttributes({width:imageWidth,height:imageHeight});
			return;
		  }
		  //decide which changed, width or height
		  if (!('height'in sizes) )props.setAttributes({width:sizes.width,height:parseInt(sizes.width*(imageHeight/imageWidth),10)});
		  else  props.setAttributes({width:parseInt(sizes.height*(imageWidth/imageHeight),10),height:sizes.height});

	  }

	  function handle_launch_seachwindow() {
		  SME_launchSearchWindow(props.clientId);
	  }
	  function setLinkTarget(target) {
		  props.setAttributes({linkTarget:target});
	  }
	  function toggleOpenInNewWindow() {
		  props.setAttributes({openNewWindow: !openNewWindow});
	  }
	  function onImageClick() {
		  if (props.attributes.captionFocused)
			props.setAttributes({captionFocused: FALSE});
	  }
		function _onResizeStop() {
		  return toggleSelection(true);
		}
		function onChangeAlignment(newAlignment) {
			var extraUpdatedAttributes = ['wide', 'full'].indexOf(newAlignment) !== -1 ? {
				width: undefined,
				height: undefined
			} : {};
			width=attributes.width;
			props.setAttributes(Object.assign({alignment: newAlignment}, extraUpdatedAttributes));
			props.setAttributes({alignment:newAlignment});

		}

	  var is_resized = attributes.width || attributes.height ? "is-resized"  :"";
      var classes = is_focused ?'wp-block-image is-focused '.concat(is_resized) :'wp-block-image is-resized';
	   classes = attributes.isVideo ? 'wp-block-video '.concat(classes): classes;
	  
      var isResizable = ['wide', 'full'].indexOf(alignment) === -1 && isLargeViewport;
	  var linkDestinationOptions = [{
		value: 'www.yahoo.com',
		label: 'URL'
	  }];
	  var thisOpenNewWindow = typeof openNewWindow !== 'undefined' ? openNewWindow: (defaultnewwindow==="true");
	  var MyLinkTargetsMenu = function() {
		  return [
		  el(MenuGroup,{label:'Target',key:'target_menu'},
			el(MenuItemsChoice,{ 
				value:linkTarget,
				choices: [
					{
						label:'No Link',
						value:'none',
					},
					{
						label:'Image',
						value:'image',
					},
					{
						label:'SmugMug Lightbox',
						value:'smugmug_lightbox',
					},
					{
						label:'SmugMug Gallery',
						value:'smugmug_gallery',
					},
					{
						label:'SmugMug Cart',
						value:'smugmug_cart',
					}					
				],
				onSelect:setLinkTarget
			}
			),
	 	    el(MenuGroup,{label:'Options',key:'options_menu'},
				el(MenuItem,{
					icon:  thisOpenNewWindow ? 'yes' : '',
					isSelected:thisOpenNewWindow,
					onClick:toggleOpenInNewWindow,
					info:'Open in New Window'
				}),
			),
			),


			];
				
	  }
      return [
        el(BlockControls, { key: 'controls' }, // Display controls when the block is clicked on.
          el('div', { className: 'components-toolbar' },
		   el(BlockAlignmentToolbar,{
			   value:attributes.alignment,
			   onChange:onChangeAlignment,
			   wideControlsEnabled:true,
		   },),
		   			
			el(IconButton,{
				icon: 'search',
                title: 'Search SmugMug',
                onClick:handle_launch_seachwindow,
                
			},),
				!attributes.isVideo && attributes.mediaURL && el(DropDownMenu,{
						icon:'admin-links',
						label:'Links',
						children:MyLinkTargetsMenu,
						},
				),
          ),
          // Display alignment toolbar within block controls.
			
        ),
        el(InspectorControls, { key: 'inspector' }, // Display the block options in the inspector panel.
          el(PanelBody, {
            title: __('SmugMug Embed Settings'),
            className: 'SME_Image_Attrs',
            initialOpen: true
          },
			!attributes.isVideo && el(ImageSizeControl,{
					isResizable:isResizable,
					imageWidth:imageWidth,
					imageHeight:imageHeight,
					width:width,
					height:height,
					onChange:onChangeImageSize,
			},),
			attributes.isVideo && attributes.embedVideoUrls && el(ToggleControl,{
				label:__('Autoplay'),
				checked: autoplay ,
				help:function (value) { value ? __('Note: Autoplaying videos may cause usability issues for some visitors.') : null},
				onChange: function (value) {	props.setAttributes({autoplay:value});},
			},
			),		
			attributes.isVideo && attributes.embedVideoUrls && el(ToggleControl,{
				label:__('Loop'),
				checked: loop ,
				onChange: function (value) {	props.setAttributes({loop:value});},
			},
			),
				
			attributes.isVideo && attributes.embedVideoUrls && el(ToggleControl,{
				label:__('Muted'),
				checked: muted ,
				onChange: function (value) {	props.setAttributes({muted:value});},
			},
			),
			attributes.isVideo && attributes.embedVideoUrls && el(ToggleControl,{
				label:__('Playback Controls'),
				checked: controls ,
				onChange: function (value) {	props.setAttributes({controls:value});},
			},
			),
			attributes.isVideo && attributes.embedVideoUrls && el(ToggleControl,{
				label:__('Play inline'),
				checked: playsInline ,
				onChange: function (value) {	props.setAttributes({playsInline:value});},
			},
			),
			attributes.isVideo && attributes.embedVideoUrls && el(SelectControl,{
				label:__('Preload'),
				value: preload ,
				onChange: function (value) {props.setAttributes({preload: value})},
				options: [{value: 'auto',label:__('Auto')},{value: 'metadata',label:__('Metadata')}, {value:'none',label:__('None')}],
				},
			),	
			attributes.isVideo && attributes.embedVideoUrls && el(SelectControl,{
				label:__('Video Size'),
				value: attributes.videoURL,
				options: attributes.embedVideoUrls,
				onChange:function (newUrl) {
					props.setAttributes({videoURL:newUrl});
				}
			},),			
          el('p', {}, __('Default values for the following fields are defined in the settings page.')),
		  		
          el(TextareaControl, {
            label: __('Title'),
            value: attributes.title,
            onChange: function (newTitle) {
              props.setAttributes({ title: newTitle })
            }
          }),
          el(TextareaControl, {
            label: __('Alt Text'),
            value: attributes.alt,
            onChange: function (newAlt) {
              props.setAttributes({ alt: newAlt })
            }
          }),
          )
        ),
			el('div',{
			key:'feedback',
				className:'smugmugembed_feedback',
			}),
			attributes.mediaURL && 
			el('figure',{
				key:'content',
				className:classes,
			},
				isResizable && !attributes.isVideo && el('div',{},
					el(ResizableBox, {
				size:{width,height},
				minHeight:"50",
				minWidth:"50",
				lockAspectRatio:true,
				showHandle:props.isSelected,
				enable:{
					top: false,
					right: true,
					bottom: true,
					left: true,
					topRight: false,
					bottomRight: false,
					bottomLeft: false,
					topLeft: false
					},
					onResizeStop:function ( event, direction, elt, delta ) {
						props.setAttributes({
							height: parseInt( height + delta.height, 10 ),
							width: parseInt( width + delta.width, 10 ),
							});
					},
				} , 				
				  el('img', {
							src: attributes.mediaURL,
							alt: 'alt',
							onLoad: function(img) {props.setAttributes({width:attributes.width || img.currentTarget.naturalWidth,height:attributes.height || img.currentTarget.naturalHeight,imageWidth:img.currentTarget.naturalWidth,imageHeight:img.currentTarget.naturalHeight});},

							}
						),
					),
					el(RichText, {
						tagName: "figcaption",
						keepPlaceholderOnFocus: true,
						placeholder: __('Write caption…'),
						value: attributes.caption,
						//unstableOnFocus: this.onFocusCaption,
						onChange: function onChange(value) {
							props.setAttributes({caption: value});
						},
				        inlineToolbar: true,
						allowedFormats:['core/bold','core/italic'],
					}
					),
				), !isResizable && el('img', {
							src: attributes.mediaURL,
							alt: 'alt',
							onLoad: function(img) {props.setAttributes({width:attributes.width || img.currentTarget.naturalWidth,height:attributes.height || img.currentTarget.naturalHeight,imageWidth:img.currentTarget.naturalWidth,imageHeight:img.currentTarget.naturalHeight});},

							}
						),
					 !isResizable && el(RichText, {
						tagName: "figcaption",
						keepPlaceholderOnFocus: true,
						placeholder: __('Write caption…'),
						value: attributes.caption,
						//unstableOnFocus: this.onFocusCaption,
						onChange: function onChange(value) {
							props.setAttributes({caption: value});
						},
				        inlineToolbar: true,
						allowedFormats:['core/bold','core/italic'],
					}
					), attributes.isVideo && attributes.videoURL && el("video", {
						    className: "components-disabled",
							autoPlay: false,
							muted: true,
							loop: true,
							controls:false,
							src: attributes.videoURL,
					}), attributes.isVideo && el(RichText, {
						tagName: "figcaption",
						keepPlaceholderOnFocus: true,
						placeholder: __('Write caption…'),
						value: attributes.caption,
						//unstableOnFocus: this.onFocusCaption,
						onChange: function onChange(value) {
							props.setAttributes({caption: value});
						},
				        inlineToolbar: true,
						allowedFormats:['core/bold','core/italic'],
					}
					),
			   ),
			
		el('div', { key:'image',className: props.className },
			el('div', {
				className: 'SME_ImageChooser_block',
				id:"SME_ImageChooser_block",
			}, el('div',{
				className:'SME_ImageChooser_block_button'
			},!attributes.mediaURL && el(Placeholder,{
				icon:'search',
				label:'Search SmugMug',
				instructions:'Select an image from your SmugMug albums.',
				
			},
			  el(Button, {
				  
					clientid:props.clientId,
				    onClick:handle_launch_seachwindow,
					className:'is-secondary',
					label:'Select Image',
			  },"Choose Image"),
			  ),
			  ),
			  ),
          )
      ]
    },

    save: function (props) {
		
      var attributes = props.attributes
      var alignment = props.attributes.alignment
      var imageClass = "align".concat(alignment," is-resized");
	  var className = ' wp-block-image';
      var isVideo = attributes.isVideo;
      var videoURL = attributes.videoURL;
	  var linkTarget = attributes.linkTarget;
	  if (typeof linkTarget === 'undefined') linkTarget = "none";
	  var linkTargetList = [];
      if (attributes.linkTargets) linkTargetList= attributes.linkTargets;
	  var openNewWindow = attributes.openNewWindow;
	  //define link target
	  var includeLink = false;
	  if (linkTarget!="none" && typeof linkTarget !== 'undefined') includeLink = true;
	  
	  var linkTargetUrl = "";
      if (linkTargetList.length >0 && linkTarget!='none') linkTargetUrl=linkTargetList.find(item=>item.name==linkTarget).url;
	  function getSaveUrl(mode) {
		if (!attributes.mediaURL) return;
		var defaultWideSize = passedData['defaultwidesize'];
	    var vw = defaultWideSize;//Math.max(document.documentElement.clientWidth, window.innerWidth || 0);
		if (mode=="sized") {if (attributes.width &&attributes.width >attributes.height ) vw = attributes.width;
			else if (attributes.height && attributes.height> attributes.width)vw = attributes.height;
		}
		var mediaurl=attributes.mediaURL;
		var extension = mediaurl.substring(mediaurl.lastIndexOf("."));
		var imageName = mediaurl.substring(mediaurl.lastIndexOf("/"),mediaurl.length - extension.length-1);
		var imageSize = vw+"x"+vw;
		imageName = imageName.concat(imageSize+extension);
		var newUrl = mediaurl.substring(0,mediaurl.lastIndexOf("/",mediaurl.lastIndexOf("/")-3)).concat("/"+imageSize+imageName);
		return newUrl;
	  }
	 
		  
		  
	  if (alignment==="left" || alignment ==="right" || alignment==="center") {
	  	  if (isVideo){
			   return (
			    attributes.videoURL && 
				el('figure', { 
					className: imageClass 
				},
					el('video', {
						autoPlay: attributes.autoplay,
						controls: attributes.controls,
						loop: attributes.loop,
						muted: attributes.muted,
						//poster: poster,
						preload: attributes.preload ? attributes.preload : undefined,
						src: videoURL,
						playsInline: attributes.playsInline
					}
					)
				)
				);
		  }		 

		  var imageSrc= getSaveUrl("sized");
		  if (includeLink) {
		  return (
			el('div', {className:className},
			  attributes.mediaURL && el('figure', { className: imageClass },
			   el('a',{href:linkTargetUrl, target: openNewWindow ? '_blank' : '',rel:"noopener noreferrer"},
				  el('img', { src: imageSrc, alt: attributes.alt, width: attributes.width, height: attributes.height, title: attributes.title, data_id:'sme_managed' }),),
				  attributes.caption && el('figcaption', {},attributes.caption),
				)
			  )
			)
		  } else {
		  return (
			el('div', {className:className},
			  attributes.mediaURL && el('figure', { className: imageClass },
			  	  el('img', { src: imageSrc, alt: attributes.alt, width: attributes.width, height: attributes.height, title: attributes.title, data_id:'sme_managed' }),
				  attributes.caption && el('figcaption', {},attributes.caption),
				)
			  )
			)
		  }
	  } else {
	  	  if (isVideo){
			    return (
			    attributes.videoURL && 
				el('figure', { 
					className: imageClass 
				},
					el('video', {
						autoPlay: attributes.autoplay,
						controls: attributes.controls,
						loop: attributes.loop,
						muted: attributes.muted,
						preload: attributes.preload ? attributes.preload : undefined,
						src: videoURL,
						playsInline: attributes.playsInline
					}
					)
				)
				);
		  }		  
	  	  var imageSrc= getSaveUrl("full");
		  imageClass=imageClass.concat(className);
  		  if (includeLink) {
		  return (
			  attributes.mediaURL && el('figure', { className: imageClass },
  			    el('a',{href:linkTargetUrl, target: openNewWindow ? '_blank' : '',rel:"noopener noreferrer"},
				  el('img', { src: imageSrc, alt: attributes.alt,title:attributes.title, data_id:'sme_managed'  }),),
				  attributes.caption && el('figcaption', {},attributes.caption),
				)
			  )
		  } else {
		  return (
			  attributes.mediaURL && el('figure', { className: imageClass },
				  el('img', { src: imageSrc, alt: attributes.alt,title:attributes.title, data_id:'sme_managed'  }),
				  attributes.caption && el('figcaption', {},attributes.caption),
				)
			  )
		  }			  
			  
	  }
    }
  })
})(
  window.wp.blocks,
  window.wp.blockEditor,
  window.wp.components,
  window.wp.i18n,
  window.wp.element
)
function SME_LoadAlbum(el) {
	var nodeId = jQuery(el).attr('data-key');
	var imageChooserEl=jQuery(el).parent(".SME_ImageChooser_block_Album_Holder");
	jQuery.ajax({
		type:"POST",
		url: "../wp-admin/admin-ajax.php",
		data: {
			action: 'SME_LoadSelectedImagesFromGallery',
			nonce: SME_Ajax.nonce,
			mode:'frontend',
			galleryNode:nodeId
			},
		success:function(data){
			imageChooserEl.html(data.data);
		},
		error:function(data) {
			console.log(data);
		}
	});	
	
}	
	
function SME_loadImage(el) {
	var imageId = el.id;
	var imageChooserEl=jQuery(el).parent(".SME_ImageChooser_block_Album_Holder");
	var uri = jQuery(el).attr("data-uri");
	var clientId = jQuery(imageChooserEl).attr('clientid');
	var galleryId = jQuery(el).attr('data-galleryid');
	jQuery.ajax({
		type:"POST",
		url: "../wp-admin/admin-ajax.php",
		data: {
			action: 'SME_getImageInfo',
			nonce: SME_Ajax.nonce,
			imageId:imageId,
			imageUri:uri,
			galleryId:galleryId
			},
		success:function(data){
			var imageData = data.data;
			var imageSize = passedData['editorimagesize'];
			var tempTitle = passedData['title'];
			var tempAltText = passedData['alttext'];
			var tempCaption = passedData['caption'];
			var defaultnewwindow = passedData['defaultnewwindow'];
			var defaultclickresponse = passedData['defaultclickresponse'];

			var found = [],          // an array to collect the strings that are found
			rxp = /{([^}]+)}/g,
			curMatch;
			var title = tempTitle;
			var alttext = tempAltText;
			var caption = tempCaption;
			while( curMatch = rxp.exec( tempTitle ) ) {

				var tempImg = imageData.Image;
				//if its a base property on the image
				if (curMatch[1] in tempImg)
					var tempVar = imageData.Image[curMatch[1]];
				//otherwise it's in the metadata uri
				else
					var tempVar = imageData.Image.Uris.ImageMetadata.ImageMetadata[curMatch[1]];
	if (!tempVar) tempVar = "";
	var replace = "{"+curMatch[1]+"}";
				var re = new RegExp(replace,"g");
				title=title.replace(re,tempVar);
			}
			while( curMatch = rxp.exec( tempAltText ) ) {
				var tempImg = imageData.Image;
				//if its a base property on the image
				if (curMatch[1] in tempImg)
					var tempVar = imageData.Image[curMatch[1]];
				//otherwise it's in the metadata uri
				else
					var tempVar = imageData.Image.Uris.ImageMetadata.ImageMetadata[curMatch[1]];
				if (!tempVar) tempVar = "";
				var replace = "{"+curMatch[1]+"}";
				var re = new RegExp(replace,"g");
				alttext=alttext.replace(re,tempVar);
			}
			while( curMatch = rxp.exec( tempCaption ) ) {
				var tempImg = imageData.Image;
				//if its a base property on the image
				if (curMatch[1] in tempImg)
					var tempVar = imageData.Image[curMatch[1]];
				//otherwise it's in the metadata uri
				else
					var tempVar = imageData.Image.Uris.ImageMetadata.ImageMetadata[curMatch[1]];
				if (!tempVar) tempVar = "";
				var replace = "{"+curMatch[1]+"}";
				var re = new RegExp(replace,"g");
				caption=caption.replace(re,tempVar);
			}	
			var videoEmbed =[];
			var isVideo = imageData.Image.IsVideo;
			if (isVideo) { 
				var tempURLArray = imageData.Image.Uris.ImageSizes.ImageSizes;
				var defaultVideoUrl = imageData.Image.Uris.ImageSizes.ImageSizes.LargestVideoUrl;
				var tempKeys = ['1920VideoUrl','1280VideoUrl','960VideoUrl','640VideoUrl','320VideoUrl'];
				for (index=0; index <tempKeys.length;++index) {
					var thisVid = {'value':tempURLArray[tempKeys[index]],'label':tempKeys[index]};
					if (!videoEmbed.some(e => e.value === tempURLArray[tempKeys[index]])) 
					videoEmbed.push(thisVid);
				}
			}
			var imageURL = imageData.Image.Uris.ImageSizes.ImageSizes.LargestImageUrl;
			switch(imageSize){
				case 'Ti':
				    if ('TinyImageUrl' in imageData.Image.Uris.ImageSizes.ImageSizes)
						imageURL = imageData.Image.Uris.ImageSizes.ImageSizes.TinyImageUrl;
				break;
				case 'Th':
				    if ('ThumbImageUrl' in imageData.Image.Uris.ImageSizes.ImageSizes)
						imageURL = imageData.Image.Uris.ImageSizes.ImageSizes.ThumbImageUrl;
				break;
				case 'S':
				    if ('SmallImageUrl' in imageData.Image.Uris.ImageSizes.ImageSizes)
						imageURL = imageData.Image.Uris.ImageSizes.ImageSizes.SmallImageUrl;
				break;
				case 'M':
				    if ('MediumImageUrl' in imageData.Image.Uris.ImageSizes.ImageSizes)
						imageURL = imageData.Image.Uris.ImageSizes.ImageSizes.MediumImageUrl;
				break;
				case 'L':
				    if ('LargeImageUrl' in imageData.Image.Uris.ImageSizes.ImageSizes)
						imageURL = imageData.Image.Uris.ImageSizes.ImageSizes.LargeImageUrl;
				break;
				case 'XL':
				    if ('XLargeImageUrl' in imageData.Image.Uris.ImageSizes.ImageSizes)
						imageURL = imageData.Image.Uris.ImageSizes.ImageSizes.XLargeImageUrl;
				break;
				case 'X2':
				    if ('X2LargeImageUrl' in imageData.Image.Uris.ImageSizes.ImageSizes)
						imageURL = imageData.Image.Uris.ImageSizes.ImageSizes.X2LargeImageUrl;
				break;
				case 'X3':
				    if ('X3LargeImageUrl' in imageData.Image.Uris.ImageSizes.ImageSizes)
						imageURL = imageData.Image.Uris.ImageSizes.ImageSizes.X3LargeImageUrl;
				break;
				case 'X4':
				    if ('X4LargeImageUrl' in imageData.Image.Uris.ImageSizes.ImageSizes)
						imageURL = imageData.Image.Uris.ImageSizes.ImageSizes.X4LargeImageUrl;
				break;
				case 'X5':
				    if ('X5LargeImageUrl' in imageData.Image.Uris.ImageSizes.ImageSizes)
						imageURL = imageData.Image.Uris.ImageSizes.ImageSizes.X5LargeImageUrl;
				break;
				case '4k':
					var tempImg = 'imageData.Image.Uris.ImageSizes.ImageSizes.4KImageUrl'
				    if ('4KImageUrl' in imageData.Image.Uris.ImageSizes.ImageSizes){
						imageURL =tempImg;
					}
				break;
				case '5k':
					var tempImg = 'imageData.Image.Uris.ImageSizes.ImageSizes.5KImageUrl'
				    if ('5KImageUrl' in imageData.Image.Uris.ImageSizes.ImageSizes)
						imageURL = tempImg;
				break;
			}
			var imageTargets =[];
			var largestImageUrl=imageData.Image.Uris.ImageSizes.ImageSizes.LargestImageUrl;
			var imageWebUri=imageData.Image.WebUri;
			imageTargets.push({name:'smugmug_lightbox',url:imageWebUri});
			imageTargets.push({name:'smugmug_cart',url:imageWebUri +'/buy'});
			imageTargets.push({name:'smugmug_gallery',url:imageData.Image.Uris.ImageAlbum.Album.WebUri});
			imageTargets.push({name:'image',url:largestImageUrl});
		
			//var imageURL = imageData.Image.Uris.ImageSizes.ImageSizes.MediumImageUrl;
			wp.data.dispatch( 'core/block-editor' ).updateBlockAttributes( clientId,  { defaultnewwindow:defaultnewwindow,linkTarget:defaultclickresponse, mediaURL: imageURL ,linkTargets: imageTargets,width:"",height:"",caption:caption,alt:alttext,title:title,isVideo:isVideo,embedVideoUrls:videoEmbed,videoURL:isVideo?defaultVideoUrl : "" } )
			jQuery(imageChooserEl).remove();
		},
		error:function(data) {
			var feedbackDiv = jQuery("#block-"+clientId).find('.smugmugembed_feedback');
			jQuery(feedbackDiv).html( '<p>Error' + data.responseText + '</p>' );
			jQuery(feedbackDiv ).css('backgroundColor','pink').animate({'opacity':'1'},200).delay( 1500 ).animate({'opacity':'0'},2500);
			console.log(data);

		}
	});		
}
function SME_launchSearchWindow(clientId) {
	var el = jQuery("#block-"+clientId);
	var imageChooserEl = jQuery(el).find(".SME_ImageChooser_block_Album_Holder");
	if (!jQuery(el).find(".SME_ImageChooser_block_Album_Holder").length){
		jQuery(el).prepend("<div clientid='"+clientId+"' class='SME_ImageChooser_block_Album_Holder' ></div>");
	}
	var imageChooserEl = jQuery(el).find(".SME_ImageChooser_block_Album_Holder");
	jQuery.ajax({
		type:"POST",
		url: "../wp-admin/admin-ajax.php",
		data: {
			action: 'SME_loadSelectedAlbums',
			nonce: SME_Ajax.nonce,
			mode:'frontend'
			},
		success:function(data){
			imageChooserEl.html(data.data);
		},
		error:function(data) {
			console.log("error:"+data);
		}
	});		
}