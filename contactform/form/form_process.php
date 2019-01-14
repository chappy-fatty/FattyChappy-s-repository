<?php
  try {
    $headers = array(
      'From' => 'ctctabby@gmail.com',
      'Reply-To' =>'ctctabby@gmail.com',
      'X-Mailer' => 'PHP/' . phpversion()
    );

    $name = (string)filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $email = (string)filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $subject = (string)filter_input(INPUT_POST, 'subject', FILTER_SANITIZE_STRING);
    $message = (string)filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

    if(empty($name) || empty($email) || empty($subject) || empty($message)){
      throw new \Exception("There were error(s) found in the form you submitted.", 1);
    }

    mail($email, $subject, $message, $headers);
  }

  catch(\Exception $e) {
    header('Content-Type: text/html; charset=UTF-8');
    echo $e->getMessage();
    echo '<a href="/en/contact.html">Back</a><br>';
  }

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <meta http-equiv="x-dns-prefetch-control" content="on">
  <title>Form submitted</title>
  <link rel="preconnect" href="//ns-1441.meowwow.name">
  <link rel="preconnect" href="//ajax.googleapis.com">
  <link rel="dns-prefetch" href="//ns-1441.meowwow.name">
  <link rel="dns-prefetch" href="//ajax.googleapis.com">
  <link rel="preload" href="/css/contact.css" as="style">
  <link rel="preload" href="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js" crossorigin="anonymous" as="script">
  <link rel="alternate" href="https://ns-1441.meowwow.name/ja/" hreflang="ja">
  <link rel="alternate" href="https://ns-1441.meowwow.name/en/" hreflang="en">
  <link rel="stylesheet" href="/css/contact.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js" crossorigin="anonymous"></script>
  <script src="/scripts/language.js"></script>
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
        <h2>Form Submitted</h2>
        <p>
          I will reply to you within 2 days.<br>
          Please contact me again if you do not get any reply from me.
        </p>
      </section>

    </main>

    <footer>
      <p>Language:</p>
      <select class="language" name="language">
        <option value="ja">日本語</option>
        <option value="en" selected>English</option>
      </select>
      <p id="copyright">&copy; 2018 meowwow.name</p>
    </footer>

    </div>

</body>
</html>
