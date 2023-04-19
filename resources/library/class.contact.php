<?php
require_once ("/web/se/ww2.restaurangkarl.se/resources/dbconfig.php");

class FORM
{	
	private $conn;
	private $table_contact = "contact_form";
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
	
	/* Reg contact form*/
	public function register($samtycke_form,$samtycke,$name,$email,$phone,$subject,$message,$code){
		
		try
		{
			$ipaddress = $_SERVER['REMOTE_ADDR'];
			
			$stmt = $this->conn->prepare("INSERT INTO " . $this->table_contact . " (datetime,samtycke_form,samtycke,name,email,phone,subject,message,ipaddress,tokenCode) 
		                                               VALUES(NOW(), :samtycke_form, :samtycke, :name, :email, :phone, :subject, :message, :ipaddress, :active_code)");
													   
			$stmt->bindparam(':samtycke_form', $samtycke_form, PDO::PARAM_INT);
			$stmt->bindparam(':samtycke', $samtycke, PDO::PARAM_STR);
			$stmt->bindparam(':name', $name, PDO::PARAM_STR);
			$stmt->bindparam(':email', $email, PDO::PARAM_STR);
			$stmt->bindparam(':phone', $phone, PDO::PARAM_STR);
			$stmt->bindparam(':subject', $subject, PDO::PARAM_STR);
			$stmt->bindparam(':message', $message, PDO::PARAM_STR);
			$stmt->bindparam(':ipaddress', $ipaddress, PDO::PARAM_STR);
			$stmt->bindparam(':active_code', $code, PDO::PARAM_STR);
			
			$stmt->execute();	
			return $stmt;
			
		}
		catch(PDOException $ex){
			echo $ex->getMessage();
		}
	}
	
	/* Reg subscribe newsletter */
	public function email_register($name,$email,$samtycke,$code){
		
		try
		{	
			$ipaddress = $_SERVER['REMOTE_ADDR'];
			
			$stmt = $this->conn->prepare("INSERT INTO " . $this->table_email . " (datetime,name,email,samtycke,ipaddress,tokenCode) 
									   VALUES(NOW(), :name, :email, :samtycke, :ipaddress, :active_code)
									   ON DUPLICATE KEY UPDATE
										name=:name,
										email=:email,
										ipaddress=:ipaddress,
										tokenCode=:active_code ");
			
			$stmt->bindparam(':name', $name, PDO::PARAM_STR);
			$stmt->bindparam(':email', $email, PDO::PARAM_STR);
			$stmt->bindparam(':samtycke', $samtycke, PDO::PARAM_STR);
			$stmt->bindparam(':ipaddress', $ipaddress, PDO::PARAM_STR);
			$stmt->bindparam(':active_code', $code, PDO::PARAM_STR);
			
			$stmt->execute();	
			return $stmt;
		}
		catch(PDOException $ex){
			echo $ex->getMessage();
		}
	}
	
	/* Delete contact*/
	public function delete($id){
		
		$stmt = $this->conn->prepare("DELETE FROM " . $this->table_contact . "  WHERE contact_id=:id");
		$stmt->bindparam(':id',$id , PDO::PARAM_INT);
		
		$stmt->execute();
		return true;
	}
	
	/* Delete email*/
	public function delete_epost($id){
		
		$stmt = $this->conn->prepare("DELETE FROM " . $this->table_email . " WHERE id=:id");
		$stmt->bindparam(':id',$id , PDO::PARAM_INT);
		
		$stmt->execute();
		return true;
	}
	
	// Delete subscriber email
	function deleteSubscriber($id){
 
		$query = "DELETE FROM " . $this->table_email . " WHERE id = ?";
		 
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $this->id);
	 
		if($result = $stmt->execute()){
			return true;
		}
		else{
			return false;
		}
	}
	
	public function redirect($url){
		header('Location:'.$url);
	}
	
	// Read subscribers
	function contactNewsletter($from_record_num, $records_per_page){
 
		$query = "SELECT
					id, name, email
				FROM
					" . $this->table_email . "
				ORDER BY
					id ASC
				LIMIT
					{$from_record_num}, {$records_per_page}";
	 
		$stmt = $this->conn->prepare( $query );		
		$stmt->execute();
		
		return $stmt;		
	}
	
	// used for paging subscribers
	public function countAll(){
 
		$query = "SELECT id FROM " . $this->table_email . "";
	 
		$stmt = $this->conn->prepare( $query );
		$stmt->execute();
	 
		$num = $stmt->rowCount();
	 
		return $num;
	}
	
	// Read subscribers email
	function subscribers_email(){
 
		$query = "SELECT
					name, email
				FROM
					" . $this->table_email . "";
	 
		$stmt = $this->conn->prepare( $query );		
		$stmt->execute();
		
		return $stmt;
	}
}