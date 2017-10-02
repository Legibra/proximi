<?php
if ( !function_exists( 'add_action' ) ) {
    echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
    exit;
}
class cf7_savetodata_frontend {
    function __construct(){
        add_action( 'wpcf7_before_send_mail', array($this,'save_data') );
    }
    function save_data( $form_tag ) {
      global $wpdb;
      
      $form = WPCF7_Submission::get_instance();
      if ( $form ) {
          $form_data   = array();
          $data        = $form->get_posted_data();
          foreach ($data as $key => $value) {


                $name_value = "";
                  if ( ! is_array($value) ){
                      $search   = array('\"',"\'",'/','\\');
                      $replace   = array('&quot;','&#039;','&#047;', '&#092;');
                      $name_value = str_replace($search, $replace,$value);
                  } 
                  $form_data[$key] = $name_value; 
              
          }


   
          $wpdb->insert( $wpdb->prefix."cf7_data", array( 
              'contact_id' => $form_tag->id(),
              'contact_name'   => $form_tag->title(),
              'value'    => maybe_serialize( $form_data ),
              'date'    => current_time('timestamp')
          ) );
      }

  }
}
new cf7_savetodata_frontend;
