<?php

$title = 'bencoder - Encode your PHP script using bcompiler';

$help =	<<<HELP

$title

Usage: bencoder file1.php file2.php ...
       bencoder -d DIR  [-rs | --re]

  -d dir : encode all files from this source directory
  --rs   : encode and remove Source file
  --re   : remove encode file

HELP;

if(!function_exists('bcompiler_write_file')) {
	print "$title\n";
	print "ERROR: Please install `bcompiler' before running this encoder\n\n";
	exit;
}

$dir = '';
$infiles = array();
$ext = 'op';
$rmSource = false;
$rmEncode = false;

for($i = 1;$i < $_SERVER['argc'];$i++) {
	switch($_SERVER['argv'][$i]) {
	case '-h':
		print $help;
		die;
		break;
	case '-d':
		if(++$i < $_SERVER['argc'])
			$dir = $_SERVER['argv'][$i];
		break;
	case '--rs':
		$rmSource = true;
		break;
	case '--re':
		$rmEncode = true;
		break;
	default:
		$infiles[] = $_SERVER['argv'][$i];
	}
}

$numfiles = count($infiles);

if($dir == '' && $numfiles == 0) {
	print "--------\n ERROR : specify -d or source file \n ----------";
}

if( $dir != '' ) {
	bencoder_process_dir( $dir);
}
if ($numfiles) {
	foreach($infiles as $infile) {
		bcompiler_encode_file($infile);
	}
}

function bencoder_process_dir($dir) {
	global $ext,$rmEncode;
	$dh = opendir($dir);
	while (($file = readdir($dh)) !== false) {
		if($file == '.' || $file == '..')
			continue;
		$dirpath = "$dir/$file";
		if(is_dir($dirpath)) {
			bencoder_process_dir( $dirpath);
		}
		elseif(is_file($dirpath)) {
			$pinfo = pathinfo($dirpath);
			if ($pinfo['extension'] == $ext) {
				if ($rmEncode) {
					unlink($dirpath);
				}
				continue;
			}else{
				bcompiler_encode_file( $dirpath);
			}
			continue;
		}
	}
	closedir($dh);
}

function bcompiler_encode_file($infile) {
	global $rmSource,$ext,$rmEncode;	
	if ($rmEncode) {
		unlink($infile.'.'.$ext);
		return;
	}

	$fh = @fopen($infile.'.'.$ext, 'w');
    if(!$fh) {
    	printf("%-50s\t%.10s\n",$infile,"failed");
    }
	bcompiler_write_header($fh);
	bcompiler_write_file($fh, $infile);
	bcompiler_write_footer($fh);
	fclose($fh);

	if ($rmSource) {
		unlink($infile);
	}
  	printf("%-50s\t%.10s\n",$infile,"encode");
}
