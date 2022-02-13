<?php

    class NuzukaLeadGenerationActivator {
        
        private static $oc = "__nzk_org_code__";
        private static $pc = "__nzk_prof_code__";
        
    	public static function activate($surl) {
    	    $rdata = (object) array();
            $rdata->oc = self::$oc;
            $rdata->pc = self::$pc;
            $rdata->surl = $surl;
            
            error_log("Activation: Nuzuka Server Request json [".json_encode($rdata)."]");
    	    $ch = curl_init("https://api.pearnode.com/nuzuka/site/plugin/activate.php");
    	    curl_setopt($ch, CURLOPT_POST, 1);
    	    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($rdata));
    	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    	    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    	    curl_setopt($ch, CURLOPT_FAILONERROR, true);
    	    curl_exec($ch);
    	    $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    	    $curl_errno = curl_errno($ch);
    	    error_log("Activation: Nuzuka server status [$http_status] and error number [$curl_errno]");
    	    curl_close($ch);
    	    
    	    createDB();
    	}
    	
    	public static function createDB(){
    	    global $wpdb;
    	    $test_db_version = "1.0.0";
    	    $db_table_name = $wpdb->prefix . 'nzk_page_widget_map';  // table name
    	    $charset_collate = $wpdb->get_charset_collate();
    	    
    	    //Check to see if the table exists already, if not, then create it
    	    if($wpdb->get_var( "show tables like '$db_table_name'" ) != $db_table_name ) {
    	        $sql = "CREATE TABLE $db_table_name (
                    id int(11) NOT NULL auto_increment,
                    page_id int(11) NOT NULL,
                    widget_id varchar(60) NOT NULL,
                    UNIQUE KEY id (id)
                    ) $charset_collate;";
    	       require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    	       dbDelta( $sql );
    	       add_option( 'test_db_version', $test_db_version );
    	    }
    	}
    }

?>