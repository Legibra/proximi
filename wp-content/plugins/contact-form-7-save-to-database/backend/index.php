<?php
if ( !function_exists( 'add_action' ) ) {
    echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
    exit;
}
include CT7_SAVE_PLUGIN_PATH."backend/table.php";
class cf7_save_to_database_backend {
    // class instance
    static $instance;
    // customer WP_List_Table object
    public $customers_obj;
    // class constructor
    public function __construct() {
        add_filter( 'set-screen-option', [ __CLASS__, 'set_screen' ], 10, 3 );
        add_action( 'admin_menu', [ $this, 'plugin_menu' ] );
        add_action("admin_enqueue_scripts",array($this,"add_lib"));
        add_action( 'wp_ajax_cf7_data_export', array($this,'cf7_data_export') );
        add_action( 'admin_post_print.csv', array($this,'print_csv' ));

    }
    function print_csv(){
         global $wpdb;
        if ( ! current_user_can( 'manage_options' ) )
            return;

        header('Content-Type: application/csv');
        header('Content-Disposition: attachment; filename="cf7_export_'. date("Y-m-d") .'csv');
        header('Pragma: no-cache');
        $form = $_REQUEST["id"];
        $where = " WHERE contact_id = ".$form;
        
        $sql = "SELECT * FROM {$wpdb->prefix}cf7_data{$where}";
        $results = $wpdb->get_results( $sql, 'OBJECT' );
            $data = array();
            $i=1;
            foreach ($results as $result) :
                     $data[$i]['contact_id']    = $result->contact_id;
                     $data[$i]['contact_name']    = $result->contact_name;
                    $data[$i]['date']  = date("Y-m-d",$result->date);

                    $list_hide   = array('_wpcf7', '_wpcf7_version', '_wpcf7_locale', '_wpcf7_unit_tag','_wpcf7_is_ajax_call','cfdb7_name', '_wpcf7_container_post','_cf_logic');
                    $meta = maybe_unserialize($result->value);
                    foreach ($meta as $key => $value) {
                        if( !in_array($key, $list_hide) && $value != "") :
                            $data[$i][$key]  = $value;
                        endif;
                         
                    }
                    $i++;
            endforeach;
            echo $this->array2csv($data);
    }
    function array2csv($array){
       if (count($array) == 0) {
         return null;
       }
       ob_start();
       $df = fopen("php://output", 'w');
       fputcsv($df, array_keys(reset($array)));
       foreach ($array as $row) {
          fputcsv($df, $row);
       }
       fclose($df);
       return ob_get_clean();
    }
    
    function add_lib(){
        wp_enqueue_script("cf7-save-data",CT7_SAVE_PLUGIN_URL."backend/js/cf7_save.js",array(),time());
    }

    public static function set_screen( $status, $option, $value ) {
        return $value;
    }
    public function plugin_menu() {
         $hook =   add_submenu_page("wpcf7",__("Database",CT7_SAVE_TEXT_DOMAIN),__("Database",CT7_SAVE_TEXT_DOMAIN),'manage_options','cf7-database',[ $this, 'plugin_settings_page' ]);
        add_action( "load-$hook", [ $this, 'screen_option' ] );
    }
    /**
     * Plugin settings page
     */
    public function plugin_settings_page() {
        ?>
        <div class="wrap">
            <h2><?php _e("Contact form 7 Database",CT7_SAVE_TEXT_DOMAIN) ?></h2>
            <form method="post" action="">
            <?php

            $this->customers_obj->prepare_items();
            $this->customers_obj->display(); ?>
            </form>
        </div>
    <?php
    }
    /**
     * Screen options
     */
    public function screen_option() {
        $option = 'per_page';
        $args   = [
            'label'   => 'Number posts',
            'default' => 5,
            'option'  => 'customers_per_page'
        ];
        add_screen_option( $option, $args );
        $this->customers_obj = new cf7Database_Save_Table();

    }
    /** Singleton instance */
    public static function get_instance() {
        if ( ! isset( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    
}
add_action( 'plugins_loaded', function () {
    cf7_save_to_database_backend::get_instance();
} );
