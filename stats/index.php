<?
//mysql connection
$con = mysql_connect("localhost","*","*");
mysql_select_db("*", $con);

?>



<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<link rel="shortcut icon" type="image/ico" href="Tank-Bonus.png" />
		
		<title>BOS STATS</title>
		<style type="text/css" title="currentStyle">
			@import "media/css/demo_page.css";
			@import "media/css/demo_table.css";
		</style>
		<script type="text/javascript" language="javascript" src="media/js/jquery.js"></script>
		<script type="text/javascript" language="javascript" src="media/js/jquery.dataTables.js"></script>
		<script type="text/javascript" charset="utf-8">
			$(document).ready(function() {
				$('#example').dataTable();
			} );
		</script>
	</head>
	<body id="dt_example">
		<div id="container">
<? if ($_GET['user'] == ''){ ?>
	
	
<div class="full_width big">
WOT STATS
</div>	 

<h1>Development</h1>
<p>This module is still under heavy development!!</p>
<p><code><font color="purple"><b>STAT UPLOADER: <a href="http://bostanks.enjin.com/forum/m/9835790/viewthread/5162147-bos-utilities" target="_blank">BOS Utilities</a></b></font></code></p>
			<div id="demo">
<table cellpadding="0" cellspacing="0" border="0" class="display" id="example" width="100%">
	<thead>
		<tr>
			<th>User</th>
			<th>Battles</th>
			<th>Best Tank</th>
			<th>BOS EFF</th>
		</tr>
	</thead>
	<tbody>
<?
$get_tankers = mysql_query("SELECT * FROM a_tstats");
while($tnkr = mysql_fetch_array($get_tankers)){

echo '<tr class="odd gradeA">';
echo '<td><a href="http://bos.mrpg.co/stats/?user='.$tnkr['user'].'">'.$tnkr['user'].'</a></td>
	<td align="center">'.$tnkr['battles'].'</td>
	<td align="center">'.$tnkr['btank'].'</td>
	<td align="center">'.$tnkr['aveeff'].'</td>';
echo '</tr>';
	
	
}
?>

	</tbody>
	<tfoot>
		<tr>
			<th>User</th>
			<th>Battles</th>
			<th>Best Tank</th>
			<th>BOS EFF</th>
		</tr>
	</tfoot>
</table>


<? 
}else{
?>

<div class="full_width big">
	WOT Stats for <? echo $_GET['user']; ?>
</div>

<h1>Development</h1>
<p>This module is still under heavy development!!</p>
<p><code><font color="purple"><b>STAT UPLOADER: <a href="http://bostanks.enjin.com/forum/m/9835790/viewthread/5162147-bos-utilities" target="_blank">BOS Utilities</a></b></font></code></p>

			<div id="demo">
<table cellpadding="0" cellspacing="0" border="0" class="display" id="example" width="100%">
	<thead>
		<tr>
			<th>Tank</th>
			<th>Tier</th>
			<th>Battles</th>
			<th>DPM</th>
			<th>KPB</th>
			<th>BOS EFF</th>
			<th>XVM EFF</th>
		</tr>
	</thead>
	<tbody>



<?php


$str_data = file_get_contents("http://bos.mrpg.co/upload/files/".$_GET['user'].".json");
$data = json_decode($str_data,true);

$efft = 0;
$ead = 0;
$tbattles = 0;
$besttank = array();
$axvm = 0;

$xml = new SimpleXMLElement('<root/>');

foreach($data['tanks'] as $tank => $name){

$akpb = $name['tankdata']['frags'] / $name['tankdata']['battlesCount'];

$winlose = $name['tankdata']['wins'] / $name['tankdata']['battlesCount'];

$axp = $name['tankdata']['xp'] / $name['tankdata']['battlesCount'];

$add = $name['tankdata']['damageDealt'] / $name['tankdata']['battlesCount'];

$adr = $name['tankdata']['damageReceived'] / $name['tankdata']['battlesCount'];

$hmr = ($name['tankdata']['shots'] - $name['tankdata']['hits']) / $name['tankdata']['battlesCount'];

$acp = ($name['tankdata']['capturePoints'] + $name['tankdata']['droppedCapturePoints']) / $name['tankdata']['battlesCount'];

$deths = ($name['tankdata']['battlesCount'] - $name['tankdata']['survivedBattles']) / $name['tankdata']['battlesCount'];

$tlive = ($name['tankdata']['battleLifeTime'] / 60) / $name['tankdata']['battlesCount'];

$det = $name['tankdata']['spotted'] / $name['tankdata']['battlesCount'];

$cap = $name['tankdata']['capturePoints'] / $name['tankdata']['battlesCount'];

$def = $name['tankdata']['droppedCapturePoints'] / $name['tankdata']['battlesCount'];

$dpm = $add / $tlive;

if($name['tankdata']['battlesCount'] >= '30'){ 

$eff = (($add / 4) * $akpb) * $acp + $axp - ($hmr * $deths);

$xvm = ($akpb / $name['tankdata']['battlesCount']) * (350-($name['common']['tier'] * 20)) + ($add * ( .2+ ( .5 / $name['common']['tier']))) + (200 * $name['tankdata']['spotted'] / $name['tankdata']['battlesCount']) + (($name['tankdata']['capturePoints'] + $name['tankdata']['droppedCapturePoints']) * 150 / $name['tankdata']['battlesCount']);




$efft += $eff;
$axvm += $xvm;
$ead += '1';
$tbattles += $name['tankdata']['battlesCount'];

// XML OUTPUT

$bstank = $xml->addChild('stats');
$bstank->addAttribute('tank',$tank);
$bstank->addChild('bos_eff', $eff);
$bstank->addChild('xvm_eff', $xvm);

//=========================


echo '<tr class="odd gradeA">';
echo '<td>'.$tank.'</td>
	<td>'.$name['common']['tier'].'</td>
	<td>'.$name['tankdata']['battlesCount'].'</td>
	<td>'.substr($dpm,0,6).'</td>
	<td>'.substr($akpb,0,4).'</td>
	<td>'.substr($eff,0,6).'</td>
	<td>'.substr($xvm,0,6).'</td>';
echo '</tr>';
}
}

?>
	</tbody>
	<tfoot>
		<tr>
			<th>Tank</th>
			<th>Tier</th>
			<th>Battles</th>
			<th>DPM</th>
			<th>KPB</th>
			<th>BOS EFF</th>
			<th>XVM EFF</th>
		</tr>
	</tfoot>
</table>

<?

function trange($int,$min,$max){
    return ($int>$min && $int<$max);
}

echo '<br /><code><font color="blue">BATTLES: '.$tbattles.'</font> | ';

$oaeff = $efft / $ead;
$oxvm = $axvm / $ead;

echo 'XVM EFF: '.substr($oxvm,0,6).' | ';

if(trange($oaeff,'0','449')){
	echo '<font color="red">BOS EFF: '.substr($oaeff,0,6).'</font> | ';
}
else if(trange($oaeff,'450','649')){
	echo '<font color="orange">BOS EFF: '.substr($oaeff,0,6).'</font> | ';
	}
else if(trange($oaeff,'650','899')){
	echo '<font color="green">BOS EFF: '.substr($oaeff,0,6).'</font> | ';
	}	  
else if(trange($oaeff,'900','1199')){
	echo '<font color="blue">BOS EFF: '.substr($oaeff,0,6).'</font> | ';
	}
else if($oaeff > '1200'){
	echo '<font color="purple">BOS EFF: '.substr($oaeff,0,6).'</font> | ';
	}else{}



echo '<font color="blue">BEST TANK: '.max($besttank).'</font></code>';


//Print XML info
$dom = dom_import_simplexml($xml)->ownerDocument;
$dom->formatOutput = true;
echo '<pre>';
echo htmlspecialchars($dom->saveXML());
echo '</pre>';



mysql_query("INSERT INTO a_tstats (aveeff, btank, battles, user) VALUES ('$oaeff','".max($besttank)."','$tbattles','".$_GET['user']."') ON DUPLICATE KEY UPDATE aveeff='$oaeff', btank='".max($besttank)."', battles='$tbattles'");

?>




<h1>-BOS- Tankers</h1>
<p>Those who have uploaded stats so far...</p>

<div id="demo">
<table cellpadding="0" cellspacing="0" border="0" class="display" id="example" width="100%">
	<thead>
		<tr>
			<th>User</th>
			<th>Battles</th>
			<th>Best Tank</th>
			<th>Efficiency</th>
		</tr>
	</thead>
	<tbody>
<?
$get_tankers = mysql_query("SELECT * FROM a_tstats");
while($tnkr = mysql_fetch_array($get_tankers)){

echo '<tr class="odd gradeA">';
echo '<td><a href="http://bos.mrpg.co/stats/?user='.$tnkr['user'].'">'.$tnkr['user'].'</a></td>
	<td align="center">'.$tnkr['battles'].'</td>
	<td align="center">'.$tnkr['btank'].'</td>
	<td align="center">'.$tnkr['aveeff'].'</td>';
echo '</tr>';
	
	
}
?>

	</tbody>
	<tfoot>
		<tr>
			<th>User</th>
			<th>Battles</th>
			<th>Best Tank</th>
			<th>Efficiency</th>
		</tr>
	</tfoot>
</table>

<? } ?>
</div>
</div>
</div>