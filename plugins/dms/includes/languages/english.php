<?php
//  ------------------------------------------------------------------------ //
//                     Document Management System                            //
//                   Written By:  Brian E. Reifsnyder                        //
//                                                                           //
//                See License.txt for copyright information.                 //
// ------------------------------------------------------------------------- //

// english.php   Language file


//Administration Buttons
define("_DMS_L_ADMINISTRATION","Administration");
define("_DMS_L_AUDITING","Auditing");
define("_DMS_L_GROUP_EDITOR","Group Editor");
define("_DMS_L_STATISTICS","Statistics");
define("_DMS_L_TRANSFER_OWNERSHIP","Transfer Ownership");


//Automated Folder Creation
define("_DMS_L_AUTO_FOLDER_CREATION","Auto Folder Creation");
define("_DMS_L_AUTOMATED_FOLDER_CREATION","Automated User Folder Creation");
define("_DMS_L_AFC_ADD","Add");
define("_DMS_L_AFC_CONFIRM_MANUAL_SCAN","Confirm Manual Scan");
define("_DMS_L_AFC_UPDATE","Update");
define("_DMS_L_AFC_DELETE","Delete");
define("_DMS_L_AFC_CONFIRM_DELETE","Are you sure you want to delete this entry?");
define("_DMS_L_AFC_MANUAL_SCAN","Manual Scan");

//Main Screen and others...
define("_DMS_L_TOP_BREADCRUMB","Top");
define("_DMS_L_IMPORT_DOCUMENT","Import Document");
define("_DMS_L_LIFECYCLES","Lifecycles");
define("_DMS_L_CLOSE_ALL_FOLDERS","Close all open folders");
define("_DMS_L_ITEM","Item:");
define("_DMS_L_STATUS","Status:");
define("_DMS_L_CLOSE_DEL_FOLDER","Close Deleted Folder");
define("_DMS_L_CLOSE_FOLDER","Close Folder");
define("_DMS_L_OPEN_DEL_FOLDER","Open Deleted Folder");
define("_DMS_L_OPEN_FOLDER","Open Folder");
define("_DMS_L_CLOSE_INBOX_EMPTY","Close Inbox (Empty)");
define("_DMS_L_CLOSE_INBOX","Close Inbox (Documents present)");
define("_DMS_L_OPEN_INBOX_EMPTY","Open Inbox (Empty)");
define("_DMS_L_OPEN_INBOX","Open Inbox (Documents present)");
define("_DMS_L_RESTORE","Restore");
define("_DMS_L_RECALL","Recall");
define("_DMS_L_OPTIONS","Options");
define("_DMS_L_DELETED_DOC","Deleted Document");
define("_DMS_L_DOC","Document");
define("_DMS_L_VIEW_DOC","View Document");
define("_DMS_L_CHECKOUT","Checkout");
define("_DMS_L_CHECKIN","Check-in");
define("_DMS_L_ROUTE","Route");
define("_DMS_L_PROMOTE","Promote");
define("_DMS_L_DOC_AVAILABLE","Document is available for checkout.");
define("_DMS_L_DOC_NOT_AVAILABLE","Document is not available for checkout.");
define("_DMS_L_ROUTED_DOC","Routed Document");
define("_DMS_L_VIEW_ROUTED_DOC","View Routed Document");
define("_DMS_L_EMPTY","Empty");
define("_DMS_L_CREATE_DOCUMENT","Create Document");
define("_DMS_L_TITLE","Document Management System");

define("_DMS_L_INBOX","Inbox");
//define("_DMS_L_FOLDER","Folder");

// File Import
define("_DMS_L_FILE_TO_UPLOAD","Please select or enter a file to upload.");
define("_DMS_L_IMPORT_DOC","Import Document");
define("_DMS_L_FILE_NAME","Name");
define("_DMS_L_INITIAL_VERSION","Initial Version");
define("_DMS_L_SELECT_FILE","Select File");
define("_DMS_L_SUBMIT","Submit");
define("_DMS_L_CANCEL","Cancel");
define("_DMS_L_ENTER_DOC_NAME","Please enter a document name");

