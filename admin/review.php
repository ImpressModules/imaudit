<?php
/**
* Admin page to manage reviews
*
* List, add, edit and delete review objects
*
* @copyright	The ImpressCMS Project
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @since		1.0
* @author		marcan <marcan@impresscms.org>
* @package		imaudit
* @version		$Id$
*/

/**
 * Edit a Review
 *
 * @param int $review_id Reviewid to be edited
*/
function editreview($review_id = 0)
{
	global $imaudit_review_handler, $xoopsModule, $icmsAdminTpl;

	$reviewObj = $imaudit_review_handler->get($review_id);

	if (!$reviewObj->isNew()){
		$xoopsModule->displayAdminMenu(1, _AM_IMAUDIT_REVIEWS . " > " . _CO_ICMS_EDITING);
		$sform = $reviewObj->getForm(_AM_IMAUDIT_REVIEW_EDIT, 'addreview');
		$sform->assign($icmsAdminTpl);

	} else {
		$xoopsModule->displayAdminMenu(1, _AM_IMAUDIT_REVIEWS . " > " . _CO_ICMS_CREATINGNEW);
		$sform = $reviewObj->getForm(_AM_IMAUDIT_REVIEW_CREATE, 'addreview');
		$sform->assign($icmsAdminTpl);

	}
	$icmsAdminTpl->display('db:imaudit_admin_review.html');
}

include_once("admin_header.php");

$imaudit_review_handler = xoops_getModuleHandler('review');
/** Use a naming convention that indicates the source of the content of the variable */
$clean_op = '';
/** Create a whitelist of valid values, be sure to use appropriate types for each value
 * Be sure to include a value for no parameter, if you have a default condition
 */
$valid_op = array ('mod','changedField','addreview','del','view','');

if (isset($_GET['op'])) $clean_op = htmlentities($_GET['op']);
if (isset($_POST['op'])) $clean_op = htmlentities($_POST['op']);

/** Again, use a naming convention that indicates the source of the content of the variable */
$clean_review_id = isset($_GET['review_id']) ? (int) $_GET['review_id'] : 0 ;

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

  		editreview($clean_review_id);
  		break;
  	case "addreview":
          include_once ICMS_ROOT_PATH."/kernel/icmspersistablecontroller.php";
          $controller = new IcmsPersistableController($imaudit_review_handler);
  		$controller->storeFromDefaultForm(_AM_IMAUDIT_REVIEW_CREATED, _AM_IMAUDIT_REVIEW_MODIFIED);

  		break;

  	case "del":
  	    include_once ICMS_ROOT_PATH."/kernel/icmspersistablecontroller.php";
          $controller = new IcmsPersistableController($imaudit_review_handler);
  		$controller->handleObjectDeletion();

  		break;

  	case "view" :
  		$reviewObj = $imaudit_review_handler->get($clean_review_id);

  		smart_xoops_cp_header();
  		smart_adminMenu(1, _AM_IMAUDIT_REVIEW_VIEW . ' > ' . $reviewObj->getVar('review_name'));

  		smart_collapsableBar('reviewview', $reviewObj->getVar('review_name') . $reviewObj->getEditReviewLink(), _AM_IMAUDIT_REVIEW_VIEW_DSC);

  		$reviewObj->displaySingleObject();

  		smart_close_collapsable('reviewview');

  		break;

  	default:

  		xoops_cp_header();

  		$xoopsModule->displayAdminMenu(1, _AM_IMAUDIT_REVIEWS);

  		include_once ICMS_ROOT_PATH."/kernel/icmspersistabletable.php";
  		$objectTable = new IcmsPersistableTable($imaudit_review_handler);
  		$objectTable->addColumn(new IcmsPersistableColumn('review_date'));
  		$objectTable->addColumn(new IcmsPersistableColumn('changeset_id'));
  		$objectTable->addColumn(new IcmsPersistableColumn('reviewer'));
  		$objectTable->addColumn(new IcmsPersistableColumn('comment'));

  		$objectTable->addIntroButton('addreview', 'review.php?op=mod', _AM_IMAUDIT_REVIEW_CREATE);
  		$icmsAdminTpl->assign('imaudit_review_table', $objectTable->fetch());
  		$icmsAdminTpl->display('db:imaudit_admin_review.html');
  		break;
  }
  xoops_cp_footer();
}
/**
 * If you want to have a specific action taken because the user input was invalid,
 * place it at this point. Otherwise, a blank page will be displayed
 */
?>