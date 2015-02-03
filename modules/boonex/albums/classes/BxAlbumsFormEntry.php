<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Albums Albums
 * @ingroup     TridentModules
 *
 * @{
 */

bx_import('BxBaseModTextFormEntry');

/**
 * Create/Edit entry form
 */
class BxAlbumsFormEntry extends BxBaseModTextFormEntry
{
    public function __construct($aInfo, $oTemplate = false)
    {
        $this->MODULE = 'bx_albums';
        parent::__construct($aInfo, $oTemplate);
    }

    protected function _associalFileWithContent($oStorage, $iFileId, $iProfileId, $iContentId)
    {
        parent::_associalFileWithContent($oStorage, $iFileId, $iProfileId, $iContentId);

        $sData = '';
        $aFile = $oStorage->getFile($iFileId);
        if (0 == strncmp('image/', $aFile['mime_type'], 6)) {
            bx_import('BxDolTranscoderImage');
            bx_import('BxDolImageResize');
            $oTranscoder = BxDolTranscoderImage::getObjectInstance($this->_oModule->_oConfig->CNF['OBJECT_IMAGES_TRANSCODER_BIG']);
            $a = BxDolImageResize::getImageSize($oTranscoder->getFileUrl($iFileId));
            $sData = isset($a['w']) && isset($a['h']) ? $a['w'] . 'x' . $a['h'] : '';
        }
        
        $this->_oModule->_oDb->associateFileWithContent ($iContentId, $iFileId, $this->getCleanValue('title-' . $iFileId), $sData);
    }

    function _deleteFile ($iFileId)
    {
        if (!($bRet = parent::_deleteFile ($iFileId)))
            return false;

        $this->_oModule->serviceDeleteFileAssociations ($iFileId);

        return true;
    }

    function delete ($iContentId, $aContentInfo = array())
    {
        if ($bRet = parent::delete ($iContentId, $aContentInfo))
            $this->_oModule->_oDb->deassociateFileWithContent ($iContentId, 0);
        return $bRet;
    }
}

/** @} */
