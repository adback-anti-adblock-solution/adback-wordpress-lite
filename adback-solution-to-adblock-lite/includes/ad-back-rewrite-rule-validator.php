<?php

/**
 * Fired during rewrite rule update
 *
 * @link       https://www.adback.co
 * @since      1.0.0
 *
 * @package    Ad_Back
 * @subpackage Ad_Back/includes
 */

/**
 * Fired during rewrite rule update
 *
 * This class defines all code necessary to validate the rewrite rules.
 *
 * @since      2.4.2
 * @package    Ad_Back
 * @subpackage Ad_Back/includes
 * @author     AdBack <contact@adback.co>
 */
class Ad_Back_Rewrite_Rule_Validator
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
