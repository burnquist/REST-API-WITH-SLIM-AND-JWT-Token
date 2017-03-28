<?php
	class db {

		private $dbhost = "localhost";
		private $dbuser = "root";
		private $password = "123456";
		private $dbname = "customer";
		


		public function connect () {
			$dsn = 'mysql:dbname=$this->dbname;host=$this->dbhost';
			$user = '$this->user';
			$password = '$this->password';

		try {
    	$dbh = new PDO($dsn, $user, $password);
    	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				} catch (PDOException $e) {
    			echo 'Connection failed: ' . $e->getMessage();
				}
		$this->db = $dbh;
		return $dbh;
				}


	}