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
  <textarea name="input" id="textarea"><?php
      if (!empty($_POST)) {
          echo $_POST["input"];
      }
      ?></textarea>
            <button type="submit">Shuffle!</button>
        </form>
    </div>

    <?php

    function scramble($input)
    {
        return preg_replace_callback("(\w+)", function ($matches) {
            $word = $matches[0];
            $word_length = strlen($word);
            if ($word_length > 3) {
                $inner = preg_split("//", substr($word, 1, $word_length - 2));
                shuffle($inner);
                $inner = implode("", $inner);
                return $word[0] . $inner . $word[$word_length - 1];
            }

            return $word;
        }, $input);
    }

    if (!empty($_POST["input"])) { ?>
        <div class="card">
        <?php echo scramble($_POST["input"]); ?>
        </div>
    <?php }
    ?>

</div>
<link rel="stylesheet" href="http://www.medien.ifi.lmu.de/lehre/ws1617/mmn/uebung/material/assignments.css">
</body>
</html>