<?php
/**
* Common file of the module included on all pages of the module
*
* @copyright	The ImpressCMS Project
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @since		1.0
* @author		marcan <marcan@impresscms.org>
* @package		imaudit
* @version		$Id$
*/

if (!defined("ICMS_ROOT_PATH")) die("ICMS root path not defined");

if(!defined("IMAUDIT_DIRNAME"))		define("IMAUDIT_DIRNAME", $modversion['dirname'] = basename(dirname(dirname(__FILE__))));
if(!defined("IMAUDIT_URL"))			define("IMAUDIT_URL", ICMS_URL.'/modules/'.IMAUDIT_DIRNAME.'/');
if(!defined("IMAUDIT_ROOT_PATH"))	define("IMAUDIT_ROOT_PATH", ICMS_ROOT_PATH.'/modules/'.IMAUDIT_DIRNAME.'/');
if(!defined("IMAUDIT_IMAGES_URL"))	define("IMAUDIT_IMAGES_URL", IMAUDIT_URL.'images/');
if(!defined("IMAUDIT_ADMIN_URL"))	define("IMAUDIT_ADMIN_URL", IMAUDIT_URL.'admin/');

// Include the common language file of the module
icms_loadLanguageFile('imaudit', 'common');

include_once(IMAUDIT_ROOT_PATH . "include/functions.php");

// Creating the module object to make it available throughout the module
$imauditModule = icms_getModuleInfo(IMAUDIT_DIRNAME);
if (is_object($imauditModule)){
	$imaudit_moduleName = $imauditModule->getVar('name');
}

// Find if the user is admin of the module and make this info available throughout the module
$imaudit_isAdmin = icms_userIsAdmin(IMAUDIT_DIRNAME);

// Creating the module config array to make it available throughout the module
$imauditConfig = icms_getModuleConfig(IMAUDIT_DIRNAME);

// creating the icmsPersistableRegistry to make it available throughout the module
global $icmsPersistableRegistry;
$icmsPersistableRegistry = IcmsPersistableRegistry::getInstance();

?>