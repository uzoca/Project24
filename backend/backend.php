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
        //  $this->host = "zarontest.org.ng";
        //  $this->dbname = "zarontes_financial_admin";
        //  $this->usern = "zarontes_wp547";
        //  $this->pass = "Ijukwalechi1";
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
    public  function register($fir,$las,$email,$phon,$pass,$gend,$uimg,$priviledge){
        $this->uid =substr(md5(openssl_random_pseudo_bytes(7)),10,8);
        $sql=" INSERT INTO  usertable (firstname, lastname, email,phone,`password`,gender,userid,userimg,priviledges)
		            VALUES(:fir,:las,:email,:phon,:pass,:gend,:uid,:uimg,:pri)";
        $connarray= array(":fir"=>$fir,":las"=>$las,":email"=>$email,":phon"=>$phon,":pass"=>$pass,":gend"=>$gend,
                            ":uid"=>$this->uid,":uimg"=>$uimg,":pri"=>$priviledge);
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
$timh= $timh->format('Y-m-d H:i:s');

function cleanData($data){
 return strip_tags(trim($data));
}

// Register organisation =============

if(isset($_POST['account_number']) && isset($_POST['organisation_name'] )){
 $imageLocaion ='';
 $accountDetails = get_bank_details($_POST['account_number']);
 echo $accountDetails;
if(isset( $_FILES['org_image'] )){
     $fltype= $_FILES['org_image']['type'];
            // echo $fltype;
            $fltypearay= explode('/',$fltype) ;
            // echo $fltypearay[1]=='jpeg';
            if(!($fltypearay[1] == 'png' || $fltypearay[1] =='jpg' || $fltypearay[1] =='jpeg' )){
               echo 'Please choose a jpeg,png,jpg image of you.Thankyou!';
            }elseif ( $_FILES['org_image']['size'] > 4000000 ) {
                echo   "The file $_FILES[vuploads]['name'] is too large";
            }else{
                  $uninquename ='iun'.random_int(100000000,9999999999);
                if (move_uploaded_file($_FILES['org_image']['tmp_name'],'../img/org_images/profile_image'.$uninquename.'.'.$fltypearay[1])) {
                     echo " gotten here" ;
                     $imageLocaion = '../img/org_images/profile_image_'.$uninquename.'.'.$fltypearay[1] ;
                    
                   $sql = "INSERT INTO `organisations`(`id`, `name`, `account_no`, `account_type`, `balance`,`password`, `Organisation Image`)
                    VALUES ('$uninquename','".cleanData($_POST['organisation_name'])."','".cleanData($_POST['account_number'])."','".cleanData($_POST['account_type'])."','".cleanData($accountDetails['acount_balance'])."','".cleanData($_POST['pass'])."','$imageLocaion')";
                   $rg = $pocket->doQuery($sql,array()); 
                   if($rg){
                    $_SESSION['online'] =$pocket->uid;
                     $_SESSION['organisation']=  $_SESSION['online'] ;
                    echo"<script> setCookie('session_id','".$_SESSION['online']."', '2'); window.location='./dashboard2.html'</script>";
                    }else{
                     echo "error" ;
                    }

                }
                
            }
            
}
  
}

// Get details ========

// Get organisation Details

if(isset($_POST['get_org_details'])){

 $sql="SELECT * FROM `organisations` WHERE `account_no`= '$_POST[get_org_details]'";
 $profiledata=$pocket->doQuery($sql,array())->fetch(PDO::FETCH_ASSOC);
 echo json_encode($profiledata);
}


if(isset($_POST['get_bank_details_org'])){
 if(isset($_SESSION['online']))
  logActivity("Requested organisations bank details");
 echo json_encode(getDashboardDetails());
 
}
// Get Account Amount function
function get_organisation_accNo(){
$pocket = new pocket();
 $sql = "SELECT account_no FROM `organisations` WHERE id = '$_SESSION[organisation]'";
 $acc_no = $pocket->doQuery($sql,array())->fetch(PDO::FETCH_ASSOC);
  return $acc_no['account_no'] ;
}
function getDashboardDetails(){
  $details = get_bank_details(get_organisation_accNo());
  return $details ;
}

// Admin User code ============

if(isset($_POST['all_user'])){
 $sql = "SELECT `userid`, `organisation_key`, `firstname`, `lastname`, `email`, `phone`, `gender`, `userimg`, `priviledges` FROM `usertable`";
  $reply= $pocket->doQuery($sql,array());
    $reply=$reply->fetchAll(PDO::FETCH_ASSOC);
    if($reply){
        echo json_encode($reply);
    }else{
      echo " Error occurred";
    }
 
}
// user registration ============
if(isset($_POST['pass']) && isset($_POST['firstname']) && isset($_POST['lastname']) && !isset($_POST["edit_admin"]) ) {

    $mfirstname = cleanData($_POST['firstname']);
    $mlastname =cleanData($_POST['lastname']);
    $memail = cleanData($_POST['email']);
    $mphone = cleanData($_POST['phone']);
    $mpass = cleanData($_POST['pass']);
    $mgender =cleanData($_POST['gender']);
    $privi = cleanData($_POST['priviledge']);

    if($mgender=="Male"){
        $uimg ='img/male_avatar.png ' ;
    }else {
     $uimg ='img/female_avatar.jpg';
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
        $newuser=$pocket->register($mfirstname,$mlastname,$memail,$mphone,$passkey,$mgender,$uimg,$privi);
        if($newuser && !(isset($_POST['from_admin']))){
           $_SESSION['online'] =$pocket->uid; 
            logActivity("A new admin registration");
           echo"<script> setCookie('session_id','".$_SESSION['online']."', '2'); window.location='./dashboard2.html'</script>";
        }else if($newuser && isset($_POST['from_admin'])){

          echo "Registration successfull ";

        }
           
        exit();

    }

}
// Edit user ==============
if(isset($_POST["edit_admin"]) && isset($_POST["from_admin"])){
     echo $_POST["edit_admin"] ;
    $mfirstname = cleanData($_POST['firstname']);
    $mlastname =cleanData($_POST['lastname']);
    $memail = cleanData($_POST['email']);
    $mphone = cleanData($_POST['phone']);
    $mpass = cleanData($_POST['pass']);
    $mgender =cleanData($_POST['gender']);
    $privi = cleanData($_POST['priviledge']);

   $sql = "UPDATE `usertable` SET `firstname`='$mfirstname',`lastname`='$mlastname',`email`='$memail',`phone`='$mphone',`password`='$mpass',`gender`='$mgender',
   `priviledges`='$privi',`date_edited`='$timh' WHERE `userid`='$_POST[edit_admin]'";
   $edited = $pocket->doQuery($sql,array()) ;
   if($edited){
     if(isset($_SESSION['online']))
      logActivity("Edited admin $_POST[edit_admin] new details now is email: $memail phone:$mphone firstname : $mfirstname lastname : $mlastname  priviledges : $privi");
    echo "Edited Successfully";
   }else{
    echo " Error try again";
   }
}
// Delete amdin ==========
if(isset($_POST["delete_admin"])){
 $deleted = $pocket->doQuery("DELETE FROM `usertable` WHERE `userid`='$_POST[delete_admin]'",array());
 if($deleted){
   if(isset($_SESSION['online']))
      logActivity("Deleted admin $_POST[delete_admin]");
   echo "Successful";
 }else{
   echo "Error occurred try again";
 }

}
// login process ==========
$profiledata = "";
if(isset($_POST['email']) && isset($_POST['pass'])&& !isset($_POST["edit_admin"])){
    //$profiledata= $prof->showprofile($_POST['email'],$_POST['password']);
    $sql="SELECT * FROM usertable WHERE email=:em AND password =:p";
    $shw=array(":em"=> trim($_POST['email']),":p"=>trim($_POST['pass']));
    if($profiledata=$pocket->doQuery($sql,$shw)->fetch(PDO::FETCH_ASSOC)) {
      //  if(password_verify($_POST['password'] ,$profiledata['password']))
         $_SESSION['online']=$profiledata['userid'];   
          logActivity(" Logged in");
          $_SESSION['organisation']=  $profiledata['organisation_key'];
         echo"<script> setCookie('session_id','".$_SESSION['online']."', '2'); window.location='./dashboard2.html'</script>";
        }else echo "Error Email or Password not correct check and try again.";
    
}
// checkif user is online ===========
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

// Get all transactions ==============
if(isset($_POST['all_transactions'])){

//    $sql=$_POST['all_transactions']=='all'?"SELECT * FROM transaction_table " :
//     "SELECT * FROM transaction_table LIMIT  $_POST[all_transactions]";
//     $transactiondata=$pocket->doQuery($sql,array())->fetchAll(PDO::FETCH_ASSOC);
//     echo json_encode($transactiondata);
}


// log out code
if(isset($_POST['logout'])){
    if(isset($_SESSION['online']))
    logActivity("Logged Out");
    if (isset($_COOKIE['session_id'])) {
    unset($_COOKIE['session_id']); 
    setcookie('session_id', '', -1, '/'); 
    } else {
    return false;
   }
    session_unset();
    session_destroy();

    echo"<script> window.location='./index.html'</script>";
}

if(isset($_POST['get_activities'])){
 $sql="SELECT  `activity`, `which_admin`, `date` FROM `activity_log_table`";
 if($activity = $pocket->doQuery($sql,array())){
   echo json_encode($activity->fetchAll(PDO::FETCH_ASSOC)) ;
 }else{
   echo "Error could not retrieve activity log";
 }
}

function entitySum($entity, $from,$to){
 $pocket = new pocket();
 $sql ="SELECT amount FROM `transaction_table` WHERE paymenttype='$entity' AND Date >= '$from' AND Date <= '$to'";
 $result = $pocket->doQuery($sql,array())->fetchAll(PDO::FETCH_ASSOC);
 $total = 0 ;
 foreach( $result as $ele){
  $total = $total + (int) $ele['amount'];
 }
 echo $total;
 return $total;
}
//  set budget or goals codes

 if(isset($_POST['budget_name']) && isset($_POST['settype'])){

   $_POST['settype'] == 'Goals'?
     $sql="INSERT INTO `financial_target_table`( `name`,`type`, `amount`, `from_date`, `to_date`)
    VALUES ('".cleanData($_POST['budget_name'])."','".cleanData($_POST['settype'])."','".cleanData($_POST['amount'])."','".cleanData($_POST['from_date'])."','".cleanData($_POST['to_date'])."')"
   :
   $sql="INSERT INTO `budget_table`( `name`,`type`, `amount`, `from_date`, `to_date`)
    VALUES ('".cleanData($_POST['budget_name'])."','".cleanData($_POST['settype'])."','".cleanData($_POST['amount'])."','".cleanData($_POST[from_date])."','".cleanData($_POST[to_date])."')";
   $setBudget = $pocket->doQuery($sql,array());
   if($setBudget){
      if(isset($_SESSION['online']))
      logActivity("set $_POST[settype] Name: $_POST[budget_name]");
      echo 'Successful';
   }else {
      echo 'An server error occured please try again'; 
   }
   
 }

 if(isset($_POST['all_budget'])){
    $sql="SELECT * FROM `budget_table` " ;
    $transactiondata = $pocket->doQuery($sql,array())->fetchAll(PDO::FETCH_ASSOC);

    // echo  entitySum('Donations',$transactiondata[0]['from_date'],$transactiondata[0]['to_date']);
  echo json_encode($transactiondata);
}
  if(isset($_POST['all_goals'])){
    $sql="SELECT * FROM `financial_target_table` " ;
    $transactiondata = $pocket->doQuery($sql,array())->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($transactiondata);
}

// Activity logs ===========
function logActivity($log){
$pocket = new pocket();
$sql="SELECT `userid`, `firstname`,`lastname`,priviledges FROM usertable WHERE userid = '$_SESSION[online]' ";
 $profiledata=$pocket->doQuery($sql,array())->fetch(PDO::FETCH_ASSOC);
 $nameString = "Admin $profiledata[firstname] $profiledata[lastname] with user id $profiledata[userid]";
if($profiledata){
   $sql_activity ="INSERT INTO `activity_log_table`( `activity`, `which_admin`) 
   VALUES ('$log','$nameString')";
   if($pocket->doQuery($sql_activity,array())){
     return ;
   }else{
     return "Note Could not log admin activity please check server";
   }
}

}

// pseudo bank codes ==================

if(isset($_POST['account_name'])&& isset($_POST['pass'])){
     $sql = "SELECT * FROM `bank_account_table` WHERE `account_name`= '".cleanData($_POST['account_name'])."' AND `password`='".cleanData($_POST['pass'])."'";
      $profiledata=$pocket->doQuery($sql,array())->fetch(PDO::FETCH_ASSOC);
    if(count($profiledata)>0) {
      //  if(password_verify($_POST['password'] ,$profiledata['password']))
      $_SESSION['bank_user'] =  $profiledata['acount_number']; 
     // $_SESSION['userKey'] =    $profiledata['organisation_key'] ;
      echo $_SESSION['bank_user']; 
         echo"<script> setCookie('session_id','".$_SESSION['bank_user']."', '2'); window.location='./bank_home_page.html'</script>";
        }else echo "Error wrong account details.";
}

if(isset($_POST['firstname_bank']) && isset($_POST['bank_email'])) {
$name = $_POST['firstname_bank'].' '.$_POST['lastname_bank'];
 $sql="SELECT `email`,`account_name` FROM `bank_account_table` WHERE `email`= '".cleanData($_POST['bank_email'])."' OR `account_name`='".cleanData($_POST['username_bank'])."'";

    if($profiledata=$pocket->doQuery($sql,array())->fetch(PDO::FETCH_ASSOC)) {
     echo "Error Details already exit";
     exit;
    }else{
        $acc = random_int(100000000,9999999999) ;
        $sql = "INSERT INTO `bank_account_table`(`firstname`,`lastname`,`acount_number`,`email`, `account_name`, `acount_balance`, `password`)
         VALUES ('".cleanData($_POST['firstname_bank'])."','".cleanData($_POST['lastname_bank'])."','$acc','".cleanData($_POST['bank_email'])."','".cleanData($_POST['username_bank'])."','50000','".cleanData($_POST['pass'])."')";
        
            if( $pocket->doQuery($sql,array())){
            $_SESSION['bank_user'] = $acc; 
                echo"<script> setCookie('session_id','".$_SESSION['bank_user']."', '2'); window.location='./bank_home_page.html'</script>";
            }else{
              echo "An error occurred";
            } 
    }
  

}


//   get bank acount name
if(isset($_POST['get_accountname'])){
   $sql="SELECT `account_name`,`firstname`,`lastname` FROM `bank_account_table` WHERE  `acount_number`='".cleanData($_POST['get_accountname'])."'";

    if($profiledata=$pocket->doQuery($sql,array())->fetch(PDO::FETCH_ASSOC)) {
     echo json_encode($profiledata);
     exit;
    }else{
     echo "Unknown account details";
    }
}

// Get allbank details ===========
 if(isset($_POST['get_bank_details'])){
   if( $_SESSION['bank_user']){

     $sql="SELECT * FROM `bank_account_table` WHERE  `acount_number`='$_SESSION[bank_user]'";
     $profiledata=$pocket->doQuery($sql,array())->fetch(PDO::FETCH_ASSOC);
    if(count($profiledata)>0 ) {
     echo json_encode($profiledata);
     exit;
    }else{
      session_destroy();
       echo"<script>  window.location='./bankpage_login.html'</script>" ;
    }
     echo   $_SESSION['bank_user'];
   }else {
     session_destroy();
       echo"<script>  window.location='./bankpage_login.html'</script>" ;
   }
   
 }

//  money send and recieve codes 

if(isset($_POST['send_money'])){
  if(trim($_POST['account_number']) !== $_SESSION['bank_user']){
  $sql = "INSERT INTO `bank_transaction_table`( `subject`, `object`,`object_fullname`, `bank`, `amount`, `type`, `flow_type`, `description`, `checked`)
   VALUES ('$_SESSION[bank_user]','".cleanData($_POST['account_number'])."','".cleanData($_POST['receiver_name'])."','".cleanData($_POST['bank_name'])."','".cleanData($_POST['amount'])."','debit',
   '".cleanData($_POST['cash_flow_type'])."','".cleanData($_POST['description'])."','0')";
   $transaction = $pocket->doQuery($sql,array());

   if($transaction){

      $sql1= "SELECT `acount_balance` FROM `bank_account_table` WHERE `acount_number`='$_SESSION[bank_user]'";
      $sql2= "SELECT `acount_balance` FROM `bank_account_table` WHERE `acount_number`='$_POST[account_number]'";
      $sender_balance = $pocket->doQuery($sql1,array())->fetch(PDO::FETCH_ASSOC);
      $receiver_balance = $pocket->doQuery($sql2,array())->fetch(PDO::FETCH_ASSOC);
      $sender_balance =(float) $sender_balance['acount_balance'] - (float) $_POST['amount'];
      $receiver_balance = (float) $receiver_balance['acount_balance'] + (float) $_POST['amount'];

      $sql3 = "UPDATE `bank_account_table` SET `acount_balance`='$sender_balance' WHERE `acount_number`= $_SESSION[bank_user]";
      $sql4 =  "UPDATE `bank_account_table` SET `acount_balance`='$receiver_balance' WHERE `acount_number`= $_POST[account_number]";
      $update_sender = $pocket->doQuery($sql3,array());
      $update_receiver = $pocket->doQuery($sql4,array());
      $sql = "INSERT INTO `bank_transaction_table`( `subject`, `object`,`object_fullname`,`bank`, `amount`, `type`, `flow_type`, `description`, `checked`)
      VALUES ('".cleanData($_POST['account_number'])."','$_SESSION[bank_user]','".cleanData($_POST['receiver_name'])."','".cleanData($_POST['bank_name'])."','".cleanData($_POST['amount'])."','credit','".cleanData($_POST['cash_flow_type'])."','".cleanData($_POST['description'])."','0')";
       $transaction2 = $pocket->doQuery($sql,array());
      if($update_receiver && $update_sender &&  $transaction2){
         echo "Transaction successfull";
      }

   }
  }{
  echo "";
  }
}

// Getting all transaction of the current user


if(isset($_POST['get_user_transaction'])){
  
  $sql = "SELECT *  FROM `bank_transaction_table` WHERE `subject`='".cleanData($_POST['get_user_transaction'])."'";
  $transaction = $pocket->doQuery($sql,array())->fetchAll(PDO::FETCH_ASSOC);
  echo json_encode($transaction);
  
}
if(isset($_POST['transaction_decending'])){
  $sql = "SELECT *  FROM `bank_transaction_table` WHERE `type`='credit' AND `subject`='".cleanData($_POST['transaction_decending'])."' ORDER BY `bank_transaction_table`.`amount` DESC LIMIT 6";
  $creditArray = $pocket->doQuery($sql,array())->fetchAll(PDO::FETCH_ASSOC);
  
  $sql = "SELECT *  FROM `bank_transaction_table` WHERE `type`='debit'AND `subject`='".cleanData($_POST['transaction_decending'])."' ORDER BY `bank_transaction_table`.`amount` DESC LIMIT 6";
  $debitArray = $pocket->doQuery($sql,array())->fetchAll(PDO::FETCH_ASSOC);
  
  echo json_encode(array("debit"=>$debitArray,"credit"=>$creditArray));  
}



// Apis Codes =======
function get_bank_details($accountNumber){
$pocket = new pocket();
 $sql = "SELECT * FROM `bank_account_table` WHERE `acount_number` = '$accountNumber'";
     $profiledata=$pocket->doQuery($sql,array())->fetch(PDO::FETCH_ASSOC);
    if(count($profiledata)>0 ) {
     return $profiledata;
     exit;
    }else{
       return " Unknown details provided" ;
    }

}