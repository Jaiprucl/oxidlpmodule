<?php
/**
 * @author Christopher Olhoeft
 */

/**
 * Starting shop page.
 * Shop starter, manages starting visible articles, etc.
 */
class landingpages extends oxUBase
{

    /**
     * Current class template name.
     * @var string
     */
    protected $_sThisTemplate = 'landingpages.tpl';

    /**
	 * Render
     */
    public function render()
    {
        if ( oxConfig::getParameter( 'showexceptionpage' ) == '1' ) {
            return 'message/exception.tpl';
        }

        parent::render();

        return $this->_sThisTemplate;
    }

    /**
     * Template variable getter. Returns newest article list
     *
     * @return array
     */
    public function getLandingPageArticles()
    {
        if ( $this->_aLandingPageArticles === null ) {
            $this->_aLandingPageArticles = array();{
                // newest articles
                $oArtList = oxNew( 'oxarticlelist' );
                $oArtList->loadActionArticles( $_GET['lp'] );

                if ( $oArtList->count() ) {
                    $this->_aLandingPageArticles = $oArtList;
                }
            }
        }
        return $this->_aLandingPageArticles;
    }
}