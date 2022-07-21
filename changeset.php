<?php
/**
* Changeset page
*
* @copyright	The ImpressCMS Project
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @since		1.0
* @author		marcan <marcan@impresscms.org>
* @package		imaudit
* @version		$Id$
*/

include_once 'header.php';
include_once ICMS_ROOT_PATH."/kernel/icmspersistabletable.php";

$xoopsOption['template_main'] = 'imaudit_changeset.html';
include_once ICMS_ROOT_PATH . '/header.php';

$imaudit_changeset_handler = xoops_getModuleHandler('changeset');
$imaudit_branch_handler = xoops_getModuleHandler('branch');
$imaudit_review_handler = xoops_getModuleHandler('review');

/** Use a naming convention that indicates the source of the content of the variable */
$clean_changeset_id = isset($_GET['changeset_id']) ? intval($_GET['changeset_id']) : 0 ;
$clean_branch_id = isset($_GET['branch_id']) ? intval($_GET['branch_id']) : 0 ;
$clean_status_id = isset($_GET['status_id']) ? intval($_GET['status_id']) : 1 ;

if ($clean_changeset_id) {
	$changesetObj = $imaudit_changeset_handler->get($clean_changeset_id);

	if($changesetObj && !$changesetObj->isNew()) {
		// display this changeset
		$xoopsTpl->assign('imaudit_changeset', $changesetObj->toArray());

		if (imaudit_checkPermission('review_add')) {
			// display review form if
			$reviewObj = $imaudit_review_handler->create();
			$reviewObj->hideFieldFromForm(array('changeset_id', 'reviewer', 'review_date'));
			$reviewObj->setVar('changeset_id', $clean_changeset_id);
			$reviewObj->setVar('reviewer', $xoopsUser->uid());
			$reviewObj->setVar('review_date', time());
			$reviewObj->setVar('suggested_status', CHANGESET_STATUS_APPROVED);
			$sform = $reviewObj->getForm(_MD_IMAUDIT_REVIEW_CREATE, 'addreview', 'review.php');
			$sform->assign($xoopsTpl);

			$branchObj = $changesetObj->getBranch();
			if ($notification_email = $branchObj->getVar('notification_email')) {
				$xoopsTpl->assign('imaudit_notification_info', sprintf(_MD_IMAUDIT_REVIEW_NOTIFICATION_INFO, $notification_email));
			}
		}

		// list reviews
		$criteria = new CriteriaCompo();
		$criteria->add(new Criteria('changeset_id', $clean_changeset_id));

		$objectTable = new IcmsPersistableTable($imaudit_review_handler, $criteria, array());
		$objectTable->isForUserSide();
		$objectTable->addColumn(new IcmsPersistableColumn('review_date'));
		$objectTable->addColumn(new IcmsPersistableColumn('reviewer'));
		$objectTable->addColumn(new IcmsPersistableColumn('comment'));
		$objectTable->addColumn(new IcmsPersistableColumn('suggested_status'));
		$xoopsTpl->assign('imaudit_review_table', $objectTable->fetch());
		$xoopsTpl->assign('imaudit_category_path', _CO_IMAUDIT_CHANGESET_CHANGESET . $changesetObj->getVar('changeset_number'));
	}
} else {
	if ($clean_branch_id) {
		// get branch
		$branchObj = $imaudit_branch_handler->get($clean_branch_id);
		if ($branchObj->isNew()) redirect_header(ICMS_URL, 3, _NOPERM);
		$criteria = new CriteriaCompo();
		$criteria->add(new Criteria('branch_id', $clean_branch_id));
		$xoopsTpl->assign('imaudit_title', sprintf(_MD_IMAUDIT_ALL_CHANGESETS_OF_BRANCH, $branchObj->getVar('name')));
	} else {
		// get all branches to display links
		$criteria = null;
		$xoopsTpl->assign('imaudit_branchesArray', $imaudit_branch_handler->getList());
		$xoopsTpl->assign('imaudit_title', _MD_IMAUDIT_ALL_CHANGESETS);
	}
	// list changesets
	$objectTable = new IcmsPersistableTable($imaudit_changeset_handler, $criteria, array());
	$objectTable->isForUserSide();
	$objectTable->addColumn(new IcmsPersistableColumn('changeset_number', 'left', 80, 'getChangesetNumberLink'));
	$objectTable->addColumn(new IcmsPersistableColumn('timestamp'));
	$objectTable->addColumn(new IcmsPersistableColumn('message'));
	$objectTable->addColumn(new IcmsPersistableColumn('status'));

	$criteria_status_to_be_reviewed = new CriteriaCompo();
	$criteria_status_to_be_reviewed->add(new Criteria('status', CHANGESET_STATUS_TO_BE_REVIEWED));
	$objectTable->addFilter(_CO_IMAUDIT_CHANGESET_STATUS_TO_BE_REVIEWED, array(
								'key' => 'status',
								'criteria' => $criteria_status_to_be_reviewed
	));
	$criteria_status_change_requested = new CriteriaCompo();
	$criteria_status_change_requested->add(new Criteria('status', CHANGESET_STATUS_CHANGE_REQUESTED));
	$objectTable->addFilter(_CO_IMAUDIT_CHANGESET_STATUS_CHANGE_REQUESTED, array(
								'key' => 'status',
								'criteria' => $criteria_status_change_requested
	));
	$criteria_status_to_be_reverted = new CriteriaCompo();
	$criteria_status_to_be_reverted->add(new Criteria('status', CHANGESET_STATUS_TO_BE_REVERTED));
	$objectTable->addFilter(_CO_IMAUDIT_CHANGESET_STATUS_TO_BE_REVERTED, array(
								'key' => 'status',
								'criteria' => $criteria_status_to_be_reverted
	));
	$criteria_status_approved = new CriteriaCompo();
	$criteria_status_approved->add(new Criteria('status', CHANGESET_STATUS_APPROVED));
	$objectTable->addFilter(_CO_IMAUDIT_CHANGESET_STATUS_APPROVED, array(
								'key' => 'status',
								'criteria' => $criteria_status_approved
	));

	$xoopsTpl->assign('imaudit_changeset_table', $objectTable->fetch());
}
$xoopsTpl->assign('imaudit_module_home', imaudit_getModuleName(true, true));

include_once 'footer.php';
?>