<?php
/**
 *    This file is part of co
 *
 * @author    Christopher Olhoeft
 */

/**
 * Content seo config class
 */
class co_seo extends co_admindetails
{
	public function getSeoUri() {
		
		$iShopId = $this->getConfig()->getShopId();

        $sQ = "select oxseo.oxseourl from oxseo where
                   oxseo.oxobjectid = ".oxDb::getDb()->quote( $this->getEditObjectId() )." and
                   oxseo.oxshopid = '".$iShopId."' and oxseo.oxlang = ".$this->getEditLang();
        return oxDb::getDb()->getOne( $sQ, false, false );
	}
}
