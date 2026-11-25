<?php
declare(strict_types=1);

use \TRP\BeaverQuery\Statement as BQS;
\TRP\BeaverQuery\BeaverQuery::setDefaultDatabase(DBBASE);

class DB {
	private static ?mysqli $conn = null;
	
	private static function init() : void {
		if(!self::$conn) {
			self::$conn = new mysqli(DBHOST,DBUSER,DBPASS,DBBASE);
			self::$conn->set_charset('utf8mb4');
		}
	}

	public static function query(BQS\Select $sql) : mysqli_result|true {
		self::init();
		return self::$conn->query($sql->print());
	}
}
