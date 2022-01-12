<?php

function listFilesInDirectory($path)
{
  $ia = array();
  $ih = @opendir($path) or die("Unable to open f $path");
  while ($img = readdir($ih)) {
    if (is_dir($path . $img) || $img == "." || $img == ".." || $img == "thumb" || $img == "Thumbs" || $img == "thumbs" || $img == "Thumbs.db") continue;
    $ia[] = $img;
  }
  closedir($ih);
  return $ia;
}

// Metodo d() -> Dump.
if (!function_exists('d')) {
  function d()
  {
    echo '<pre>';
    array_map(function ($x) {
      var_dump($x);
    }, func_get_args());
  }
}

// Metodo dd() -> Dump die.
if (!function_exists('dd')) {
  function dd()
  {
    echo '<pre>';
    array_map(function ($x) {
      var_dump($x);
    }, func_get_args());
    die;
  }
}

function writePHPMemory($file)
{

  $handle = fopen('php://temp', 'w+');

  fwrite($handle, $file);

//  rewind($handle); // resets the position of pointer
//
//  fread($handle, fstat($handle)['size']); // I am freaking awesome

  return $handle;
}

function readPHPMemory($handle)
{

  rewind($handle); // resets the position of pointer

  return fread($handle, fstat($handle)['size']);

}