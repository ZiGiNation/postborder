<?php
/**
 *
 * Post Border extension for the phpBB Forum Software package
 *
 * @copyright (c) 2020, Kailey Truscott, https://www.layer-3.org/
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace kinerity\postborder\event;

/**
 * @ignore
 */
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Post Border event listener
 */
class main_listener implements EventSubscriberInterface
{
	public static function getSubscribedEvents()
	{
		return [
			'core.viewtopic_cache_user_data'	=> 'viewtopic_cache_user_data',
			'core.viewtopic_modify_post_row'	=> 'viewtopic_modify_post_row',
		];
	}

	public function viewtopic_cache_user_data($event)
	{
		$row = $event['row'];

		$team_auth = new \phpbb\auth\auth();
		$team_auth->acl($row);
		$event->update_subarray('user_cache_data', 's_team_user', ($team_auth->acl_get('a_') || $team_auth->acl_getf_global('m_')) ? true : false);
		unset($team_auth);
	}

	public function viewtopic_modify_post_row($event)
	{
		$event['post_row'] = array_merge($event['post_row'], [
			'S_TEAM_USER'	=> $event['user_poster_data']['s_team_user'],
			'GROUP_COLOR'	=> $event['user_poster_data']['author_colour'],
		]);
	}
}
