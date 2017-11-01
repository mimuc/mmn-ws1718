<?php
// TODO start the session before anything else:
?>
<html>
<head>
  <title>Codebreaker</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="author" content="Tobias Seitz">
  <link rel="stylesheet" href="codebreaker.css"/>
</head>
<body>
<div id="container">

  <h1>Codebreaker</h1>

  <?php
  // one nice thing about PHP is that it can be split into multiple scripts.
  // for the sake of demonstration: we include the form from an external file.
  include_once('form.inc.php');
  ?>
  <div id="output">
    <?php

    // with the "define" function you initialize constants instead of variables.
    // To make debugging your solution easier, you could add ?debug=1 to the URL
    // and show the code to yourself for example.
    define('DEBUG', isset($_GET['debug']));


    // first, check if the player submitted the form or if the session has even started.
    if (!isset($_SESSION['hasStarted']) || !isset($_POST['submit'])) {
      // if neither is the case, (re-)start the game.
      restart();

      echo '<div>Try to guess the code.</div>';
    }
    // the session has started and/or there is a submitted form.
    // so we check if the user can still guess by checking the session variable.
    // TODO check if the user is allowed to try, and if $_POST contains all necessary information.
    else if (TRUE) { // <-- replace this with the correct condition.

      // TODO start with decrementing the remaining attempts

      // TODO tell the user how many attempts are left.

      // to allow that the player can submit lowercase letters,
      // we just transform all of them to uppercase.
      $one = strtoupper($_POST['one']);
      $two = strtoupper($_POST['two']);
      $three = strtoupper($_POST['three']);
      $four = strtoupper($_POST['four']);

      // TODO construct an array of the current guessing round

      // TODO store the current guessing round to the session variable $_SESSION['guesses'];


      echo '<div id="results">';

      // the following will print the history of guesses
      // we loop through all guessing rounds that are stored in $_SESSION["guesses"]
      foreach ($_SESSION["guesses"] as $attempt) {
        echo "<div class='guessRow'>";

        // print the individual letters (next to the the colored dots)
        echo '<div class="letters">';
        foreach ($attempt as $letter) {
          echo "<div class='letter'>$letter</div>";
        }
        echo '</div>'; // .letters

        // transform the attempt into a color code.
        // TODO this will only work after you implement the function.

        $dots = generateDots($attempt, array()); // TODO replace array() with the current secret code (as an array)

        // translate the $dots array into divs with the corresponding class.
        foreach ($dots as $dot) {
          switch ($dot) {
            case "R":
              $dotClass = "red";
              break;
            case "B":
              $dotClass = "black";
              break;
            default:
              $dotClass = "white";
              break;
          }
          echo "<div class='dot $dotClass'></div>";
        }


        // now to the critical point.
        // did the player get it right?
        // we can do that by examining the $dots array
        // if it only contains "R", i.e. correct letters at the correct position,
        // the solution is correct.
        // the array_diff function comes in handy here, if we do not want to loop through all that again.
        $diff = array_diff($dots, array("R", "R", "R", "R"));
        if (count($diff) == 0) {
          printResult("You won.");
          echo '<div class="clear"></div>';
          echo '</div>';

          // TODO reset the session.
        }
        echo '</div>';// .guessRow
      }

      echo '</div>'; // #results
    }
    // the player does not have any more attempts.
    else {
      printResult("You lose.");
      $_SESSION = array();
    }





    /**
     * this function initializes all necessary session variables
     * foremost, it constructs the code word.
     */
    function restart() {
      // how long is the code word?
      $codeLength = 4;

      // this can be used to show the landing page and then only
      $_SESSION['hasStarted'] = true;

      // how many attempts does the player have?
      // TODO initialize a session variable that holds the number of remaining attempts.


      // here's where you define the alphabet (code space)
      // TODO create an array containing the letters and assign it to a variable $alphabet

      // TODO shuffle the alphabet

      // TODO save four characters of the shuffled alphabet to the session as secret code.
      // (you can take the first four letters after they were shuffled)


      // this will hold a history of the user's guesses.
      // it is a two-dimensional array (each entry is a guessing round that contains the individual guesses).
      $_SESSION['guesses'] = array();
    }


    /**
     *
     * The main idea behind this function is to generate an array that contains the "colors" of the
     * dots.
     *
     * @param $guess array containing the full history of guesses.
     * @param $code array representing the correct code
     * @return array containing the "hints" as abbreviated colors, i.e. "R", "B", "W"
     */
    function generateDots($guess, $code) {
      $dots = array();

      // TODO :
      //
      // 1) loop through the guess array, which should contain 4 letters.
      // 2) for each letter, check if it is contained in the code.
      // 3) if it is contained and is at the right position, add an "R" to the $dots array
      // 4) if it is contained, but at some other position, add a "B" to the $dots array
      // 5) if it is not contained, add a "W" to the $dots array.


      // to avoid revealing *which* letter is represented by the dots, we simply sort the array that contains the hints.
      sort($dots);
      return $dots;
    }


    /**
     * prints a message and the correct code.
     * @param $message String to be shown to the user. (e.g. "you win!" or "you lose!")
     */
    function printResult($message) {
      echo "<div class='result'>$message</div>";
      echo "<div class='code'>The code was:";
      // TODO show the code to the user.
      echo "</div>";
    }

    // this is for the cheaters.
    // if you call the script with ?debug=true
    // it will secretly print the code in the javascript console.
    // FYI: yes, you can mix PHP, HTML, CSS, and JavaScript - this is why PHP is kind of a mess.
    if (DEBUG && isset($_SESSION['code'])) {

      echo "<script type='text/javascript'>console.log('";
      foreach ($_SESSION['code'] as $letter) {
        echo $letter;
      }
      echo "');</script>";

    }
    ?>
    <div class="clear"></div>
  </div>
  <div class="clear"></div>
</div>
<div class="clear"></div>
</body>
</html>
