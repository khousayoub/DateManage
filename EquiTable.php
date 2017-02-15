<?php
function equiTable($begDate,$endDate,$nbDate,$weekEnd) {

    // get the date format as YYYY-MM-DD
    $begDay = substr($begDate,0,-11);
    $endDay = substr($endDate,0,-11);

    // Convert the date format into timestamp to be able to calculate the interval between available dates
    $begDate = new dateTime(substr($begDate,-10));
    $endDate = new dateTime(substr($endDate,-10));
    if ($weekEnd){
      echo "in work !";
    }
    else {
    // Calculating the interval between available dates as timestamp
    $diff = ($endDate->getTimestamp() - $begDate->getTimestamp())/(3600*24);

    // initializing the first date to start date
    $firstDate = $begDate->getTimestamp();

    // Calculating the interval as number of dates
    $intervalDate = floor($diff / $nbDate)*3600*24;

    // save dates into an Array
    for ($i = 0; $i < $nbDate ; $i++) {
      $firstDate += $intervalDate;
      $date1 = new dateTime();
      $tab[$i] = $date1->setTimestamp($firstDate)->format("l Y-m-d");
      unset($date1);
    }

    // show them
    print_r($tab);

    echo $result."<br>";
    echo "Number of days : ".($endDate->getTimestamp() - $begDate->getTimestamp())/(3600*24);
  }
}
equiTable("Wednesday 2017-01-01","Thursday 2017-01-31", 3 , false);
?>
