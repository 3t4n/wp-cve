const { __ } = wp.i18n;

const {
	registerBlockType
} = wp.blocks;

const {
    InspectorControls,
    BlockControls,
	AlignmentToolbar
} = wp.editor;

const {
    PanelBody,
    PanelRow,
    TextControl
} = wp.components;
 
const {
    Fragment
} = wp.element;


registerBlockType('hotblocks/contact', {
    title: "Hot Contact",
    icon: 'email',
    category: 'hot-blocks',
    description: __('Simple contact form with anti-spam protection.'),

    supports: {
	    align: true
	},

    attributes: {
        antiSpamQuestion: {
	    	type: 'string',
	        default: '8 + 4 = ?'
	    },
	    antiSpamAnswer: {
	    	type: 'string',
	        default: '12'
	    },
	    buttonText: {
	    	type: 'string',
	        default: 'Send'
	    },
    },

    // props are passed to edit by default
    // props contains things like setAttributes and attributes
    edit(props) {

        // we are peeling off the things we need
        const {
        	setAttributes,
        	attributes,
        	className, // The class name as a string!
        	focus // this is "true" when the user clicks on the block
        } = props;
        const { antiSpamQuestion, antiSpamAnswer, buttonText } = props.attributes;

        function onAntiSpamQuestionChange(changes) {
            setAttributes({
                antiSpamQuestion: changes
            });
        }

		function onAntiSpamAnswerChange(changes) {
		    setAttributes({
		        antiSpamAnswer: changes
		    })
		}

		function onButtonTextChange(changes) {
		    setAttributes({
		        buttonText: changes
		    })
		}

        return ([
		    <InspectorControls>
		    	<div style={{
		            padding:"4px 16px"
		        }}>
			        <TextControl
				        label={ __( 'Question' ) }
				        value={ attributes.antiSpamQuestion }
				        onChange={ onAntiSpamQuestionChange }
				    />
			    </div>
			    <div style={{
		            padding:"4px 16px"
		        }}>
			        <TextControl
				        label={ __( 'Answer' ) }
				        value={ attributes.antiSpamAnswer }
				        onChange={ onAntiSpamAnswerChange }
				    />
			    </div>
			    <div style={{
		            padding:"4px 16px"
		        }}>
			        <TextControl
				        label={ __( 'Button Text' ) }
				        value={ attributes.buttonText }
				        onChange={ onButtonTextChange }
				    />
			    </div>
			</InspectorControls>,
		    <div className={className}>
		        <form method="post">
		        	<input type="text" name="hb_name" id="hb_name" size="15" value="" placeholder={ __( 'Name *' ) }/>
		        	<input type="email" name="hb_email" id="hb_email" size="15" value="" placeholder={ __( 'E-mail *' ) }/>
		        	<textarea name="hb_message" id="hb_message" placeholder={ __( 'Message *' ) } spellcheck="false"></textarea>
		        	<input type="text" name="hb_anti_spam_answer" id="hb_anti_spam_answer" size="15" value="" placeholder={'Anti-spam: ' + attributes.antiSpamQuestion}/>
		        	<input type="submit" name="hb_submit" id="hb_submit" value={attributes.buttonText}/>
		        	<input type="hidden" value={attributes.antiSpamAnswer}/>
		        </form>
		    </div>
		]);
    },

    // again, props are automatically passed to save and edit
	save(props) {

	    const { attributes, className } = props;
	    const { antiSpamQuestion, antiSpamAnswer, buttonText } = props.attributes;

	    return (
	        <div className={className}>
	            <form method="post">
		        	<input type="text" required name="hb_name" id="hb_name" size="15" value="" placeholder={ __( 'Name *' ) }/>
		        	<input type="email" required name="hb_email" id="hb_email" size="15" value="" placeholder={ __( 'E-mail *' ) }/>
		        	<textarea required name="hb_message" id="hb_message" placeholder={ __( 'Message *' ) } spellcheck="false"></textarea>
		        	<input type="text" required name="hb_anti_spam_answer" id="hb_anti_spam_answer" size="15" value="" placeholder={'Anti-spam: ' + attributes.antiSpamQuestion}/>
		        	<input type="submit" name="hb_submit" id="hb_submit" value={attributes.buttonText}/>
		        	<input type="hidden" name="hb_anti_spam_correct" value={attributes.antiSpamAnswer}/>
		        </form>
	        </div>
	    );
	}
});