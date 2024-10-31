<?php

function sheriff_menu() {
	add_menu_page('Sheriff Tools','Sheriff Tools','edit_published_posts','sheriff_menu_main','sheriff_menu_main');
	add_submenu_page('sheriff_menu_main','Sheriff Sales','Sheriff Sales','edit_published_posts','sheriff_menu_sale','sheriff_menu_sale');
	add_submenu_page('sheriff_menu_main','Road Levels','Road Levels','edit_published_posts','sheriff_menu_road','sheriff_menu_road');
	add_submenu_page('sheriff_menu_main','Wanted','Wanted','edit_published_posts','sheriff_menu_wanted','sheriff_menu_wanted');
	add_submenu_page('sheriff_menu_main','Road Closures','Road Closures','edit_published_posts','sheriff_menu_closures','sheriff_menu_closures');
}

function sheriff_menu_main() {
	sheriff_admin_header("Options");
	sheriff_admin_options_save();
	sheriff_admin_options_form();	
	sheriff_admin_footer();
}

function sheriff_admin_options_checkbox($label, $name, $option_name, $option_description = FALSE) {
?>
	<tr valign="top">
		<th scope="row"><?php _e($label); ?></th>
		<td><input type="checkbox" name="<?php echo $name; ?>" value="1"<?php if(get_option($option_name)) { ?>	checked="Checked"<?php } ?> />
<?php 
		if($option_description && get_option($option_name)) {
?>
			<p class="description"><?php echo $option_description; ?></p>
<?php 	
		}
?>
		</td>	
	</tr>
<?php 
}

function sheriff_admin_options_form() {
?>
	<form action="<?php echo str_replace('%7E', '~', $_SERVER['REQUEST_URI']); ?>" method="POST">
		<h3><?php _e('General Options'); ?></h3>
		<table class="form-table">
			<tbody>
				<?php sheriff_admin_options_county_select(); ?>
			</tbody>
		</table>
		<h3><?php _e('Road Levels'); ?></h3>
		<table class="form-table">
			<tbody>
<?php 
				sheriff_admin_options_checkbox("Enable road levels.", "road_levels", "enable_road_levels", "Turning off road levels will reset all of the options in this section.");
				if(get_option('enable_road_levels')) {
					sheriff_admin_options_checkbox("Hide widget when there is no level?", "road_hide_widget", "road_hide_widget", "Hide the road level widget when there is no current snow emergency. This should only be done in nice weather.");
					sheriff_admin_options_checkbox("Automatically create posts when road level changes?", "road_create_post", "road_create_post", "Automatically create a news post anytime the level is updated. Very useful if your news feed automatically posts to Facebook or other social networking websites.");
	 			}
?>
			</tbody>
		</table>
		<h3><?php _e('Road Closures'); ?></h3>
		<table class="form-table">
			<tbody>
<?php 
				sheriff_admin_options_checkbox("Enable road closures.", "road_closures", "enable_road_closures");
				if(get_option('enable_road_closures')) {
					sheriff_admin_options_checkbox("Automatically create posts when a road closure is created?", "closure_create_post", "closure_create_post", "Automatically create a news post anytime a road closure is created. Very userful if your news feed automatically posts to Facebook or other social networking websites.");	
				}
?>
			</tbody>
		</table>
		<h3><?php _e('Sheriff Sale'); ?></h3>
		<table class="form-table">
			<tbody>
				<p style="font-weight:bold;">Sheriff Sale functionality will be added in a future release. Please stay tuned.</p>
<?php 
				sheriff_admin_options_checkbox("Enable Sheriff sales.", "sheriff_sales", "enable_sheriff_sales");
?>
			</tbody>
		</table>
		<h3><?php _e('Wanted'); ?></h3>
		<table class="form-table">
			<tbody>
				<p style="font-weight:bold;">Sheriff Sale functionality will be added in a future release. Please stay tuned.</p>
<?php 
				sheriff_admin_options_checkbox("Enable wanted database.", "wanted_database", "enable_wanted_database");
?>
			</tbody>
		</table>
		<p class="submit"><input type="submit" name="update_options" id="update_options" class="button-primary" value="Save Options" /></p>
	</form>
<?php 
}

function sheriff_admin_options_save() {
	if($_POST['update_options'] == "Save Options") {
		update_option('sheriff_county_id', $_POST['sheriff_county_id']);
		update_option('enable_road_levels', $_POST['road_levels']); 
		if(!$_POST['road_levels']) {
			/* Remove all levels if road levels are disabled. */
			update_option('road_level', 0);
		}
		update_option('road_hide_widget', $_POST['road_hide_widget']);
		update_option('road_create_post', $_POST['road_create_post']);

		update_option('enable_road_closures', $_POST['road_closures']);
		if(get_option('enable_road_closures')) {
			create_road_closures_page();
		} else {
			remove_road_closures_page();
		}
		update_option('closure_create_post', $_POST['closure_create_post']);

		update_option('enable_sheriff_sales', $_POST['sheriff_sales']);

		update_option('enable_wanted_database', $_POST['wanted_database']);
	}
}

