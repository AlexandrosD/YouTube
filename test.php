<?php

require_once 'youtube.php';

$youtube = new YouTube();

echo $youtube->getPlaylistsByUser("live");

