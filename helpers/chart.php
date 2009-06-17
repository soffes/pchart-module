<?php defined('SYSPATH') OR die('No direct access allowed.');

class chart_Core {

	public static function line($data, $filename = 'line.png', $width = 160, $height = 120)
	{
		$chart = new PChart($width, $height);
		$chart->setFontProperties('tahoma.ttf', 8);
		$chart->setGraphArea(1, 1, ($width - 1), ($height - 1));

		$chart->drawScale($data->GetData(), $data->GetDataDescription(), SCALE_NORMAL, 200, 200, 200, FALSE);

		// Draw the line graph
		$chart->drawLineGraph($data->GetData(), $data->GetDataDescription());

		$chart->Render($filename);
	}
	
	public static function pie($data, $filename = 'pie.png', $width = 160, $height = 160)
	{
		$chart = new PChart($width, $height);

		$radius = (($width > $height) ? ($width / 2.5) : ($height / 2.5));

		// Draw the pie chart
		$chart->setFontProperties('tahoma.ttf', 8);
		$chart->drawBasicPieGraph($data->GetData(), $data->GetDataDescription(), ($width / 2), ($height / 2), $radius, PIE_LABELS);

		$chart->Render($filename);
	}
	
	// Creates a hash for the arguments passed
	public static function hash()
	{
		$items = func_get_args();
		
		if (count($items) == 0)
		{
			return '';
		}
		
		$hash = '';
				
		return sha1(implode('|', $items));
	}
}
