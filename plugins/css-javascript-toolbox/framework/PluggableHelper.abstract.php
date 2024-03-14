<?php
/**
* 
*/

/**
* 
*/
abstract class CJTPluggableHelper
{
	
	const ACTION_CJT_TEXT_DOMAIN_LOADED = 'cjt-text-domain-loaded';
	
	const ACTION_BLOCK_QUERY_BLOCKS = 'cjt-block-query-blocks';
	
	const FILTER_BLOCK_ASSIGN_PANEL_TABS = 'cjt-block-assign-panel-tabs';
	
	const FILTER_BLOCK_MODEL_CUSTOM_PINS = 'cjt-block-model-custom-pins';
	const FILTER_BLOCK_MODEL_PROPERTIES_META = 'cjt-block-model-properties-meta';
	const FILTER_BLOCK_MODEL_PRE_UPDATE_BLOCK = 'cjt-block-model-pre-update-block';
	const FILTER_BLOCK_MODEL_PRE_UPDATE_BLOCK_PINS = 'cjt-block-model-pre-update-block-pins';
	
	const FILTER_BLOCKS_COUPLING_MODEL_BLOCKS_QUEUE = 'cjt-blocks-coupling-model-blocks-queue';
    
	const FILTER_LOCALIZE_SCRIPT = 'cjt-localize-script';
    
	const ACTION_BLOCK_CUSTOM_POST_TYPES = 'cjt-block-custom-post-types';
	const ACTION_BLOCK_ASSIGN_PANEL = 'cjt-block-assign-panel';
    
	const ACTION_BLOCKS_MANAGER_TOOLBOX_LEFT_BUTTONS = 'cjt-blocks-manager-toolbox-left-buttons';
	const ACTION_BLOCKS_MANAGER_TOOLBOX_ADMIN_TOOLS_TOP = 'cjt-blocks-manager-toolbox-tools-top';
	const ACTION_BLOCKS_MANAGER_TOOLBOX_RIGHT_BUTTONS = 'cjt-blocks-manager-toolbox-right-buttons';
	
	const ACTION_BLOCK_SCREEN_INFO_TOP = 'cjt-block-screen-info-top';
	
	const ACTION_BLOCK_ASSIGN_PANEL_TAB_BOTTOM = 'cjt-block-assign-panel-tab-bottom';
    
    const ACTION_BLOCK_BEFORE_INFO_BAR = 'cjt-block-before-info-bar';
	
	const ACTION_BLOCK_CODE_FILE_TEMPLATE_CREATE_NEW_FILE = 'cjt-block-code-file-template-create-new-file';
    
    const ACTION_BLOCK_TOOLBOX_TEMPLATE_INSIDE_BEFORE_FLAGS = 'cjt-toolbox-template-toolbox-inside-after-flags';
    const ACTION_BLOCK_TOOLBOX_TEMPLATE_INSIDE_AFTER_FLAGS = 'cjt-toolbox-template-toolbox-inside-before-flags';
    
    const ACTION_BLOCKS_COUPLING_INIT_ATTACHER = 'cjt-blocks-coupling-initialize-attacher';
    
    const ACTION_BLOCK_INSIDE_TOOLBOX_TEMPLATES_MENU = 'cjt-block-inside-toolbox-templates-menu';
    const ACTION_BLOCK_INFO_BAR_TOP_END = 'cjt-block-info-bar-top-end';
}