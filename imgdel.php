<?php
    header('Content-Type: text/html; charset=UTF-8');
  try {
    require_once(__DIR__ . '/DBinfo.php');

    //set image file paths
    $path = [
      '/var/www/html/imgupload_guest/uploaded/',
      '/var/www/html/imgupload_guest/thumbs/',
    ];

    $key = (int)filter_input(INPUT_GET, 'key');
    $idxmax = 0;
    $filetodelete = '';

    $pdo = new PDO(DBinfo::DSN,DBinfo::USER,DBinfo::PASSWORD);
    $pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo -> setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

    // transaction
    $pdo -> beginTransaction();
    // fetch file name from DB
    $sql = "SELECT `name` FROM imglist.image_for_guest WHERE index_no=?";
    $stmt = $pdo -> prepare($sql);
    $stmt -> bindValue(1, $key, PDO::PARAM_INT);
    // check if the target files exist
    $stmt -> execute();
    if($result = $stmt -> fetch(PDO::FETCH_ASSOC)){
      $filetodelete = $result['name'];
      for($i = 0, $arrsize = count($path); $i < $arrsize; $i++){
        if(!file_exists($path[$i] . $filetodelete)){
          echo "The file {$path[$i]}{$filetodelete} does not exist.<br>";
        }
      }
    }
    else{
      throw new Exception("Cannot get the file data.");
    }
    // delete DB item
    $sql2 = 'DELETE FROM imglist.image_for_guest WHERE index_no=?';
    $stmt = $pdo -> prepare($sql2);
    $stmt -> bindValue(1, $key, PDO::PARAM_INT);
    $stmt -> execute();

    $sql3 = "
      SET SQL_SAFE_UPDATES = 0;
      SET @i = 0;
      UPDATE imglist.image SET index_no = (@i := @i +1);
    ";
    $count = $pdo -> exec($sql3);

    $sql4 = 'SELECT MAX(index_no) FROM imglist.image_for_guest';
    $stmt = $pdo -> query($sql4);
    if($result = $stmt -> fetch(PDO::FETCH_NUM)){
      $idxmax = (int)filter_var(($result[0] + 1), FILTER_SANITIZE_NUMBER_INT);
    }

    $sql5 = "ALTER TABLE imglist.image_for_guest AUTO_INCREMENT=$idxmax";
    $pdo -> exec($sql5);

    for($i = 0, $arrsize = count($path); $i < $arrsize; $i++){
      if(file_exists($path[$i] . $filetodelete) && is_writable($path[$i] . $filetodelete)){
        unlink($path[$i] . $filetodelete);
      }
      else if(file_exists($path[$i] . $filetodelete) && !is_writable($path[$i] . $filetodelete)){
        throw new Exception("The file {$path[$i]}{$filetodelete} cannot delete.");
      }
      else {
        echo "The file {$path[$i]}{$filetodelete} does not exist.<br>";
      }
    }

    echo "Succeeded deleting all of seleted images and database fields.";
    echo "<br>";
    echo "<a href='imgmanager.php'>Back</a>";
  }

  catch(Exception $e){
    if(isset($pdo) === true && $pdo ->inTransaction() === true){
      $pdo -> rollBack();
      echo "Database roll backed.<br>";
    }
    header('Content-Type: text/html; charset=UTF-8', true, 500);
    $message = $e -> getMessage();
    echo "$message<br>";
    echo "<a href='javascript:history.back()'>Back</a>";
    exit;
  }
