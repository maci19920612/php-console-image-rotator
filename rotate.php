<?php


function execute($directory){
	echo "Convert started: " . $directory . "\n";
	$files = [];
	foreach (new DirectoryIterator($directory) as $fileInfo) {
		if($fileInfo->isDot()) continue;
		$ext = $fileInfo->getExtension();
		if(strtolower($ext) != "png") continue;
		$fileName = $fileInfo->getFilename();
		array_push($files, $fileName);
	}
	@unlink($directory . "/output");
	if(!file_exists($directory . "/output")){
		mkdir($directory . "/output");
	}
	usort($files,function($str1, $str2){
		$str1Len = strlen($str1);
		$str2Len = strlen($str2);
		if($str1Len > $str2Len) return 1;
		if($str1Len < $str2Len) return -1;
		for($i = 0;$i<$str1Len;$i++){
			if($str1[$i] > $str2[$i]) return 1; 
			if($str1[$i] < $str2[$i]) return -1; 
		}
		return 0;
	});
	for($i = 0;$i < count($files);$i++){
		echo $i;
		echo "\rConverting: " . ($i*100 / count($files)) . "%";
		
		
		
		$degrees = 180;
		$source = imagecreatefrompng($directory . "/" . $files[$i]);
		$rotate = imagerotate($source, $degrees, 0);
		if($i % 2 == 1){
			imagejpeg($source, $directory . "/output/" . $files[$i], 80);
		}else{
			imagejpeg($rotate, $directory . "/output/" . $files[$i], 80);
		}
		// Output
		
		// Free the memory
		imagedestroy($source);
		imagedestroy($rotate);
	}
	
	echo "\rConverting: 100%\n\n";
}

$dir = dirname(__FILE__);
execute($dir . "/1");
execute($dir . "/2");
execute($dir . "/3");

?>