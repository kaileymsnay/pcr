<?php
/**
 *
 * Post Count Requirements extension for the phpBB Forum Software package
 *
 * @copyright (c) 2021, Kailey Snay, https://www.snayhomelab.com/
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace kaileymsnay\pcr\migrations\v10x;

class m1_initial_schema extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return $this->db_tools->sql_column_exists($this->table_prefix . 'groups', 'group_no_pcr');
	}

	public static function depends_on()
	{
		return ['\phpbb\db\migration\data\v330\v330'];
	}

	/**
	 * Update database schema
	 */
	public function update_schema()
	{
		return [
			'add_columns'	=> [
				$this->table_prefix . 'forums'			=> [
					'forum_pcr_post'		=> ['UINT', 0],
					'forum_pcr_view'		=> ['UINT', 0],
				],

				$this->table_prefix . 'groups'			=> [
					'group_no_pcr'			=> ['UINT', 0],
				],
			],
		];
	}

	/**
	 * Revert database schema
	 */
	public function revert_schema()
	{
		return [
			'drop_columns'	=> [
				$this->table_prefix . 'forums'			=> [
					'forum_pcr_post',
					'forum_pcr_view',
				],

				$this->table_prefix . 'groups'			=> [
					'group_no_pcr',
				],
			],
		];
	}
}
