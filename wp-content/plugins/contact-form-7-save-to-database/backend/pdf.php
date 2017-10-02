<?php 
$parse_uri = explode( 'wp-content', $_SERVER['SCRIPT_FILENAME'] );
require_once( $parse_uri[0] . 'wp-load.php' );
include CT7_SAVE_PLUGIN_PATH."mpdf/mpdf.php";

$id = $_GET["id"];
if( is_numeric($id) ) {
	global $wpdb;
	$info= $wpdb->get_row( "SELECT * FROM ".$wpdb->prefix.CT7_SAVE_TABLE ." WHERE id = ".$id );
	//var_dump($info);
	$mpdf = new mPDF();
	$mpdf->Bookmark('Contact Form');
	$mpdf->WriteHTML('<h1>'.$info->contact_name.'</h1>');
	$mpdf->WriteHTML('<ul>');

	$meta = maybe_unserialize($info->value);
	$list_hide   = array('_wpcf7', '_wpcf7_version', '_wpcf7_locale', '_wpcf7_unit_tag','_wpcf7_is_ajax_call','cfdb7_name', '_wpcf7_container_post',"_cf_logic");
	 foreach ($meta as $key => $value) {
    	if( !in_array($key, $list_hide) && $value != "") :
    		$mpdf->WriteHTML("<li><Strong>".$key."</strong>: ".$value."</li>");
    	endif;
    	 
    }
	$mpdf->WriteHTML('</ul>');
	$mpdf->Output('contactform-'.$id.'.pdf', 'I');
}


