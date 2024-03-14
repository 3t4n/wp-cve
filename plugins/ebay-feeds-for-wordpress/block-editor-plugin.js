( function( blocks, i18n, element ) {
	/* Set up variables */
	var el = element.createElement;
	//var children = blocks.source.children;
	var BlockControls = wp.blocks.BlockControls;
  var AlignmentToolbar = wp.blocks.AlignmentToolbar;
  var Fragment = wp.element.Fragment;
	var MediaUpload = wp.blocks.MediaUpload;
  var InspectorControls = wp.editor.InspectorControls;
  var TextControl = wp.components.TextControl;
	//var SelectControl = wp.blocks.InspectorControls.SelectControl;

  const iconeBay = el('svg', { width: 25, height: 25 },
    el('path', { d: "M6.056 12.13V7.21h1.2v3.026c.59-.703 1.402-.906 2.202-.906 1.34 0 2.828.904 2.828 2.855 0 .233-.015.457-.06.668.24-.953 1.274-1.305 2.896-1.344.51-.018 1.095-.018 1.56-.018v-.135c0-.885-.556-1.244-1.53-1.244-.72 0-1.245.3-1.305.81h-1.275c.136-1.29 1.5-1.62 2.686-1.62 1.064 0 1.995.27 2.415 1.02l-.436-.84h1.41l2.055 4.125 2.055-4.126H24l-3.72 7.305h-1.346l1.07-2.04-2.33-4.38c.13.255.2.555.2.93v2.46c0 .346.01.69.04 1.005H16.8c-.03-.255-.046-.51-.046-.765-.603.734-1.32.96-2.32.96-1.48 0-2.272-.78-2.272-1.695 0-.15.015-.284.037-.405-.3 1.246-1.36 2.086-2.767 2.086-.87 0-1.694-.315-2.2-.93 0 .24-.015.494-.04.734h-1.18c.02-.39.04-.855.04-1.245v-1.05h-4.83c.065 1.095.818 1.74 1.853 1.74.718 0 1.355-.3 1.568-.93h1.24c-.24 1.29-1.61 1.725-2.79 1.725C.95 15.007 0 13.82 0 12.23c0-1.754.982-2.91 3.116-2.91 1.688 0 2.93.886 2.94 2.806v.005zm9.137.183c-1.095.034-1.77.233-1.77.95 0 .465.36.97 1.305.97 1.26 0 1.935-.69 1.935-1.814v-.13c-.45 0-.99.006-1.484.022h.012zm-6.06 1.875c1.11 0 1.876-.806 1.876-2.02s-.768-2.02-1.893-2.02c-1.11 0-1.89.806-1.89 2.02s.765 2.02 1.875 2.02h.03zm-4.35-2.514c-.044-1.125-.854-1.546-1.725-1.546-.944 0-1.694.474-1.815 1.546h3.54z" } )
    );


  /* Register block type */
  blocks.registerBlockType('ebay-feeds-for-wordpress/ebay-feeds-for-wordpress-form', {
  		title: i18n.__( 'eBay Feed' ), // The title of our block.
  		icon: iconeBay, // Dashicon icon for our block
  		category: 'widgets', // The category of the block.
  		attributes: { // To save block content
  			label: {
          type: 'string'
        },
        feedurl: {
          type: 'string'
        },
        items: {
          type: 'string'
        },
        header: {
          type: 'string'
        }
      },

      edit: function( props ) {

       var focus = props.focus;
       var focusedEditable = props.focus ? props.focus.editable || 'titleOne' : null;
       var attributes = props.attributes;
       var label = props.attributes.label ? props.attributes.label : '';
       var items = props.attributes.items ? props.attributes.items : '';
       var feedurl = props.attributes.feedurl ? props.attributes.feedurl : '';
       var header = props.attributes.header ? props.attributes.header : '';

       var extrastring = items ? " showing " + items + " items" : "";

       function setFeedValue( nextValue ) {

        props.setAttributes( {feedurl: nextValue } );

      }

      function setNumberOfItemsValue( nextValue ) {

        props.setAttributes( { items: nextValue } );

      }

      function setLabel( nextValue ) {

        props.setAttributes( { label: nextValue } );

      }

      function setHeader(nextValue) {

        props.setAttributes({ header: nextValue });

        }

      /* return [

      el(
        'h3',
        { className: 'ebay-feeds-for-wordpress-admin-feed' },
        'eBay Feed ' + label + extrastring + ' will appear here'
        ),

      !! focus && el(

        wp.blocks.InspectorControls,
        { key: 'inspector' },
        el( 'div', { className: 'components-block-description'},
         el( 'p', {}, i18n.__('Here are your options for the eBay Feed' ) ),
         ),
        el(
          TextControl,
          {
            label: i18n.__( 'Label' ),
            value: label,
            help: i18n.__( 'Used to identify the feed within the WordPress Editor. Will not be shown on the site.' ),
            instanceId: 'ebay-feeds-for-wordpress-label',
            onChange: setLabel,
          }
          ),
        el(
         TextControl,
         {
          label: i18n.__( 'eBay Feed' ),
          value: feedurl,
          instanceId: 'ebay-feeds-for-wordpress-feed-url',
          onChange: setFeedValue,
        }
        ),
        el(
         TextControl,
         {
          label: i18n.__( 'Number of Items' ),
          value: items,
          onChange: setNumberOfItemsValue,
          instanceId: 'ebay-feeds-for-wordpress-feed-items',
        }
        ),
        )

      ] */

      return (
        el(
          Fragment,
          null,
          el(
            InspectorControls,
            null,
            el(
              'div', { className: 'components-block-description' },
              el('p', {}, i18n.__('Here are your options for the eBay Feed')),
            ),
            el(
              TextControl,
              {
                label: i18n.__('Label'),
                value: label,
                help: i18n.__('Used to identify the feed within the WordPress Editor. Will not be shown on the site.'),
                instanceId: 'ebay-feeds-for-wordpress-label',
                onChange: setLabel,
              }
            ),
            el(
              TextControl,
              {
                label: i18n.__('eBay Feed'),
                value: feedurl,
                instanceId: 'ebay-feeds-for-wordpress-feed-url',
                onChange: setFeedValue,
              }
            ),
            el(
              TextControl,
              {
                label: i18n.__('Number of Items'),
                value: items,
                onChange: setNumberOfItemsValue,
                instanceId: 'ebay-feeds-for-wordpress-feed-items',
              }
            ),
            el(
              TextControl,
              {
                label: i18n.__('Header'),
                value: header,
                onChange: setHeader,
                instanceId: 'ebay-feeds-for-wordpress-feed-header',
              }
            )

          ),
          el (
            'h3',
            { className: 'ebay-feeds-for-wordpress-admin-feed' },
            'eBay Feed ' + label + extrastring + ' will appear here'
          )
        )
      );
    },
    save: function() {
      return null;
    },
  } )

} )(
window.wp.blocks,
window.wp.i18n,
window.wp.element,
);