<?php

/**
* Classes responsible for managing imAudit branch objects
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

class ImauditBranch extends IcmsPersistableObject {

	/**
	 * Constructor
	 *
	 * @param object $handler ImauditPostHandler object
	 */
	public function __construct(& $handler) {
		global $xoopsConfig;

		$this->IcmsPersistableObject($handler);

		$this->quickInitVar('branch_id', XOBJ_DTYPE_INT, true);
		$this->quickInitVar('name', XOBJ_DTYPE_TXTBOX, true);
		$this->quickInitVar('description', XOBJ_DTYPE_TXTAREA, false);
		$this->quickInitVar('rssfeed', XOBJ_DTYPE_TXTBOX, false);
		$this->quickInitVar('first_revision', XOBJ_DTYPE_INT, true);
		$this->quickInitVar('notification_email', XOBJ_DTYPE_TXTBOX);
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
		if ($format == 's' && in_array($key, array ())) {
			return call_user_func(array ($this,	$key));
		}
		return parent :: getVar($key, $format);
	}

	/**
	 * Get branch update link action
	 *
	 * @return str link on image to update branch
	 */
    function getUpdateLink() {
    	$ret = '<a href="' . IMAUDIT_ADMIN_URL . 'branch.php?branch_id=' . $this->id() . '&op=update"><img src="' . ICMS_IMAGES_SET_URL . '/actions/down.png" alt="' . _AM_IMAUDIT_BRANCH_UPDATE . '" title="' . _AM_IMAUDIT_BRANCH_UPDATE . '" style="vertical-align: middle;" /></a>';
    	return $ret;
    }

    /**
     * Update branch with the latest changeset using the RSS feed of the branch
     */
    function updateBranch(&$log) {
    	include_once(ICMS_ROOT_PATH . '/modules/imaudit/class/icmssimplerss.php');

    	$log[] = 'Updating branch ' . $this->id() . ' ' . $this->getVar('name');

    	$latestChangesetNumber = $this->getLatestChangesetNumber() + 1;
    	$rssfeed = str_replace('[from_revision]', $latestChangesetNumber, $this->getVar('rssfeed'));

		// Create a new instance of the SimplePie object
		$log[] = 'Rss Feed: ' . $rssfeed;
		$feed = new IcmsSimpleRss($rssfeed);

		$imaudit_changeset_handler = xoops_getModulehandler('changeset', 'imaudit');
		$branch_id = $this->id();

		if ($feed) {
			foreach($feed->get_items(0) as $feed_item) {
				$changesetNumber = $this->getChangesetNumber($feed_item->get_title());
				$feed_items[$changesetNumber] = $feed_item;
			}
			ksort($feed_items);
			foreach($feed_items as $k => $v) {
				$date = $feed_item->get_date();
				$changesetObj = $imaudit_changeset_handler->create();
				$changesetObj->setVar('branch_id', $branch_id);
				$changesetObj->setVar('timestamp', strtotime($v->get_date()));
				$changesetObj->setVar('link', $v->get_permalink());
				$changesetObj->setVar('message', $v->get_content());
				$changesetObj->setVar('status', CHANGESET_STATUS_TO_BE_REVIEWED);
				$changesetObj->setVar('changeset_number', $k);
				if (!$imaudit_changeset_handler->insert($changesetObj, true)) {
					$log[] = 'Error inserting changset: ' . $k;
					$log[] = $changesetObj->getHtmlErrors();
				} else {
					$log[] = 'Successfully inserted changset: ' . $k;
				}
			}
		} else {
			$log[] = 'No feed avaiable';
		}
    }

    /**
     * Get changeset number from title
     *
     * @param int changeset number
     */
    function getChangesetNumber($title) {
		$title = str_replace('Revision ', '', $title);
		$title = substr($title, 0, strpos($title, ':'));
		return $title;
    }

	/**
	 * Get latest changeset number for this branch
	 *
	 * @param int latest changeset number
	 */
	function getLatestChangesetNumber() {
		$imaudit_changeset_handler = xoops_getModuleHandler('changeset', 'imaudit');
		$sql = 'SELECT changeset_number FROM ' . $imaudit_changeset_handler->table;
		$criteria = new CriteriaCompo();
		$criteria->add(new Criteria('branch_id', $this->id()));
		$criteria->setSort('changeset_number');
		$criteria->setOrder('DESC');
		$criteria->setLimit(1);
		$result = $imaudit_changeset_handler->query($sql, $criteria);
		if ($result && count($result) > 0) {
			return $result[0]['changeset_number'];
		} else {
			return $this->getVar('first_revision');
		}
	}
}
class ImauditBranchHandler extends IcmsPersistableObjectHandler {

	/**
	 * Constructor
	 */
	public function __construct(& $db) {
		$this->IcmsPersistableObjectHandler($db, 'branch', 'branch_id', 'name', 'decsription', 'imaudit');
	}

	function updateAllBranches() {
		$log = array();
		$branchesObj = $this->getObjects();
		foreach($branchesObj as $branchObj) {
			$branchObj->updateBranch($log);
		}
		return $log;
	}
}
?>