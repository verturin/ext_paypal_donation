<?php
/**
 *
 * PayPal Donation extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2015-2020 Skouat
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace skouat\ppde\actions;

class auth
{
	protected $auth;
	protected $phpbb_root_path;
	protected $php_ext;
	protected $config;
	protected $user;
	protected $db;
	/** @var array|null Cached group ids of the current user */
	protected $user_group_ids;

	/**
	 * auth constructor.
	 *
	 * @param \phpbb\auth\auth     $auth            Auth object
	 * @param \phpbb\config\config $config          Config object
	 * @param \phpbb\user          $user            User object
	 * @param string               $phpbb_root_path phpBB root path
	 * @param string               $php_ext         phpEx
	 * @param \phpbb\db\driver\driver_interface $db Database connection
	 *
	 * @access public
	 */

	public function __construct(
		\phpbb\auth\auth $auth,
		\phpbb\config\config $config,
		\phpbb\user $user,
		$phpbb_root_path,
		$php_ext,
		\phpbb\db\driver\driver_interface $db
	)
	{
		$this->auth = $auth;
		$this->config = $config;
		$this->user = $user;
		$this->phpbb_root_path = $phpbb_root_path;
		$this->php_ext = $php_ext;
		$this->db = $db;
	}

	public function set_guest_acl(): void
	{
		if (!class_exists(\auth_admin::class))
		{
			include($this->phpbb_root_path . 'includes/acp/auth.' . $this->php_ext);
		}
		$auth_admin = new \auth_admin();

		$auth['u_ppde_use'] = (int) $this->config['ppde_allow_guest'];
		$auth['u_ppde_view_donorlist'] = (int) $this->config['ppde_ipn_dl_allow_guest'];

		$auth_admin->acl_set('user', [0], [ANONYMOUS], $auth);
	}

	/**
	 * @return bool
	 * @access public
	 */
	public function can_use_ppde(): bool
	{
		return $this->auth->acl_get('u_ppde_use') && $this->is_in_allowed_groups();
	}

	/**
	 * Group ids allowed to see the donation features.
	 * An empty list means "no restriction".
	 *
	 * @return int[]
	 * @access public
	 */
	public function get_allowed_groups(): array
	{
		$value = isset($this->config['ppde_display_groups']) ? (string) $this->config['ppde_display_groups'] : '';

		return array_values(array_unique(array_filter(array_map('intval', explode(',', $value)))));
	}

	/**
	 * Checks whether the current user belongs to at least one of the groups
	 * selected in the ACP (always true when no group is selected).
	 *
	 * @return bool
	 * @access public
	 */
	public function is_in_allowed_groups(): bool
	{
		$allowed_groups = $this->get_allowed_groups();

		if (empty($allowed_groups))
		{
			return true;
		}

		if ($this->user_group_ids === null)
		{
			$this->user_group_ids = [];

			$sql = 'SELECT group_id
				FROM ' . USER_GROUP_TABLE . '
				WHERE user_id = ' . (int) $this->user->data['user_id'] . '
					AND user_pending = 0';
			$result = $this->db->sql_query($sql);
			while ($row = $this->db->sql_fetchrow($result))
			{
				$this->user_group_ids[] = (int) $row['group_id'];
			}
			$this->db->sql_freeresult($result);
		}

		return (bool) array_intersect($allowed_groups, $this->user_group_ids);
	}

	/**
	 * @return bool
	 * @access public
	 */
	public function can_view_ppde_donorlist(): bool
	{
		return $this->auth->acl_get('u_ppde_view_donorlist');
	}

	/**
	 * @return bool
	 * @access public
	 */
	public function can_manage_ppde(): bool
	{
		return $this->auth->acl_get('a_ppde_manage');
	}

	/**
	 * Check we are in the ACP
	 *
	 * @return bool
	 * @access public
	 */
	public function is_in_admin(): bool
	{
		return defined('IN_ADMIN') && isset($this->user->data['session_admin']) && (bool) $this->user->data['session_admin'];
	}
}
