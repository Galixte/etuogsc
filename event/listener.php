<?php
/**
*
* @package Email to user on group status change
* @copyright (c) 2017 RMcGirr83
* @author Rich McGirr rmcgirr83
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace rmcgirr83\etuogsc\event;

/**
* @ignore
*/
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
* Event listener
*/
class listener implements EventSubscriberInterface
{
	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\db\driver\driver */
	protected $db;

	/** @var \phpbb\log\log */
	protected $log;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\user */
	protected $user;

	/** @var string phpBB root path */
	protected $root_path;

	/** @var string phpEx */
	protected $php_ext;

	public function __construct(
		\phpbb\config\config $config,
		\phpbb\db\driver\driver_interface $db,
		\phpbb\log\log $log,
		\phpbb\template\template $template,
		\phpbb\user $user,
		$root_path,
		$php_ext)
	{
		$this->config 		= $config;
		$this->db			= $db;
		$this->log			= $log;
		$this->template		= $template;
		$this->user			= $user;
		$this->root_path	= $root_path;
		$this->php_ext		= $php_ext;

	}

	static public function getSubscribedEvents()
	{
		return array(
			'core.group_add_user_after'			=> 'email_to_user_add',
			'core.user_set_group_attributes'	=> 'email_to_user_attrib',
			'core.group_delete_user_after'		=> 'email_to_user_delete',
		);
	}

	public function email_to_user_add($event)
	{
		$this->email_to_user($event['user_id_ary'], 'add', $event['pending'], $event['group_id'], $event['group_name']);
	}
	
	public function email_to_user_attrib($event)
	{
		$this->email_to_user($event['user_id_ary'], $event['action'], false, $event['group_id'], $event['group_name']);
	}
	
	public function email_to_user_delete($event)
	{
		$this->email_to_user($event['user_id_ary'], 'delete', false, $event['group_id'], $event['group_name']);
	}

	private function email_to_user($user_id_array, $action = 'default', $pending = false, $group_id = false, $group_name = '')
	{

		// no array set or user is pending or action is default just go back to what we were doing
		if (!sizeof($user_id_array) || $pending || $action == 'default')
		{
			return;
		}

		// grab user(s) data that is getting the email
		$sql = 'SELECT user_id, username, user_email, user_lang, user_jabber, user_notify_type
			FROM ' . USERS_TABLE . '
			WHERE ' . $this->db->sql_in_set('user_id', $user_id_array);
		$result = $this->db->sql_query($sql);
		$email_users = $this->db->sql_fetchrowset($result);
		$this->db->sql_freeresult($result);

		// we didn't get a soul
		if (!sizeof($email_users))
		{
			return;
		}

		$group_name = (empty($group_name)) ? get_group_name((int) $group_id) : $group_name;
		switch ($action)
		{
			case 'add':
				$email_template	= 'group_added';
				$log = 'LOG_EMAIL_SENT_USER_ADDED_GROUP';
			break;

			case 'delete':
				$email_template = '@rmcgirr83_etuogsc/user_delete_group';
				$log = 'LOG_EMAIL_SENT_USER_DELETED_GROUP';
			break;

			case 'promote';
				$email_template = '@rmcgirr83_etuogsc/user_promote_group';
				$log = 'LOG_EMAIL_SENT_USER_PROMOTED_GROUP';
			break;

			case 'demote';
				$email_template = '@rmcgirr83_etuogsc/user_demote_group';
				$log = 'LOG_EMAIL_SENT_USER_DEMOTED_GROUP';
			break;

			case 'approve';
				$email_template = '@rmcgirr83_etuogsc/user_approve_group';
				$log = 'LOG_EMAIL_SENT_USER_APPROVED_GROUP';
			break;

			default;
				return;
		}

		if (!class_exists('messenger'))
		{
			include($this->root_path . 'includes/functions_messenger.' . $this->php_ext);
		}
		$server_url = generate_board_url();

		$messenger = new \messenger(true);

		// Email headers
		$messenger->headers('X-AntiAbuse: Board servername - ' . $this->config['server_name']);
		$messenger->headers('X-AntiAbuse: User_id - ' . $this->user->data['user_id']);
		$messenger->headers('X-AntiAbuse: Username - ' . $this->user->data['username']);
		$messenger->headers('X-AntiAbuse: User IP - ' . $this->user->ip);

		// Loop through our list of users
		$email_users_name = array();
		for ($i = 0, $size = sizeof($email_users); $i < $size; $i++)
		{
			$messenger->template($email_template, $email_users[$i]['user_lang']);
			$messenger->to($email_users[$i]['user_email'], $email_users[$i]['username']);
			$messenger->im($email_users[$i]['user_jabber'], $email_users[$i]['username']);
			$messenger->from($this->config['board_contact']);
			$messenger->replyto($this->config['board_contact']);

			$messenger->assign_vars(array(
				'SITENAME'		=> $this->config['sitename'],
				'USERNAME'		=> $email_users[$i]['username'],
				'GROUP_NAME'	=> $group_name,
				'EMAIL_SIG'		=> $this->config['board_email_sig'],
				'U_GROUP'		=> "$server_url/memberlist.$this->php_ext?mode=group&g={$group_id}")
			);

			$messenger->send($email_users[$i]['user_notify_type']);
			$email_users_name[] = $email_users[$i]['username'];
		}
		// and an entry into the log table
		$this->log->add('admin', $this->user->data['user_id'], $this->user->ip, $log, false, array($group_name, implode(', ', $email_users_name)));
		$messenger->save_queue();
	}
}
