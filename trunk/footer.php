<?php
/**
* Footer page included at the end of each page on user side of the mdoule
*
* @copyright	The ImpressCMS Project
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @since		1.0
* @author		marcan <marcan@impresscms.org>
* @package		imaudit
* @version		$Id$
*/

if (!defined("ICMS_ROOT_PATH")) die("ICMS root path not defined");

$xoopsTpl->assign("imaudit_adminpage", imaudit_getModuleAdminLink());
$xoopsTpl->assign("imaudit_is_admin", $imaudit_isAdmin);
$xoopsTpl->assign('imaudit_url', IMAUDIT_URL);
$xoopsTpl->assign('imaudit_images_url', IMAUDIT_IMAGES_URL);
$xoopsTpl->assign('imaudit_can_review', imaudit_checkPermission('review_add', 'index.php'));
$xoTheme->addStylesheet(IMAUDIT_URL . 'module'.(( defined("_ADM_USE_RTL") && _ADM_USE_RTL )?'_rtl':'').'.css');

include_once(ICMS_ROOT_PATH . '/footer.php');

?>