<?php
defined('Rm_REST_LOG_VERBOSE') or define('Rm_REST_LOG_VERBOSE', 1000);
defined('Rm_REST_LOG_WARNING') or define('Rm_REST_LOG_WARNING', 500);
defined('Rm_REST_LOG_ERROR') or define('Rm_REST_LOG_ERROR', 250);
defined('Rm_REST_LOG_NONE') or define('Rm_REST_LOG_NONE', 0);

if (!class_exists('Rm_REST_Log')) {
	class Rm_REST_Log {
	    var $_level;

	    function __construct($level) {
	        $this->_level = $level;
	    }

	    function log_message($message, $module, $level) {
	        if($this->_level >= $level) {
	            echo date('G:i:s').' - '.$module.': '.$message."<br />\n";
	        }
	    }
	}
}