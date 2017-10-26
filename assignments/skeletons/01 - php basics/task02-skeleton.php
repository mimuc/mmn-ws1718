<!DOCTYPE html>
<html>
<head lang="en">
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Shuffle my words</title>
  <style>
    textarea {
      width: 100%;
      display: block;
      margin: 10px 0;
    }
  </style>
</head>
<body>
<header>
  <h2>Shuffle my words!</h2>
</header>
<div id="container">
  <div class="card">
    <form method="post">
  <textarea placeholder="Your text here" name="text"><?php if (isset($_POST['text'])) {
      echo $_POST['text'];
    } ?></textarea>
      <button type="submit">Shuffle!</button>
    </form>
  </div>

  <?php
  // Your code goes here.

  ?>

</div>
<link rel="stylesheet" href="http://www.medien.ifi.lmu.de/lehre/ws1617/mmn/uebung/material/assignments.css">
</body>
</html>