<?php

class Connection{
	public function connect(){
		$link = new PDO("mysql:host=localhost;dbname=luispaint", "root", "");

		// $link = new PDO("mysql:host=localhost;dbname=u661302059_luispaint", "u661302059_luispaint", "BcdLuisPa1nt!");

		$link -> exec("set names utf8");
		return $link;
	}
}