// Folder New
define("_DMS_L_FOLDER_NAME","Folder Name");
define("_DMS_L_CREATE_FOLDER","Create Folder:");

// Group Editor
//define("_DMS_L_GROUP_EDITOR","Group Editor");
define("_DMS_L_GROUP","Group:");
define("_DMS_L_GROUP_NEW_GROUP_NAME","New Group Name");
define("_DMS_L_GROUP_ALL_USERS","All Users");
define("_DMS_L_GROUP_USERS_IN_GROUP","Users In Group");
define("_DMS_L_GROUP_RENAME","Rename");
define("_DMS_L_GROUP_CHANGE_NAME","Change Group Name");

// Search Options
define("_DMS_L_SEARCH_OPTIONS","Search Options");
define("_DMS_L_EXIT","Exit");
define("_DMS_L_NAME","Name");
define("_DMS_L_OPTION_IS","is");
define("_DMS_L_OPTION_CONTAINS","contains");
define("_DMS_L_OPTION_STARTS","starts with");
define("_DMS_L_PROPERTIES_SEARCH","Properties Search");
define("_DMS_L_SEARCH","Search");
define("_DMS_L_DOCUMENTS","Document(s)");
define("_DMS_L_VERSION","Version");
define("_DMS_L_ACCESS_DENIED","Access Denied");
define("_DMS_L_BECAUSE_AT_LEAST","Because at least ");
define("_DMS_L_DOCS_EXCEED_LIMIT"," documents have been found that match your search parameters, the document for which you are searching for may not have been displayed.   ");
define("_DMS_L_REFINE_PARAMETERS","        You may want to refine your search parameters.");
define("_DMS_L_NO_FILES_FOUND","No files have been found that match your query.");

define("_DMS_L_PS_NOT_APPLICABLE","Not Applicable");
define("_DMS_L_PS_ON","On");
define("_DMS_L_PS_BEFORE","Before");
define("_DMS_L_PS_AFTER","After");
define("_DMS_L_PS_LIMIT","Limit Search to Active Folder and Sub-Folders");

define("_DMS_L_PS_ANYONE","Anyone");

// Life Cycle
define("_DMS_L_LIFECYCLE_TITLE_LIFECYCLE_STAGE_PROPERTIES","Lifecycle Stage Properties");
define("_DMS_L_LIFECYCLE_APPLY","Apply Lifecycle");
define("_DMS_L_NEW","New");
define("_DMS_L_LIFECYCLE_MANAGER","Lifecycles");
define("_DMS_L_LIFECYCLE_MANAGEMENT","Lifecycle Management");
define("_DMS_L_LIFECYCLE_SELECTION","Select Lifecycle and Stage");
define("_DMS_L_DESCRIPTION","Description");
define("_DMS_L_EDIT","Edit");
define("_DMS_L_LIFECYCLE_BUTTON_PROMOTE","Promote");
define("_DMS_L_LIFECYCLE_BUTTON_APPLY","Apply");
define("_DMS_L_LIFECYCLE_PROMOTE_TITLE","Promote Document");
define("_DMS_L_LIFECYCLE_DEMOTE_TITLE","Demote Document");
define("_DMS_L_LIFECYCLE_DEMOTE_SELECT_INSTRUCTION","Demote this document to the following stage or remove this document from the lifecycle");
define("_DMS_L_LIFECYCLE_FILE_NAME","File Name");
//define("_DMS_L_DELETE","Delete");

// lifecycle_manager.php
define("_DMS_L_INVALID_LIFECYCLE_ID","Invalid Lifecycle ID ... Operation Terminated");
define("_DMS_L_DUP_LIFECYCLE_ALERT","A new lifecycle stage already exists.  Please edit this stage before creating any additional stages.");
define("_DMS_L_LIFECYCLE_EDITOR","Lifecycle Editor");
define("_DMS_L_LIFECYCLE_PROPERTIES","Lifecycle Properties");
define("_DMS_L_UPDATE","Update");
define("_DMS_L_LIFECYCLE_STAGES","Lifecycle Stages");
define("_DMS_L_STAGE","Stage");
define("_DMS_L_NEW_UPPER","NEW");

