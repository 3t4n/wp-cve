<?php
/**
 * Main dashboard template
 */
?><div
	id="lastudio-kit-dashboard-page"
	class="lastudio-kit-dashboard-page"
	ref="LaStudioKitDashboardPage"
>

	<div class="lastudio-kit-dashboard-page__body">

		<lastudio-kit-dashboard-alert-list
			:alert-list="alertNoticeList"
		></lastudio-kit-dashboard-alert-list>

		<lastudio-kit-dashboard-before-content
			:config="beforeContentConfig"
		></lastudio-kit-dashboard-before-content>

		<div class="lastudio-kit-dashboard-page__content">

			<lastudio-kit-dashboard-header
				:config="headerConfig"
			></lastudio-kit-dashboard-header>

			<div class="lastudio-kit-dashboard-page__component">

				<lastudio-kit-dashboard-before-component
					:config="beforeComponentConfig"
				></lastudio-kit-dashboard-before-component>

				<component
					:is="pageModule"
					:subpage="subPageModule"
				></component>

				<lastudio-kit-dashboard-after-component
					:config="afterComponentConfig"
				></lastudio-kit-dashboard-after-component>

			</div>

		</div>

		<div
			class="lastudio-kit-dashboard-page__sidebar-container"
			v-if="sidebarVisible"
		>

			<lastudio-kit-dashboard-before-sidebar
				:config="beforeSidebarConfig"
			></lastudio-kit-dashboard-before-sidebar>

			<lastudio-kit-dashboard-sidebar
				:config="sidebarConfig"
				:guide="guideConfig"
				:help-center="helpCenterConfig"
			></lastudio-kit-dashboard-sidebar>

			<lastudio-kit-dashboard-after-sidebar
				:config="afterSidebarConfig"
			></lastudio-kit-dashboard-after-sidebar>

		</div>

	</div>

	<transition name="popup">
		<cx-vui-popup
			class="service-actions-popup"
			v-model="serviceActionsVisible"
			:footer="false"
			body-width="400px"
		>
			<div slot="title">
				<div class="cx-vui-popup__header-label">Service Actions</div>
			</div>
			<div class="service-actions-popup__form" slot="content">
				<cx-vui-select
					size="fullwidth"
					placeholder="Choose Action"
					:prevent-wrap="true"
					:options-list="serviceActionOptions"
					v-model="serviceAction"
				></cx-vui-select>
				<cx-vui-button
					button-style="accent"
					size="mini"
					:loading="serviceActionProcessed"
					@click="executeServiceAction"
				>
					<span slot="label">Go</span>
				</cx-vui-button>
			</div>
		</cx-vui-popup>
	</transition>

</div>
