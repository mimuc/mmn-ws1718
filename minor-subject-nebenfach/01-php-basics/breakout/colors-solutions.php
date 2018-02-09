<!DOCTYPE html>
<html>
<head lang="en">
  <meta charset="UTF-8">
  <title>Colors</title>
  <style>
    body, html {
      background-color: #263238;
    }
    body {
      padding: 10%;
    }

    #lorem{
      color: white;
    }

    <?php

  if(isset($_POST['color']) && !empty($_POST['color'])) {
    $color = $_POST['color'];
    echo "#lorem { color: $color ;}";
  }

  ?>

  </style>


</head>
<body>


<form method="post">
  <input type="text" pattern="^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$" name="color"
         value="<?php echo isset($color) ? $color : '#ffea00' ?>">
  <input type="submit">
</form>

<div id="lorem">
  <p>Toffee icing tiramisu marzipan marshmallow tootsie roll halvah pastry caramels. Cheesecake jelly-o jelly-o. Gummies
    jelly beans icing sesame snaps candy jelly-o jelly-o. Soufflé liquorice dessert. Liquorice chupa chups bonbon
    brownie
    pastry cotton candy danish muffin tart. Lemon drops cookie candy cookie jelly powder halvah dessert tootsie roll.
    Cookie toffee cheesecake jelly beans.</p>
  <p>Donut oat cake lemon drops tootsie roll dragée jelly-o lemon drops fruitcake. Cotton candy lemon drops ice cream
    cupcake candy canes halvah chupa chups carrot cake. Sweet roll gummi bears chocolate cake apple pie carrot cake
    powder
    donut donut. Chupa chups gummies pastry jelly. Powder chocolate bar toffee carrot cake oat cake soufflé danish wafer
    pastry. Cupcake carrot cake pudding sweet biscuit dragée apple pie pastry jelly beans. Sweet marzipan topping
    cupcake
    chupa chups marzipan. Fruitcake caramels brownie sesame snaps gummies jelly beans icing bonbon macaroon. Tart
    macaroon
    marzipan ice cream.</p>
</div>
</body>
</html>