function sheriff_admin_header($title) {
?>
	<div class="wrap">
	<h2><?php echo $title; ?></h2>
<?php 
}

function sheriff_admin_footer() {
?>
	</div>
<?php 
}

function get_county() {
	switch(get_option('sheriff_county_id')) {
		case 1: return "Adams";
		case 2: return "Allen";
		case 3: return "Ashland";
		case 4: return "Ashtabula";
		case 5: return "Athens";
		case 6: return "Auglaize";
		case 7: return "Belmont";
		case 8: return "Brown";
		case 9: return "Butler";
		case 10: return "Carroll";
		case 11: return "Champaign";
		case 12: return "Clark";
		case 13: return "Clermont";
		case 14: return "Clinton";
		case 15: return "Columbiana";
		case 16: return "Coshocton";
		case 17: return "Crawford";
		case 18: return "Cuyahoga";
		case 19: return "Darke";
		case 20: return "Defiance";
		case 21: return "Delaware";
		case 22: return "Erie";
		case 23: return "Fairfield";
		case 24: return "Fayette";
		case 25: return "Franklin";
		case 26: return "Fulton";
		case 27: return "Gallia";
		case 28: return "Geauga";
		case 29: return "Greene";
		case 30: return "Guernsey";
		case 31: return "Hamilton";
		case 32: return "Hancock";
		case 33: return "Hardin";
		case 34: return "Harrison";
		case 35: return "Henry";
		case 36: return "Highland";
		case 37: return "Hocking";
		case 38: return "Holmes";
		case 39: return "Huron";
		case 40: return "Jackson";
		case 41: return "Jefferson";
		case 42: return "Knox";
		case 43: return "Lake";
		case 44: return "Lawrence";
		case 45: return "Licking";
		case 46: return "Logan";
		case 47: return "Lorain";
		case 48: return "Lucas";
		case 49: return "Madison";
		case 50: return "Mahoning";
		case 51: return "Marion";
		case 52: return "Medina";
		case 53: return "Meigs";
		case 54: return "Mercer";
		case 55: return "Miami";
		case 56: return "Monroe";
		case 57: return "Montgomery";
		case 58: return "Morgan";
		case 59: return "Morrow";
		case 60: return "Muskingum";
		case 61: return "Noble";
		case 62: return "Ottawa";
		case 63: return "Paulding";
		case 64: return "Perry";
		case 65: return "Pickaway";
		case 66: return "Pike";
		case 67: return "Portage";
		case 68: return "Preble";
		case 69: return "Putnam";
		case 70: return "Richland";
		case 71: return "Ross";
		case 72: return "Sandusky";
		case 73: return "Scioto";
		case 74: return "Seneca";
		case 75: return "Shelby";
		case 76: return "Stark";
		case 77: return "Summit";
		case 78: return "Trumbull";
		case 79: return "Tuscarawas";
		case 80: return "Union";
		case 81: return "Van Wert";
		case 82: return "Vinton";
		case 83: return "Warren";
		case 84: return "Washington";
		case 85: return "Wayne";
		case 86: return "Williams";
		case 87: return "Wood";
		case 88: return "Wyandot";
		default: return "BAD COUNTY ID STORED IN DATABASE";
	}
}

