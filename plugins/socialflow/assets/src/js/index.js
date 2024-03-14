'use strict';

import FormInPopup          from './ng-post-form/form-in-popup.component';
import ComposeForm          from './ng-post-form/compose-form.component';

import Stats                from './ng-post-form/compose-form/stats.component';
import Errors               from './ng-post-form/compose-form/errors.component';
import GlobalSettings       from './ng-post-form/compose-form/global-settings.component';
import ComposeMedia         from './ng-post-form/compose-form/compose-media.component';

import AccountsList         from './ng-post-form/compose-form/accounts/accounts.component';

import SocialTabs           from './ng-post-form/compose-form/social-tabs/social-tabs.component';
import SocialTabsList       from './ng-post-form/compose-form/social-tabs/social-tabs-list.component';

import MessagesList         from './ng-post-form/compose-form/social-tabs/messages-list/messages-list.component';

import Message              from './ng-post-form/compose-form/social-tabs/messages-list/message/message.component';
import MessageTwitter       from './ng-post-form/compose-form/social-tabs/messages-list/message/message-twitter.component';
import MessagePinterest     from './ng-post-form/compose-form/social-tabs/messages-list/message/message-pinterest.component';

import MessageAttachments   from './ng-post-form/compose-form/social-tabs/messages-list/message/attachments/attachments.component';

import DatePicker           from './ng-post-form/compose-form/social-tabs/messages-list/settings/datepicker.component';
import AdvancedSettingsList from './ng-post-form/compose-form/social-tabs/messages-list/settings/settings-list.component';
import AdvancedSetting      from './ng-post-form/compose-form/social-tabs/messages-list/settings/setting.component';

import HttpService          from './ng-post-form/services/http.service';
import WPMediaFrameService  from './ng-post-form/services/wp-media.service';
import PostAttachments      from './ng-post-form/services/post-attachments.service';
import CommonService        from './ng-post-form/services/common.service';
import FieldService         from './ng-post-form/services/field.service';
import CacheService         from './ng-post-form/services/cache.service';
import AccountsService      from './ng-post-form/services/accounts.service';


angular.module( 'sfComposeForm', [] )
	.component( 'formInPopup', FormInPopup )
	.component( 'composeForm', ComposeForm )

	.component( 'stats', Stats )
	.component( 'errors', Errors )
	.component( 'globalSettings', GlobalSettings )
	.component( 'composeMedia', ComposeMedia )

	.component( 'accountsList', AccountsList )


	.component( 'socialTabs', SocialTabs )
	.component( 'socialTabsList', SocialTabsList )
	.component( 'messagesList', MessagesList )

	.component( 'message', Message )
	.component( 'messageTwitter', MessageTwitter )
	.component( 'messagePinterest', MessagePinterest )
	.component( 'messageAttachments', MessageAttachments )

	.component( 'datePicker', DatePicker )
	.component( 'advancedSettingsList', AdvancedSettingsList )
	.component( 'advancedSetting', AdvancedSetting )

	.service( 'httpService', HttpService )
	.service( 'WPMediaFrameService', WPMediaFrameService )
	.service( 'postAttachments', PostAttachments )
	.service( 'postAttachments', PostAttachments )
	.service( 'commonService', CommonService )
	.service( 'fieldService', FieldService )
	.service( 'cacheService', CacheService )
	.service( 'accountsService', AccountsService )
;
