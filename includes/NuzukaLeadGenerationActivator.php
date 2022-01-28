<?php

    class NuzukaLeadGenerationActivator {
        
        private static $oc = "__nzk_org_code__";
        private static $pc = "__nzk_prof_code__";
        
    	public static function activate($surl) {
            $rdata = (object) array();
            $rdata->oc = self::$oc;
            $rdata->pc = self::$pc;
            $rdata->surl = $surl;
            
    	    $ch = curl_init("https://api.pearnode.com/nuzuka/site/plugin/activate.php");
    	    curl_setopt($ch, CURLOPT_POST, 1);
    	    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($rdata));
    	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    	    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    	    curl_exec($ch);
    	    curl_close($ch);
    	}
    }

?>