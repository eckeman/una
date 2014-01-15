<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinStudio Dolphin Studio
 * @{
 */
defined('BX_DOL') or die('hack attempt');

bx_import('BxTemplStudioPage');
bx_import('BxDolStudioStoreQuery');

class BxDolStudioDashboard extends BxTemplStudioPage {
	function __construct() {
        parent::BxTemplStudioPage('dashboard');

		//--- Check actions ---//
        if(($sAction = bx_get('dbd_action')) !== false) {
	        $sAction = bx_process_input($sAction);

            $aResult = array('code' => 1, 'message' => _t('_adm_err_cannot_process_action'));
	        switch($sAction) {
	        	/*
	        	case 'action_name':
			        $aResult = array();
			    	break;
			    */
	        }

	        if(!empty($aResult['message'])) {
                bx_import('BxDolStudioTemplate');
                $aResult['message'] = BxDolStudioTemplate::getInstance()->parseHtmlByName('page_action_result.html', array('content' => $aResult['message']));

                bx_import('BxTemplStudioFunctions');
                $aResult['message'] = BxTemplStudioFunctions::getInstance()->transBox('', $aResult['message']);
            }

	        $oJson = new Services_JSON();		        
            echo $oJson->encode($aResult);
            exit;
        }
    }

    protected function loadBlocks() {
        $aNames = array('versions', 'space');  	

        $aBlocks = array();
        foreach($aNames as $sName)
	        $aBlocks[$sName] = array(
	            'caption' => '_adm_block_cpt_' . $sName,
	            'actions' => array(), 
	            'items' => ''
	        );

        return $aBlocks;
    }

    protected function getDbSize() {
    	$iTotalSize = 0;
    	$oDb = BxDolDb::getInstance();

    	$aTables = $oDb->getAll('SHOW TABLE STATUS');
    	foreach($aTables as $aTable)
    		$iTotalSize += $aTable['Data_length'] + $aTable['Index_length'];

		return $iTotalSize;
    }

	protected function getFolderSize($sPath) {
		$iTotalSize = 0;
		$aFiles = scandir($sPath);

		$sPath = rtrim($sPath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
		foreach($aFiles as $sFile) {
			if(is_dir($sPath . $sFile))
      			if($sFile != '.' && $sFile != '..')
          			$iTotalSize += $this->getFolderSize($sPath . $sFile); 
	    	else
	      		$iTotalSize += filesize($sPath . $sFile);
		}

		return $iTotalSize;
	}
}
/** @} */