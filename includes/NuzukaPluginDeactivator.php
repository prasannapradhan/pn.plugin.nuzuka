<?php

    class NuzukaPluginDeactivator {
    
    	public static function deactivate($surl) {
    	    $post_args = array(
    	        'timeout' => '5',
    	        'redirection' => '5',
    	        'httpversion' => '1.0',
    	        'blocking' => true,
    	        'headers' => array('Content-Type' => 'application/json; charset=utf-8'),
    	        'cookies' => array(),
    	        'method'  => 'POST',
    	        'data_format' => 'body'
    	    );
    	    $rdata = array('surl' => $surl);
    	    $post_args['body'] = json_encode($rdata);
    	    wp_remote_post('https://api.pearnode.com/sakamari/plugin/deactivate.php', $post_args);
    	}
    }

?>