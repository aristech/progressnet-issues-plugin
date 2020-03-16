<?php

/**
 * @package Progressnet
 * @version 0.0.1
 */

class ProgressnetActivate
{
    public static function activate()
    {
        flush_rewrite_rules();
    }
}