// lifecycle_stage_editor.php
define("_DMS_L_INVALID_LIFECYCLE_STAGE","Invalid Lifecycle Stage...Operation Terminated");
define("_DMS_L_DESTINATION_FOLDER_ALERT","Please select a destination folder.");
define("_DMS_L_LIFECYCLE_STAGE_EDITOR","Lifecycle Stage Editor");
define("_DMS_L_STAGE_NUMBER","Stage Number");
define("_DMS_L_DESTINATION_FOLDER","Destination Folder");

// folder_options.php
define("_DMS_L_CONVERT_TO_INBOX","Convert to Inbox");
define("_DMS_L_CONVERT_TO_FOLDER","Convert to Folder");
define("_DMS_L_PROPERTIES","Properties:");
define("_DMS_L_NAME_DOT","Name:");
define("_DMS_L_UPDATE_PROPERTIES","Update Properties");
define("_DMS_L_INFORMATION","Information:");
define("_DMS_L_AUDIT_LOG","Audit Log");
define("_DMS_L_PROPAGATE_PERMISSIONS","Propagate Permissions");
define("_DMS_L_FOLDER_SUBSCRIPTIONS","Folder Subscriptions");
define("_DMS_L_UPDATE_FOLDER_SUBSCRIPTIONS","Update Folder Subscriptions");
//define("_DMS_L_OPTIONS","Options");

// inc_perms_set.php
define("_DMS_L_PERMISSIONS","Permissions");
define("_DMS_L_OWNER","Owner");
define("_DMS_L_EVERYONE","Everyone:");
define("_DMS_L_NONE","None");
define("_DMS_L_BROWSE","Browse");
define("_DMS_L_READ_ONLY","Read Only");
define("_DMS_L_GROUPS","Groups:");
define("_DMS_L_ADD","Add");
define("_DMS_L_REMOVE","Remove");
define("_DMS_L_READ_ONLY_DOT","Read Only");
define("_DMS_L_EDIT_DOT","Edit:");
define("_DMS_L_USERS","Users:");
define("_DMS_L_UPDATE_PERMISSIONS","Update Permissions");

// audit_log_select_user.php
define("_DMS_L_AUDIT_BY_USER","Audit by User");
define("_DMS_L_SELECT_USER","Select User:");
define("_DMS_L_USER_NAME","User Name");

// audit_log_user.php
//define("_DMS_L_AUDITING","Auditing");
define("_DMS_L_DATE_AND_TIME","Date & Time");
define("_DMS_L_OBJECT_ID","Object ID");

// audit_log_detail.php
define("_DMS_L_DATE_AND_TIME_DOT","Date & Time");
define("_DMS_L_DOC_NAME","Document name");

// file_options.php
define("_DMS_L_CANCEL_CHECKOUT","Cancel Checkout");
define("_DMS_L_LIFECYCLE","Lifecycle");
define("_DMS_L_COPY","Copy");
define("_DMS_L_MOVE","Move");
define("_DMS_L_DEMOTE","Demote");
define("_DMS_L_CHECKED_OUT_BY","Checked-Out By:");
define("_DMS_L_DOC_OWNER","Document Owner");
define("_DMS_L_PERMISSION_LEVEL","Permission Level");
define("_DMS_L_CURRENT_VERSION","Current Version");
define("_DMS_L_MODIFIED","Modified");
define("_DMS_L_CREATED","Created");
define("_DMS_L_SIZE","Size");
define("_DMS_L_NA","N/A");
define("_DMS_L_TEMPLATE_NAME","Template Name:");
define("_DMS_L_VIEW_VERSION_OF_DOC","View a version of this document");
define("_DMS_L_EXPORT","Export");
define("_DMS_L_EMAIL","E-Mail");
define("_DMS_L_REVERT","Revert");
define("_DMS_L_VIEW_OLDER_VERSION","View Older Version");

// link_options.php
define("_DMS_L_ROUTED_BY","Routed By:");
define("_DMS_L_ROUTED","Routed:");
define("_DMS_L_NO_PERMISSION_TO_ACCESS_DOC","You do not have any permission to access this document.");

// link_delete.php
define("_DMS_L_CONFIRM_LINK_DEL","Confirm Rounted Document Deletion");
define("_DMS_L_LINK_NAME","Routed Document Name");

