<?php
    //ftp_rename - returns true if successful
    //ftp_delete - reutnrs true if successful

    $start = microtime(true);
    $useBackup = false;
    
    echo "Clean FTP Export files  <br/>";
    $ini_array = parse_ini_file("fishbowlSolidcommerce.ini", true);

    require_once( realpath(dirname(__FILE__)).'/../lib/Fishbowl/Inventory.php' );
    $backupDir = realpath(dirname(__FILE__))."\\".$ini_array['SC']['BACKUP_DIR'];
    
    $days = 7;
    $seconds = 3600*24;
    $maxNumFiles = 24;
    $timeVal = time();
    // connect to ftp
    $connId = ftp_connect($ini_array['SC']['FTP_HOST']);

    $login_result = ftp_login($connId, $ini_array['SC']['FTP_USER'], $ini_array['SC']['FTP_PASSWORD']);

    ftp_chdir($connId, $ini_array['SC']['INCOMING_DIR']);

    $ftpFile = ftp_nlist($connId, "/incoming");

    // backup and delete files over 7 days
    $ftpAction = $useBackup ? "backup" : "delete";
    
    $numOfFile = count($ftpFile);

    if($numOfFile>$maxNumFiles){
        for($i=0;$i<$numOfFile ;$i++){
            $difDays = ($timeVal-ftp_mdtm($connId,$ftpFile[$i]))/$seconds; 
            //echo $difDays;
            if($difDays>$days){ 
                $ftpSuccess = $useBackup 
                    ? ftp_rename($connId,"/incoming/".$ftpFile[$i],"/backup/".$ftpFile[$i])
                    : ftp_delete($connId, $ftpFile[$i]);
                if( $ftpSuccess ) {
                    echo $ftpFile[$i]." - " . $ftpAction . " was successful<br/>"; 
                    } else {
                    echo $ftpFile[$i]." - " . $ftpAction . " failed<br/>"; 
                    }        
            }else {
                echo $ftpFile[$i]."-no need to " . $ftpAction . "<br/>";
            }
        }	    
    }
    
    // close the connection
    ftp_close($connId);
       
    $end = microtime(true);
    echo "Script took ".($end - $start)." sec.<br/>";
    
?>