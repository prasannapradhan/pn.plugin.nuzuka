<?php

    class NuzukaPluginActivator {
        
    	public static function activate($surl) {
    	    $plugin_directory = plugin_basename(__FILE__);
    	    error_log("Plugin directory basename [$plugin_directory]");
    	    $pdname = dirname($plugin_directory);
    	    error_log("Plugin directory name [$pdname]");
    	}
    	
    }

?>