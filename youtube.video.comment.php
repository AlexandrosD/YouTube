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

class YouTubeVideoComment {
	public $published;
	public $updated;
	public $title;
	public $content;
	public $author;
	
	public function __construct( $data ) {
		$this->_load ( $data );
	}
	
	private function _load( $data ) {
		$this->published 	= (string) $data->published;
		$this->updated 		= (string) $data->updated;
		$this->title 		= (string) $data->title;
		$this->content		= (string) $data->content;
		$this->author 		= (string) $data->author;
	}
	
}