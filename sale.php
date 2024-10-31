<?php

/* Admin section */

function sheriff_menu_sale() {
	sheriff_admin_header("Sheriff Sales");
	if(!get_option('enable_sheriff_sales')) {
		echo "<p><em>Sheriff Sales are currently disabled. You can enable them <a href='admin.php?page=sheriff_menu_main'>here</a>.</em></p>";
	} else {
		echo "Sheriff Sales Enabled<br />";
	}
	?>
	<p>This feature has not yet been implemented. Please watch for a future release.</p>
	<?php 
	sheriff_admin_footer();
}

?>