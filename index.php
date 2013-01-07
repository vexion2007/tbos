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
echo '<td>'.$tnkr['user'].'</td>
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

	
?>
<script>
        tankLvl.battle_count = 0;

        for(var i = 1; i <= 10; i++) {
            tankLvl[i] = { battle_count: 0 };
        }

        data.vehicles.forEach(function(item) {
            tankLvl[item.level].battle_count += item.battle_count;
            tankLvl.battle_count += item.battle_count;
        });

        for(var j = 1; j <= 10; j++) {
            mid += j * tankLvl[j].battle_count / tankLvl.battle_count;
        }

        if(battlesCount !== 0) {
            var battles = data.battles,
                dmg = <?php echo json_encode($add); ?>,
                des = <?php echo json_encode($akpb); ?>,
                det = <?php echo json_encode($det); ?>,
                cap = <?php echo json_encode($cap); ?>,
                def = <?php echo json_encode($def); ?>;

            return Math.round((dmg * (10 / mid) * (0.15 + mid / 50) + des * (0.35 - mid / 50)
                * 1000 + det * 200 + cap * 150 + def * 150) / 10, 0) * 10;
        } else {
            return 0;
        }
    };

    var getVehicleType = function(vclass) {
        switch(vclass.toLowerCase()) {
            case "lighttank": return "LT";
            case "mediumtank": return "MT";
            case "heavytank": return "HT";
            case "at-spg": return "TD";
            case "spg": return "SPG";
            default: return "unknown";
        }
    };

    // log
    var log = function(str) {
        var now = new Date();
        var s = now.getFullYear() + "-" +
            (now.getMonth() < 9 ? "0" : "") + (now.getMonth() + 1) + "-" +
            (now.getDate() < 10 ? "0" : "") + now.getDate() + " " +
            now.toLocaleTimeString();
        console.log(s + ": " + str);
    };

    // debug
    var debug = function(str) {
        log("DEBUG: " + str);
    };

    // exports
    return {
        calculateEfficiency: calculateEfficiency,
        getVehicleType: getVehicleType,
        log: log,
        debug: debug
    }
})();

</script>

<?








if($name['tankdata']['battlesCount'] >= '30'){ 

$eff = (($add / 4) * $akpb) * $acp + $axp - ($hmr * $deths);

$xvm = ($akpb / $name['tankdata']['battlesCount']) * (350-($name['common']['tier'] * 20)) + ($add * ( .2+ ( .5 / $name['common']['tier']))) + (200 * $name['tankdata']['spotted'] / $name['tankdata']['battlesCount']) + (($name['tankdata']['capturePoints'] + $name['tankdata']['droppedCapturePoints']) * 150 / $name['tankdata']['battlesCount']);




$efft += $eff;
$axvm += $xvm;
$ead += '1';
$tbattles += $name['tankdata']['battlesCount'];

$besttank = array($eff => 'eff', $tank => 'tank');


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
	echo '<font color="red">AVE EFF: '.substr($oaeff,0,6).'</font> | ';
}
else if(trange($oaeff,'450','649')){
	echo '<font color="orange">AVE EFF: '.substr($oaeff,0,6).'</font> | ';
	}
else if(trange($oaeff,'650','899')){
	echo '<font color="green">AVE EFF: '.substr($oaeff,0,6).'</font> | ';
	}	  
else if(trange($oaeff,'900','1199')){
	echo '<font color="blue">AVE EFF: '.substr($oaeff,0,6).'</font> | ';
	}
else if($oaeff > '1200'){
	echo '<font color="purple">AVE EFF: '.substr($oaeff,0,6).'</font> | ';
	}else{}



echo '<font color="blue">BEST TANK: '.max($besttank).'</font></code>';

$xml = new SimpleXMLElement('<root/>');

array_walk_recursive($besttank, array ($xml, 'addChild'));

echo '<pre>';
echo htmlentities($xml->asXML());


echo json_encode($besttank);
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