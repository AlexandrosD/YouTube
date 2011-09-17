<?php
/**
* YouTube PHP library
*
* @author Alexandros D
* @copyright Alexandros D 2011
* @license GNU/GPL v2
* @since 0.8.0
*
*/

require_once 'youtube.php';
require_once 'youtube.video.comment.php';

class YouTubeVideoComments {
	private $_maxResults;
	private $_startIndex;
	private $_totalComments;
	private $_comments;
	private $_developerKey;
	
	/**
	* Load comments data
	*
	* @param String $playlistId The playlist id
	*/
	public function __construct( $videoId , $developerKey = NULL ) {
		$this->_id = $videoId;
		$this->_developerKey = $developerKey;
	}
	
	public function getComments() {
		return $this->_comments;
	}
	
	/**
	* Load Comments
	*
	* @return boolean
	*/
	public function loadComments( $maxResults = 0 , $startIndex = 0 ) {
		$this->_maxResults = $maxResults;
		$this->_startIndex = $startIndex;
		return $this->_loadComments();
	}
	
	/**
	* Count Comments
	*
	* @return int
	*/
	public function getTotal(){
		return $this->_totalComments;
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
	
	
	private function _loadComments(){
		$youtube = new YouTube( $this->_developerKey );
		$data =  $youtube->getComments( $this->_id , $this->_maxResults , $this->_startIndex );
		
		$xml = new SimpleXMLElement( $data );
		
		//fetch comments
		$this->_comments = array();
		foreach ( $xml->entry as $entry ) {
			$comment = new YouTubeVideoComment( $entry );
			$this->_comments[] = $comment;
		}
		
		//fetch list data
		$namespaces = $xml->getNameSpaces(true);
        if ($namespaces) {
    	    $openSearch = $entry->children($namespaces['openSearch']);
		}
        else {
            return FALSE;
        }
		
		$this->_totalVideos = (int) $openSearch->totalResults;
		$this->_startIndex = (int) $openSearch->startIndex;
		$this->_maxResults = (int) $openSearch->itemsPerPage;
		
		if ($this->_comments) {
			return TRUE;
		}
		/* else */
		return FALSE;
	}
}