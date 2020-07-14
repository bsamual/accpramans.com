<?php
function number_format_invoice($value)
{
	$explode = explode(".",$value);
	if(count($explode) > 1)
	{
		$after_decimal = substr($explode[1], 0, 2);
		if($after_decimal < 10)
		{
			$checkval = substr($after_decimal, 0, 1);
			$checkval2 = substr($after_decimal, 1, 2);
			if($checkval == 0 && $checkval2 < 10)
			{
				$after_decimal = $after_decimal;
			}
			else{
				$after_decimal = $after_decimal.'0';
			}
		}
	}
	else{
		$after_decimal = '00';
	}
	$first = add_commas((int)$explode[0]);
	return $first.'.'.$after_decimal;
}
function number_format_invoice_without_decimal($value)
{
	$explode = explode(".",$value);
	if(count($explode) > 1)
	{
		$after_decimal = substr($explode[1], 0, 2);
		if($after_decimal < 10)
		{
			$checkval = substr($after_decimal, 0, 1);
			$checkval2 = substr($after_decimal, 1, 2);
			if($checkval == 0 && $checkval2 < 10)
			{
				$after_decimal = $after_decimal;
			}
			else{
				$after_decimal = $after_decimal.'0';
			}
		}
	}
	else{
		$after_decimal = '';
	}
	$first = add_commas((int)$explode[0]);
	if($after_decimal == "")
	{
		return $first.'.00';
	}
	else{
		return $first.'.'.$after_decimal;
	}
}
function add_commas($number)
{
	return number_format($number);
}
function number_format_invoice_without_comma($value)
{
	$explode = explode(".",$value);
	if(count($explode) > 1)
	{
		$after_decimal = substr($explode[1], 0, 2);
		if($after_decimal < 10)
		{
			$checkval = substr($after_decimal, 0, 1);
			$checkval2 = substr($after_decimal, 1, 2);
			if($checkval == 0 && $checkval2 < 10)
			{
				$after_decimal = $after_decimal;
			}
			else{
				$after_decimal = $after_decimal.'0';
			}
		}
	}
	else{
		$after_decimal = '00';
	}
	$first = (int)$explode[0];
	return $first.'.'.$after_decimal;
}
?>
