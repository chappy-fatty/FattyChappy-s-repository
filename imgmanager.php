<?php
  $category = '';
  $imgpos = '';
  try{
    //get DB infomation then login DB
    require_once(__DIR__ . '/DBinfo.php');
    $pdo = new PDO(DBinfo::DSN,DBinfo::USER,DBinfo::PASSWORD);
    $pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //fetch whole imagelist from image table
    $thumbPath = 'thumbs/';
    $imgPath = 'uploaded/';
    $sql = 'SELECT * FROM imglist.image_for_guest';
    $statement = $pdo -> query($sql);
    while($result = $statement -> fetch(PDO::FETCH_ASSOC)){
      switch($result['category']){
        case 0:
          $category = 'Cats';
          break;
        case 1:
          $category = 'Figures';
          break;
        case 2:
          $category = 'Landscapes';
          break;
        default:
          $category = 'Miscellaneous';
      }

      switch($result['img_pos']){
        case 'imgPosCtrTop':
          $imgpos = 'center top';
          break;
        case 'imgPosCtrBtm':
          $imgpos = 'center bottom';
          break;
        case 'imgPosRgtTop':
          $imgpos = 'right top';
          break;
        case 'imgPosRgtCtr':
          $imgpos = 'right center';
          break;
        case 'imgPosRgtBtm':
          $imgpos = 'right bottom';
          break;
        case 'imgPosLftTop':
          $imgpos = 'left top';
          break;
        case 'imgPosLftCtr':
          $imgpos = 'left center';
          break;
        case 'imgPosLftBtm':
          $imgpos = 'left bottom';
          break;
        default:
          $imgpos = 'center center';
      }

      $imgslist[] = array(
        $result['index_no'],
        $result['name'],
        $result['description'],
        $category,
        $imgpos,
      );
    }
  }

  catch(PDOException $e){
    header('Content-Type: text/html; charset=UTF-8', true, 500);
    $message = $e -> getMessage();
    echo "$message<br>";
    echo "<a href='imgmanager.php'>Back</a>";
    exit;
  }

  $totalpage = ceil(count($imgslist) / 10);
  $page = empty($_GET['page']) ? 1 : (int)filter_input(INPUT_GET, 'page');
  $firstidx = ($page - 1) * 10;
  $lastidx = $firstidx + 9;
  if($lastidx >= count($imgslist)){
    $lastidx = count($imgslist) - 1;
  }

?>

