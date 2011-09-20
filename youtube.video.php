<?php
/**
* YouTube PHP library
*
* @author Alexandros D
* @copyright Alexandros D 2011
* @license GNU/GPL v2
* @since 0.6.0
*
* 
*/

require_once 'youtube.video.comments.php';
require_once 'youtube.php';

class YouTubeVideo {
	public $title;
	public $link;
	public $uploader;
	public $description;
	public $content;
	public $thumbnails;
	public $videoId;
	public $dislikes;
	public $likes;
	public $duration;
    public $duration_formatted;
	public $uploaded;
	public $viewCount;
	public $favoriteCount;
	public $comments;
	
	private $_developerKey;
	
	/**
	* Load video data from xml atom simplexml object
	*
	* @param SimpleXMLElement $videoData
	*/
	public function __construct( $videoData = NULL , $developerKey = NULL , $videoId = NULL ) {
		$this->_developerKey = $developerKey;
		
		//if video data (xml) is provided then load the video by xml
		if ( $videoData ) {
			$this->_load($videoData);
		}
		
		//if video id is provided then make a request to youtube and fetch video data
		if ( $videoId ) {
			$this->load($videoId);
		}
	}
	
	/**
	 * Load video data using video id
	 * @param String $videoId
	 */
	public function load( $videoId ) { 
		$youtube = new YouTube( $this->_developerKey );
		$videoData = $youtube->getVideo( $videoId );
		
		if ($videoData != "BAD_REQUEST") {
			$xml = new SimpleXMLElement( $videoData );
			$this->_load( $xml );
		}
	}
	
	/**
	* Load video data from xml atom simplexml object
	*
	* @param SimpleXMLElement $videoData
	*/
	private function _load( $videoData ) {
		$entry = $videoData;
		//title
		$this->title = (string) $entry->title;
		
		//link
		$this->link = (string) $entry->link[0]->attributes()->href;
		
		//namespaces
		$namespaces = $entry->getNameSpaces(true);
        
        if ($namespaces) {
    	    //media namespace
    	    $media = $entry->children($namespaces['media']);
		    //yt namespace
	    	$yt = $entry->children($namespaces['yt']);
		    //yt namespace inside media namespace
		    $media_yt = $media->children($namespaces['yt']);
		    //gd namespace
		    $gd = $entry->children($namespaces['gd']);
		}
        else {
            return FALSE;
        }
		
		//uploader
		$this->uploader = (string) $media->group->credit;
		
		//description
		$this->description = (string) $media->group->description;
		
		//thumbnails
		$this->thumbnails = array();
		foreach ($media->group->thumbnail as $thumbnail) {
			$thumb = new stdClass();

			$thumb->url = (string) $thumbnail->attributes()->url;
			$thumb->width = (int) $thumbnail->attributes()->width;
			$thumb->height = (int) $thumbnail->attributes()->height;
			$thumb->time = (string) $thumbnail->attributes()->time;
		
			$this->thumbnails[] = $thumb;
		}
		
		//content - full player - suppress errors because not all videos have this attribute
		@$this->content = (string) $media->group->content->attributes()->url;
		
		//rating - yt:rating - numDislikes - suppress errors because not all videos have this attribute
		@$this->dislikes = (int) $yt->rating->attributes()->numDislikes;
		
		//rating - yt:rating - numLikes - suppress errors because not all videos have this attribute
		@$this->likes = (int) $yt->rating->attributes()->numLikes;
		
		//video id
		$this->videoId = (string) $media_yt->videoid;
		
		//duration
    	$this->duration = (int) $media_yt->duration->attributes()->seconds;
		@$mins = (string) (int) ($this->duration / 60);
		@$secs = (string) (int) ($this->duration % 60);
		if ( strlen($secs) == 1 )
			$secs = "0" . $secs;
		$this->duration_formatted = $mins . ":" . $secs;
        
		//uploaded
		$this->uploaded = (string) $media_yt->uploaded;
		
		//statistics - viewCount
		$this->viewCount = (int) $yt->statistics->attributes()->viewCount;
		
		//statistics - favoriteCount
		$this->favoriteCount = (int) $yt->statistics->attributes()->favoriteCount;
		
		//comments
		$this->comments = new YouTubeVideoComments($this->videoId , $this->_developerKey );
	}	
}