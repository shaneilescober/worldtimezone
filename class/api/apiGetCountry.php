<?php
class apiGetCountry extends Controller_Api
{
	protected function get($aArgs)
	{
		$connection = new modelWorldtimezone();
		$sSearch = $connection->getCountry($aArgs['data']);
		
		foreach($sSearch as $val){
			$a['country'] = $val['country'];
			$aData[] = $a;
		}
		return $aData;
	}
}