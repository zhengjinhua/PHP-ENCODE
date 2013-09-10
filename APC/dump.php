<?php
/**
 *apc导出opcode脚本
 *用法：
 * php dump.php [全路径目录|文件] [全路径目录|文件] ...
 */
define('DELETESCRIPT', 1);//删除编译前的脚步

error_reporting(-1);
if ($argc < 2) {
	echo 'USE: php ',$argv[0]," [FULL PATH]...";die;
}

$dumpScript = array_shift($argv);

$paths=array();
foreach ($argv as $path) {
	if (is_dir($path)){
		$paths = array_merge($paths, traverse($path));
	}elseif (is_file($path)) {
		$paths[] = $path;
	}else{
		echo 'ERROR PARAM: ',$path;die;
	}
}

foreach ($paths as $file) {
	if (preg_match("#\.php#", $file)) {
		echo 'Complile:',$file,"\n";
		complile($file);
		echo 'Output:'.$file.'.op',"\n";
	}
}
echo 'Done',"\n";

function traverse($path){
    $arr = array();
    $files = scandir($path);
    foreach ($files as $key => $file) {
        if($file=="."||$file=="..") {continue;}
          $file= $path.DIRECTORY_SEPARATOR.$file;
        if(is_dir($file)){
            $arr = array_merge($arr, traverse($file));
        }else if(is_file($file)){
            $arr[] = $file;
        }
    }
    return $arr;
}

function complile($file)
{
	apc_compile_file($file);
	apc_bin_dumpfile(array($file), null, $file.'.op');
	if (DELETESCRIPT) {
		unlink($file);
	}
}
