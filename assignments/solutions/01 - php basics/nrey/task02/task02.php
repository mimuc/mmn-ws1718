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
    $sanitizeInput = htmlspecialchars($_POST['text']);
    $text = scrambleInput($sanitizeInput);
      echo $text;
    } ?></textarea>
      <button type="submit">Shuffle!</button>
    </form>
  </div>

  <?php

  function scrambleInput($input) {
    //transform input to an array of strings separated by whitespace
    $parts = explode(" ", $input);
    //transform each element of the array using a custom function
    array_walk($parts, 'scrambleWord');
    //reassemble the transformed array elements with whitespaces in between
    $result = implode(" ", $parts);
    return $result;
  }

  function scrambleWord(&$word) {
    $pattern = "/[äöüÄÖÜ0-9a-z-_']+/";

    //use a word without possible attached punctuation etc.
    preg_match($pattern,$word,$matchArray);
    $match = "";
    //first element of the matchArray is the word to be transformed if it exists
    if(isset($matchArray[0]) || array_key_exists(0,$matchArray)) {
      $match = $matchArray[0];
    }

    //only transform if the match is longer than 2 chars
    if(strlen($match) > 2) {
      //use word without first and last char as pattern
      $replaceWord = substr($match, 1, strlen($match)-2);
      //shuffle word without first and last char
      $shuffleWord = str_shuffle(substr($match, 1, strlen($match)-2));
      //replace and put shuffled part in a word
      $word = preg_replace("/$replaceWord/", $shuffleWord, $word);
    }
  }

  ?>

</div>
<link rel="stylesheet" href="http://www.medien.ifi.lmu.de/lehre/ws1617/mmn/uebung/material/assignments.css">
</body>
</html>
