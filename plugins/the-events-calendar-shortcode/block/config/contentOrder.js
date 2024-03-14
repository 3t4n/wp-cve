import { __ } from '@wordpress/i18n';

const config = {
	standard: [
		{
			value: 'title',
			label: __( 'Title', 'the-events-calendar-shortcode' ),
			checked: true,
		},
		{
			value: 'thumbnail',
			label: __( 'Thumbnail', 'the-events-calendar-shortcode' ),
			checked: true,
			conditional: {
				attribute: 'thumb',
				comparison: '!==',
				value: 'false',
			},
		},
		{
			value: 'excerpt',
			label: __( 'Excerpt', 'the-events-calendar-shortcode' ),
			checked: true,
			conditional: {
				attribute: 'excerpt',
				comparison: '!==',
				value: 'false',
			},
		},
		{
			value: 'date',
			label: __( 'Date', 'the-events-calendar-shortcode' ),
			checked: true,
		},
		{
			value: 'venue',
			label: __( 'Venue', 'the-events-calendar-shortcode' ),
			checked: true,
			conditional: {
				attribute: 'venue',
				comparison: '===',
				value: 'true',
			},
		},
	],
};

export default config;
