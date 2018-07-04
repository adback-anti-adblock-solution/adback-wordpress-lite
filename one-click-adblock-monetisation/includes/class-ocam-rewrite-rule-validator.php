<?php

/**
 * Fired during rewrite rule update
 *
 * @since      2.4.2
 * @package    One_Click_Adblock_Monetisation
 * @subpackage One_Click_Adblock_Monetisation/includes
 * @author     AdBack <contact@adback.co>
 */
class Ocam_Rewrite_Rule_Validator
{
    public static function validate($endpoint)
    {
        if (!$rules = get_option('rewrite_rules')) {
            return false;
        }

        /** @var $rule array */
        foreach ($rules as $rule => $rewrite) {
            if (preg_match('/^' . $endpoint . '.*/', $rule) && false === strpos($rewrite, 'adback_proxy')) {
                return true;
            }
        }

        return false;
    }
}
