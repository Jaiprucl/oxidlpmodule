<?php
/**
 *    This file is part of co
 *
 * @author    Christopher Olhoeft
 */

/**
 * Base seo config class
 */
class co_lpactions_seo extends co_seo
{
    /**
     * @return string
     */
    public function render()
    {	
        parent::render();
        return 'co_seo.tpl';
    }

    /**
     *
     * @return mixed
     */
    public function save()
    {
	  $aSeoData = oxConfig::getParameter( 'aSeoData' );
	  if(!$this->existEntry()) {
        $sQ = "INSERT INTO `oxseo` (OXOBJECTID, OXIDENT, OXSHOPID, OXLANG, OXSTDURL, OXSEOURL, OXTYPE, OXFIXED, OXEXPIRED, OXPARAMS, OXTIMESTAMP ) 
			VALUES ('". $this->getEditObjectId() ."',
					'". md5(strtolower($aSeoData['oxseourl'])) ."',
					'". $this->getConfig()->getShopId(). "', 
					'". $this->getEditLang() ."', 
					'index.php?cl=landingpages&lp=". $this->getEditObjectId() ."', 
					'". $aSeoData['oxseourl'] ."',
					'dynamic',
					'0',
					'0',
					'',
					CURRENT_TIMESTAMP)";
			$sSql = oxDb::getDb()->Execute($sQ);
		}
		else {
			$sQ = "UPDATE `oxseo` SET oxseourl = '". $aSeoData['oxseourl'] ."', oxident = '". md5(strtolower($aSeoData['oxseourl'])) ."' WHERE oxobjectid = '". $this->getEditObjectId() ."' LIMIT 1;";
			$sSql = oxDb::getDb()->Execute($sQ);
		}
    }

    /**
     * Returns id of object which must be saved
     *
     * @return string
     */
    protected function _getSaveObjectId()
    {
        return $this->getEditObjectId();
    }

    /**
     * Returns object seo data
     *
     * @param string $sMetaType meta data type (oxkeywords/oxdescription)
     *
     * @return string
     */
    public function getEntryMetaData( $sMetaType )
    {
        return $this->_getEncoder()->getMetaData( $this->getEditObjectId(), $sMetaType, $this->getConfig()->getShopId(), $this->getEditLang() );
    }

	public function existEntry()
    {
        $iLang   = (int) $this->getEditLang();
        $iShopId = $this->getConfig()->getShopId();

        $sQ = "select * from oxseo where
                   oxseo.oxobjectid = ".oxDb::getDb()->quote( $this->getEditObjectId() )." and
                   oxseo.oxshopid = '{$iShopId}' and oxseo.oxlang = {$iLang} and oxparams = '' ";
        return (bool) oxDb::getDb()->getOne( $sQ, false, false );
    }

    /**
     *
     * @return int
     */
    public function getEditLang()
    {
        return $this->_iEditLang;
    }

    /**
     * Returns seo entry type
     *
     * @return string
     */
    protected function _getSeoEntryType()
    {
        return $this->_getType();
    }

    /**
     * Processes parameter before writing to db
     *
     * @param string $sParam parameter to process
     *
     * @return string
     */
    public function processParam( $sParam )
    {
        return $sParam;
    }

    /**
     *
     * @return bool
     */
    public function isEntrySuffixed()
    {
        return false;
    }

    /**
     * Returns TRUE if seo object supports suffixes. Default is FALSE
     *
     * @return bool
     */
    public function isSuffixSupported()
    {
        return false;
    }

    /**
     * Returns FALSE, as this view does not support category selector
     *
     * @return bool
     */
    public function showCatSelect()
    {
        return false;
    }

    /**
     * Returns FALSE, as this view does not support active selection type
     *
     * @return bool
     */
    public function getActCatType()
    {
        return false;
    }
}