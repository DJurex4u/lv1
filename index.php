    <?php
    include('dataManager.php');

    $url = "https://stup.ferit.hr/index.php/zavrsni-radovi/page/2";
    $dataManager = new dataManager($url);
    $dataManager->fetchData();
    echo $dataManager->getDiplomskiRadovi()->read();    
    ?>