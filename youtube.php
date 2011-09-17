<?php 
/**
 * YouTube PHP library
 * 
 * @author Alexandros D
 * @copyright Alexandros D 2011
 * @license GNU/GPL v2
 * @since 0.0.1
 * 
 * 
 */

class YouTube {
	
	private $_developerKey;
	private $_lastError;
	private $_baseUrl;
	private $_format; /* null = atom ; rss = RSS 2.0 ; json = JSON */
	
	private $_debug;
	
	public function __construct( $developerKey = NULL , $baseUrl = NULL , $format = NULL) {
		$this->_developerKey = $developerKey;
		if ( $baseUrl ) {
			$this->_baseUrl = $baseUrl;
		}
		else {
			$this->_baseUrl = "http://gdata.youtube.com/feeds/";
		}
		$this->_format = $format;
		$this->_debug = FALSE;
	}
	
	/***************************************************************************
	****************************************************************************
	*** Public functions
	*****************************************************************************
	*****************************************************************************/
	/**
	 * Returns an Exception object about the last error that occured
	 * 
	 * @return Exception
	 */
	public function getLastError() {
		return $this->_lastError;
	}
	
	/**
	* Load a specific user's playlists
	*
	* @param String $username The username
	* @param int $maxResults The maximum results to return
	* @param int $startIndex If set then it will skip the first $startIndex-1 entries
	* @return String
	*/
	public function getPlaylistsByUser ( $username , $maxResults = 0 , $startIndex = 0 ) {
		$url = $this->_baseUrl . "api/users/" . $username . "/playlists";
		
		//YouTube limits maxResults to 50
		if ( $maxResults > 50 )
			$maxResults = 50;
		
        $params = $this->_getParams( $maxResults , $startIndex );
		
		return $this->_httpGet($url , $params);
	}
	
	/**
	* Load a specific user's favorite videos
	*
	* @param String $username The username
	* @return String
	*/
	public function getFavoritesByUser ( $username , $maxResults = 0 , $startIndex = 0 ) {
		$url = $this->_baseUrl . "api/users/" . $username . "/favorites";
		
		//YouTube limits maxResults to 50
		if ( $maxResults > 50 )
			$maxResults = 50;
		
        $params = $this->_getParams( $maxResults , $startIndex );
		
		return $this->_httpGet($url , $params);
	}
	
	/**
	* Load a specific playlist
	*
	* @param String $playlist The playlist id
	* @return String
	*/
	public function getPlaylist ( $playlist , $maxResults = 0 , $startIndex = 0 ) {
		$url = $this->_baseUrl . "api/playlists/" . $playlist;
		
		//YouTube limits maxResults to 50
		if ( $maxResults > 50 )
			$maxResults = 50;
		
		$params = $this->_getParams( $maxResults , $startIndex ); 
		
		return $this->_httpGet($url , $params);
	}
	
	/**
	* Load a specific playlist entry
	*
	* @param String $playlist The playlist id
	* @param String $entry The entry id
	* @return String
	*/
	public function getPlaylistEntry ( $playlist , $entry ) {
		$url = $this->_baseUrl . "api/playlists/" . $playlist . "/" . $entry;
		return $this->_httpGet($url);
	}
	
	/**
	* Load a specific user's subscriptions
	*
	* @param String $username The username
	* @param int $maxResults The maximum results to return
	* @param int $startIndex If set then it will skip the first $startIndex-1 entries
	* @return String
	*/
	public function getSubscriptionsByUser ( $username , $maxResults = 0 , $startIndex = 0 ) {
		$url = $this->_baseUrl . "api/users/" . $username . "/subscriptions";
		
		//YouTube limits maxResults to 50
		if ( $maxResults > 50 )
			$maxResults = 50;
	
		$params = $this->_getParams( $maxResults , $startIndex );
		
		return $this->_httpGet($url , $params);
	}
	
