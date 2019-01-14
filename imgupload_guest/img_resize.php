<?php
  ini_set('memory_limit', '256M');
  header('Content-Type: text/html; charset=UTF-8');

  function ImageResize($file){
    try {
      $filename = basename($file);
      $filepath = [
        'sfile' => '/var/www/html/smallimages/' . $filename,
        'mfile' => '/var/www/html/images/' . $filename,
        'lfile' => '/var/www/html/largeimages/' . $filename,
        'xlfile' => '/var/www/html/xlargeimages/' . $filename
      ];

      $pictureFile = [
          'jpg' => 'image/jpeg',
          'png' => 'image/png',
          'gif' => 'image/gif'
      ];

      $simg = new \Imagick(realpath($file));
      $format = $simg -> getImageFormat();
      switch ($format) {
        case 'JPEG':
          $formatmime = 'image/jpeg';
          break;
        case 'PNG':
          $formatmime = 'image/png';
          break;
        case 'GIF':
          $formatmime = 'image/gif';
          break;

        default:
          throw new Exception('Invalid file format.');
          break;
      }
      $width = $simg -> getImageWidth();
      $height = $simg -> getImageHeight();
      $quality = $simg -> getImageCompressionQuality();
      if($quarity > 85){
        $quality = 85;
      }
      $simg -> setImageFormat($format);
      $simg -> setImageCompressionQuality($quality);
      $mimg = clone $simg;
      $limg = clone $simg;
      $xlimg = clone $simg;

      $simg -> resizeImage(300, 300, Imagick::FILTER_LANCZOS, 1, true);
      $simg -> writeimage($filepath['sfile']);
      $simg -> clear();

      if($width > 768 || $height > 768){
        $mimg -> resizeImage(768, 768, Imagick::FILTER_LANCZOS, 1, true);
      }
      $mimg -> writeimage($filepath['mfile']);
      $mimg -> clear();

      if($width > 1024 || $height > 1024){
        $limg -> resizeImage(1024, 1024, Imagick::FILTER_LANCZOS, 1, true);
      }
      $limg -> writeimage($filepath['lfile']);
      $limg -> clear();

      if($width > 1400 || $height > 1400){
        $xlimg -> resizeImage(1400, 1400, Imagick::FILTER_LANCZOS, 1, true);
      }
      $xlimg -> writeimage($filepath['xlfile']);
      $xlimg -> clear();

      foreach ($filepath as $key => $value){
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $fileMime = $finfo -> file($value);
        // whether the uploading file type is correct image file
        if(!array_search($fileMime, $pictureFile) || $formatmime !== $fileMime){
          throw new Exception('Invalid file format.');
        }
        chmod($value, 0664);
      }
    }

    catch(ImagickException $e) {
      throw new Exception($e -> getMessage() . 'File: ' . basename(__FILE__) . ' Line: ' . __LINE__);
    }
  }
