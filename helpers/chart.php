<?php defined('SYSPATH') OR die('No direct access allowed.');

class chart_Core {

	public static function line($data, $filename = 'line.png', $width = 160, $height = 120)
	{
		$chart = new PChart($width, $height);
		$chart->setFontProperties('tahoma.ttf', 8);
		$chart->setGraphArea(1, 1, ($width - 1), ($height - 1));

		$chart->drawScale($data, SCALE_NORMAL, 200, 200, 200, FALSE);

		// Draw the line graph
		$chart->drawLineGraph($data);

		$chart->render($filename);
	}
	
	public static function pie($data, $filename = 'pie.png', $width = 160, $height = 160)
	{
		$chart = new PChart($width, $height);

		$radius = (($width > $height) ? ($width / 2.5) : ($height / 2.5));

		// Draw the pie chart
		$chart->setFontProperties('tahoma.ttf', 8);
		$chart->drawBasicPieGraph($data, ($width / 2), ($height / 2), $radius, PIE_LABELS);

		$chart->render($filename);
	}
}
