<?php
// Je hebt een database nodig om dit bestand te gebruiken....
require "database.php";
if (!isset($db_conn)) { //deze if-statement checked of er een database-object aanwezig is. Kun je laten staan.
    return;
}

$database_gegevens = null;
$poolIsChecked = false;
$bathIsChecked = false;
$bbqIsChecked = false;
$wifiIsChecked = false;
$fireplaceIsChecked = false;
$dishwasherIsChecked = false;
$bikeIsChecked = false;


$sql = "SELECT * FROM `homes`"; //Selecteer alle huisjes uit de database


if (isset($_GET['filter_submit'])) {

    if ($_GET['faciliteiten'] == "ligbad") { // Als ligbad is geselecteerd filter dan de zoekresultaten
        $bathIsChecked = true;

        $sql = "SELECT * FROM `homes` WHERE bath_present = 1 "; // query die zoekt of er een BAD aanwezig is.
    }

    if ($_GET['faciliteiten'] == "zwembad") {
        $poolIsChecked = true;

        $sql = "SELECT * FROM `homes` WHERE pool_present = 1"; // query die zoekt of er een ZWEMBAD aanwezig is.
    }
    if ($_GET['faciliteiten'] == "bbq") {
        $bbqIsChecked = true;

        $sql = "SELECT * FROM `homes` WHERE bbq_present = 1"; // query die zoekt of er een ZWEMBAD aanwezig is.
    }
    if ($_GET['faciliteiten'] == "wifi") {
        $wifiIsChecked = true;

        $sql = "SELECT * FROM `homes` WHERE wifi_present = 0"; // query die zoekt of er een ZWEMBAD aanwezig is.
    }
    if ($_GET['faciliteiten'] == "fireplace") {
        $fireplaceIsChecked = true;

        $sql = "SELECT * FROM `homes` WHERE fireplace_present = 1"; // query die zoekt of er een ZWEMBAD aanwezig is.
    }
    if ($_GET['faciliteiten'] == "dishwasher") {
        $dishwasherIsChecked = true;

        $sql = "SELECT * FROM `homes` WHERE dishwasher_present = 0"; // query die zoekt of er een ZWEMBAD aanwezig is.
    }
    if ($_GET['faciliteiten'] == "bike") {
        $dishwasherIsChecked = true;

        $sql = "SELECT * FROM `homes` WHERE bike_rental = 1"; // query die zoekt of er een ZWEMBAD aanwezig is.
    }
}



