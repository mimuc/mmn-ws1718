<?php
// start the session before anything else:
session_start();
$sesson_id = session_id();

// enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// It is better to have this constant declaration at the top

// with the "define" function you initialize constants instead of variables.
// To make debugging your solution easier, you could add ?debug=1 to the URL
// and show the code to yourself for example.
define('DEBUG', isset($_GET['debug']));
define("MAX_ATTEMPTS", 10);

// currentuser variable
$currentuser = null;
// leaderboard variable
$leaderboard = array();

if (isset($_POST["register"]) && isset($_POST["username"])) {
    setcookie("currentuser", trim($_POST["username"])); // save current user
    $currentuser = $_POST["username"];
} else {
    if (isset($_COOKIE["currentuser"])) {
        $currentuser = $_COOKIE["currentuser"];
    }
}

if (isset($_POST["changeuser"])) {
    setcookie("currentuser", null);
    $currentuser = null;
}

if (isset($_POST["save"])) {
    saveUserData($_COOKIE["currentuser"], $_SESSION["attempts"], implode($_SESSION["code"]));
} else {
    if (isset($_COOKIE["leaderboard"])) {
        $leaderboard = json_decode($_COOKIE["leaderboard"], true);
    }
}

// alphabet variable
$alphabet = array(
    "A", "B", "C", "D", "E", "F", "G"
);

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
    if (!isset($currentuser)) {
        // load registry form
        include_once('register.inc.php'); ?>
        <?php
    } else { ?>
        <form id="userform" method="post">
            <input class="btn" type="submit" name="changeuser" value="Change player"/>
        </form>
        <div class="clear"></div>
        <?php
        include_once('form.inc.php');
        ?>
        <div id="output">
            <?php

            // first, check if the player submitted the form or if the session has even started.
            if (!isset($_SESSION['hasStarted']) || !isset($_POST['submit'])) {
                // if neither is the case, (re-)start the game.
                restart($alphabet);

                echo '<div>' . $currentuser . ', try to guess the code.</div>';
            }
            // the session has started and/or there is a submitted form.
            // so we check if the user can still guess by checking the session variable.
            // check if the user is allowed to try, and if $_POST contains all necessary information.
            else if (
                isset($sesson_id) && isset($_POST["submit"]) && $_SESSION["attempts"] > 1 &&
                isset($_POST["one"]) && isset($_POST["two"]) && isset($_POST["three"]) && isset($_POST["four"])
            ) { // <-- replace this with the correct condition.
                // start with decrementing the remaining attempts

                // to allow that the player can submit lowercase letters,
                // we just transform all of them to uppercase.
                $one = strtoupper($_POST['one']);
                $two = strtoupper($_POST['two']);
                $three = strtoupper($_POST['three']);
                $four = strtoupper($_POST['four']);

                // construct an array of the current guessing round
                $guess = array($one, $two, $three, $four);

                if (validateInput($guess, $alphabet)) {
                    $_SESSION["attempts"] = max(0, $_SESSION["attempts"] - 1);

                    // store the current guessing round to the session variable $_SESSION['guesses'];
                    $_SESSION['guesses'][] = $guess;
                } else {
                    echo "<p style='color:red;'>Only letters between A-G are allowed and each letter may occur only once.</p>";
                }

                // tell the user how many attempts are left.
                echo $currentuser . ", you have " . $_SESSION["attempts"] . " attempts left";

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
                    // this will only work after you implement the function.

                    $dots = generateDots($attempt, $_SESSION["code"]);

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

                        // check if record was broken
                        if (!key_exists($currentuser, $leaderboard) ||
                            $leaderboard[$currentuser]["attempts"] > (MAX_ATTEMPTS - $_SESSION["attempts"])
                        ) {
                            echo "<p>New record!</p>";
                            echo "<form method='post'><input class='btn' type='submit' name='save' value='Save result to leaderboard'></form>";
                        }
                        echo '<div class="clear"></div>';
                        echo '</div>';

                        // reset the session.
                        session_abort();
                        $_SESSION = array();
                    }
                    echo '</div>';// .guessRow
                }

                echo '</div>'; // #results
            } // the player does not have any more attempts.
            else {
                printResult("You lose.");
                $_SESSION = array();
            }

            ?>
            <div class="clear"></div>
        </div>
    <?php }


    function saveUserData($username, $attempts, $code)
    {
        $data = array();
        if (!empty($_COOKIE["leaderboard"])) {
            // load data from cookie. Because data is a JSON string we have to decode to associative array
            $data = json_decode($_COOKIE["leaderboard"], true);
        }

        $name = trim("" . $username); // implicit conversion to string

        // we do not need a check if key already exists because we overwrite any way.
        $data[$name] = array(
            "attempts" => MAX_ATTEMPTS - $attempts + 1,
            "code" => $code
        );

        //sort leaderboard by the number of attempts
        uasort($data, function ($a, $b) {
            if ($a["attempts"] === $b["attempts"])
                return 0;
            if ($a["attempts"] > $b["attempts"])
                return 1;

            return -1;
        });

        setcookie("leaderboard", json_encode($data), time() + (3600 * 24 * 7)); // save for 7 days
        global $leaderboard;
        $leaderboard = $data;
    }

    /**
     * this function initializes all necessary session variables
     * foremost, it constructs the code word.
     */
    function restart($default_alphabet)
    {
        // how long is the code word?
        $codeLength = 4;

        // this can be used to show the landing page and then only
        $_SESSION['hasStarted'] = true;

        // how many attempts does the player have?
        // initialize a session variable that holds the number of remaining attempts.
        $_SESSION['attempts'] = MAX_ATTEMPTS;

        // here's where you define the alphabet (code space)
        // create an array containing the letters and assign it to a variable $alphabet
        $alphabet = $default_alphabet;

        // shuffle the alphabet
        shuffle($alphabet);

        // save four characters of the shuffled alphabet to the session as secret code.
        // (you can take the first four letters after they were shuffled)
        $_SESSION["code"] = array_slice($alphabet, 0, $codeLength);

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
    function generateDots($guess, $code)
    {
        $dots = array();
        $code_str = implode($code);

        //
        // 1) loop through the guess array, which should contain 4 letters.
        // 2) for each letter, check if it is contained in the code.
        // 3) if it is contained and is at the right position, add an "R" to the $dots array
        // 4) if it is contained, but at some other position, add a "B" to the $dots array
        // 5) if it is not contained, add a "W" to the $dots array.
        for ($i = 0; $i < sizeof($guess); $i++) {
            $letter = $guess[$i];
            if (stristr($code_str, $letter) !== false) {
                // letter exists in the code
                if ($code[$i] === $letter) {
                    // letter is at the right position
                    $dots[] = "R";
                } else {
                    // letter is at another position
                    $dots[] = "B";
                }
            } else {
                // letter is not contained in the code
                $dots[] = "W";
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
    function printResult($message)
    {
        echo "<div class='result'>$message</div>";
        echo "<div class='code'>The code was: ";
        // show the code to the user.
        echo implode($_SESSION["code"]);
        echo "</div>";
    }

    function validateInput($guess, $alphabet)
    {
        $any_empty = false;
        for ($i = 0; $i < sizeof($guess); $i++) {
            if (empty($guess[$i])) {
                $any_empty = true;
                break;
            }
        }

        if ($any_empty) {
            // there is a empty field
            return false;
        }

        // check if letters from guess are in the alphabet
        $in_alphabet = true;
        for ($i = 0; $i < sizeof($guess); $i++) {
            $letter = $guess[$i];
            if (stripos(implode($alphabet), $letter) === false) {
                $in_alphabet = false;
                break;
            }
        }

        // check if letter is used more than once
        $letter_once = true;
        for ($i = 0; $i < sizeof($guess); $i++) {
            $letter = $guess[$i];
            // letter is not empty
            $occurances = substr_count(implode($guess), $letter);
            if ($occurances > 1) {
                $letter_once = false;
                break;
            }
        }


        return $in_alphabet && $letter_once;
    }

    // this is for the cheaters.
    // if you call the script with ?debug=true
    // it will secretly print the code in the javascript console.
    // FYI: yes, you can mix PHP, HTML, CSS, and JavaScript - this is why PHP is kind of a mess.
    if (DEBUG && isset($_SESSION['code'])) {

        echo "<script type='text/javascript'>console.log('";
        echo implode($_SESSION["code"]); // this is smaller than your code ;)
        echo "');</script>";

    }
    ?>
    <div class="clear"></div>
</div>
<div class="clear"></div>
<div id="toplist">
    <h3>Leaderboard</h3>
    <table>
        <thead>
        <tr>
            <td>Rank</td>
            <td>Name</td>
            <td>Code</td>
            <td>Attempts</td>
        </tr>
        <tbody>
        <?php
        // read data from leaderboard to table
        if (isset($leaderboard)) {
            $rank = 0;
            $attempts_before = -1;
            foreach ($leaderboard as $key => $value) {
                if ($attempts_before !== $value["attempts"]) {
                    ++$rank;
                }
                echo "<tr><td># " . $rank . "</td><td>" . $key . "</td>" . "<td>" . $value["code"] . "</td>" . "<td>" . $value["attempts"] . "</td></tr>";
                $attempts_before = $value["attempts"];
            }
        }
        ?>
        </tbody>
        </thead>
    </table>
</div>
<script>
    // javascript code to focus on start and focus the next field after typing a letter
    if (document.getElementById("output")) {
        setTimeout(function () {
            document.getElementById("one").focus();
        }, 500);

        function onKeyUp(event, field) {
            if (
                !(event.hasOwnProperty("key") && (event.key === "Shift"
                    || event.key === "Backspace" || event.key === "Tab")) &&
                (event.keyCode !== 16 && event.keyCode !== 8 && event.keyCode !== 9)
            ) {
                // Shift key was not pressed
                document.getElementById(field).focus();
            }
        }

        document.getElementById("one").onkeyup = function (event) {
            document.getElementById("one").focus();
            onKeyUp(event, "two");
        };
        document.getElementById("two").onkeyup = function (event) {
            onKeyUp(event, "three");
        };
        document.getElementById("three").onkeyup = function (event) {
            onKeyUp(event, "four");
        };
    }
</script>
</body>
</html>
