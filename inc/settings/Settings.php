<?php
/**
 * @package Aristech
 *
 */

 namespace Inc\settings;

/* ========================
 * Settings
 * ========================
 */

use Inc\Globals;

class Settings
{


    function __construct(){

        $this   ->  themeUri            =   get_template_directory_uri();
        $this   ->  theme               =   get_template_directory();
        $this   ->  logo                =   get_option( 'aristech_logo' );
        $this   ->  menu_style          =   get_option( 'aristech_menu_style' );
        $this   ->  header_title        =   get_option( 'aristech_header_title' );
        $this   ->  sticky              =   get_option( 'aristech_sticky' );
        $this   ->  shade               =   get_option( 'aristech_shade_menu' );
    }
    public function settings() {

        register_setting( 'aristech-settings-nav', 'aristech_logo');
        register_setting( 'aristech-settings-nav', 'aristech_menu_style');
        register_setting( 'aristech-settings-general', 'aristech_logosize', array( $this, 'validate' ));
        register_setting( 'aristech-settings-nav', 'aristech_sticky');
        register_setting( 'aristech-settings-general', 'aristech_shade_menu');
        register_setting( 'aristech-settings-general', 'aristech_header_image');
        register_setting( 'aristech-settings-general', 'aristech_header_title');

        add_settings_section( 'aristech_general_settings', '', '', 'aristech_general' );

        add_settings_section( 'aristech_nav_settings', '', '', 'aristech_nav' );

        add_settings_field( 'logo-main', 'Add Logo', array($this, 'logo'), 'aristech_nav', 'aristech_nav_settings' );
        add_settings_field( 'menu-style', 'Menu style', array($this, 'menu_style'), 'aristech_nav', 'aristech_nav_settings' );
        add_settings_field( 'sticky-menu', 'Sticky Menu', array($this, 'sticky'), 'aristech_nav', 'aristech_nav_settings' );
        add_settings_field( 'shade-menu', 'Light or Dark Menu', array($this, 'shade'), 'aristech_general', 'aristech_general_settings' );
        add_settings_field( 'header-title', 'Header Title', array($this, 'header_title'), 'aristech_general', 'aristech_general_settings' );

    }

    /*  ======================
        Theme Options Settings
        ======================
    */



    function logo()
    {
        if (!empty($this->logo)) {
            echo '<div class="add-logo-picture"><div><input type="button" class="button button-secondary btn-upload" value="Upload" id="upload-button" />
                    <input type="button" class="button button-secondary btn-remove" value="&times;" id="remove-picture" />
                    <input type="hidden" id="profile-picture" name="aristech_logo" value="'. $this->logo .'"/>
                    </div>
                    <img id="logo-prev" style="max-width: '. $this->logosize .';" src="'.$this->logo.'" >
                    </div>';
        } else {
            echo '<input type="button" class="button button-secondary btn-upload" value="Upload" id="upload-button" /><input type="hidden" id="profile-picture" name="aristech_logo" value="'. $this->logo .'"/>';
        }
    }

    function menu_style()
    {
        $formats = array( 'Pop-Up', 'Swipable' );
        $output = '<select id="menu_style" name="aristech_menu_style">';
        foreach ( $formats as $format ){
            //echo $format;
            $checked = ( @$this->menu_style == $format ? 'selected="selected"' : '' );
            $output .= '<option value="'. $format.'" '.$checked.' >'.ucfirst($format).'</option>';
        }
        $output .= '</select> <label for="aristech_menu_style">Menu Style</label>';
        echo $output;
    }

    function validate($input){
        $output = $input;
        $output .= '%';
        return $output;
    }
    function sticky()
    {
        $formats = array( 'static', 'fixed', 'sticky', 'absolute' );
        $output = '<select id="sticky" name="aristech_sticky">';
        foreach ( $formats as $format ){
            //echo $format;
            $checked = ( @$this->sticky == $format ? 'selected="selected"' : '' );
            $output .= '<option value="'. $format.'" '.$checked.' >'.ucfirst($format).'</option>';
        }
        $output .= '</select> <label for="sticky">Sticky Menu</label>';
        echo $output;
    }