if (is_object($db_conn->query($sql))) { //deze if-statement controleert of een sql-query correct geschreven is en dus data ophaalt uit de DB
    $database_gegevens = $db_conn->query($sql)->fetchAll(PDO::FETCH_ASSOC); //deze code laten staan
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>    

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==" crossorigin="" />
    <!-- Make sure you put this AFTER Leaflet's CSS -->
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js" integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==" crossorigin=""></script>
    <link href="css/index.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css2?family=Staatliches&display=swap" rel="stylesheet">
</head>

<body>
    <header>
        <h1>Quattro Cottage Rental</h1>
    </header>
    <main>
        <div class="left">
        <div class="filter-box">
                <form class="filter-form">
                    <div class="form-control">
                        
                        <button type="submit" name="reset filters">reset filters</button>
                    </div>
                    <div class="form-control">
                        <label for="ligbad">Ligbad</label>
                        <input type="radio" id="ligbad" name="faciliteiten" value="ligbad" <?php if ($bathIsChecked) echo 'checked' ?>>
                    </div>
                    <div class="form-control">
                        <label for="zwembad">Zwembad</label>
                        <input type="radio" id="zwembad" name="faciliteiten" value="zwembad" <?php if ($poolIsChecked) echo 'checked' ?>>
                    </div>
                    <div class="form-control">
                        <label for="bbq">bbq</label>
                        <input type="radio" id="bbq" name="faciliteiten" value="bbq" <?php if ($bbqIsChecked) echo 'checked' ?>>
                    </div>
                    <div class="form-control">
                        <label for="wifi">wifi</label>
                        <input type="radio" id="wifi" name="faciliteiten" value="wifi" <?php if ($wifiIsChecked) echo 'checked' ?>>
                    </div>
                    <div class="form-control">
                        <label for="fireplace">fireplace</label>
                        <input type="radio" id="fireplace" name="faciliteiten" value="fireplace" <?php if ($fireplaceIsChecked) echo 'checked' ?>>
                    </div>
                    <div class="form-control">
                        <label for="bike">bike</label>
                        <input type="radio" id="bike" name="faciliteiten" value="bike" <?php if ($bikeIsChecked) echo 'checked' ?>>
                    </div>
                    <div class="form-control">
                        <label for="dishwasher">dishwasher</label>
                        <input type="radio" id="dishwasher" name="faciliteiten" value="dishwasher" <?php if ($dishwasherIsChecked) echo 'checked' ?>>
                    </div>
           
                    <button type="submit" name=" filter_submit"> Filter</button>
                </form>
        </div>
            
            <div id="mapid"></div>
        </div>
        <div class="right">

       
                <div class="homes-box">
                    <?php if (isset($database_gegevens) && $database_gegevens != null) : ?>
                        <?php foreach ($database_gegevens as $huisje) : ?>
                            <h4>
                                <?php echo $huisje['name']; ?>
                                <?php echo $huisje['price_p_p_p_n']; ?> 
                                <?php echo $huisje['price_bed_sheets']; ?> 
                                <?php echo $huisje['id']; ?> 

                                
                            </h4>
                            
                          <?php  $huisjes = array (
                          array( $huisje['id'],$huisje['price_bed_sheets'],$huisje['price_p_p_p_n'])
                        
                          ); print_r($huisjes); 

                        ?>
                            <p>
                                <?php echo $huisje['description'] ?>
                            </p>
                            <div class="kenmerken"> 
                                <h6>Kenmerken</h6>
                                <ul>
                            
                                    <?php
                                    if ($huisje['bath_present'] ==  1) {
                                        echo "<li>Er is ligbad!</li>";?>
                                         
                                   
                                    

                                    <?php
                                    }

                                    
                                    ?>





                                    <?php
                                    if ($huisje['pool_present'] ==  1) {
                                        echo "<li>Er is zwembad!</li>";?>
                                
                                        <?php
                                    }
                                    ?>

                                 <?php
                                    if ($huisje['bath_present'] ==  1) {
                                        echo "<li>Er is bbq!</li>";
                                    }
                                    ?>
                                   <?php
                                    if ($huisje['wifi_present'] ==  1) {
                                        echo "<li>Er is wifi!</li>";
                                    }
                                    ?>
                                     <?php
                                    if ($huisje['fireplace_present'] ==  1) {
                                        echo "<li>Er is fireplace!</li>";
                                    }
                                    ?>
                                     <?php
                                    if ($huisje['dishwasher_present'] ==  1) {
                                        echo "<li>Er is dishwasher!</li>";
                                    }
                                    ?>
                                     <?php
                                    if ($huisje['bike_rental'] ==  1) {
                                        echo "<li>Er is bike!</li>";
                                    }
                                        if ($huisje['bike_rental'] ==  1) {
                                    echo "<li>Fiets huren kost: €15,- </li>";
                                    }
                                    ?>
                                    
                                </ul>

                            </div>
                              <img src="images/<?php echo $huisje[ 'image'] ?>">
                                                      <?php endforeach; ?>
                    <?php endif; ?>
                </div>
<div class="book">
    <form method="get" action="">
                <h3>Reservering maken</h3>
                <div class="form-control">
                    <label for="aantal_personen">Vakantiehuis</label>
                    <select name="gekozen_huis" id="gekozen_huis">
                        <option value="1">IJmuiden Cottage</option>
                        <option value="2">Assen Bungalow</option>
                        <option value="3">Espelo Entree</option> 
                        <option value="4">Weustenrade Woning</option>
                    </select>
                </div>
                <div class="form-control">
                    <label for="aantal_personen">Aantal personen</label>
                    <input type="number" name="aantal_personen" id="aantal_personen">
                </div>
                <div class="form-control">
                    <label for="aantal_dagen">Aantal dagen</label>
                    <input type="number" name="aantal_dagen" id="aantal_dagen">
                    <
                </div>
                <div class="form-control">
                    <h5>Beddengoed</h5>
                    <label for="beddengoed_ja">Ja</label>
                    <input type="radio" id="beddengoed_ja" name="beddengoed" value="ja">
                    <label for="beddengoed_nee">Nee</label>
                    <input type="radio" id="beddengoed_nee" name="beddengoed" value="nee">
                </div>
                <button type="submit"> Reserveer huis</button>

            </div>

            <div class="currentBooking">
                <div class="bookedHome"></div>
                <div class="totalPriceBlock">Totale prijs &euro;<span class="totalPrice">0.00</span></div>
                
            </div>
            </div>
         </form>
        </div>
    </main>
    <footer>
        <div></div>
        <div>copyright Quattro Rentals BV.</div>
        <div></div>

    </footer>
    <script src="js/map_init.js"></script>
    <script>
        // De verschillende markers moeten geplaatst worden. Vul de longitudes en latitudes uit de database hierin
        
        
var coordinates = [
            [52.44902, 4.61001],[52.99864,6.64928],[52.30340,6.36800],[50.89720,5.90979]
        ];

        var bubbleTexts = [2
            "Ijmuiden cottage", "Assen bungalo", "Espolo entree", "Weustenrade woning"
        ];    

    </script>
    <script src="js/place_markers.js"></script>
</body>
        
</html>
