<?php

/**
 * @package Progressnet
 *
 */

namespace Inc\settings;

/* ================================
 * Enqueue Admin Configuration Page
 * =================================
 */

class EnqueueWp
{

    public function enqueueWp()
    {


        wp_enqueue_script('progressnet', plugin_dir_url(__DIR__) . '/assets/js/progressnet.js', array(), '0.0.1', true);
        wp_enqueue_script('chunk', plugin_dir_url(__DIR__) . '/static/js/2.f0a6b728.chunk.js', array(), '0.0.1', true);
        wp_enqueue_script('main', plugin_dir_url(__DIR__) . '/static/js/main.2e975d32.chunk.js', array(), '0.0.1', true);
        wp_localize_script(
            'main',
            'WP_API_Settings',
            array(
                'rootUrl' => esc_url_raw(rest_url()),
                'nonce' => wp_create_nonce('wp_rest')
            )
        );
    }
}
