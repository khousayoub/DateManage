<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Date Manage</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  </head>
  <body>
    <div class="container jumbotron">
      <div style="text-align: center;text-decoration: underline;">
        <h2>Date Manager</h2>
      </div>
      <form method="post">
        <div class="form-group row">
          <label for="begdate" class="col-sm-2 col-form-label">Start date :</label>
          <div class="col-sm-10">
            <input type="date" class="form-control" name="begdate" placeholder="YYYY-MM-DD" required>
          </div>
        </div>
        <div class="form-group row">
          <label for="enddate" class="col-sm-2 col-form-label">End date :</label>
          <div class="col-sm-10">
            <input type="date" class="form-control" name="enddate" placeholder="YYYY-MM-DD" required>
          </div>
        </div>
        <div class="form-group row">
          <label for="task" class="col-sm-2 col-form-label">Number of tasks :</label>
          <div class="col-sm-10">
            <input type="number" class="form-control" name="nbtask" placeholder="Tasks" required>
          </div>
        </div>
        <div class="form-group row">
          <label for="task" class="col-sm-2 col-form-label">Week-end OFF/ON ? </label>
          <div class="col-sm-10">
            <div class="form-check">
              <label class="form-check-label">
                <input class="form-check-input" type="radio" name="weekend" id="gridRadios1" value="true" checked>
                ON
              </label>
            </div>
            <div class="form-check">
              <label class="form-check-label">
                <input class="form-check-input" type="radio" name="weekend" id="gridRadios2" value="false">
                OFF
              </label>
            </div>
          </div>
        </div>
        <div class="form-group row ">
          <div class="offset-sm-2 col-sm-12">
            <button type="submit" class="btn btn-primary btn-block btn-lg">Show dates</button>
          </div>
        </div>
      </form>
      <div class="offset-sm-2 col-sm-12 well">
        <ul class="list-group">
          <li class="list-group-item list-group-item-info">Start Date : <strong><?php echo $_POST["begdate"] ?></strong> </li>
          <li class="list-group-item list-group-item-info">End Date :<strong> <?php echo $_POST["enddate"] ?></strong></li>
          <li class="list-group-item list-group-item-info">Number of tasks :<strong> <?php echo $_POST["nbtask"] ?></strong> </li>
          <li class="list-group-item list-group-item-info">Week-end : <strong><?php  if($_POST["weekend"] === "false") echo "OFF";elseif($_POST["weekend"] === "true") echo "ON"; ?></strong></li>
        </ul>
      </div>
    <table class="table table-striped">
      <thead>
        <tr>
          <th>Day</th>
          <th>Date</th>
          <th>Task</th>
        </tr>
      </thead>
      <tbody>
        <?php include "EquiTable.php";
        $a = equiDate($_POST["begdate"],$_POST["enddate"], $_POST["nbtask"], $_POST["weekend"]);
        if ($_POST["begdate"] > $_POST["enddate"]) echo "<h3> Error : Verify your dates start date must be before end date !</h3>";
        else {
          $color = "info";
        for ($i=0; $i<count($a); $i++){
          ?>
          <tr class="<?php echo $color ?>">
            <td><?php echo substr($a[$i],0,-11); ?></td>
            <td><?php echo substr($a[$i],-10); ?></td>
            <td><?php echo "task : ".($i+1) ?></td>
          </tr>
        <?php
          if ($color === "info") $color = "default";
          else $color = "info";
          }
        }
          ?>
      </tbody>
    </table>
    </div>
  </body>
</html>
