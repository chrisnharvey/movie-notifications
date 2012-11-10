<?php if ( ! defined('BASEPATH')) exit('No direct script access');

function js($file)
{
	if(is_array($file))
	{
		$return = "";
		
		foreach($file as $file)
		{
			$return .= '<script type="text/javascript" src="'.site_url("js/".$file).'"></script>'."\n";
		}
		
		return $return;
	}
	else
	{
		return '<script type="text/javascript" src="'.site_url("js/".$file).'"></script>'."\n";
	}
}


function css($file, $media = NULL)
{
	if(is_array($file))
	{
		$return = "";
		
		foreach($file as $file)
		{
			if($media == NULL)
			{
				$return .= '<link rel="stylesheet" type="text/css" href="'.site_url("css/".$file).'" />'."\n";
			}
			else
			{
				$return .= '<link rel="stylesheet" type="text/css" href="'.site_url("css/".$file).'" media="'.$media.'" />'."\n";
			}
		}
		
		return $return;
	}
	else
	{
		if($media == NULL)
		{
			return '<link rel="stylesheet" type="text/css" href="'.site_url("css/".$file).'" />'."\n";
		}
		else
		{
			return '<link rel="stylesheet" type="text/css" href="'.site_url("css/".$file).'" media="'.$media.'" />'."\n";
		}
	}
}
