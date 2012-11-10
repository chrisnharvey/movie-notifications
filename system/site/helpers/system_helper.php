<?php if ( ! defined('BASEPATH')) exit('No direct script access');

function country_menu($selected = 226)
{
	$CI =& get_instance();
	
	$return = '<select name="country">';
	
	foreach($CI->db->get_where('countries', array('enabled' => 1))->result() as $country)
	{
		if($selected == $country->country_id || $selected == $country->short)
		{
			$return .= '<option value="'.$country->country_id.'" selected="selected">'.$country->name.'</option>';
		}
		else
		{
			$return .= '<option value="'.$country->country_id.'">'.$country->name.'</option>';
		}
	}
	
	return $return . '</select>';
}