function sheriff_admin_options_county_select() {
	$current_county = get_option('sheriff_county_id');
?>
	<tr>
	<th scope="row"><?php _e("Select your county."); ?></th>
	<td>
	<select name="sheriff_county_id">
		<option value="0">Please select a county</option>
		<option value="1"<?php if($current_county == "1") { ?> selected="selected"<?php } ?>>Adams</option>
		<option value="2"<?php if($current_county == "2") { ?> selected="selected"<?php } ?>>Allen</option>
		<option value="3"<?php if($current_county == "3") { ?> selected="selected"<?php } ?>>Ashland</option>
		<option value="4"<?php if($current_county == "4") { ?> selected="selected"<?php } ?>>Ashtabula</option>
		<option value="5"<?php if($current_county == "5") { ?> selected="selected"<?php } ?>>Athens</option>
		<option value="6"<?php if($current_county == "6") { ?> selected="selected"<?php } ?>>Auglaize</option>
		<option value="7"<?php if($current_county == "7") { ?> selected="selected"<?php } ?>>Belmont</option>
		<option value="8"<?php if($current_county == "8") { ?> selected="selected"<?php } ?>>Brown</option>
		<option value="9"<?php if($current_county == "9") { ?> selected="selected"<?php } ?>>Butler</option>
		<option value="10"<?php if($current_county == "10") { ?> selected="selected"<?php } ?>>Carroll</option>
		<option value="11"<?php if($current_county == "11") { ?> selected="selected"<?php } ?>>Champaign</option>
		<option value="12"<?php if($current_county == "12") { ?> selected="selected"<?php } ?>>Clark</option>
		<option value="13"<?php if($current_county == "13") { ?> selected="selected"<?php } ?>>Clermont</option>
		<option value="14"<?php if($current_county == "14") { ?> selected="selected"<?php } ?>>Clinton</option>
		<option value="15"<?php if($current_county == "15") { ?> selected="selected"<?php } ?>>Columbiana</option>
		<option value="16"<?php if($current_county == "16") { ?> selected="selected"<?php } ?>>Coshocton</option>
		<option value="17"<?php if($current_county == "17") { ?> selected="selected"<?php } ?>>Crawford</option>
		<option value="18"<?php if($current_county == "18") { ?> selected="selected"<?php } ?>>Cuyahoga</option>
		<option value="19"<?php if($current_county == "19") { ?> selected="selected"<?php } ?>>Darke</option>
		<option value="20"<?php if($current_county == "20") { ?> selected="selected"<?php } ?>>Defiance</option>
		<option value="21"<?php if($current_county == "21") { ?> selected="selected"<?php } ?>>Delaware</option>
		<option value="22"<?php if($current_county == "22") { ?> selected="selected"<?php } ?>>Erie</option>
		<option value="23"<?php if($current_county == "23") { ?> selected="selected"<?php } ?>>Fairfield</option>
		<option value="24"<?php if($current_county == "24") { ?> selected="selected"<?php } ?>>Fayette</option>
		<option value="25"<?php if($current_county == "25") { ?> selected="selected"<?php } ?>>Franklin</option>
		<option value="26"<?php if($current_county == "26") { ?> selected="selected"<?php } ?>>Fulton</option>
		<option value="27"<?php if($current_county == "27") { ?> selected="selected"<?php } ?>>Gallia</option>
		<option value="28"<?php if($current_county == "28") { ?> selected="selected"<?php } ?>>Geauga</option>
		<option value="29"<?php if($current_county == "29") { ?> selected="selected"<?php } ?>>Greene</option>
		<option value="30"<?php if($current_county == "30") { ?> selected="selected"<?php } ?>>Guernsey</option>
		<option value="31"<?php if($current_county == "31") { ?> selected="selected"<?php } ?>>Hamilton</option>
		<option value="32"<?php if($current_county == "32") { ?> selected="selected"<?php } ?>>Hancock</option>
		<option value="33"<?php if($current_county == "33") { ?> selected="selected"<?php } ?>>Hardin</option>
		<option value="34"<?php if($current_county == "34") { ?> selected="selected"<?php } ?>>Harrison</option>
		<option value="35"<?php if($current_county == "35") { ?> selected="selected"<?php } ?>>Henry</option>
		<option value="36"<?php if($current_county == "36") { ?> selected="selected"<?php } ?>>Highland</option>
		<option value="37"<?php if($current_county == "37") { ?> selected="selected"<?php } ?>>Hocking</option>
		<option value="38"<?php if($current_county == "38") { ?> selected="selected"<?php } ?>>Holmes</option>
		<option value="39"<?php if($current_county == "39") { ?> selected="selected"<?php } ?>>Huron</option>
		<option value="40"<?php if($current_county == "40") { ?> selected="selected"<?php } ?>>Jackson</option>
		<option value="41"<?php if($current_county == "41") { ?> selected="selected"<?php } ?>>Jefferson</option>
		<option value="42"<?php if($current_county == "42") { ?> selected="selected"<?php } ?>>Knox</option>
		<option value="43"<?php if($current_county == "43") { ?> selected="selected"<?php } ?>>Lake</option>
		<option value="44"<?php if($current_county == "44") { ?> selected="selected"<?php } ?>>Lawrence</option>
		<option value="45"<?php if($current_county == "45") { ?> selected="selected"<?php } ?>>Licking</option>
		<option value="46"<?php if($current_county == "46") { ?> selected="selected"<?php } ?>>Logan</option>
		<option value="47"<?php if($current_county == "47") { ?> selected="selected"<?php } ?>>Lorain</option>
		<option value="48"<?php if($current_county == "48") { ?> selected="selected"<?php } ?>>Lucas</option>
		<option value="49"<?php if($current_county == "49") { ?> selected="selected"<?php } ?>>Madison</option>
		<option value="50"<?php if($current_county == "50") { ?> selected="selected"<?php } ?>>Mahoning</option>
		<option value="51"<?php if($current_county == "51") { ?> selected="selected"<?php } ?>>Marion</option>
		<option value="52"<?php if($current_county == "52") { ?> selected="selected"<?php } ?>>Medina</option>
		<option value="53"<?php if($current_county == "53") { ?> selected="selected"<?php } ?>>Meigs</option>
		<option value="54"<?php if($current_county == "54") { ?> selected="selected"<?php } ?>>Mercer</option>
		<option value="55"<?php if($current_county == "55") { ?> selected="selected"<?php } ?>>Miami</option>
		<option value="56"<?php if($current_county == "56") { ?> selected="selected"<?php } ?>>Monroe</option>
		<option value="57"<?php if($current_county == "57") { ?> selected="selected"<?php } ?>>Montgomery</option>
		<option value="58"<?php if($current_county == "58") { ?> selected="selected"<?php } ?>>Morgan</option>
		<option value="59"<?php if($current_county == "59") { ?> selected="selected"<?php } ?>>Morrow</option>
		<option value="60"<?php if($current_county == "60") { ?> selected="selected"<?php } ?>>Muskingum</option>
		<option value="61"<?php if($current_county == "61") { ?> selected="selected"<?php } ?>>Noble</option>
		<option value="62"<?php if($current_county == "62") { ?> selected="selected"<?php } ?>>Ottawa</option>
		<option value="63"<?php if($current_county == "63") { ?> selected="selected"<?php } ?>>Paulding</option>
		<option value="64"<?php if($current_county == "64") { ?> selected="selected"<?php } ?>>Perry</option>
		<option value="65"<?php if($current_county == "65") { ?> selected="selected"<?php } ?>>Pickaway</option>
		<option value="66"<?php if($current_county == "66") { ?> selected="selected"<?php } ?>>Pike</option>
		<option value="67"<?php if($current_county == "67") { ?> selected="selected"<?php } ?>>Portage</option>
		<option value="68"<?php if($current_county == "68") { ?> selected="selected"<?php } ?>>Preble</option>
		<option value="69"<?php if($current_county == "69") { ?> selected="selected"<?php } ?>>Putnam</option>
		<option value="70"<?php if($current_county == "70") { ?> selected="selected"<?php } ?>>Richland</option>
		<option value="71"<?php if($current_county == "71") { ?> selected="selected"<?php } ?>>Ross</option>
		<option value="72"<?php if($current_county == "72") { ?> selected="selected"<?php } ?>>Sandusky</option>
		<option value="73"<?php if($current_county == "73") { ?> selected="selected"<?php } ?>>Scioto</option>
		<option value="74"<?php if($current_county == "74") { ?> selected="selected"<?php } ?>>Seneca</option>
		<option value="75"<?php if($current_county == "75") { ?> selected="selected"<?php } ?>>Shelby</option>
		<option value="76"<?php if($current_county == "76") { ?> selected="selected"<?php } ?>>Stark</option>
		<option value="77"<?php if($current_county == "77") { ?> selected="selected"<?php } ?>>Summit</option>
		<option value="78"<?php if($current_county == "78") { ?> selected="selected"<?php } ?>>Trumbull</option>
		<option value="79"<?php if($current_county == "79") { ?> selected="selected"<?php } ?>>Tuscarawas</option>
		<option value="80"<?php if($current_county == "80") { ?> selected="selected"<?php } ?>>Union</option>
		<option value="81"<?php if($current_county == "81") { ?> selected="selected"<?php } ?>>Van Wert</option>
		<option value="82"<?php if($current_county == "82") { ?> selected="selected"<?php } ?>>Vinton</option>
		<option value="83"<?php if($current_county == "83") { ?> selected="selected"<?php } ?>>Warren</option>
		<option value="84"<?php if($current_county == "84") { ?> selected="selected"<?php } ?>>Washington</option>
		<option value="85"<?php if($current_county == "85") { ?> selected="selected"<?php } ?>>Wayne</option>
		<option value="86"<?php if($current_county == "86") { ?> selected="selected"<?php } ?>>Williams</option>
		<option value="87"<?php if($current_county == "87") { ?> selected="selected"<?php } ?>>Wood</option>
		<option value="88"<?php if($current_county == "88") { ?> selected="selected"<?php } ?>>Wyandot</option>
	</select>
	</td>
	</tr>
<?php 
}

?>