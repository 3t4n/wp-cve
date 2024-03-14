import React, { Component } from 'react';
import { render } from 'react-dom';
const ReuseForm = __REUSEFORM__;

const selectOptions = REUSEB_ADMIN.postTypes;

let fields = [
  {
    id: 'reuseb_post_type_slug',
    type: 'text', // switchalt
    label: REUSEB_ADMIN.LANG.POST_SLUG,
    param: '',
    subtitle:
      REUSEB_ADMIN.LANG
        .IF_WANT_TO_CHANGE_THE_DEFAULT_POST_SLUG_ADD_THE_NAME_HERE,
    value: '', // eikhane changes
  },
  {
    id: 'reuseb_post_type_menu_position',
    type: 'select', // switchalt
    label: REUSEB_ADMIN.LANG.MENU_POSITION,
    param: '',
    multiple: false,
    clearable: false,
    subtitle: REUSEB_ADMIN.LANG.SELECT_THE_POST_TYPE_MENU_POSITION,
    options: {
      '5': REUSEB_ADMIN.LANG.BELOW_POSTS,
      '10': REUSEB_ADMIN.LANG.BELOW_MEDIA,
      '15': REUSEB_ADMIN.LANG.BELOW_LINKS,
      '20': REUSEB_ADMIN.LANG.BELOW_PAGES,
      '25': REUSEB_ADMIN.LANG.BELOW_COMMENTS,
      '60': REUSEB_ADMIN.LANG.BELOW_FIRST_SEPARATOR,
      '65': REUSEB_ADMIN.LANG.BELOW_PLUGINS,
      '70': REUSEB_ADMIN.LANG.BELOW_USERS,
      '75': REUSEB_ADMIN.LANG.BELOW_TOOLS,
      '80': REUSEB_ADMIN.LANG.BELOW_SETTINGS,
      '100': REUSEB_ADMIN.LANG.BELOW_SECOND_SEPARATOR,
    },
    value: '25',
  },
  {
    id: 'reuseb_post_type_support_title',
    type: 'switch', // switchalt
    label: REUSEB_ADMIN.LANG.TITLE,
    param: '',
    subtitle: REUSEB_ADMIN.LANG.ENABLE_TITILE_INTO_THIS_POST,
    value: null, // eikhane changes
  },
  {
    id: 'reuseb_post_type_support_editor',
    type: 'switch', // switchalt
    label: REUSEB_ADMIN.LANG.EDITOR,
    param: '',
    subtitle: REUSEB_ADMIN.LANG.ENABLE_EDITOR_INTO_THIS_POST,
    value: null, // eikhane changes
  },
  {
    id: 'reuseb_post_type_support_author',
    type: 'switch', // switchalt
    label: REUSEB_ADMIN.LANG.AUTHOR,
    param: '',
    subtitle: REUSEB_ADMIN.LANG.ENABLE_AUTHOR_INTO_THIS_POST,
    value: null, // eikhane changes
  },
  {
    id: 'reuseb_post_type_support_thumbnail',
    type: 'switch', // switchalt
    label: REUSEB_ADMIN.LANG.THUMBNAIL,
    param: '',
    subtitle: REUSEB_ADMIN.LANG.ENABLE_THUMBNAIL_INTO_THIS_POST,
    value: null, // eikhane changes
  },
  {
    id: 'reuseb_post_type_support_excerpt',
    type: 'switch', // switchalt
    label: REUSEB_ADMIN.LANG.EXCERPT,
    param: '',
    subtitle: REUSEB_ADMIN.LANG.ENABLE_EXCERPT_INTO_THIS_POST,
    value: null, // eikhane changes
  },
  {
    id: 'reuseb_post_type_support_trackbacks',
    type: 'switch', // switchalt
    label: REUSEB_ADMIN.LANG.TRACKBACKS,
    param: '',
    subtitle: REUSEB_ADMIN.LANG.ENABLE_TRACKBACKS_INTO_THIS_POST,
    value: null, // eikhane changes
  },
  {
    id: 'reuseb_post_type_support_custom_fields',
    type: 'switch', // switchalt
    label: REUSEB_ADMIN.LANG.CUSTOM_FIELDS,
    param: '',
    subtitle: REUSEB_ADMIN.LANG.ENABLE_CUSTOM_FIELDS_INTO_THIS_POST,
    value: null, // eikhane changes
  },
  {
    id: 'reuseb_post_type_support_comments',
    type: 'switch', // switchalt
    label: REUSEB_ADMIN.LANG.COMMENTS,
    param: '',
    subtitle: REUSEB_ADMIN.LANG.ENABLE_COMMENTS_INTO_THIS_POST,
    value: null, // eikhane changes
  },
  {
    id: 'reuseb_post_type_support_rest_api',
    type: 'switch', // switchalt
    label: REUSEB_ADMIN.LANG.REST_API,
    param: '',
    subtitle: REUSEB_ADMIN.LANG.ENABLE_REST_API_SUPPORT_FOR_THIS_POST,
    value: null, // eikhane changes
  },
  {
    id: 'reuseb_meta_support_rest_api',
    type: 'switch', // switchalt
    label: REUSEB_ADMIN.LANG.REST_API_META,
    param: '',
    subtitle: REUSEB_ADMIN.LANG.ENABLE_REST_API_SUPPORT_FOR_THIS_POST_META,
    value: null, // eikhane changes
  },
  {
    id: 'reuseb_post_type_support_revisions',
    type: 'switch', // switchalt
    label: REUSEB_ADMIN.LANG.REVISIONS,
    param: '',
    subtitle: REUSEB_ADMIN.LANG.ENABLE_REVISIONS_INTO_THIS_POST,
    value: null, // eikhane changes
  },
  {
    id: 'reuseb_post_type_support_page_attributes',
    type: 'switch', // switchalt
    label: REUSEB_ADMIN.LANG.PAGE_ATTRIBUTES,
    param: '',
    subtitle: REUSEB_ADMIN.LANG.ENABLE_PAGE_ATTRIBUTES_INTO_THIS_POST,
    value: null, // eikhane changes
  },
  {
    id: 'reuseb_post_type_support_post_formats',
    type: 'switch', // switchalt
    label: REUSEB_ADMIN.LANG.POST_FORMATS,
    param: '',
    subtitle: REUSEB_ADMIN.LANG.ENABLE_POST_FORMATS_INTO_THIS_POST,
    value: null, // eikhane changes
  },
  {
    id: 'reuseb_post_type_menu_icon_select',
    type: 'select', // switchalt
    label: REUSEB_ADMIN.LANG.ICON_TYPE,
    param: '',
    multiple: false,
    clearable: false,
    subtitle: REUSEB_ADMIN.LANG.SELECT_THE_DEFAULT_ICON_TYPE_OR_UPLOAD_A_NEW,
    options: {
      default_icon: REUSEB_ADMIN.LANG.DEFAULT_ICON,
      upload_icon: REUSEB_ADMIN.LANG.UPLOAD_ICON,
    },
    value: 'default_icon',
  },
  {
    id: 'reuseb_post_type_menu_icon_custom',
    type: 'imageupload',
    label: REUSEB_ADMIN.LANG.UPLOAD_CUSTOM_ICON,
    param: 'imageupload',
    subtitle: REUSEB_ADMIN.LANG.YOU_CAN_UPLOAD_ANY_CUSTOM_IMAGE_ICON,
    multiple: false,
  },
  {
    id: 'reuseb_post_type_menu_icon',
    type: 'iconselect',
    label: REUSEB_ADMIN.LANG.MENU_ICON,
    param: 'iconselect',
    subtitle: REUSEB_ADMIN.LANG.SELECT_MENU_ICON,
    options: [
      {
        name: 'dashicons dashicons-menu',
        value: 'dashicons-menu',
      },
      {
        name: 'dashicons dashicons-admin-site',
        value: 'dashicons-admin-site',
      },
      {
        name: 'dashicons dashicons-dashboard',
        value: 'dashicons-dashboard',
      },
      {
        name: 'dashicons dashicons-admin-post',
        value: 'dashicons-admin-post',
      },
      {
        name: 'dashicons dashicons-admin-media',
        value: 'dashicons-admin-media',
      },
      {
        name: 'dashicons dashicons-admin-links',
        value: 'dashicons-admin-links',
      },
      {
        name: 'dashicons dashicons-admin-page',
        value: 'dashicons-admin-page',
      },
      {
        name: 'dashicons dashicons-admin-comments',
        value: 'dashicons-admin-comments',
      },
      {
        name: 'dashicons dashicons-admin-appearance',
        value: 'dashicons dashicons-admin-appearance',
      },
      {
        name: 'dashicons dashicons-admin-plugins',
        value: 'dashicons-admin-plugins',
      },
      {
        name: 'dashicons dashicons-admin-users',
        value: 'dashicons-admin-users',
      },
      {
        name: 'dashicons dashicons-admin-tools',
        value: 'dashicons-admin-tools',
      },
      {
        name: 'dashicons dashicons-admin-settings',
        value: 'dashicons-admin-settings',
      },
      {
        name: 'dashicons dashicons-admin-network',
        value: 'dashicons-admin-network',
      },
      {
        name: 'dashicons dashicons-admin-home',
        value: 'dashicons-admin-home',
      },
      {
        name: 'dashicons dashicons-admin-generic',
        value: 'dashicons-admin-generic',
      },
      {
        name: 'dashicons dashicons-admin-collapse',
        value: 'dashicons-admin-collapse',
      },
      {
        name: 'dashicons dashicons-filter',
        value: 'dashicons-filter',
      },
      {
        name: 'dashicons dashicons-admin-customizer',
        value: 'dashicons-admin-customizer',
      },
      {
        name: 'dashicons dashicons-admin-multisite',
        value: 'dashicons-admin-multisite',
      },
      {
        name: 'dashicons dashicons-welcome-write-blog',
        value: 'dashicons-welcome-write-blog',
      },
      {
        name: 'dashicons dashicons-welcome-add-page',
        value: 'dashicons-welcome-write-blog',
      },
      {
        name: 'dashicons dashicons-welcome-view-site',
        value: 'dashicons-welcome-view-site',
      },
      {
        name: 'dashicons dashicons-welcome-widgets-menus',
        value: 'dashicons-welcome-widgets-menus',
      },
      {
        name: 'dashicons dashicons-welcome-comments',
        value: 'dashicons-welcome-comments',
      },
      {
        name: 'dashicons dashicons-welcome-learn-more',
        value: 'dashicons-welcome-learn-more',
      },
      {
        name: 'dashicons dashicons-format-aside',
        value: 'dashicons-format-aside',
      },
      {
        name: 'dashicons dashicons-format-image',
        value: 'dashicons-format-image',
      },
      {
        name: 'dashicons dashicons-format-gallery',
        value: 'dashicons-format-gallery',
      },
      {
        name: 'dashicons dashicons-format-video',
        value: 'dashicons-format-video',
      },
      {
        name: 'dashicons dashicons-format-status',
        value: 'dashicons-format-status',
      },
      {
        name: 'dashicons dashicons-format-quote',
        value: 'dashicons-format-quote',
      },
      {
        name: 'dashicons dashicons-format-chat',
        value: 'dashicons-format-chat',
      },
      {
        name: 'dashicons dashicons-format-audio',
        value: 'dashicons-format-audio',
      },
      {
        name: 'dashicons dashicons-camera',
        value: 'dashicons-camera',
      },
      {
        name: 'dashicons dashicons-images-alt',
        value: 'dashicons-images-alt',
      },
      {
        name: 'dashicons dashicons-images-alt2',
        value: 'dashicons-images-alt2',
      },
      {
        name: 'dashicons dashicons-video-alt',
        value: 'dashicons-video-alt',
      },
      {
        name: 'dashicons dashicons-video-alt2',
        value: 'dashicons-video-alt2',
      },
      {
        name: 'dashicons dashicons-video-alt3',
        value: 'dashicons-video-alt3',
      },
      {
        name: 'dashicons dashicons-media-archive',
        value: 'dashicons-media-archive',
      },
      {
        name: 'dashicons dashicons-media-audio',
        value: 'dashicons-media-audio',
      },
      {
        name: 'dashicons dashicons-media-code',
        value: 'dashicons-media-code',
      },
      {
        name: 'dashicons dashicons-media-default',
        value: 'dashicons-media-default',
      },
      {
        name: 'dashicons dashicons-media-document',
        value: 'dashicons-media-document',
      },
      {
        name: 'dashicons dashicons-media-interactive',
        value: 'dashicons-media-interactive',
      },
      {
        name: 'dashicons dashicons-media-spreadsheet',
        value: 'dashicons-media-spreadsheet',
      },
      {
        name: 'dashicons dashicons-media-text',
        value: 'dashicons-media-text',
      },
      {
        name: 'dashicons dashicons-media-video',
        value: 'dashicons-media-video',
      },
      {
        name: 'dashicons dashicons-playlist-audio',
        value: 'dashicons-playlist-audio',
      },
      {
        name: 'dashicons dashicons-playlist-video',
        value: 'dashicons-playlist-video',
      },
      {
        name: 'dashicons dashicons-controls-play',
        value: 'dashicons-controls-play',
      },
      {
        name: 'dashicons dashicons-controls-pause',
        value: 'dashicons-controls-pause',
      },
      {
        name: 'dashicons dashicons-controls-forward',
        value: 'dashicons-controls-forward',
      },
      {
        name: 'dashicons dashicons-controls-skipforward',
        value: 'dashicons-controls-skipforward',
      },
      {
        name: 'dashicons dashicons-controls-back',
        value: 'dashicons-controls-back',
      },
      {
        name: 'dashicons dashicons-controls-skipback',
        value: 'dashicons-controls-skipback',
      },
      {
        name: 'dashicons dashicons-controls-repeat',
        value: 'dashicons-controls-repeat',
      },
      {
        name: 'dashicons dashicons-controls-volumeon',
        value: 'dashicons-controls-volumeon',
      },
      {
        name: 'dashicons dashicons-controls-volumeoff',
        value: 'dashicons-controls-volumeoff',
      },
      {
        name: 'dashicons dashicons-image-crop',
        value: 'dashicons-image-crop',
      },
      {
        name: 'dashicons dashicons-image-rotate',
        value: 'dashicons-image-rotate',
      },
      {
        name: 'dashicons dashicons-image-rotate-left',
        value: 'dashicons-image-rotate-left',
      },
      {
        name: 'dashicons dashicons-image-rotate-right',
        value: 'dashicons-image-rotate-right',
      },
      {
        name: 'dashicons dashicons-image-flip-vertical',
        value: 'dashicons-image-flip-vertical',
      },
      {
        name: 'dashicons dashicons-image-flip-horizontal',
        value: 'dashicons-image-flip-horizontal',
      },
      {
        name: 'dashicons dashicons-image-filter',
        value: 'dashicons-image-filter',
      },
      {
        name: 'dashicons dashicons-undo',
        value: 'dashicons-undo',
      },
      {
        name: 'dashicons dashicons-redo',
        value: 'dashicons-redo',
      },
      {
        name: 'dashicons dashicons-editor-bold',
        value: 'dashicons-editor-bold',
      },
      {
        name: 'dashicons dashicons-editor-italic',
        value: 'dashicons-editor-italic',
      },
      {
        name: 'dashicons dashicons-editor-ul',
        value: 'dashicons-editor-ul',
      },
      {
        name: 'dashicons dashicons-editor-ol',
        value: 'dashicons-editor-ol',
      },
      {
        name: 'dashicons dashicons-editor-quote',
        value: 'dashicons-editor-quote',
      },
      {
        name: 'dashicons dashicons-editor-alignleft',
        value: 'dashicons-editor-alignleft',
      },
      {
        name: 'dashicons dashicons-editor-aligncenter',
        value: 'dashicons-editor-aligncenter',
      },
      {
        name: 'dashicons dashicons-editor-alignright',
        value: 'dashicons-editor-alignright',
      },
      {
        name: 'dashicons dashicons-editor-insertmore',
        value: 'dashicons-editor-insertmore',
      },
      {
        name: 'dashicons dashicons-editor-spellcheck',
        value: 'dashicons-editor-spellcheck',
      },
      {
        name: 'dashicons dashicons-editor-expand',
        value: 'dashicons-editor-expand',
      },
      {
        name: 'dashicons dashicons-editor-contract',
        value: 'dashicons-editor-contract',
      },
      {
        name: 'dashicons dashicons-editor-kitchensink',
        value: 'dashicons-editor-kitchensink',
      },
      {
        name: 'dashicons dashicons-editor-underline',
        value: 'dashicons-editor-underline',
      },
      {
        name: 'dashicons dashicons-editor-justify',
        value: 'dashicons-editor-justify',
      },
      {
        name: 'dashicons dashicons-editor-textcolor',
        value: 'dashicons-editor-textcolor',
      },
      {
        name: 'dashicons dashicons-editor-paste-word',
        value: 'dashicons-editor-paste-word',
      },
      {
        name: 'dashicons dashicons-editor-paste-text',
        value: 'dashicons-editor-paste-text',
      },
      {
        name: 'dashicons dashicons-editor-removeformatting',
        value: 'dashicons-editor-removeformatting',
      },
      {
        name: 'dashicons dashicons-editor-video',
        value: 'dashicons-editor-video',
      },
      {
        name: 'dashicons dashicons-editor-customchar',
        value: 'dashicons-editor-customchar',
      },
      {
        name: 'dashicons dashicons-editor-outdent',
        value: 'dashicons-editor-outdent',
      },
      {
        name: 'dashicons dashicons-editor-indent',
        value: 'dashicons-editor-indent',
      },
      {
        name: 'dashicons dashicons-editor-help',
        value: 'dashicons-editor-help',
      },
      {
        name: 'dashicons dashicons-editor-strikethrough',
        value: 'dashicons-editor-strikethrough',
      },
      {
        name: 'dashicons dashicons-editor-unlink',
        value: 'dashicons-editor-unlink',
      },
      {
        name: 'dashicons dashicons-editor-rtl',
        value: 'dashicons-editor-rtl',
      },
      {
        name: 'dashicons dashicons-editor-break',
        value: 'dashicons-editor-break',
      },
      {
        name: 'dashicons dashicons-editor-code',
        value: 'dashicons-editor-code',
      },
      {
        name: 'dashicons dashicons-editor-paragraph',
        value: 'dashicons-editor-paragraph',
      },
      {
        name: 'dashicons dashicons-editor-table',
        value: 'dashicons-editor-table',
      },
      {
        name: 'dashicons dashicons-align-left',
        value: 'dashicons-align-left',
      },
      {
        name: 'dashicons dashicons-align-right',
        value: 'dashicons-align-right',
      },
      {
        name: 'dashicons dashicons-align-center',
        value: 'dashicons-align-center',
      },
      {
        name: 'dashicons dashicons-align-none',
        value: 'dashicons-align-none',
      },
      {
        name: 'dashicons dashicons-lock',
        value: 'dashicons-lock',
      },
      {
        name: 'dashicons dashicons-unlock',
        value: 'dashicons-unlock',
      },
      {
        name: 'dashicons dashicons-calendar',
        value: 'dashicons-calendar',
      },
      {
        name: 'dashicons dashicons-calendar-alt',
        value: 'dashicons-calendar-alt',
      },
      {
        name: 'dashicons dashicons-visibility',
        value: 'dashicons-visibility',
      },
      {
        name: 'dashicons dashicons-hidden',
        value: 'dashicons-hidden',
      },
      {
        name: 'dashicons dashicons-post-status',
        value: 'dashicons-post-status',
      },
      {
        name: 'dashicons dashicons-edit',
        value: 'dashicons-edit',
      },
      {
        name: 'dashicons dashicons-trash',
        value: 'dashicons-trash',
      },
      {
        name: 'dashicons dashicons-sticky',
        value: 'dashicons-sticky',
      },
      {
        name: 'dashicons dashicons-external',
        value: 'dashicons-external',
      },
      {
        name: 'dashicons dashicons-arrow-up',
        value: 'dashicons-arrow-up',
      },
      {
        name: 'dashicons dashicons-arrow-down',
        value: 'dashicons-arrow-down',
      },
      {
        name: 'dashicons dashicons-arrow-right',
        value: 'dashicons-arrow-right',
      },
      {
        name: 'dashicons dashicons-arrow-left',
        value: 'dashicons-arrow-left',
      },
      {
        name: 'dashicons dashicons-arrow-up-alt',
        value: 'dashicons-arrow-up-alt',
      },
      {
        name: 'dashicons dashicons-arrow-down-alt',
        value: 'dashicons-arrow-down-alt',
      },
      {
        name: 'dashicons dashicons-arrow-right-alt',
        value: 'dashicons-arrow-right-alt',
      },
      {
        name: 'dashicons dashicons-arrow-left-alt',
        value: 'dashicons-arrow-left-alt',
      },
      {
        name: 'dashicons dashicons-arrow-up-alt2',
        value: 'dashicons-arrow-up-alt2',
      },
      {
        name: 'dashicons dashicons-arrow-down-alt2',
        value: 'dashicons-arrow-down-alt2',
      },
      {
        name: 'dashicons dashicons-arrow-right-alt2',
        value: 'dashicons-arrow-right-alt2',
      },
      {
        name: 'dashicons dashicons-arrow-left-alt2',
        value: 'dashicons-arrow-left-alt2',
      },
      {
        name: 'dashicons dashicons-sort',
        value: 'dashicons-sort',
      },
      {
        name: 'dashicons dashicons-leftright',
        value: 'dashicons-leftright',
      },
      {
        name: 'dashicons dashicons-randomize',
        value: 'dashicons-randomize',
      },
      {
        name: 'dashicons dashicons-list-view',
        value: 'dashicons-list-view',
      },
      {
        name: 'dashicons dashicons-exerpt-view',
        value: 'dashicons-exerpt-view',
      },
      {
        name: 'dashicons dashicons-grid-view',
        value: 'dashicons-grid-view',
      },
      {
        name: 'dashicons dashicons-move',
        value: 'dashicons-move',
      },
      {
        name: 'dashicons dashicons-share',
        value: 'dashicons-share',
      },
      {
        name: 'dashicons dashicons-share-alt',
        value: 'dashicons-share-alt',
      },
      {
        name: 'dashicons dashicons-share-alt2',
        value: 'dashicons-share-alt2',
      },
      {
        name: 'dashicons dashicons-twitter',
        value: 'dashicons-twitter',
      },
      {
        name: 'dashicons dashicons-rss',
        value: 'dashicons-rss',
      },
      {
        name: 'dashicons dashicons-email',
        value: 'dashicons-email',
      },
      {
        name: 'dashicons dashicons-email-alt',
        value: 'dashicons-email-alt',
      },
      {
        name: 'dashicons dashicons-facebook',
        value: 'dashicons-facebook',
      },
      {
        name: 'dashicons dashicons-facebook-alt',
        value: 'dashicons-facebook-alt',
      },
      {
        name: 'dashicons dashicons-googleplus',
        value: 'dashicons-googleplus',
      },
      {
        name: 'dashicons dashicons-googleplus',
        value: 'dashicons-googleplus',
      },
      {
        name: 'dashicons dashicons-hammer',
        value: 'dashicons-hammer',
      },
      {
        name: 'dashicons dashicons-art',
        value: 'dashicons-art',
      },
      {
        name: 'dashicons dashicons-migrate',
        value: 'dashicons-migrate',
      },
      {
        name: 'dashicons dashicons-performance',
        value: 'dashicons-performance',
      },
      {
        name: 'dashicons dashicons-universal-access',
        value: 'dashicons-universal-access',
      },
      {
        name: 'dashicons dashicons-universal-access-alt',
        value: 'dashicons-universal-access-alt',
      },
      {
        name: 'dashicons dashicons-tickets',
        value: 'dashicons-tickets',
      },
      {
        name: 'dashicons dashicons-nametag',
        value: 'dashicons-nametag',
      },
      {
        name: 'dashicons dashicons-clipboard',
        value: 'dashicons-clipboard',
      },
      {
        name: 'dashicons dashicons-heart',
        value: 'dashicons-heart',
      },
      {
        name: 'dashicons dashicons-megaphone',
        value: 'dashicons-megaphone',
      },
      {
        name: 'dashicons dashicons-schedule',
        value: 'dashicons-schedule',
      },
      {
        name: 'dashicons dashicons-wordpress',
        value: 'dashicons-wordpress',
      },
      {
        name: 'dashicons dashicons-wordpress-alt',
        value: 'dashicons-wordpress-alt',
      },
      {
        name: 'dashicons dashicons-pressthis',
        value: 'dashicons-pressthis',
      },
      {
        name: 'dashicons dashicons-update',
        value: 'dashicons-update',
      },
      {
        name: 'dashicons dashicons-screenoptions',
        value: 'dashicons-screenoptions',
      },
      {
        name: 'dashicons dashicons-info',
        value: 'dashicons-info',
      },
      {
        name: 'dashicons dashicons-cart',
        value: 'dashicons-cart',
      },
      {
        name: 'dashicons dashicons-feedback',
        value: 'dashicons-feedback',
      },
      {
        name: 'dashicons dashicons-cloud',
        value: 'dashicons-cloud',
      },
      {
        name: 'dashicons dashicons-translation',
        value: 'dashicons-translation',
      },
      {
        name: 'dashicons dashicons-tag',
        value: 'dashicons-tag',
      },
      {
        name: 'dashicons dashicons-category',
        value: 'dashicons-category',
      },
      {
        name: 'dashicons dashicons-archive',
        value: 'dashicons-archive',
      },
      {
        name: 'dashicons dashicons-tagcloud',
        value: 'dashicons-tagcloud',
      },
      {
        name: 'dashicons dashicons-text',
        value: 'dashicons-text',
      },
      {
        name: 'dashicons dashicons-yes',
        value: 'dashicons-yes',
      },
      {
        name: 'dashicons dashicons-no',
        value: 'dashicons-no',
      },
      {
        name: 'dashicons dashicons-no-alt',
        value: 'dashicons-no-alt',
      },
      {
        name: 'dashicons dashicons-plus',
        value: 'dashicons-plus',
      },
      {
        name: 'dashicons dashicons-plus-alt',
        value: 'dashicons-plus-alt',
      },
      {
        name: 'dashicons dashicons-minus',
        value: 'dashicons-minus',
      },
      {
        name: 'dashicons dashicons-dismiss',
        value: 'dashicons-dismiss',
      },
      {
        name: 'dashicons dashicons-marker',
        value: 'dashicons-marker',
      },
      {
        name: 'dashicons dashicons-star-filled',
        value: 'dashicons-star-filled',
      },
      {
        name: 'dashicons dashicons-star-half',
        value: 'dashicons-star-half',
      },
      {
        name: 'dashicons dashicons-star-empty',
        value: 'dashicons-star-empty',
      },
      {
        name: 'dashicons dashicons-flag',
        value: 'dashicons-flag',
      },
      {
        name: 'dashicons dashicons-warning',
        value: 'dashicons-warning',
      },
      {
        name: 'dashicons dashicons-location',
        value: 'dashicons-location',
      },
      {
        name: 'dashicons dashicons-location-alt',
        value: 'dashicons-location-alt',
      },
      {
        name: 'dashicons dashicons-vault',
        value: 'dashicons-vault',
      },
      {
        name: 'dashicons dashicons-shield',
        value: 'dashicons-shield',
      },
      {
        name: 'dashicons dashicons-shield-alt',
        value: 'dashicons-shield-alt',
      },
      {
        name: 'dashicons dashicons-sos',
        value: 'dashicons-sos',
      },
      {
        name: 'dashicons dashicons-search',
        value: 'dashicons-search',
      },
      {
        name: 'dashicons dashicons-slides',
        value: 'dashicons-slides',
      },
      {
        name: 'dashicons dashicons-analytics',
        value: 'dashicons-analytics',
      },
      {
        name: 'dashicons dashicons-chart-pie',
        value: 'dashicons-chart-pie',
      },
      {
        name: 'dashicons dashicons-chart-bar',
        value: 'dashicons-chart-bar',
      },
      {
        name: 'dashicons dashicons-chart-line',
        value: 'dashicons-chart-line',
      },
      {
        name: 'dashicons dashicons-chart-area',
        value: 'dashicons-chart-area',
      },
      {
        name: 'dashicons dashicons-groups',
        value: 'dashicons-groups',
      },
      {
        name: 'dashicons dashicons-businessman',
        value: 'dashicons-businessman',
      },
      {
        name: 'dashicons dashicons-id',
        value: 'dashicons-id',
      },
      {
        name: 'dashicons dashicons-id-alt',
        value: 'dashicons-id-alt',
      },
      {
        name: 'dashicons dashicons-products',
        value: 'dashicons-products',
      },
      {
        name: 'dashicons dashicons-awards',
        value: 'dashicons-awards',
      },
      {
        name: 'dashicons dashicons-forms',
        value: 'dashicons-forms',
      },
      {
        name: 'dashicons dashicons-testimonial',
        value: 'dashicons-testimonial',
      },
      {
        name: 'dashicons dashicons-portfolio',
        value: 'dashicons-portfolio',
      },
      {
        name: 'dashicons dashicons-book',
        value: 'dashicons-book',
      },
      {
        name: 'dashicons dashicons-book-alt',
        value: 'dashicons-book-alt',
      },
      {
        name: 'dashicons dashicons-download',
        value: 'dashicons-download',
      },
      {
        name: 'dashicons dashicons-upload',
        value: 'dashicons-upload',
      },
      {
        name: 'dashicons dashicons-backup',
        value: 'dashicons-backup',
      },
      {
        name: 'dashicons dashicons-clock',
        value: 'dashicons-clock',
      },
      {
        name: 'dashicons dashicons-lightbulb',
        value: 'dashicons-lightbulb',
      },
      {
        name: 'dashicons dashicons-microphone',
        value: 'dashicons-microphone',
      },
      {
        name: 'dashicons dashicons-desktop',
        value: 'dashicons-desktop',
      },
      {
        name: 'dashicons dashicons-laptop',
        value: 'dashicons-laptop',
      },
      {
        name: 'dashicons dashicons-tablet',
        value: 'dashicons-tablet',
      },
      {
        name: 'dashicons dashicons-smartphone',
        value: 'dashicons-smartphone',
      },
      {
        name: 'dashicons dashicons-phone',
        value: 'dashicons-phone',
      },
      {
        name: 'dashicons dashicons-index-card',
        value: 'dashicons-index-card',
      },
      {
        name: 'dashicons dashicons-carrot',
        value: 'dashicons-carrot',
      },
      {
        name: 'dashicons dashicons-building',
        value: 'dashicons-building',
      },
      {
        name: 'dashicons dashicons-store',
        value: 'dashicons-store',
      },
      {
        name: 'dashicons dashicons-album',
        value: 'dashicons-album',
      },
      {
        name: 'dashicons dashicons-palmtree',
        value: 'dashicons-palmtree',
      },
      {
        name: 'dashicons dashicons-tickets-alt',
        value: 'dashicons-tickets-alt',
      },
      {
        name: 'dashicons dashicons-money',
        value: 'dashicons-money',
      },
      {
        name: 'dashicons dashicons-smiley',
        value: 'dashicons-smiley',
      },
      {
        name: 'dashicons dashicons-thumbs-up',
        value: 'dashicons-thumbs-up',
      },
      {
        name: 'dashicons dashicons-thumbs-down',
        value: 'dashicons-thumbs-down',
      },
      {
        name: 'dashicons dashicons-layout',
        value: 'dashicons-layout',
      },
      {
        name: 'dashicons dashicons-paperclip',
        value: 'dashicons-paperclip',
      },
    ],
    value: 'dashicons-admin-post',
  },
];

export default class PostTypeBuilder extends Component {
  constructor(props) {
    super(props);
    this.state = {
      preValue: REUSEB_ADMIN.UPDATED_POST_TYPES
        ? JSON.parse(REUSEB_ADMIN.UPDATED_POST_TYPES)
        : {},
    };
  }
  render() {
    const getUpdatedFields = data => {
      const newData = {};
      fields.forEach(field => {
        const id = field.id.replace('PostTypeBuilder__', '');
        if (data[id] === undefined) {
          newData[id] = field.value;
        } else {
          newData[id] = data[id];
        }
      });
      document.getElementById('reuseb_post_types_data').value = JSON.stringify(
        newData
      );
    };
    const reuseFormOption = {
      reuseFormId: 'TexanomySettings',
      fields,
      getUpdatedFields,
      errorMessages: {},
      preValue: this.state.preValue,
    };
    return (
      <div>
        <ReuseForm {...reuseFormOption} />
      </div>
    );
  }
}

const documentRoot = document.getElementById('reuseb_post_type_builder');
if (documentRoot) {
  render(<PostTypeBuilder />, documentRoot);
}
