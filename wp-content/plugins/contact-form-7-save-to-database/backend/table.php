<?php
if ( !function_exists( 'add_action' ) ) {
    echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
    exit;
}
if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}
class cf7Database_Save_Table extends WP_List_Table {
	/** Class constructor */
	public function __construct() {
		parent::__construct( [
			'singular' => __( 'Customer', CT7_SAVE_TEXT_DOMAIN ), //singular name of the listed records
			'plural'   => __( 'Customers', CT7_SAVE_TEXT_DOMAIN ), //plural name of the listed records
			'ajax'     => false //does this table support ajax?
		] );
	}
	/**
	 * Retrieve customers data from the database
	 *
	 * @param int $per_page
	 * @param int $page_number
	 *
	 * @return mixed
	 */
	public static function get_customers( $per_page = 5, $page_number = 1 ) {
		global $wpdb;
		$form = @$_GET["form"];
		$where ="";
		if( is_numeric($form)) {
			$where = " WHERE contact_id = ".$form;
		}
		$sql = "SELECT * FROM {$wpdb->prefix}cf7_data{$where} ORDER BY id DESC ";
		$sql .= " LIMIT $per_page";
		$sql .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;
		$result = $wpdb->get_results( $sql, 'ARRAY_A' );
		return $result;
	}
	/**
	 * Delete a customer record.
	 *
	 * @param int $id customer ID
	 */
	public static function delete_customer( $id ) {
		global $wpdb;
		$wpdb->delete(
			"{$wpdb->prefix}cf7_data",
			[ 'id' => $id ],
			[ '%d' ]
		);
	}
	/**
	 * Returns the count of records in the database.
	 *
	 * @return null|string
	 */
	public static function record_count() {
		global $wpdb;
		$form = @$_GET["form"];
		$where ="";
		if( is_numeric($form)) {
			$where = " WHERE contact_id =".$form;
		}
		$sql = "SELECT COUNT(*) FROM {$wpdb->prefix}cf7_data{$where}";
		return $wpdb->get_var( $sql );
	}
	/** Text displayed when no customer data is available */
	public function no_items() {
		_e( 'No database avaliable.', CT7_SAVE_TEXT_DOMAIN);
	}
	/**
	 * Render a column when no column specific method exist.
	 *
	 * @param array $item
	 * @param string $column_name
	 *
	 * @return mixed
	 */
	public function column_default( $item, $column_name ) {
    switch ( $column_name ) {
			case 'form':
                return '<a target="_blank" href="'.admin_url( 'admin.php?page=wpcf7&post='.$item["contact_id"].'&action=edit').'" >'.$item["contact_name"].'</a>';
			case 'data':
				$list_hide   = array('_wpcf7', '_wpcf7_version', '_wpcf7_locale', '_wpcf7_unit_tag','_wpcf7_is_ajax_call','cfdb7_name', '_wpcf7_container_post','_cf_logic');
                $meta = maybe_unserialize($item["value"]);
                 $data_text = "<ul>";
                foreach ($meta as $key => $value) {
                	if( !in_array($key, $list_hide) && $value != "") :
                		$data_text .= "<li><Strong>".$key."</strong>: ".$value."</li>";
                	endif;
                	 
                }
                return $data_text;
			case 'email':
				$meta = maybe_unserialize($item["value"]);
				foreach ($meta as $key => $value) { 
					if( preg_match("#mail#", $key) ) { 
						return print_r( $value, true );		
					}
				}
				return $item;
			case 'name':
				$meta = maybe_unserialize($item["value"]);
				foreach ($meta as $key => $value) { 
					if( preg_match("#name#", $key) ) { 
						return print_r( $value, true );		
					}
				}
				return $item;
			case 'subject':
				$meta = maybe_unserialize($item["value"]);
				foreach ($meta as $key => $value) { 
					if( preg_match("#subject#", $key) ) { 
						return print_r( $value, true );		
					}
				}
				return $item;  
            case 'pdf':
                return '<a target="_blank" download href="'.CT7_SAVE_PLUGIN_URL."backend/pdf.php?id=".$item["id"].'&nonce='.rand(10000,9999999).'" >Download</a>';
            case 'date':
                return date('Y-d-m H:i:s',$item["date"]);
			default:
				return print_r( $item, true ); //Show the whole array for troubleshooting purposes
		}
	}
	/**
	 * Render the bulk edit checkbox
	 *
	 * @param array $item
	 *
	 * @return string
	 */
	function column_cb( $item ) {
		return sprintf(
			'<input type="checkbox" name="delete[]" value="%s" />', $item['id']
		);
	}
	/**
	 *  Associative array of columns
	 *
	 * @return array
	 */
	function get_columns() {
		global $wpdb;
		$form = @$_GET["form"];
		$where ="";
		if( is_numeric($form)) {
			$where = " WHERE contact_id = ".$form;
		}
		$sql = "SELECT * FROM {$wpdb->prefix}cf7_data{$where}";
		$rs = $wpdb->get_row($sql);
		$meta = maybe_unserialize($rs->value);

		$columns = [
			'cb'      => '<input type="checkbox" />',
			'form'    => __( 'Form', CT7_SAVE_TEXT_DOMAIN ),
		];
		foreach ($meta as $key => $value) { 
			if( preg_match("#mail#", $key) ) {
				$columns["email"] = __("Email",CT7_SAVE_TEXT_DOMAIN);
			}elseif ( preg_match("#name#", $key) ) {
				$columns["name"] = __("Name",CT7_SAVE_TEXT_DOMAIN);	
			}elseif ( preg_match("#subject#", $key) ) { 
				$columns["subject"] = __("Subject",CT7_SAVE_TEXT_DOMAIN);
			}
		}
		$columns["data"] = __( 'Data', CT7_SAVE_TEXT_DOMAIN );
		$columns["pdf"] = __( 'Download PDF', CT7_SAVE_TEXT_DOMAIN );
		$columns["date"] = __( 'Date', CT7_SAVE_TEXT_DOMAIN );
		return $columns;
	}
	/**
	 * Columns to make sortable.
	 *
	 * @return array
	 */
	/**
	 * Returns an associative array containing the bulk action
	 *
	 * @return array
	 */
	public function get_bulk_actions() {
		
		return $actions;
	}
	/**
	 * Handles data query and filter, sorting, and pagination.
	 */
	public function prepare_items() {
		$this->_column_headers = $this->get_column_info();
		/** Process bulk action */
		$this->process_bulk_action();
		$per_page     = $this->get_items_per_page( 'customers_per_page', 10 );
		$current_page = $this->get_pagenum();
		$total_items  = self::record_count();
		$this->set_pagination_args( [
			'total_items' => $total_items, //WE have to calculate the total number of items
			'per_page'    => $per_page //WE have to determine how many items to show on a page
		] );
		$this->items = self::get_customers( $per_page, $current_page );
	}
	public function process_bulk_action() {
		
		
	}
	protected function bulk_actions( $which = '' ) {
       	?>			
			<div class="alignleft actions">
						
				<label class="screen-reader-text" for="cat">Filter by category</label><select name="cat" id="cat" class="postform">
					<option data-id="0" value="<?php echo admin_url( 'admin.php?page=cf7-database') ?>">All Form</option>
					<?php
					$the_query = new WP_Query( array("posts_per_page"=>-1,"post_type"=>"wpcf7_contact_form") );
					while ( $the_query->have_posts() ): $the_query->the_post();
					?>
					<option <?php selected( @$_GET["form"], get_the_id()) ?> data-id="<?php echo admin_url( 'admin-post.php?action=print.csv&id=' ).get_the_id()  ?>" value="<?php echo admin_url( 'admin.php?page=cf7-database&form='). get_the_id(); ?>"><?php the_title() ?></option>
					<?php
					endwhile; wp_reset_postdata();
					?>
				</select>
				<input type="submit" name="filter_action" id="cf7-filter-form" class="button" value="Filter">	
				<input type="submit" name="filter_action" id="cf7-export-form" class="button" value="Export CSV">		
			</div>
       	<?php
 		
    }
}
