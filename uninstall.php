<?php

/**
 *
 * @package Progressnet
 */


if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}

global $wpdb;
$array = array('_progressnet_meta_key', '_progressnet_status_meta_key');

foreach ($array as $item) {
    $item = esc_sql($item);
    $wpdb->query("DELETE FROM wp_options WHERE option_name = '$item'");
}
