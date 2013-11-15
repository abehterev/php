<?php

//
// jQuery File Tree PHP Connector
//
// Version 2.00
//
// Andrey I Bekhterev
// HTTP://BEHTEREV.SU/
// 24 Oct 2013
//
// History:
//
// 1.00 - based on Cory S.N. LaViska code (24 March 2008) [http://abeautifulsite.net/], add nodir exclude for security and symlink file test
//
// Output a list of files for jQuery File Tree
//

function utf8_urldecode($str) {
    $str = preg_replace("/%u([0-9a-f]{3,4})/i","&#x\\1;",urldecode($str));
    return html_entity_decode($str,null,'UTF-8');;
}

$root_dir = '/torrent';

$nodir = array('/\.\.\//','/\.\//'); /* backdoor dir's like ./ AND . */
$hidden = array('пор');

$_POST['dir'] = preg_replace($nodir,'/',utf8_urldecode($_POST['dir']));

#echo $_POST['dir'];

if( file_exists($root_dir . $_POST['dir']) ) {
	$files = scandir($root_dir . $_POST['dir']);
	natcasesort($files); /* human-style sort */
	if( count($files) > 2 ) { /* The 2 accounts for . and .. */
		echo "<ul class=\"jqueryFileTree\" style=\"display: none;\">";
		// All dirs
                foreach( $files as $file ) {
			if(!in_array($file,$hidden)){
	                        if(!is_link($root_dir . $_POST['dir'] . $file)){
        	                        if( file_exists($root_dir . $_POST['dir'] . $file) && $file != '.' && $file != '..' ) {
                	                        if(is_dir($root_dir . $_POST['dir'] . $file)){
							echo "<li class=\"directory collapsed\"><a href=\"#\" rel=\"" . htmlspecialchars($_POST['dir'] . $file) . "/\">" . htmlspecialchars($file) . "</a></li>";
                                	        }
                                	}
                        	}
			}
                }
		// All files
		foreach( $files as $file ) {
			if(!is_link($root_dir . $_POST['dir'] . $file)){
				if( file_exists($root_dir . $_POST['dir'] . $file) && $file != '.' && $file != '..' ) {
					if(!is_dir($root_dir . $_POST['dir'] . $file)){
						$ext = preg_replace('/^.*\./', '', $file);
						$filename = $root_dir . $_POST['dir'] . $file;
						#echo $filename;
						$h = hash("crc32", $filename);
						$size = round(filesize($filename) / 1024  / 1024);
						echo "<li class=\"file ext_$ext\"><a href=\"#\" rel=\"" .
							htmlspecialchars($h) . 
							"\">" . 
							htmlspecialchars($file) . 
							" ( " . 
							$size  . 
							" Mb) [" .
							$h .
							"]</a></li>";
					}
				}
			}
		}
		echo "</ul>";   
	}
}

?>
