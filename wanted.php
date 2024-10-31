<?php

/* Admin section */

function sheriff_menu_wanted() {
	sheriff_admin_header("Wanted");
	if(!get_option('enable_sheriff_wanted')) {
		echo "<p><em>Wanted database is currently disabled. You can enable them <a href='admin.php?page=sheriff_menu_main'>here</a>.</em></p>";
	} else {
		echo "Wanted Database Enabled.<br />";
	}
	?>
	<p>This feature has not yet been implemented. Please watch for a future release.</p>
	<?php 
	sheriff_admin_footer();
}

?>