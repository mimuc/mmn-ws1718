<?php
// TODO start the session before anything else:
session_start();
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
    else if ($_SESSION['attempts'] > 0
              && isset($_POST['one'])
              && isset($_POST['two'])
              && isset($_POST['three'])
              && isset($_POST['four'])) { // <-- replace this with the correct condition.
      // to allow that the player can submit lowercase letters,
      // we just transform all of them to uppercase.
      $one = strtoupper(htmlspecialchars($_POST['one']));
      $two = strtoupper(htmlspecialchars($_POST['two']));
      $three = strtoupper(htmlspecialchars($_POST['three']));
      $four = strtoupper(htmlspecialchars($_POST['four']));

      // TODO construct an array of the current guessing round
      $guesses = array();
      array_push($guesses, $one, $two, $three, $four);

      if(!validateInput($guesses)){
        echo "<h2>Wrong input!</h2><p>Try again..</p>";
        return;
      }

      // TODO start with decrementing the remaining attempts
      $attempts = $_SESSION['attempts'];
      $attempts--;
      $_SESSION['attempts'] = $attempts;
      // TODO tell the user how many attempts are left.
      echo "<p>$attempts left!</p>";


      // TODO store the current guessing round to the session variable $_SESSION['guesses'];
      array_push($_SESSION['guesses'], $guesses);

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

        $dots = generateDots($attempt, $_SESSION['code']); // TODO replace array() with the current secret code (as an array)

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
          // clear all variables contected with the session
          $_SESSION = array();
        }
        echo '</div>';// .guessRow
      }

      echo '</div>'; // #results
    }
    // the player does not have any more attempts.
    else {
      printResult("You lose.");
      // clear all variables contected with the session
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
      $_SESSION['attempts'] = 10;

      // here's where you define the alphabet (code space)
      // TODO create an array containing the letters and assign it to a variable $alphabet
      $alphabet = array("A","B","C","D","E","F","G");
      // TODO shuffle the alphabet
      shuffle($alphabet);

      // TODO save four characters of the shuffled alphabet to the session as secret code.
      // (you can take the first four letters after they were shuffled)

      //get 4 elements starting from first element
      $_SESSION['code'] = array_slice($alphabet, 0, 4);

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

      //loop trough a guessing round
      for($i = 0; $i < 4;$i++){
        //check if a guessed element is part of the code
        if(in_array($guess[$i], $code)) {
          //check if the element is at the right position
          if($guess[$i] == $code[$i]){
            //element at right position
            array_push($dots, 'R');
          } else {
            //element in code but not at right position
            array_push($dots, 'B');
          }
        } else {
          //element not in code
          array_push($dots, 'W');
        }
      }

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
      echo "<div class='code'>The code was: ";
      //generate string containing all letters of the code separated by ' - '
      $code = implode($_SESSION['code'], ' - ');
      echo $code;
      echo "</div>";
    }

    /**
    * checks if user input elements are part of the given alphabet and
    * if two input elements are the same.
    * @param $inputArray User input submitted by form
    */
    function validateInput($inputArray){
      $alphabet = array("A","B","C","D","E","F","G");
      foreach ($inputArray as $key => $value) {
        if(!in_array($value, $alphabet)){
          return false;
        }
        foreach ($inputArray as $k => $v) {
          if($k != $key && $v == $value){
            return false;
          }
        }
      }
      return true;
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
