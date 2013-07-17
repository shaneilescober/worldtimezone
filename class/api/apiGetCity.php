<?php
class apiGetCity extends Controller_Api
{
	protected function get($aArgs)
	{
		$connection = new modelWorldtimezone();
		$sSearch = $connection->getCity($aArgs['data']);
		
		foreach($sSearch as $val){
			$a['country'] = $val['country'];
			$a['city'] = $val['city'];
			$a['location'] = $val['location'];
			
			$aData[] = $a;
		}
		return $aData;
	}
}