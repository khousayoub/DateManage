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

    if ($weekEnd === "false"){ // when we have to return dates without weekends
      // $diff content la difference entre la date de debut et la date de fin en timsetamp (3600*24)
      $diff = (($stmEnd - $stmBeg)+(3600*24))/(3600*24);

      // initialize stmbeg to the start date
      $stmBeg = $begDate->getTimestamp();

      // $recordDate contient les dates retournés
      $recordDate = $stmBeg;

      // $intervalDate contient l'interval entre une date retourné et sa succession
      $intervalDate = floor($diff/$nbDate)*3600*24;

      // cas la difference entre les dates est plus grande que le nombre de dates à retourner
      if ($diff >= $nbDate) {

        // boucle par rapport aux dates qu'on doit retourné
        for ($i = 0; $i<$nbDate; $i++){
          // création de l'objet dateTime
          $date1 = new DateTime();
          // si ce n'est pas un weekend on ajoute notre date
          if (($date1->setTimestamp($recordDate)->format("l") !== "Saturday") &&
           ($date1->setTimestamp($recordDate)->format("l") !== "Sunday") )
            $tab[$i] = $date1->setTimestamp($recordDate)->format("Y-m-d");
            // si c'est un Samedi on met la tâche à Vendredi
          elseif ($date1->setTimestamp($recordDate)->format("l") === "Saturday")
            $tab[$i] = $date1->setTimestamp($recordDate-86400)->format("Y-m-d");
            // si c'est un Dimanche on met la tâche à Lundi
          elseif ($date1->setTimestamp($recordDate)->format("l") === "Sunday")
            $tab[$i] = $date1->setTimestamp($recordDate+86400)->format("Y-m-d");
            // on increment notre date par rapport à l'interval qu'on a calculé
         $recordDate += $intervalDate;
         // libérer la variable
         unset($date1);

        }
        // Affichage du tableau
      /*  echo '<pre>';
       trace(print_r($tab),"liste des tâches par date", true, 'green');
        echo '</pre>';
        */
      }
      // when the numbers of days is more than the numbers of available days weekend  OFF
      else {
        // on calcul la difference entre les 2 dates sans prendre en compte les weekends
        $diff = 0;
        while ($stmBeg <= $stmEnd) {
          $dateTmp = new dateTime();
          if ( ($dateTmp->setTimestamp($stmBeg)->format("l") !== "Saturday") &&
           ($dateTmp->setTimestamp($stmBeg)->format("l") !== "Sunday") )
             $diff ++;
          $stmBeg += 86400;
          }
          // nbTaskPerDay calcul le nombre de tâches qui peuvent être répété dans une seul journée
        $nbTaskPerDay = floor($nbDate/$diff) ;
        // dans le cas ou on a une reste sur la division entre la difference et le nombre de jour à retourner
        $rest = $nbDate%$diff;
        // convertir l'interval en timestamp 3600*24
        $intervalDate = $nbTaskPerDay*3600*24;
        // initialiser la variable qui permet de boucler les tâches sur une journée
        $k=0;
        // boucler par rapport aux dates demandés
        for ($i = 0; $i<$diff; $i++) {
          $date1 = new dateTime();
          // on verifie si la premiére date est un weekend
          if ($date1->setTimestamp($recordDate)->format("l") === "Saturday" )
          // dans le cas ou c'est un samedi on pointe sur vendredi
            $recordDate = $recordDate-86400;

          elseif ($date1->setTimestamp($recordDate)->format("l") === "Sunday" )
          // dans le cas ou c'estun dimanche on pointe sur un lundi
            $recordDate = $recordDate+86400;
            // si on a une reste on increment les nommbres de tâche de la journée X par un et décremente le reste
          if ($rest !== 0) {$nbTaskPerDay++;$rest--;}
          // si on a pas de reste on initialse notre variable par le nombre de tâches par journée
          else $nbTaskPerDay = floor($nbDate / $diff);
          // une boucle pour ajouter les dates par rapport au nombre de tâches par journée
          for ($j = 1; $j<=$nbTaskPerDay; $j++){
            $tab[$k] = $date1->setTimestamp($recordDate)->format("Y-m-d");
            // incrementation de l'indice k pour permettre d'avance sur notre tableau retourné
            $k++;
            }
            // on a fini d'ajouter les tâches dans la journée X, on garde toujours la valeur de k
            //pour qu'on puisse ajouter les autres tâches des autres journées dans la table $tab
            // on avance d'une journée (puisqu'on est dans le cas ou toute les journées entre les dates fixés ont des tâches)
          $recordDate += 86400;
          // si on tombe sur un samedi quand on incremente on reste sur la même journée et on reboucle sur elle
          if ($date1->setTimestamp($recordDate)->format("l") === "Saturday" )
            $recordDate = $recordDate-86400;

          unset($date1);
          // on reinitialise la variable nbTaskPerDay
          $nbTaskPerDay = floor($nbDate/$diff);

          }
          /*echo '<pre>';
         trace(print_r($tab),"liste des tâches par date dans le cas ou diff < nbDate weekend OFF", true, 'green');
          echo '</pre>';*/

        }

    }
    else { // when we have to return dates with weekends

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
        $tab[$i] = $date1->setTimestamp($recordDate)->format("Y-m-d");
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
        // ici ça sera la même fonction que celle de weekend OFF sauf qu'on traite pas le cas des weekends
        for ($i = 0; $i<$diff; $i++) {
          $date1 = new dateTime();

          if ($rest !== 0) {$nbTaskPerDay++;$rest--;}
          else $nbTaskPerDay = floor($nbDate / $diff);

          for ($j = 1; $j<=$nbTaskPerDay; $j++){
            $tab[$k] = $date1->setTimestamp($recordDate)->format("Y-m-d");
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
//equiDate("2017-02-20","2017-03-05", 1, true);
/*equiDate("2017-01-02","2017-01-21", 3 , true); // weekend ON $diff > $nbDate
equiDate("2017-01-02","2017-01-09", 20 , true); // weekend ON $diff < $nbDate big numbers
equiDate("2017-01-02","2017-01-15", 3 , false); // weekend OFF $diff > $nbDate
equiDate("2017-01-02","2017-01-05", 10 , false); // weekend OFF $diff < $nbDate cas of no weekend between begDate and endDate
equiDate("2017-01-03","2017-01-06", 5 , false); // weekend OFF $diff < $nbDate cas of weekend between bgDate and endDate
equiDate("2017-01-04","2017-01-07", 10 , false); // weekend OFF $diff < $nbDate cas of weekend in endDate
equiDate("2017-01-07","2017-01-10", 10 , false); // weekend OFF $diff < $nbDate cas of weekend in begDate (Saturday)
equiDate("2017-01-08","2017-01-12", 10 , false); // weekend OFF $diff < $nbDate cas of weekend in begDate (Sunday)*/
