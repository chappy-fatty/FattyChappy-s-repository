<?php
  require_once(__DIR__ . '/img_resize.php');
  require_once(__DIR__ . '/addDB.php');

  // get parameters from the uploading form
  $alt = (string)filter_input(INPUT_POST, 'alt');
  $category = (int)filter_input(INPUT_POST, 'category');
  $imgpos = (string)filter_input(INPUT_POST, 'imgpos');

  $file = empty($_FILES['upFile']['name']) ? '' : basename($_FILES['upFile']['name']);
  $err = $_FILES['upFile']['error'];
  $size = empty($_FILES['upFile']['size']) ? '' : $_FILES['upFile']['size'];
  $tmp = empty($_FILES['upFile']['tmp_name']) ? '' : $_FILES['upFile']['tmp_name'];

  try{
    $folderPath = '/var/www/html/uploaded/';

    // define image file types
    $pictureFile = array(
      'jpg' => 'image/jpeg',
      'png' => 'image/png',
      'gif' => 'image/gif'
    );

    // verify uploading file by name
    if(!preg_match('/\A(?!\.)[\w.-]++(?<!\.)(?<!\.php)(?<!\.cgi)(?<!\.py)(?<!\.rb)\z/i', $file)){
      throw new RuntimeException('Invalid file format.');
    }

    // set file's path for move the extra large image directory
    $upFullPath = $folderPath . $file;

    // Undefined | Multiple Files | $_FILES Corruption Attack
    // If this request falls under any of them, treat it invalid.
    if(!isset($err) || is_array($err)){
      throw new RuntimeException('Invalid parameters.');
    }

    // read and validate image file types
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $fileMime = $finfo -> file($tmp);

    // Check $_FILES['upFile']['error']
    switch($err){
      case UPLOAD_ERR_OK:
      break;
      // whether a temporary file exists
      case UPLOAD_ERR_NO_FILE:
      throw new RuntimeException('No file sent.');
      case UPLOAD_ERR_INI_SIZE:
      case UPLOAD_ERR_FORM_SIZE:
      throw new RuntimeException('Exceeded file size limit.');
      default:
      throw new RuntimeException('Unknown errors.');
    }

    // whether the uploading file size is under 2MB
    if($size > 2000000){
      throw new RuntimeException('Exceeded file size limit.');
    }

    // whether the uploading file type is correct image file
    if(!array_search($fileMime, $pictureFile)){
      throw new RuntimeException('Invalid file format.');
    }

    // if temporary file didn't move to uploading directory, throw exception
    if(!move_uploaded_file($tmp, $upFullPath)){
      throw new RuntimeException('Failed to move uploaded file.');
    }

    // processes after succefully uploaded file
    unset($finfo);
    chmod($upFullPath, 0644);
    $message = 'Upload succeeded.';
    ImageResize($upFullPath);
    addImageDB($file, $alt, $category, $imgpos);
  }

  catch(RuntimeException $e){
    header('Content-Type: text/html; charset=UTF-8');
    $message = $e -> getMessage();
    echo "$message<br>";
    echo "<a href='img_upload.html'>Back</a>";
    exit;
  }

  header('Content-Type: text/html; charset=UTF-8');
  echo "File Name : $file<br>";
  echo "Result: $message<br>";
  echo '<a href="img_upload.html">Image Uploader</a><br>';
  echo '<a href="imgmanager.php">Image List Viewer</a>';
