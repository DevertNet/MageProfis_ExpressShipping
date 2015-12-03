<?php
/**
 * @category   MagePsycho
 * @package    MagePsycho_Shipmentfilter
 * @author     magepsycho@gmail.com
 * @website    http://www.magepsycho.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
    */
class MageProfis_ExpressShipping_Model_Shipping extends Mage_Shipping_Model_Shipping
{
    public function collectCarrierRates($carrierCode, $request)
    {
        //if (!$this->_checkCarrierAvailability($carrierCode, $request)) {
        //    return $this;
        //}
        return parent::collectCarrierRates($carrierCode, $request);
    }
 
    protected function _checkCarrierAvailability($carrierCode, $request = null)
    {
        /*
        $refrigerationSeal = Mage::helper('mageprofis_expressshipping')->hasOneItemRefrigerationSealAttribute( $request );
        
        if($carrierCode == 'tablerate' && $refrigerationSeal){
            return false;
        }
        */
        
        return true;
    }
}