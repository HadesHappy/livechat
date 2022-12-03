<?php 

/*===============================================*\
|| ############################################# ||
|| # JAKWEB.CH                                 # ||
|| # ----------------------------------------- # ||
|| # Copyright 2016 JAKWEB All Rights Reserved # ||
|| ############################################# ||
\*===============================================*/

class emailExport { 

    public static function createFile($arrays, $filename = 'chat_emails.csv') {
    
            $string = '';
            $b = 0;
            
            foreach($arrays as $array) {
                $val_array = array();
                $key_array = array();
                foreach($array as $key => $val) {
                    $key_array[] = $key;
                    $val = str_replace('"', '""', $val);
                    $val_array[] = "\"$val\"";
                }
                if($b == 0) {
                    $string .= implode(",", $key_array)."\n";
                }
                $string .= implode(",", $val_array)."\n";
                $b++;
            }
            emailExport::downloadFile($string, $filename);
        }
        
     private static function downloadFile($string, $filename) {
     	header("Pragma: public");
     	header("Expires: 0");
     	header("Cache-Control: private");
     	header("Content-type: application/octet-stream");
     	header("Content-Disposition: attachment; filename=$filename");
     	header("Accept-Ranges: bytes");
     	echo $string;
     	exit;
     }
} 

?>