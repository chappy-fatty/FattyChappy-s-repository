<?php
  require_once(__DIR__ . '/img_resize.php');
  require_once(__DIR__ . '/modifyDB.php');

  $index = (int)filter_input(INPUT_POST, 'index', FILTER_SANITIZE_NUMBER_INT);
  $alt = (string)filter_input(INPUT_POST, 'alt', FILTER_SANITIZE_STRING);
  $category = (int)filter_input(INPUT_POST, 'category', FILTER_SANITIZE_NUMBER_INT);
  $imgpos = (string)filter_input(INPUT_POST, 'imgpos', FILTER_SANITIZE_STRING);
  $orgFile = (string)filter_input(INPUT_POST, 'orgFile', FILTER_SANITIZE_STRING);
  $orgAlt = (string)filter_input(INPUT_POST, 'orgAlt', FILTER_SANITIZE_STRING);
  $orgCat = (int)filter_input(INPUT_POST, 'orgCat', FILTER_SANITIZE_NUMBER_INT);
  $orgPos = (string)filter_input(INPUT_POST, 'orgPos', FILTER_SANITIZE_STRING);
  $message = '';

  $path = [
    '/var/www/html/imgupload_guest/thumbs/',
    '/var/www/html/imgupload_guest/uploaded/'
  ];

  $file = (empty($_FILES['upFile']['name'])) ? basename($orgFile) : basename($_FILES['upFile']['name']);
  $err = $_FILES['upFile']['error'];
  $size = (empty($_FILES['upFile']['size'])) ? '' : $_FILES['upFile']['size'];
  $tmp = (empty($_FILES['upFile']['tmp_name'])) ? '' : $_FILES['upFile']['tmp_name'];

  if($file !== $orgFile){
    try{
      // define image file types
      $pictureFile = array(
        'jpg' => 'image/jpeg',
        'png' => 'image/png',
        'gif' => 'image/gif'
      );

      // verify uploading file by name
      if(!preg_match('/\A(?!\.)[\w.-]++(?<!\.)(?<!\.php)(?<!\.cgi)(?<!\.py)(?<!\.rb)\z/i', $file)){
        throw new RuntimeException('Invalid file.');
      }

      // set file's path for move the uploaded image directory
      $upFullPath = $path[1] . $file;

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
      chmod($upFullPath, 0664);
      for($i = 0, $arrsize = count($path); $i < $arrsize; $i++){
        if(file_exists($path[$i] . $orgFile)){
          unlink($path[$i] . $orgFile);
        }
      }
      ImageResize($upFullPath);
      modDB($index, $file, $alt, $category, $imgpos);
      $message = 'Upload succeeded.';
    }

    catch(RuntimeException $e){
      header('Content-Type: text/html; charset=UTF-8', true, 500);
      $message = $e -> getMessage();
      echo "$message<br>";
      echo "<a href='javascript:history.back()'>Back</a>";
      exit;
    }
  }

if($orgAlt !== $alt || $orgCat !== $category || $orgPos !== $imgpos){
  modDB($index, $file, $alt, $category, $imgpos);
}

header('Content-Type: text/html; charset=UTF-8');
echo "Index : $index<br>";
echo "File Name : $file<br>";
echo "Description : $alt<br>";
echo "Category : $category<br>";
echo "Image Position : $imgpos<br>";
echo "Result: $message<br>";
echo '<a href="img_upload.html">Image Uploader</a><br>';
echo '<a href="imgmanager.php">Image List Viewer</a>';
