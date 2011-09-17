<?php
/**
 * YouTube PHP library
 * 
 * @author Alexandros D
 * @copyright Alexandros D 2011
 * @license GNU/GPL v2
 * @since 0.9.0
 *
 * @todo Add phpDoc Info
 * @todo Implement getInfo, getPlaylists, getUploads
 * @todo Implement getSubscriptions
 * 
 */
 
 require_once 'youtube.php';
 require_once 'youtube.videolist.php';
 require_once 'youtube.playlist.php';

class YouTubeUser {
    
    private $_loaded;
    
    private $_username;
    private $_channelTitle;
    private $_gender;
    private $_age;
    private $_location;
    
    private $_youtube;
    
    private $_playlists;
    private $_playlists_maxResults;
    private $_playlists_startIndex;
    
    private $_uploads;
    private $_uploads_maxResults;
    private $_uploads_startIndex;
    
    private $_subscriptions;
    private $_subscriptions_maxResults;
    private $_subscriptions_startIndex;
    
    private $_favorites;
    private $_favorites_maxResults;
    private $_favorites_startIndex;
    
    private $_developerKey;
    
    public function __construct ( $username , $developerKey = NULL ) {
        $this->_username = $username;
        $this->_developerKey = $developerKey;
        $this->_youtube = new YouTube( $this->_developerKey );
    }
    
    /**
    * Load user's details
	*
	* @return Array
	*/
    public function getInfo() {
        if ( !$this->_loaded )
            $this->_loadInfo();
        
        $info = array (
                "username" => $this->_username,
                "channelTitle" => $this->_channelTitle,
                "gender" => $this->_gender,
                "age" => $this->_age,
                "location" => $this->_location
            );
        
        return $info;
    }
    
    public function getPlaylists( $maxResults = 0 , $startIndex = 0 ) {
        if ( !$this->_playlists_maxResults == $maxResults || !$this->_playlists_startIndex == $startIndex || !$this->_playlists )
            $this->_loadPlaylists( $maxResults , $startIndex);
        
        return $this->_playlists; 
    }
    
    public function getUploads( $maxResults = 0 , $startIndex = 0 ) {
        if ( !$this->_uploads_maxResults == $maxResults || !$this->_uploads_startIndex == $startIndex || !$this->_uploads )
            $this->_loadUploads( $maxResults , $startIndex);
        
        return $this->_uploads;
    }
    
    public function getFavorites( $maxResults = 0 , $startIndex = 0 ) {
        if ( !$this->_favorites_maxResults == $maxResults || !$this->_favorites_startIndex == $startIndex || !$this->_favorites )
            $this->_favorites( $maxResults , $startIndex);
        
        return $this->_favorites;
    }
    
    public function getSubscriptions( $maxResults = 0 , $startIndex = 0 ) {
        //TODO - Phase II
        return FALSE;
    }
    
    /*****************************************************************
     *********** PRIVATE FUNCTIONS
     ****************************************************************/
    
    private function _loadInfo() {
        $data = $this->_youtube->getUserData( $this->_username );
        
        $xml = new SimpleXMLElement( $data );
        
        $namespaces = $xml->getNameSpaces(true);
    	$yt = $xml->children($namespaces['yt']);
        
        $this->_channelTitle = (string) $xml->title;
        $this->_age = (string) $yt->age;
        $this->_gender = (string) $yt->gender;
        $this->_location = (string) $yt->location;
        
        $this->_loaded = TRUE;
    }
    
    private function _loadPlaylists( $maxResults = 0 , $startIndex = 0 ) {
    	$this->_playlists_maxResults = $maxResults;
    	$this->_playlists_startIndex = $startIndex;
    	
        $data = $this->_youtube->getPlaylistsByUser( $this->_username , $maxResults , $startIndex );
        
        $ids = array();
        $xml = new SimpleXMLElement( $data );

        foreach ($xml->entry as $playlist) {
        	$namespaces = $playlist->getNamespaces(true);
        	$yt = $playlist->children($namespaces['yt']);
        	$ids[] = $yt->playlistId;
        }
        
        $playlists = array();
        foreach ($ids as $id) {
        	$playlists[] = new YouTubePlaylist($id , $this->_developerKey);
        }
        
        $this->_playlists = $playlists;
    }
    
    private function _loadUploads( $maxResults = 0 , $startIndex = 0 ) {
    	$this->_uploads_maxResults = $maxResults;
    	$this->_uploads_startIndex = $startIndex;
    	
        $uploads = new YouTubeVideoList( $this->_developerKey );
        $uploads->load( "UploadsByUser" , $this->_username , $maxResults , $startIndex );
        
        $this->_uploads = $uploads;
    }
    
    private function _loadFavorites( $maxResults = 0 , $startIndex = 0 ) {
        $this->_favorites_maxResults = $maxResults;
    	$this->_favorites_startIndex = $startIndex;
    	
        $favorites = new YouTubeVideoList( $this->_developerKey );
        $favorites->load( "FavoritesByUser" , $this->_username , $maxResults , $startIndex );
        
        $this->_favorites = $favorites;
    }
    
    private function _loadSubscriptions( $maxResults = 0 , $startIndex = 0 ) {
        //TODO - Phase II
        return FALSE;
    }
}