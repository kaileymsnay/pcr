<?php
/**
 *
 * Post Count Requirements extension for the phpBB Forum Software package
 *
 * @copyright (c) 2021, Kailey Snay, https://www.snayhomelab.com/
 * @license GNU General Public License, version 2 (GPL-2.0)
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
	$lang = [];
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
// ’ » “ ” …
//

$lang = array_merge($lang, [
	'FORUM_PCR_POST'			=> 'Post count requirement (post)',
	'FORUM_PCR_POST_EXPLAIN'	=> 'Number of posts required to post in this forum. Setting the value to 0 disables this behaviour.',
	'FORUM_PCR_VIEW'			=> 'Post count requirement (view)',
	'FORUM_PCR_VIEW_EXPLAIN'	=> 'Number of posts required to view this forum. Setting the value to 0 disables this behaviour.',

	'GROUP_NO_PCR'			=> 'Disable post count requirement',
	'GROUP_NO_PCR_EXPLAIN'	=> 'This group can view and post in post-restricted forums.',

	'PCR_NO_POST'	=> [
		1	=> 'You do not have the required post count to post in this forum. In order to post in this forum, you must have %1$d post.',
		2	=> 'You do not have the required post count to post in this forum. In order to post in this forum, you must have %1$d posts.',
	],
	'PCR_NO_VIEW'	=> [
		1	=> 'You do not have the required post count to view this forum. In order to view this forum, you must have %1$d post.',
		2	=> 'You do not have the required post count to view this forum. In order to view this forum, you must have %1$d posts.',
	],
]);
