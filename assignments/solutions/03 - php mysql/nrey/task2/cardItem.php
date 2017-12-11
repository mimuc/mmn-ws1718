<?php
// creates a card element for a note
// checkbox for deleting multiple elements is included here
// but belongs to form at the bottom of the dashboard page
function cardItem($id, $title, $note){
  $title = "<input type='text' name='title' value='$title'>";
  $checkbox = "<input type='checkbox' form='deleteForm' name='$id' value='$id'>";
  $output = <<<EOD
    <div class='card'>
    $checkbox
    <form method='post'>
    <input class='hidden' type='text' name='id' value=$id>
    $title
    <textarea name='note'>$note</textarea>
    <input class='update button button-hover inline' type='submit' name='submit' value='Update'>
    <input class='delete button button-hover inline' type='submit' name='submit' value='Delete'>
    </form>
    </div>
EOD;
echo $output;
}
?>
