<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.adback.co
 * @since      1.0.0
 *
 * @package    Ad_Back
 * @subpackage Ad_Back/admin/partials
 */
?>

<h1 class="ab-lefty"><?php _e('Checking credentials...', 'adback-solution-to-adblock-lite'); ?></h1>

<script>
	setTimeout(function(){
	    	window.location.href = '<?php echo $_SERVER['PHP_SELF'] . '?page=ab'; ?>';
	}, 2000);
</script>
