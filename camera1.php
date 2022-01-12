<?php

include "Util.php";
include "SimpleImage.php";
include "State.php";

define('functionName', 'rgbColorDistance');

// get a list of images from a subdirectory
$imageLast = "./img/1/img_2022-01-12-17-54-01.jpg";
$imagePath = "./img/1/";
$files = listFilesInDirectory($imagePath);
if (count($files) > 0) {
  $arrayKeys = array_keys($files);
  asort($files);
  $files = array_combine($arrayKeys, $files);
  $imageLast = $imagePath . $files[count($files) - 1];
}
//$imageLast = "./img/1/img_2022-01-12-17-54-01.jpg";
// setup two images to work with

$i1 = new SimpleImage($imageLast);

//$imageSource = file_get_contents('http://192.168.1.10:81');
//$imageSource = './img/1/img_2022-01-12-17-54-01.jpg';
$imageSource = file_get_contents('http://192.168.0.82/uploads/camera1.jpeg');

$filename = $imageLast . 'img_' . date('Y-m-d-H-i-s') . '.jpg';

file_put_contents('/tmp/camera1.jpg', $imageSource);

//$handle = writePHPMemory($imageSource);
//$image = readPHPMemory($handle);

$i2 = new SimpleImage('/tmp/camera1.jpg');

$state = new State(15, 8, $i1);
$state = $state->difference(new State(15, 8, $i2), functionName);
$state->abs()->denoiseStdDev()->scale(10)->round(0);

// $box will hold an array (x,y,w,h) that indicates location of change
$box = $state->getBoundingBox($i1->getWidth(), $i1->getHeight());

if ($box) {

  echo 'Some difference';

  file_put_contents($filename, $imageSource);
  chmod($filename, 0755);

} else {
  echo 'Image equals.';
}

