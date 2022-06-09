<?php
/*
 * changepins.php
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

class Openshelve{
	private $dbname;
	private $password;
	private $servname;
	private $username;

	public function __construct ($dbname, $password, $servname, $username) {
		$this->dbname=$dbname;
		$this->password=$password;
		$this->servname=$servname;
		$this->username=$username;
	}

	public function __invoke ($table, $key, $value, $valueval) {
		try{
			$dbco = new PDO("mysql:host=".$this->servname.";dbname=".$this->dbname, $this->username, $this->password);
			$dbco->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			$sth = $dbco->prepare("
				SELECT ".$key." FROM ".$table."
				WHERE ".$value."=".var_export($valueval, true));
			$sth->execute();
			$resultat = $sth->fetchAll(PDO::FETCH_ASSOC);
			if ($resultat) {
				return $resultat[0][$key];
			}
		}

		catch(Exception $e){
			throw $e;
		}
	}

	public function setItem ($table, $newvalue, $newvalueval, $value, $valueval) {
		try{
			$dbco = new PDO("mysql:host=".$this->servname.";dbname=".$this->dbname, $this->username, $this->password);
			$dbco->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			//On prépare la requête et on l'exécute
			$txt="
			  UPDATE ".$table."
			  SET ".$newvalue."=".var_export($newvalueval, true)."
			  WHERE ".$value."=".var_export($valueval, true)."
			";

			$sth = $dbco->prepare($txt);
			$sth->execute();

			//On affiche le nombre d'entrées mise à jour
			return $sth->rowCount();
		}

		catch(PDOException $e){
			throw $e;
		}
	}

	public function additem ($table, $array) {
		try{
			$dbco = new PDO("mysql:host=".$this->servname.";dbname=".$this->dbname, $this->username, $this->password);
			$dbco->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			$alls="(";
			$vals="(";
			foreach ($array as $key=>$valueof){
				$alls.=$key;
				$alls.=',';

				$vals.=var_export($valueof, true);
				$vals.=',';
			}

			$alls[-1]=')';
			$vals[-1]=')';

			$sql = "INSERT INTO ".$table.$alls."
					VALUES ".$vals;

			$dbco->exec($sql);
		}

		catch(PDOException $e) {
			throw $e;
		}
	}

	public function delitem ($table, $value, $valueval) {
		try{
			$dbco = new PDO("mysql:host=".$this->servname.";dbname=".$this->dbname, $this->username, $this->password);
			$dbco->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			$sql = "DELETE FROM $table WHERE $value='$valueval'";
			$sth = $dbco->prepare($sql);
			$sth->execute();

			$count = $sth->rowCount();
			return $count;
		}

		catch(PDOException $e){
			throw $e;
		}
	}
}

function sendrequest($request, $servname, $user, $pass, $dbname, $retour=true) {
	try{
		$dbco = new PDO("mysql:host=$servname;dbname=$dbname", $user, $pass);
		$dbco->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		/*Sélectionne toutes les valeurs dans la table users*/
		$sth = $dbco->prepare($request);
		$sth->execute();

		/*Retourne un tableau associatif pour chaque entrée de notre table
		 *avec le nom des colonnes sélectionnées en clefs*/
		if ($retour) { $resultat = $sth->fetchAll(PDO::FETCH_ASSOC);}

		/*print_r permet un affichage lisible des résultats,
		 *<pre> rend le tout un peu plus lisible*/
		return $resultat;
	}

	catch(PDOException $e){
		throw $e;
	}
}

?>
