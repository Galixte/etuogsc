<?php
/**
*
* @package Email to user on group status change
* @copyright (c) 2017 RMcGirr83
* @author Rich McGirr rmcgirr83
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/
/**
* @ignore
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

/**
* DO NOT CHANGE
*/
if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine
$lang = array_merge($lang, array(
	'LOG_EMAIL_SENT_USER_ADDED_GROUP'		=> '<strong>Email sent to user added to usergroup</strong> %1$s<br />» %2$s',
	'LOG_EMAIL_SENT_USER_DELETED_GROUP'		=> '<strong>Email sent to user deleted from usergroup</strong> %1$s<br />» %2$s',
	'LOG_EMAIL_SENT_USER_PROMOTED_GROUP'	=> '<strong>Email sent to user promoted to leader in usergroup</strong> %1$s<br />» %2$s',
	'LOG_EMAIL_SENT_USER_DEMOTED_GROUP'		=> '<strong>Email sent to user demoted in usergroup</strong> %1$s<br />» %2$s',	
	'LOG_EMAIL_SENT_USER_APPROVED_GROUP'	=> '<strong>Email sent to user approved in usergroup</strong> %1$s<br />» %2$s',	
));
