<?php
/**
 * Payment method for allowing payment by certain IPs chosen in the admin.
 *
 * @category Develo
 * @package Develo_PaymentByAdminIp
 * @author Doug Bromley <doug@develodesign.co.uk>
 * @copyright Develo Design Ltd. 2015
 * @license https://raw.githubusercontent.com/develodesign/magento-payment-by-ip/master/LICENSE MIT
 */

class Develo_PaymentByAdminIp_Model_PaymentByAdminIp extends Mage_Payment_Model_Method_Abstract
{
    protected $_code = 'paymentbyadminip';
    protected $_canUseInternal = true;

    public function isAvailable($quote = null)
    {
        if (!$quote) {
            return false;
        }

        if (parent::isAvailable($quote)) {
            if ($this->_checkCustomerIp()) {
                return true;
            }
        }

        return false;
    }

    private function _checkCustomerIp()
    {
        $customerIp = Mage::helper('core/http')->getRemoteAddr();
        $allowedIpsConfig = Mage::getStoreConfig('payment/paymentbyadminip/allowed_ips');


        if (strpos($allowedIpsConfig, ',') !== false) {
            $allowedIps = explode(',', $allowedIpsConfig);
            if (in_array($customerIp, $allowedIps)) {
                return true;
            }
        }

        if ($allowedIpsConfig == $customerIp) {
            return true;
        }

        return false;
    }
}