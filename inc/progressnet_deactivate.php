<?php

/**
 * @package Progressnet
 */

class ProgressnetDeactivate
{
    public static function deactivate()
    {
        flush_rewrite_rules();
    }
}
