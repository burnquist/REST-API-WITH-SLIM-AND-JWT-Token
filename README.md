# Slim Framework 3 Skeleton Application

Use this skeleton application to quickly setup and start working on a new Slim Framework 3 application. This application uses the latest Slim 3 with the PHP-View template renderer. It also uses the Monolog logger.

This skeleton application was built for Composer. This makes setting up a new Slim Framework application quick and easy.

## Install the Application

Run this command from the directory in which you want to install your new Slim Framework application.

    php composer.phar create-project slim/slim-skeleton [my-app-name]

Replace `[my-app-name]` with the desired directory name for your new application. You'll want to:

* Point your virtual host document root to your new application's `public/` directory.
* Ensure `logs/` is web writeable.

To run the application in development, you can also run this command. 

	php composer.phar start

Run this command to run the test suite

	php composer.phar test

That's it! Now go build something cool.
# REST-API-WITH-SLIM-AND-JWT-Token

# Test method POST : http://localhost/slim/public/index.php/login
# Body : username and password

# Test method POST : http://localhost/slim/public/index.php/get
# status 200 (ok);

# setting database on :
#dir/path : slim/src/config/db.php
#here is code : <?php
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