	/**
	* Load a specific user's uploads
	*
	* @param String $username The username
	* @param int $maxResults The maximum results to return
	* @param int $startIndex If set then it will skip the first $startIndex-1 entries
	* @param String $orderby If null, will sort by relevance. Other options are 'published' and 'viewCount'
	* @return String
	*/
	public function getUploadsByUser ( $username , $maxResults = 0 , $startIndex = 0 , $orderby = NULL) {
		$url = $this->_baseUrl . "api/users/" . $username . "/uploads";
		
		//YouTube limits maxResults to 50
		if ( $maxResults > 50 )
			$maxResults = 50;
	
		$params = $this->_getParams( $maxResults , $startIndex );
	
		return $this->_httpGet($url , $params);
	}
	
	/**
	* Load a video's related videos
	*
	* @param String $videoId The video id
	* @param int $maxResults The maximum results to return
	* @param int $startIndex If set then it will skip the first $startIndex-1 entries
	* @return String
	*/
	public function getRelatedVideos ( $videoId , $maxResults = 0 , $startIndex = 0 ) {
		$url = $this->_baseUrl . "api/videos/" . $videoId . "/related";
		
		//YouTube limits maxResults to 50
		if ( $maxResults > 50 )
			$maxResults = 50;
	
		$params = $this->_getParams( $maxResults , $startIndex );
	
		return $this->_httpGet($url , $params);
	}
	
	/**
	* Load a video's comments
	*
	* @since 0.7.5
	*
	* @param String $videoId The video id
	* @param int $maxResults The maximum results to return
	* @param int $startIndex If set then it will skip the first $startIndex-1 entries
	* @return String
	*/
	public function getComments ( $videoId , $maxResults = 0 , $startIndex = 0 ) {
		$url = $this->_baseUrl . "api/videos/" . $videoId . "/comments";
	
		//YouTube limits maxResults to 50
		if ( $maxResults > 50 )
			$maxResults = 50;
	
		$params = $this->_getParams( $maxResults , $startIndex );
	
		return $this->_httpGet($url , $params);
	}
    
    /**
    * Get a specific user's details
	*
	* @param String $username The user's id
	* @return String
	*/
	public function getUserData ( $username ) {
		$url = $this->_baseUrl . "api/users/" . $username;
		return $this->_httpGet($url);
	}
	
	/**
	 * Get a single video's data
	 * 
	 * @param String $videoId The video ID
	 * @return String 
	 */
	public function getVideo( $videoId ) {
		$url = $this->_baseUrl . "api/videos/" . $videoId;
		return $this->_httpGet($url);
	}
	
	/***************************************************************************
	****************************************************************************
	*** Private functions
	*****************************************************************************
	*****************************************************************************/
    
    /**
     * Returns an array containing the parameters that will be included in the request.
     *
     * @returns Array
     */
    private function _getParams( $maxResults, $startIndex ) {
        $params = Array();
    	if ($maxResults !=0 && $startIndex !=0) {
			$params[] = "max-results=" . $maxResults;
			$params[] = "start-index=" . $startIndex;
		}
		if ($maxResults == 0 && $startIndex != 0) {
			$params[] = "start-index=" . $startIndex;
		}
		if ($maxResults != 0 && $startIndex == 0) {
			$params[] = "max-results=" . $maxResults;
		}
        return $params;
    }
    
	/**
	* Perform an HTTP get request
	*
	* @param String $url The url to GET
	* @param Array $params An array of parameters
	* @return String
	*/
	private function _httpGet( $url , $params = NULL) {
		if ($this->_format != NULL) {
			$params[] = "alt=" . $this->_format;
		}
		
		if ( count($params) ) {
			$queryString = implode("&", $params);
			$url .= "?" . $queryString;
		}
		
		if ($this->_debug) {
			echo "URL: $url<br /><br />";
		}
		
		//Initialize curl
		$ch = curl_init();
		
		//Set curl url
		curl_setopt($ch, CURLOPT_URL, $url);
		
		//HTTP headers
		$headers = array(
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
