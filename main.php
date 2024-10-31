<?php
/*
Plugin Name: Ohio Sheriff Tools
Plugin URI: http://www.10twebdesign.com/plugins/ohio-sheriff/
Description: A set of tools designed to provide County Sheriffs in Ohio with handy tools for their WordPress based websites. Originally designed for, and with the help of, the <a href="http://www.belmontsheriff.net/">Belmont County Sheriff's Office</a>. For more information, please visit the plugin's website.
Version: 0.1.1
Author: 10T Web Design
Author URI: http://www.10twebdesign.com/
License: GPL2
*/

/* Copyright 2013 by 10T Web Design (email : brock@10twebdesign.com)
 *
 * This program is free software; you can distribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as
 * published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * A copy of the GNU General Public License, version 2, is located at
 * http://www.gnu.org/licenses/gpl-2.0.html . If not, please write to
 * the Free Software Foundation, Inc., 51 Franklin St, Fifth Floor,
 * Boston, MA 02110-1301 USA.
 */

global $sheriff_db_version;
$sheriff_db_version = 1;

include (plugin_dir_path(__FILE__) . 'admin.php');
include (plugin_dir_path(__FILE__) . 'road.php');
include (plugin_dir_path(__FILE__) . 'sale.php');
include (plugin_dir_path(__FILE__) . 'wanted.php');
include (plugin_dir_path(__FILE__) . 'closures.php');

register_activation_hook(__FILE__, 'sheriff_install');

add_action('admin_menu','sheriff_menu');
add_action('admin_notices','sheriff_admin_notices');
add_action('widgets_init', create_function('', 'register_widget("level_widget");'));

function sheriff_install () {
	sheriff_create_categories();
	sheriff_create_database();
}

function sheriff_create_categories() {
	if(!is_category("Road Levels")) {
		wp_create_category("Road Levels");
	}
	if(!is_category("Wanted")) {
		wp_create_category("Wanted");
	}
	if(!is_category("Sheriff Sale")) {
		wp_create_category("Sheriff Sale");
	}
	if(!is_category("Road Closures")) {
		wp_create_category("Road Closures");
	}
}

function sheriff_create_database() {
	global $wpdb;
	global $sheriff_db_version;
	
	if(get_option('sheriff_db_version') != $sheriff_db_version) {
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		
		$table_name = $wpdb->prefix . "road_closure";
		$sql = "CREATE TABLE " . $table_name . " (
			id bigint(11) NOT NULL AUTO_INCREMENT,
			message VARCHAR(5000) NOT NULL,
			UNIQUE KEY id (id)
		);";
		dbDelta($sql);

		update_option("sheriff_db_version", $sheriff_db_version);
	}
}

function sheriff_admin_notices() {
	if(!get_option('sheriff_county_id') && !$_POST['update_options']) {
?>
	<div class="error">
		<p><strong>Warning!</strong> The Ohio Sheriff Tools plugin has been activated, but no county has been selected. Before using, you should set your county.<br /><br /><a class="button-secondary" href="admin.php?page=sheriff_menu_main" title="Set county">Set county</a></p>
	</div>
<?php
	} 
}

?>
