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

use Glpi\Plugin\Hooks;

define('PLUGIN_RESERVATIONINFO_VERSION', '0.0.1');

// Minimal GLPI version, inclusive
define('PLUGIN_RESERVATIONINFO_MIN_GLPI', '10.0.0');

// Maximum GLPI version, exclusive
define('PLUGIN_RESERVATIONINFO_MAX_GLPI', '10.0.99');

if (!defined("PLUGIN_RESERVATIONINFO_DIR")) {
    define("PLUGIN_RESERVATIONINFO_DIR", Plugin::getPhpDir("reservationinfo"));
}
if (!defined("PLUGIN_RESERVATIONINFO_WEB_DIR")) {
    define("PLUGIN_RESERVATIONINFO_WEB_DIR", Plugin::getWebDir("reservationinfo"));
}

if (!defined("PLUGIN_RESERVATIONINFO_DOC_DIR")) {
    define("PLUGIN_RESERVATIONINFO_DOC_DIR", GLPI_PLUGIN_DOC_DIR . "/reservationinfo");
}
if (!file_exists(PLUGIN_RESERVATIONINFO_DOC_DIR)) {
    mkdir(PLUGIN_RESERVATIONINFO_DOC_DIR);
}

if (!defined("PLUGIN_RESERVATIONINFO_CLASS_PATH")) {
    define("PLUGIN_RESERVATIONINFO_CLASS_PATH", PLUGIN_RESERVATIONINFO_DIR . "/inc");
}
if (!file_exists(PLUGIN_RESERVATIONINFO_CLASS_PATH)) {
    mkdir(PLUGIN_RESERVATIONINFO_CLASS_PATH);
}

if (!defined("PLUGIN_RESERVATIONINFO_FRONT_PATH")) {
    define("PLUGIN_RESERVATIONINFO_FRONT_PATH", PLUGIN_RESERVATIONINFO_DIR . "/front");
}
if (!file_exists(PLUGIN_RESERVATIONINFO_FRONT_PATH)) {
    mkdir(PLUGIN_RESERVATIONINFO_FRONT_PATH);
}

/**
 * Get the name and the version of the plugin - REQUIRED
 */
function plugin_version_reservationinfo() {
	return [
		'name'           => __("Reservation Info", "reservationinfo"),
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
    if (version_compare(GLPI_VERSION, '10.0.0', 'lt') || version_compare(GLPI_VERSION, '10.0.7', 'gt')) {
        echo __('This plugin requires GLPI >= 10.0.0 and GLPI < 10.0.8', 'reservationinfo');
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
   
	global $PLUGIN_HOOKS,$CFG_GLPI;
	
	$PLUGIN_HOOKS[Hooks::CSRF_COMPLIANT]['reservationinfo'] = true;
	
	// Add plugin config page:
	if (Session::haveRight('config', UPDATE)) {
		$PLUGIN_HOOKS['config_page']['reservationinfo'] = 'front/config.form.php';
	}
	
	// Load plugin custom class:
	include_once(PLUGIN_RESERVATIONINFO_DIR . "/inc/reservationinfo.class.php");
	$ri = new PluginReservationInfo;
	
	// Exit if plugin is not installed:
	if($ri->getActiveItemTypes() == NULL) return;	
	
	// Exit if current page is not concerned by reservations:
	$current_page = explode('/', $_SERVER["SCRIPT_NAME"]);
	$current_page = $current_page[count($current_page) - 1];
	$current_page = explode('.', $current_page);
	if($current_page[1] == "form") return;	// exit plugin as current page is not concerned by reservations
	$current_page = ucfirst($current_page[0]);
	if($current_page == "Networkequipment") $current_page = "NetworkEquipment";
	
	// Update reservations info:
	if(in_array($current_page, $ri->getActiveItemTypes())){		
		$ri->updateReservationInfoForEachItem($current_page);		
	}
	
	return;
   
}
