<?php
  ini_set('memory_limit', '256M');
  header('Content-Type: text/plain; charset=UTF-8');

  function ImageResize($file){
    try {
      $filename = basename($file);
      $filepath = [
        'thumbfile' => '/var/www/html/imgupload_guest/thumbs/' . $filename,
        'midfile' => '/var/www/html/imgupload_guest/uploaded/' . $filename
      ];
      $quality = 85;
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
      $simg -> setImageFormat($format);
      $simg -> setImageCompressionQuality($quality);
      $midimg = clone $simg;

      $simg -> resizeImage(300, 300, Imagick::FILTER_LANCZOS, 1, true);
      $simg -> writeimage($filepath['thumbfile']);
      $simg -> clear();

      if($midimg -> getImageWidth() > 768 || $midimg -> getImageHeight() > 768){
        $midimg -> resizeImage(768, 768, Imagick::FILTER_LANCZOS, 1, true);
      }
      $midimg -> writeimage($filepath['midfile']);
      $midimg -> clear();

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
      throw new Exception($e->getMessage() . 'File: ' . basename(__FILE__) . ' Line: ' . __LINE__);
    }
  }
