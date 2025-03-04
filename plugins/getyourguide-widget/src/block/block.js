/* eslint-disable react/display-name */
/**
 * BLOCK: gyg-wp-plugin
 */
import React from 'react';
import { activitiesDefaultAttributes, cityDefaultAttributes } from './config';
import ActivitiesEdit from './activities/Edit';
import CityEdit from './city/Edit';
import Search from './Search/Search';
import WPAttributes from './common/WPAttributes';
import { wpIframe } from './common/Iframe';

const { __ } = wp.i18n;
const { registerBlockType } = wp.blocks;
const category = 'embed';
const icon = (
  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 358.6 283.5">
    <path
      fill="#f53"
      d="M82.3 127.8a10 10 0 1 1 10.1 9.9h-.2a9.8 9.8 0 0 1-9.9-9.7zM99.9 110a9.8 9.8 0 1 0 0-.2z"
    />
    <path
      fill="#f53"
      d="M124 97.9v17.7c13.8 0 24.8 11.4 24.8 26.1s-11 26.1-24.8 26.1-24.8-11.3-24.8-26.1H79.8c0 24.6 19.6 43.9 44.2 43.9s44.2-19.3 44.2-43.9-19.6-43.8-44.2-43.8zM323.5 264.8v-16.9h31.2v-16.8h-31.2v-16.7H358v-16.9h-53.5v84.2h54.1v-16.9h-35.1zM248.6 197.3h-27.8v84.4h27.8c26.1 0 44.6-17.9 44.6-42.1s-18.5-42.3-44.6-42.3zm1.5 67.5h-10.3v-50.5h10.3c14.1 0 23.7 10.9 23.7 25.3s-9.6 25.2-23.7 25.2zM146.9 249.3c0 9.7-7 16.8-16.4 16.8s-16.4-7.1-16.4-16.8v-51.8H95.2v51.4c0 19.9 14.9 34.6 35.3 34.6s35.2-14.7 35.2-34.6v-51.4h-18.8zM250.2 151.1V99.6h-18.8v51.8c0 9.7-7 16.9-16.4 16.9s-16.4-7.2-16.4-16.9V99.6h-18.8v51.5c0 19.8 14.8 34.5 35.2 34.5s35.2-14.7 35.2-34.5zM31.8 183.9h18.9v-32l31.7-52.3H61.1l-20 34.5-19.7-34.5H0l31.8 52.5v31.8zM157.7 1.7h64v17h-64zM149.6 69h-35.1V52.2h31.3V35.3h-31.3V18.7H149v-17H95.6V86h54V69zM293.3 162.6l-9.8-10.3 9.7-10.5-9.8-10.3L293 121l-9.8-10.3L294 99.6h-19.6l-10.7 11.2 9.8 10.3-9.6 10.5 9.7 10.3-9.6 10.5 9.8 10.4-9.7 10.4 10.4 10.8h19.7l-10.6-10.9 9.7-10.5zM201.4 237.1c2.8-2.5 7.5-6.7 7.5-14.5s-4.7-11.9-7.5-14.4l-.3-.4a6.6 6.6 0 0 1-1.7-4.5v-6h-19v7.9h.1v.3c0 7.8 4.7 12 7.5 14.5s2 1.9 2 2.6 0 .9-2 2.7-7.1 6.4-7.4 13.6v1.3c.3 7.2 4.7 11.2 7.4 13.6s2 1.9 2 2.6 0 .9-2 2.7-7.5 6.7-7.5 14.5v.2h-.1v7.9h19v-5.9a7.1 7.1 0 0 1 1.7-4.6l.3-.3c2.8-2.5 7.5-6.7 7.5-14.5s-4.7-11.9-7.5-14.5-2-1.8-2-2.4.3-.8 2.1-2.4zM180.2 70.3h18.9V86h-18.9zM180.2 47.8h18.9v15.7h-18.9zM180.2 25.4h18.9v15.7h-18.9zM342.5 183.9l-23-32.2a26.6 26.6 0 0 0 19.1-25.3c0-15.3-12.6-26.8-28.6-26.8h-11v17.1h10.3a10.2 10.2 0 0 1 0 20.3H299v16.2l20.5 30.7zM79.2 24C74.4 9.3 58.6 0 40.5 0 17.7 0 .6 15.4.6 37.8v11.6c0 22.1 16.1 38.4 36.1 38.4a35.8 35.8 0 0 0 28.9-14.5V86h16.5V39.5H37.5v15.8h24.3c-2 8.7-10.3 15.4-20.9 15.4S19 62 19 49.4V37.8c0-11.9 8.9-20.7 21.4-20.7 9.7 0 18.4 5.3 21.3 13.1zM79.2 219.7c-4.8-14.7-20.6-24-38.7-24-22.8 0-39.9 15.4-39.9 37.8v11.6c0 22.1 16.1 38.4 36.1 38.4A35.8 35.8 0 0 0 65.6 269v12.7h16.5v-46.5H37.5V251h24.3c-2 8.7-10.3 15.4-20.9 15.4S19 257.7 19 245.1v-11.6c0-11.9 8.9-20.7 21.4-20.7 9.7 0 18.4 5.3 21.3 13.1z"
    />
  </svg>
);

