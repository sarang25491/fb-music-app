<?php

  #############################################################################
  # Burst Development MySQL Object                                            #
  # Copyright (c) 2007 Burst Development, LLC                                 #
  #                                                                           #
  # This file is property of Burst Development, LLC.  You should not be       #
  # seeing this message unless you are a client of, or an employee of Burst   #
  # Development.                                                              #
  #                                                                           #
  # Defines a class for usage with a MySQL Database.                          #
  # Originally written by Russell Frank, 10 13 07.                            #
  #############################################################################

class BurstMySQL {
   var $mConnection;
   var $mDBName;
   var $mQueries = array();
   var $mTotal=0;

   public function __construct ($mHost, $mUser, $mPass, $mDatabase, 
                                 $mPort=3306) {
      $this->mConnection = @mysql_connect($mHost . ':' . $mPort, $mUser, $mPass)
                                          or $this->OnError();
      $this->mDBName = $mDatabase;
      mysql_select_db ($mDatabase, $this->mConnection);
   }

   public function OnError ($mQuery) {
      echo ('<br><div style="border-style:solid; border-width: 1px; border-color: #ffd04d; background-color: #fff5b1; padding:10px">
             <div style="letter-spacing: 1px; font-family: verdana; font-size: 20px; text-align: left; color: #003355">
	          Oops! Something went wrong.<br />Please report to the developers how you generated this error.</div>
	          <div style="font-family: verdana; font-size: 12px; text-align: left;"></div></div>');
	   $this->logError(mysql_error($this->mConnection), $mQuery);	  
      die();
   }

	public function logError ($error, $mQuery) {
    	$handle = fopen('/var/www/db.log','a+'); // ammend data to end of file, create file if it doesn't exist.
    	$timestamp = date('m.d.Y h:iA T');
    	fwrite($handle, "[" . $timestamp . "] " . $mQuery . " -- " . $error . " \r\n");
    	fclose($handle);
   }

   public function Raw ($mQuery) {
      $mReturnData = array();
      $mResult = @mysql_query ($mQuery, $this->mConnection) or $this->OnError($mQuery);
      while ($row = @mysql_fetch_array ($mResult, MYSQL_ASSOC))
        $mReturnData[] = $row;
      return $mReturnData;
   }

   public function getSlots($user)
   {
      $slots = $this->Raw("SELECT `credit`,`credit_new`,`override` FROM `userdb_users` WHERE `user`='$user'");
      
      if (count($slots) == 0)
         return 0;

      $slots = $slots[0]['credit']+$slots[0]['credit_new']+$slots[0]['override'];   
      return $slots;
   }

   public function getUsage($input)
   {
      if (is_array($input))
      {
         $idString = "'";
         foreach ($input as $user) $idString .= $user . "','";
         $idString = substr($idString, 0, -2);
         $usage = $this->Raw("SELECT COUNT(*) FROM `userdb_uploads` WHERE `user` IN ($idString)");
      }
      else
      {
         $usage = $this->Raw("SELECT COUNT(*) FROM `userdb_uploads` WHERE `user`='$input'");
      }      

      return $usage[0]['COUNT(*)'];
   }

   /*
   checkSlots
   checks to see if the inputted user has available slots
   @return: boolean $return
   @arg: int $user, array() $ids, obj. $facebook
   */
   public function checkSlots($user, $ids)
   {
      $slots = getSlots();
      $usage = getUsage();
      
      if ($slots-$usage > 0)
         return true;
   }


   public function getStats($user, $activity, $offset='-1 year')
   {
      switch($activity)
      {
         case "start": $activity = 'p'; break;
         case "fullplay": $activity = 'fp'; break;
         default: return 0;
      } 
       
      $date = date('Y-m-d', strtotime($offset));
      $dbCall = $this->Raw("SELECT COUNT(*) FROM `userdb_activity` WHERE `user`='$user' AND `time` > '$date' AND `action`='$activity'");  
      return $dbCall[0]['COUNT(*)'];
   }
      

   public function logActivity($user, $activity, $data='')
   {
      switch ($activity)
      {
         case "logStart": $activity = 'p'; break;
         case "logFullPlay": $activity = 'fp'; break;
         default: return false;
      }
      
      $this->Raw("INSERT INTO `userdb_activity` (`user`,`action`,`data`) VALUES ('$user','$activity','$data')");
      return true;
   }

   public function __destruct () {
      foreach ($this->mQueries as $mQuery) {
         if (!@mysql_query ($mQuery, $this->mConnection))
	         $this->OnError();
      }
      @mysql_close ($this->mConnection);
   }

   public function GetTotal () {
      return $this->mTotal;
   }

}

?>
