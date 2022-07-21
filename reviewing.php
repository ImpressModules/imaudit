<?php
/**
* Page to easily review. Changset will be displayed one after the other to make the
* process quick and easy
*
* @copyright	The ImpressCMS Project
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @since		1.0
* @author		marcan <marcan@impresscms.org>
* @package		imaudit
* @version		$Id$
*/

include_once 'header.php';

// if user does not have right to review, then get him of of there
imaudit_checkPermission('review_add', 'index.php', _NOPERM);

include_once ICMS_ROOT_PATH."/kernel/icmspersistabletable.php";

$xoopsOption['template_main'] = 'imaudit_reviewing.html';
include_once ICMS_ROOT_PATH . '/header.php';

$imaudit_changeset_handler = xoops_getModuleHandler('changeset');
$imaudit_branch_handler = xoops_getModuleHandler('branch');
$imaudit_review_handler = xoops_getModuleHandler('review');

/** Use a naming convention that indicates the source of the content of the variable */
$clean_branch_id = isset($_GET['branch_id']) ? intval($_GET['branch_id']) : 0 ;
$clean_status_id = isset($_GET['status_id']) ? intval($_GET['status_id']) : 1 ;
$clean_offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0 ;

// get next changeset to review
$changesetObj = $imaudit_changeset_handler->getNextToReview($clean_status_id, $clean_offset, $clean_branch_id);

if ($clean_branch_id) {
	$xoopsTpl->assign('imaudit_branch_id', $clean_branch_id);
}

$xoopsTpl->assign('imaudit_statusArray', $imaudit_changeset_handler->getStatusArrayForReview());
$xoopsTpl->assign('imaudit_status_id', $clean_status_id);
$xoopsTpl->assign('imaudit_offset', $clean_offset + 1);

if ($changesetObj) {
	// display this changeset
	$changeset_id = $changesetObj->id();
	$xoopsTpl->assign('imaudit_changeset', $changesetObj->toArray());

	// display review form if
	$reviewObj = $imaudit_review_handler->create();
	$reviewObj->hideFieldFromForm(array('changeset_id', 'reviewer', 'review_date'));
	$reviewObj->setVar('changeset_id', $changeset_id);
	$reviewObj->setVar('reviewer', $xoopsUser->uid());
	$reviewObj->setVar('review_date', time());
	$reviewObj->setVar('suggested_status', CHANGESET_STATUS_APPROVED);
	$sform = $reviewObj->getForm(_MD_IMAUDIT_REVIEW_CREATE, 'addreview', 'review.php');
	$sform->assign($xoopsTpl);
	$xoopsTpl->assign('imaudit_review', $reviewObj->toArray());
	$xoopsTpl->assign('imaudit_now', time());

	$branchObj = $changesetObj->getBranch();
	if ($notification_email = $branchObj->getVar('notification_email')) {
		$xoopsTpl->assign('imaudit_notification_info', sprintf(_MD_IMAUDIT_REVIEW_NOTIFICATION_INFO, $notification_email));
	}

	// list reviews
	$criteria = new CriteriaCompo();
	$criteria->add(new Criteria('changeset_id', $changeset_id));

	$objectTable = new IcmsPersistableTable($imaudit_review_handler, $criteria, array());
	$objectTable->isForUserSide();
	$objectTable->addColumn(new IcmsPersistableColumn('review_date'));
	$objectTable->addColumn(new IcmsPersistableColumn('reviewer'));
	$objectTable->addColumn(new IcmsPersistableColumn('comment'));
	$objectTable->addColumn(new IcmsPersistableColumn('suggested_status'));
	$xoopsTpl->assign('imaudit_title', _CO_IMAUDIT_REVIEWS);
	$xoopsTpl->assign('imaudit_review_table', $objectTable->fetch());
	$xoopsTpl->assign('imaudit_category_path', _MD_IMAUDIT_REVIEWING);
}
$xoopsTpl->assign('imaudit_reviewing', true);
$xoopsTpl->assign('imaudit_module_home', imaudit_getModuleName(true, true));

include_once 'footer.php';
?>