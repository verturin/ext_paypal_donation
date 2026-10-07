<?php
/**
 *
 * PayPal Donation extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2015-2026 Skouat
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace skouat\ppde\migrations\v40x;

class v404_m1_display_groups extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return isset($this->config['ppde_display_groups']);
	}

	public static function depends_on()
	{
		return ['\skouat\ppde\migrations\v40x\v400_m5_widen_amounts'];
	}

	public function update_data()
	{
		return [
			// Comma separated list of group ids allowed to see the donation features (empty = all groups)
			['config.add', ['ppde_display_groups', '']],
		];
	}
}
