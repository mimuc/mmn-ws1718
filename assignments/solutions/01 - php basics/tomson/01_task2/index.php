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
        .card {
            float: left;
            width: 45%;
            padding: 20px;
            box-sizing: border-box;
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

    <div class="card">
        <?php
        //Skript läuft nur, wenn ein Text eingegeben wurde
        if(isset($_POST['text'])){
            // Ausgabe Array
                $ausgabe = array();
            //Text in Variable speichern
                $text = $_POST['text'];
            //Text als Wörter in das Array einlesen
                 $woerter = explode(" ", $text);
            // alle Wörter einzeln Scrumbeln und ins Ausgabe Array schreiben

                foreach($woerter as $wort){
                    //Wortlänge bestimmen
                    $wortlaenge = strlen($wort);
                    //Sonderzeichen am Wortende rausfiltern
                    $sonderzeichen = false;
                   if(ctype_alpha($wortlaenge -1)){
                       $sonderzeichen = true;
                   }

                    //Mittelteil herausfiltern
                    $tmp = array();
                    $offset = 2;
                    if($sonderzeichen = true){$offset = 3;}
                    for($i = 1; $i <= ($wortlaenge-$offset); $i++){
                    $tmp[] = $wort[$i];
                    }
         ;
                    //Mittelteil Shuffeln
                    shuffle($tmp);
                    //Array wieder zusammenfügen
                    $ausgabewort = $wort[0];

                    foreach($tmp as $zeichen){ $ausgabewort .= $zeichen;}
                    if($sonderzeichen = true){$ausgabewort .= $wort[($wortlaenge - 2)];}
                    $ausgabewort .= $wort[($wortlaenge - 1)];
                    //Wort an Output anhängen
                    $ausgabe[] = $ausgabewort;

                }

        $ausgabetext = implode(" ", $ausgabe);
        echo"<h2>Output:</h2> <p>$ausgabetext</p>";




        }
        ?>
    </div>
</div>
<link rel="stylesheet" href="http://www.medien.ifi.lmu.de/lehre/ws1617/mmn/uebung/material/assignments.css">
</body>
</html>