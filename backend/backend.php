<?php
/**
 * Author: GTtec
 * Date: 20/02/2024
 * Time: 11:35 PM
 */
// CREATE TABLE `financial_admin`.`usertable` (`userid` VARCHAR(100) NOT NULL , `firstname` VARCHAR(200) NOT NULL , `lastname` VARCHAR(200) NOT NULL , `email` VARCHAR(200) NOT NULL , `phone` VARCHAR(50) NOT NULL , `gender` VARCHAR(10) NOT NULL ,
//  ```userimg` VARCHAR(300) NOT NULL , PRIMARY KEY (`userid`(100))) ENGINE = InnoDB; 

session_start();
class pocket
{
   private $conn;
    protected $db;
    private $pass, $host,$usern,$dbname;
    public $uid;public $dbresults;
    public function __construct(){
         $this->host = "localhost";
         $this->dbname = "financial_admin";
        $this->usern = "root";
         $this->pass = "";
        try{

            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->dbname, $this->usern, $this->pass);
			$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->db=$this->conn;

        }catch ( PDOException $e){
            return $e->getMessage();
        }
        return $this->db;
  }//end of construct
    public function pocketcon($sqll){
        return $this->conn->prepare($sqll);

    }
    public function doQuery( $sql, array $arr){
        $con= $this->db;
       $pre= $con->prepare($sql);
        $pre->execute($arr);
        return $pre;
    }
    public function jasonstring(array $arr){
        return json_encode($arr);
    }
    public  function register($fir,$las,$email,$phon,$pass,$gend,$uimg){
        $this->uid =substr(md5(openssl_random_pseudo_bytes(7)),10,8);
        $sql=" INSERT INTO  usertable (firstname, lastname, email,phone,`password`,gender,userid,userimg)
		            VALUES(:fir,:las,:email,:phon,:pass,:gend,:uid,:uimg)";
        $connarray= array(":fir"=>$fir,":las"=>$las,":email"=>$email,":phon"=>$phon,":pass"=>$pass,":gend"=>$gend,
                            ":uid"=>$this->uid,":uimg"=>$uimg);
        $result=$this->doQuery($sql,$connarray);
        return $result;


    }
    public function showprofile($u,$p)
    {
        $sql="SELECT * FROM usertable WHERE email=:em AND password=:p";
        $shw=array(":em"=>$u,":p"=>$p);
        $shwm=$this->doQuery($sql,$shw);
        $shwdetails=$shwm->fetch(PDO::FETCH_ASSOC);
       return $shwdetails;
    }

    public function editprofile($u,$fir,$las,$email,$phon,$gend,$uimg){
        $sql="UPDATE `usertable` SET firstname=:f,lastname=:l,email=:e,phone=:p,
          gender=:g,`userimg`=:uimg WHERE userid=:uid";
  $editarray= array(":f"=>$fir,":l"=>$las,":e"=>$email,":p"=>$phon,":g"=>$gend,":uid"=>$u);
  if($this->doQuery($sql,$editarray)){
      return "update sucessfull";
  }else return "An error occured";
}
    // public function editprofile($u,$fir,$las,$email,$phon,$gend,$day,$mon,$year,$contr,$state,$accnum,$accnam,$bank,$uimg){
    //           $sql="UPDATE `usertable` SET firstname=:f,lastname=:l,email=:e,phone=:p,
    //             gender=:g,dayy=:d,montth=:mon,yearr=:y,country=:c,
    //             state=:s,accnumber=:accnum,accname=:accnam,bank=:bk,`userimg`=:uimg WHERE userid=:uid";
    //     $editarray= array(":f"=>$fir,":l"=>$las,":e"=>$email,":p"=>$phon,":g"=>$gend,":d"=>$day,":mon"=>$mon,
    //         ":y"=>$year,":c"=>$contr,":s"=>$state,":accnum"=>$accnum,":accnam"=>$accnam,":bk"=>$bank,
    //         ":uimg"=>$uimg,":uid"=>$u);
    //     if($this->doQuery($sql,$editarray)){
    //         return "update sucessfull";
    //     }else return "An error occured";
    // }

   }

$pocket = new pocket();
$timh=new DateTime("now");
$timh= $timh->format('D,d M h:ia');

if(isset($_POST['pass']) && isset($_POST['firstname']) && isset($_POST['lastname']) ) {
    $mfirstname = strip_tags($_POST['firstname']);
    $mlastname = strip_tags($_POST['lastname']);
    $memail = strip_tags($_POST['email']);
    $mphone = strip_tags($_POST['phone']);
    $mpass = strip_tags($_POST['pass']);
    $mgender = strip_tags($_POST['gender']);
    
    if($mgender=="Male"){
        $uimg ='./../img/male_avatar.png ' ;
    }else {
     $uimg ='./../img/female_avatar.jpg';
     }

    $ql="SELECT phone,email FROM usertable WHERE phone=:ph AND email=:em";
    $ar4ph=array(":ph"=>$_POST['phone'],":em"=>$_POST['email']);
   $reply= $pocket->doQuery($ql,$ar4ph);
    $reply=$reply->fetch(PDO::FETCH_ASSOC);
    if($reply){
        echo "error phone number or email already used try another one";
    }else{
       // $passkey=password_hash($mpass,PASSWORD_DEFAULT);
       $passkey = $mpass;
        $newuser=$pocket->register($mfirstname,$mlastname,$memail,$mphone,$passkey,$mgender,$uimg);
        if($newuser)
            $_SESSION['online'] =$pocket->uid;
           echo"<script> window.location='./dashboard.html'</script>";
        exit();

    }

}
$profiledata = "";
if(isset($_POST['email']) && isset($_POST['pass'])){
    //$profiledata= $prof->showprofile($_POST['email'],$_POST['password']);
    $sql="SELECT * FROM usertable WHERE email=:em AND password =:p";
    $shw=array(":em"=>$_POST['email'],":p"=>$_POST['pass']);
    if($profiledata=$pocket->doQuery($sql,$shw)->fetch(PDO::FETCH_ASSOC)) {
      //  if(password_verify($_POST['password'] ,$profiledata['password']))
         $_SESSION['online']=$profiledata['userid'];    
         echo"<script> setCookie('session_id','".$_SESSION['online']."', '2'); window.location='./dashboard2.html'</script>";
        }else echo "Error Email or Password not correct check and try again.";
    
}
if(isset($_POST['check-user'])){

    if($_COOKIE['session_id'] == $_SESSION['online']){
        if($profiledata == ""){
            $sql="SELECT * FROM usertable WHERE userid = '$_COOKIE[session_id]' ";
        $profiledata=$pocket->doQuery($sql,array())->fetch(PDO::FETCH_ASSOC);
        }
        echo json_encode($profiledata) ;
    }else{
        session_unset();
        session_destroy();
        echo"<script> window.location='./index.html'</script>";
    }
}

if(isset($_POST['logout'])){
    session_unset();
    session_destroy();
    echo"<script> window.location='./index.html'</script>";
}