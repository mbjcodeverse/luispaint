<?php

class Connection{
	public function connect(){
		// $link = new PDO("mysql:host=localhost;dbname=trilab", "root", "");

		$link = new PDO("mysql:host=localhost;dbname=u911784584_trilab", "u911784584_trilab", "TriLab2026onwards");

		$link -> exec("set names utf8");
		return $link;
	}
}