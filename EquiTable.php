<?php
function equiTable($begDate,$endDate,$nbDate,$weekEnd) {
    $begDay = substr($begDate,0,-11);
    $endDay = substr($endDate,0,-11);

    $begDate = new dateTime(substr($begDate,-10));
    $endDate = new dateTime(substr($endDate,-10));

    $diff = ($endDate->getTimestamp() - $begDate->getTimestamp())/(3600*24);

    $firstDate = $begDate->getTimestamp();

    $intervalDate = floor($diff / $nbDate)*3600*24;

    for ($i = 0; $i < $nbDate ; $i++) {
      $firstDate += $intervalDate;
      $date1 = new dateTime();
      $tab[$i] = $date1->setTimestamp($firstDate)->format("l Y-m-d");
      unset($date1);
    }
    print_r($tab);

    echo $result."<br>";
    echo ($endDate->getTimestamp() - $begDate->getTimestamp())/(3600*24);

}
equiTable("Wednesday 2017-01-01","Thursday 2017-01-31", 3 , true);
?>
