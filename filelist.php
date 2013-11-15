<?php

function getFileList($dir,$intdir,$excl = array('.','..'), &$files=array()){
	$dir .= $intdir;
	if ($handle = opendir($dir)) {
		/* This is the correct way to loop over the directory. */
		while (false !== ($entry = readdir($handle))) {
			if(!is_link($dir.$entry)){
				if(!is_dir($dir.$entry)){
					$file['name'] = $entry;
					$file['dir'] = $dir;
					$h = hash("crc32",$file['dir'].$file['name']);
					$file['hash'] = $h;
					$files[] = $file;
				}elseif (!in_array(strtolower($entry),$excl)){
					getFileList($dir,$entry.'/',$excl,$files);
				}
			}
		}
	}
	closedir($handle);
}

function getNameByHash($hash,$dir,$excl = array('.','..')){

	$F = array();
	getFileList($dir,'',$excl,$F);
	foreach($F as $file){
		if($file['hash']==$hash){
			return $file['dir'].'/'.$file['name'];
		}
	}
	return 0;
}

function getFilesHash($dir,$excl = array('.','..')){
	$F = array();
	getFileList($dir,'',$excl,$F);
	foreach($F as $file){
		echo $file['dir'].$file['name'].' ('.$file['hash'].")\n";
	}
}

$dir = '/torrent/';
$hash = 'd5ccae9c';

#$name = getNameByHash($hash,$dir);

#echo $name."\n";

getFilesHash($dir);

?>