// audit_log_obj.php
define("_DMS_L_USER_ID","User Id");

// file_route.php
define("_DMS_L_UNABLE_TO_ROUTE","An unsuccessful attempt has been made to route the document to the following user(s):");
define("_DMS_L_ROUTE_DOCUMENT","Route Document");

// file_copy.php
define("_DMS_L_FAILURE_TO_COPY","Error:  Failure to copy file.  Either source path or destination path is not accessable.");
define("_DMS_L_COPY_FILE","Copy File");

// file_move.php
define("_DMS_L_MOVE_FILE","Move File");

// lifecycle_apply.php
define("_DMS_L_LIFECYCLE_COFIG_ERROR","The lifecycle you selected has not been properly configured.  Please contact your system administrator.");
define("_DMS_L_APPLY","Apply");
define("_DMS_L_APPLY_LIFECYCLE","Apply Lifecycle");
define("_DMS_L_SELECT_LIFECYCLE","Select Lifecycle");
define("_DMS_L_SELECTION","Selection");

// file_checkout.php
define("_DMS_L_CONFIRM_CHECKOUT","Confirm Checkout of Document:");

// file_checkout_cancel.php
define("_DMS_L_CONFIRM_CANCEL_CHECKOUT","Confirm Document Checkout Cancellation:");

// file_checkin.php
define("_DMS_L_CHECKIN_FILE","Check-in File:");
define("_DMS_L_NEW_VERSION","New Version");
define("_DMS_L_SAME","same");
define("_DMS_L_UNABLE_TO_MOVE","Error:  Unable to move file.");
define("_DMS_L_FILE_INACCESSABLE","Error:  Uploaded File inaccessable.");

// file_email.php
define("_DMS_L_EMAIL_DOC","E-mail Document");
define("_DMS_L_EMAIL_RECIPIENT","E-mail Address of Recipient:");
define("_DMS_L_EMAIL_DOC_SUB","Send");

//  obj_delete.php
define("_DMS_L_CONFIRM","Confirm");
define("_DMS_L_DELETE","Delete");
define("_DMS_L_DELETION","Deletion");
define("_DMS_L_DOCUMENT","Document");
define("_DMS_L_DOCUMENT_NAME","Document Name");
define("_DMS_L_FOLDER","Folder");
define("_DMS_L_ROUTED_DOCUMENT","Routed Document");
define("_DMS_L_ROUTED_DOCUMENT_NAME","Routed Document Name");



//define("_DMS_L_EMPTY","Empty");
define("_DMS_L_CONFIG_INBOX_NOT_EMPTY","The configuration cannot be changed because this inbox has routed documents.");

define("_DMS_L_SET_PAGE","Set Wordpress Page For DMS");
define("_DMS_L_CONFIG","Configuration");

define("_DMS_L_NO_OLDER_VERSION","No older version exists.");

define("_DMS_L_FTS_CONFIG_BUTTON","Configure Full Text Search");


//Install Warnings ... Not in use
define("_DMS_L_IW_CONFIG_FILE","The DMS plugin is unable to write to the config.php file at");
define("_DMS_L_IW_INITIAL_CONFIG","The DMS plugin requires initial configuration.");
define("_DMS_L_IW_REPOSITORY","The DMS plugin is unable to write to the document repository at");


define("_DMS_L_DIAGNOSTICS","Diagnostics");



//Move Folder
define("_DMS_L_MOVE_FOLDER","Move Folder");
define("_DMS_L_MOVE_FOLDER_NAME","Folder Name");

define("_DMS_OPEN_DEL_FOLDER","_DMS_OPEN_DEL_FOLDER");

//Configuration Screen
define("_DMS_L_CONFIG_TITLE","DMS Configuration");
define("_DMS_L_CONFIG_UPGRADE_NOTICE_URL","Upgrade to DMS Pro at ");
define("_DMS_L_CONFIG_UPGRADE_NOTICE","Please upgrade to DMS Pro for this feature.");

define("_DMS_L_CONFIG_ADMIN_CHANGE_PERMS","Only administrators can change permissions");
define("_DMS_L_CONFIG_ADMIN_MANAGE_NOTIFY","Only administrators can manage folder subscriptions");

