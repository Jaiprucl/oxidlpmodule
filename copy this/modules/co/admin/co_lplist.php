<?php
/**
 *    This file is part of co
 *
 * @author    Christopher Olhoeft
 */

class co_lpactions_list extends oxAdminList
{
    /**
     * @var string
     */
    protected $_sThisTemplate = 'co_lpactions_list.tpl';

    /**
     *
     * @var string
     */
    protected $_sListClass = 'oxactions';

    /**
     *
     * @var string
     */
    protected $_sDefSortField = 'oxtitle';

    /**
     *
     * @return string
     */
    public function render()
    {
        parent::render();

        // passing display type back to view
        $this->_aViewData["displaytype"] = oxConfig::getParameter( "displaytype" );

        return $this->_sThisTemplate;
    }

    /**
     * Adds active promotion check
     *
     * @param array  $aWhere  SQL condition array
     * @param string $sqlFull SQL query string
     *
     * @return $sQ
     */
    protected function _prepareWhereQuery( $aWhere, $sqlFull )
    {
		$key = $this->displayTree($aWhere);
		
        $sQ = substr($sqlFull, 0, -2) . "oxtitle LIKE '%" . $key ."%' AND OXTYPE = 4";
        $sDisplayType = (int) oxConfig::getParameter( 'displaytype' );
        $sTable = getViewName( "oxactions" );

        //searchong for empty oxfolder fields
        if ( $sDisplayType ) {

            $sNow   = date( 'Y-m-d H:i:s', oxRegistry::get("oxUtilsDate")->getTime() );

            switch ( $sDisplayType ) {
                case 1: // active
                    $sQ .= " and {$sTable}.oxactivefrom < '{$sNow}' and {$sTable}.oxactiveto > '{$sNow}' ";
                    break;
                case 2: // upcoming
                    $sQ .= " and {$sTable}.oxactivefrom > '{$sNow}' ";
                    break;
                case 3: // expired
                    $sQ .= " and {$sTable}.oxactiveto < '{$sNow}' and {$sTable}.oxactiveto != '0000-00-00 00:00:00' ";
                    break;
            }
        }
        return $sQ;
    }
	
	protected function displayTree($var) {

     foreach($var as $key => $value) {
         if (is_array($value) || is_object($value)) {
             $value = displayTree($value);
         }

         if (is_array($var)) {
             if (!stripos($value, "")) {
                $output .= $value;
             }
             else {
                $output .= $value;
             }
         }
         else { // is_object
            if (!stripos($value, "")) {
               $value = $value;
            } 
            
            $output .= $value;
         }
     }
     return $output;
	}
}