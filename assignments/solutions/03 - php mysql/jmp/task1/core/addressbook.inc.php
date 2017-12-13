<h1>Adressbook</h1>
<p>
    Task 1 with optional features.
</p>
<?php
// initialize variables for the form
$firstname = "";
$lastname = "";
$street = "";
$code = "";
$city = "";
$email = "";
$ID = "";

if (!empty($_POST)) {
    // something submitted

    if (empty($_POST["reset_form"])) {
        // convert $_POST array to local variables
        // the default values will be overwritten
        extract($_POST);
    }

    if (isset($save_contact)) {
        // convert code to null if not number (to make sql query work)
        if (isset($code) && ($code == "" || !is_numeric($code))) {
            $code = 'null';
        }

        // check if mandatory elements are set
        if (isset($firstname) && $firstname != "" && isset($email) && $email != "") {

            if (isset($ID) && is_numeric($ID)) {
                // change entry

                // check if email already exists in DB
                $email_exists = true;
                $result = $db->query("SELECT * FROM " . TABLE_NAME . " WHERE email='$email' AND ID <> $ID");
                if ($result == false || $result->num_rows == 0) {
                    $email_exists = false;
                }
                if (!$email_exists) {
                    $result = $db->query("UPDATE " . TABLE_NAME . " SET firstname='$firstname', lastname='$lastname', street='$street', code=$code, city='$city', email='$email' WHERE ID=$ID");

                    if ($result != false) {
                        // edit ok ?>
                        <div class="alert alert-success" role="alert">
                            Contact info updated.
                        </div>
                    <?php } else {
                        // edit failed ?>
                        <div class="alert alert-warning" role="alert">
                            Could not overwrite new contact info.
                        </div>
                    <?php }
                } else {
                    ?>
                    <div class="alert alert-warning" role="alert">
                        Duplicate entry: another user has the same email address.
                    </div>
                <?php }
            } else {
                // new entry
                // check if email already exists in DB
                $email_exists = true;
                $result = $db->query("SELECT * FROM " . TABLE_NAME . " WHERE email='$email'");
                if ($result == false || $result->num_rows == 0) {
                    $email_exists = false;
                }
                if (!$email_exists) {
                    $db->query("INSERT INTO " . TABLE_NAME . " (firstname, lastname, street, code, city, email) VALUES ('$firstname', '$lastname', '$street', $code, '$city', '$email')");
                } else {
                    ?>
                    <div class="alert alert-warning" role="alert">
                        Duplicate entry: the email address may occur only once.
                    </div>
                <?php }
            }
        } else {
            // no firstname or email address
            ?>
            <div class="alert alert-danger" role="alert">
                First name and email address are mandatory.
            </div>
        <?php }
    } else if (isset($entry_action) && isset($selected_id) && is_numeric($selected_id)) {
        // some action on an entry

        $result = false;
        $person = null;

        // get action type
        switch ($entry_action) {
            case("delete"):
                // delete entry
                $person = $db->query("SELECT * FROM " . TABLE_NAME . " WHERE ID=$selected_id")->fetch_object();
                $result = $db->query("DELETE FROM " . TABLE_NAME . " WHERE ID=$selected_id");
                break;
            case("edit"):
                // edit entry
                // that means we have to get the data from the DB
                $person = $db->query("SELECT * FROM " . TABLE_NAME . " WHERE ID=$selected_id")->fetch_assoc();

                // extract data
                extract($person);
                break;
        }

        if ($entry_action !== 'delete') {
            // show message only on delete

            if ($result == false || empty($person)) {
                if ($entry_action == "delete") {
                    ?>
                    <div class="alert alert-danger" role="alert">
                        Contact could not be deleted.
                    </div>
                <? }
            } else if ($entry_action == "delete") { ?>
                <div class="alert alert-success" role="alert">
                    Contact "<?php echo $person->firstname . " (" . $person->email . ")" ?>" successfully deleted.
                </div>
            <? }
        }
    }
}

