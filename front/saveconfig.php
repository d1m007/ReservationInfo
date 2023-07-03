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
 * @link      https://github.com/dim00z/reservationinfo
 * -------------------------------------------------------------------------
 */

include('../../../inc/includes.php');

// Check GLPI profile rights on this page:
Session::checkRight("config", UPDATE);

// Save plugin configuration in GLPI database
if ($_POST && isset($_POST['save'])) {

	$ri = new PluginReservationInfo;
	$list = $ri->getAllItemTypes();
	
	foreach($list as $itemtype){
		
		if(isset($_POST[$itemtype])) $ri->savePluginConfig($itemtype, $_POST[$itemtype]);
		else $ri->savePluginConfig($itemtype, 0);
		
	}

	if(isset($_POST["Update_items_users"])) $ri->savePluginConfig("Update_items_users", 1);
	else $ri->savePluginConfig("Update_items_users", 0);

	// Redirect the user to previous page:
	Html::back();
 
}
