<?php

    class NuzukaLeadGenerationActivator {
        
        private static $oc = "__nzk_org_code__";
        private static $pc = "__nzk_prof_code__";
        
    	public static function activate($surl) {
    	    $rdata = (object) array();
            $rdata->oc = self::$oc;
            $rdata->pc = self::$pc;
            $rdata->surl = $surl;
            
            error_log("Request json [".json_encode($rdata)."]");
    	    $ch = curl_init("https://api.pearnode.com/nuzuka/site/plugin/activate.php");
    	    curl_setopt($ch, CURLOPT_POST, 1);
    	    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($rdata));
    	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    	    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    	    curl_setopt($ch, CURLOPT_FAILONERROR, true);
    	    curl_exec($ch);
    	    $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    	    $curl_errno = curl_errno($ch);
    	    error_log("CURL HTTP status [$http_status] and error number [$curl_errno]");
    	    curl_close($ch);
    	}
    }

?>