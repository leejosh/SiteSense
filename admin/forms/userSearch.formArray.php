<?php
/*
* SiteSense
*
* NOTICE OF LICENSE
*
* This source file is subject to the Open Software License (OSL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/osl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@sitesense.org so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade SiteSense to newer
* versions in the future. If you wish to customize SiteSense for your
* needs please refer to http://www.sitesense.org for more information.
*
* @author     Full Ambit Media, LLC <pr@fullambit.com>
* @copyright  Copyright (c) 2011 Full Ambit Media, LLC (http://www.fullambit.com)
* @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*/
$this->action=$data->linkRoot.'admin/users/search/';
$this->formPrefix='searchUser_';
$this->caption='Searching Users';
$this->submitTitle='Search';
$this->fromForm='searchUser';
global $languageText;
$levelOptions = array(array('value' => '-100', 'text' => 'All'));
foreach($languageText['userLevels'] as $value => $text){
	$levelOptions[] = array('value' => $value, 'text' => $text);
}
$this->fields=array(
	'name' => array(
		'label' => 'Username',
		'required' => false,
		'tag' => 'input',
		'value' => '%',
		'params' => array(
			'type' => 'text',
			'size' => 128
		)
	),
	'fullName' => array(
		'label' => 'Full Name',
		'required' => false,
		'tag' => 'input',
		'value' => '%',
		'params' => array(
			'type' => 'text',
			'size' => 128
		)
	),
	'userLevel' => array(
		'label' => 'User Access Level',
		'required' => false,
		'tag' => 'select',
		'value' => '-100',
		'options' => $levelOptions,
	),
);
