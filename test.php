<?php

require_once 'youtube.playlist.php';
require_once 'youtube.user.php';



//PLAYLIST TESTS
/*
$playlist = new YouTubePlaylist("C5768E547E4661D3");
$playlist->loadPlaylist(52);

echo "<h1>" . $playlist->getTitle() ."</h1>";
echo "<h2>" . $playlist->getSubtitle() . "</h2>";

echo "<h1>Generating videolist:</h1>";
$videos = $playlist->getVideos();

echo "getVideosCount() = " . $playlist->getVideosCount() . "<br /><br />";

foreach ($videos as $v) {
	echo "<h2>" . $v->title . "</h2>";
	print_r($v);
}

$videos[0]->comments->loadComments();
$c=$videos[0]->comments->getComments();
echo "<pre>First Comment of the first video is " . $c[0]->content . "</pre>";
*/

echo "<h1>USERS TESTS</h1>";

$user = new YouTubeUser( "youtube" );

$userinfo = $user->getInfo();
print_r ( $userinfo );

echo "<hr />";

$uploads = $user->getUploads();
echo "getVideosCount() = " . $uploads->getVideosCount() . "<br /><br />";
//print_r( $uploads );

foreach ($uploads as $v) {
	echo "<h2>" . $v->title . "</h2>";
	print_r($v);
}



