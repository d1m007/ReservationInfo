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

class PluginReservationInfo extends CommonGLPI
{

	/**
     * This function is called to create required tables
	 *  in the GLPI database
     */
	public function createPluginDB(){
		
		// Table to store reservations info:
		$DB = new DB;
		$table = "glpi_plugin_reservationinfo";
		$query = "CREATE TABLE `".$table."` (
						`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
						`items_id` INT(10) UNSIGNED NOT NULL,
						`itemtype` VARCHAR(255) DEFAULT 'Computer',
						`status` VARCHAR(255) DEFAULT NULL,
						`users_id` INT(10) UNSIGNED NOT NULL DEFAULT 0,
						`begin` VARCHAR(255) DEFAULT NULL,
						`end` VARCHAR(255) DEFAULT NULL,
						`comment` text DEFAULT NULL,
						PRIMARY KEY (`id`) USING BTREE
					) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;";
		$result = $DB->query($query);	
		
		// Table to store plugin config:
		$DB = new DB;
		$table = "glpi_plugin_reservationinfo_config";
		$query = "CREATE TABLE `glpi_plugin_reservationinfo_config` (
						`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
						`setting` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
						`value` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
						PRIMARY KEY (`id`) USING BTREE
					) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";
		$result = $DB->query($query) or die($DB->error());
		$query = "INSERT INTO `glpi_plugin_reservationinfo_config` (`id`, `setting`, `value`) VALUES
					(1, 'Computer', '0'),
					(2, 'Monitor', '0'),
					(3, 'Software', '0'),
					(4, 'NetworkEquipment', '0'),
					(5, 'Peripheral', '0'),
					(6, 'Printer', '0'),
					(7, 'Phone', '0'),
					(8, 'Update_items_users', '0');";
		$result = $DB->query($query) or die($DB->error());
		
