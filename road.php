<?php

/* Widget section */

class level_Widget extends WP_Widget {
	/* Register Widget */
	public function __construct() {
		parent::__construct(
			'level_widget',
			'Winter Road Levels Widget',
			array('description' => __('Displays widget based on your current winter road level.'), )
		);
	}

	/* Front End */
	public function widget($args, $instance) {
		global $wpdb;
		extract($args);
		$current_level = get_option('road_level');
		$hide_if_zero = get_option('road_hide_widget');
		if((!$hide_if_zero || $current_level) && get_option('enable_road_levels')) {
			$title = apply_filters('widget_title',$instance['title']);
			echo $before_widget;
			if (!empty($title)) {
				echo $before_title . $title . $after_title;
			}
			switch ($current_level) {
				case 0:
					echo "<strong>There are currently no road advisories for " . get_county() . " County.</strong>";
					break;
				case 1:
					echo "<strong>". get_county() . " County is currently under a Level One snow emergency.</strong><br /><br />Roads are hazardous with blowing and drifting snow. Roads are icy and drivers are warned to be cautious.";
					break;
				case 2:
					echo "<strong>". get_county() ." County is currently under a Level Two snow emergency.</strong><br /><br />Roads are hazardous. Only those who feel it is necessary to drive should do so. Drivers are encouraged to call their employers to verify that they need to report to work.";
					break;
				case 3:
					echo "<strong>". get_county() ." County is currently under a Level Three snow emergency.</strong><br /><br />All roadways are closed to non-emergency personnel. No one should be driving unless it is absolutely necessary.<br /><br />Those traveling on the roadways may subject themselves to arrest.";
					break;
				default:
					echo "Error: Reached default case in road level widget.";
					break;
			}
			echo $after_widget;
		}
	}
	
	/* Sanitize widget values when saved. */
	public function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		return $instance;
	}
	
	/* Backend */
	public function form($instance) {
		if($instance) {
			$title = esc_attr($instance['title']);
		} else {
			$title = __('Road Advisory', 'text-domain');
		}
		?>
			<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
			</p>
		<?php 
	}
}

/* Admin section */

function sheriff_menu_road() {
	sheriff_admin_header("Road Levels");
	if(!get_option('enable_road_levels')) {
		echo "<p><em>Road levels are currently disabled. You can enable them <a href='admin.php?page=sheriff_menu_main'>here</a>.</em></p>";
	} else {
		?>
		<p>This page is used to set the current snow emergency levels. If you have the widget placed on your website, the message displayed will correspond to the level set here. Changing the level <strong>will <?php if(!get_option('road_create_post')) { echo "not "; } ?></strong>create a news post, based on your selected preferences, <a href="admin.php?page=sheriff_menu_main">which can be changed here</a>.</p>
		<?php 
		sheriff_menu_road_save();
		sheriff_menu_road_form();
	}
	sheriff_admin_footer();
}

function sheriff_menu_road_form() {
	$current_level = get_option('road_level');
?>
	<form action="<?php echo str_replace('%7E', '~', $_SERVER['REQUEST_URI']); ?>" method="POST">
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row"><?php _e('Current road advisory level: '); ?></th>
					<td>
						<select name='road_level'>
							<option value="0"<?php if($current_level == '0') { ?> selected="selected"<?php } ?>>None</option>
							<option value="1"<?php if($current_level == '1') { ?> selected="selected"<?php } ?>>One</option>
							<option value="2"<?php if($current_level == '2') { ?> selected="selected"<?php } ?>>Two</option>
							<option value="3"<?php if($current_level == '3') { ?> selected="selected"<?php } ?>>Three</option>
						</select>
					</td>
				</tr>
			</tbody>
		</table>
		<p class="submit"><input type="submit" name="set_road_level" id="set_road_level" class="button-primary" value="Set Road Level" /></p>
	</form>
<?php 
}

function sheriff_menu_road_save() {
	if($_POST['set_road_level'] == "Set Road Level") {
		$old_level = get_option('road_level');
		$new_level = $_POST['road_level'];
		if($old_level != $new_level) {
			update_option('road_level', $new_level);
			if(get_option('road_create_post')) {
				sheriff_menu_road_post($old_level, $new_level);
			}
		}
	}
}

function sheriff_menu_road_post($old_level, $new_level) {
	switch($new_level) {
		case 0:
			$post_title = get_county() . " County Snow Emergency Canceled";
			$post_content = "<p>All snow emergency levels for " . get_county() . " County have now been removed.  Please continue to use caution while driving.</p>";
			break;
		case 1:
			$post_title = get_county() . " County Now Under Level One Snow Emergency";
			switch($old_level) {
				case 0:
					$post_content = "<p>" . get_county() . " County has been placed under a level one snow emergency.  A level one snow emergency means that roads are hazardous with blowing and drifting snow. Roads are icy and drivers are warned to be cautious.</p>";
					break;					
				default:
					$post_content = "<p>" . get_county() . " County's snow emergency level has been lowered to a level one.  Conditions are improving, however roads are still hazardous with blowing and drifting snow. Roads remain icy and drivers are warned to be cautious.</p>";
					break;
			}
			break;
		case 2:
			$post_title = get_county() . " County Now Under Level Two Snow Emergency";
			switch($old_level) {
				case 3:
					$post_content = "<p>". get_county() . " County's snow emergency level has been lowered to a level two.  Conditions are improving, however roads remain hazardous. Only those who feel it is necessary to drive should do so. Drivers are encouraged to call their employers to verify that they need to report to work.</p>";
					break;
				default:
					$post_content = "<p>" . get_county() . " County has been placed under a level two snow emergency.  A level two snow emergency means that roads are hazardous. Only those who feel it is necessary to drive should do so. Drivers are encouraged to call their employers to verify that they need to report to work.</p>";
					break;
			}
			break;
		case 3:
			$post_title = get_county() . " County Now Under Level Three Snow Emergency";
			$post_content = "<p>" . get_county() . " County has been placed under a level three snow emergency.  A level three snow emergency means that all roadways are closed to non-emergency personnel. No one should be driving unless it is absolutely necessary.  Those traveling on the roadways may subject themselves to arrest.</p>";
			break;
	}

	if($new_level) {
    	$post_content = $post_content . "<p>Please continue to be aware of weather conditions, and stay up to date with all the current snow emergency levels by checking in at the <a href='" . get_site_url() . "'>" . get_option('blogname') . " website</a>.</p>";
	}

	$category = get_cat_ID("Road Levels");
	$new_post = array(
		'post_title' => $post_title,
		'post_content' => $post_content,
		'post_status' => 'publish',
		'post_author' => 1,
		'post_category' => array($category)
	);
	wp_insert_post( $new_post );
}

?>