<?php
/**
* Admin page to manage branchs
*
* List, add, edit and delete branch objects
*
* @copyright	The ImpressCMS Project
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @since		1.0
* @author		marcan <marcan@impresscms.org>
* @package		imaudit
* @version		$Id$
*/

/**
 * Edit a Branch
 *
 * @param int $branch_id Branchid to be edited
*/
function editbranch($branch_id = 0)
{
	global $imaudit_branch_handler, $xoopsModule, $icmsAdminTpl;

	$branchObj = $imaudit_branch_handler->get($branch_id);

	if (!$branchObj->isNew()){
		$xoopsModule->displayAdminMenu(2, _AM_IMAUDIT_BRANCHS . " > " . _CO_ICMS_EDITING);
		$sform = $branchObj->getForm(_AM_IMAUDIT_BRANCH_EDIT, 'addbranch');
		$sform->assign($icmsAdminTpl);

	} else {
		$xoopsModule->displayAdminMenu(2, _AM_IMAUDIT_BRANCHS . " > " . _CO_ICMS_CREATINGNEW);
		$sform = $branchObj->getForm(_AM_IMAUDIT_BRANCH_CREATE, 'addbranch');
		$sform->assign($icmsAdminTpl);

	}
	$icmsAdminTpl->display('db:imaudit_admin_branch.html');
}

include_once("admin_header.php");

$imaudit_branch_handler = xoops_getModuleHandler('branch');
/** Use a naming convention that indicates the source of the content of the variable */
$clean_op = '';
/** Create a whitelist of valid values, be sure to use appropriate types for each value
 * Be sure to include a value for no parameter, if you have a default condition
 */
$valid_op = array ('mod','changedField','addbranch','del','view','update','');

if (isset($_GET['op'])) $clean_op = htmlentities($_GET['op']);
if (isset($_POST['op'])) $clean_op = htmlentities($_POST['op']);

/** Again, use a naming convention that indicates the source of the content of the variable */
$clean_branch_id = isset($_GET['branch_id']) ? (int) $_GET['branch_id'] : 0 ;

/**
 * in_array() is a native PHP function that will determine if the value of the
 * first argument is found in the array listed in the second argument. Strings
 * are case sensitive and the 3rd argument determines whether type matching is
 * required
*/
if (in_array($clean_op,$valid_op,true)){
  switch ($clean_op) {
  	case 'update':
		$branchObj = $imaudit_branch_handler->get($clean_branch_id);
		$branchObj->updateBranch();
  	break;
  	case "mod":
  	case "changedField":

  		xoops_cp_header();

  		editbranch($clean_branch_id);
  		break;
  	case "addbranch":
          include_once ICMS_ROOT_PATH."/kernel/icmspersistablecontroller.php";
          $controller = new IcmsPersistableController($imaudit_branch_handler);
  		  $controller->storeFromDefaultForm(_AM_IMAUDIT_BRANCH_CREATED, _AM_IMAUDIT_BRANCH_MODIFIED);

  		break;

  	case "del":
  	    include_once ICMS_ROOT_PATH."/kernel/icmspersistablecontroller.php";
          $controller = new IcmsPersistableController($imaudit_branch_handler);
  		$controller->handleObjectDeletion();

  		break;

  	case "view" :
  		$branchObj = $imaudit_branch_handler->get($clean_branch_id);

  		smart_xoops_cp_header();
  		smart_adminMenu(1, _AM_IMAUDIT_BRANCH_VIEW . ' > ' . $branchObj->getVar('branch_name'));

  		smart_collapsableBar('branchview', $branchObj->getVar('branch_name') . $branchObj->getEditBranchLink(), _AM_IMAUDIT_BRANCH_VIEW_DSC);

  		$branchObj->displaySingleObject();

  		smart_close_collapsable('branchview');

  		break;

  	default:

  		xoops_cp_header();

  		$xoopsModule->displayAdminMenu(2, _AM_IMAUDIT_BRANCHS);

  		include_once ICMS_ROOT_PATH."/kernel/icmspersistabletable.php";
  		$objectTable = new IcmsPersistableTable($imaudit_branch_handler);
  		$objectTable->addColumn(new IcmsPersistableColumn('name'));
  		$objectTable->addColumn(new IcmsPersistableColumn('description'));

  		$objectTable->addCustomAction('getUpdateLink');

  		$objectTable->addIntroButton('addbranch', 'branch.php?op=mod', _AM_IMAUDIT_BRANCH_CREATE);
  		$icmsAdminTpl->assign('imaudit_branch_table', $objectTable->fetch());
  		$icmsAdminTpl->display('db:imaudit_admin_branch.html');
  		break;
  }
  xoops_cp_footer();
}
/**
 * If you want to have a specific action taken because the user input was invalid,
 * place it at this point. Otherwise, a blank page will be displayed
 */
?>