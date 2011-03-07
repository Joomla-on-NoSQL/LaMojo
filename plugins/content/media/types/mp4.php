<?php
class MediaTypemp4 extends MediaType{
	
	public function getMedia($media='', $width='', $height='', $params=array()){
    	$plugin = JPluginHelper::getPlugin('content', 'media');
		$params = new JParameter($plugin->params);
		$plgParams=$params;
		$registry = new JRegistry();
		$registry->loadJSON($params);
		$params= $registry->toArray();
		$plgParams= $registry->toObject();
		$autostart=$plgParams->autostart;
		
    	$replace = '<object style="'.$width . $height.'" 
		classid="clsid:67DABFBF-D0AB-41fa-9C46-CC0F21721616"
		codebase="http://go.divx.com/plugin/DivXBrowserPlugin.cab">'
		. '<param name="src" value="'. $media .'" />'
		. '<param name="custommode" value="none" />'
		. '<param name="autoPlay" value="'.( ($autostart)? 'true': 'false' ).'" />'
		. '<param name="mode" value="mini">'
		. '<param name="allowContextMenu" value="true">'
		. '<param name="bannerEnabled" value="false">'
		. '<param name="bufferingMode" value="auto">'

		. '<embed class="mediagalleries" style="'.$width . $height.'" src="'. $media .'" type="video/divx" 
			custommode="none" autoPlay="'.( ($autostart)? 'true': 'false' ).'"  mode="mini"
			allowContextMenu="true" bannerEnabled="false" bufferingMode="auto"
			pluginspage="http://go.divx.com/plugin/download/" ></embed>'
	. '</object>';


	return $replace;
    }

}