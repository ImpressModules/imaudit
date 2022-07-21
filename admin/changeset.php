<?php
/**
* Admin page to manage changesets
*
* List, add, edit and delete changeset objects
*
* @copyright	The ImpressCMS Project
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @since		1.0
* @author		marcan <marcan@impresscms.org>
* @package		imaudit
* @version		$Id$
*/

/**
 * Edit a Changeset
 *
 * @param int $changeset_id Changesetid to be edited
*/
function editchangeset($changeset_id = 0)
{
	global $imaudit_changeset_handler, $xoopsModule, $icmsAdminTpl;

	$changesetObj = $imaudit_changeset_handler->get($changeset_id);

	if (!$changesetObj->isNew()){
		$xoopsModule->displayAdminMenu(0, _AM_IMAUDIT_CHANGESETS . " > " . _CO_ICMS_EDITING);
		$sform = $changesetObj->getForm(_AM_IMAUDIT_CHANGESET_EDIT, 'addchangeset');
		$sform->assign($icmsAdminTpl);

	} else {
		$xoopsModule->displayAdminMenu(0, _AM_IMAUDIT_CHANGESETS . " > " . _CO_ICMS_CREATINGNEW);
		$sform = $changesetObj->getForm(_AM_IMAUDIT_CHANGESET_CREATE, 'addchangeset');
		$sform->assign($icmsAdminTpl);

	}
	$icmsAdminTpl->display('db:imaudit_admin_changeset.html');
}

include_once("admin_header.php");

$imaudit_changeset_handler = xoops_getModuleHandler('changeset');
/** Use a naming convention that indicates the source of the content of the variable */
$clean_op = '';
/** Create a whitelist of valid values, be sure to use appropriate types for each value
 * Be sure to include a value for no parameter, if you have a default condition
 */
$valid_op = array ('mod','changedField','addchangeset','del','view','');

if (isset($_GET['op'])) $clean_op = htmlentities($_GET['op']);
if (isset($_POST['op'])) $clean_op = htmlentities($_POST['op']);

/** Again, use a naming convention that indicates the source of the content of the variable */
$clean_changeset_id = isset($_GET['changeset_id']) ? (int) $_GET['changeset_id'] : 0 ;

/**
 * in_array() is a native PHP function that will determine if the value of the
 * first argument is found in the array listed in the second argument. Strings
 * are case sensitive and the 3rd argument determines whether type matching is
 * required
*/
if (in_array($clean_op,$valid_op,true)){
  switch ($clean_op) {
  	case "mod":
  	case "changedField":

  		xoops_cp_header();

  		editchangeset($clean_changeset_id);
  		break;
  	case "addchangeset":
          include_once ICMS_ROOT_PATH."/kernel/icmspersistablecontroller.php";
          $controller = new IcmsPersistableController($imaudit_changeset_handler);
  		$controller->storeFromDefaultForm(_AM_IMAUDIT_CHANGESET_CREATED, _AM_IMAUDIT_CHANGESET_MODIFIED);

  		break;

  	case "del":
  	    include_once ICMS_ROOT_PATH."/kernel/icmspersistablecontroller.php";
          $controller = new IcmsPersistableController($imaudit_changeset_handler);
  		$controller->handleObjectDeletion();

  		break;

  	case "view" :
  		$changesetObj = $imaudit_changeset_handler->get($clean_changeset_id);

  		smart_xoops_cp_header();
  		smart_adminMenu(1, _AM_IMAUDIT_CHANGESET_VIEW . ' > ' . $changesetObj->getVar('changeset_name'));

  		smart_collapsableBar('changesetview', $changesetObj->getVar('changeset_name') . $changesetObj->getEditChangesetLink(), _AM_IMAUDIT_CHANGESET_VIEW_DSC);

  		$changesetObj->displaySingleObject();

  		smart_close_collapsable('changesetview');

  		break;

  	default:

  		xoops_cp_header();

  		$xoopsModule->displayAdminMenu(0, _AM_IMAUDIT_CHANGESETS);

  		include_once ICMS_ROOT_PATH."/kernel/icmspersistabletable.php";
  		$objectTable = new IcmsPersistableTable($imaudit_changeset_handler);
  		$objectTable->addColumn(new IcmsPersistableColumn('changeset_number'));
  		$objectTable->addColumn(new IcmsPersistableColumn('branch_id'));
  		$objectTable->addColumn(new IcmsPersistableColumn('timestamp'));
  		$objectTable->addColumn(new IcmsPersistableColumn('message'));

  		$objectTable->addIntroButton('addchangeset', 'changeset.php?op=mod', _AM_IMAUDIT_CHANGESET_CREATE);
  		$icmsAdminTpl->assign('imaudit_changeset_table', $objectTable->fetch());
  		$icmsAdminTpl->display('db:imaudit_admin_changeset.html');
  		break;
  }
  xoops_cp_footer();
}
/**
 * If you want to have a specific action taken because the user input was invalid,
 * place it at this point. Otherwise, a blank page will be displayed
 */
?>