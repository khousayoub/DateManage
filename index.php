<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Date Manage</title>
    <link rel="stylesheet" href="style.css">
  </head>
  <body>

    <form method="post">
      <label for="">Start Date :</label>
      <input type="text" name="begdate" value=""></br>
      <label for="">End Date :</label>
      <input type="text" name="enddate" value=""></br>
      <label for="">Number of tasks :</label>
      <input type="number" name="nbtask" value=""><br>
      <label for="">Weekend : </label>
      <label for="">ON</label><input type="radio" name="weekend" value="true">
      <label for="">OFF</label><input type="radio" name="weekend" value="false"><br>
      <input type="submit"></input>

    </form>
    <table>
      <thead>
        <th>Day</th>
        <th>Date</th>
      </thead>
      <tbody>
        <?php include "EquiTable.php";
        $a = equiDate($_POST["begdate"],$_POST["enddate"], $_POST["nbtask"], $_POST["weekend"]);
        for ($i=0; $i<count($a); $i++){
          ?>
          <tr>
            <td><?php echo substr($a[$i],0,-11); ?></td>
            <td><?php echo substr($a[$i],-10); ?></td>
          </tr>
        <?php
          }
          ?>
      </tbody>
    </table>
  </body>
</html>
