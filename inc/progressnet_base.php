<?php

/**
 * @package Progressnet
 */

if (!defined('ABSPATH')) {
    die;
}

if (file_exists(plugin_dir_path(__DIR__) . '/vendor/autoload.php')) {
    require_once plugin_dir_path(__DIR__) . '/vendor/autoload.php';
}

use Inc\settings\EnqueueWp;
use Inc\post_types\Issue;

class Progressnet_Base
{
    public function __construct()
    {
        //$this->update();
        $this->plugin               =   plugin_dir_path(__FILE__);
        $this->url                  =   plugin_dir_url(__FILE__);
        $this->enqueueWp            =   new EnqueueWp;
        $this->issue                =   new Issue;
    }


    public function register()
    {

        add_action('init', array($this, 'progressnet_get_current_user'), 5);
    }
    function progressnet_get_current_user()
    {

        if (current_user_can('editor') || current_user_can('administrator')) {


            add_filter('post_updated_messages', array($this->issue,  'issue_updated_messages'));
            add_action('add_meta_boxes', array($this, 'progressnet_add_users_box'));
            add_action('add_meta_boxes', array($this, 'progressnet_add_status_box'));
            add_action('save_post', array($this, 'progressnet_save_wp_assign_postdata'));
            add_action('init', array($this->issue,  'issue_init'));
            add_filter('post_updated_messages', array($this->issue, 'issue_updated_messages'));
            add_action('wp_footer', array($this,  'root_html'));
            add_action('wp_enqueue_scripts', array($this->enqueueWp, 'enqueueWp'));

            add_action(
                'rest_api_init',
                function () {
                    remove_filter('rest_pre_serve_request', 'rest_send_cors_headers');

                    add_filter('rest_pre_serve_request', function ($value) {
                        header('Access-Control-Allow-Origin:  *');
                        header('Access-Control-Allow-Methods: POST, GET');
                        header('Access-Control-Allow-Credentials: true');
                        header('Access-Control-Allow-Headers: Content-Type,Content-Disposition, Access-Control-Allow-Headers, Authorization, X-Requested-With');
                        return $value;
                    });
                    // Register routes
                    register_rest_route('progressnet/v1', '/data', array(
                        'methods' => 'GET',
                        'callback' => array($this, 'get_data'),
                        'args' => array(
                            'id' => array(
                                'validate_callback' => 'is_numeric'
                            ),
                        ),
                        'permission_callback' => function () {
                            return current_user_can('editor') || current_user_can('administrator');
                        }
                    ));
                    register_rest_route('progressnet/v1', '/new', array(
                        'methods' => 'POST',
                        'callback' => array($this, 'new_issue'),
                        'args' => array(
                            'id' => array(
                                'validate_callback' => 'is_numeric'
                            ),
                        ),
                        'permission_callback' => function () {
                            return current_user_can('editor') || current_user_can('administrator');
                        }
                    ));
                    register_rest_route('progressnet/v1', '/delete', array(
                        'methods' => 'POST',
                        'callback' => array($this, 'delete_issue'),
                        'args' => array(
                            'id' => array(
                                'validate_callback' => 'is_numeric'
                            ),
                        ),
                        'permission_callback' => function () {
                            return current_user_can('editor') || current_user_can('administrator');
                        }
                    ));
                    register_rest_route('progressnet/v1', '/update', array(
                        'methods' => 'POST',
                        'callback' => array($this, 'update_issue'),
                        'args' => array(
                            'id' => array(
                                'validate_callback' => 'is_numeric'
                            ),
                        ),
                        'permission_callback' => function () {
                            return current_user_can('editor') || current_user_can('administrator');
                        }
                    ));
                }
            );
        }
    }

    function get_data()
    {
        $posts_data = array();
        $post_type = 'issue';
        $posts = get_posts(
            array(
                'post__not_in'      => get_option('sticky_posts'),
                'posts_per_page'    => -1,
                'post_type'         => array($post_type)
            )
        );
        $args = array(
            'role'    => 'administrator',
            'orderby' => 'user_nicename',
            'order'   => 'ASC'
        );


        $users = array();
        $usersData = get_users($args);
        foreach ($usersData as $key ) {
            $users[] = (object) [$key->ID => $key->display_name];


        }
        foreach ($posts as $post) {
            $id = $post->ID;
            $user_info = get_userdata(get_post_meta($id, "_progressnet_meta_key")[0]);
            $st_value = get_post_meta($post->ID, '_progressnet_status_meta_key', true);
            // if(isset($value)){
            //     $status =
            // }
            $posts_data[] = (object) array(
                'id' => $id,
                'title' => $post->post_title,
                'date' => $post->post_modified,
                'description' => wp_strip_all_tags($post->post_content),

                'status'     => (int)$st_value,
                'statuses'   => (object) array(
                    0 =>'closed',
                    1 => 'open'
                ),

                'assigned'          => (int)$user_info->ID,
                'users' => $users,

            );
        }
        return $posts_data;
    }
    public function progressnet_add_users_box()
    {
        $screens = ['issue'];
        foreach ($screens as $screen) {
            add_meta_box(
                'issue_assign_box_id',           // Unique ID
                'Assign user to this issue',  // Box title
                array($this, 'progressnet_issue_assign_box_html'),  // Content callback, must be of type callable
                $screen                   // Post type
            );
        }
    }
    public function progressnet_add_status_box()
    {
        $screens = ['issue'];
        foreach ($screens as $screen) {
            add_meta_box(
                'issue_status_box_id',           // Unique ID
                'Assign status to this issue',  // Box title
                array($this, 'progressnet_issue_status_box_html'),  // Content callback, must be of type callable
                $screen                   // Post type
            );
        }
    }

