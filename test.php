<?php

require_once 'youtube.php';

$youtube = new YouTube();

echo $youtube->getPlaylist("5332434504531E00");

