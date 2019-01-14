<?php
  require_once('/var/www/html/server/DBinfo.php');
  $thumbPath = '/thumbs/';
  $imgposlist = [
    ['center top', 'imgPosCtrTop'],
    ['center center', 'imgPosCtrCtr'],
    ['center bottom', 'imgPosCtrBtm'],
    ['right top', 'imgPosRgtTop'],
    ['right center', 'imgPosRgtCtr'],
    ['right bottom', 'imgPosRgtBtm'],
    ['left top', 'imgPosLftTop'],
    ['left center', 'imgPosLftCtr'],
    ['left bottom', 'imgPosLftBtm']
  ];
  $output = '';
  $key = (int)filter_input(INPUT_GET, 'key', FILTER_SANITIZE_NUMBER_INT);
  try{
    $pdo = new PDO(DBinfo::DSN,DBinfo::USER,DBinfo::PASSWORD);
    $pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql1 = 'SELECT * FROM imglist.category';
    $stmt = $pdo -> query($sql1);
    while ($row1 = $stmt -> fetch(PDO::FETCH_ASSOC)) {
      $categorylist[] = array (
        $row1['category_id'],
        $row1['name'],
      );
    }

    $sql2 = 'SELECT * FROM imglist.image_for_guest WHERE index_no=:key';
    $stmt = $pdo -> prepare($sql2);
    $stmt -> bindValue(':key', $key, PDO::PARAM_INT);
    $stmt -> execute();
    if($result = $stmt -> fetch(PDO::FETCH_ASSOC)){
      $imgfilepath = $thumbPath . $result['name'];
    }
    else {
      $output = 'No data found<br>';
    }
  }

  catch(PDOException $e){
    header('Content-Type: text/html; charset=UTF-8');
    $message = $e -> getMessage();
    echo "$message<br>";
    echo "<a href='imgmanager.php'>Back</a>";
    exit;
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <meta http-equiv="x-dns-prefetch-control" content="on">
  <title>Image Infomation</title>
  <link rel="preconnect" href="//ns-1441.meowwow.name">
  <link rel="preconnect" href="//use.fontawesome.com">
  <link rel="preconnect" href="//ajax.googleapis.com">
  <link rel="dns-prefetch" href="//ns-1441.meowwow.name">
  <link rel="dns-prefetch" href="//use.fontawesome.com">
  <link rel="dns-prefetch" href="//ajax.googleapis.com">
  <link rel="preload" href="/perfect-scrollbar/css/perfect-scrollbar.css" as="style">
  <link rel="preload" href="/css/imgupload.css" as="style">
  <link rel="preload" href="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js" crossorigin="anonymous" as="script">
  <link rel="preload" href="/perfect-scrollbar/dist/perfect-scrollbar.min.js" as="script">
  <link rel="preload" href="/scripts/imgupload.js" as="script">
  <link rel="alternate" href="https://ns-1441.meowwow.name/ja/" hreflang="ja">
  <link rel="alternate" href="https://ns-1441.meowwow.name/en/" hreflang="en">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
  <link rel="stylesheet" href="/jqueryui/jquery-ui.min.css">
  <link rel="stylesheet" href="/jqueryui/jquery-ui.structure.min.css">
  <link rel="stylesheet" href="/jqueryui/jquery-ui.theme.min.css">
  <link rel="stylesheet" href="/perfect-scrollbar/css/perfect-scrollbar.css">
  <link rel="stylesheet" href="/css/imgupload.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js" crossorigin="anonymous"></script>
  <script src="/jqueryui/jquery-ui.min.js"></script>
  <script src="/perfect-scrollbar/dist/perfect-scrollbar.min.js"></script>
  <script src="/scripts/language.js"></script>
  <script src="/scripts/tooltip.js"></script>
  <script src="/scripts/imgupload.js"></script>
</head>

<body>
  <div id="wrapper">
    <header>
      <h1 id="logo"><a href="/en/index.html">Gallery</a></h1>
      <nav>
        <div id="global-menu">
          <p><a href="/en/about.html">About</a></p>
          <div><label class="acdn-menu" for="acdn-01">Gallery</label>
            <input type="checkbox" id="acdn-01">
            <div class="acdn-item">
              <p><a href="/en/gallery1.php">Illustration Gallery</a></p>
              <p><a href="/en/gallery2.html">Website Portfolio</a></p>
            </div>
          </div>
          <div><label class="acdn-menu" for="acdn-02">Applications</label>
            <input type="checkbox" id="acdn-02">
            <div class="acdn-item">
              <p><a href="/en/imgupload_guest/img_upload.html">Image Uploader</a></p>
              <p><a href="/en/imgupload_guest/imgmanager.php">Image Viewer</a></p>
            </div>
          </div>
          <p><a href="/blog/">Blog</a></p>
          <p><a href="/en/contact.html">Contact</a></p>
        </div>
      </nav>
    </header>

    <main>

      <section id="main-container">
        <h2>Image Information</h2>
        <?php if(!empty($output)): ?>
          <?= $output ?>
        <?php endif; ?>
        <div class="enable-scroll">
          <form id="modify" action="info_process.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="MAX_FILE_SIZE" value="2000000">
            <input type="hidden" name="index" value="<?= $key ?>">
            <input type="hidden" name="orgFile" value="<?= $result['name'] ?>">
            <input type="hidden" name="orgAlt" value="<?= $result['description'] ?>">
            <input type="hidden" name="orgCat" value="<?= $result['category'] ?>">
            <input type="hidden" name="orgPos" value="<?= $result['img_pos'] ?>">
            <p id="up-file-name"><i class="fas fa-angle-double-right"></i>&nbsp;<?= $result['name'] ?></p>
            <img id="up-image" src="<?= $imgfilepath ?>" alt="<?= $result['description'] ?>">
            <label for="upFile" class="label">Change Image File: Click to select file</label>
            <input class="input" type="file" name="upFile" id="upFile" accept="image/png, image/jpeg, image/gif">
            <label for="description" class="label">Description:</label>
            <input class="input" type="text" name="alt" id="description" value="<?= $result['description'] ?>">
            <label for="category" class="label">Category:</label>
            <select class="input" name="category" id="category" required>
              <?php for($i = 0, $size = count($categorylist); $i < $size; $i++): ?>
                <?php $selected = (int)$categorylist[$i][0] === (int)$result['category'] ? 'selected' : ''; ?>
                <option value="<?= $categorylist[$i][0]; ?>" <?= $selected; ?>>
                <?= $categorylist[$i][1]; ?>
                  </option>
              <?php endfor; ?>
            </select>
            <label for="imgpos" class="label toolhover">Thumbnail position:</label>
            <select class="input toolhover" id="imgpos" name="imgpos">
              <?php for($i = 0, $size = count($imgposlist); $i < $size; $i++): ?>
                  <?php $selected = $imgposlist[$i][1] === $result['img_pos'] ? 'selected' : ''; ?>
                  <option value="<?= $imgposlist[$i][1]; ?>" <?= $selected; ?>>
                  <?= $imgposlist[$i][0]; ?>
                  </option>
              <?php endfor; ?>
            </select>
            <div class="tooltip"><img src="/css/css_img/tooltip.jpg"></div>
            <input id="sbmtbtn" type="button" value="Modify data">
            <input type="reset" value="Reset">
          </form>
          <section id="links">
            <a href="imgmanager.php">Back to Image List Viewer</a>
          </section>
        </div>
        <div id="confirm">
          <fieldset id="cdialog" form="modify">
            <h1>Modify confirmation</h1>
            <p>Are you sure to modify this data?</p>
            <input type="submit" id="yes" value="Yes">
            <input type="button" id="no" value="No">
          </fieldset>
        </div>
      </section>

  </main>

  <footer>
    <p>Language:</p>
    <select class="language" name="language">
      <option value="ja">日本語</option>
      <option value="en" selected>English</option>
    </select>
    <p id="copyright">&copy; 2018 meowwow.name. All rights reserved.</p>
  </footer>

  </div>
</body>
</html>
