<?php
/*
 *
 * This file installs the custom attributes into the DB. To see if it has been
 * executed properly, see the "core_resource" table in the DB. To run the
 * script again, simply delete the corresponding row from "core_resources"
 *
 * IMPORTANT: Make sure the filename of this file matches the version specified
 * in your module's config.xml. Otherwise, the script might not be executed!
 *
 */
$installer = $this;
$installer->startsetup();

// Create the custom attribute
$installer->addAttribute(
	'customer', // Some identifiers are different, e.g. 'catalog_product',
	'account_currency',
	array(
		'type' => 'varchar',
		'label' => 'Account Currency',
		'input' => 'text',
		'required' => 0,
		'default' => '',
	)
);

// Add the custom attribute to the adminhtml_customer form in the backend.
// Check the table customer_form_attribute to see if the attribute was properly
// added to the form
Mage::getSingleton('eav/config')
	->getAttribute('customer', 'account_currency')
	->setData('used_in_forms', array('adminhtml_customer'))
	->save();
	
$installer->endSetup();