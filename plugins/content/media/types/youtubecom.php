<?php 
function host($video, $params){
	plgContentMedia::getExtension('swf');
	$plgParams=plgContentMedia::getParams();
	
	/*$vparams[] = 'autoplay='.$autostart;
	$vparams[] = 'rel='.$plgParams->youtube_rel;//, 'advanced');
	$vparams[] = 'loop='.$plgParams->youtube_loop;//, 'advanced');
	$vparams[] = 'enablejsapi='.$plgParams->get('youtube_enablejsapi', '', 'advanced');
	$vparams[] = 'playerapiid='.$plgParams->get('youtube_playerapiid', 'advanced');
	$vparams[] = 'disablekb='.$plgParams->get('youtube_disablekb');//, 'advanced');
	$vparams[] = 'egm='.$plgParams->get('youtube_egm');//, 'advanced');
	$vparams[] = 'border='.$plgParams->get('youtube_border');//, 'advanced');
	$vparams[] = 'color1=0x'.$plgParams->get('youtube_color1');//, 'advanced');
	$vparams[] = 'color2=0x'.$plgParams->get('youtube_color2');//, 'advanced');
	*/
	if( !$params['width'] ){
		$params['width']='width: 425px;';
		$params['height']='height: 344px;';// Auto H		
		
		}
	if( strpos( $video, '/v/' ) ) {// If yes, New way
		$video = substr( strstr( $video, '/v/' ), 3 );
		$video = explode( '/', $video);
		$video = $video[0];
	}
	else{// Else, Old way		
		$video = substr( strstr( $video, 'v=' ), 2 ) ; 
		$video = explode( '&', $video);
		$video = $video[0];
	}	
		
	$player = 'http://www.youtube.com/v/'. $video .'&'. implode('&', $params);
	
	$params['a'] = '';
	$params['p'] = '';
	//Call the SWF's extension function
	return extension( $player,$params );
}