		return;
		
	}
	
	/**
     * This function is called to drop plugin tables
	 *  from the GLPI database
     */
	public function dropPluginDB(){
		
		// Drop plugin tables:
		$DB = new DB;
		$table = "glpi_plugin_reservationinfo";
		$query = "DROP TABLE `".$table."`;";
		$result = $DB->query($query) or die($DB->error());
		$table = "glpi_plugin_reservationinfo_config";
		$query = "DROP TABLE `".$table."`;";
		$result = $DB->query($query) or die($DB->error());
		
		return;
		
	}

	/**
    * Function to update a row in the database or insert a new one
    *
    */
	public function updateOrInsert($table, $fields, $values, $where) {

		$DB = new DB;

		$query = "SELECT `".$fields[0]."` FROM `".$table."` WHERE ".$where.";";
		$result = $DB->query($query) or die($DB->error());
		$nb_results = mysqli_num_rows($result);
		if ($nb_results == 0) {
			$query = "INSERT INTO `".$table."` (";
			for($i=0; $i<count($fields); $i++){
				$query_f[] = "`".$fields[$i]."`";
			}
			for($i=0; $i<count($values); $i++){
				$query_v[] = "'".$values[$i]."'";
			}			
			$query = $query . implode(",", $query_f) . ") VALUES (" . implode(",", $query_v) . ");";
		} else if ($nb_results > 0) {
			$query = "UPDATE `".$table."` SET ";
			for($i=0; $i<count($fields); $i++){
				$query_args[] = "`" . $fields[$i] . "`='".$values[$i]."'";
			}
			$query = $query . implode(",", $query_args) . " WHERE " . $where . ";";
		}
		$result = $DB->query($query) or die($DB->error());
		
		return;
	}

	/**
     * This function is called to add reservation info fields
	 *  in available fields to display on items listing pages
     */
	public function getAddSearchOptions($itemtype)
	{

		$obj = array (
			65536 => 
			array (
				'table' => 'glpi_plugin_reservationinfo',
				'field' => 'status',
				'name' => __('Reservation Status', 'reservationinfo'),
				'joinparams' => 
				array (
				'jointype' => 'itemtype_item',
				),
				'datatype' => 'string',
			),
			65537 => 
			array (
				'table' => 'glpi_users',
				'field' => 'name',
				'name' =>  __('Reservation User', 'reservationinfo'),
				'joinparams' => 
				array (
				'jointype' => '',
				'beforejoin' => 
				array (
					'table' => 'glpi_plugin_reservationinfo',
					'joinparams' => 
					array (
					'jointype' => 'itemtype_item',
					),
				),
				),
				'datatype' => 'dropdown',
			),
			65538 => 
			array (
				'table' => 'glpi_plugin_reservationinfo',
				'field' => 'begin',
				'name' => __('Reservation Begin', 'reservationinfo'),
				'joinparams' => 
				array (
				'jointype' => 'itemtype_item',
				),
				'datatype' => 'datetime',
			),
			65539 => 
			array (
				'table' => 'glpi_plugin_reservationinfo',
				'field' => 'end',
				'name' => __('Reservation End', 'reservationinfo'),
				'joinparams' => 
				array (
				'jointype' => 'itemtype_item',
				),
				'datatype' => 'datetime',
			),
			65540 => 
			array (
				'table' => 'glpi_plugin_reservationinfo',
				'field' => 'comment',
				'name' => __('Reservation Comment', 'reservationinfo'),
				'joinparams' => 
				array (
				'jointype' => 'itemtype_item',
				),
				'datatype' => 'text',
			)
			
		);

		return $obj;

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
		$fields = ["setting", "value"];
		$values = [$setting, $value];
		$where = "`setting`='".$setting."'";

		self::updateOrInsert($table, $fields, $values, $where);
		
		return true;
		
	}

	/**
     * This function is called to get list of all possible bookable item types
     * 
     */
	public static function getAllItemTypes()
    {

		return(array('Computer','Monitor','Software','NetworkEquipment','Peripheral','Printer','Phone'));

	}
	
	/**
     * This function is called to get list of item types selected to display info
     * 
     */
	public static function getActiveItemTypes()
    {
		
		$DB = new DB;		
		$table = "glpi_plugin_reservationinfo_config";
		$query = "SELECT `setting`,`value` FROM `".$table."` WHERE `value`='1';";
		$result = $DB->query($query);
		
		if($result){

			$types = self::getAllItemTypes();
			$tab = array();

			foreach($result as $row){
				foreach($row as $setting){
					if(in_array($setting, $types)){
		
							$tab[] = $row['setting'];
		
					}
				}
			}

			return ($tab);

		}

		return;

	}
	
	/**
     * This function is used to get item from reservation itemId
     *  
     */
	private function getItemFromReservationItemId($item_type, $reservationitem_id){

		$DB = new DB;		
		$table = "glpi_".strtolower($item_type)."s";
		$query = "SELECT `id`,`name` FROM `".$table."` WHERE `id`='".$reservationitem_id."';";
		$result = $DB->query($query) or die($DB->error());
		$row = $result->fetch_assoc();
		
		return ($row);
		 
	}

	/**
     * This function is used to set item reservation info
     *  
     */	
	private function setItemReservationInfo($item_type, $item_id, $user_id, $status, $begin, $end, $comment){
		 
		$DB = new DB;
		
		// Set booking user as item user in item table ("Computer", "Monitor", ...):
		// (only if config option is selected)
		if(self::getPluginSetting("Update_items_users") == 1) {
			$table = "glpi_".strtolower($item_type)."s";
			$query = "UPDATE `".$table."` SET `users_id`=".$user_id." WHERE `".$table."`.`id`=".$item_id.";";
			$result = $DB->query($query) or die($DB->error());
		}
		
		// Update reservation info for current item:
		$table = "glpi_plugin_reservationinfo";
		$fields = ["items_id", "itemtype", "status", "users_id", "begin", "end", "comment"];
		$values = [$item_id, $item_type, $status, $user_id, $begin, $end, $comment];
		$where = "`".$table."`.`items_id`='".$item_id."' AND `".$table."`.`itemtype`='".$item_type."'";

		self::updateOrInsert($table, $fields, $values, $where);

		return true;
		 
	}

	/**
     * This function is used to updat reservation info for each item
     *  
     */	
	public function updateReservationInfoForEachItem($item_type){
		
		$DB = new DB;

		// Update status for non-bookable iems:
		$table = "glpi_" . strtolower($item_type)."s";
		$query_items = "SELECT `id` FROM `" . $table . "`;";
		$items = $DB->query($query_items) or die($DB->error());
		foreach($items as $item){
			$table = "glpi_reservationitems";
			$query_bookable_item = "SELECT `items_id` FROM `" . $table . "` WHERE `" . $table . "`.`itemtype`='" . $item_type . "' AND  `" . $table . "`.`items_id`='" . $item['id'] . "';";
			$bookable_items = $DB->query($query_bookable_item) or die($DB->error());
			if(mysqli_num_rows($bookable_items) == 0){
				$booking_status = __('Not_bookable', 'reservationinfo');
				self::setItemReservationInfo($item_type, $item['id'], 0, $booking_status, "", "", "");
			}
		}

		// Get all bookable items of current item type:
		$table = "glpi_reservationitems";
		$query_reservationitems = "SELECT * FROM `" . $table . "` WHERE `" . $table . "`.`itemtype`='" . $item_type . "';";
		$reservationitems = $DB->query($query_reservationitems) or die($DB->error());
		
		// For each bookable item:
		foreach($reservationitems as $reservationitem){
			
			$booked_item = 0;

			// Get item settings:
			$item = self::getItemFromReservationItemId($reservationitem['itemtype'], $reservationitem['items_id']);

			// Check if booking is active for this item:
			$query_active_booking = "SELECT `is_active` FROM `glpi_reservationitems` WHERE `id`=".$reservationitem['id'].";";
			$result_active_booking = $DB->query($query_active_booking) or die($DB->error());
			$item_active = $result_active_booking->fetch_assoc();
			$item_active = $item_active['is_active'];
			$booking_status = __('Not_available', 'reservationinfo');	// default value
			
			// Check if item is currently booked:
			$query_curr_booking = "SELECT * FROM `glpi_reservations` WHERE `reservationitems_id`=".$reservationitem['id']." AND (`begin`<NOW() AND `end`>NOW());";
			$result_curr_booking = $DB->query($query_curr_booking) or die($DB->error());
			$booked_item = mysqli_num_rows($result_curr_booking);
			
			// If item is currently booked:
			if($booked_item == 1){
				
				// For each currently booked item:
				$booking = $result_curr_booking->fetch_assoc();			
				if($item_active == 1) $booking_status = __('Booked', 'reservationinfo');
				
				// Update item user and booking information tables:
				self::setItemReservationInfo($reservationitem['itemtype'], $item['id'], $booking['users_id'], $booking_status, $booking['begin'], $booking['end'], addslashes($booking['comment']));
				
			}
			// If item is currently available:
			else {
				
				// Update item status (empty 'User' and 'Comment' fields for this item):
				if($item_active == 1) $booking_status = __('Available', 'reservationinfo');
				self::setItemReservationInfo($reservationitem['itemtype'], $item['id'], 0, $booking_status, "", "", "");
				
			}
			
		}
		
		return true;
		
	}

	/**
     * function to display var info in alert box
     */
	public function alert($msg){

		echo('<script type="text/javascript">alert("'.$msg.'");</script>');
		return;
		
	}
	
	/**
     * function to display var info on page (debug purposes)
     */
	public function echo_msg($msg){

		/* $ret = "";
		if(is_array($msg)) {
			$ret = ;
		} */
		echo($msg . " <br/>\n");
		//var_export($msg);
		return;
	
	}
	
}
