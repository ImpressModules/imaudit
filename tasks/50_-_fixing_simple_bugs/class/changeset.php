<?php

/**
* Classes responsible for managing imAudit changeset objects
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

define('CHANGESET_STATUS_TO_BE_REVIEWED', 1);
define('CHANGESET_STATUS_TO_BE_REVERTED', 4);
define('CHANGESET_STATUS_CHANGE_REQUESTED', 2);
define('CHANGESET_STATUS_APPROVED', 3);
define('CHANGESET_STATUS_APPROVED_NOTIF', 5);

class ImauditChangeset extends IcmsPersistableObject {

	/**
	 * Constructor
	 *
	 * @param object $handler ImauditPostHandler object
	 */
	public function __construct(& $handler) {
		global $xoopsConfig;

		$this->IcmsPersistableObject($handler);

		$this->quickInitVar('changeset_id', XOBJ_DTYPE_INT, true);
		$this->quickInitVar('branch_id', XOBJ_DTYPE_INT, true);
		$this->quickInitVar('timestamp', XOBJ_DTYPE_LTIME, true);
		$this->quickInitVar('link', XOBJ_DTYPE_TXTBOX, true);
		$this->quickInitVar('message', XOBJ_DTYPE_TXTAREA, false);
		$this->quickInitVar('status', XOBJ_DTYPE_INT, true);
		$this->quickInitVar('changeset_number', XOBJ_DTYPE_INT, true);

		$this->setControl('branch_id', array('itemHandler' => 'branch',
                                          'method' => 'getList',
                                          'module' => 'imaudit'));
		$this->setControl('status', array('itemHandler' => 'changeset',
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
		if ($format == 's' && in_array($key, array ('branch_id', 'status', 'timestamp'))) {
			return call_user_func(array ($this,	$key));
		}
		return parent :: getVar($key, $format);
	}

	/** Get the name of the branch instead of its ID
	 *
	 * @return str name of the branch
	 */
    function branch_id() {
    	$icms_persistable_registry = IcmsPersistableRegistry::getInstance();
    	$ret = $this->getVar('branch_id', 'e');
    	$obj = $icms_persistable_registry->getSingleObject('branch', $ret);
    	if ($obj) $ret = '<a href="changeset.php?branch_id=' . $obj->id() . '">'  . $obj->getVar('name') . '</a>';
    	return $ret;
    }

	/**
	 * Returns the status name instead of it's ID
	 *
	 * @return str status name
	 */
	function status() {
		$imaudit_changeset_handler =  xoops_getModuleHandler('changeset');
		$statusArray = $imaudit_changeset_handler->getStatusArray();
		$ret = $this->getVar('status', 'e');
		if (isset($statusArray[$ret])) {
			return $statusArray[$ret];
		} else {
			return $ret;
		}
	}

	/**
	 * Formating this value with only date, not time
	 *
	 * @return str timestamp
	 */
	function timestamp() {
		return formatTimestamp($this->getVar('timestamp', 'e'), 'Y-m-d');
	}

    /**
     * Retreive the object user side link
     *
     * This is overriding the IcmsPersistableObject::getItemLink() to add "Changeset " just before
     * the changeset number in the returned link
     *
     * @param bool $onlyUrl wether or not to return a simple URL or a full <a> link
     * @return string user side link to the object
     */
    function getItemLink($onlyUrl=false) {
		$ret = $this->handler->_moduleUrl . $this->handler->_page . "?" . $this->handler->keyName . "=" . $this->getVar($this->handler->keyName);

		if (!$onlyUrl) {
			$ret = "<a href='" . $ret . "'>" . _CO_IMAUDIT_CHANGESET_CHANGESET . ' ' . $this->getVar($this->handler->identifierName) . "</a>";
		}
    	return $ret;
	}

	/**
	 * Overriding IcmsPersistableObject::toArray to use getItemLink from the object
	 * instead of from its controller
	 */
	function toArray() {
		$ret = parent::toArray();
		$ret['itemLink'] = $this->getItemLink();
		return $ret;
	}

	/** Get the branch object related to this changeset
	 *
	 * @return object ImauditBranch related to this changeset
	 */
    function getBranch() {
    	$icms_persistable_registry = IcmsPersistableRegistry::getInstance();
    	$ret = $this->getVar('branch_id', 'e');
    	$obj = $icms_persistable_registry->getSingleObject('branch', $ret);
    	if ($obj) $ret = $obj;
    	return $ret;
    }

    /**
     * Used in changeset page on user side to display only the revision number, without the "Changeset" prefix
     *
     * @return str changeset number with link on it
     */
    function getChangesetNumberLink() {
    	$ret = '<a href="' . $this->getItemLink(true) . '">' . $this->getVar('changeset_number') . '</a>';
    	return $ret;
    }
}
class ImauditChangesetHandler extends IcmsPersistableObjectHandler {

	private $changesetArray=false;
	private $changesetArrayForReviewing=false;

	/**
	 * Constructor
	 */
	public function __construct(& $db) {
		$this->IcmsPersistableObjectHandler($db, 'changeset', 'changeset_id', 'changeset_number', 'message', 'imaudit');
	}

	/**
	 * Get changset possible status of a changeset
	 *
	 * @return array possible status array
	 */
	function getStatusArray() {
		if (!$this->changesetArray) {
			$this->changesetArray[CHANGESET_STATUS_TO_BE_REVIEWED] = _CO_IMAUDIT_CHANGESET_STATUS_TO_BE_REVIEWED;
			$this->changesetArray[CHANGESET_STATUS_TO_BE_REVERTED] = _CO_IMAUDIT_CHANGESET_STATUS_TO_BE_REVERTED;
			$this->changesetArray[CHANGESET_STATUS_CHANGE_REQUESTED] = _CO_IMAUDIT_CHANGESET_STATUS_CHANGE_REQUESTED;
			$this->changesetArray[CHANGESET_STATUS_APPROVED] = _CO_IMAUDIT_CHANGESET_STATUS_APPROVED;
			$this->changesetArray[CHANGESET_STATUS_APPROVED_NOTIFY] = _CO_IMAUDIT_CHANGESET_STATUS_APPROVED_NOTIFY;
		}
		return $this->changesetArray;
	}

	/**
	 * Get status arrayy for reviewing
	 *
	 * @param array status array for reviewing
	 */
	function getStatusArrayForReview() {
		if (!$this->changesetArrayForReviewing) {
			$this->changesetArrayForReviewing[CHANGESET_STATUS_TO_BE_REVIEWED] = _CO_IMAUDIT_CHANGESET_STATUS_TO_BE_REVIEWED;
			$this->changesetArrayForReviewing[CHANGESET_STATUS_TO_BE_REVERTED] = _CO_IMAUDIT_CHANGESET_STATUS_TO_BE_REVERTED;
			$this->changesetArrayForReviewing[CHANGESET_STATUS_CHANGE_REQUESTED] = _CO_IMAUDIT_CHANGESET_STATUS_CHANGE_REQUESTED;
		}
		return $this->changesetArrayForReviewing;
	}

	/**
	 * Get next changeset to review
	 *
	 * @param bool $status_id status of the next changeset to return
	 * @param int offset
	 * @param int $branch_id specific branch_id if specified
	 * @return object ImauditChangeset
	 */
	function getNextToReview($status_id=false, $offset=0, $branch_id=0) {
		$criteria = new CriteriaCompo();
		if ($branch_id) {
			$criteria->add(new Criteria('branch_id', $branch_id));
		}
		if ($offset) {
			$criteria->setStart($offset);
		}
		if ($status_id) {
			$criteria->add(new Criteria('status', $status_id));
		}
		$criteria->setLimit(1);
		$criteria->setSort('changeset_number');
		$criteria->setOrder('ASC');
		$changesetsObj = $this->getObjects($criteria);
		if ($changesetsObj && count($changesetsObj) > 0) {
			return $changesetsObj[0];
		} else {
			return false;
		}
	}
}
?>