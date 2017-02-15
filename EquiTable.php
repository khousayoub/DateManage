<?php
function equiTable($begDate,$endDate,$nbDate,$weekEnd) {

    // get the name of both start and end dates
    $begDay = substr($begDate,0,-11);
    $endDay = substr($endDate,0,-11);

    // Convert the date format into timestamp to be able to calculate the interval between available dates
    $begDate = new dateTime(substr($begDate,-10));
    $endDate = new dateTime(substr($endDate,-10));
    if (!$weekEnd){ // when we have to return dates without weekends

      // get the timestamp of boot start and end dates into tmp variables
      $tmpBeg = $begDate->getTimestamp();
      $tmpEnd = $endDate->getTimestamp();
      // Calculating the number of dates between the start date and the end date without weekends
      $cpt = 0;
      while ($tmpBeg <= $tmpEnd) {
        $dateTmp = new dateTime();
        if ( ($dateTmp->setTimestamp($tmpBeg)->format("l") !== "Saturday") &&
         ($dateTmp->setTimestamp($tmpBeg)->format("l") !== "Sunday") )
           $cpt ++;
        $tmpBeg += 86400;
      }
      echo $cpt;
    }
    else { // when we have to return dates with weekends

      // Calculating the number of dates between the start date and the end date (weekends include)
      $diff = (($endDate->getTimestamp() - $begDate->getTimestamp())+(3600*24))/(3600*24);

      // when the numbers of days we must return is less than the numbers of available days
      if ($diff > $nbDate) {

      // initializing the first date to start date
      $firstDate = $begDate->getTimestamp();

      // Calculating the interval as number of dates
      $intervalDate = floor($diff / $nbDate)*3600*24;

      // save dates into an Array
      for ($i = 0; $i < $nbDate ; $i++) {
        $date1 = new dateTime();
        $tab[$i] = $date1->setTimestamp($firstDate)->format("l Y-m-d");
        $firstDate += $intervalDate;
        unset($date1);
      }

      // show them
      print_r($tab);
      echo "Number of days : ".$diff;

    }

     // when the numbers of days we must return is more than the numbers of available days
      else {
      echo "do another stuff !";
        }
  }
}
equiTable("2017-01-01","2017-01-10", 10 , false);
?>
