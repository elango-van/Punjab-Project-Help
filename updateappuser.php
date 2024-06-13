<?php

   function GUID()
   {
      if (function_exists('com_create_guid') === true)
      {
         return trim(com_create_guid(), '{}');
      }

      return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
   }

   $con=mysqli_connect("localhost","username","password","database");

   if (mysqli_connect_errno($con)) {
      echo "Failed to connect to MySQL: " . mysqli_connect_error();
   }
	
   $name= $_POST['name'];
   $mobile = $_POST['mobile'];
   $email = $_POST['email'];

   // email already exists
   $qry = "select * from aabprahari_users where email='$email'";
   $rs = $con->query($qry);
   if ($rs) {
      echo 'Email already exist.\n';
      exit;
   }      

   // create user id
   $userid = mt_rand(1000,9999999);
   while(1)
   {
     $qry = "select * from aabprahari_users where userid=";
     $rs = $con->query($qry);
     if ($rs) {
        $userid = mt_rand(1000,9999999);
     } else {
       break;
     }
   }
   // create activation code
   $activationcode = GUID();
   while (1){
     $qry = "select * from aabprahari_users where activationcode ='$activationcode'";
     $rs = $con->query($qry);
     if ($rs) {
        $activationcode = GUID();
     } else {
       break;
     }
   }
   $active='FALSE';
   $sql="INSERT INTO aabprahari_users (userid,name,phone_number,email,active,activationcode) VALUES ('$userid','$name','$mobile','$email','$active','$activationcode')";
   if (mysqli_query($con,$sql)) {
      echo "Updated successfully";
   }else{
	   echo "Not Updated successfully";
   }
	
   mysqli_close($con);


?>
