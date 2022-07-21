<?php
/**
* Common functions used by the module
*
* @copyright	The ImpressCMS Project
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @since		1.0
* @author		marcan <marcan@impresscms.org>
* @package		imaudit
* @version		$Id$
*/

if (!defined("ICMS_ROOT_PATH")) die("ICMS root path not defined");

/**
 * Get module admion link
 *
 * @todo to be move in icms core
 *
 * @param string $moduleName dirname of the moodule
 * @return string URL of the admin side of the module
 */

function imaudit_getModuleAdminLink($moduleName='imaudit') {
	global $xoopsModule;
	if (!$moduleName && (isset ($xoopsModule) && is_object($xoopsModule))) {
		$moduleName = $xoopsModule->getVar('dirname');
	}
	$ret = '';
	if ($moduleName) {
		$ret = "<a href='" . ICMS_URL . "/modules/$moduleName/admin/index.php'>" ._MD_IMAUDIT_ADMIN_PAGE . "</a>";
	}
	return $ret;
}

/**
 * @todo to be move in icms core
 */
function imaudit_getModuleName($withLink = true, $forBreadCrumb = false, $moduleName = false) {
	if (!$moduleName) {
		global $xoopsModule;
		$moduleName = $xoopsModule->getVar('dirname');
	}
	$icmsModule = icms_getModuleInfo($moduleName);
	$icmsModuleConfig = icms_getModuleConfig($moduleName);
	if (!isset ($icmsModule)) {
		return '';
	}

	if (!$withLink) {
		return $icmsModule->getVar('name');
	} else {
/*	    $seoMode = smart_getModuleModeSEO($moduleName);
	    if ($seoMode == 'rewrite') {
	    	$seoModuleName = smart_getModuleNameForSEO($moduleName);
	    	$ret = XOOPS_URL . '/' . $seoModuleName . '/';
	    } elseif ($seoMode == 'pathinfo') {
	    	$ret = XOOPS_URL . '/modules/' . $moduleName . '/seo.php/' . $seoModuleName . '/';
	    } else {
			$ret = XOOPS_URL . '/modules/' . $moduleName . '/';
	    }
*/
		$ret = ICMS_URL . '/modules/' . $moduleName . '/';
		return '<a href="' . $ret . '">' . $icmsModule->getVar('name') . '</a>';
	}
}

/**
 * Check specific permission in the module
 *
 * @param str $permission name of the permission
 * @param bool $redirectUrl to what URL shall we redirect the user if pemrission is denied ? FALSE will not redirect user
 * @param str $redirectMsg the message to be use in a redirect message
 */
function imaudit_checkPermission($permission, $redirectUrl=false, $redirectMsg=false) {
	global $xoopsModuleConfig, $xoopsUser;

	if (is_object($xoopsUser)) {
		$user_groups = $xoopsUser->getGroups();
	} else {
		$user_groups = false;
	}

	switch ($permission) {
		case 'review_add':
			if ($user_groups && count(array_intersect($xoopsModuleConfig['reviewer_group'], $user_groups)) > 0) {
				return true;
			} else {
				if ($redirectUrl) {
					redirect_header($redirectUrl, 3, $redirectMsg);
				}
			}
		break;
	}
	return false;
}

/**
 * Get URL of previous page
 *
 * @todo to be moved in ImpressCMS 1.2 core
 *
 * @param string $default default page if previous page is not found
 * @return string previous page URL
 */
function imaudit_getPreviousPage($default=false) {
	global $impresscms;
	if (isset($impresscms->urls['previouspage'])) {
		return $impresscms->urls['previouspage'];
	} elseif($default) {
		return $default;
	} else {
		return ICMS_URL;
	}
}
?>