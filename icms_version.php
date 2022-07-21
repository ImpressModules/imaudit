<?php
/**
* imAudit version infomation
*
* This file holds the configuration information of this module
*
* @copyright	The ImpressCMS Project
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @since		1.0
* @author		marcan <marcan@impresscms.org>
* @package		imaudit
* @version		$Id$
*/

if (!defined("ICMS_ROOT_PATH")) die("ICMS root path not defined");

/**  General Information  */
$modversion = array(
  'name'=> _MI_IMAUDIT_MD_NAME,
  'version'=> 1.0,
  'description'=> _MI_IMAUDIT_MD_DESC,
  'author'=> "marcan",
  'credits'=> "INBOX International inc.",
  'help'=> "",
  'license'=> "GNU General Public License (GPL)",
  'official'=> 0,
  'dirname'=> basename( dirname( __FILE__ ) ),

/**  Images information  */
  'iconsmall'=> "images/icon_small.png",
  'iconbig'=> "images/icon_big.png",
  'image'=> "images/icon_big.png", /* for backward compatibility */

/**  Development information */
  'status_version'=> "1.0",
  'status'=> "Beta",
  'date'=> "Unreleased",
  'author_word'=> "",

/** Contributors */
  'developer_website_url' => "http://www.impresscms.org",
  'developer_website_name' => "The ImpressCMS Project",
  'developer_email' => "marcan@impresscms.org");

$modversion['people']['developers'][] = "[url=http://community.impresscms.org/userinfo.php?uid=168]marcan[/url]";
//$modversion['people']['testers'][] = "";
//$modversion['people']['translators'][] = "";
//$modversion['people']['documenters'][] = "";
//$modversion['people']['other'][] = "";

/** Manual */
$modversion['manual']['wiki'][] = "<a href='http://wiki.impresscms.org/index.php?title=imAudit' target='_blank'>English</a>";

$modversion['warning'] = _CO_ICMS_WARNING_BETA;

/** Administrative information */
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = "admin/index.php";
$modversion['adminmenu'] = "admin/menu.php";

/** Database information */
$modversion['object_items'][1] = 'changeset';
$modversion['object_items'][] = 'review';
$modversion['object_items'][] = 'branch';
$modversion["tables"] = icms_getTablesArray($modversion['dirname'], $modversion['object_items']);

/** Install and update informations */
$modversion['onInstall'] = "include/onupdate.inc.php";
$modversion['onUpdate'] = "include/onupdate.inc.php";

/** Search information */
$modversion['hasSearch'] = 1;
$modversion['search'] = array (
  'file' => "include/search.inc.php",
  'func' => "imaudit_search");

/** Menu information */
$modversion['hasMain'] = 1;

/** Blocks information */
/** To come soon in imBuilding...
$modversion['blocks'][1] = array(
  'file' => 'post_recent.php',
  'name' => _MI_IMAUDIT_POSTRECENT,
  'description' => _MI_IMAUDIT_POSTRECENTDSC,
  'show_func' => 'imaudit_post_recent_show',
  'edit_func' => 'imaudit_post_recent_edit',
  'options' => '5',
  'template' => 'imaudit_post_recent.html');

$modversion['blocks'][] = array(
  'file' => 'post_by_month.php',
  'name' => _MI_IMAUDIT_POSTBYMONTH,
  'description' => _MI_IMAUDIT_POSTBYMONTHDSC,
  'show_func' => 'imaudit_post_by_month_show',
  'edit_func' => 'imaudit_post_by_month_edit',
  'options' => '',
  'template' => 'imaudit_post_by_month.html');
*/

/** Templates information */
$modversion['templates'][1] = array(
  'file' => 'imaudit_header.html',
  'description' => 'Module Header');

$modversion['templates'][] = array(
  'file' => 'imaudit_footer.html',
  'description' => 'Module Footer');

$modversion['templates'][]= array(
  'file' => 'imaudit_admin_changeset.html',
  'description' => 'changeset Admin Index');

$modversion['templates'][]= array(
  'file' => 'imaudit_changeset.html',
  'description' => 'changeset Index');

$modversion['templates'][]= array(
  'file' => 'imaudit_admin_review.html',
  'description' => 'review Admin Index');

$modversion['templates'][]= array(
  'file' => 'imaudit_review.html',
  'description' => 'review Index');

$modversion['templates'][]= array(
  'file' => 'imaudit_admin_branch.html',
  'description' => 'branch Index');

$modversion['templates'][]= array(
  'file' => 'imaudit_reviewing.html',
  'description' => 'reviewing Index');


/** Preferences information */
// Retrieve the group user list, because the automatic group_multi config formtype does not include Anonymous group :-(
$member_handler =& xoops_getHandler('member');
$groups_array = $member_handler->getGroupList();
foreach($groups_array as $k=>$v) {
	$select_groups_options[$v] = $k;
}

$modversion['config'][1] = array(
  'name' => 'reviewer_group',
  'title' => '_MI_IMAUDIT_REVIEWERGR',
  'description' => '_MI_IMAUDIT_REVIEWERGRDSC',
  'formtype' => 'select_multi',
  'valuetype' => 'array',
  'options' => $select_groups_options,
  'default' =>  '1');

/** Comments information */
$modversion['hasComments'] = 1;

$modversion['comments'] = array(
  'itemName' => 'post_id',
  'pageName' => 'post.php',
  /* Comment callback functions */
  'callbackFile' => 'include/comment.inc.php',
  'callback' => array(
    'approve' => 'imaudit_com_approve',
    'update' => 'imaudit_com_update')
    );

/** Notification information */
/** To come soon in imBuilding...
$modversion['hasNotification'] = 1;

$modversion['notification'] = array (
  'lookup_file' => 'include/notification.inc.php',
  'lookup_func' => 'imaudit_notify_iteminfo');

$modversion['notification']['category'][1] = array (
  'name' => 'global',
  'title' => _MI_IMAUDIT_GLOBAL_NOTIFY,
  'description' => _MI_IMAUDIT_GLOBAL_NOTIFY_DSC,
  'subscribe_from' => array('index.php', 'post.php'));

$modversion['notification']['event'][1] = array(
  'name' => 'post_published',
  'category'=> 'global',
  'title'=> _MI_IMAUDIT_GLOBAL_POST_PUBLISHED_NOTIFY,
  'caption'=> _MI_IMAUDIT_GLOBAL_POST_PUBLISHED_NOTIFY_CAP,
  'description'=> _MI_IMAUDIT_GLOBAL_POST_PUBLISHED_NOTIFY_DSC,
  'mail_template'=> 'global_post_published',
  'mail_subject'=> _MI_IMAUDIT_GLOBAL_POST_PUBLISHED_NOTIFY_SBJ);
*/
?>