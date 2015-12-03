<?php

class MageProfis_ExpressShipping_Helper_Data extends
    Mage_Core_Helper_Abstract
{
    const XML_PRICE_SURCHARGE = 'carriers/mageprofis_expressshipping/price_surcharge';
    const XML_TIME_BASED_PRICE = 'carriers/mageprofis_expressshipping/time_based_price';
    const XML_SHOW_DEFAULT = 'carriers/mageprofis_expressshipping/show_default_always';
    const XML_DEFAULT_TITLE = 'carriers/mageprofis_expressshipping/default_title';
    const XML_DEFAULT_PRICE = 'carriers/mageprofis_expressshipping/price';
    
    /**
     * Get max weight of single item for express shipping
     *
     * @return mixed
     */
    public function getPriceSurcharge()
    {
        return floatval( Mage::getStoreConfig(self::XML_PRICE_SURCHARGE) );
    }
    
    public function getTimeBasedPriceArray()
    {
        $raw = Mage::getStoreConfig(self::XML_TIME_BASED_PRICE);
        //$raw = "1-2/00:00-15:00/14.95\n3-4/1:00-2:00/13.95";
        $lines = array_filter(preg_split ('/\R/', $raw));
        $out = array();
        
        foreach( $lines as $line ){
            //validate line
            $pieces = explode("/", $line);
            if( count($pieces) <> 4 ) continue;
            
            //validate day of week
            $day_of_week = explode("-", $pieces[0]);
            if( count($day_of_week) <> 2 ) continue;
            
            //validate time
            $time = explode("-", $pieces[1]);
            if( count($time) <> 2 ) continue;
            if( !strstr($time[0], ":") || !strstr($time[1], ":") ) continue; 
            $time[0] = str_replace(":", "", $time[0]);
            $time[1] = str_replace(":", "", $time[1]);
            
            $out[] = array(
                "day_of_week_from" => intval($day_of_week[0]),
                "day_of_week_to" => intval($day_of_week[1]),
                "time_from" => intval($time[0]),
                "time_to" => intval($time[1]),
                "price" => floatval( $pieces[2] ),
                "title" => $pieces[3]
            );
        }
        
        return $out;
    }
    
    public function showDefaultAlways()
    {
        return Mage::getStoreConfig(self::XML_SHOW_DEFAULT);
    }
    
    public function getDefaultTitle()
    {
        return Mage::getStoreConfig(self::XML_DEFAULT_TITLE);
    }
    
    public function getDefaultPrice()
    {
        return Mage::getStoreConfig(self::XML_DEFAULT_PRICE);
    }
}