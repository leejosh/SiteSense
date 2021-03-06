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
function killHacker($reason) { 
     echo ' 
          <h1>Aborting Execution</h1> 
          <p>Hacking attempt detected - ',$reason,'</p>'; 
     die; 
} 
function common_loadPlugin(&$data,$name)
{
	if(isset($data->plugins[$name]))
	{
		return true;
	}
	
	if(file_exists('plugins/'.$name.'/plugin.php'))
	{
		common_include('plugins/'.$name.'/plugin.php');
		$objectName = 'plugin_'.$name;		
		$data->plugins[$name] = new $objectName;
		return true;
	} else {
		return false;
	}
}

function common_generateLink(&$data,$link,$text,$id = FALSE,$rel = FALSE,$class = NULL,$return = FALSE)
{
	$data->output['links'][] = array(
		'link' => $link,
		'rel' => ($rel) ? $rel : $id,
		'id' => $id
	);
	
	if(!$return)
	{
		echo '<a href="',$link,'" rel="',$rel,'" id="',$id,'" class="',$class,'">',$text,'</a>';
	} else {
		return '<a href="'.$link.'" rel="'.$rel.'" id="'.$id.'" class="'.$class.'">'.$text.'</a>';
	}
	
}
function common_isValidEmail($address) {
	if (filter_var($address,FILTER_VALIDATE_EMAIL)==FALSE) {
		return false;
	}
	/* explode out local and domain */
	list($local,$domain)=explode('@',$address);
	$localLength=strlen($local);
	$domainLength=strlen($domain);
	return (
		/* check for proper lengths */
		($localLength>0 && $localLength<65) &&
		($domainLength>3 && $domainLength<256) &&
		(
			checkdnsrr($domain,'MX') ||
			checkdnsrr($domain,'A')
		)
	);
}
function common_redirect_local($data, $where){
	common_redirect($data->linkHome . $where);
}
function common_redirect($where){
	ob_end_clean();
	header('location: ' . $where);
	exit;
}
function common_camelBack($inString) {
	return lcfirst(str_replace(
		array(
			' ',"\n","\t","\r",'&nbsp;','_'
		),'',ucwords($inString)
	));
}
function common_randomPassword($min=8,$max=12) {
	$result='';
	if ($max<$min) $max=$min;
	$count=rand($min,$max);
	while ($count>0) {
		switch (rand(1,3)) {
			case 1:
				$result.=chr(rand(48,57));
			break;
			case 2:
				$result.=chr(rand(65,90));
			break;
			case 3:
				$result.=chr(rand(97,122));
			break;
		}
		$count--;
	}
	return $result;
}
function common_hasUrlPrefix($url) {
	$urlArray=Array(
		'http:',
		'https:',
		'ftp:'
	);
	foreach ($urlArray as $urlPrefix) {
		if (stripos($url,$urlPrefix)===0) return true;
	}
	return false;
}
function common_timedRedirect($URL, $seconds = 5){
	echo _common_timedRedirect($URL);
}
function _common_timedRedirect($URL, $seconds = 5){
	return '
		<p>Click <a href="'. $URL . '">here</a> if you are not redirected in ' . $seconds . ' seconds</p>
		<script type="text/javascript">
			window.setTimeout("window.location.href = \'' . $URL . '\';", ' . ($seconds * 1000) . ');
		</script>
	';
}
function common_parseDynamicValues(&$data, &$textToParse,$db = NULL) {
	$codeReplacements=array(
		'|linkRoot|' => $data->linkRoot,
		'|imageDir|' => $data->linkRoot.'images/',	
		'|smallStaticLinkRoot|' => $data->settings['cdnSmall'],
		'|largeStaticLinkRoot|' => $data->settings['cdnLarge'],
		'|flashLinkRoot|' => $data->settings['cdnFlash'],
		'|rssLink|' => (isset($data->output['rssLink'])) ? $data->output['rssLink'] : '',
		'|attribution|' => '<p id="attribution">Powered by <a href="http://www.sitesense.org">SiteSense</a>&trade; '.$data->settings['version'].', a <a href="http://www.fullambit.com">Full Ambit Media</a> product.</p>'
	);
	foreach ($codeReplacements as $key => $value) {
		$textToParse=str_replace($key,$value,$textToParse);
	}
	
	// Any Blocks?
	preg_match_all('/\|block:([a-zA-Z0-9\s\-]+)\(?(.*?)\)?\|/',$textToParse,$matches,PREG_PATTERN_ORDER);
	//$textToParse = preg_replace('/\|loadBlock:([a-zA-Z0-9\s\-]+)\|/','',$textToParse);
	$blockList = $matches[1];
	
	ob_start();
	foreach($blockList as $key => $originalBlockName)
	{
		$blockName = common_generateShortName($originalBlockName);
		
		if(file_exists('blocks/'.$blockName.'.block.php'))
		{
			
			common_include('blocks/'.$blockName.'.block.php');
			
			$attributes = array(false);
			$attributesString = $matches[2][$key];
			$attributes = explode(',',$attributesString);
			
			$getUniqueSettings = $blockName.'_getUniqueSettings';
			$buildContent = $blockName.'_buildContent';
			$content = $blockName.'_content';
			
			if(function_exists($getUniqueSettings))
			{
				$getUniqueSettings($data,$attributes);
			}
			
			if(function_exists($buildContent) && $db !== NULL)
			{
				$buildContent($data,$db,$attributes);
			}
			
			if(function_exists($content))
			{
				$content($data,$attributes);
			}
		}
		
		$buffer = ob_get_contents();
		$textToParse = str_replace($matches[0][$key],$buffer,$textToParse);
	}	
	
	ob_end_clean();
	return $textToParse;
}

function common_generateShortName($string)
{
	$string = preg_replace("/[^a-z0-9\-\s]/",'',str_replace(' ','-',strtolower($string)));
	$string = str_replace("--","-",$string);
	$string = str_replace("--","-",$string	);
	
	return $string;
}

function common_include($includeName) {
	require_once($includeName);
}
?>