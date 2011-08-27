<?php

require_once 'youtube.playlist.php';

$playlist = new YouTubePlaylist("E0B60191A5F3786A");

echo "<h1>Generating videolist:</h1>";
foreach ($playlist->getVideos() as $v) {
	echo "<h2>" . $v->title . "</h2>";
	print_r($v);
}

