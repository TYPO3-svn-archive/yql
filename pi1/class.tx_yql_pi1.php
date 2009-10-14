<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2009 Sebastian Gebhard <sebastian.gebhard@gmail.com>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/
/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 * Hint: use extdeveval to insert/update function index above.
 */

require_once(PATH_tslib.'class.tslib_pibase.php');


/**
 * Plugin 'YQL' for the 'yql' extension.
 *
 * @author	Sebastian Gebhard <sebastian.gebhard@gmail.com>
 * @package	TYPO3
 * @subpackage	tx_yql
 */
class tx_yql_pi1 extends tslib_pibase {
	var $prefixId      = 'tx_yql_pi1';		// Same as class name
	var $scriptRelPath = 'pi1/class.tx_yql_pi1.php';	// Path to this script relative to the extension dir.
	var $extKey        = 'yql';	// The extension key.
	var $pi_checkCHash = true;
	
	/**
	 * The main method of the PlugIn
	 *
	 * @param	string		$content: The PlugIn content
	 * @param	array		$conf: The PlugIn configuration
	 * @return	The content that is displayed on the website
	 */
	function main($content, $conf) {
		$this->conf = $conf;
		$this->pi_setPiVarDefaults();
		$this->pi_loadLL();
		
		$conf['select.']['where'] = $this->cObj->stdWrap($conf['select.']['where'], $conf['select.']['where.']);
		$conf['select.']['fields'] = $this->cObj->stdWrap($conf['select.']['fields'], $conf['select.']['fields.']);
		$conf['select.']['remotelimit'] = $this->cObj->stdWrap($conf['select.']['remotelimit'], $conf['select.']['remotelimit.']);
		$conf['select.']['locallimit'] = $this->cObj->stdWrap($conf['select.']['locallimit'], $conf['select.']['locallimit.']);
		$conf['select.']['functions'] = $this->cObj->stdWrap($conf['select.']['functions'], $conf['select.']['functions.']);
		
		$query =
			'select ' . $conf['select.']['fields'] .
			' from ' . $conf['select.']['table'] .
			'('. intval($conf['select.']['remotelimit']) . ')' .
			' where ' . $conf['select.']['where'] .
			($conf['select.']['locallimit'] ? ' limit ' . $conf['select.']['locallimit'] . ' ' : '') .
			$conf['select.']['functions'];
		
		$url = 'http://query.yahooapis.com/v1/public/yql?q=' . urlencode($query) . '&format=xml&diagnostics=false';
		$xml = simplexml_load_string(file_get_contents($url));
		
		$content = '';
		$i = 0;
		foreach($xml->results->item as $item){
			if($conf['limit'] && ($i++ < $conf['limit'])){
				$this->cObj->data = array();
				foreach($item as $field => $value){
					$this->cObj->data[(string)$field] = (string)$value;
				}
				$content .= $this->cObj->COBJ_ARRAY($conf['renderObj.']);
			}
		}
		
		$content = $this->cObj->stdWrap($content, $conf['stdWrap.']);
	
		return $this->pi_wrapInBaseClass($content);
	}
}



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/yql/pi1/class.tx_yql_pi1.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/yql/pi1/class.tx_yql_pi1.php']);
}

?>