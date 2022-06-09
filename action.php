<?php
/*
 * action.php
 *
 * Copyright 2020 Kaki In <kaki@mifamofi.net>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA 02110-1301, USA.
 *
 *
 */

include './shelve.php';
include "./conf.php";
$shelveitem = new Openshelve($conf["database-name"], $conf["database-password"], $conf["database-server"], $conf["database-user"]);

function randomColor() {
	$a='#';
	for ($i=0;$i<6;$i++) {
		$a.=['0','1','2','3','4','5','6','7','8','9','a','b','c','d','e','f'][rand(0, 15)];
	};
	return $a;
};

try {
if (isset($_REQUEST['action'])) {
	if ($_REQUEST['action']=='refresh') {
		echo "setPointsIds([";
		$a = sendrequest("SELECT * FROM Points", $conf["database-server"], $conf["database-user"], $conf["database-password"], $conf["database-name"]);
		$n=0;
		foreach ($a as $point) {
			if ($n) {echo ", ";}
			echo $point['id'];
			$n++;
		};
		echo "]);";
		foreach ($a as $point) {
			$nomType = $shelveitem('Types', 'nom', 'id', $point['type']);
			$colType = $shelveitem('Types', 'couleur', 'id', $point['type']);
			echo "addPoint({$point['longitude']}, {$point['latitude']}, ".var_export($point['name'], true).", '{$point['id']}', ".var_export($nomType, true).", ".var_export($colType, true).");";
		};
		echo "document.getElementById('mapCenter').setAttribute('lon', ".$shelveitem('Centre', 'lon', 'id', 0).");";
		echo "document.getElementById('mapCenter').setAttribute('lat', ".$shelveitem('Centre', 'lat', 'id', 0).");";}
	elseif ($_REQUEST['action']=='refreshTypes') {
		$a = sendrequest("SELECT * FROM Types", $conf["database-server"], $conf["database-user"], $conf["database-password"], $conf["database-name"]);
		echo "setTypes([";
		$n=0;
		foreach ($a as $num=>$type) {
			if ($n>0) {echo ", ";}
			$n++;
			echo "[{$type['id']}, ".var_export($type['nom'], true)."]";
		};
		echo "]);";
	}
	elseif ($_REQUEST['action']=="changePointName" and isset($_REQUEST['pointId']) and isset($_REQUEST['newName'])) {
		$shelveitem->setItem('Points', 'Name', $_REQUEST['newName'], "id", $_REQUEST['pointId']);
		echo "refresh(true);mapview.popup.close();";
	}
	elseif ($_REQUEST['action']=="changeCenterPoint" and isset($_REQUEST['lat']) and isset($_REQUEST['lon'])) {
		$shelveitem->setItem('Centre', 'lon', $_REQUEST['lon'], 'id', 0);
		$shelveitem->setItem('Centre', 'lat', $_REQUEST['lat'], 'id', 0);
		echo "refresh(true);";
	}
	elseif ($_REQUEST['action']=="refreshFirst") {
		echo "mapview.goTo({center: [".$shelveitem('Centre', 'lon', 'id', 0).",".$shelveitem('Centre', 'lat', 'id', 0)."], 
                             zoom: 18}, {duration: 1000});refresh();";
	}
	elseif ($_REQUEST['action']=="addPoint" and isset($_REQUEST['lat']) and isset($_REQUEST['lon']) and isset($_REQUEST['name']) and isset($_REQUEST['type'])) {
		$shelveitem->addItem('Points', ['longitude'=>$_REQUEST['lon'], 'latitude'=>$_REQUEST['lat'], 'name'=>$_REQUEST['name'], 'type'=>$_REQUEST['type']]);
		echo "refresh(true);";
	}
	elseif ($_REQUEST['action']=="deletePoint" and isset($_REQUEST['pointId'])) {
		$shelveitem->delItem('Points', "id", $_REQUEST['pointId']);
		echo "refresh(true);";
	}
	elseif ($_REQUEST['action']=="addType" and isset($_REQUEST['name'])) {
		if ($shelveitem('Types', 'nom', 'nom', $_REQUEST['name'])==$_REQUEST['name']) {
			echo "alert('Un script du même nom existe déjà');document.getElementById('askTypeName').status='failed';";
		}
		else {
			$col = randomColor();
			while ($shelveitem('Types', 'couleur', 'couleur', $col)==$col) {
				$col=randomColor();
			};
			$shelveitem->addItem('Types', ['nom'=>$_REQUEST['name'], 'couleur'=>$col]);
			echo "refreshTypes();";
		};
	}
	elseif ($_REQUEST['action']=="changePointType" and isset($_REQUEST['pointId']) and isset($_REQUEST['newType'])) {
		$shelveitem->setItem('Points', 'type', $_REQUEST['newType'], 'id', $_REQUEST['pointId']);
		echo 'refresh(true)';
	};
};
}
catch (Exception $e) {echo "alert('Impossible de se connecter au serveur. Veuillez vérifier la configuration');";die;}

?>
