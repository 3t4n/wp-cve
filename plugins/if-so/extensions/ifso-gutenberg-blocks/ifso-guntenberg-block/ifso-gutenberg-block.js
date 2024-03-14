var el = wp.element.createElement;

var iconEl = el('svg', {width:20, height:20,  viewBox: '0 0 1080 1080', class:'ifso-block-icon' },[ el('path', {key:'icon-path-1', d: "M418.9,499.8c-32.2,0-61.5,0-92.2,0c0-46.7,0-92.6,0-140c29.8,0,59.6,0,91.9,0c0-7.6-0.7-14,0.1-20.1c4.6-32.2,5.5-65.6,15.3-96.2c19.4-60.5,67.6-90.1,127.1-102.1c67.4-13.6,135.3-6.5,204.2-3c0,51.9,0,102.8,0,155.4c-15.7-1.8-30.7-3.7-45.6-5.2c-7.5-0.8-15.2-1.7-22.7-1.2c-43.8,3.2-61,25.8-53.6,71.6c38.1,0,76.5,0,116.2,0c0,47,0,92.5,0,139.9c-37.1,0-74.3,0-113.2,0c0,152.1,0,302.3,0,453.7c-76.3,0-151,0-227.5,0C418.9,802.1,418.9,652,418.9,499.8z", class:'st0'})
    ,el('path', {key:'icon-path-2', d: "M0,134.5c83.7,0,166.3,0,250,0c0,272.8,0,544.9,0,818.3c-82.8,0-165.8,0-250,0C0,680.8,0,408.3,0,134.5z", class:'st0'}),
    el('path', {key:'icon-path-3',style: {fill:'#FD5B56'},  d: "M893.5,392.3c62.2,44.4,123.4,88.1,185.8,132.7c-62.2,44.4-123.3,88-185.8,132.7C893.5,568.8,893.5,481.5,893.5,392.3z", class:'st1'})]);

wp.blocks.registerBlockType('ifso/ifso-block', {

    title: 'Dynamic Content', // Block name visible to user

    icon: iconEl, // Toolbar icon can be either using WP Dashicons or custom SVG

    category: 'common', // Under which category the block would appear

    attributes: { // The data this block will be storing

        selected: { type: 'integer', default:0 } /// The ID of the trigger selected to be displayed

    },

    edit:  window.wp.data.withSelect( function( select ) {
            return {
                posts: select( 'core' ).getEntityRecords( 'postType', 'ifso_triggers', {per_page:-1} )
            };
        } )( function( props ) {

            if ( ! props.posts ) {
                return "Loading...";
            }

            if ( props.posts.length === 0 ) {
                //If no triggers are present on the site, make the block and show the error message
                return el('div',{className:'ifso-block-wrapper components-placeholder ifso-block-error ' + props.className},
                         [el('div',{className: 'components-placeholder__label'},
                            [iconEl,el('span',{className:'components-placeholder__instructions'},'Dynamic Content')])
                             ,el('span',{className:'components-placeholder__instructions'},'Select a dynamic trigger from the list'),el('span',{className:"errMsg components-placeholder__instructions"},[el('span',{className:'dashicons dashicons-info'},''),"You haven\'t set up any dynamic triggers"]),el('button',{onClick:function(){window.open(ifso_base_url+"/wp-admin/post-new.php?post_type=ifso_triggers","_blank")},className:'is-secondary components-button is-button is-large is-default'},"Create a new trigger")]);
            }

            var ret = [];
            var selectedExists = false;
            ret.push(el('option',{id:0, value:0},'Select a trigger'));
            props.posts.map(function(post){     //Fill the ret array with <option> tags for every trigger
                var opts = { id:post.id, value:post.id };
                if (props.attributes.selected == post.id){
                    opts.selected = 'selected';
                    selectedExists = true;
                }
                var e = el('option',opts,((post.title.raw == '') ? '' : ' ' + post.title.raw) + ' (ID: ' + post.id + ')');
                ret.push(e);
            });

            if(props.attributes.selected==0) props.setAttributes({selected : props.posts[0].id });  //Set the selected value to the first option if nothing is saved there
            //if(props.attributes.selected==0) props.setAttributes({selected : 0 });  //Set the selected value to the first option if nothing is saved there

            var selClass = '';

            if(props.attributes.selected!=0 && !selectedExists) selClass = 'trigger-error';     //The trigger previously selected doesnt exist anymore, display error.

            var sel = el('select',{onChange:function(e){props.setAttributes({selected : parseInt(e.target.value) }); e.target.className = '';}, className: selClass},ret);    //Make the trigger selector


            //Create the final block element(yes, i know -_- )
            var wrap = el('div',{className:'ifso-block-wrapper components-placeholder ' + props.className},
                [el('div',{className: 'components-placeholder__label'},iconEl,el('span',{className:'components-placeholder__instructions'},'Dynamic Content')),el('span',{className:'components-placeholder__instructions'},'Select a dynamic trigger from the list'),sel,
                    el('div',{className:'ifso-button-wrap'},[el('button',{className: 'is-secondary components-button is-button is-large is-default', onClick:function(){window.open(ifso_base_url + '?post_type=ifso_triggers&p=' + props.attributes.selected,'_blank')}},'View trigger'),
                    el('button',{className: 'is-secondary components-button is-button is-large is-default',onClick:function(){window.open(ifso_base_url + '/wp-admin/post.php?action=edit&post=' + props.attributes.selected,'_blank')}},'Edit trigger')])
                ]);


            return wrap;
        } )

});