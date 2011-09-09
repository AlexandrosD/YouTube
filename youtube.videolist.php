<?php
/**
* YouTube PHP library :: YouTubeVideoList class
*
* @author Alexandros D
* @copyright Alexandros D 2011
* @license GNU/GPL v2
* @since 0.7.0
*
*/

require_once 'youtube.php';
require_once 'youtube.video.php';

class YouTubeVideoList {
	protected $_videos;
	protected $_totalVideos;
	protected $_maxResults;
	protected $_startIndex;
	protected $_youtube;
	protected $_developerKey;
	
	/**
	* Constructor
	*
	* @param String $developerKey The developer that wuill be used if calls are to be performed
	*/
	public function __construct( $developerKey = NULL ) {
		$this->_developerKey = $developerKey;
	}
	
	/**************************************************************************
	* Public Functions
	**************************************************************************/
	
	/**
	* Fetch list's videos
	*
	* @return Array A YouTubeVideo() array holding playlist's videos
	*/
	public function getVideos() {
		return $this->_videos;
	}
	
	/**
	
	/**
	* Count list's videos
	*
	* @return int The number of videos
	*/
	public function getVideosCount() {
		return $this->_totalVideos;
	}
	
	/**
	* Get Start Index
	*
	* @return int The starting index
	*/
	public function getStartIndex() {
		return $this->_startIndex;
	}
	
	/**
	* Get maximum Results
	*
	* @return int The maximum results
	*/
	public function getMaxResults() {
		return $this->_maxResults;
	}
	
	/**
	* Populate the list with an ATOM 1.0, RSS 2.0 or JSON feed
	* Notice: Currently ony ATOM 1.0 feed are supported
	* 
	* @param String $data The data in plain text format
	* @param String $format Supported values are 'atom' , 'rss' , 'json'
	* @return boolean
	*/
	public function populate( $data , $format = "atom") {		
		if ( $format == 'rss' )
			return $this->_parseRss( $data);
		
		if ( $format == 'json' )
			return $this->_parseJson( $data);
		/* else */
		return $this->_parseAtom( $data);
	}	
	
	/**
	* Populate the list by loading data using YouTube class function calls
	* 
	* @param String $source The source function that will be called. Supported values are:
	* getRelatedVideos, getUploadsByUser, getSubscriptionsByUser, getPlaylist, getFavoritesByUser
	* @param String $key The first & base parameter of each of these functions
	* i.e The getRelatedVideos has a videoId as key while the getUploads by user has the username as key
	* @param int $maxResults The maximum number of results to fetch
	* @param int $startIndex the needle...
	* @return boolean
	*/
	public function load( $source , $key , $maxResults = 0 , $startIndex = 0 ) {
		$functionName = "get" . $source;
		
		if ( !$this->_youtube )
			$this->_youtube = new YouTube( $this->_developerKey );
		
		$data = $this->_youtube-> $functionName ( $key , $maxResults , $startIndex );
		
		if ($data) {
			return $this->_parseAtom( $data );
		}
		/* else */
		return FALSE;
	}
	
	/**************************************************************************
	 * Private Functions
	 **************************************************************************/
	
	private function _parseAtom( $data ){
		$xml = new SimpleXMLElement( $data );
		
		//fetch videos
		$this->_videos = array();
		foreach ( $xml->entry as $entry ) {
			$video = new YouTubeVideo( $entry , $this->_developerKey );
			$this->_videos[] = $video;
		}
		
		//fetch list data
		$namespaces = $xml->getNameSpaces(true);
		//$openSearch = $entry->children($namespaces['openSearch']); //for some reason is buggy on user uploads..
		$openSearch = $xml->children($namespaces['openSearch']);
        
		$this->_totalVideos = (int) $openSearch->totalResults;
		$this->_startIndex = (int) $openSearch->startIndex;
		$this->_maxResults = (int) $openSearch->itemsPerPage;
		
		if ($this->_videos) {
			return TRUE;
		}
		/* else */
		return FALSE;
	}
	
	private function _parseRss( $data ) {
		//TODO
		return FALSE;
	}
	
	private function _parseJson( $data ) {
		//TODO
		return FALSE;
	}
}