<?php
/**
 * Author : Ebizmarts <info@ebizmarts.com>
 * Date   : 8/6/14
 * Time   : 12:16 AM
 * File   : Userinfo.php
 * Module : Ebizmarts_Mandrill
 */
class Ebizmarts_Mandrill_Model_System_Config_Source_Userinfo
{

    /**
     * Account details storage
     *
     * @access protected
     * @var bool|array
     */
    protected $_account_details;

    /**
     * Set AccountDetails on class attribute if not already set
     *
     * @return void
     */
    public function __construct()
    {
        $storeId = Mage::app()->getStore()->getId();
        if (!$this->_account_details&&Mage::getStoreConfig( Ebizmarts_Mandrill_Model_System_Config::APIKEY,$storeId)) {
            $api = new Mandrill_Message(Mage::getStoreConfig( Ebizmarts_Mandrill_Model_System_Config::APIKEY,$storeId));
            try {
                $this->_account_details = $api->users->info();
            }
            catch(Exception $e) {
                $this->_account_details = "Invalid API key";
            }
        }
    }

    /**
     * Return data if API key is entered
     *
     * @return array
     */
    public function toOptionArray()
    {
        $helper = Mage::helper('ebizmarts_mandrill');
        if(is_array($this->_account_details)){
            return array(
                array('value' => 0, 'label' => $helper->__("<strong>Username</strong>: %s %s", $this->_account_details["username"], "<small>used for SMTP authentication</small>")),

                array('value' => 1, 'label' => $helper->__('<strong>Reputation</strong>: %s %s', $this->_account_details['reputation'], "<small>scale from 0 to 100, with 75 generally being a \"good\" reputation</small>")),

                array('value' => 2, 'label' => $helper->__('<strong>Hourly Quota</strong>: %s %s', $this->_account_details['hourly_quota'], "<small>the maximum number of emails Mandrill will deliver for this user each hour. Any emails beyond that will be accepted and queued for later delivery. Users with higher reputations will have higher hourly quotas</small>")),

                array('value' => 3, 'label' => $helper->__('<strong>Backlog</strong>: %s %s', $this->_account_details['backlog'], "<small>the number of emails that are queued for delivery due to exceeding your monthly or hourly quotas</small>"))
            );
        }else{
            return array(array('value' => '', 'label' => $helper->__('--- Enter your API KEY first ---')));
        }
    }

}
