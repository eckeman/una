<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Events Events
 * @ingroup     TridentModules
 *
 * @{
 */

/**
 * Entries user joined to.
 */
class BxEventsPageJoinedEntries extends BxBaseModGroupsPageJoinedEntries
{
    public function __construct($aObject, $oTemplate = false)
    {
        parent::__construct($aObject, $oTemplate);
    }

}

/** @} */
