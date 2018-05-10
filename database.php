<?phpๅๅๅๅ

class Database {
	private $host="localhost";
	private $username="it57160605";
	private $password="suwankhetnikom";
	private $dbname="it57160605";
	public 	$link;
	public 	$results;

	function connect(){
		if ($this->link = new PDO("mysql:host=$this->host; dbname=$this->dbname",
									$this->username,$this->password)){
			$this->link->exec("SET CHARACTER SET utf8");
			return true;
		}else {
			// echo "Could not linkect to the database!!";
			return false;
		}
	}

	function query($sql){
		if ($this->results=$this->link->query($sql)) {
			return true;
		}else{
			// echo "Could not query data form database";
			return false;
		}
	}

	function close(){
		$this->link = null;
	}

}

?>
