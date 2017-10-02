<?php
/*
Plugin Name: Contact Form 7 Save To Database / CSV / PDF
Plugin URI: https://codecanyon.net/user/rednumber/portfolio
Description: Allows you save all submitted from contact form 7 to database / CSV / PDF
Author: Rednumber
Version: 1.0
Author URI: https://codecanyonwp.com/
*/
if ( !function_exists( 'add_action' ) ) {
    echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
    exit;
}
define( 'CT7_SAVE_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'CT7_SAVE_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'CT7_SAVE_TEXT_DOMAIN', "cf7_save" );
register_activation_hook( __FILE__, 'cf7_save_data_pugin_activation' );
function cf7_save_data_pugin_activation(){
    global $wpdb;
    $table_name = $wpdb->prefix.'cf7_data';

    if( $wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name ) {

        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
            id INT NOT NULL AUTO_INCREMENT,
            contact_id INT NOT NULL,
            contact_name VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
            value LONGTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
            date VARCHAR(255) NOT NULL,
            PRIMARY KEY  (id)
        ) $charset_collate;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    }
}
/*
* Include pib
*/
if ( in_array( 'contact-form-7/wp-contact-form-7.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
    include CT7_SAVE_PLUGIN_PATH."backend/index.php";
    include CT7_SAVE_PLUGIN_PATH."frontend/index.php";
}
/*
* Check plugin contact form 7
*/
class cf7_save_to_database_init {
    function __construct(){
       add_action('admin_notices', array($this, 'on_admin_notices' ) );
    }
    function on_admin_notices(){
        if ( !in_array( 'contact-form-7/wp-contact-form-7.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
            echo '<div class="error"><p>' . __('Plugin need active plugin Contact Form 7', CT_7_AUTO_TEXT_DOMAIN) . '</p></div>';
        }
    }
}
new cf7_save_to_database_init;