<?php
/** 
 * @package     VikRentCar
 * @subpackage  core
 * @author      E4J s.r.l.
 * @copyright   Copyright (C) 2022 E4J s.r.l. All Rights Reserved.
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link        https://vikwp.com
 */

// No direct access
defined('ABSPATH') or die('No script kiddies please!');

/**
 * Implements the event dispatcher interface for the WordPress platform.
 * 
 * @since 1.3.0
 */
class VRCPlatformOrgWordpressDispatcher implements VRCPlatformDispatcherInterface
{
	/**
	 * Triggers the specified event by passing the given argument.
	 * No return value is expected here.
	 * 
	 * @param   string  $event  The event to trigger.
	 * @param   array   $args   The event arguments.
	 * 
	 * @return  void
	 */
	public function trigger($event, array $args = [])
	{
		do_action_ref_array($event, $args);
	}

	/**
	 * Triggers the specified event by passing the given argument.
	 * At least a return value is expected here.
	 * 
	 * @param   string  $event  The event to trigger.
	 * @param   array   $args   The event arguments.
	 * 
	 * @return  array   A list of returned values.
	 */
	public function filter($event, array $args = [])
	{
		if (!$args)
		{
			// inject argument at the beginning of the list
			$args[] = null;
		}

		$return = apply_filters_ref_array($event, $args);

		if (is_null($return))
		{
			// no attached hooks
			return [];
		}

		// wrap returned value into an array
		return [$return];
	}
}
