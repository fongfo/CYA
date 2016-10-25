<?php

    $start = microtime(true);
    echo "Clean FTP Export files  <br/>";
    $ini_array = parse_ini_file("fishbowlSolidcommerce.ini", true);

    require_once( realpath(dirname(__FILE__)).'/../lib/Fishbowl/Inventory.php' );
    $backupDir = realpath(dirname(__FILE__))."\\".$ini_array['SC']['BACKUP_DIR'];
    
    $days = 7.3;
    $seconds = 3600*24;
    $maxNumFiles = 24;
    $timeVal = time();
    // connect to ftp
    $connId = ftp_connect($ini_array['SC']['FTP_HOST']);

    $login_result = ftp_login($connId, $ini_array['SC']['FTP_USER'], $ini_array['SC']['FTP_PASSWORD']);

    ftp_chdir($connId, $ini_array['SC']['INCOMING_DIR']);

    $ftpFile = ftp_nlist($connId, "/incoming");

    // backup and delete files over 7 days
    $numOfFile = count($ftpFile);

    if($numOfFile>$maxNumFiles){
        for($i=0;$i<$numOfFile ;$i++){
            $difDays = ($timeVal-ftp_mdtm($connId,$ftpFile[$i]))/$seconds;  
            //echo $difDays;
            if($difDays>$days){ 
                if (ftp_rename($connId,"/incoming/".$ftpFile[$i],"/backup/".$ftpFile[$i])){
                    echo $ftpFile[$i]."-backuped successfully<br/>";                 
                }else {
                    echo $ftpFile[$i]."-could not backup<br/>";
                }
            }else {
                echo $ftpFile[$i]."-no need to backup<br/>";
            }
        }	    
    }
    
    // close the connection
    ftp_close($connId);
       
    $end = microtime(true);
    echo "Script took ".($end - $start)." sec.<br/>";
    
?>