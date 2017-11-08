<!DOCTYPE html>
<html>
<head lang="en">
  <meta charset="UTF-8">
  <title> Scramble text example</title>
</head>
  <body>
  <p>Type text to scramble here:</p>

<?php
$text = "";

//check for posted input
if (!empty($_POST['scrambleText'])) {
  //prevent malicious code injection
  $sanitizeInput = htmlspecialchars($_POST['scrambleText']);
  $text = scrambleInput($sanitizeInput);
}

//write html elements for form
echo <<<EOT
<form method="post">
<textarea id="scrambleText" name="scrambleText" style="width: 500px;height: 150px;">$text</textarea>
<br>
<input type="submit" value="Scramble" name="submit"/>
</form>
EOT;

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

  </body>
</html>
