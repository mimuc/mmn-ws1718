<div class="container">
    <form method="POST">
        <div class="card">
            <div class="card-header">
                <input type="text" name="title" style="width:100%;" placeholder="Title"/>
            </div>
            <div class="card-body" style="min-height: 300px;">
                <textarea style="width:100%; resize: none;height:100%; border:none;" placeholder="My new fancy note"
                          name="text"></textarea>
            </div>
            <input type="submit" class="btn btn-primary" name="add_note" value="SAVE">
        </div>
    </form>
</div>
<div class="container" style="margin-top: 30px;">
    <h3>My Notes</h3>
    <form method="POST">
        <input type="submit" name="delete_notes" value="Delete Notes" style="float:right;" class="btn btn-secondary"/>
        <div style="clear: both;"></div>
        <?php
        if (!empty($user)) {
            foreach ($user->getNotes() as $note) { ?>
                <div class="card" style="margin-bottom:5px;">
                    <div class="card-header">
                        <?php echo $note->getTitle(); ?>
                        <input type="checkbox" style="position:absolute; top:0;right:0; margin:5px;"
                               name="note[]" value="<?php echo $note->getId(); ?>"/>
                    </div>
                    <div class="card-body">
                        <?php echo $note->getText(); ?>
                    </div>
                </div>
            <?php }
        }
        ?>
    </form>
</div>