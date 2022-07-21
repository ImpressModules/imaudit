<?php
/**
* Configuring the amdin side menu for the module
*
* @copyright	The ImpressCMS Project
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @since		1.0
* @author		marcan <marcan@impresscms.org>
* @package		imaudit
* @version		$Id$
*/

$i = -1;

$i++;
$adminmenu[$i]['title'] = _MI_IMAUDIT_CHANGESETS;
$adminmenu[$i]['link'] = 'admin/changeset.php';

$i++;
$adminmenu[$i]['title'] = _MI_IMAUDIT_REVIEWS;
$adminmenu[$i]['link'] = 'admin/review.php';

$i++;
$adminmenu[$i]['title'] = _MI_IMAUDIT_BRANCHS;
$adminmenu[$i]['link'] = 'admin/branch.php';

global $xoopsModule;
if (isset($xoopsModule)) {

	$i = -1;

	$i++;
	$headermenu[$i]['title'] = _PREFERENCES;
	$headermenu[$i]['link'] = '../../system/admin.php?fct=preferences&amp;op=showmod&amp;mod=' . $xoopsModule->getVar('mid');

	$i++;
	$headermenu[$i]['title'] = _CO_ICMS_GOTOMODULE;
	$headermenu[$i]['link'] = ICMS_URL . '/modules/imaudit/';

	$i++;
	$headermenu[$i]['title'] = _CO_ICMS_UPDATE_MODULE;
	$headermenu[$i]['link'] = ICMS_URL . '/modules/system/admin.php?fct=modulesadmin&op=update&module=' . $xoopsModule->getVar('dirname');

	$i++;
	$headermenu[$i]['title'] = _MODABOUT_ABOUT;
	$headermenu[$i]['link'] = ICMS_URL . '/modules/imaudit/admin/about.php';
}
?>