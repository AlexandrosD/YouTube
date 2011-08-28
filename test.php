<?php

require_once 'youtube.playlist.php';

//$playlist = new YouTubePlaylist("E0B60191A5F3786A");
$playlist = new YouTubePlaylist("C5768E547E4661D3");
$playlist->loadPlaylist(52);

echo "<h1>" . $playlist->getTitle() ."</h1>";
echo "<h2>" . $playlist->getSubtitle() . "</h2>";

echo "<h1>Generating videolist:</h1>";
foreach ($playlist->getVideos() as $v) {
	echo "<h2>" . $v->title . "</h2>";
	print_r($v);
}

