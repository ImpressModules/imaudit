<?php
/**
* English language constants used in the user side of the module
*
* @copyright	The ImpressCMS Project
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @since		1.0
* @author		marcan <marcan@impresscms.org>
* @package		imaudit
* @version		$Id$
*/

if (!defined("ICMS_ROOT_PATH")) die("ICMS root path not defined");


define('_MD_IMAUDIT_ADMIN_PAGE', ':: Admin page ::');

define("_MD_IMAUDIT_ALL_CHANGESETS", "All changesets");
define("_MD_IMAUDIT_ALL_REVIEWS", "All reviews");
define("_MD_IMAUDIT_REVIEW_CREATE", "Review this changeset");
define("_MD_IMAUDIT_REVIEW_CREATE_INFO", "To approve this changeset, simply click the <b>Submit button</b>. If you believe there is a problem in this changeset, please write a comment and select a status.");
define("_MD_IMAUDIT_REVIEW_NOTIFICATION_INFO", "If the status you select is <b>Approved</b>, no notification email will be sent. However, any other status you select will trigger a notification email sent to <b>%s</b>.");
define("_MD_IMAUDIT_REVIEW_CREATED", "The review has been successfully created.");
define("_MD_IMAUDIT_REVIEW_MODIFIED", "The review was successfully modified.");
define("_MD_IMAUDIT_BRANCH_SELECT", "Filter by branch:");
define("_MD_IMAUDIT_ALL_CHANGESETS_OF_BRANCH", "Changesets of branch %s");
define("_MD_IMAUDIT_BY", "by");
define("_MD_IMAUDIT_REVIEWING_APPROVED", "Approved");
define("_MD_IMAUDIT_REVIEWING_IGNORE", "No review, next...");
define("_MD_IMAUDIT_REVIEWING_STATUS", "Only changesets with following status: ");
define("_CO_IMAUDIT_REVIEWING_NOTHING", "Nothing to review...");
define("_MD_IMAUDIT_REVIEWING_QUICK", "Quick review:");
define("_MD_IMAUDIT_REVIEWING", "Reviewing");
define("_MD_IMAUDIT_ALL_REVIEW", "All reviews");
define("_MD_IMAUDIT_START_REVIEWING", "Start reviewing");

//define("_MD_IMAUDIT_GO_REVIEWING", "Click <a href='reviewing.php'>here</a> to start reviewing...");
?>