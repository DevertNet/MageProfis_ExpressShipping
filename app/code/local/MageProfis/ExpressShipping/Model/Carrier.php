<?php

class MageProfis_ExpressShipping_Model_Carrier
    extends Mage_Shipping_Model_Carrier_Abstract
    implements Mage_Shipping_Model_Carrier_Interface
{
    /**
     * Carrier's code, as defined in parent class
     *
     * @var string
     */
    protected $_code = 'mageprofis_expressshipping';

    /**
     * Returns available shipping rates for MageProfis_RefrigeratedShipping carrier
     *
     * @param Mage_Shipping_Model_Rate_Request $request
     * @return Mage_Shipping_Model_Rate_Result
     */
    public function collectRates(Mage_Shipping_Model_Rate_Request $request)
    {
        $result = Mage::getModel('shipping/rate_result');
        $_helper = Mage::helper('mageprofis_expressshipping');
        $timeRules = $_helper->getTimeBasedPriceArray();

        $dayOfWeek = Mage::getModel('core/date')->date('N'); //1 (für Montag) bis 7 (für Sonntag)
        $timeOfDay = Mage::getModel('core/date')->date('Gi'); //000 - 2400 Hour/Minute without leading zero
        
        $timeRuleFound = false;
        foreach($timeRules as $timeRule)
        {            
            if ( 
                $dayOfWeek >= $timeRule['day_of_week_from'] && 
                $dayOfWeek <= $timeRule['day_of_week_to'] && 
                $timeOfDay >= $timeRule['time_from'] && 
                $timeOfDay <= $timeRule['time_to']
            )
            {
                $result->append($this->_getTimeRuleRate($request, $timeRule['title'], $timeRule['price']));
                $timeRuleFound = true;
                
                break;
            }
        }
        
        if ( $_helper->showDefaultAlways() || !$timeRuleFound ) {
            $result->append($this->_getStandardRate($request));
        }
                
        return $result;
    }

    /**
     * Returns Allowed shipping methods
     *
     * @return array
     */
    public function getAllowedMethods()
    {
        return array(
            'standard'    =>  'Standard delivery',
            'timerule'    =>  'Time rule delivery',
        );
    }
    
    public function isTrackingAvailable(){
        return true;
    }

    protected function _getStandardRate(Mage_Shipping_Model_Rate_Request $request)
    {
        /** @var Mage_Shipping_Model_Rate_Result_Method $rate */
        $rate = Mage::getModel('shipping/rate_result_method');
        $_helper = Mage::helper('mageprofis_expressshipping');
        
        $price = $_helper->getDefaultPrice();
        
        $rate->setCarrier($this->_code);
        //$rate->setCarrierTitle( "im Code Carrier" );
        $rate->setMethod( "standard" );
        $rate->setMethodTitle( $_helper->getDefaultTitle() );
        $rate->setPrice( (float)$price );
        $rate->setCost( 0 );
        
        return $rate;
    }
    
    protected function _getTimeRuleRate(Mage_Shipping_Model_Rate_Request $request, $title, $price)
    {
        /** @var Mage_Shipping_Model_Rate_Result_Method $rate */
        $rate = Mage::getModel('shipping/rate_result_method');
        $helper = Mage::helper('mageprofis_expressshipping');
        
        $rate->setCarrier($this->_code);
        //$rate->setCarrierTitle( "im Code TIME Carrier" );
        $rate->setMethod( "timerule" );
        $rate->setMethodTitle( $title );
        $rate->setPrice( (float)$price );
        $rate->setCost( 0 );
        
        return $rate;
    }
}