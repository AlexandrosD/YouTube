<?php 
/**
 * YouTube PHP library
 * 
 * @author alexd3499
 * @copyright alexd3499 2011
 * @license GNU/GPL v2
 * @version 0.0.1
 * 
 */

class YouTube {
	
	private $_developerKey;
	private $_lastError;
	private $_baseUrl;
	
	public function __construct( $developerKey = NULL , $baseUrl = NULL) {
		$this->_developerKey = $developerKey;
		if ( $baseUrl ) {
			$this->_baseUrl = $baseUrl;
		}
		else {
			$this->_baseUrl = "http://gdata.youtube.com/feeds/api/";
		}
	}
	
	/*
	 * Public functions
	 */
	
	public function getLastError() {
		return $this->_lastError;
	}
	
	/**
	* Load a specific user's playlists
	* 
	* @name getPlaylistsByUser
	* @param String $username The username
	* @return String
	*/
	public function getPlaylistsByUser ( $username ) {
		$url = $this->_baseUrl . "users/" . $username . "/playlists";
		return $this->_httpGet($url);
	}
	
	/**
	* Helper function that transforms xml to array
	*
	* @name xmlToArray
	* @param String $xml The XML string to transform
	* @return Array
	*/
	public function xmlToArray ( $xml ) {
		//TODO: add implementation..
		return $xml;
	}
	
	/*
	 * Private functions
	 */
	
	/**
	* Perform an HTTP get request
	*
	* @name _httpGet
	* @param String $url The url to GET
	* @return String
	*/
	private function _httpGet( $url ) {
		//Initialize curl
		$ch = curl_init();
		
		//Set curl url
		curl_setopt($ch, CURLOPT_URL, $url);
		
		//HTTP headers
		$headers = array(
			//'Content-type: application/x-www-form-urlencoded; charset=UTF-8',
			'GData-Version: 2',
			'Cache-Control: no-cache'
		);
		
		//Set dev key if has been provided
		if ( $this->_developerKey ) {
			$headers[] = 'X-GData-Key: key=' . $this->_developerKey;
		}
		
		//Set HTTP headers
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers );
		
		//Start buffering output
		ob_start();
		
		//fetch data
		try {
			curl_exec($ch);
			curl_close($ch);
			$data = ob_get_contents();
			ob_end_clean();
		} 
		catch(Exception $err) {
			$data = null;
			$this->_lastError = $err;
		}
		
		//return data
		return $data;
	} 	
}