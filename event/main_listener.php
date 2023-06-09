<?php
/**
 *
 * Post Count Requirements extension for the phpBB Forum Software package
 *
 * @copyright (c) 2021, Kailey Snay, https://www.snayhomelab.com/
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace kaileymsnay\pcr\event;

/**
 * @ignore
 */
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Post Count Requirements event listener
 */
class main_listener implements EventSubscriberInterface
{
	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/** @var \phpbb\language\language */
	protected $language;

	/** @var \phpbb\request\request */
	protected $request;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\user */
	protected $user;

	/** @var */
	protected $tables;

	/**
	 * Constructor
	 *
	 * @param \phpbb\db\driver\driver_interface  $db
	 * @param \phpbb\language\language           $language
	 * @param \phpbb\request\request             $request
	 * @param \phpbb\template\template           $template
	 * @param \phpbb\user                        $user
	 * @param                                    $tables
	 */
	public function __construct(\phpbb\db\driver\driver_interface $db, \phpbb\language\language $language, \phpbb\request\request $request, \phpbb\template\template $template, \phpbb\user $user, $tables)
	{
		$this->db = $db;
		$this->language = $language;
		$this->request = $request;
		$this->template = $template;
		$this->user = $user;
		$this->tables = $tables;
	}

	public static function getSubscribedEvents()
	{
		return [
			'core.acp_manage_forums_display_form'		=> 'acp_manage_forums_display_form',
			'core.acp_manage_forums_initialise_data'	=> 'acp_manage_forums_initialise_data',
			'core.acp_manage_forums_request_data'		=> 'acp_manage_forums_request_data',

			'core.acp_manage_group_display_form'	=> 'acp_manage_group_display_form',
			'core.acp_manage_group_initialise_data'	=> 'acp_manage_group_initialise_data',
			'core.acp_manage_group_request_data'	=> 'acp_manage_group_request_data',

			'core.posting_modify_template_vars'	=> 'posting_modify_template_vars',

			'core.search_modify_param_before'	=> 'search_modify_param_before',

			'core.user_setup'	=> 'user_setup',

			'core.viewforum_get_topic_data'	=> 'viewforum_viewtopic_get_data',
			'core.viewtopic_get_post_data'	=> 'viewforum_viewtopic_get_data',
		];
	}

	public function acp_manage_forums_display_form($event)
	{
		$data = [
			'FORUM_PCR_POST'	=> $event['forum_data']['forum_pcr_post'],
			'FORUM_PCR_VIEW'	=> $event['forum_data']['forum_pcr_view'],
		];

		foreach ($data as $key => $value)
		{
			$event->update_subarray('template_data', $key, $value);
		}
	}

	public function acp_manage_forums_initialise_data($event)
	{
		if ($event['action'] == 'add')
		{
			$data = [
				'forum_pcr_post'	=> 0,
				'forum_pcr_view'	=> 0,
			];

			foreach ($data as $key => $value)
			{
				$event->update_subarray('forum_data', $key, $value);
			}
		}
	}

	public function acp_manage_forums_request_data($event)
	{
		$data = [
			'forum_pcr_post'	=> $this->request->variable('forum_pcr_post', 0),
			'forum_pcr_view'	=> $this->request->variable('forum_pcr_view', 0),
		];

		foreach ($data as $key => $value)
		{
			$event->update_subarray('forum_data', $key, $value);
		}
	}

	public function acp_manage_group_display_form($event)
	{
		$group_row = $event['group_row'];

		$this->template->assign_vars([
			'GROUP_NO_PCR'	=> (isset($group_row['group_no_pcr']) && $group_row['group_no_pcr']) ? ' checked="checked"' : '',
		]);
	}

	public function acp_manage_group_initialise_data($event)
	{
		$event->update_subarray('test_variables', 'no_pcr', 'int');
	}

	public function acp_manage_group_request_data($event)
	{
		$event->update_subarray('submit_ary', 'no_pcr', $this->request->variable('group_no_pcr', 0));
	}

	public function posting_modify_template_vars($event)
	{
		$group_no_pcr = $this->query_groups();

		if (!$group_no_pcr)
		{
			$sql = 'SELECT forum_pcr_post
				FROM ' . $this->tables['forums'] . '
				WHERE forum_id = ' . (int) $event['forum_id'];
			$result = $this->db->sql_query($sql);
			$forum_data = $this->db->sql_fetchrow($result);
			$this->db->sql_freeresult($result);

			if ((int) $this->user->data['user_posts'] < (int) $forum_data['forum_pcr_post'])
			{
				trigger_error($this->language->lang('PCR_NO_POST', (int) $forum_data['forum_pcr_post']));
			}
		}
	}

	public function search_modify_param_before($event)
	{
		$ex_fid_ary = $event['ex_fid_ary'];

		$group_no_pcr = $this->query_groups();

		if (!$group_no_pcr)
		{
			$sql = 'SELECT forum_pcr_view, forum_id
				FROM ' . $this->tables['forums'] . '
				WHERE forum_pcr_view > ' . (int) $this->user->data['user_posts'];
			$result = $this->db->sql_query($sql);
			while ($row = $this->db->sql_fetchrow($result))
			{
				$ex_fid_ary[] = $row['forum_id'];
			}
			$this->db->sql_freeresult($result);
		}

		$event['ex_fid_ary'] = $ex_fid_ary;
	}

	public function user_setup($event)
	{
		$lang_set_ext = $event['lang_set_ext'];
		$lang_set_ext[] = [
			'ext_name' => 'kaileymsnay/pcr',
			'lang_set' => 'common',
		];
		$event['lang_set_ext'] = $lang_set_ext;
	}

	public function viewforum_viewtopic_get_data($event)
	{
		$group_no_pcr = $this->query_groups();

		if (!$group_no_pcr)
		{
			$sql = 'SELECT forum_pcr_view
				FROM ' . $this->tables['forums'] . '
				WHERE forum_id = ' . (int) $event['forum_id'];
			$result = $this->db->sql_query($sql);
			$forum_data = $this->db->sql_fetchrow($result);
			$this->db->sql_freeresult($result);

			if ((int) $this->user->data['user_posts'] < (int) $forum_data['forum_pcr_view'])
			{
				trigger_error($this->language->lang('PCR_NO_VIEW', (int) $forum_data['forum_pcr_view']));
			}
		}
	}

	private function query_groups()
	{
		$sql = 'SELECT COUNT(g.group_no_pcr) as group_no_pcr
			FROM ' . $this->tables['user_group'] . ' ug, ' . $this->tables['groups'] . ' g
			WHERE g.group_no_pcr = 1
				AND ug.group_id = g.group_id
				AND ug.user_id = ' . (int) $this->user->data['user_id'];
		$result = $this->db->sql_query($sql);
		$group_no_pcr = (int) $this->db->sql_fetchfield('group_no_pcr');
		$this->db->sql_freeresult($result);

		return $group_no_pcr;
	}
}
