<?php
//  ------------------------------------------------------------------------ //
//                     Document Management System                            //
//                   Written By:  Brian E. Reifsnyder                        //
//                                                                           //
//                See License.txt for copyright information.                 //
// ------------------------------------------------------------------------- //

// defines.php


// Object Types
define('FILE',0);
define('FOLDER',1);
define('INBOXEMPTY',2);
define('INBOXFULL',3);
define('DOCLINK',4);                    // Used for routed documents only....don't use, replace with ROUTEDDOC
define('ROUTEDDOC',4);                  // Replaces DOCLINK
define('DISKDIR',5);            //  Imports server directory into DMS.  One time scan only.
define('DISKDIR_RO_REFRESH',6); //  Re-scans server directory on every access.  Is Read-Only from within DMS.
define('FILELINK',8);                  // Link to documents...created by admins
define('FOLDERLINK',9);                // Link to folders...created by admins
define('LIFECYCLE',20);
define('LIFECYCLE_STAGE',21);
define('PERMISSION',30);
define('WEBPAGE',40);

define('SP_DMS_FILE',50);
define('SP_DMS_FOLDER',51);

define('WP_MEDIA_FILE',60);
define('WP_MEDIA_FOLDER',61);

define('DIR_FILE',70);
define('DIR_DIR',71);

define('DIR_FILE_RO',75);

define ('AFC_FOLDER_NAME',200);            //  Folder name for automatic folder creation.  obj_owner is the user id.

define('ROOTFOLDER',250);

// Object Status
define('NORMAL',0);
define('CHECKEDOUT',1);
define('DELETED',2);
define('PURGED_FS',3);  // Purged, Files Saved
define('PURGED_FD',4);  // Purged, Files Deleted

// Document Purge Levels
define('FLAGGING',0);  // Only the status flag is changed
define('FILES',1);     // The status flag is changed and the files are deleted
define('TOTAL',2);     // All database entries and files are deleted (No auditing)

// Permissions
define('NONE',0);
define('BROWSE',1);
define('READONLY',2);
define('EDIT',3);
define('OWNER',4);
//define('DENY',99);

// Search Parameters
define('IS',1);
define('CONTAINS',2);
define('STARTSWITH',3);
define('ISANYOF',4);
define('ISALLOF',5);

// Version Changes
define('SAME',1);
define('INCSUB',2);
define('INCMINOR',3);
define('INCMAJOR',4);

// dms_object_versions file_location types
define('DMS',10);
define('DIR',20);

// dms_object_misc data types
define('PATH',1);
define('URL',2);
define('FOLDER_AUTO_LIFECYCLE_NUM',11);
define('FLAGS',20);
define('PERMS_GROUP',40);
define('SP_DMS_PARENT_FOLDER_ID',50);


// Job Server

// Job types
define('FTS_INDEX',0);
define('OBJ_DELETION',1);
define('PERM_CHANGE',2);
define('EXTERN_PUB',3);
define('EXEC_SCRIPT',4);
define('EXPIRE_DOCS',5);
define('PURGE_FOLDER',6);

// Scheduling
define('ON',0);
define('AT',0);
define('EVERY',1);

define('DAY',0);
define('MONDAY',1);
define('TUESDAY',2);
define('WEDNESDAY',3);
define('THURSDAY',4);
define('FRIDAY',5);
define('SATURDAY',6);
define('SUNDAY',7);
?>
