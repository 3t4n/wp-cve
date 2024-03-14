( function( blocks, element, config ) {
    let el = element.createElement;
    const singlePostIcon = el('img', {src: 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMy41OTciIGhlaWdodD0iMTYuNSIgdmlld0JveD0iMCAwIDEzLjU5NyAxNi41Ij4KICA8ZyBpZD0iVG9nZ2xlX1N3aXRjaF9vZmYiIGRhdGEtbmFtZT0iVG9nZ2xlIFN3aXRjaCBvZmYiIHRyYW5zZm9ybT0idHJhbnNsYXRlKC0zLjU1IDAuMjUpIj4KICAgIDxwYXRoIGlkPSJQYXRoXzMwNTYiIGRhdGEtbmFtZT0iUGF0aCAzMDU2IiBkPSJNNS45NTIuNWg5LjE5NEExLjQ1MiwxLjQ1MiwwLDAsMSwxNi42LDEuOTUydjEyLjFBMS40NTIsMS40NTIsMCwwLDEsMTUuMTQ1LDE1LjVINS45NTJBMS40NTIsMS40NTIsMCwwLDEsNC41LDE0LjA0OFYxLjk1MkExLjQ1MiwxLjQ1MiwwLDAsMSw1Ljk1Mi41WiIgdHJhbnNmb3JtPSJ0cmFuc2xhdGUoLTAuMikiIGZpbGw9Im5vbmUiIHN0cm9rZT0iIzU2NWU2NiIgc3Ryb2tlLWxpbmVjYXA9InJvdW5kIiBzdHJva2UtbGluZWpvaW49InJvdW5kIiBzdHJva2Utd2lkdGg9IjEuNSIvPgogIDwvZz4KPC9zdmc+Cg=='});
    const allPostIcon = el('img', {src: 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxNy42NDQiIGhlaWdodD0iMTYuNSIgdmlld0JveD0iMCAwIDE3LjY0NCAxNi41Ij4KICAgIDxnIGlkPSJJY29uX2ZlYXRoZXItY2hlY2stc3F1YXJlIiBkYXRhLW5hbWU9Ikljb24gZmVhdGhlci1jaGVjay1zcXVhcmUiIHRyYW5zZm9ybT0idHJhbnNsYXRlKC0zLjc1IC0zLjc1KSI+CiAgICAgICAgPHBhdGggaWQ9IlBhdGhfNDU1NCIgZGF0YS1uYW1lPSJQYXRoIDQ1NTQiIGQ9Ik0xMy41LDExLjgzM2wyLjUsMi41TDI0LjMzMyw2IiB0cmFuc2Zvcm09InRyYW5zbGF0ZSgtNCAtMC42NjcpIiBmaWxsPSJub25lIiBzdHJva2U9IiM1NjVlNjYiIHN0cm9rZS1saW5lY2FwPSJyb3VuZCIgc3Ryb2tlLWxpbmVqb2luPSJyb3VuZCIgc3Ryb2tlLXdpZHRoPSIxLjUiLz4KICAgICAgICA8cGF0aCBpZD0iUGF0aF80NTU1IiBkYXRhLW5hbWU9IlBhdGggNDU1NSIgZD0iTTE5LjUsMTJ2NS44MzNBMS42NjcsMS42NjcsMCwwLDEsMTcuODMzLDE5LjVINi4xNjdBMS42NjcsMS42NjcsMCwwLDEsNC41LDE3LjgzM1Y2LjE2N0ExLjY2NywxLjY2NywwLDAsMSw2LjE2Nyw0LjVoOS4xNjciIGZpbGw9Im5vbmUiIHN0cm9rZT0iIzU2NWU2NiIgc3Ryb2tlLWxpbmVjYXA9InJvdW5kIiBzdHJva2UtbGluZWpvaW49InJvdW5kIiBzdHJva2Utd2lkdGg9IjEuNSIvPgogICAgPC9nPgo8L3N2Zz4='});
    const catPostIcon = el('img', {src: 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyNi4yNSIgaGVpZ2h0PSIyMi41IiB2aWV3Qm94PSIwIDAgMjYuMjUgMjIuNSI+CiAgPGcgaWQ9Ikdyb3VwXzgwMDAiIGRhdGEtbmFtZT0iR3JvdXAgODAwMCIgdHJhbnNmb3JtPSJ0cmFuc2xhdGUoLTg0Ny4yNSAtNTE5LjI1KSI+CiAgICA8ZyBpZD0iVG9nZ2xlX1N3aXRjaF9vZmYiIGRhdGEtbmFtZT0iVG9nZ2xlIFN3aXRjaCBvZmYiIHRyYW5zZm9ybT0idHJhbnNsYXRlKDg0OS43IDUyNS41KSI+CiAgICAgIDxwYXRoIGlkPSJQYXRoXzMwNTYiIGRhdGEtbmFtZT0iUGF0aCAzMDU2IiBkPSJNNS45NTIuNWg5LjE5NEExLjQ1MiwxLjQ1MiwwLDAsMSwxNi42LDEuOTUydjEyLjFBMS40NTIsMS40NTIsMCwwLDEsMTUuMTQ1LDE1LjVINS45NTJBMS40NTIsMS40NTIsMCwwLDEsNC41LDE0LjA0OFYxLjk1MkExLjQ1MiwxLjQ1MiwwLDAsMSw1Ljk1Mi41WiIgdHJhbnNmb3JtPSJ0cmFuc2xhdGUoLTAuMikiIGZpbGw9Im5vbmUiIHN0cm9rZT0iIzU2NWU2NiIgc3Ryb2tlLWxpbmVjYXA9InJvdW5kIiBzdHJva2UtbGluZWpvaW49InJvdW5kIiBzdHJva2Utd2lkdGg9IjEuNSIvPgogICAgPC9nPgogICAgPGcgaWQ9IkNvcHkiIHRyYW5zZm9ybT0idHJhbnNsYXRlKDg0OCA1MjApIj4KICAgICAgPHBhdGggaWQ9IlBhdGhfNzAiIGRhdGEtbmFtZT0iUGF0aCA3MCIgZD0iTTQuMjUsMTYuNzVIMy41QTEuNSwxLjUsMCwwLDEsMiwxNS4yNVYzLjVBMS41LDEuNSwwLDAsMSwzLjUsMmg2Ljc1YTEuNSwxLjUsMCwwLDEsMS41LDEuNXYuNzUiIHRyYW5zZm9ybT0idHJhbnNsYXRlKC0yIC0yKSIgZmlsbD0ibm9uZSIgc3Ryb2tlPSIjNTY1ZTY2IiBzdHJva2UtbGluZWNhcD0icm91bmQiIHN0cm9rZS1saW5lam9pbj0icm91bmQiIHN0cm9rZS13aWR0aD0iMS41Ii8+CiAgICA8L2c+CiAgICA8ZyBpZD0iQ29weS0yIiBkYXRhLW5hbWU9IkNvcHkiIHRyYW5zZm9ybT0idHJhbnNsYXRlKDg2MyA1MjApIj4KICAgICAgPHBhdGggaWQ9IlBhdGhfNzAtMiIgZGF0YS1uYW1lPSJQYXRoIDcwIiBkPSJNOS41LDE2Ljc1aC43NWExLjUsMS41LDAsMCwwLDEuNS0xLjVWMy41QTEuNSwxLjUsMCwwLDAsMTAuMjUsMkgzLjVBMS41LDEuNSwwLDAsMCwyLDMuNXYuNzUiIHRyYW5zZm9ybT0idHJhbnNsYXRlKC0yIC0yKSIgZmlsbD0ibm9uZSIgc3Ryb2tlPSIjNTY1ZTY2IiBzdHJva2UtbGluZWNhcD0icm91bmQiIHN0cm9rZS1saW5lam9pbj0icm91bmQiIHN0cm9rZS13aWR0aD0iMS41Ii8+CiAgICA8L2c+CiAgPC9nPgo8L3N2Zz4K'});

    let blockStyle = {
        padding: '20px',
        border: '1px solid #1657CD',
        backgroundColor: '#F5F8FB',
        lineHeight: 1.8,
        color: '#191e23',
        fontSize: '16px',
        letterSpacing: '-0.015em',
    };

    let labelStyle = {
        margin: '0',
        color: '#474747',
        fontSize: '14px',
        paddingBottom: '16px',
        display: 'block',
        fontWeight: '400'
    }

    let contentStyle = {
        fontSize: '14px',
        margin: '0',
        padding: '9px 16px',
        borderRadius: '5px',
        backgroundColor: '#FFFFFF',
        border: '1px solid #E5E5E5',
        lineHeight: 1,
        color: '#474747',
    }

    let selectStyle = {
        width: '100%',
        padding: '9px 16px',
        borderRadius: '5px',
        minWidth: '400px',
        maxWidth: 'none',
        backgroundColor: '#FFFFFF',
        border: '1px solid #E5E5E5',
        lineHeight: 1,
        color: '#474747',
        fontSize: '14px',
        letterSpacing: '-0.015em',
    };

    /**
     * Gutenberg Custom Block for display all published posts of MakeStories 
     */
    blocks.registerBlockType( 'makestories/custom-block-all-published-post', {
        title: 'MS Published Post',
        icon: allPostIcon,
        category: 'layout',
        example: {},

        edit: function() {
            return el(
                'div',
                { style: blockStyle },
                el(
                    'label',
                    {style: labelStyle} ,
                    'This is shortcode for all published posts which would be displayed on Preview !'),
                el(
                    'p',
                    {style: contentStyle } ,
                    "[ms_get_published_post]")
            )
        },

        save: function() {
			return "[ms_get_published_post]";
        },
	} );

    /**
     * Gutenberg Custom Block for display all published posts from category of MakeStories 
     */
    blocks.registerBlockType( 'makestories/custom-block-category-post', {
        title: 'MS Category Post',
        icon: catPostIcon,
        category: 'layout',
        attributes: {
            cat: {type: 'string'}
        },
        example: {},

        edit: function({attributes, setAttributes}) {
            let id = false;
            if(typeof attributes === "object" && attributes.id){
                id = attributes.id;
            }
            if(!id){
                id = "single_post_"+Math.round(Math.random() * 1000);
                setAttributes({
                    id,
                });
            }

            function onChange() {
                var value = $('#'+id).val();
                setAttributes({cat: value})
            }

            return el(
                'div',
                { style: blockStyle },
                el(
                    'label',
                    {style: labelStyle} ,
                    'Select Category of Story'),
                    el(
                        'select',
                        { style: selectStyle, id, onChange,  value: attributes.cat,},
                        [
                            el(
                                "option",
                                { value: "" },
                                "Select Category"
                            ),
                            config.categories.map(category => el(
                                "option",
                                { value: category.id },
                                category.name+" ("+category.count+" storie(s) published) "
                            ))
                        ]
                    )
            )
        },

        save: function({attributes}) {
            var shortcode = "[ms_get_post_by_category category_id='" + attributes.cat + "']";
            return shortcode;
        },
    } );

    /**
     * Gutenberg Custom Block for display single published post of MakeStories 
     */
    blocks.registerBlockType( 'makestories/custom-block-single-post', {
        title: 'MS Single Post',
        icon: singlePostIcon,
        category: 'layout',
        example: {},
        attributes: {
            storyId: {type: 'string'},
        },

        edit: function({attributes, setAttributes}) {
            let id = false;
            if(typeof attributes === "object" && attributes.id){
                id = attributes.id;
            }
            if(!id){
                id = "single_post_"+Math.round(Math.random() * 1000);
                setAttributes({
                    id,
                });
            }
            function onChange() {
                let story = $('#'+id).val();
                setAttributes({storyId: story})
            }

            return el(
                'div',
                { style: blockStyle },
                el(
                    'label',
                    {style: labelStyle} ,
                    'Select Story'),
                    el(
                        'select',
                        { style: selectStyle, id, onChange, value: attributes.storyId,},
                            [
                                el(
                                    "option",
                                    { value: "" },
                                    "Select Story"
                                ),
                                config.stories.map(story => el(
                                    "option",
                                    { value: story.id },
                                    story.name
                                )),
                            ]
                    )
            )
        },

        save: function({attributes}) {
            console.log(attributes);
            return "[ms_get_single_post post_id='" + attributes.storyId + "']";
        },
    } );

    /**
     * Gutenberg Custom Block for display single published widget of MakeStories 
     */
     blocks.registerBlockType( 'makestories/custom-block-single-widget', {
        title: 'MS Single Widget',
        icon: singlePostIcon,
        category: 'layout',
        example: {},
        attributes: {
            widgetId: {type: 'string'},
        },

        edit: function({attributes, setAttributes}) {
            let id = false;
            if(typeof attributes === "object" && attributes.id){
                id = attributes.id;
            }
            if(!id){
                id = "single_post_"+Math.round(Math.random() * 1000);
                setAttributes({
                    id,
                });
            }
            function onChange() {
                let story = $('#'+id).val();
                setAttributes({widgetId: story})
            }

            return el(
                'div',
                { style: blockStyle },
                el(
                    'label',
                    {style: labelStyle} ,
                    'Select Widget'),
                    el(
                        'select',
                        { style: selectStyle, id, onChange, value: attributes.widgetId,},
                            [
                                el(
                                    "option",
                                    { value: "" },
                                    "Select Widget"
                                ),
                                config.widgets.map(story => el(
                                    "option",
                                    { value: story.id },
                                    story.name
                                )),
                            ]
                    )
            )
        },

        save: function({attributes}) {
            console.log(attributes);
            return "[ms_get_single_widget widget_id='" + attributes.widgetId + "']";
        },

    } );
}(
    window.wp.blocks,
    window.wp.element,
    window.MS_API_CONFIG,
) );