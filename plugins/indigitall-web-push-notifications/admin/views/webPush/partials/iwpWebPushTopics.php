<?php

	$deleteIcon = IWP_ADMIN_URL . 'images/delete-icon.svg';
	$editIcon = IWP_ADMIN_URL . 'images/edit-icon.svg';
	$leftPagination = IWP_ADMIN_URL . 'images/left-chevron-icon.svg';
	$rightPagination = IWP_ADMIN_URL . 'images/right-chevron-icon.svg';

	$defaultColor = '#8db8ff'; // Definimos un color predeterminado por si acaso
	$topicsColor = isset($topicsColor) ? $topicsColor : $defaultColor;
	$topicsStatus = (isset($topicsStatus) && $topicsStatus) ? ' checked ' : '';
	$topicsContainerClass = (!empty($topicsStatus)) ? '' : ' iwp-hide ';
	$topics = isset($topics) ? $topics : array();

	$showError = (isset($showError) && $showError) ? '' : ' iwp-hide ';


	$webPushTopicsTitleLabel 		 = __('Groups of <b>interest</b>', 'iwp-text-domain');
	$webPushTopicsSubtitleLabel 	 = __('Send notifications filtering through users who have visited a specific URL, by the type of content they want to receive, or by specific user group, among other possibilities.', 'iwp-text-domain');
	$webPushTopicsStatusLabelLabel 	 = __('Send stakeholder survey to your users', 'iwp-text-domain');
	$webPushTopicsStatusTipLabel 	 = __('It is the main color of the pop up that will appear to the user to select the groups of interest', 'iwp-text-domain');
	$webPushTopicsInterestGroupLabel = __('Interest group', 'iwp-text-domain');
	$webPushTopicsCodeLabel 		 = __('Code', 'iwp-text-domain');
	$webPushTopicsDeleteLabel 		 = __('', 'iwp-text-domain');
	$webPushTopicsAddGroupLabel 	 = __('Add group', 'iwp-text-domain');

	$webPushTopicsColorLabel		 = __('Theme color', 'iwp-text-domain');
	$webPushTopicsColorTip			 = __('It is the color of the pop up that will appear to the user to select the groups of interest', 'iwp-text-domain');

	$errorMessage = __("Error getting your interest groups. Please reload the page and if the error persists, <a href='https://iurny.com/en/contact-us/#contact-form' target='_blank'>contact us</a>",  'iwp-text-domain');
	$emptyList 	  = __('There are no interest groups defined',  'iwp-text-domain');

?>
<div id="iwp-admin-topic-error-box" class="iwp-admin-error-box <?php echo($showError); ?>"><?php echo($errorMessage); ?></div>
<div class="iwp-admin-webPush-topics">
	<div class="iwp-admin-webPush-topics-title"><?php echo($webPushTopicsTitleLabel); ?></div>
	<div class="iwp-admin-webPush-topics-subtitle"><?php echo($webPushTopicsSubtitleLabel); ?></div>
	<label id="webPushTopicsStatusContainer" class="iwp-checkbox-container iwp-admin-webPush-topics-status-container" for="webPushTopicsStatus">
		<input type="checkbox" id="webPushTopicsStatus" name="webPushTopicsStatus" <?php echo($topicsStatus); ?>>
		<i class="iwp-checkbox checked"></i>
		<i class="iwp-checkbox unchecked"></i>
		<span class="iwp-admin-webPush-topics-status-label"><?php echo($webPushTopicsStatusLabelLabel); ?></span>
	</label>

	<label class="iwp-admin-webPush-topics-color-container" for="iwpTopicsColor">
		<div class="iwp-admin-webPush-topics-color-label-container">
			<div class="iwp-admin-webPush-topics-color-label"><?php echo($webPushTopicsColorLabel); ?></div>
			<i class="iwp-question-icon" title="<?php echo($webPushTopicsColorTip); ?>"></i>
		</div>
		<input name="iwpTopicsColor" id="iwpTopicsColor" type="color" class="iwp-admin-webPush-topics-color" value="<?php echo($topicsColor); ?>" />
	</label>

	<div id="iwpTopicsContainer" class="iwp-admin-webPush-topics-container <?php echo($topicsContainerClass); ?>">
		<table id="webPushTopicsTable" class="iwp-admin-webPush-topics-table">
			<thead>
				<tr>
					<td class="iwp-admin-webPush-topics-table-name-col"><?php echo($webPushTopicsInterestGroupLabel); ?></td>
					<td class="iwp-admin-webPush-topics-table-code-col"><?php echo($webPushTopicsCodeLabel); ?></td>
					<td class="iwp-admin-webPush-topics-table-action-col"><?php echo($webPushTopicsDeleteLabel); ?></td>
				</tr>
			</thead>
			<tbody id="webPushTopicsTableBody">
			<?php
				foreach ($topics as $topic) {
					?>
					<tr class="iwp-admin-webPush-topics-table-item">
						<td class="iwp-admin-webPush-topics-table-name-col">
							<input class="iwp-admin-webPush-topics-item-name" type="text"
								   value="<?php echo(htmlentities($topic->getName(), ENT_QUOTES)); ?>" data-id="<?php echo($topic->getId()); ?>">
						</td>
						<td class="iwp-admin-webPush-topics-table-code-col">
							<div class="iwp-admin-webPush-topics-item-code"><?php echo($topic->getCode()); ?></div>
						</td>
						<td class="iwp-admin-webPush-topics-table-action-col">
							<div class="iwp-admin-webPush-topics-table-action-col-content">
								<img class="iwp-admin-webPush-topics-item-edit" src="<?php echo($editIcon); ?>"
									 alt="">
								<img class="iwp-admin-webPush-topics-item-delete" src="<?php echo($deleteIcon); ?>"
									 alt="" data-id="<?php echo($topic->getId()); ?>">
							</div>
						</td>
					</tr>
					<?php
				}
				if (empty($topics)) {
					?>
					<tr class="iwp-admin-webPush-topics-table-item empty-list">
						<td colspan="3"><?php echo($emptyList); ?></td>
					</tr>
					<?php
				}
			?>
			</tbody>
		</table>
		<div class="iwp-admin-webPush-topics-footer">
			<button id="iwpAdminWebPushTopicCreate" class="iwp-btn iwp-btn-green" type="button"><?php echo($webPushTopicsAddGroupLabel); ?></button>
		</div>
	</div>
</div>