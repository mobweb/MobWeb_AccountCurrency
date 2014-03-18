<?php
class MobWeb_AccountCurrency_Model_Observer
{
	/*
	 *
	 * When the customer logs in, check if an "Account Currency" has been
	 * set for their account. If yes, update the current session currency
	 * with that currency
	 *
	 */
	public function customerLogin($observer)
	{
		if($customer = $observer->getCustomer()) {
			// Get the custom attribute from the customer object
			$accountCurrency = strtoupper($customer->getData('account_currency'));

			// Get the list of available currencies
			$codes = array_values(Mage::app()->getStore()->getAvailableCurrencyCodes(true));

			// Check if the pre-selected currency is available
			if(in_array($accountCurrency, $codes)) {
				// Select the currency for the customer
				Mage::app()->getStore()->setCurrentCurrencyCode($accountCurrency);

				//Mage::log(sprintf('Currency updated to %s', $accountCurrency), NULL, 'mobweb_accountcurrency.log');
			} else {
				//Mage::log(sprintf('Account currency %s not available in current store, available currencies: %s', $accountCurrency, implode(', ', $codes)), NULL, 'mobweb_accountcurrency.log');
			}
		}
	}

	/*
	 *
	 * This custom observer saves the newly selected currency code in
	 * the customer object, if the customer is logged in
	 *
	 */
	public function currencySwitch($observer)
	{
		if(Mage::getSingleton('customer/session')->isLoggedIn()) {
			// Get the current customer
			$customer = Mage::getSingleton('customer/session')->getCustomer();
			if($currency = $observer->getCurrency()) {
				// Save the newly selected currency in the customer object
				$customer->setData('account_currency', $currency)->save();
				//Mage::log(sprintf('Currency for customer %s switched to: %s', $customer->getId(), $currency), NULL, 'mobweb_accountcurrency.log');
			} else {
				//Mage::log('Unable to save account currency, no currency has been passed', NULL, 'mobweb_accountcurrency.log');
			}
		} else {
			//Mage::log('User isn\'t logged in, unable to update account currency', NULL, 'mobweb_accountcurrency.log');
		}
	}
}