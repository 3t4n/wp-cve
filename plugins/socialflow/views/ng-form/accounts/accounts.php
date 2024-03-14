<?php
/**
 * Template for displaying options page updated notice
 *
 * @package SocialFlow
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit( 'No direct script access allowed' );
} ?>

<div class="nano">
	<div class="nano-content">
		<ul class="sf-accounts-list">
			<li 
				class="sf-accounts-list" 
				ng-repeat="account in $ctrl.accounts track by $index" 
				ng-class="account.type"
				ng-if="account.valid"
			>
				<input 
					type="checkbox" 
					ng-model="account.send"
					ng-change="$ctrl.checkSendList( account , account.type)"
					name="{{ $ctrl.getFieldName( account ) }}"
					id="{{ $ctrl.getFieldId( account ) }}"
				>
				<label for="{{ $ctrl.getFieldId( account ) }}">
					{{ account.name }}
				</label>
			</li>
		</ul>
	</div>
</div>