define("_DMS_L_CONFIG_COMMENTS","Comments");
define("_DMS_L_CONFIG_ENABLE","Enable");
define("_DMS_L_CONFIG_DOCUMENT_PROPERTIES","Document Properties");
define("_DMS_L_CONFIG_PROPERTY","Property");

define("_DMS_L_CONFIG_DOCUMENT_REPOSITORY","Document Repository");
define("_DMS_L_CONFIG_DOCUMENT_STORAGE_PATH","Document Storage Path");

define("_DMS_L_CONFIG_EMAIL_CONFIGURATION","E-Mail Configuration");
define("_DMS_L_CONFIG_FOLDER_SUBSCRIPTIONS","Folder Subscriptions");
define("_DMS_L_CONFIG_FOLDER_SUBSCRIPTIONS_SENDER_EMAIL","Sender E-mail Address");
define("_DMS_L_CONFIG_FOLDER_SUBSCRIPTIONS_SENDER_SUBJECT","Subject Line");

define("_DMS_L_CONFIG_INTERFACE_SETTINGS","Interface Settings");
define("_DMS_L_CONFIG_INTERFACE_SETTINGS_PAGE_TITLE","DMS Page Title");
define("_DMS_L_CONFIG_INTERFACE_SETTINGS_DOCS_PER_PAGE","Documents Displayed Per Page");
define("_DMS_L_CONFIG_INTERFACE_SETTINGS_FRAME_WIDTH","Frame Width");
define("_DMS_L_CONFIG_INTERFACE_SETTINGS_FRAME_HEIGHT","Frame Height");

define("_DMS_L_CONFIG_PERMISSIONS_SYSTEM","Permissions System");
define("_DMS_L_CONFIG_PERMISSIONS_SYSTEM_INHERIT","Inherit Permissions from Parent Directory");
define("_DMS_L_CONFIG_PERMISSIONS_SYSTEM_EVERYONE","Everyone Includes Non-Authenticated Users");

define("_DMS_L_CONFIG_ROUTING","Routing");

define("_DMS_L_CONFIG_SEARCH_CONFIGURATION","Search Configuration");
define("_DMS_L_CONFIG_SEARCH_LIMIT","Search Limit");

define("_DMS_L_CONFIG_DOCUMENTS_PER_PAGE","Documents Per Page");
define("_DMS_L_CONFIG_SEARCH_SUMMARIES","Search Summaries");
define("_DMS_L_CONFIG_THUMBNAILS","Thumbnails");
define("_DMS_L_CONFIG_THUMBNAILS_DISPLAY_GLOBALLY","Display Image Thumbnails Globally");
define("_DMS_L_CONFIG_THUMBNAILS_WIDTH","Width (in pixels)");

define("_DMS_L_CONFIG_VERSION_CONTROL","Version Control");

define("_DMS_L_CONFIG_UPDATE","Update");



//  Statistics
define("_DMS_L_STATISTICS_STATISTICS","Statistics");
define("_DMS_L_STATISTICS_DOCUMENTS","Documents");
define("_DMS_L_STATISTICS_FOLDERS","Folders");
define("_DMS_L_STATISTICS_INBOXES","Inboxes");
define("_DMS_L_STATISTICS_DOC_REPOSITORY","Document Repository");

//  Transfer Owner Permissions
define("_DMS_L_TRANSFER_PERMS_GLOBALLY_TRANSFER_OWNERSHIP_PERMISSIONS","Globally Transfer Ownership Permissions");
define("_DMS_L_TRANSFER_PERMS_OLD_USER_ACCOUNT","Old User Account");
define("_DMS_L_TRANSFER_PERMS_NEW_USER_ACCOUNT","New User Account");
define("_DMS_L_TRANSFER_PERMS_DELETE_USER_PERMS_ON_ALL_DOCUMENTS_AND_FOLDERS","Delete User Permissions On All Documents And Folders");
define("_DMS_L_TRANSFER_PERMS_TRANSFER","Transfer");
define("_DMS_L_TRANSFER_PERMS_EXIT","Exit");



?>
