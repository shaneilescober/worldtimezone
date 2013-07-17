<?php
class apiGetDatetime extends Controller_Api
{
	protected function get($aArgs)
	{
		require_once('builder/builderInterface.php');
		usbuilder()->init($this, $aArgs);
		
		$aResult = array();
		if( $aArgs['data'] != ""){
			$sLocation = $aArgs['data'];
		}else{
			$sLocation = "America/Anchorage";
		}
		$dateTimeZone = new DateTime("now", new DateTimeZone($sLocation));
		
		$aResult['tz'] = $sLocation;
		$aResult['datetime'] = $dateTimeZone->format("r"); //date('D, d M Y H:i:s', strtotime($newArray['date'])) . " " . date('O', date_default_timezone_set($sLocation)); //date('D, d M Y H:i:s', strtotime($newArray['date'])) . " " . date('O', date_default_timezone_set($sLocation));
		$aResult['hour'] = $dateTimeZone->format("H");
		$aResult['second'] = (int) $dateTimeZone->format("s");
		$aResult['minute'] = (int) $dateTimeZone->format("i");
		$aResult['gmt'] = $dateTimeZone->format("O");
		
		return $aResult;
	}
}