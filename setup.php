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

use Glpi\Plugin\Hooks;

define('PLUGIN_RESERVATIONINFO_VERSION', '0.0.5');

// Minimal GLPI version, inclusive
define('PLUGIN_RESERVATIONINFO_MIN_GLPI', '10.0.0');

// Maximum GLPI version, exclusive
define('PLUGIN_RESERVATIONINFO_MAX_GLPI', '10.0.10');

if (!defined("PLUGIN_RESERVATIONINFO_DIR")) {
    define("PLUGIN_RESERVATIONINFO_DIR", Plugin::getPhpDir("ReservationInfo"));
}

/**
 * Get the name and the version of the plugin - REQUIRED
 */
function plugin_version_reservationinfo() {
	return [
		'name'           => __("Reservation Info", "ReservationInfo"),
		'version'        => PLUGIN_RESERVATIONINFO_VERSION,
		'author'         => 'Dimitri Mestdagh',
		'license'        => 'GPLv3.0',
		'homepage'       => 'https://github.com/dim00z/ReservationInfo',
		'requirements'   => [
			'glpi' => [
				'min' => PLUGIN_RESERVATIONINFO_MIN_GLPI,
				'max' => PLUGIN_RESERVATIONINFO_MAX_GLPI,
			]
		]
	];
}

/**
 *  Check if the config is ok - REQUIRED
 */
function plugin_reservationinfo_check_config() {
    return true;
}

/**
 * Check if the prerequisites of the plugin are satisfied - REQUIRED
 */
function plugin_reservationinfo_check_prerequisites() {
 
    // Check that GLPI version is compatible:
    if (version_compare(GLPI_VERSION, PLUGIN_RESERVATIONINFO_MIN_GLPI, 'lt') || version_compare(GLPI_VERSION, PLUGIN_RESERVATIONINFO_MAX_GLPI, 'gt')) {
        echo __('This plugin requires GLPI >= '.PLUGIN_RESERVATIONINFO_MIN_GLPI.' and GLPI < ' . PLUGIN_RESERVATIONINFO_MAX_GLPI, 'ReservationInfo');
        return false;
    }
 
    return true;
}

/**
 * Init hooks of the plugin - REQUIRED
 *
 * @return void
 */
function plugin_init_reservationinfo() {
   
	global $PLUGIN_HOOKS;
	
	$PLUGIN_HOOKS[Hooks::CSRF_COMPLIANT]['ReservationInfo'] = true;
	
	// Load plugin custom class:
	include_once(PLUGIN_RESERVATIONINFO_DIR . "/inc/reservationinfo.class.php");
		
	// Exit if plugin is not installed/active:
	if (!Plugin::isPluginActive('ReservationInfo')) return;
	
	// Add plugin config page:
	if (Session::haveRight('config', UPDATE)) {
		$PLUGIN_HOOKS['config_page']['ReservationInfo'] = 'front/config.form.php';
	}

	return;
   
}