    function shade()
    {
        $checked = ( @$this->shade == 1 ? 'checked' : '' );
        $name = ( @$this->shade == 1 ? 'Dark Menu' : 'Light Menu' );
        echo '<input type="checkbox" class="ios8-switch" id="shade" name="aristech_shade_menu" value="1" '.$checked.' /> <label for="shade">'.$name.'</label>';
    }


    function header_title()
    {
        echo '<input class="regular-text" type="text" name="aristech_header_title" placeholder="Title" value="'. $this->header_title .'"/>';
    }





function user_cpt() {
    global $wpdb;

    $custom_post_type = 'cpt'; // define your custom post type slug here
    // A sql query to return all post titles
    $results = $wpdb->get_results( $wpdb->prepare( "SELECT ID, post_title FROM {$wpdb->posts} WHERE post_type = %s and post_status = 'publish'", $custom_post_type ), ARRAY_A );

    // Return null if we found no results
    // if ( ! $results )
    //     return;

    foreach( $results as $index => $post ) {
        // $output .= '<option value="' . $post['ID'] . '">' . $post['post_title'] . '</option>';
        $labels = array(
            'name'               => $post['post_title'],
            'singular_name'      => $post['post_title'],
            'add_new'            => 'Add ' .$post['post_title'],
            'all_items'          => 'All '.$post['post_title'].'s',
            'add_new_item'       => 'Add '.$post['post_title'],
            'edit_item'          => 'Edit '.$post['post_title'],
            'new_item'           => 'New '.$post['post_title'],
            'view_item'          => 'View '.$post['post_title'],
            'search_item'        => 'Search '.$post['post_title'],
            'not_found'          => 'No items found',
            'not_found_in_trash' => 'No items found in trash',
            'parent_item_colon'  => 'Parent Item'
        );
        $args = array(
            'labels' => $labels,
            'public' => true,
            'has_archive' => true,
            'publicly_queryable' => false,
            'query_var' => true,
            'rewrite' => true,
            'capability_type' => 'post',
            'hierarchical' => false,
            'supports' => array(
                'title',
                'editor',
                'excerpt',
                'thumbnail',
                'revisions',
                'comments',
                'metaboxes',
                'custom-fields'
            ),
            // 'taxonomies' => array('category', 'post_tag'),
            'menu_icon' => 'data:image/svg+xml;base64,' . base64_encode('<svg style="enable-background:new 0 0 20 20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" version="1.1" y="0px" x="0px">
            <style type="text/css">.st0{fill:#164D9D;}
               .st1{fill:#FFFFFF;}
               .st2{fill:none;stroke:#FFFFFF;stroke-width:0.1079;stroke-miterlimit:10;}
               .st3{fill:none;stroke:#FFFFFF;stroke-width:0.1082;stroke-miterlimit:10;}</style>
            <rect stroke-width="1.052" class="st0" width="21.949" y="-.084746" x="-28.475" height="20.169" fill="#164d9d"/>
            <g fill="#fff" transform="matrix(1.0975 0 0 1.0975 -1.05 -.86613)">
             <path class="st1" d="m5.2 10v0.3c0 0.1-0.1 0.2-0.1 0.3s-0.1 0.2-0.2 0.2-0.1 0.1-0.2 0.2c-0.1 0-0.2 0.1-0.3 0.1h-0.3-0.3c-0.1-0.1-0.1-0.1-0.2-0.1s-0.2-0.1-0.2-0.2l-0.2-0.2c0-0.1-0.1-0.2-0.1-0.3v-0.3-0.3c0-0.1 0.1-0.2 0.1-0.3s0.1-0.2 0.2-0.2c0-0.1 0.1-0.1 0.2-0.2 0.1 0 0.2-0.1 0.3-0.1h0.3 0.3s0.1 0.1 0.2 0.1 0.2 0.1 0.2 0.2l0.2 0.2c0 0.1 0.1 0.2 0.1 0.3v0.3zm-0.4 0c0-0.1 0-0.2-0.1-0.3 0-0.1-0.1-0.2-0.1-0.2 0-0.1-0.1-0.1-0.2-0.1s-0.2-0.1-0.3-0.1-0.2 0-0.3 0.1c-0.1 0-0.2 0.1-0.2 0.1 0 0.1-0.1 0.1-0.1 0.2s-0.1 0.2-0.1 0.3 0 0.2 0.1 0.3c0 0.1 0.1 0.2 0.1 0.2 0.1 0.1 0.1 0.1 0.2 0.1s0.2 0.1 0.3 0.1 0.2 0 0.3-0.1c0.1 0 0.2-0.1 0.2-0.1 0.1-0.1 0.1-0.1 0.1-0.2 0.1-0.1 0.1-0.2 0.1-0.3z"/>
             <path class="st1" d="m6 11.1h-0.5v-2.2h0.9 0.2c0.1 0.1 0.2 0.1 0.2 0.1 0.1 0 0.1 0.1 0.2 0.1 0 0.1 0.1 0.2 0.1 0.2 0 0.1 0.1 0.1 0.1 0.2v0.2 0.2c0 0.1 0 0.1-0.1 0.2 0 0.1-0.1 0.1-0.1 0.2-0.1 0.1-0.1 0.1-0.2 0.1l0.2 0.6h-0.4l-0.2-0.4h-0.5v0.5zm0-1.8v0.9h0.4 0.2c0.1 0 0.1-0.1 0.1-0.1l0.1-0.1v-0.2-0.2c0-0.1-0.1-0.1-0.1-0.1l-0.1-0.1h-0.2-0.4z"/>
             <path class="st1" d="m8 11.1h-0.5v-2.2h0.9 0.2c0.1 0.1 0.2 0.1 0.2 0.1 0.1 0 0.1 0.1 0.2 0.1 0.1 0.1 0.1 0.1 0.1 0.2s0.1 0.1 0.1 0.2v0.2c0 0.1 0 0.2-0.1 0.3 0 0.1-0.1 0.2-0.2 0.3s-0.2 0.1-0.3 0.2-0.2 0.1-0.3 0.1h-0.3zm0-1.8v0.9h0.4 0.2c0.1 0 0.1-0.1 0.1-0.1l0.1-0.1v-0.2-0.2c0-0.1-0.1-0.1-0.1-0.1l-0.1-0.1h-0.2-0.4z"/>
             <path class="st1" d="m9.9 11.1h-0.4v-2.2h0.4v0.9h0.9v-0.9h0.4v2.2h-0.4v-0.9h-0.9z"/>
             <path class="st1" d="m13.1 11.1h-1.5v-2.2h1.5v0.4h-1v0.4h0.7v0.4h-0.7v0.4h1z"/>
             <path class="st1" d="m15.1 10.2c0 0.1 0 0.2-0.1 0.3 0 0.1-0.1 0.2-0.2 0.3s-0.2 0.1-0.3 0.2c-0.1 0-0.2 0.1-0.3 0.1s-0.2 0-0.3-0.1c-0.1 0-0.2-0.1-0.3-0.2s-0.1-0.2-0.2-0.3c0-0.1-0.1-0.2-0.1-0.3v-1.3h0.4v1.3 0.2c0 0.1 0.1 0.1 0.1 0.1l0.1 0.1h0.2 0.2c0.1 0 0.1-0.1 0.1-0.1l0.1-0.1v-0.2-1.3h0.4v1.3z"/>
             <path class="st1" d="m15.3 9.6c0-0.1 0-0.2 0.1-0.3 0-0.1 0.1-0.1 0.1-0.2s0.2-0.1 0.2-0.1c0.1 0 0.2-0.1 0.3-0.1h1v0.4h-1-0.1-0.1v0.1 0.1 0.1 0.1h0.1 0.1 0.4c0.1 0 0.2 0 0.3 0.1 0.1 0 0.1 0.1 0.2 0.1 0.1 0.1 0.1 0.1 0.1 0.2s0.1 0.2 0.1 0.3 0 0.2-0.1 0.3c0 0.1-0.1 0.1-0.1 0.2-0.1 0.1-0.1 0.1-0.2 0.1s-0.2 0.1-0.3 0.1h-1v-0.4h1 0.1 0.1v-0.1-0.1-0.1-0.1h-0.1-0.1-0.4c-0.1 0-0.2 0-0.3-0.1-0.1 0-0.1-0.1-0.2-0.1-0.1-0.1-0.1-0.1-0.1-0.2 0-0.2-0.1-0.3-0.1-0.3z"/>
            </g>
            <g transform="matrix(1.0975 0 0 1.0975 -1.05 -.86613)">
             <path fill="#fff" class="st1" d="m12.1 4.7c-0.4 0-0.8-0.1-1.2-0.2s-0.7-0.3-1-0.6c-0.2 0.2-0.5 0.4-0.9 0.6-0.4 0.1-0.8 0.2-1.2 0.2h-1.4c-0.4 0-0.7 0.1-0.9 0.4-0.3 0.2-0.4 0.6-0.4 0.9h-2.2c0-0.5 0.1-0.9 0.3-1.4 0.2-0.4 0.4-0.7 0.8-1 0.3-0.4 0.6-0.6 1.1-0.8 0.4-0.2 0.9-0.3 1.3-0.3h1.4c0.3 0 0.5-0.1 0.7-0.3 0.3-0.2 0.4-0.5 0.4-0.8v-0.3h2.2v0.3c0 0.3 0.1 0.6 0.3 0.8s0.5 0.3 0.8 0.3h1.3c0.5 0 0.9 0.1 1.4 0.3 0.4 0.2 0.7 0.4 1.1 0.8 0.3 0.3 0.6 0.7 0.8 1.1 0.1 0.4 0.2 0.8 0.2 1.3h-2.2c0-0.2 0-0.3-0.1-0.5s-0.2-0.3-0.3-0.4-0.2-0.2-0.4-0.3-0.3-0.1-0.5-0.1z"/>
             <g stroke="#fff" stroke-width=".1079" stroke-miterlimit="10" fill="none">
              <line y2="8.5" x2="5.8" y1="3.6" x1="5.8" class="st2"/>
              <line y2="8.5" x2="7.2" y1="3.6" x1="7.2" class="st2"/>
              <line y2="8.5" x2="8.5" y1="3.6" x1="8.5" class="st2"/>
              <line y2="8.5" x2="9.8" y1="3.6" x1="9.8" class="st2"/>
              <line y2="8.5" x2="11.2" y1="3.6" x1="11.2" class="st2"/>
              <line y2="8.5" x2="12.5" y1="3.6" x1="12.5" class="st2"/>
              <line y2="8.5" x2="13.8" y1="3.6" x1="13.8" class="st2"/>
             </g>
            </g>
            <g transform="matrix(1.0975 0 0 1.0975 -1.05 -.86613)">
             <path fill="#fff" class="st1" d="m7.8 15.3c0.4 0 0.8 0.1 1.2 0.2s0.7 0.3 1 0.6c0.3-0.2 0.6-0.4 1-0.6 0.4-0.1 0.8-0.2 1.2-0.2h1.4c0.4 0 0.7-0.1 0.9-0.4 0.3-0.3 0.4-0.6 0.4-0.9h2.1c0 0.5-0.1 0.9-0.3 1.4-0.2 0.4-0.4 0.8-0.8 1.1-0.3 0.3-0.7 0.6-1.1 0.8s-0.9 0.3-1.4 0.3h-1.4c-0.3 0-0.5 0.1-0.7 0.3s-0.3 0.5-0.3 0.8v0.3h-2.1v-0.3c0-0.3-0.1-0.6-0.3-0.8s-0.5-0.3-0.8-0.3h-1.3c-0.5 0-0.9-0.1-1.4-0.3-0.4-0.3-0.8-0.5-1.1-0.9-0.3-0.3-0.6-0.7-0.8-1.1s-0.3-0.8-0.3-1.3h2.2c0 0.2 0 0.3 0.1 0.5s0.2 0.3 0.3 0.4 0.3 0.2 0.4 0.3c0.2 0.1 0.3 0.1 0.5 0.1z"/>
             <g stroke="#fff" stroke-width=".1082" stroke-miterlimit="10" fill="none">
              <line y2="16.4" x2="5.8" y1="11.5" x1="5.8" class="st3"/>
              <line y2="16.4" x2="7.2" y1="11.5" x1="7.2" class="st3"/>
              <line y2="16.4" x2="8.5" y1="11.5" x1="8.5" class="st3"/>
              <line y2="16.4" x2="9.8" y1="11.5" x1="9.8" class="st3"/>
              <line y2="16.4" x2="11.2" y1="11.5" x1="11.2" class="st3"/>
              <line y2="16.4" x2="12.5" y1="11.5" x1="12.5" class="st3"/>
              <line y2="16.4" x2="13.8" y1="11.5" x1="13.8" class="st3"/>
             </g>
            </g>
           </svg>'),
            'menu_position' => 5,
            'exclude_from_search' => false,
            'show_in_rest'       => true,
            'rest_base'          => sanitize_title(Globals::remove_accent($post['post_title'])),
            'rest_controller_class' => 'WP_REST_Posts_Controller',
        );
        register_post_type(sanitize_title(Globals::remove_accent($post['post_title'])),$args);


    }


}


function user_add_metabox()
{
    $args = array(
        'name' => 'cpt'
     );

     $output = 'names'; // names or objects, note names is the default
    //  $operator = 'and';

    $screens = get_post_types( $args, $output );

    foreach ( $screens as $screen ) {
        for ($i=1; $i < 6; $i++) {

            add_meta_box( 'aristech_cat'.$i, ' Category '.$i, array($this,'usr_cat'.$i.'_callback'), $screen, 'normal', 'high' );
        }

    }
}


function usr_cat1_callback($post)
{
    $cat = 'cat1';
    wp_nonce_field( 'aristech_save_'.$cat.'_data', 'aristech_'.$cat.'_meta_box_nonce' );
    $value = get_post_meta( $post->ID, '_'.$cat.'_value_key', true );
    echo '<label for="aristech_'.$cat.'_field"> Taxonomie Name: </label>';
    echo '<input class="regular-text" type="text" id="aristech_'.$cat.'_field" name="aristech_'.$cat.'_field" value="'.esc_attr( $value ).'" size="25"/>';

}
function usr_cat2_callback($post)
{
    $cat = 'cat2';
    wp_nonce_field( 'aristech_save_'.$cat.'_data', 'aristech_'.$cat.'_meta_box_nonce' );
    $value = get_post_meta( $post->ID, '_'.$cat.'_value_key', true );
    echo '<label for="aristech_'.$cat.'_field"> Taxonomie Name: </label>';
    echo '<input class="regular-text" type="text" id="aristech_'.$cat.'_field" name="aristech_'.$cat.'_field" value="'.esc_attr( $value ).'" size="25"/>';

}
function usr_cat3_callback($post)
{
    $cat = 'cat3';
    wp_nonce_field( 'aristech_save_'.$cat.'_data', 'aristech_'.$cat.'_meta_box_nonce' );
    $value = get_post_meta( $post->ID, '_'.$cat.'_value_key', true );
    echo '<label for="aristech_'.$cat.'_field"> Taxonomie Name: </label>';
    echo '<input class="regular-text" type="text" id="aristech_'.$cat.'_field" name="aristech_'.$cat.'_field" value="'.esc_attr( $value ).'" size="25"/>';

}
function usr_cat4_callback($post)
{
    $cat = 'cat4';
    wp_nonce_field( 'aristech_save_'.$cat.'_data', 'aristech_'.$cat.'_meta_box_nonce' );
    $value = get_post_meta( $post->ID, '_'.$cat.'_value_key', true );
    echo '<label for="aristech_'.$cat.'_field"> Taxonomie Name: </label>';
    echo '<input class="regular-text" type="text" id="aristech_'.$cat.'_field" name="aristech_'.$cat.'_field" value="'.esc_attr( $value ).'" size="25"/>';

}
function usr_cat5_callback($post)
{
    $cat = 'cat5';
    wp_nonce_field( 'aristech_save_'.$cat.'_data', 'aristech_'.$cat.'_meta_box_nonce' );
    $value = get_post_meta( $post->ID, '_'.$cat.'_value_key', true );
    echo '<label for="aristech_'.$cat.'_field"> Taxonomie Name: </label>';
    echo '<input class="regular-text" type="text" id="aristech_'.$cat.'_field" name="aristech_'.$cat.'_field" value="'.esc_attr( $value ).'" size="25"/>';

}

function aristech_save_cat_data($post_id)
{
    // $data = sanitize_text_field( $_POST['aristech_cat1_field'] );
    // update_post_meta( $post_id, '_cat1_value_key', $data );
    $metaBoxes = array( 'cat1', 'cat2', 'cat3', 'cat4', 'cat5' );

    foreach ($metaBoxes as $meta) {
        if( !isset( $_POST['aristech_'.$meta.'_meta_box_nonce'])){
            return;
        }
        if(! wp_verify_nonce( $_POST['aristech_'.$meta.'_meta_box_nonce'], 'aristech_save_'.$meta.'_data' )){
            return;
        }
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
           return;
        }
        if (!current_user_can( 'edit_post', $post->ID )) {
            return;
        }
        if (! isset( $_POST['aristech_'.$meta.'_field'])) {
            return;
        }

        $data = sanitize_text_field( $_POST['aristech_'.$meta.'_field'] );
        update_post_meta( $post_id, '_'.$meta.'_value_key', $data );
    }



}


function usr_taxonomies()
{

    global $wpdb;

    $custom_post_type = 'cpt'; // define your custom post type slug here

    // A sql query to return all post titles
    $results = $wpdb->get_results( $wpdb->prepare( "SELECT ID, post_title FROM {$wpdb->posts} WHERE post_type = %s and post_status = 'publish'", $custom_post_type ), ARRAY_A );
    // Return null if we found no results
    if ( ! $results )
        return;

        foreach( $results as $index => $post ) {
            // var_dump( $post);
            $cats = array('cat1','cat2','cat3','cat4','cat5');
            foreach ($cats as $cat) {
                # code...
                $key_value = get_post_meta( $post["ID"], '_'.$cat.'_value_key', true );
                // Check if the custom field has a value.
                $slug           = str_replace(' ', '_', strtolower($key_value)).'_type';
                $single_name    = ucfirst($key_value);
                $plural_name    = ucfirst($key_value).'s';
                //$post_type      = $post["post_title"];
                $post_type  =   sanitize_title(Globals::remove_accent($post['post_title']));
                $rewrite        = array( 'slug' => $slug );
                $rest_base      = $slug;
                $hierarchical   = true;

                if ( ! empty( $key_value ) ) {

                    $labels = array(
                        'name' => $plural_name,
                        'singular_name' => $single_name,
                        'search_items' =>  'Search ' . $plural_name,
                        'all_items' => 'All ' . $plural_name,
                        'parent_item' => 'Parent ' . $single_name,
                        'parent_item_colon' => 'Parent ' . $single_name . ':',
                        'edit_item' => 'Edit ' . $single_name,
                        'update_item' => 'Update ' . $single_name,
                        'add_new_item' => 'Add New ' . $single_name,
                        'new_item_name' => 'New ' . $single_name . ' Name',
                        'menu_name' => $plural_name
                    );
                    $rewrite = isset( $rewrite ) ? $rewrite : array( 'slug' => $slug );
                    $hierarchical = isset( $hierarchical ) ? $hierarchical : true;

                    register_taxonomy( $slug, $post_type, array(
                        'hierarchical' => $hierarchical,
                        'labels' => $labels,
                        'show_ui' => true,
                        'show_admin_column' => true,
                        'query_var' => true,
                        'rewrite' => $rewrite,
                        'show_in_rest'  => true,
                        'rest_base' => $rest_base,
                        'rest_controller_class' => 'WP_REST_Terms_Controller',
                    ));
                }
            }

        }
}


function cpt(){
    $labels = array(
        'name'              =>  'Cpt',
        'singular_name'     =>  'Cpt',
        'menu_name'         =>  'Cpt',
        'name_admin_bar'    =>  'Cpt',

    );

    $args = array(
        'labels'            =>  $labels,
        'show_ui'           =>  true,
        'show_in_menu'      =>  'edit.php?post_type=cpt',
        'show_in_rest'      =>  false,
        'show_in_nav_menus' =>  false,
        'view_item'         =>  true,
        'capability_type'   =>  'post',
        'hierarchical'      =>  false,
        'menu_position'     =>  26,
        'supports'          =>  array( 'title', 'custom-fields' ),
    );
    register_post_type( 'cpt', $args );
}




}


