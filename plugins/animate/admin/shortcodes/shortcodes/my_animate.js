var Attention_Seekers = ['bounce', 'flash', 'pulse', 'rubberBand', 'shake', 'swing', 'tada', 'wobble'];
var Bouncing_Entrances = ['bounceIn','bounceInDown','bounceInLeft','bounceInRight','bounceInUp'];
var Bouncing_Exits = ['bounceOut','bounceOutDown','bounceOutLeft','bounceOutRight','bounceOutUp'];
var Fading_Entrances = ['fadeIn','fadeInDown','fadeInDownBig','fadeInLeft','fadeInLeftBig','fadeInRight','fadeInRightBig','fadeInUp','fadeInUpBig'];
var Fading_Exits = ['fadeOut','fadeOutDown','fadeOutDownBig','fadeOutLeft','fadeOutLeftBig','fadeOutRight','fadeOutRightBig','fadeOutUp','fadeOutUpBig'];
var Flippers = ['flip','flipInX','flipInY','flipOutX','flipOutY'];
var Lightspeed = ['lightSpeedIn','lightSpeedOut'];
var Rotating_Entrances = ['rotateIn','rotateInDownLeft','rotateInDownRight','rotateInUpLeft','rotateInUpRight'];
var Rotating_Exits = ['rotateOut','rotateOutDownLeft','rotateOutDownRight','rotateOutUpLeft','rotateOutUpRight'];
var Specials = ['hinge','rollIn','rollOut'];
var Zoom_Entrances = ['zoomIn','zoomInDown','zoomInLeft','zoomInRight','zoomInUp'];
var Zoom_Exits = ['zoomOut','zoomOutDown','zoomOutLeft','zoomOutRight','zoomOutUp'];
						
frameworkShortcodeAtts={
	attributes:[
			{
                                label:animate_plugin_data.animation_style_label,
                                id:"style",
                                controlType:"optgroup-select-control",
                                selectValues:[Attention_Seekers, Bouncing_Entrances, Bouncing_Exits, Fading_Entrances, Fading_Exits, Flippers, Lightspeed, Rotating_Entrances, Rotating_Exits, Specials, Zoom_Entrances, Zoom_Exits],
				selectTitles:["Attention Seekers","Bouncing Entrances","Bouncing Exits","Fading Entrances","Fading Exits","Flippers","Lightspeed","Rotating Entrances","Rotating Exits","Specials","Zoom Entrances","Zoom Exits"],
                                defaultValue: 'bounce',
                                defaultText: 'bounce',
                        },
			{
				label:animate_plugin_data.duration_label,
				id:"data-wow-duration",
				help:animate_plugin_data.duration_help
			},
			{
				label:animate_plugin_data.delay_label,
				id:"data-wow-delay",
				help:animate_plugin_data.delay_help
			},
			{
                                label:animate_plugin_data.offset_label,
                                id:"data-wow-offset",
                                help:animate_plugin_data.offset_help
                        },
			{
                                label:animate_plugin_data.iteration_label,
                                id:"data-wow-iteration",
                                help:animate_plugin_data.iteration_help
                        },
			{
                                label:animate_plugin_data.animate_infinitely_label,
                                id:"infinitely",
                                controlType:"select-control",
                                selectValues:['no', 'yes'],
                                defaultValue: 'no',
                                defaultText: 'no'
                        },
			{
				label:animate_plugin_data.custom_class_label,
				id:"custom_class",
				help:animate_plugin_data.custom_class_help
			}
	],
	defaultContent: "<img src='"+animate_plugin_data.url+"images/round200x200.png' width='200' height='200' alt=''>",
	shortcode:"animate"
};
