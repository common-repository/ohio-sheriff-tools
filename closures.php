<?php

/* Helper functions */
function get_road_closures() {
	global $wpdb;
	$sql = 'SELECT * FROM ' . $wpdb->prefix . 'road_closure';
	return $wpdb->get_results($sql);
}

/* Shortcodes */
if (get_option('enable_road_closures')) {
	add_shortcode('road_closure_detail', 'road_closure_detail_display');
	add_shortcode('road_closure_summary','road_closure_summary_display');
}

function road_closure_summary_display($atts) {
	/* Shortcode currently does not accept arguments, so no need to extract them. */
	$closures = get_road_closures();
	if($closures) {
		$page = get_page_by_title("Road Closures");
		$uri = get_page_link($page->ID); 
		$ret = '<p style="text-align:center;font-weight:bold;"><a href="' . $uri . '">Road closures have been reported.</a></p>';
	}
	return $ret;
}

function road_closure_detail_display($atts) {	
	/* Shortcode currently does not accept arguments, so no need to extract them. */
	$closures = get_road_closures();
	if($closures) {
		$ret = '<p>The following road closures have been reported in ' . get_county() . ' County.';
		foreach ($closures as $closure) {
			$ret .= '<p>' . $closure->message . '</p>';
		}
	} else {
		$ret = '<p>There are currently no road closures reported in ' . get_county() . ' County.';
	}
	return $ret;
}

/* Admin section */

function sheriff_menu_closures() {
	sheriff_admin_header("Road Closures");
	if(!get_option('enable_road_closures')) {
		echo "<p><em>Road closures are currently disabled. You can enable them <a href='admin.php?page=sheriff_menu_main'>here</a>.</em></p>";
	} else {
		if($_POST['information']) 	{ 		echo road_closure_create(); }
		if($_POST['delete'])		{		echo road_closure_delete(); }

		$closures = get_road_closures();
		if($closures) {
			road_closure_table($closures);
		} else {
			echo '<p>There are currently no road closures in the database.</p>';
		}
		
		road_closure_form();
	}
	sheriff_admin_footer();
}

function road_closure_create() {
	global $wpdb;
	$message = stripslashes($_POST['information']);
	$rows_affected = $wpdb->insert($wpdb->prefix . 'road_closure', array('message' => $message));
	if(get_option('closure_create_post')) {
		$category = get_cat_ID("Road Closures");
		$new_post = array(
			'post_title' 	=> "Road Closure",
			'post_content' 	=> $message,
			'post_status'	=> 'publish',
			'post_author'	=> 1,
			'post_category'	=> array($category)
		);
		wp_insert_post($new_post);
	}
	return "<p><em>Road closure added.</em></p>";
}

function road_closure_delete() {
	global $wpdb;
	$sql = "DELETE FROM " . $wpdb->prefix . 'road_closure WHERE id = ' . $_POST['delete'];
	$wpdb->query($sql);
	return "<p><em>Road closure deleted.</em></p>";
}

function road_closure_table($closures) {
	?>
	<h3>Current Road Closures</h3>
	<table>
	<?php  foreach ($closures as $closure) { ?>
		<tr>
			<td style="text-align:center;">
				<form name="delete_<?php echo $closure->id;?>" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
					<input type="hidden" name="delete" value="<?php echo $closure->id; ?>">
					<input type="submit" value="Delete" class="button">
				</form>
			</td>
			<td><?php echo $closure->message; ?></td>
		</tr>
	<?php } ?>	
	</table>
	<?php 
}

function road_closure_form() {
	?>
	<h3>Add Road Closure</h3>
	<p>If you would like to add a road closure, please type all the information into the box below.  Please note that, whatever you type into this box will be published to the website <?php if (get_option('closures_create_post')) {?>and newsfeed <?php }?>as soon as you click the add button, so please be as descriptive as possible.</p>
	<form name="addroadclosure" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
		<textarea rows="6" cols="80" name="information"></textarea><br />
		<input type="submit" value="Add" class="button" />
	</form>
	<?php 	
}

function create_road_closures_page() {
	$page = get_page_by_title("Road Closures");
	if($page) {
		$new_page = array();
		$new_page['ID'] = $page->ID;
		$new_page['post_status'] = 'publish';
		wp_update_post($new_page);
	} else {
		wp_insert_post(array(
			'comment_status' => 'closed',
			'ping_status' => 'closed',
			'post_content' => '[road_closure_detail]',
			'post_status' => 'publish',
			'post_title' => 'Road Closures',
			'post_type' => 'page'
		));
	}
}

function remove_road_closures_page() {
	$page = get_page_by_title("Road Closures");
	$new_page = array();
	$new_page['ID'] = $page->ID;
	$new_page['post_status'] = 'draft';
	wp_update_post($new_page);
	
}
?>