<?php

$uus_fail=(isset($_POST["uus_fail"])) && $_POST["uus_fail"];
//XML andmete salvestamine  uusBaas.xml
if(isset($_POST['submit']) && $uus_fail){
    $xmlDoc = new DOMDocument("1.0","UTF-8");
    $xmlDoc->preserveWhiteSpace = false;
    $xmlDoc->formatOutput = true;

    $xml_root = $xmlDoc->createElement("tooted");
    $xmlDoc->appendChild($xml_root);

    $xml_toode = $xmlDoc->createElement("toode");
    $xmlDoc->appendChild($xml_toode);

    $xml_root->appendChild($xml_toode);

    $xml_toode->appendChild($xmlDoc->createElement('nimi', $_POST['nimi']));
    $xml_toode->appendChild($xmlDoc->createElement('hind',$_POST['hind']));
    $xml_toode->appendChild($xmlDoc->createElement('varv',$_POST['varv']));

    $lisad=$xml_toode->appendChild($xmlDoc->createElement('lisad'));
    $lisad->appendChild($xmlDoc->createElement('nimi', $_POST['lnimi']));

    $xmlDoc->save('tooted.xml');
}


//XML andmete täiendamine
if(isset($_POST['submit']) && !$uus_fail){
    $xmlDoc = new DOMDocument("1.0","UTF-8");
    $xmlDoc->preserveWhiteSpace = false;
    $xmlDoc->load('andmeteBaas.xml');
    $xmlDoc->formatOutput = true;

    $xml_root = $xmlDoc->documentElement;
    $xmlDoc->appendChild($xml_root);

    $xml_toode = $xmlDoc->createElement("toode");
    $xmlDoc->appendChild($xml_toode);

    $xml_root->appendChild($xml_toode);

    $xml_toode->appendChild($xmlDoc->createElement('nimi', $_POST['nimi']));
    $xml_toode->appendChild($xmlDoc->createElement('hind',$_POST['hind']));
    $xml_toode->appendChild($xmlDoc->createElement('varv',$_POST['varv']));
    $lisad=$xml_toode->appendChild($xmlDoc->createElement('lisad'));
    $lisad->appendChild($xmlDoc->createElement('nimi', $_POST['lnimi']));


    $xmlDoc->save('andmeteBaas.xml');
}
$andmed=simplexml_load_file('andmeteBaas.xml');

?>
<!DOCTYPE html>
<html lang="et">
<head>
    <title>XML andmete lugemine PHP abil</title>
</head>
<body>
<?php
// lisamisvorm html failist
include('lisamisvorm.html');
?>
<h1>Andmed andmeteBaas.xml failist</h1>
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
<h2>RSS uudised</h2>
<ul>
<?php
$feed= simplexml_load_file('https://www.postimees.ee/rss');
$linkide_arv=10;
$loendur=1;
foreach($feed->channel->item as $item){
    if($loendur<=$linkide_arv){
        echo "<li>";
        echo "<a href='$item->link' target='_blank'>$item->title</a>";
        echo "</li>";
        $loendur++;
    }
}

// KODUS:
// lisada +CSS kujundus,
// logiliselt linkide paigutus - otsing, salvestamine + checkbox = töötav leht
// kui leht on valmis-> siis link õpetajale
?>
</ul>

</body>
</html>
