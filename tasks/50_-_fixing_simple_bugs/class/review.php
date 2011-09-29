<?php

/**
* Classes responsible for managing imAudit review objects
*
* @copyright	The ImpressCMS Project
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @since		1.0
* @author		marcan <marcan@impresscms.org>
* @package		imaudit
* @version		$Id$
*/

if (!defined("ICMS_ROOT_PATH")) die("ICMS root path not defined");

// including the IcmsPersistabelSeoObject
include_once ICMS_ROOT_PATH . '/kernel/icmspersistableobject.php';
include_once(ICMS_ROOT_PATH . '/modules/imaudit/include/functions.php');

class ImauditReview extends IcmsPersistableObject {

	/**
	 * Constructor
	 *
	 * @param object $handler ImauditPostHandler object
	 */
	public function __construct(& $handler) {
		global $xoopsConfig;

		$this->IcmsPersistableObject($handler);

		$this->quickInitVar('review_id', XOBJ_DTYPE_INT, true);
		$this->quickInitVar('changeset_id', XOBJ_DTYPE_INT, true);
		$this->quickInitVar('reviewer', XOBJ_DTYPE_INT, true);
		$this->quickInitVar('comment', XOBJ_DTYPE_TXTAREA);
		$this->quickInitVar('review_date', XOBJ_DTYPE_LTIME, true);
		$this->quickInitVar('suggested_status', XOBJ_DTYPE_INT, true);

		$this->setControl('suggested_status', array('itemHandler' => 'changeset',
                                          'method' => 'getStatusArray',
                                          'module' => 'imaudit'));
	}

	/**
	 * Overriding the IcmsPersistableObject::getVar method to assign a custom method on some
	 * specific fields to handle the value before returning it
	 *
	 * @param str $key key of the field
	 * @param str $format format that is requested
	 * @return mixed value of the field that is requested
	 */
	function getVar($key, $format = 's') {
		if ($format == 's' && in_array($key, array('reviewer', 'suggested_status'))) {
			return call_user_func(array ($this,	$key));
		}
		return parent :: getVar($key, $format);
	}

	/**
	 * Retrieving the name of the reviewer, linked to his profile
	 *
	 * @return str name of the poster
	 */
	function reviewer() {
		return icms_getLinkedUnameFromId($this->getVar('reviewer', 'e'));
	}

	/**
	 * Returns the status name instead of it's ID
	 *
	 * @return str status name
	 */
	function suggested_status() {
		$imaudit_changeset_handler =  xoops_getModuleHandler('changeset');
		$statusArray = $imaudit_changeset_handler->getStatusArray();
		$ret = $this->getVar('suggested_status', 'e');
		if (isset($statusArray[$ret])) {
			return $statusArray[$ret];
		} else {
			return $ret;
		}
	}

	/**
	 * Retrieve the related changeset object
	 *
	 * @param obj ImauditChangeset Object
	 */
	function getChangeset() {
    	$icms_persistable_registry = IcmsPersistableRegistry::getInstance();
    	$ret = $this->getVar('changeset_id', 'e');
    	$obj = $icms_persistable_registry->getSingleObject('changeset', $ret);
		return $obj;
	}

	/**
	 * Get link to related changet
	 *
	 * @return str changeset link
	 */
	function getChangesetLink() {
		$changesetObj = $this->getChangeset();
		if ($changesetObj) {
			return $changesetObj->getItemLink();
		} else {
			return $this->getVar('changeset_id');
		}
	}

	/**
	 * Get name of the reviewer
	 *
	 * @return str name of the reviewer
	 */
	function getReviewerName() {
		$member_handler = xoops_getHandler('member');
		$userObj = $member_handler->getuser($this->getVar('reviewer', 'e'));
		if (is_object($userObj)) {
			return $userObj->getVar('uname');
		} else {
			return '';
		}
	}

	/**
	 * Notify email list config about this review and its related changeset
	 *
	 * @return VOID
	 */
	function notifyEmailList() {
		global $xoopsConfig;

		$changesetObj = $this->getChangeset();
		$branchObj = $changesetObj->getBranch();

		// if no notification_email is set for that branch, then no email is needed
		if ($notification_email = $branchObj->getVar('notification_email')) {

			$xoopsMailer =& getMailer();
			$xoopsMailer->useMail();
			if (file_exists(IMAUDIT_ROOT_PATH . 'language/' . $xoopsConfig['language'] . '/mail_template/')) {
				$templateDir = IMAUDIT_ROOT_PATH . 'language/' . $xoopsConfig['language'] . '/mail_template/';
			} else {
				$templateDir = IMAUDIT_ROOT_PATH . 'language/english/mail_template/';
			}

			$xoopsMailer->setTemplateDir($templateDir);
			$xoopsMailer->setTemplate('review_notify.tpl');
			$xoopsMailer->setToEmails($notification_email);
			$xoopsMailer->setFromEmail($xoopsConfig['adminmail']);
			$xoopsMailer->setFromName($xoopsConfig['sitename']);
			$xoopsMailer->setSubject(sprintf(_CO_IMAUDIT_REVIEW_NOTIFY_SUBJECT,$changesetObj->getVar('changeset_number'), $this->getVar('suggested_status')));
			$xoopsMailer->assign('CHANGESET_NUMBER', $changesetObj->getVar('changeset_number'));
			$xoopsMailer->assign('CHANGESET_BRANCH', $changesetObj->getVar('branch_id'));
			$xoopsMailer->assign('CHANGESET_DATE', $changesetObj->getVar('timestamp'));
			$xoopsMailer->assign('CHANGESET_LINK', $changesetObj->getVar('link'));
			$xoopsMailer->assign('CHANGESET_MESSAGE', $changesetObj->getVar('message'));
			$xoopsMailer->assign('REVIEW_DATE', $this->getVar('review_date'));
			$xoopsMailer->assign('REVIEWER', $this->getReviewerName());
			$xoopsMailer->assign('REVIEW_STATUS', $this->getVar('suggested_status'));
			$xoopsMailer->assign('REVIEW_COMMENT', $this->getVar('comment', 'e'));
			$xoopsMailer->assign('REVIEW_LINK', $this->getItemLink(true));

			if(!$xoopsMailer->send(true)) {
				icms_debug($xoopsMailer->getErrors(true));
			}
		}
	}
}
class ImauditReviewHandler extends IcmsPersistableObjectHandler {

	/**
	 * Constructor
	 */
	public function __construct(& $db) {
		$this->IcmsPersistableObjectHandler($db, 'review', 'review_id', 'review_date', 'comment', 'imaudit');
	}

	/**
	 * After save event
	 *
	 * @param object $obj ImauditReview object
	 */
	function afterSave(&$obj) {
		$imaudit_changeset_handler = xoops_getModuleHandler('changeset');
		$changesetObj = $imaudit_changeset_handler->get($obj->getVar('changeset_id', 'e'));
		$suggested_status = $obj->getVar('suggested_status', 'e');

		if ($changesetObj->getVar('status', 'e') != $suggested_status) {
			$changesetObj->setVar('status', $suggested_status);
			$imaudit_changeset_handler->insert($changesetObj);
		}
		// if $status is not Approved, send a notification email to the list email config
		if ($suggested_status != CHANGESET_STATUS_APPROVED) {
			$obj->notifyEmailList();
		}

		return true;
	}
}
?>