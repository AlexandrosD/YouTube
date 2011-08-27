<?php
/**
* YouTube PHP library
*
* @author Alexandros D
* @copyright Alexandros D 2011
* @license GNU/GPL v2
* @since 0.6.0
*
* @todo add comments support
* 
*/

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
	public $uploaded;
	public $viewCount;
	public $favoriteCount;
	
	//TODO
	public $comments;
	
	/**
	* Load video data from xml atom simplexml object
	*
	* @param SimpleXMLElement $videoData
	*/
	public function __construct( $videoData ) {
		$this->_load($videoData);
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
		
		//media namespace
		$media = $entry->children($namespaces['media']);
		//yt namespace
		$yt = $entry->children($namespaces['yt']);
		//yt namespace inside media namespace
		$media_yt = $media->children($namespaces['yt']);
		//gd namespace
		$gd = $entry->children($namespaces['gd']);
		
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
		
		//comments
		/*
		 * 	<gd:comments>
		 * 		<gd:feedLink href='http://gdata.youtube.com/feeds/api/videos/yT6Ti6ZUgSQ/comments' countHint='641'/>
		 * 	</gd:comments>
		 */
		//TODO: Implement YouTubeVideoComments class & getCommentsByVideo functions - use them to fetch comments..
		
		//rating - yt:rating - numDislikes
		$this->dislikes = (int) $yt->rating->attributes()->numDislikes;
		
		//rating - yt:rating - numLikes
		$this->likes = (int) $yt->rating->attributes()->numLikes;
		
		//video id
		$this->videoId = (string) $media_yt->videoid;
		
		//duration
		$this->duration = (int) $media_yt->duration->attributes()->seconds;
		
		//uploaded
		$this->uploaded = (string) $media_yt->uploaded;
		
		//statistics - viewCount
		$this->viewCount = (int) $yt->statistics->attributes()->viewCount;
		
		//statistics - favoriteCount
		$this->favoriteCount = (int) $yt->statistics->attributes()->favoriteCount;
	}
	
}