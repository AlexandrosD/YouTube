<?php
/**
* YouTube PHP library
*
* @author Alexandros D
* @copyright Alexandros D 2011
* @license GNU/GPL v2
* @since 0.6.0
*
*/

require_once 'youtube.php';
require_once 'youtube.video.php';
require_once 'youtube.videolist.php';

class YouTubePlaylist extends YouTubeVideoList {
	private $_title;
	private $_subtitle;
	private $_id;
	
	/**
	* Load playlist data
	*
	* @param String $playlistId The playlist id
	*/
	public function __construct( $playlistId , $developerKey = NULL ) {
		$this->_id = $playlistId;
		$this->_developerKey = $developerKey;
		$this->_loadInfo();
	}
    
    /**
    * Get Playlist ID
	*
	* @return int the Playlist ID
	*/
	public function getID() {
		return $this->_id;
	}
	
	/**
	* Load Playlist
	*
	* @return boolean
	*/
	public function loadPlaylist( $maxResults = 0 , $startIndex = 0 ) {
		$this->_maxResults = $maxResults;
		$this->_startIndex = $startIndex;
		return $this->_loadVideos();
	}
	
	/**
	* Fetch playlist's title
	*
	* @return String The playlist's title
	*/
	public function getTitle() {
		return $this->_title;
	}
	
	/**
	* Fetch playlist's subtitle
	*
	* @return String The playlist's title
	*/
	public function getSubtitle() {
		return $this->_subtitle;
	}
	
	private function _loadVideos(){
		$youtube = new YouTube( $this->_developerKey );
		$playlist =  $youtube->getPlaylist( $this->_id , $this->_maxResults , $this->_startIndex );
		
		//load playlist data
		$xml = new SimpleXMLElement( $playlist );
		$this->_title = (string) $xml->title;
		$this->_subtitle = (string) $xml->subtitle;
		
		//load videos
		return parent::populate( $playlist );
	}
	
	private function _loadInfo() {
		$youtube = new YouTube( $this->_developerKey );
		$playlist =  $youtube->getPlaylist( $this->_id );
		
		//load playlist data
		$xml = new SimpleXMLElement( $playlist );
		$this->_title = (string) $xml->title;
		$this->_subtitle = (string) $xml->subtitle;
	}
}