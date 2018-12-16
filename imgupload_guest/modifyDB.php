<?php
  require_once('DBinfo.php');

  function modDB($index, $file, $alt, $category, $imgpos){
    try{
      $pdo = new PDO(DBinfo::DSN, DBinfo::USER, DBinfo::PASSWORD);
      $pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $pdo -> setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

      // add this file's data into database 'image'
      $sql = "UPDATE imglist.image_for_guest SET name=:name,description=:alt,category=:category,img_pos=:imgpos WHERE index_no=:idx";
      $statement = $pdo -> prepare($sql);
      $statement -> bindValue(':idx',$index, PDO::PARAM_INT);
      $statement -> bindValue(':name', $file, PDO::PARAM_STR);
      $statement -> bindValue(':alt', $alt, PDO::PARAM_STR);
      $statement -> bindValue(':category', $category, PDO::PARAM_INT);
      $statement -> bindValue(':imgpos', $imgpos, PDO::PARAM_STR);
      $statement -> execute();
    }

    catch(PDOException $e){
      header('Content-Type: text/html; charset=UTF-8', true, 500);
      $message = $e -> getMessage();
      echo "$message<br>";
      echo "<a href='javascript:history.back()'>Back</a>";
      exit;
    }
  }
