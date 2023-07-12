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

include ("../../../inc/includes.php");

// Check GLPI profile rights:
Session::checkRight("config", UPDATE);

// To be available when plugin in not activated:
Plugin::load('ReservationInfo');

Html::header(__('Plugin Reservation Info - Configuration', 'ReservationInfo'), $_SERVER['PHP_SELF'], "config", "plugins");

$ri = new PluginReservationInfo;

$out  = ("\n<div class=\"tab-content p-2 flex-grow-1 card border-start-0\" style=\"min-height: 150px\">");
$out .= ("<table>\n<tbody>\n");
$out .= ("<form action='./saveconfig.php' method='post'>\n");
$out .= ("	" . Html::hidden('_glpi_csrf_token', array('value' => Session::getNewCSRFToken())));
$out .= ("\n");
$out .= ("	<tr><th colspan=\"2\" style=\"font-size:16px; padding-bottom:10px\">" . __("Plugin Reservation Info - Configuration", 'ReservationInfo') . "</th></tr>\n");
$out .= ("<fieldset>\n");
$out .= ("\n");
$out .= ("	<tr><td colspan=\"2\" style=\"padding:20px;\">" . __("Select item types for which to display booking information", 'ReservationInfo') . ":</td></tr>\n");
$out .= ("	<tr><td colspan=\"2\" style=\"padding-left:40px;\">");
$out .= ("		<input type=\"checkbox\" id=\"Computer\" name=\"Computer\" value=\"1\"");
if($ri->getPluginSetting("Computer") == 1) $out .= "checked";
$out .= ">\n";
$out .= ("		<label for=\"Computer\">"._n("Computer", "Computers", 2)."</label>");
$out .= ("	</td></tr>\n");
$out .= ("	<tr><td colspan=\"2\" style=\"padding-left:40px;\">");
$out .= ("		<input type=\"checkbox\" id=\"Monitor\" name=\"Monitor\" value=\"1\"");
if($ri->getPluginSetting("Monitor") == 1) $out .= "checked";
$out .= ">\n";
$out .= ("		<label for=\"Monitor\">"._n("Monitor", "Monitors", 2)."</label>");
$out .= ("	</td></tr>\n");
$out .= ("	<tr><td colspan=\"2\" style=\"padding-left:40px;\">");
$out .= ("		<input type=\"checkbox\" id=\"Software\" name=\"Software\" value=\"1\"");
if($ri->getPluginSetting("Software") == 1) $out .= "checked";
$out .= ">\n";
$out .= ("		<label for=\"Software\">"._n("Software", "Softwares", 2)."</label>");
$out .= ("	</td></tr>\n");
$out .= ("	<tr><td colspan=\"2\" style=\"padding-left:40px;\">");
$out .= ("		<input type=\"checkbox\" id=\"NetworkEquipment\" name=\"NetworkEquipment\" value=\"1\"");
if($ri->getPluginSetting("NetworkEquipment") == 1) $out .= "checked";
$out .= ">\n";
$out .= ("		<label for=\"NetworkEquipment\">"._n("Network device", "Network devices", 2)."</label>");
$out .= ("	</td></tr>\n");
$out .= ("	<tr><td colspan=\"2\" style=\"padding-left:40px;\">");
$out .= ("		<input type=\"checkbox\" id=\"Peripheral\" name=\"Peripheral\" value=\"1\"");
if($ri->getPluginSetting("Peripheral") == 1) $out .= "checked";
$out .= ">\n";
$out .= ("		<label for=\"Peripheral\">"._n("Device", "Devices", 2)."</label>");
$out .= ("	</td></tr>\n");
$out .= ("	<tr><td colspan=\"2\" style=\"padding-left:40px;\">");
$out .= ("		<input type=\"checkbox\" id=\"Printer\" name=\"Printer\" value=\"1\"");
if($ri->getPluginSetting("Printer") == 1) $out .= "checked";
$out .= ">\n";
$out .= ("		<label for=\"Printer\">"._n("Printer", "Printers", 2)."</label>");
$out .= ("	</td></tr>\n");
$out .= ("	<tr><td colspan=\"2\" style=\"padding-left:40px;\">");
$out .= ("		<input type=\"checkbox\" id=\"Phone\" name=\"Phone\" value=\"1\"");
if($ri->getPluginSetting("Phone") == 1) $out .= "checked";
$out .= ">\n";
$out .= ("		<label for=\"Phone\">"._n("Phone", "Phones", 2)."</label>");
$out .= ("	</td></tr>\n");
$out .= ("	<tr><td colspan=\"2\" style=\"padding-top: 20px;padding-left: 60px; \"><input type=\"submit\" class=\"submit\" value=\"" . __('Save', 'ReservationInfo') . "\" name=\"save\"/></td>\n");
$out .= ("</fieldset>\n</form>\n</tbody>\n</table>\n</div>\n");

echo($out);

Html::footer();
