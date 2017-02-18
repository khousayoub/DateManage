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
    $begDay = substr($begDate,0,-11);
    $endDay = substr($endDate,0,-11);

    // Convert the date format into timestamp to be able to calculate the interval between available dates
    $begDate = new dateTime(substr($begDate,-10));
    $endDate = new dateTime(substr($endDate,-10));

    // get the timestamp of boot start and end dates into tmp variables
    $stmBeg = $begDate->getTimestamp();
    $stmEnd = $endDate->getTimestamp();

    if (!$weekEnd){ // when we have to return dates with weekends
      $diff = (($stmEnd - $stmBeg)+(3600*24))/(3600*24);

      // initialize stmbeg to the start date
      $stmBeg = $begDate->getTimestamp();
      $recordDate = $stmBeg;
      $intervalDate = floor($diff/$nbDate)*3600*24;

      if ($diff > $nbDate) {

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
      /*  echo '<pre>';
       trace(print_r($tab),"liste des tâches par date", true, 'green');
        echo '</pre>';*/
      }
      // when the numbers of days is more than the numbers of available days weekend  OFF
      else {
        $diff = 0;
        while ($stmBeg <= $stmEnd) {
          $dateTmp = new dateTime();
          if ( ($dateTmp->setTimestamp($stmBeg)->format("l") !== "Saturday") &&
           ($dateTmp->setTimestamp($stmBeg)->format("l") !== "Sunday") )
             $diff ++;
          $stmBeg += 86400;
          }

        $nbTaskPerDay = floor($nbDate/$diff) ;
        $rest = $nbDate%$diff;
        $intervalDate = $nbTaskPerDay*3600*24;
        $k=0;

        for ($i = 0; $i<$diff; $i++) {
          $date1 = new dateTime();
          if ($date1->setTimestamp($recordDate)->format("l") === "Saturday" )
            $recordDate = $recordDate-86400;

          elseif ($date1->setTimestamp($recordDate)->format("l") === "Sunday" )
            $recordDate = $recordDate+86400;

          if ($rest !== 0) {$nbTaskPerDay++;$rest--;}
          else $nbTaskPerDay = floor($nbDate / $diff);

          for ($j = 1; $j<=$nbTaskPerDay; $j++){
            $tab[$k] = $date1->setTimestamp($recordDate)->format("l Y-m-d");
            $k++;
            }
          $recordDate += 86400;
          if ($date1->setTimestamp($recordDate)->format("l") === "Saturday" )
            $recordDate = $recordDate+86400;

          unset($date1);
          $nbTaskPerDay = floor($nbDate/$diff);

          }
          /*echo '<pre>';
         trace(print_r($tab),"liste des tâches par date dans le cas ou diff < nbDate weekend OFF", true, 'green');
          echo '</pre>';*/

        }

    }
    else { // when we have to return dates without weekends

      // Calculating the number of dates between the start date and the end date (weekends include)
      $diff = (($stmEnd - $stmBeg)+(3600*24))/(3600*24);

      // initializing the record date to start date
      $recordDate = $stmBeg;

      // Calculating the interval as number of dates
      $intervalDate = floor($diff / $nbDate)*3600*24;

      // when the numbers of days we must return is less than the numbers of available days
      if ($diff > $nbDate) {


      // save dates into an Array
      for ($i = 0; $i < $nbDate ; $i++) {
        $date1 = new dateTime();
        $tab[$i] = $date1->setTimestamp($recordDate)->format("l Y-m-d");
        $recordDate += $intervalDate;
        unset($date1);
      }

      // show them
      /*echo '<pre>';
     trace(print_r($tab),"liste des tâches par date", true, 'green');
      echo '</pre>';*/
    }

     // when the numbers of days is more than the numbers of available days weekend ON
      else {
        $nbTaskPerDay = floor($nbDate/$diff) ;
        $rest = $nbDate%$diff;
        $intervalDate = $nbTaskPerDay*3600*24;
        $k=0;

        for ($i = 0; $i<$diff; $i++) {
          $date1 = new dateTime();

          if ($rest !== 0) {$nbTaskPerDay++;$rest--;}
          else $nbTaskPerDay = floor($nbDate / $diff);

          for ($j = 1; $j<=$nbTaskPerDay; $j++){
            $tab[$k] = $date1->setTimestamp($recordDate)->format("l Y-m-d");
            $k++;
            }
          $recordDate += 86400;
          unset($date1);
          $nbTaskPerDay = floor($nbDate/$diff);
          }
      /*    echo '<pre>';
         trace(print_r($tab),"liste des tâches par date dans le cas ou diff < nbDate weekend ON", true, 'green');
          echo '</pre>';*/
        }
      }
      return $tab;
  }
// TEST SET

/*equiDate("2017-01-02","2017-01-21", 3 , true); // weekend ON $diff > $nbDate
equiDate("2017-01-02","2017-01-09", 20 , true); // weekend ON $diff < $nbDate big numbers
equiDate("2017-01-02","2017-01-15", 3 , false); // weekend OFF $diff > $nbDate
equiDate("2017-01-02","2017-01-05", 10 , false); // weekend OFF $diff < $nbDate cas of no weekend between begDate and endDate
equiDate("2017-01-03","2017-01-06", 5 , false); // weekend OFF $diff < $nbDate cas of weekend between bgDate and endDate
equiDate("2017-01-04","2017-01-07", 10 , false); // weekend OFF $diff < $nbDate cas of weekend in endDate
equiDate("2017-01-07","2017-01-10", 10 , false); // weekend OFF $diff < $nbDate cas of weekend in begDate (Saturday)
equiDate("2017-01-08","2017-01-12", 10 , false); // weekend OFF $diff < $nbDate cas of weekend in begDate (Sunday)*/
