<?php 
class adminPageSettings extends Controller_Admin
{
	protected function run($aArgs)
	{
		require_once('builder/builderInterface.php');
		usbuilder()->init($this, $aArgs);
		
		$sFormScript = usbuilder()->getFormAction('worldtimezone_form', 'adminExecWorldTimezone');
		$this->writeJs($sFormScript);
		$this->assign("sUrl", common()->getFullUrl());
        $this->assign("seq", $aArgs['seq']);
        $this->assign("bExtensionView", ($aArgs['etype'] ? 1 : 0));
		
		$this->importCss('setup');
		$this->display($aArgs);
	}
	
	public function display($aArgs)
	{
		require_once('builder/builderInterface.php');
		usbuilder()->init($this, $aArgs);
		
		$this->importJS('worldtimezone.setup');
		
		$fetchedData = common()->modelContents()->getSetting($aArgs['seq']);
		$this->assign('aData', $fetchedData);
		
		$this->view(__CLASS__);
	}
}