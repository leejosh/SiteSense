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
function admin_buildContent($data,$db)
{
	/**
	 *	Permissions: Admin Only
	**/
	if($data->user['userLevel'] < USERLEVEL_ADMIN)
	{
		$data->output['abort'] = true;
		$data->output['abortMessage'] = '
			<h2>Insufficient Permissions</h2>
			You do not have the permissions to access this area';
			
			return;
	}
	
	// Action To Take = $data->action[2]
	// Sub User Info = $data->action[3]
	if(empty($data->action[2]))
	{
		$action = 'list';
	} else {
		$action = $data->action[2];
	}
	$target = 'admin/plugins.include.'.$action.'.php';
	// Check If Function File Exists
	if(file_exists($target))
	{
		common_include($target);	
		$data->output['function'] = $action;
	}
	// Run The Function
	if (function_exists('admin_pluginsBuild')) admin_pluginsBuild($data,$db);
	$data->output['pageTitle']='Plugins';
}

function admin_content($data)
{
	if ($data->output['abort']) {
		echo $data->output['abortMessage'];
	} else {
		if (!empty($data->output['function'])) {
			admin_pluginsShow($data);
		} else admin_unknown();
	}
}

?>