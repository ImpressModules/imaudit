<?php
/**
* English language constants commonly used in the module
*
* @copyright	The ImpressCMS Project
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @since		1.0
* @author		marcan <marcan@impresscms.org>
* @package		imaudit
* @version		$Id$
*/

if (!defined("ICMS_ROOT_PATH")) die("ICMS root path not defined");

// changeset
define("_CO_IMAUDIT_CHANGESET_CHANGESET", "Changeset");
define("_CO_IMAUDIT_CHANGESET_TIMESTAMP", "Date");
define("_CO_IMAUDIT_CHANGESET_TIMESTAMP_DSC", "Timestamp of the changeset");
define("_CO_IMAUDIT_CHANGESET_BRANCH_ID", "Branch");
define("_CO_IMAUDIT_CHANGESET_BRANCH_ID_DSC", "");
define("_CO_IMAUDIT_CHANGESET_LINK", "Link");
define("_CO_IMAUDIT_CHANGESET_LINK_DSC", "");
define("_CO_IMAUDIT_CHANGESET_AUTHOR", "Author");
define("_CO_IMAUDIT_CHANGESET_AUTHOR_DSC", "Author of the changeset");
define("_CO_IMAUDIT_CHANGESET_MESSAGE", "Message");
define("_CO_IMAUDIT_CHANGESET_MESSAGE_DSC", "Message explaining the changeset");
define("_CO_IMAUDIT_CHANGESET_STATUS", "Status");
define("_CO_IMAUDIT_CHANGESET_STATUS_DSC", "Status of the changeset");
define("_CO_IMAUDIT_CHANGESET_CHANGESET_NUMBER", "Number");
define("_CO_IMAUDIT_CHANGESET_CHANGESET_NUMBER_DSC", "");
define("_CO_IMAUDIT_CHANGESET_STATUS_ALL", "All changeset");
define("_CO_IMAUDIT_CHANGESET_STATUS_TO_BE_REVIEWED", "Review");
define("_CO_IMAUDIT_CHANGESET_STATUS_TO_BE_REVERTED", "Revert");
define("_CO_IMAUDIT_CHANGESET_STATUS_CHANGE_REQUESTED", "Change");
define("_CO_IMAUDIT_CHANGESET_STATUS_APPROVED", "Approved");
define("_CO_IMAUDIT_CHANGESET_STATUS_APPROVED_NOTIFY", "Approved and notifiy");

// review
define("_CO_IMAUDIT_REVIEW", "Review");
define("_CO_IMAUDIT_REVIEWS", "Reviews");
define("_CO_IMAUDIT_REVIEW_CHANGESET_ID", "Changeset");
define("_CO_IMAUDIT_REVIEW_CHANGESET_ID_DSC", "Changeset linked to this review");
define("_CO_IMAUDIT_REVIEW_REVIEWER", "Reviewer");
define("_CO_IMAUDIT_REVIEW_REVIEWER_DSC", "");
define("_CO_IMAUDIT_REVIEW_COMMENT", "Comment");
define("_CO_IMAUDIT_REVIEW_COMMENT_DSC", "");
define("_CO_IMAUDIT_REVIEW_REVIEW_DATE", "Review date");
define("_CO_IMAUDIT_REVIEW_REVIEW_DATE_DSC", "");
define("_CO_IMAUDIT_REVIEW_SUGGESTED_STATUS", "Status");
define("_CO_IMAUDIT_REVIEW_SUGGESTED_STATUS_DSC", "");
define("_CO_IMAUDIT_REVIEW_NOTIFY_SUBJECT", "Changeset %u reviewed as %s");

// branch
define("_CO_IMAUDIT_BRANCH_NAME", "Name");
define("_CO_IMAUDIT_BRANCH_NAME_DSC", "");
define("_CO_IMAUDIT_BRANCH_DESCRIPTION", "Description");
define("_CO_IMAUDIT_BRANCH_DESCRIPTION_DSC", "");
define("_CO_IMAUDIT_BRANCH_RSSFEED", "RSS Feed");
define("_CO_IMAUDIT_BRANCH_RSSFEED_DSC", "");
define("_CO_IMAUDIT_BRANCH_FIRST_REVISION", "First revision");
define("_CO_IMAUDIT_BRANCH_FIRST_REVISION_DSC", "The very first revision for which you would like to fetch the changesets of this branch.");
define("_CO_IMAUDIT_BRANCH_NOTIFICATION_EMAIL", "Notification email");
define("_CO_IMAUDIT_BRANCH_NOTIFICATION_EMAIL_DSC", "Enter the email address you wish to be notified when a review with a status other then 'Approved' will be made on a changeset for this branch.");
?>