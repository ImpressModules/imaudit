<?php
/**
* Generating an RSS feed
*
* @copyright	The ImpressCMS Project
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @since		1.0
* @author		marcan <marcan@impresscms.org>
* @package		imaudit
* @version		$Id$
*/

/** Include the module's header for all pages */
include_once 'header.php';
include_once ICMS_ROOT_PATH.'/header.php';

/** To come soon in imBuilding...

$clean_post_uid = isset($_GET['uid']) ? intval($_GET['uid']) : false;

include_once IMAUDIT_ROOT_PATH.'/class/icmsfeed.php';
$imaudit_feed = new IcmsFeed();

$imaudit_feed->title = $xoopsConfig['sitename'] . ' - ' . $xoopsModule->name();
$imaudit_feed->url = XOOPS_URL;
$imaudit_feed->description = $xoopsConfig['slogan'];
$imaudit_feed->language = _LANGCODE;
$imaudit_feed->charset = _CHARSET;
$imaudit_feed->category = $xoopsModule->name();

$imaudit_post_handler = xoops_getModuleHandler('post');
//ImauditPostHandler::getPosts($start = 0, $limit = 0, $post_uid = false, $year = false, $month = false
$postsArray = $imaudit_post_handler->getPosts(0, 10, $clean_post_uid);

foreach($postsArray as $postArray) {
	$imaudit_feed->feeds[] = array (
	  'title' => $postArray['post_title'],
	  'link' => str_replace('&', '&amp;', $postArray['itemUrl']),
	  'description' => htmlspecialchars(str_replace('&', '&amp;', $postArray['post_lead']), ENT_QUOTES),
	  'pubdate' => $postArray['post_published_date_int'],
	  'guid' => str_replace('&', '&amp;', $postArray['itemUrl']),
	);
}

$imaudit_feed->render();
*/
?>