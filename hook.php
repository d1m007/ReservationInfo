<?php

/**
 * -------------------------------------------------------------------------
 * Reservation Info plugin for GLPI
 * -------------------------------------------------------------------------
 *
 * LICENSE
 *
 * This file is part of the Reservation Info plugin for GLPI.
 *
 * Reservation Info is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * Reservation Info is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Reservation Info. If not, see <http://www.gnu.org/licenses/>.
 * -------------------------------------------------------------------------
 * @copyright Copyright (C) 2023 by Dimitri Mestdagh.
 * @license   GPLv3 https://www.gnu.org/licenses/gpl-3.0.html
 * @link      https://github.com/dim00z/ReservationInfo
 * -------------------------------------------------------------------------
 */

/**
 * Called when user click Install - REQUIRED
 */
function plugin_reservationinfo_install() {
	
	// Create database table:
	$ri = new PluginReservationInfo;
    $ri->createPluginDB();
	
	return true;
	
}
 
/**
 * Called when user click Uninstall - REQUIRED
 */
function plugin_reservationinfo_uninstall() { 

	// Drop database table:
	$ri = new PluginReservationInfo;
    $ri->dropPluginDB();
	
	return true;
	
}

/**
 * Called to add the plugin fields in possibly displayed fields
 */
function plugin_reservationinfo_getAddSearchOptions($itemtype)
{
    $ri = new PluginReservationInfo;
	$itemtypes = $ri->getActiveItemTypes();

	if(in_array($itemtype, $itemtypes)){
		if (
			isset($_SESSION['glpiactiveentities'])
			&& is_array($_SESSION['glpiactiveentities'])
			&& count($_SESSION['glpiactiveentities']) > 0
		) {
			
			return $ri->getAddSearchOptions($itemtype);

		}
	}

    return null;
	
}

/**
 * Called when plugin is init - REQUIRED
 */
function plugin_reservationinfo_postinit() {

}
