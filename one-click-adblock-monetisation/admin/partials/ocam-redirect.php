<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.adback.co
 * @since      1.0.0
 *
 * @package    One_Click_Adblock_Monetisation
 * @subpackage One_Click_Adblock_Monetisation/admin/partials
 */
?>

<h1 class="ocam-lefty"><?php _e('Checking credentials...', 'one-click-adblock-monetisation'); ?></h1>

<script>
    setTimeout(function(){
            window.location.href = '<?php echo $_SERVER['PHP_SELF'] . '?page=ocam-lite'; ?>';
    }, 2000);
</script>
