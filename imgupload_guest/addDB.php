<?php
function addImageDB($file, $alt, $categoryNo, $imgpos){
  try{
    require_once('DBinfo.php');
    $pdo = new PDO(DBinfo::DSN, DBinfo::USER, DBinfo::PASSWORD);
    $pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo -> setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

    // add this file's data into database 'image'
    $sql = 'INSERT INTO imglist.image_for_guest(name,description,category,img_pos) VALUES(:name,:alt,:category,:imgpos) ON DUPLICATE KEY UPDATE index_no = index_no+1';
    $statement = $pdo -> prepare($sql);
    $statement -> bindValue(':name', $file, PDO::PARAM_STR);
    $statement -> bindValue(':alt', $alt, PDO::PARAM_STR);
    $statement -> bindValue(':category', $categoryNo, PDO::PARAM_INT);
    $statement -> bindValue(':imgpos', $imgpos, PDO::PARAM_STR);
    $statement -> execute();
  }

  catch(PDOException $e){
    header('Content-Type: text/html; charset=UTF-8');
    $message = $e -> getMessage();
    echo "$message<br>";
    echo "<a href='img_upload.html'>Back</a>";
    exit;
  }
}
