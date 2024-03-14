(function(){
	tinymce.PluginManager.add('oracle_cards',function(editor, url) {
		editor.addButton('oracle_cards', {
			text : ' Oracle Cards',
			tooltip : 'Insert a deck',
			icon : 'oracle-cards',
			onclick : function () {
				if(false !== decks_ids){
					var decks = [],n = 0;
					for(n;n < decks_ids.length;++n){
						decks[n] = {text:decks_titles[n],value:decks_ids[n]};
					}
					editor.windowManager.open({
						title : 'Oracle cards',
						id : 'oracle-cards-window',
						body : [
							{
								type : 'listbox',
								name : 'deck_id',
								label : 'Deck',
								'values' : decks
							}, {
								type : 'listbox',
								name : 'deck_layout',
								label : 'Layout',
								'values' : [{
										text : 'Deck',
										value : 'deck'
									}, {
										text : 'Folding Fan',
										value : 'folding_fan',
									}
								],
								onselect : function () {
									var deck_type = this.value(),
										layout_from_input = this.parent().parent().items()[6];
									document.body.className = document.body.className.replace(' eos-folding_fan','').replace(' eos-deck','') + ' eos-' + deck_type;

								}
							},{
								type : 'listbox',
								name : 'show_title',
								label : 'Show titles',
								'values' : [{
										text : 'Show',
										value : 'true'
									}, {
										text : 'Don\'t show',
										value : 'false'
									},
								]
							}, {
								type : 'listbox',
								name : 'title_alignment',
								label : 'Titles alignment',
								'values' : [{
										text : 'Left',
										value : 'left'
									}, {
										text : 'Center',
										value : 'center'
									}, {
										text : 'Right',
										value : 'right'
									},
								]
							},{
								type: 'textbox',
								subtype: 'hidden',
								name: 'custom_back_id',
								id: 'custom_back_id'
							},{
								type : 'button',
								name : 'custom_back_id_button',
								label : 'Custom back',
								text : 'Upload image',
								onclick: function(e){
									e.preventDefault();
									var hidden = document.getElementById('custom_back_id'),
										imgText = document.getElementById('imageText-body'),
									custom_uploader = wp.media.frames.file_frame = wp.media({
										title: 'Choose an image for the deck',
										button: {text: 'Add as back'},
										multiple: false
									});
									custom_uploader.on('select', function() {
										var attachment = custom_uploader.state().get('selection').first();
										hidden.value = attachment.id;
										if(!imgText.value){
											imgText.style.backgroundImage = 'url(' + attachment.attributes.sizes.thumbnail.url + ')';
										}
									});
									custom_uploader.open();
								}
							},{
								type: 'container',
								name: 'text',
								label: '',
								id: 'imageText'
							},{
								type : 'textbox',
								name : 'space_top',
								label : 'Space before the deck',
								'value' : 20
							}, {
								type : 'textbox',
								name : 'space_top_text',
								label : 'Space before the text',
								'value' : 20
							},{
								type : 'textbox',
								name : 'space_top_button',
								label : 'Space before the button',
								'value' : 20
							},{
								type : 'textbox',
								name : 'maxnumber',
								label : 'Max number of cards',
								'value' : 100
							},{
								type : 'textbox',
								name : 'deck_from',
								label : 'Deck layout from',
								id : 'deck_from',
								'value' : 930,
							},{
								type : 'textbox',
								name : 'distance',
								label : 'Cards distance',
								id : 'cards_distance',
								'value' : 2,
							},{
								type : 'textbox',
								name : 'maxmargin',
								label : 'Maximum space',
								id : 'cards_maxmargin',
								'value' : 400,
							},{
								type : 'textbox',
								name : 'maxrand',
								label : 'Level of randomness',
								id : 'cards_maxrand',
								'value' : 100,
							},{
								type : 'listbox',
								name : 'on_mobile',
								label : 'Visibility on mobile',
								'values' : [{
										text : 'Show',
										value : 'show'
									},{
										text : 'Hide',
										value : 'hide',
									},{
										text : 'Remove',
										value : 'remove',
									}
								],
							}
						],
						onsubmit : function (e) {
							editor.insertContent('[oracle_cards deck="' + e.data.deck_id + '" deck_type="' + e.data.deck_layout + '" deck_from="' + e.data.deck_from + '" show_title="' + e.data.show_title + '" title_alignment="' + e.data.title_alignment + '" custom_back_id="' + e.data.custom_back_id + '" space_top="' + e.data.space_top + '" space_top_text="' + e.data.space_top_text + '" space_top_button="' + e.data.space_top_button + '" maxnumber="' + e.data.maxnumber + '" distance="' + e.data.distance + '" maxmargin="' + e.data.maxmargin + '" maxrand="' + e.data.maxrand + '" on_mobile="' + e.data.on_mobile + '"]');
						}
					});
				}
				else{
					alert('You have no decks yet. First save by clicking on Update.');
				}
			}
		});
	});
})();
