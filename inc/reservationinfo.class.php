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
 * @link      https://github.com/d1m007/ReservationInfo
 * -------------------------------------------------------------------------
 */

class PluginReservationInfo extends CommonGLPI
{

	/**
	* This function is called to create required tables
	*  in the GLPI database
	*/
	public function createPluginDB(){
		
		// Delete existing tables if any:
		self::dropPluginDB(0);

		// Create table to store plugin config:
		$DB = new DB;
		$table = "glpi_plugin_reservationinfo_config";
		$query = "CREATE TABLE `glpi_plugin_reservationinfo_config` (
						`id` INT(10) UNSIGNED,
						`setting` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
						`value` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
						PRIMARY KEY (`id`) USING BTREE
					) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";
		$result = $DB->query($query) or die($DB->error());
		$query = "INSERT INTO `glpi_plugin_reservationinfo_config` (`id`, `setting`, `value`) VALUES
					(0, '0', '".__('Unavailable', 'ReservationInfo')."'),
					(1, '1', '".__('Active', 'ReservationInfo')."'),
					(2, 'Computer', '0'),
					(3, 'Monitor', '0'),
					(4, 'Software', '0'),
					(5, 'NetworkEquipment', '0'),
					(6, 'Peripheral', '0'),
					(7, 'Printer', '0'),
					(8, 'Phone', '0');";
		$result = $DB->query($query) or die($DB->error());
		
		return;
		
	}
	
	/**
	* This function is called to drop plugin tables
	*  from GLPI database
	*/
	public function dropPluginDB($die){
		
		// Drop plugin tables:
		$DB = new DB;
		$table = "glpi_plugin_reservationinfo";	// for old version compatibility
		$query = "DROP TABLE `".$table."`;";	// for old version compatibility
		$result = $DB->query($query);			// for old version compatibility	
		$table = "glpi_plugin_reservationinfo_config";
		$query = "DROP TABLE `".$table."`;";
		if($die) $result = $DB->query($query) or die($DB->error());
		else $result = $DB->query($query);
		
		return;
		
	}

	/**
	* This function is called to get plugin config from DB
	*/
	function getPluginSetting($setting){
		
		$DB = new DB;		
		$table = "glpi_plugin_reservationinfo_config";
		$query = "SELECT `id`,`value` FROM `".$table."` WHERE `setting`='".$setting."';";
		$result = $DB->query($query) or die($DB->error());
		$row = $result->fetch_assoc();
		if(mysqli_num_rows($result) > 0) return ($row['value']);
		else return 0;
		
	}
	
	/**
	* This function is called to save plugin configuration
	* 
	*/
	function savePluginConfig($setting, $value){
		
		$DB = new DB;
		$table = "glpi_plugin_reservationinfo_config";
		$query = "UPDATE `".$table."` SET `value`='".$value."' WHERE `setting`='".$setting."';";
		$result = $DB->query($query) or die($DB->error());
		return true;		
		
	}
	
	/**
	* This function is called to get list of all possible bookable item types
	* 
	*/
	public static function getAllItemTypes()
    {

		return(['Computer','Monitor','Software','NetworkEquipment','Peripheral','Printer','Phone']);

	}

	/**
	* This function is called to get list of item types selected to display info
	* 
	*/
	public static function getActiveItemTypes()
    {
		
		$DB = new DB;		
		$table = "glpi_plugin_reservationinfo_config";
		$query = "SELECT `setting` FROM `".$table."` WHERE `value`='1';";
		$result = $DB->query($query);
		
		if($result){

			$tab = [];
			foreach($result as $row) $tab[] = $row['setting'];
			return ($tab);

		}

		return;

	}

	/**
	* This function is called to add reservation info fields
	*  in available fields to display on items listing pages
	*/
	public function getAddSearchOptions($itemtype)
	{

		$now = date("Y-m-d H:i:s");

		$obj = [	

			65536 => [
				'name' =>  __('Reservation Status', 'ReservationInfo'),
				'table' => 'glpi_plugin_reservationinfo_config',
				'field' => 'value',
				'linkfield' => 'is_active',
				'joinparams' => [
					'jointype' => '',
					'beforejoin' => [
						'table' => 'glpi_reservationitems',
						'joinparams' => [
							'jointype' => 'itemtype_item',
							
						],
					]
				],
				'datatype' => 'dropdown'
			],
			65537 => [
				'table' => 'glpi_users',
				'field' => 'name',
				'name' =>  __('Reservation User', 'ReservationInfo'),
				'joinparams' => [
					'jointype' => '',
					'beforejoin' => [
						'table' => 'glpi_reservations',
						'joinparams' => [
							'jointype' => 'child',
							'condition' => [
								'NEWTABLE.begin' => ['<', $now],
								'NEWTABLE.end' => ['>', $now],
							],
							'beforejoin' => [
								'table' => 'glpi_reservationitems',
								'joinparams' => [
									'jointype' => 'itemtype_item',
								],
							],
						],
					],
				],
				'datatype' => 'dropdown'
			],
			65538 => [
				'table' => 'glpi_reservations',
				'field' => 'begin',
				'name' => __('Reservation Begin', 'ReservationInfo'),
				'joinparams' => [
					'jointype' => 'child',
					'condition' => [
						'NEWTABLE.begin' => ['<', $now],
						'NEWTABLE.end' => ['>', $now],
					],
					'beforejoin' => [
						'table' => 'glpi_reservationitems',
						'joinparams' => [
							'jointype' => 'itemtype_item',
						],
					]
				],
				'datatype' => 'datetime'
			],
			65539 => [
				'table' => 'glpi_reservations',
				'field' => 'end',
				'name' => __('Reservation End', 'ReservationInfo'),
				'joinparams' => [
					'jointype' => 'child',
					'condition' => [
						'NEWTABLE.begin' => ['<', $now],
						'NEWTABLE.end' => ['>', $now],
					],
					'beforejoin' => [
						'table' => 'glpi_reservationitems',
						'joinparams' => [
							'jointype' => 'itemtype_item',
						],
					],
				],
				'datatype' => 'datetime'
			],
			65540 => [
				'table' => 'glpi_reservations',
				'field' => 'comment',
				'name' => __('Reservation Comment', 'ReservationInfo'),
				'joinparams' => [
					'jointype' => 'child',
					'condition' => [
						'NEWTABLE.begin' => ['<', $now],
						'NEWTABLE.end' => ['>', $now],
						],
					'beforejoin' => [
						'table' => 'glpi_reservationitems',
						'joinparams' => [
							'jointype' => 'itemtype_item',
						],
					],
				],				 
				'datatype' => 'text'
			]			

		];

		return $obj;

	}

}
