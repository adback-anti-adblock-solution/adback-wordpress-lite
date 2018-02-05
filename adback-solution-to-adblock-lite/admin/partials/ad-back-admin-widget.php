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

<div id="adb-widget" data-links="<?php echo get_admin_url(get_current_blog_id(), 'admin.php?page=ab-settings')?>"></div>
<script type="text/javascript">
    window.onload = function() {
        if(typeof adbackjs === 'object') {
            adbackjs.init({
                token: '<?php echo $this->getToken()->access_token; ?>',
                url: 'https://<?php echo $this->getDomain(); ?>/api/',
                language: '<?php echo str_replace('_', '-', get_locale()); ?>',
                version: 2
            });
        }
    }
</script>
<style>
    #table-page-view {
        border-collapse: collapse;
    }
    #table-page-view .sample {
        display:none;
    }
    #table-page-view td {
        border:1px solid #e5e5e5;
        margin: 0;
    }
    #table-page-view td, #table-page-view th{
        padding: 10px;
    }
    #table-page-view thead{
        background-color: #f1f1f1;
    }
    #table-page-view th.number, #table-page-view th.rate{
        border-left: 1px solid #e5e5e5;
        border-right: 1px solid #e5e5e5;
    }
    #table-page-view .rate{
        min-width: 50px;
    }
</style>