?>
<hr>
<div class="container">
    <div class="row">
        <div class="col-md-5">
            <div class="card">
                <div class="card-header">
                    Add new contact
                </div>
                <div class="card-body">
                    <!-- CONTACT FORM -->
                    <form method="POST">
                        <div class="form-group">
                            <label for="firstname">First Name</label>
                            <input type="text" class="form-control" id="firstname" name="firstname"
                                   placeholder="Enter First Name" value="<?php echo $firstname; ?>">
                        </div>
                        <div class="form-group">
                            <label for="lastname">Last Name</label>
                            <input type="text" class="form-control" id="lastname" name="lastname"
                                   placeholder="Enter Last Name" value="<?php echo $lastname; ?>">
                        </div>
                        <div class="form-group">
                            <label for="street">Street address</label>
                            <input type="text" class="form-control" id="street" name="street"
                                   placeholder="Enter Street address" value="<?php echo $street; ?>">
                        </div>
                        <div class="form-group">
                            <label for="code">Postal Code</label>
                            <input type="number" class="form-control" id="code" name="code"
                                   placeholder="Enter Postal code" value="<?php echo $code; ?>">
                        </div>
                        <div class="form-group">
                            <label for="code">City</label>
                            <input type="text" class="form-control" id="city" name="city"
                                   placeholder="Enter City" value="<?php echo $city; ?>">
                        </div>
                        <div class="form-group">
                            <label for="email">Email address</label>
                            <input type="email" class="form-control" id="email" name="email"
                                   placeholder="Enter email address" value="<?php echo $email; ?>">
                        </div>
                        <input type="hidden" name="ID" value="<?php echo $ID; ?>"/>
                        <div class="row">
                            <div class="col-md-5"><input type="reset" name="reset_form" value="Reset form"
                                                         style="width:100%;"
                                                         class="btn btn-secondary"
                                /></div>
                            <div class="col-md-7">
                                <input type="submit" name="save_contact" value="Add contact" style="width:100%"
                                       class="btn btn-primary"/></div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-7 contacts">
            <div class="card">
                <div class="card-header">
                    My contacts
                </div>
                <div class="card-body">
                    <!-- CONTACTS TABLE -->
                    <form method="post">
                        <table id="contactstable" class="table table-responsive table-striped">
                            <thead class="thead-light">
                            <tr>
                                <th></th>
                                <th scope="col">First name</th>
                                <th scope="col">Last name</th>
                                <th scope="col">Street</th>
                                <th scope="col">Postal code</th>
                                <th scope="col">City</th>
                                <th scope="col">Email</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $entries = $db->query("SELECT * FROM " . TABLE_NAME);
                            while ($entry = $entries->fetch_object()) { ?>
                                <tr>
                                    <td data-id="<?php echo $entry->ID; ?>">
                                        <i class="fa fa-edit icon edit-icon" aria-hidden="true"></i>
                                        <i class="fa fa-trash-o icon delete-icon" aria-hidden="true"></i>
                                        <i style="clear:left;"></i>
                                    </td>
                                    <td><?php echo $entry->firstname ?></td>
                                    <td><?php echo $entry->lastname ?></td>
                                    <td><?php echo $entry->street ?></td>
                                    <td><?php echo $entry->code ?></td>
                                    <td><?php echo $entry->city ?></td>
                                    <td><a href="mailto:<?php echo $entry->email ?>"><?php echo $entry->email ?></a>
                                    </td>
                                </tr>
                            <?php }
                            ?>
                            </tbody>
                        </table>
                        <input type="text" id="selected-id" name="selected_id" style="display: none"/>
                        <input type="submit" id="action" name="entry_action" style="display: none"/>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $(".delete-icon").click(function (event) {
            var id = $(this).parent().attr("data-id");
            $("#selected-id").val(id);
            // set action to delete
            $("#action").attr("value", "delete");
            // click hidden submit button
            $("#action").click();
        });
        $(".edit-icon").click(function (event) {
            var id = $(this).parent().attr("data-id");
            $("#selected-id").val(id);
            // set action to delete
            $("#action").attr("value", "edit");
            // click hidden submit button
            $("#action").click();
        });
    });
</script>