registerBlockType('cgb/block-gyg-wp-plugin-search', {
  attributes: {},
  category,
  description: __('Search widget for GetYourGuide.com'),
  icon,
  title: __('GetYourGuide Search'),
  edit: () => <Search />,
  save: () => <Search />,
});

/**
 * Register: aa Gutenberg Block.
 *
 * Registers a new block provided a unique name and an object defining its
 * behavior. Once registered, the block is made editor as an option to any
 * editor interface where blocks are implemented.
 *
 * @link https://wordpress.org/gutenberg/handbook/block-api/
 * @param  {string}   name     Block name.
 * @param  {Object}   settings Block settings.
 * @return {?WPBlock}          The block, if it has been successfully
 *                             registered; otherwise `undefined`.
 */
registerBlockType('cgb/block-gyg-wp-plugin', {
  attributes: Object.keys(activitiesDefaultAttributes).reduce(
    (acc, key) => ({ [key]: { type: 'string' }, ...acc }),
    {},
  ),
  description: __('Tour widget for GetYourGuide.com'),
  title: __('GetYourGuide Activity Widget'),
  icon,
  category,
  keywords: [__('gyg-wp-plugin — CGB Block'), __('CGB Example'), __('create-guten-block')],

  edit: props => (
    <WPAttributes {...{ defaultAttributes: activitiesDefaultAttributes, ...props }}>
      {({
        handleChange, labels, state, attributes, defaultAttributes,
      }) => (
        <ActivitiesEdit
          {...{
            handleChange,
            labels,
            state,
            attributes,
            defaultAttributes,
          }}
        />
      )}
    </WPAttributes>
  ),

  save({ attributes }) {
    return wpIframe({ widgetType: 'activities', wpWidgetType: 'wp_activities', queries: attributes });
  },
});

registerBlockType('cgb/block-gyg-wp-plugin-city', {
  attributes: Object.keys(cityDefaultAttributes).reduce(
    (acc, key) => ({ [key]: { type: 'string' }, ...acc }),
    {},
  ),
  category,
  description: __('City widget for GetYourGuide.com'),
  icon,
  title: __('GetYourGuide City Widget'),
  edit: props => (
    <WPAttributes {...{ defaultAttributes: cityDefaultAttributes, ...props }}>
      {({
        handleChange, labels, state, attributes, defaultAttributes,
      }) => (
        <CityEdit
          {...{
            handleChange,
            labels,
            state,
            attributes,
            defaultAttributes,
          }}
        />
      )}
    </WPAttributes>
  ),
  save({ attributes }) {
    return wpIframe({ widgetType: 'city', wpWidgetType: 'wp_city', queries: attributes });
  },
});
