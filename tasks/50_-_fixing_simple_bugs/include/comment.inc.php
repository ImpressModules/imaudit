<?php
/**
* Comment include file
*
* File holding functions used by the module to hook with the comment system of ImpressCMS
*
* @copyright	The ImpressCMS Project
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @since		1.0
* @author		marcan <marcan@impresscms.org>
* @package		imaudit
* @version		$Id$
*/

function imaudit_com_update($item_id, $total_num)
{
    $imaudit_post_handler = xoops_getModuleHandler('post', 'imaudit');
    $imaudit_post_handler->updateComments($item_id, $total_num);
}

function imaudit_com_approve(&$comment)
{
    // notification mail here
}

?>