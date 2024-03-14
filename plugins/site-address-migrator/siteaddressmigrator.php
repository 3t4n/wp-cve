<?php
/*
Plugin Name: Site Address Migrator
Plugin URI: https://membershipworks.com/wordpress-site-address-migrator/
Description: Updates urls in pages, posts, comments, descriptions, widgets and options when Site Address (Site URL) is changed.
Version: 2.0
Author: MembershipWorks
Author URI: https://membershipworks.com
License: GPL2
*/

/*  Copyright 2013-2016 SOURCEFOUND INC.  (email : info@sourcefound.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

function sf_mgr_admin_init() {
	register_setting('sf_mgr_admin_group','sf_mgr','sf_mgr_validate');
}

function sf_mgr_admin_menu() {
	add_options_page('Site Address Updater','Site Address Updater','manage_options','sf_mgr_options','sf_mgr_options');
}

if (is_admin()) {
	add_action('admin_menu','sf_mgr_admin_menu');
	add_action('admin_init','sf_mgr_admin_init');
	add_action('update_option_siteurl','sf_mgr_update_siteurl',20,2);
}

function sf_mgr_options() {
	if (!current_user_can('manage_options'))
		wp_die(__('You do not have sufficient permissions to access this page.'));
	echo '<div class="wrap"><h1>Site Address Manual Updater</h1>'
		.'<form action="options.php" method="post">';
	settings_fields("sf_mgr_admin_group");
	$opt=get_option('sf_mgr');
	echo '<table class="form-table">'
		.'<tr valign="top"><th scope="row">Old site address</th><td><input type="text" name="sf_mgr[old]" value="'.(empty($opt['old'])?'':$opt['old']).'" /></td></tr>'
		.'<tr valign="top"><th scope="row">New site address</th><td><input type="text" name="sf_mgr[new]" value="'.(empty($opt['new'])?'':$opt['new']).'" /></td></tr>'
		.'</table>'
		.'<p class="submit"><input type="submit" name="submit" id="submit" class="button-primary" value="Update"></p>'
		.(empty($opt['sta'])?'':('<p>Last manual update: '.$opt['sta'].'</p>'))
		.'</form>'
		.'</div>';
}

function sf_mgr_replace($doc,$ndl,$rpl) { // $doc=haystack, $ndl=needle, $rpl=replacement
	foreach ($doc as $key=>$val)
		if (is_string($val)) {
			if (($tmp=preg_replace($ndl,$rpl,$val))!=$val) {
				if (is_object($doc)) 
					$doc->$key=$tmp;
				else if (is_array($doc))
					$doc[$key]=$tmp;
			}
		} else if (is_array($val)||is_object($val)) {
			if (($tmp=sf_mgr_replace($val,$ndl,$rpl))!=$val) {
				if (is_object($doc))
					$doc->$key=$tmp;
				else if (is_array($doc))
					$doc[$key]=$tmp;
			}
		}
	return $doc;
}

function sf_mgr_update_table($tbl,$idn,$whr,$var,$ndl,$rpl,$ser) { // $tbl=table name, $idn=id name (id must be integer), $whr=mysql WHERE, $var=variables (array), $ndl=needle, $rpl=replacement, $ser=do check if data serialized
	global $wpdb;
	$idx=$wpdb->get_col("SELECT $idn FROM $tbl $whr");
	foreach ($idx as $id) {
		$doc=$wpdb->get_row("SELECT ".implode(',',$var)." FROM $tbl WHERE $idn=$id",ARRAY_A);
		$dat=array();
		foreach ($var as $x) {
			if ($ser&&is_serialized($doc[$x]))
				$dat[$x]=serialize(sf_mgr_replace(unserialize($doc[$x]),$ndl,$rpl));
			else if (($tmp=preg_replace($ndl,$rpl,$doc[$x]))!=$doc[$x]) 
				$dat[$x]=$tmp;
		}
		if (count($dat))
			$wpdb->update($tbl,$dat,array($idn=>intval($id)));
	}
}

function sf_mgr_update_siteurl($oldurl,$newurl) {
	global $wpdb;
	if (substr($oldurl,-1)!=substr($newurl,-1)) {// normalize trailing slash
		if (substr($newurl,-1)=='/')
			$newurl=substr($newurl,0,-1);
		else if (substr($oldurl,-1)=='/')
			$oldurl=substr($oldurl,0,-1);
	}
	if ($oldurl!=$newurl) {
		$olddmn=preg_replace('/^http:|^https:/','',$oldurl);
		$newdmn=preg_replace('/^http:|^https:/','',$newurl);
		$ndl=array('/'.preg_quote($oldurl,'/').'/','/'.preg_quote($olddmn,'/').'/');
		$rpl=array($newurl,$newdmn);
		// posts & pages
		sf_mgr_update_table($wpdb->posts,'ID','',array('post_excerpt','post_content','post_content_filtered'),$ndl,$rpl,false);
		// GUIDs
		//sf_mgr_update_table($wpdb->posts,'ID','',array('guid'),$ndl,$rpl,false);
		// post & page options
		sf_mgr_update_table($wpdb->postmeta,'meta_id','',array('meta_value'),$ndl,$rpl,true);		
		// comments
		sf_mgr_update_table($wpdb->comments,'comment_ID','',array('comment_content'),$ndl,$rpl,false);
		// comment meta
		sf_mgr_update_table($wpdb->commentmeta,'meta_id','',array('meta_value'),$ndl,$rpl,true);		
		// category & tag description
		sf_mgr_update_table($wpdb->term_taxonomy,'term_taxonomy_id','',array('description'),$ndl,$rpl,false);
		// user description
		sf_mgr_update_table($wpdb->usermeta,'umeta_id',"WHERE meta_key='description'",array('meta_value'),$ndl,$rpl,false);
		// user website
		sf_mgr_update_table($wpdb->users,'ID','',array('user_url'),$ndl,$rpl,false);
		// wp & widget options
		sf_mgr_update_table($wpdb->options,'option_id',"WHERE option_name<>'siteurl' AND option_name<>'home'",array('option_value'),$ndl,$rpl,true);
	}
	return $newurl;
}

function sf_mgr_validate($in) {
	if (!empty($in['old'])&&!empty($in['new'])) {
		sf_mgr_update_siteurl($in['old'],$in['new']);
		$in['sta']='Replaced all instances of '.$in['old'].' with '.$in['new'].' on '.date('M-d-Y G:i');
		$in['old']='';
	}
	return $in;
}

?>