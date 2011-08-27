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

class YouTubePlaylist {
	private $_videos;
	private $_title;
	private $_id;
	
	/**
	* Load playlist data
	*
	* @param String $playlistId The playlist id
	*/
	public function __construct( $playlistId ) {
		$this->_id = $playlistId;
		$this->_videos = array();
		$this->_loadVideos();
	}
	
	/**
	* Fetch playlist's videos
	*
	* @return Array A YouTubeVideo() array holding playlist's videos
	*/
	public function getVideos() {
		return $this->_videos;
	}
	
	/**
	* Fetch playlist's title
	*
	* @return String Tha playlist's title
	*/
	public function getTitle() {
		return $this->_title;
	}
	
	/**
	* Count playlist's videos
	*
	* @return int The number of videos
	*/
	public function getVideosCount() {
		return count( $this->_videos );
	}
	
	/**
	* Calculate playlist's duration
	*
	* @return int The total playlist duration
	*/
	public function getTotalDuration() {
		$duration = 0;
		foreach ($this->_videos as $v) {
			$duration += (int) $duration;
		}
		return $duration;
	}
	
	private function _loadVideos(){
		$youtube = new YouTube();
		$playlist =  $youtube->getPlaylist( $this->_id );
		
		$xml = new SimpleXMLElement($playlist);
		
		foreach ( $xml->entry as $entry ) {
			$video = new YouTubeVideo( $entry );
			$this->_videos[] = $video;
		}
	}
}