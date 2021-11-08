<?php
$andmed=simplexml_load_file('andmeteBaas.xml');

//andmete salvestamine xml faili, kus andmed lisatakse juurde

/*
 * XML andmete salvestamine PHP abil.

kasuta kursus metshein.com-> ANDMEVAHETUSVORMINGUD (teema: XML andmete salvestamine PHP abil)

https://www.metshein.com/unit/xml-xml-andmete-salvestamine-php-abil/

tee läbi oma andmeteBaas.xml failiga järgmised ülesanded
*andmete salvestamine XML faili, kus igakord luuakse uus fail
*andmete salvestamine XML faili, kus andmed lisatakse juurde
*vormist saadud andmete lisamine XML faili*/
if(isSet($_POST['submit'])){

    $toodenimi=$_POST['nimi'];
    $toodehind=$_POST['hind'];
    $toodevarv=$_POST['varv'];
    $lisanimi=$_POST['lnimi'];


    $xml_tooded=$andmed->addChild('toode');
    $xml_tooded->addChild('nimi', $toodenimi);
    $xml_tooded->addChild('hind', $toodehind);
    $xml_tooded->addChild('varv', $toodevarv);

    $lisad=$xml_tooded->addChild('lisad');
    $lisad->addChild('nimi', $lisanimi);

    $xmlDoc = new DOMDocument("1.0", "UTF-8");
    $xmlDoc->loadXML($andmed->asXML(), LIBXML_NOBLANKS);
    $xmlDoc->formatOutput=true;
    $xmlDoc->preserveWhiteSpace = false;
    $xmlDoc->save('andmeteBaas.xml');
    header("refresh: 0;");
}

// Otsing toode nimi järgi

function searchByName($query){
    global $andmed;
    $result=array();
    foreach($andmed->toode as $toode){
        if(substr(strtolower($toode->nimi), 0,
    strlen($query)) == strtolower($query))
            array_push($result, $toode);
    }
    return $result;

}
?>
<!DOCTYPE html>
<html lang="et">
    <head>
        <title>XML andmete lugemine PHP abil</title>
    </head>
<body>
<h1>XML andmete lugemine PHP abil</h1>
<h3>Esimese toode nimi:</h3>
<?php
echo $andmed->toode[0]->nimi;
?>
<table>
    <tr>
        <th>Toodenimi</th>
        <th>Hind</th>
        <th>Värv</th>
        <th>Lisade nimi</th>
        <th>Lisade suurus</th>
    </tr>
    <?php
    foreach ($andmed->toode as $toode){
        echo "<tr>";
        echo "<td>".($toode->nimi)."</td>";
        echo "<td>".($toode->hind)."</td>";
        echo "<td>".($toode->varv)."</td>";
        echo "<td>".($toode->lisad->nimi)."</td>";
        echo "<td>".($toode->lisad->suurus)."</td>";
        echo "</tr>";
    }
    ?>
</table>
<h2>Otsing toodenimi järgi</h2>
<form action="?" method="post">
    <label for="otsing">otsing:</label>
    <input type="text" id="otsing" name="otsing" placeholder="toode nimi">
    <input type="submit" value="Otsi">
</form>
<ul>
<?php
if(!empty($_POST["otsing"])){
    $result=searchByName($_POST["otsing"]);
    foreach ($result as $toode){
        echo "<li>";
        echo $toode->nimi. ", ". $toode->hind;
        echo "</li>";
      }
}

?>
</ul>
<h1>Vormist saadud andmete lisamine XML faili</h1>
<form method="post" action="">
    <label for="nimi">Toode nimi</label>
    <input type="text" id="nimi" name="nimi">
    <br>
    <label for="hind">Toode hind</label>
    <input type="text" id="hind" name="hind">
    <br>
    <label for="värv">Toode värv</label>
    <input type="text" id="värv" name="värv">
    <br>
    <label for="lnimi">Lisa nimi</label>
    <input type="text" id="lnimi" name="lnimi">
    <input type="submit" value="Sisesta" id="submit" name="submit">

</form>
</body>
</html>
