<?php defined('SYSPATH') OR die('No direct access allowed.');

class PCache_Core
{
	var $HashKey     = '';
	var $CacheFolder = Kohana::config('pchart.cache_folder');
	
	/* Create the pCache object */
	function __construct($CacheFolder=FALSE)
	{
		if ($CacheFolder !== FALSE)
		{
			$this->CacheFolder = $CacheFolder;
		}
	}
	
	/* This function is clearing the cache folder */
	function ClearCache()
	{
		if ($handle = opendir($this->CacheFolder))
		{
			while (FALSE !== ($file = readdir($handle)))
			{
				if ($file != '.' && $file != '..')
				{
					unlink($this->CacheFolder.$file);
				}
			}
			closedir($handle);
		}
	}
	
	/* This function is checking if we have an offline version of this chart */
	function IsInCache($ID, $Data, $Hash = '')
	{
		if ($Hash == '')
		{
			$Hash = $this->GetHash($ID, $Data);
		}
	
		return file_exists($this->CacheFolder.$Hash);
	}
	
	/* This function is making a copy of drawn chart in the cache folder */
	function WriteToCache($ID, $Data, $Picture)
	{
		$Hash     = $this->GetHash($ID, $Data);
		$FileName = $this->CacheFolder.$Hash;
	
		imagepng($Picture->Picture, $FileName);
	}
	
	/* This function is removing any cached copy of this chart */
	function DeleteFromCache($ID, $Data)
	{
		$Hash     = $this->GetHash($ID, $Data);
		$FileName = $this->CacheFolder.$Hash;
	
		if (file_exists($FileName ))
		{
			unlink($FileName);
		}
	}
	
	/* This function is retrieving the cached picture if applicable */
	function GetFromCache($ID, $Data)
	{
		$Hash     = $this->GetHash($ID,$Data);
		if ($this->IsInCache("","",$Hash ))
		{
			$FileName = $this->CacheFolder.$Hash;
	
			header('Content-type: image/png');
			@readfile($FileName);
			exit();
		}
	}
	
	/* This function is building the graph unique hash key */
	function GetHash($ID,$Data)
	{
		$mKey = $ID;
		foreach($Data as $key => $Values)
		{
			$tKey = "";
			foreach($Values as $Serie => $Value)
			$tKey = $tKey.$Serie.$Value;
			$mKey = $mKey.md5($tKey);
		}
		return(md5($mKey));
	}
}
