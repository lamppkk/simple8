<?php
	class database {
		private $user = "root";
		private $password = "";
		private $host = "localhost";
		private $database = "simple8";

		//Kết nối Database
		function connection() {
			$connect= new mysqli($this->host, $this->user, $this->password, $this->database);
			if($connect->connect_error){
				die("connection is error");
			} return $connect;
		}

		function disconnection() {
			$connect = $this->connection();
			$connect->close();
		}
		function __construct(){
			$this->connection();
		}
		function printData($query){
			$connect = $this->connection();
			$result = mysqli_query($connect, $query);
			$arr = [];
			//Chuyển SQL sang mảng
			while($row = mysqli_fetch_assoc($result)) {
				$arr[] = $row;
			}
			return $arr;
		}

		//Xuất Table SQL ra JSON
		function SQLtoJSON($query, $nameFile) {
			$connect = $this->connection();
			$json_array = $this->printData($query);

			//Chuyển mảng thành JSON, có Unicode và format đẹp
			$json = json_encode($json_array,\JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
			mysqli_close($connect);

			//Ghi vào file simple8.json
			$fp = fopen('database/'.$nameFile.'.json', 'w');
			fwrite($fp, $json);
			fclose($fp);
		}

		function getOutArr($arr, $index) {
			$newArr = [];
			foreach($arr as $key=>$value) {
				$newArr[] = $arr[$key][$index];
			}
			return $newArr;
		}

		/*Xuất table JSON ra cột JSON
		function tableToColumn($path, $item) {
		$string = file_get_contents($path);
		$jsond = json_decode($string, true);
		$arrImage = [];
		foreach($jsond as $key=>$value) {
			$arrImage[] = $jsond[$key][$item];
		}
		$json = json_encode($arrImage,\JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
		$fp = fopen('database/'.$item.'-URL.json', 'w');
		fwrite($fp, $json);
		fclose($fp);
		}*/
		function __destruct(){
			$this->disconnection();
		}
	}