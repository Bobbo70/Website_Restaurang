<?php
require_once ("/web/se/ww2.restaurangkarl.se/resources/dbconfig.php");

class MENU
{	
	private $conn;
	private $table_menu = "lunchmenu";
	private $table_email = "email_newsletter";
	
	public function __construct(){
		$database = new Database();
		$db = $database->dbConnection();
		$this->conn = $db;
    }
	
	public function runQuery($sql){
		$stmt = $this->conn->prepare($sql);
		return $stmt;
	}
	
	public function lasdID(){
		$stmt = $this->conn->lastInsertId();
		return $stmt;
	}
	
	// Register new menu
	public function register($weekNumber,$datum,$veckodag,$alt_1,$alt_2,$alt_veg,$sallad,$pasta){ 
	
		try
		{	
			$stmt = $this->conn->prepare("INSERT INTO " . $this->table_menu . " 
											(vecka, veckodag, datum, alt_1, alt_2, alt_veg, alt_sallad, veckans_pasta) 
		                                 VALUES
											(:vecka, :veckodag, :datum, :alt_1, :alt_2, :alt_veg, :alt_sallad, :pasta)");				
			for ($i=0; $i<=4;)
			{
				$date = date('Y-m-d', $datum); 
				$datum = strtotime('+1 day', $datum);

				// bind parameters			
				$stmt->bindparam(':vecka', $weekNumber, PDO::PARAM_STR);
				$stmt->bindparam(':veckodag', $veckodag[$i], PDO::PARAM_STR);
				$stmt->bindParam(':datum', $date, PDO::PARAM_STR);
				$stmt->bindparam(':alt_1', $alt_1[$i], PDO::PARAM_STR);
				$stmt->bindparam(':alt_2', $alt_2[$i], PDO::PARAM_STR);
				$stmt->bindparam(':alt_veg', $alt_veg[$i], PDO::PARAM_STR);
				$stmt->bindparam(':alt_sallad', $sallad[$i], PDO::PARAM_STR);
				$stmt->bindparam(':pasta', $pasta, PDO::PARAM_STR);
				

				$stmt->execute();
				$i++;
			}		
			return $stmt;
		}
		catch(PDOException $ex){
			echo $ex->getMessage();
		}
	}
	
	// update lunch dishes
	public function updatemenu($id,$weekNumber,$veckodag,$alt_1,$alt_2,$alt_veg,$sallad,$pasta){	
	
		try
		{
			$stmt = $this->conn->prepare("UPDATE " . $this->table_menu . " 
											SET
												vecka=:vecka,
												veckodag=:veckodag,
												alt_1=:alt_1,
												alt_2=:alt_2,
												alt_veg=:veg,
												alt_sallad=:alt_sallad,
												veckans_pasta=:pasta												
											WHERE 
												id=:id");
			// posted values					
			$this->vecka=htmlspecialchars(strip_tags($this->vecka));
			$this->veckodag=htmlspecialchars(strip_tags($this->veckodag));
			$this->alt_1=htmlspecialchars(strip_tags($this->alt_1));
			$this->alt_2=htmlspecialchars(strip_tags($this->alt_2));
			$this->alt_veg=htmlspecialchars(strip_tags($this->alt_veg));
			$this->alt_sallad=htmlspecialchars(strip_tags($this->alt_sallad));
			$this->veckans_pasta=htmlspecialchars(strip_tags($this->veckans_pasta));			
			
			// bind parameters
			$stmt->bindparam(':vecka',$weekNumber, PDO::PARAM_STR);
			$stmt->bindparam(':veckodag',$veckodag, PDO::PARAM_STR);
			$stmt->bindparam(':alt_1',$alt_1, PDO::PARAM_STR);
			$stmt->bindparam(':alt_2',$alt_2, PDO::PARAM_STR);
			$stmt->bindparam(':veg',$alt_veg, PDO::PARAM_STR);
			$stmt->bindparam(':alt_sallad',$sallad, PDO::PARAM_STR);
			$stmt->bindparam(':pasta',$pasta, PDO::PARAM_STR);			
			$stmt->bindparam(':id',$id, PDO::PARAM_INT);
		
			$stmt->execute();
			
			return $stmt;
		}
		catch(PDOException $ex){
			echo $ex->getMessage();
			return false;
		}
	}
	
	// Update lunchmenu
	public function update($id,$datum,$alt_1,$alt_2,$alt_veg,$sallad,$pasta){
		
		$chkcount = count($id);
		try
		{
			$stmt=$this->conn->prepare("UPDATE " . $this->table_menu . " 
										SET
											datum=:datum,
											alt_1=:alt_1,
											alt_2=:alt_2,
											alt_veg=:veg,
											alt_sallad=:alt_sallad,
											veckans_pasta=:pasta
										WHERE 
											id=:id"); 
			for ($i=0; $i<$chkcount;)
			{
				$stmt->bindparam(':datum',$datum[$i], PDO::PARAM_STR);
				$stmt->bindparam(':alt_1',$alt_1[$i], PDO::PARAM_STR);
				$stmt->bindparam(':alt_2',$alt_2[$i], PDO::PARAM_STR);
				$stmt->bindparam(':veg',$alt_veg[$i], PDO::PARAM_STR);
				$stmt->bindparam(':alt_sallad',$sallad[$i], PDO::PARAM_STR);
				$stmt->bindparam(':pasta',$pasta, PDO::PARAM_STR);
				$stmt->bindparam(':id',$id[$i], PDO::PARAM_INT);
			
				$stmt->execute();
				$i++;
			}		
				return $stmt;
		}
		catch(PDOException $ex){
			echo $ex->getMessage();
			return false;
		}
	}
	
	// delete the menu
	function delete($id){
 
		$query = "DELETE FROM " . $this->table_menu . " WHERE id = ?";
		 
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $this->id);
	 
		if($result = $stmt->execute()){
			return true;
		}
		else{
			return false;
		}
	}
	
	// Read menu
	function readAll($from_record_num, $records_per_page){
 
		$query = "SELECT
					id, vecka, veckodag, datum
				FROM
					" . $this->table_menu . "
				ORDER BY
					datum DESC
				LIMIT
					{$from_record_num}, {$records_per_page}";
	 
		$stmt = $this->conn->prepare( $query );
		$stmt->execute();
	 
		return $stmt;
	}
	
	// used for paging menu
	public function countAll(){
 
		$query = "SELECT id FROM " . $this->table_menu . "";
	 
		$stmt = $this->conn->prepare( $query );
		$stmt->execute();
	 
		$num = $stmt->rowCount();
	 
		return $num;
	}
	
	// used for read one menu
	function readOne(){

		$query = "SELECT
					vecka, veckodag, alt_1, alt_2, alt_veg, alt_sallad, veckans_pasta
				FROM
					" . $this->table_menu . "
				WHERE
					id = ?
				LIMIT
					0,1";
	 
		$stmt = $this->conn->prepare( $query );
		$stmt->bindParam(1, $this->id);
		$stmt->execute();
	 
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
	 
		$this->vecka = $row['vecka'];
		$this->veckodag = $row['veckodag'];
		$this->alt_1 = $row['alt_1'];
		$this->alt_2 = $row['alt_2'];
		$this->alt_veg = $row['alt_veg'];
		$this->alt_sallad = $row['alt_sallad'];
		$this->veckans_pasta = $row['veckans_pasta'];
	}
	
	// Read weekmenu
	function readAllweeks($from_record_num, $records_per_page){
		
		$query = "SELECT
					vecka, datum, veckodag, alt_1, alt_2, alt_veg, alt_sallad, veckans_pasta
				FROM
					" . $this->table_menu . " WHERE YEARWEEK(datum) BETWEEN YEARWEEK(NOW()) AND YEARWEEK(DATE_ADD(NOW(),INTERVAL 30 DAY)) AND WEEKDAY(datum) IN(0,1,2,3,4,5)
				ORDER BY
					datum ASC
				LIMIT
					{$from_record_num},{$records_per_page}";
	 
		$stmt = $this->conn->prepare( $query );
		$stmt->execute();
	 
		return $stmt;
	}
	
	// used for paging weekmenu
	public function countAllweeks(){
		
		$query = "SELECT * FROM " . $this->table_menu . " WHERE NOT YEARWEEK(datum) < YEARWEEK(CURDATE())";

		$stmt = $this->conn->prepare( $query );
		$stmt->execute();
		
		$num = $stmt->rowCount();
	 
		return $num;
	}
	
	// Read subscribers email
	function subscribers_email(){
 
		$query = "SELECT
					id, name, email, tokenCode
				FROM
					" . $this->table_email . "";
	 
		$stmt = $this->conn->prepare( $query );		
		$stmt->execute();
		
		return $stmt;
	}
	
	// send newsmail lunchmail
	// $mail->isSendmail(); Testar tar bort
	function newsmail($email, $name, $subject, $newsletter)
	{	
		$config = parse_ini_file(LIBRARY_PATH . '/config-mailerFile.ini.php'); 
		require_once(MAILER_PATH . '/autoload.php');
		
		$mail = new PHPMailer\PHPMailer\PHPMailer(true);

		$mail->IsSMTP();
		$mail->CharSet 		= "utf-8";
		$mail->Host 		= $config['host'];
		$mail->SMTPAuth 	= true;
		$mail->SMTPSecure 	= $config['smtpsecure'];
		$mail->Username 	= $config['username'];
		$mail->Password 	= $config['password'];
		$mail->Port			= $config['port'];
		$mail->SMTPDebug	= 0;
		
		$mail->SetFrom		(($config['mailaddress']),'Restaurang Karl');
		$mail->AddAddress	($email ,$name);
		$mail->AddReplyTo	(($config['mailaddress']),'Restaurang Karl');
		
		$mail->Subject		= $subject;
		$mail->MsgHTML		($newsletter);
		$mail->Send();
		$mail->clearAllRecipients();
	}
}