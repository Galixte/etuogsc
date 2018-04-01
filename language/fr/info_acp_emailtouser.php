<?php
/**
 *
 * Email To User On Group Status Change. An extension for the phpBB Forum Software package.
 * French translation by Galixte (http://www.galixte.com)
 *
 * @copyright (c) 2017 RMcGirr83
 * @license GNU General Public License, version 2 (GPL-2.0-only)
 *
 */

/**
 * DO NOT CHANGE
 */
if (!defined('IN_PHPBB'))
{
	exit;
}

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
//
// Some characters you may want to copy&paste:
// ’ « » “ ” …
//

$lang = array_merge($lang, array(
	'LOG_EMAIL_SENT_USER_ADDED_GROUP'		=> '<strong>E-mail envoyé au membre ajouté au groupe d’utilisateurs</strong> %1$s<br />» %2$s',
	'LOG_EMAIL_SENT_USER_DELETED_GROUP'		=> '<strong>E-mail envoyé au membre retiré du groupe d’utilisateurs</strong> %1$s<br />» %2$s',
	'LOG_EMAIL_SENT_USER_PROMOTED_GROUP'	=> '<strong>E-mail envoyé au membre promu chef du groupe d’utilisateurs</strong> %1$s<br />» %2$s',
	'LOG_EMAIL_SENT_USER_DEMOTED_GROUP'		=> '<strong>E-mail envoyé au membre rétrogradé dans le groupe d’utilisateurs</strong> %1$s<br />» %2$s',
	'LOG_EMAIL_SENT_USER_APPROVED_GROUP'	=> '<strong>E-mail envoyé au membre approuvé dans le groupe d’utilisateurs</strong> %1$s<br />» %2$s',	
));
