<?php
require_once ("/web/se/ww2.restaurangkarl.se/resources/dbconfig.php");

class USER
{	
	private $conn;
	private $table_user = "cms_user";
	
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
	
	/* insert new user  */
	public function user_register($name,$user,$email,$user_type,$pass,$code){
		
		try{
			$password = password_hash($pass, PASSWORD_DEFAULT);
			
			$stmt = $this->conn->prepare("INSERT INTO " . $this->table_user . " 
											(name,user,email,user_type,pass,tokenCode)
										VALUES
											(:name, :user, :email, :user_type, :pass, :active_code)");
										
			$stmt->bindparam(':name', $name);
			$stmt->bindparam(':user', $user);
			$stmt->bindparam(':email', $email);
			$stmt->bindparam(':user_type', $user_type);
			$stmt->bindparam(':pass', $password);
			$stmt->bindparam(':active_code', $code);
			
			$stmt->execute();
			return $stmt;
		}
		catch(PDOException $ex){
			echo $ex->getMessage();
		}
	}
	
	/* update user */
	public function user_update($id,$name,$user,$email)	{
		
		try
		{	
			$stmt=$this->conn->prepare("UPDATE " . $this->table_user . "
										SET 
											name=:name, 
											user=:user,
											email=:email													   
										WHERE 
											user_id=:id");
			
			$stmt->bindparam(':name',$name);
			$stmt->bindparam(':user',$user);
			$stmt->bindparam(':email',$email);			
			$stmt->bindparam(':id',$id);
			
			$stmt->execute();
			return $stmt;	
		}
		catch(PDOException $ex){
			echo $ex->getMessage();
			return false;
		}
	}
	
	/* Delete */
	public function delete($id){
		
		$stmt = $this->conn->prepare("DELETE FROM " . $this->table_user . " WHERE user_id=:id");
		$stmt->bindparam(':id',$id);
		
		$stmt->execute();
		return true;
	}
	
	/* Login/Logout */
	public function login($user,$email,$pass){
		
		try
		{
			$stmt = $this->conn->prepare("SELECT * FROM " . $this->table_user . " WHERE user=? OR email=?");
			$stmt->execute(array($user, $email));
			$userRow=$stmt->fetch(PDO::FETCH_ASSOC);
			
			if($stmt->rowCount() == 1){
				
				if($userRow['user_status']=="Y"){
					
					if(password_verify($pass, $userRow['pass'])){
						$userRow['user_type'] == 'admin';
						$_SESSION['user_session'] = $userRow['user_id'];
						$_SESSION['success']  = "<div class='alert alert-success'>
												<button class='close' data-dismiss='alert'>&times;</button>
												Hej! Du är nå inloggad !
												</div>";
						header('location: admin.php');
						
					 if ($userRow['user_type'] == 'user'){
						$_SESSION['success']  = "<div class='alert alert-success'>
												<button class='close' data-dismiss='alert'>&times;</button>
												Hej! Du är nå inloggad !
												</div>";
						header('location: menu_user.php');
						}					
					}
					else{
						header('Location: login.php?error');
						exit;
					}
				}
				else{
					header('Location: login.php?inactive');
					exit;
				}	
			}
			else{
				header('Location: login.php?error');
				exit;
			}		
		}
		catch(PDOException $ex){
			echo $ex->getMessage();
		}
	}
	
	public function is_loggedin(){
		
		if(isset($_SESSION['user_session'])){
			return true;
		}
	}
	
	public function logout(){
		session_destroy();
		unset($_SESSION['user_session']);
		return false;
	}
	
	public function redirect($url){
		header('Location:'.$url);
	}
	
	// user id
	public function read_user(){
		
		try
		{
			$query = "SELECT
						*
					FROM
						" . $this->table_user . "";
		 
			$stmt = $this->conn->prepare( $query );
			$stmt->execute();

			return $stmt;
			
		}
		catch(PDOException $ex){
			echo $ex->getMessage();
			return false;
		}
	}
	
	// user id session
	public function read_usersession(){
		
		try
		{
			$query = "SELECT
						*
					FROM
						" . $this->table_user . "
					WHERE
						user_id=:uid ";
		 
			$stmt = $this->conn->prepare( $query );
			$stmt->execute(array(':uid'=>$_SESSION['user_session']));
	
			return $stmt;
			
		}
		catch(PDOException $ex){
			echo $ex->getMessage();
			return false;
		}
	}
	
	/* Send verify mail to user */
	function send_userMail($email, $name, $subject, $message)
	{	
		$config = parse_ini_file(LIBRARY_PATH . '/config-mailerFile.ini.php'); 
		require_once(MAILER_PATH . '/autoload.php');
		
		$mail = new PHPMailer\PHPMailer\PHPMailer(true);
		
		$mail->IsSMTP();
		$mail->CharSet 		= 'utf-8';
		
		$mail->Host 		= $config['host'];
		$mail->SMTPAuth 	= true;
		$mail->SMTPSecure 	= $config['smtpsecure'];
		$mail->Username		= $config['webmasteruser'];
		$mail->Password		= $config['webmasterpass'];
		$mail->Port			= $config['port'];
		$mail->SMTPDebug	= 0;
		
		$mail->SetFrom		(($config['webmastermail']),'Webmaster');
		$mail->AddAddress	($email ,$name);
		$mail->AddBCC		(($config['webmastermail']),'Kopia på ny användarregistrering');
		$mail->AddReplyTo	(($config['webmastermail']),'Webmaster');
				
		$mail->Subject		= $subject;
		$mail->MsgHTML		($message);
		$mail->Send();
	}
	
	/* Send mail fpass to user */
	function send_mail($email, $subject, $message)
	{	
		require_once(MAILER_PATH . '/autoload.php');
		
		$mail = new PHPMailer\PHPMailer\PHPMailer(true);
		
		$mail->IsSMTP();
		$mail->CharSet = 'utf-8';
		
		$mail->Host 		= $config['host'];
		$mail->SMTPAuth 	= true;
		$mail->SMTPSecure 	= $config['smtpsecure'];
		$mail->Username		= $config['webmasteruser'];
		$mail->Password		= $config['webmasterpass'];
		$mail->Port			= $config['port'];
		$mail->SMTPDebug	= 0;
				
		$mail->SetFrom		(($config['webmastermail']),'Webmaster');
		$mail->AddAddress	($email);
		$mail->AddReplyTo	(($config['webmastermail']),'Webmaster');
		
		$mail->Subject		= $subject;
		$mail->MsgHTML		($message);
		$mail->Send();
	}
}