<?php

    class NuzukaLeadGenerationDeactivator {
    
        private static $oc = "__nzk_org_code__";
        private static $pc = "__nzk_prof_code__";
        
    	public static function deactivate($surl) {
    	    $rdata = (object) array();
    	    $rdata->oc = self::$oc;
    	    $rdata->pc = self::$pc;
    	    $rdata->surl = $surl;
    	    
    	    error_log("Deactivation: Nuzuka Server Request json [".json_encode($rdata)."]");
    	    $ch = curl_init("https://api.pearnode.com/nuzuka/site/plugin/deactivate.php");
    	    curl_setopt($ch, CURLOPT_POST, 1);
    	    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($rdata));
    	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    	    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    	    curl_setopt($ch, CURLOPT_FAILONERROR, true);
    	    curl_exec($ch);
    	    $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    	    $curl_errno = curl_errno($ch);
    	    error_log("Deactivation: Nuzuka server status [$http_status] and error number [$curl_errno]");
    	    curl_close($ch);
    	}
    }

?>