<!DOCTYPE html>
  <html lang="ja">
  <head>
    <meta charset="utf-8">
    <title>Image List Viewer</title>
    <link rel="preconnect" href="//ns-1441.meowwow.name">
    <link rel="preconnect" href="//use.fontawesome.com">
    <link rel="preconnect" href="//ajax.googleapis.com">
    <link rel="preconnect" href="//unpkg.com">
    <link rel="dns-prefetch" href="//ns-1441.meowwow.name">
    <link rel="dns-prefetch" href="//use.fontawesome.com">
    <link rel="dns-prefetch" href="//ajax.googleapis.com">
    <link rel="dns-prefetch" href="//unpkg.com">
    <link rel="preload" href="/css/imagemanager.css" as="style">
    <link rel="preload" href="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js" crossorigin="anonymous" as="script">
    <link rel="preload" href="/node_modules/lazyload/lazyload.min.js" as="script">
    <link rel="preload" href="https://unpkg.com/imagesloaded@4/imagesloaded.pkgd.min.js" as="script">
    <link rel="preload" href="/perfect-scrollbar/dist/perfect-scrollbar.min.js" as="script">
    <link rel="preload" href="/scripts/picturefill.min.js" as="script">
    <link rel="preload" href="/scripts/imgmanager2.js" as="script">
    <link rel="stylesheet" href="/perfect-scrollbar/css/perfect-scrollbar.css">
    <link rel="stylesheet" href="/css/imagemanager.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js" crossorigin="anonymous"></script>
    <script src="/node_modules/lazyload/lazyload.min.js"></script>
    <script src="https://unpkg.com/imagesloaded@4/imagesloaded.pkgd.min.js"></script>
    <script src="/perfect-scrollbar/dist/perfect-scrollbar.min.js"></script>
    <script src="/scripts/picturefill.min.js"></script>
    <script src="/scripts/imgmanager2.js"></script>
  </head>
  <body>
    <div id="wrapper">
      <header>
        <h1 id="logo"><a href="/index.html">Gallery</a></h1>
        <nav>
          <div id="global-menu">
            <p><a href="/about.html">About</a></p>
            <div><label class="acdn-menu" for="acdn-01">Gallery</label>
              <input type="checkbox" id="acdn-01">
              <div class="acdn-item">
                <p><a href="/gallery1.php">Gallery 1</a></p>
                <p><a href="/gallery2.php">Gallery 2</a></p>
              </div>
            </div>
            <div><label class="acdn-menu" for="acdn-02">Applications</label>
              <input type="checkbox" id="acdn-02">
              <div class="acdn-item">
                <p><a href="/imgupload_guest/img_upload.html">Image Uploader</a></p>
                <p><a href="/imgupload_guest/imgmanager.php">Image List Viewer</a></p>
              </div>
            </div>
            <p><a href="#">Blog</a></p>
            <p><a href="#">Contact</a></p>
          </div>
        </nav>
      </header>

      <main>
      <div id="main-container">
        <h1>Image List Viewer</h1>
        <form method="get" id="page-selector">
          <label for="page">Page:</label>
          <select id="page" name="page">
            <?php for($i = 1; $i <= $totalpage; $i++): ?>
              <?php if($i === $page): ?>
                <option selected><?= $i; ?></option>
              <?php else: ?>
                <option><?= $i; ?></option>
              <?php endif; ?>
            <?php endfor; ?>
          </select>
          <p>&nbsp;/&nbsp;<?= $totalpage ?></p>
          <input type="submit" value="Move">
        </form>
        <div id="table-container">
          <table>
            <thead>
              <tr>
                <th>No.</th>
                <th>Image Name</th>
                <th>Description</th>
                <th>Category</th>
                <th>Image Position</th>
              </tr>
            </thead>
            <tbody>
              <?php for($i = $firstidx; $i <= $lastidx; $i++): ?>
                  <tr><td><?= $imgslist[$i][0]; ?></td>
                  <td class="img-name">
                  <img src="<?= $thumbPath . $imgslist[$i][1]; ?>" alt="<?= $imgslist[$i][2]; ?>"><br>
                  <?= $imgslist[$i][1]; ?><br>
                  <a href="imginfo.php?key=<?= $imgslist[$i][0]; ?>"><button>Info</button></a>
                  <button class="delbtn" data-key="<?= $imgslist[$i][0]; ?>">Delete</button>
                  </td>
                  <td class="desc"><?= $imgslist[$i][2]; ?></td>
                  <td><?= $imgslist[$i][3]; ?></td>
                  <td><?= $imgslist[$i][4]; ?></td></tr>
              <?php endfor; ?>
            </tbody>
          </table>
        </div>
        <div id="gallery-main">
          <p id="closeButton"><i class="fas fa-times"></i></p>

          <div id="main-image-container">

              <img src="#" srcset="#" alt="#" id="show-img">

          </div>
        </div>
        <div id="links">
          <h2><a href="img_upload.html">Image Uploader</a></h2>
        </div>
        <div id="confirm">
          <div id="cdialog">
            <h1>Delete confirmation</h1>
            <p>Are you sure to delete this data?</p>
            <a href="#"><button id="yes">Yes</button></a>
            <button id="no">No</button>
          </div>
        </div>
      </main>

      <footer>
        <p id="copyright">&copy; 2018 meowwow.name</p>
      </footer>
      </div>
    </div>
  </body>
  </html>
