<?php



function trace($obj, $txt = '', $open = true, $bgcolor = '') {



    $debuginfo = debug_backtrace();
    $dfile = basename($debuginfo[0]['file']);
    $dline = basename($debuginfo[0]['line']);


        $idt = 'trace_' . rand(999, 99999);
        if ($open != true) {
            $open = false;
            if ($txt == '') {
                $txt = 'OPEN TRACE';
            }
        }


        echo '<pre';
        if ($bgcolor != '') {
            echo ' style="background-color:' . $bgcolor . '" ';
        }
        echo '>';

        echo '	<strong onclick="$(\'#' . $idt . '\').toggle();" style="cursor:pointer;">----------------------- ' . $txt . ' | ' . $dfile . ' line ' . $dline;
        if (is_array($obj)) {
            echo ' (' . count($obj) . ' elements) ';
        }
        echo ' -----------------------</strong><br />';

        echo '<div id="' . $idt . '" class="tracevars"';
        if ($open == false) {
            echo 'style="display:none"';
        }
        echo'>';

        print_r(var_dump($obj));
        if (is_object($obj)) {
            $obk = get_object_vars($obj);
            echo implode(',', array_keys($obk));
            echo '<br />'. "'" . implode("','", array_keys($obk)) . "'";
        }


        echo '</div>';

        echo '</pre>';

}



function equiDate($begDate,$endDate,$nbDate,$weekEnd) {

    // get the name of both start and end dates
//    $begDay = substr($begDate,0,-11);
//    $endDay = substr($endDate,0,-11);
//
//    trace($begDay);
//    trace($endDay);

    // Convert the date format into timestamp to be able to calculate the interval between available dates
    $begDate = new dateTime(substr($begDate,-10));
    $endDate = new dateTime(substr($endDate,-10));

      trace($begDate);
    trace($endDate);

    // get the timestamp of boot start and end dates into tmp variables
    $stmBeg = $begDate->getTimestamp();
    $stmEnd = $endDate->getTimestamp();

      trace($stmBeg);
    trace($stmEnd);

    if (!$weekEnd){ // when we have to return dates without weekends
      $diff = (($stmEnd - $stmBeg)+(3600*24))/(3600*24);
      // Calculating the number of dates between the start date and the end date without weekends
      /*$diff = 0;
      while ($stmBeg <= $stmEnd) {
        $dateTmp = new dateTime();
        if ( ($dateTmp->setTimestamp($stmBeg)->format("l") !== "Saturday") &&
         ($dateTmp->setTimestamp($stmBeg)->format("l") !== "Sunday") )
           $diff ++;
        $stmBeg += 86400;
      }*/
      trace($diff,'difference en jours');


      // initialize stmbeg to the start date
      $stmBeg = $begDate->getTimestamp();

      if ($diff > $nbDate) {

        $recordDate = $stmBeg;
        $intervalDate = floor($diff/$nbDate)*3600*24;

        for ($i = 0; $i<$nbDate; $i++){
          $date1 = new DateTime();

          if (($date1->setTimestamp($recordDate)->format("l") !== "Saturday") &&
           ($date1->setTimestamp($recordDate)->format("l") !== "Sunday") )
            $tab[$i] = $date1->setTimestamp($recordDate)->format("l Y-m-d");

          elseif ($date1->setTimestamp($recordDate)->format("l") === "Saturday")
            $tab[$i] = $date1->setTimestamp($recordDate-86400)->format("l Y-m-d");

          elseif ($date1->setTimestamp($recordDate)->format("l") === "Sunday")
            $tab[$i] = $date1->setTimestamp($recordDate+86400)->format("l Y-m-d");

         $recordDate += $intervalDate;
         unset($date1);

        }
      }else {
        echo "DO another stuff !";
      }
       echo '<pre>';
      print_r($tab);
       echo '</pre>';
      echo "Number of days : ".$diff."<br>";
    }
    else { // when we have to return dates with weekends

      // Calculating the number of dates between the start date and the end date (weekends include)
      $diff = (($stmEnd - $stmBeg)+(3600*24))/(3600*24);

      trace($diff,'difference en jours');

      // when the numbers of days we must return is less than the numbers of available days
      if ($diff > $nbDate) {

      // initializing the record date to start date
      $recordDate = $stmBeg;

      // Calculating the interval as number of dates
      $intervalDate = floor($diff / $nbDate)*3600*24;

      // save dates into an Array
      for ($i = 0; $i < $nbDate ; $i++) {
        $date1 = new dateTime();
        $tab[$i] = $date1->setTimestamp($recordDate)->format("l Y-m-d");
        $recordDate += $intervalDate;
        unset($date1);
      }

      // show them
      echo '<pre>';
      print_r($tab);
      echo '</pre>';

      echo "Number of days : ".$diff."<br>";
    }

     // when the numbers of days we must return is more than the numbers of available days
      else {
      echo "do another stuff !";
        }
  }
}


//equiDate("2017-01-01","2017-01-20", 3 , true);
//equiDate("2017-01-01","2017-01-30", 3 , false);

equiDate("2017-01-02","2017-01-30", 20 , true);

echo '<hr />';

equiDate("2017-01-01","2017-01-30", 30 , false);
?>
