<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.adback.co
 * @since      1.0.0
 *
 * @package    Ad_Back_Lite
 * @subpackage Ad_Back_Lite/admin/partials
 */
?>

<h1 class="ab-lefty"><?php _e('Refresh domain...', 'adback-solution-to-adblock-lite'); ?></h1>

<script>
    setTimeout(function(){
            window.location.href = '<?php echo $_SERVER['PHP_SELF'] . '?page=ab-lite'; ?>';
    }, 2000);
</script>