    public function progressnet_issue_assign_box_html($post)
    {
        $args = array(
            'role'    => 'administrator',
            'orderby' => 'user_nicename',
            'order'   => 'ASC'
        );
        $users = get_users($args);
        $value = get_post_meta($post->ID, '_progressnet_meta_key', true);

        // https://developer.wordpress.org/plugins/metadata/custom-meta-boxes/

?>
        <label for="issue_assign_field">Select User</label>
        <select name="issue_assign_field" id="issue_assign_field" class="postbox">

            <option value="">Users...</option>
            <?php
            foreach ($users as $user) {
            ?>
                <option value="<?php echo $user->ID; ?>" <?php selected($value, $user->ID); ?>><?php echo get_user_by('id', $user->ID)->display_name ?></option>
            <?php
            }
            ?>

        </select>
    <?php

    }

    public function progressnet_issue_status_box_html($post)
    {


        $value = get_post_meta($post->ID, '_progressnet_status_meta_key', true);

        // https://developer.wordpress.org/plugins/metadata/custom-meta-boxes/

    ?>
        <label for="issue_status_field">Select Status is <?php $value ?></label>
        <select name="issue_status_field" id="issue_status_field" class="postbox">


                <option value="1" <?php selected($value, '1'); ?>>Open</option>
                <option value="0" <?php selected($value, '0'); ?>>Closed</option>
            </select>
    <?php

    }

    public function progressnet_save_wp_assign_postdata($post_id)
    {

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        if (!current_user_can('administrator', $post_id)) {
            return;
        }
        if (array_key_exists('issue_assign_field', $_POST) ) {
            update_post_meta(
                $post_id,
                '_progressnet_meta_key',
                $_POST['issue_assign_field']
            );
        }
        if (array_key_exists('issue_status_field', $_POST)) {
            update_post_meta(
                $post_id,
                '_progressnet_status_meta_key',
                $_POST['issue_status_field']
            );
        }
    }


    /**
     * Respond to a REST API request to get post data.
     *
     * @param WP_REST_Request $request Request.
     * @return WP_REST_Response
     */
    function new_issue(WP_REST_Request $request)
    {
        $title = $request->get_param('title');
        $description = $request->get_param('description');
        $status = $request->get_param('status');
        $assigned = $request->get_param('assigned');

        // Create post object
        $my_post = array(
            'post_title'    => sanitize_text_field(wp_strip_all_tags($title)),
            'post_content'  => sanitize_text_field($description),
            'post_type' => 'issue',
            'post_status'   => 'publish',
            'post_author'   => get_current_user_id(),

        );

        // Insert the post into the database
        $postID = wp_insert_post($my_post);

        // Update the post into the database
        //_progressnet_meta_key
        //_progressnet_status_meta_key

        update_post_meta($postID, '_progressnet_meta_key', $assigned);
        update_post_meta($postID, '_progressnet_status_meta_key', $status);

        return $this->get_data();
    }

    /**
     * Respond to a REST API request to get post data.
     *
     * @param WP_REST_Request $request Request.
     * @return WP_REST_Response
     */
    function update_issue(WP_REST_Request $request)
    {
        $title = $request->get_param('title');
        $id = $request->get_param('id');
        $description = $request->get_param('description');
        $status = $request->get_param('status');
        $assigned = $request->get_param('assigned');

        $my_post = array(
            'ID'           => $id,
            'post_status'   => 'publish',
            'post_content' => $description,
            'post_title'   => $title
        );

        // Update the post into the database
        //_progressnet_meta_key,
        //_progressnet_status_meta_key,
        wp_update_post($my_post);
        update_post_meta($id, '_progressnet_meta_key', $assigned);
        update_post_meta($id, '_progressnet_status_meta_key', $status);

        return $this->get_data();
    }

    /**
     * Respond to a REST API request to get post data.
     *
     * @param WP_REST_Request $request Request.
     * @return WP_REST_Response
     */
    function delete_issue(WP_REST_Request $request)
    {

        $id = $request->get_param('id');


        wp_delete_post($id);


        return $this->get_data();
    }


    function root_html()
    {
        echo '<noscript>You need to enable JavaScript to run this app.</noscript>
        <div id="root"></div>';
    }
}

if (class_exists('Progressnet_Base')) {
    $Progressnet_Base = new Progressnet_Base();
    $Progressnet_Base->register();
}
