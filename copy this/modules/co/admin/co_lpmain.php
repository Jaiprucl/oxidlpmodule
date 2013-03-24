<?php
/**
 *    This file is part of co
 *
 * @author    Christopher Olhoeft
 */

class co_lpactions_main extends oxAdminDetails
{
    /**
     *
     * @return string
     */
    public function render()
    {
        parent::render();

        // check if we right now saved a new entry
        $soxId = $this->_aViewData["oxid"] = $this->getEditObjectId();
        if ( $soxId != "-1" && isset( $soxId)) {
            // load object
            $oAction = oxNew( "oxactions" );
            $oAction->loadInLang( $this->_iEditLang, $soxId);

            $oOtherLang = $oAction->getAvailableInLangs();
            if (!isset($oOtherLang[$this->_iEditLang])) {
                // echo "language entry doesn't exist! using: ".key($oOtherLang);
                $oAction->loadInLang( key($oOtherLang), $soxId );
            }

            $this->_aViewData["edit"] =  $oAction;

            // remove already created languages
            $aLang = array_diff ( oxRegistry::getLang()->getLanguageNames(), $oOtherLang );

            if ( count( $aLang))
                $this->_aViewData["posslang"] = $aLang;

            foreach ( $oOtherLang as $id => $language) {
                $oLang= new stdClass();
                $oLang->sLangDesc = $language;
                $oLang->selected = ($id == $this->_iEditLang);
                $this->_aViewData["otherlang"][$id] = clone $oLang;
            }
        }

        if ( oxConfig::getParameter("aoc") ) {
            // generating category tree for select list
            $sChosenArtCat = oxConfig::getParameter( "artcat");
            $sChosenArtCat = $this->_getCategoryTree( "artcattree", $sChosenArtCat, $soxId);

            $oActionsMainAjax = oxNew( 'actions_main_ajax' );
            $this->_aViewData['oxajax'] = $oActionsMainAjax->getColumns();

            return "popups/actions_main.tpl";
        }


        if ( ( $oPromotion = $this->getViewDataElement( "edit" ) ) ) {
            if ( ($oPromotion->oxactions__oxtype->value == 2) || ($oPromotion->oxactions__oxtype->value == 3) ) {
                if ( $iAoc = oxConfig::getParameter( "oxpromotionaoc" ) ) {
                    $sPopup = false;
                    switch( $iAoc ) {
                        case 'article':
                            // generating category tree for select list
                            $sChosenArtCat = oxConfig::getParameter( "artcat");
                            $sChosenArtCat = $this->_getCategoryTree( "artcattree", $sChosenArtCat, $soxId);

                            if ($oArticle = $oPromotion->getBannerArticle()) {
                                $this->_aViewData['actionarticle_artnum'] = $oArticle->oxarticles__oxartnum->value;
                                $this->_aViewData['actionarticle_title']  = $oArticle->oxarticles__oxtitle->value;
                            }

                            $sPopup = 'actions_article';
                            break;
                        case 'groups':
                            $sPopup = 'actions_groups';
                            break;
                    }

                    if ( $sPopup ) {
                        $aColumns = array();
                        $oActionsArticleAjax = oxNew( $sPopup.'_ajax' );
                        $this->_aViewData['oxajax'] = $oActionsArticleAjax->getColumns();
                        return "popups/{$sPopup}.tpl";
                    }
                } else {
                    if ( $oPromotion->oxactions__oxtype->value == 2) {
                        $this->_aViewData["editor"] = $this->_generateTextEditor( "100%", 300, $oPromotion, "oxactions__oxlongdesc", "details.tpl.css" );
                    }
                }
            }
        }

        return "co_lpactions_main.tpl";
    }


    /**
     *
     * @return mixed
     */
    public function save()
    {
        $myConfig  = $this->getConfig();

        parent::save();

        $soxId   = $oArticle->oxarticles__oxid->value;
        $aParams = oxConfig::getParameter( "editval");

        $oPromotion = oxNew( "oxactions" );
        if ( $soxId != "-1" ) {
            $oPromotion->load( $soxId );
        } else {
            $aParams['oxactions__oxid']   = null;
        }

        if ( !$aParams['oxactions__oxactive'] ) {
            $aParams['oxactions__oxactive'] = 0;
        }

        $oPromotion->setLanguage( 0 );
        $oPromotion->assign( $aParams );
        $oPromotion->setLanguage( $this->_iEditLang );
        $oPromotion = oxRegistry::get("oxUtilsFile")->processFiles( $oPromotion );
        $oPromotion->save();

        // set oxid if inserted
        $this->setEditObjectId( $oPromotion->getId() );
		
		if(!$this->existEntry()) {
			$sQ = "INSERT INTO `oxcontents` (OXID, OXLOADID, OXSHOPID, OXSNIPPET, OXTYPE, OXACTIVE, OXACTIVE_1, OXPOSITION, OXTITLE, OXCONTENT, OXTITLE_1, OXCONTENT_1, OXACTIVE_2, OXTITLE_2, OXCONTENT_2, OXACTIVE_3, OXTITLE_3, OXCONTENT_3, OXCATID, OXFOLDER, OXTERMVERSION, OXTIMESTAMP) 
					 VALUES ('".$this->getEditObjectId()."', '".$this->getEditObjectId()."', '". $this->getConfig()->getShopId() ."', 1, 0, '".$aParams['oxactions__oxactive']."', '".$aParams['oxactions__oxactive']."', '', '".$aParams["oxactions__oxtitle"]."', '".$aParams["oxcontents__oxcontent"]."', '', '', '".$aParams['oxactions__oxactive']."', '', '', '".$aParams['oxactions__oxactive']."', '', '', '', 'CMSFOLDER_USERINFO', '', CURRENT_TIMESTAMP) ";
			$sSql = oxDb::getDb()->Execute($sQ);
		}
		else {
			$sQ = "UPDATE `oxcontents` SET OXCONTENT = '".$aParams["oxcontents__oxcontent"]."', OXTITLE = '".$aParams["oxactions__oxtitle"]."' WHERE OXID = '".$this->getEditObjectId()."'";
			$sSql = oxDb::getDb()->Execute($sQ);
		}
    }

    /**
     * Saves changed selected action parameters in different language.
     *
     * @return null
     */
    public function saveinnlang()
    {
        $this->save();
    }
	
	public function getSeoText() {
		
		$iShopId = $this->getConfig()->getShopId();

        $sQ = "select oxcontents.oxcontent from oxcontents where
                   oxcontents.oxid = ".oxDb::getDb()->quote( $this->getEditObjectId() )." and
                   oxcontents.oxshopid = '".$iShopId."'";
        return oxDb::getDb()->getOne( $sQ, false, false );
	}
	
	public function existEntry()
    {
        $iShopId = $this->getConfig()->getShopId();

        $sQ = "select * from oxcontents where
                   oxcontents.oxid = ".oxDb::getDb()->quote( $this->getEditObjectId() )." and
                   oxcontents.oxshopid = '{$iShopId}'";
        return (bool) oxDb::getDb()->getOne( $sQ, false, false );
    }
}
