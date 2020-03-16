<?php

/**
 * @package Progressnet
 */
/*
* Plugin Name: Progressnet
* Plugin URI: https://progressnet.gr/
* Description: Issue tracker WP
* Author: aris
* Version: 0.0.1
* Author URI: https://progressnet.gr/
*/
if (!defined('ABSPATH')) {
    die;
}

require_once 'inc/progressnet_base.php';
require_once plugin_dir_path(__FILE__) . 'inc/progressnet_activate.php';
require_once plugin_dir_path(__FILE__) . 'inc/progressnet_deactivate.php';

//activate
register_activation_hook(__FILE__, array('ProgressnetActivate', 'activate'));

//deactivate
register_deactivation_hook(__FILE__, array('ProgressnetDeactivate', 'deactivate'));
