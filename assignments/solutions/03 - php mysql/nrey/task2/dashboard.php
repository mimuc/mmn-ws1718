<?php
  // wrapper for elements and imports needed for all pages
  include_once('top.php');

  $connectionInfo = new ConnectionInfo();

  // the DBHandler takes care of all the direct database interaction.

  $dbHandler = new DBHandler(
  $connectionInfo->host,
  $connectionInfo->user,
  $connectionInfo->password,
  $connectionInfo->db);

  // now, let's see whether the user has submitted the form
  $message = "";
  if(isset($_POST['submit']) && isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] && isset($_SESSION['userName'])){

    // sanitize all input
    foreach ($_POST as $value) {
      $dbHandler->sanitizeInput($value);
    }

    // use this to check which button was clicked
    $action = $_POST['submit'];
    $title = isset($_POST['title'])? $_POST['title'] : "No title!";
    $note = isset($_POST['note'])? $_POST['note'] : "No message!";
    $id = isset($_POST['id'])? $_POST['id'] : "No id!";

    // this is just a means to process the deletion of one or multiple notes the same way
    $deleteIds = array();
    foreach ($_POST as $key => $value) {
      if($key !== 'note' && $key !== 'title' && $key !== 'submit'){
        $deleteIds[] = $value;
      }
    }

    // switch over action to trigger behavior that user wanted
    switch ($action) {
      case 'Add note': // Add button was used
        if($dbHandler->insertNote($_SESSION['userID'], $title, $note, $_SESSION['userName'])){
          $message = "<div class='hint'>Successfully added a note!</div>";
        } else {
          $message = "<div class='hint error'>Error while adding note!</div>";
        }
      break;
      case 'Update': // Update button was used
        if($dbHandler->updateNote($id, $title, $note)){
          $message = "<div class='hint'>Successfully updated note!</div>";
        } else {
          $message = "<div class='hint error'>Error while updating note!</div>";
        }
        break;
      case 'Delete': // Delete button was used
      case 'Delete all marked notes': // Delete all button was used
        if($dbHandler->deleteNotes($deleteIds)){
          $message = "<div class='hint'>Successfully deleted notes!</div>";
        } else {
          $message = "<div class='hint error'>Error while deleting notes!</div>";
        }
        break;
      default: // default value
        $message = "<div class='hint error'>Something went wrong... </div>";
        break;
    }
  }
  // create and include top navigation
  dashboardNavi($message);
?>

<div id="container">

<?php
  // check if the user is logged in and render form to add new notes or link to login page
  if(isset($_SESSION['loggedIn']) && $_SESSION['loggedIn']){
    $output = <<< EOT
      <div id="newFormContainer">
      <form method="post">
      <input type="text" name="title" placeholder="Title goes here" required>
      <textarea name="note" placeholder="Add a message here" required></textarea>
      <input class="add button button-hover" type="submit" name="submit" value="Add note" />
      </form>
      </div>
EOT;
  } else {
    $output = <<<EOT
    <h4>Please login or register to view and edit notes!</h4>
    <h2><a href="login.php">Login or register</a></h2>
EOT;
  }
echo $output;
?>
</div>

<div class="container" id="notes">
<?php
  // check if notes are available for the logged in user and render
  // a card for each or a default card if none have been created yet
  if(isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] && isset($_SESSION['userName'])){
    $notes = $dbHandler->fetchNotes($_SESSION['userName']);
    if(count($notes) == 0){
      echo '<div class="card"><h4>No notes available for your account... Feel free to add some!</h4></div>';
    } else {
      foreach ($notes as $element){
        $id = $element['id'];
        $title = $element["title"];
        $note = $element["note"];
        cardItem($id,$title,$note);
      }
      $output = <<<EOD
        <form id="deleteForm" method="post">
        <input class="button button-hover" type="submit" name="submit" value="Delete all marked notes">
        </form>
EOD;
      echo $output;
    }
  }
?>

<?php include_once('bottom.php');?>

<?php
  // always close db connection after page has been rendered
  $dbHandler->closeDb();
?>
