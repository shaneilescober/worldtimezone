<?php 
class modelWorldtimezone extends Model
{
	public function getSetting($seq)
    {
		$sSql = "SELECT * FROM worldtimezone_setting WHERE seq = {$seq}";
		
		$data = $this->query($sSql, row);
	
		return $data;
    }
    
    public function getCountry($sSearch)
    {
    	$sSql = "SELECT pwt_country AS country
				FROM worldtimezone_timezone 
				WHERE 
				pwt_country LIKE '%{$sSearch}%'
				OR
				pwt_gmt LIKE '%{$sSearch}%'
				OR
				pwt_city LIKE '%{$sSearch}%'
				OR
				pwt_location LIKE '%{$sSearch}%'
    			OR
    			pwt_state LIKE '%{$sSearch}%'";
    	
    	$data = $this->query($sSql);
    	return $data;
    }
    
    public function getCity($sSearch)
    {
    	$sSql = "SELECT pwt_country AS country, pwt_city AS city, pwt_location AS location
		    	FROM worldtimezone_timezone
		    	WHERE
		    	pwt_country LIKE '%{$sSearch}%'";
    	
    	$data = $this->query($sSql);
    	return $data;
    }
    
    public function checkdb($seq)
    {
    	$sSql = "SELECT COUNT(seq) as cSeq FROM worldtimezone_setting WHERE seq = {$seq}";
    	
    	$cData = $this->query($sSql, row);
    	return $cData;
    }
    
    public function insertData($aData)
    {
    	$sSql = "INSERT INTO worldtimezone_setting 
    	(seq, 
    	pws_timezone,
    	pws_format) 
    	VALUES 
    	({$aData['seq']}, 
    	'{$aData['pg_worldtimezone_selectedtimezone']}',
    	'{$aData['pg_worldtimezone_timeformat']}')";
    	
    	$bInsert = $this->query($sSql);
    	
    	if($bInsert === false)
    	{
    		return false;
    	}else{
    		return true;
    	}
    }
    
    public function updateData($aData)
    {
    	$sSql = "UPDATE worldtimezone_setting
    			SET 
    			seq = {$aData['seq']}, 
    			pws_timezone = '{$aData['pg_worldtimezone_selectedtimezone']}',
    			pws_format = {$aData['pg_worldtimezone_timeformat']} 
    			WHERE 
    			seq = {$aData['seq']}";
    	
   		$bUpdate = $this->query($sSql);
    	
    	if ($bUpdate === false)
    	{
    		return false;
    	} else {
    		return true;
    	}
    }
    
    public function getGmt($iGMT)
    {
    	$sSql = "SELECT pwt_country as country, pwt_city as city, pwt_location as location 
    	FROM worldtimezone_timezone 
    	WHERE pwt_gmt = '{$iGMT}'";
    	
    	$data = $this->query($sSql);
    	return $data;
    }
    
    public function frontSearch($sSearch){
    	$sSql = "SELECT pwt_country AS country, pwt_city AS city, pwt_location AS location
    			FROM worldtimezone_timezone
    			WHERE pwt_country LIKE '%{$sSearch}%'
    			OR
    			pwt_city LIKE '%{$sSearch}%'
    			OR 
    			pwt_state LIKE '%{$sSearch}%'
    			OR 
    			pwt_gmt = '%{$sSearch}%'";
    	
    	$data = $this->query($sSql);
    	return $data;
    }
    
    function deleteContentsBySeq($aSeq)
    {
    	$sSeqs = implode(',', $aSeq);
    	$sQuery = "Delete from worldtimezone_sequence where seq in($sSeqs)";
    	$mResult = $this->query($sQuery);
    	return $mResult;
    }
}