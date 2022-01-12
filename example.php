<?

include "Util.php";
include "SimpleImage.php";
include "State.php";

define('functionName', 'rgbColorDistance');

$example = $_REQUEST['example'];

switch ($example) {

  case "1" :
    // merge two images together
    // get a list of images from a subdirectory
    $imagePath = "./img/3/";
    $files = listFilesInDirectory($imagePath);

    // setup an image to work with
    $baseImage = new SimpleImage($imagePath . $files[0]);

    // setup an array of all other images
    $images = array();
    for ($i = 1; $i < count($files); $i++)
      $images[] = new SimpleImage($imagePath . $files[$i]);

    // merge the latter images into the first image.
    $baseImage->merge($images);

    // stream image to client
    $baseImage->output();
    break;

  case "2" :
// detect motion or change
    // setup two images to work with
    $i1 = new SimpleImage("./img/4/IMG_0392.JPG");
    $i2 = new SimpleImage("./img/4/IMG_0393.JPG");

    // setup the Fuzzy device that layers all the states into one array
    $state = new State(15, 8, $i1);
    $state = $state->difference(new State(15, 8, $i2), functionName);
    $state->abs()->denoiseStdDev()->scale(10)->round(0);

    // for purposes of visual debugging, merge the two images together
    $i1->merge($i2);

    // using the merged image, layer on a visual of state differences
    $result = $state->drawImageIndicator($i1);

    // $box will hold an array (x,y,w,h) that indicates location of change
    $box = $state->getBoundingBox($i1->getWidth(), $i1->getHeight());
    $color = imagecolorallocate($result->getImage(), 10, 255, 10);
    imagerectangle($result->getImage(), $box["x"] - 1, $box["y"] - 1,
      $box["x"] + $box["w"] + 1, $box["y"] + $box["h"],
      $color);

    // $cog will hold an array (x,y) indicating center of change
    $cog = $state->getCenterOfGravity($i1->getWidth(), $i1->getHeight());
    imagearc($result->getImage(),
      $cog["x"], $cog["y"], 7, 7, 0, 360,
      imagecolorallocate($result->getImage(), 255, 255, 0));
    imagearc($result->getImage(),
      $cog["x"], $cog["y"], 9, 9, 0, 360,
      imagecolorallocate($result->getImage(), 255, 0, 0));

    // stream image to client
    $result->output();
    break;

  case "3" :

    // setup two images to work with
    $i1 = new SimpleImage("./img/4/IMG_0269.JPG");
    $i2 = new SimpleImage("./img/4/IMG_0270.JPG");
//    $i2 = file_get_contents('http://192.168.1.10:81');
//    $imageURL = file_get_contents('http://192.168.0.82/uploads/camera1.jpeg');
    $imageSource = './img/4/IMG_0270.JPG';
    $imagePath = './img/4/teste.jpg';

    $i2 = new SimpleImage($imageSource);

    $state = new State(15, 8, $i1);
    $state = $state->difference(new State(15, 8, $i2), functionName);
    $state->abs()->denoiseStdDev()->scale(10)->round(0);

// $box will hold an array (x,y,w,h) that indicates location of change
    $box = $state->getBoundingBox($i1->getWidth(), $i1->getHeight());

    if ($box) {

      echo 'Some difference';

//      file_put_contents($imagePath, $imageSource);
    } else {
      echo 'Image equals.';
    }

    break;

  default:
    die('Put a number to display example.');

}






