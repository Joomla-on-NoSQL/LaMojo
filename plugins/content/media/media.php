<?php
/**
 * @version		$Id: example.php 16807 2010-05-05 05:36:11Z eddieajau $
 * @package		Joomla
 * @subpackage	Content
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');
jimport( 'joomla.html.parameter' );

// This lib adds the media 
//include_once dirname(__FILE__).DS.'media'.DS.'htmlembed.php';
//include_once dirname(__FILE__).DS.'media'.DS.'thumbnails.php';
include_once dirname(__FILE__).DS.'types'.DS.'mediatype.php';


/**
 * Media Content Plugin
 *
 * @package		Joomla
 * @subpackage	Content
 * @since		1.6
 */
 
class plgContentMedia extends JPlugin
{
	
	/**
	 * Example prepare content method
	 *
	 * Method is called by the view
	 *
	 * @param	string	The context of the content being passed to the plugin.
	 * @param	object	The content objec	
	 * @param	object	The content params
	 * @param	int		The 'page' number
	 * @since	1.6
	 */
	public function onContentPrepare($context, &$row, &$params, $limitstart)
	{
		// Just to check if plg_media may be called or not, for better performance
		$makeMedia=strpos($row->text, 'media');
		$makeThumb=strpos($row->text, 'thumb');
		if ($makeMedia === false &&  $makeThumb === false) {
			return true;
		}
		
		if($makeMedia)
		{
			// Regular Expression
			$regex = '/\{media(.*?)}/i';
			$total = preg_match_all( $regex, $row->text, $matches );			
			
			// 	Default	->fixed
			$w = (int)$this->params->def('width', 400);
			$h = (int)$this->params->def('height', 0 );
			$ast = (int)$this->params->def('autostart', 0 );
		
			// Loop
			for( $x=0; $x < $total; $x++ ){
				// General Params		
				$parts = preg_replace('/\<.*?\>/', '', trim($matches[1][$x])); // Dumies Prof
				$parts = explode( ' ', $parts); // Split	
	
				// Default Vaues
				$width = $w;
				$height = ($h > 0)? $h : ($width * 0.7);
				$autostart = $ast;
			
				// Params
				$pcount = count($parts);
			
				/**Width*/
				if($pcount > 1){	
					$width 	= (is_numeric($parts[1]) && $parts[1] > 0) ? $parts[1] : $w;
					$height = $width * 0.7;
				}
				
				/**Height*/			
				if($pcount > 2){
					$height = (is_numeric($parts[2]) && $parts[2] > 0) ? $parts[2]: $h;
					$width = ($parts[1]) ? $parts[1] : $height * 1.3;
				}
				
				/**autoStart*/				
				if($pcount > 3){
					$autostart = (boolean)$parts[3];						
				}
			
				// Video Display
				$media = $parts[0];
	
				// Put Video inside the content
				$replace = self::addMedia( $media, $width, $height, $autostart );
				$replace = '<span id="media_'. $x .'" class="media" style="position:relative">'
					. $replace
				. '</span>';
				$row->text = str_replace( $matches[0][$x], $replace, $row->text );
			}
		}
			
		// Thumb
		if($makeThumb)
		{
			// Regular Expression
			$regex = '/\{thumb(.*?)}/i';
			$total = preg_match_all( $regex, $row->text, $matches );
		
			// 	Default	->fixed
			$w = (int)$this->params->def('thumbwidth', 160);
			$h = (int)$this->params->def('thumbheight');
					
			// Loop
			for( $x=0; $x < $total; $x++ ){
				// General Params		
				$parts = preg_replace('/\<.*?\>/', '', trim($matches[1][$x])); // Dumies Prof
				$parts = explode( ' ', $parts); // Split	
	
				// Default Vaues
				$width = $w;
				$height = ($h > 0)? $h : ($width * 0.7);
							
				// Params
				$pcount = count($parts);
			
				/**Width*/
				if($pcount > 1){	
					$width 	= (is_numeric($parts[1]) && $parts[1] > 0) ? $parts[1] : $w;
					$height = $width * 0.7;
				}
				
				/**Height*/			
				if($pcount > 2){
					$height = (is_numeric($parts[2]) && $parts[2] > 0) ? $parts[2]: $h;
					$width = ($parts[1]) ? $parts[1] : $height * 1.3;
				}
			
				// Video Display
				$url = $parts[0];
	
				// Put Video inside the content
				$thumbnail = self::getThumb( $url, $width, $height );
				$replace = '<span id="thumb_'. $x .'" class="thumb" style="position:relative">'
					.'<img src="'.$thumbnail.'" width="'.$width.'" height="'.$height.'">'
				. '</span>';
				$row->text = str_replace( $matches[0][$x], $replace, $row->text );
			}
		}	
		
		return true;
	}
	
	//this is the patch
	/**
	 * 
	 * @param $item
	 * @param $params
	 */
	public function onLoadMedia($item, &$params){
		// Merge params
		$this->params->merge($params);
		$params =& $this->params;
		
		// Default	->fixed
	
		$w = (int)$params->get('width', 400);
		$h = (int)$params->get('height', 0 );
		$ast = (int)$params->get('autostart', 0 );
		$item->media = self::addMedia( $item->url, $w, $h, $ast );
		return true;

	}
	
	/** The most important function! 
	 * Show Media
	 * @return str
	 * @param string $media Media URL 
	 * @param int $width [optional]
	 * @param int $width [optional]
	 * @param boolean $autoplay True if yes [optional]
	 */
	public function addMedia( $media, $width='', $height ='', $autostart=0 )
	{
		
		//Preprocess the media data to make it standard for the functions...
		$pparams	= $this->params;// make it work
		
		
		// Fix Video UrL
		$media		= strpos($media,"http://")? 
			$media: // Custom PATH
			$pparams->get('uri_img').$media; // Default PATH
		
			
		
		// Size Style
		if( $width ){			
			$height = ($height)?
				'height:'. $height .'px;': // Manual H
					'height:'. $width .'px;';// Auto H		
							
			$width = 'width:'. $width .'px;';
		}elseif( $height ){		
			$height = 'height:'. $height .'px;';
			$width = 'width:'. $height .'px;';
		}else{ 
			$height=''; 
			$width ='';
		}
		
		// AutoStart
		$autostart = (boolean)$autostart;
		
		//First Check if we have the extension...
		$type = substr( $media, strrpos($media, '.')+1 ); // type contains the text after the last '.'
		if(ctype_alnum($type)){ // If the type is alphanumeric...
			// Aha...include the file based on the extension...
			if(!file_exists(dirname(__FILE__).DS.'types'.DS.$type.'.php')){
				$embed = self::getHost("default");
				return $embed->getMedia($media, $width, $height, $params=array());
			}
			else{
				// It exists
				$embed = self::getHost($type);
				return $embed->getMedia($media, $width, $height, $params=array());
				//$embed->getMedia($media, $width, $height,$params=array());
			}
		}
		else{
			//This means that we don't know the extension of the file..
			$host=strtolower(parse_url($media,PHP_URL_HOST));
			$uexplode= explode('.',$host);
			$host='';
			foreach($uexplode as $temp){
				if(strcmp($temp,'www')){
					//Stripping Off WWW
					$host.=$temp;
				}
			} // Now host contains only what we want...like www.youtube.com is now youtubecom :D
		
			if(!file_exists(dirname(__FILE__).DS.'types'.DS.$host.'.php')){
				$embed = self::getHost("default");
				return $embed->getMedia($media, $width, $height, $params=array());
			}
			else {
				$embed = self::getHost($host);
				return $embed->getMedia($media, $width, $height, $params=array());
			}
		}
	}


	public function addMedia1( $media, $width='', $height ='', $autostart=0 )
	{	
		// The propose of this is to get the defaults set by the admin -> Fixed :D 
		$pparams	= $this->params;// make it work
		
		// Fix Video UrL
		$media		= strpos($media,"http://")? 
			$media: // Custom PATH
			$pparams->get('uri_img').$media; // Default PATH
		
		// Size Style
		if( $width ){			
			$height = ($height)?
				'height:'. $height .'px;': // Manual H
					'height:'. $width .'px;';// Auto H		
							
			$width = 'width:'. $width .'px;';
		}elseif( $height ){		
			$height = 'height:'. $height .'px;';
			$width = 'width:'. $height .'px;';
		}else{ 
			$height=''; 
			$width ='';
		}
		
		// AutoStart
		$autostart = (boolean)$autostart;
		
		
		/* ***************************************************
		 * The Show Begins
		 *****************************************************/	
		 
		/* YouTube Video 
		*****************************************************/
		//preg_match('@^(?:youtube.com)?([^/]+)@i',$media);
		if ( stripos($media, 'youtube.com' )){
			$vparams = array();
			$vparams[] = 'autoplay='.$autostart;
			$vparams[] = 'rel='.$pparams->get('youtube_rel');//, 'advanced');
			$vparams[] = 'loop='.$pparams->get('youtube_loop');//, 'advanced');
			$vparams[] = 'enablejsapi='.$pparams->get('youtube_enablejsapi', '', 'advanced');
			$vparams[] = 'playerapiid='.$pparams->get('youtube_playerapiid', 'advanced');
			$vparams[] = 'disablekb='.$pparams->get('youtube_disablekb');//, 'advanced');
			$vparams[] = 'egm='.$pparams->get('youtube_egm');//, 'advanced');
			$vparams[] = 'border='.$pparams->get('youtube_border');//, 'advanced');
			$vparams[] = 'color1=0x'.$pparams->get('youtube_color1');//, 'advanced');
			$vparams[] = 'color2=0x'.$pparams->get('youtube_color2');//, 'advanced');
		
			$replace = addVideoYoutube($media, $width, $height, $vparams );
		}
		
		/* Yahoo Video 
		*****************************************************/
		//TODO
		elseif( stripos($media,'video.yahoo') ){	
			
			$replace = addVideoYahoo($media, $width, $height, $autostart );
		}
		
		/* Google Video 
		*****************************************************/
		//To be Reviewed
		elseif( stripos($media,'video.google') ){
			
			$replace = addVideoGoogle($media, $width, $height, $autostart);
			
		}
		
		/* Brightcove Video 
		*****************************************************/
		//Need not to be there...
		elseif( stripos( $media,'brightcove.tv') ){
	
			$replace = addVideoBrightcove( $media, $width, $height, $autostart );
		}
	
		/* Metacafe.com
		*****************************************************/
		elseif( stripos( $media,'metacafe.com') ){
	
			$replace = addVideoMetacafe($media, $width, $height, $autostart );
		}
		
		/* Tangle.com
		*****************************************************/
		elseif( stripos( $media,'tangle.com') ){
	
			$replace = addVideoTangle($media, $width, $height, $autostart );
		}
		
		/* Megavideo.com
		*****************************************************/
		elseif( stripos( $media,'megavideo.com') ){
	
			$replace = addVideoMegavideo($media, $width, $height, $autostart );
		}
		
		/* Video from files
		******************************************************/ 
		else{			
			$type = substr( $media, strrpos($media, '.') );			
			$type = strtolower($type);
			switch( $type ){
				
				
			/* Flash .SWF 
			*****************************************************/
			case '.swf':		    
				$replace = addMediaSWF($media, $width, $height);
				break;				
			
			/* Music .MP3
			*****************************************************/
			case '.mp3': 	
				$vparams = array();
				$vparams['path_player'] = "plugins/content/media/media/";
				switch( $pparams->get('mp3_player') ){
					case 'jwplayer':// Play with JWPLAYER
						$vparams['flashvars'][] = 'autostart='. ( ($autostart)? 'true': 'false' );
						$vparams['flashvars'][] = 'showeq=true';
						$vparams['flashvars'][] = 'showstop=true';
						$vparams['flashvars'][] = 'fullscreen=false';
						$vparams['flashvars'][] = 'quality='. ( $pparams->get('jw_quality')? 'true': 'false' );
						$vparams['flashvars'][] = 'backcolor=0x'. $pparams->get('jw_backcolor');//, 'advanced');

						$vparams['flashvars'][] = 'frontcolor=0x'. $pparams->get('jw_frontcolor');//, 'advanced');
						$vparams['flashvars'][] = 'lightcolor=0x'. $pparams->get('jw_lightcolor');//, 'advanced');
						$vparams['flashvars'][] = 'screencolor=0x'. $pparams->get('jw_screencolor');//, 'advanced');
						if( $pparams->get('jw_logo') )	{		
							$vparams['flashvars'][] = 'logo='. JURI::base().'images/'.$pparams->get('jw_logo', 'advanced');
						}
								
						$height = 'height:100px;';
						//$width = 'width: 290px;';		
						$replace = addVideoJWPlayer($media, $width, $height, $vparams);
						break;
					
					case '1pixelout':// Play with 1PIXELOUT
					default:
						$vparams['autostart'] = ($autostart)? 'yes': 'no';
						$height = 'height:24px;';
						//$width = 'width: 290px;';	
						$replace = addMusic1Pixelout($media, $width, $height, $vparams);
						break;
						
				}			
				break;
			
			/* JPG, GIF, PNG
			*****************************************************/
			case '.jpg':
			case '.gif':
			case '.png':
				$replace = addPicture($media,$width,$height);
			 	break;
				
			/* JPG, GIF, PNG, H264
			*****************************************************/
			case '.h264':
				$vparams = array();
				$vparams['path_player'] = "plugins/content/media/media/";
				// JW PLAYER
				$vparams['flashvars'][] = 'autostart='. ( ($autostart)? 'true': 'false' );
				$vparams['flashvars'][] = 'showstop=true';
				$vparams['flashvars'][] = 'stretching=fill';
				$vparams['flashvars'][] = 'fullscreen=true';
				$vparams['flashvars'][] = 'quality='. ( $pparams->get('jw_quality')? 'true': 'false' );
				$vparams['flashvars'][] = 'backcolor=0x'. $pparams->get('jw_backcolor');//, 'advanced');
				$vparams['flashvars'][] = 'frontcolor=0x'. $pparams->get('jw_frontcolor');//, 'advanced');
				$vparams['flashvars'][] = 'lightcolor=0x'. $pparams->get('jw_lightcolor');//, 'advanced');
				$vparams['flashvars'][] = 'screencolor=0x'. $pparams->get('jw_screencolor');//, 'advanced');
				if( $pparams->get('jw_logo', '') )	{		
					$vparams['flashvars'][] = 'logo='. JURI::base().'images/'.$pparams->get('jw_logo', 'advanced');
				}
				
				$replace = addVideoJWPlayer($media, $width, $height, $vparams);
				break;
				
			/* Video .FLV
			*****************************************************/
			case '.flv': 	
				$vparams = array();
				$vparams['path_player'] = "plugins/content/media/media/";
				switch( $pparams->get('flv_player') ){
					case 'jwplayer':// Play with JWPLAYER
						$vparams['flashvars'][] = 'autostart='. ( ($autostart)? 'true': 'false' );
						$vparams['flashvars'][] = 'showstop=true';
						$vparams['flashvars'][] = 'stretching=fill';
						$vparams['flashvars'][] = 'fullscreen=true';
						$vparams['flashvars'][] = 'quality='. ( $pparams->get('jw_quality')? 'true': 'false' );
						$vparams['flashvars'][] = 'backcolor=0x'. $pparams->get('jw_backcolor');//, 'advanced');
						$vparams['flashvars'][] = 'frontcolor=0x'. $pparams->get('jw_frontcolor');//, 'advanced');
						$vparams['flashvars'][] = 'lightcolor=0x'.$pparams->get('jw_lightcolor');//, 'advanced');
						$vparams['flashvars'][] = 'screencolor=0x'. $pparams->get('jw_screencolor');//, 'advanced');
						if( $pparams->get('jw_logo', '') )	{		
							$vparams['flashvars'][] = 'logo='. JURI::base().'images/'.$pparams->get('jw_logo', 'advanced');
						}
						
						$replace = addVideoJWPlayer($media, $width, $height, $vparams);
						break;
						
					case '2kplayer':// Play with simple FLVPlayer
					default:
						$vparams['autostart'] = ($autostart)? '1': '0';
						
						$replace = addVideo2KPlayer($media, $width, $height, $vparams);
						break;
				}			
				break;				
	
						
			/* Quicktime MOV,  MP4
			************************************************/
			case '.mov':
			case '.3gp':		
			case '.mp4':
				$replace = addVideoQuicktime($media, $width, $height, $autostart);
				break;
				
			/* Realmedia .RM & .RAM
			*****************************************************/
			case '.rm':
			case '.rmvb':
			case '.ram': 	
				$replace = addVideoRealmedia($media, $width, $height, $autostart);
				break;
				
			/* Applet .CLASS
			*****************************************************/
			case '.class':				
				$replace = addAppletJava( $media, $width, $height);
				break;
			
			/* DivX
			*****************************************************/
			case '.div':
			case '.avi':
			case '.divx':
				$replace = addVideoDivx($media, $width, $height,  $autostart);
				break;
			
			/* Windows Media
			*****************************************************/
			case '.asx':
			case '.wma':
			case '.wmv':
			case '.mpg':
			case '.mpeg':
				$replace =addVideoWindows($media, $width, $height,  $autostart);
				break;
	
			/* Error
			*****************************************************/
			default: 
				
				$replace = addMediaError($media, 'Invalid Media');
				break;
			}
		}
			
		// Return video
		return $replace;
	}	

	public static function getHost($type) {
        require_once dirname(__FILE__).DS.'types'.DS.$type.'.php';
        
        $class = 'MediaType'.$type;
        $host = new $class(); 
        return $host;
    }
    
	/**
	 * getThumb Method To Get The Thumbnail Of Any Media, If Proper Thumbnail Is Not Generated A Thumbnail Based On The Media Type Is Generated
	 *
	 * Method is called by the view
	 *
	 * @param	string	The URL of the media
	 * @param	int 	Width Of Thumbnail	
	 * @param	int	 	Height Of Thumbnail
	 * @param	bool	Save On The Server Or Not
	 * @since	1.6
	 */
	public function getThumb($media , $width=160 , $height = 0)
	{
		
		
		$pparams= &$this->params;	
		
		// Fix Video UrL
		$media		= strpos($media,"http://")? 
			$media: // Custom PATH
			$pparams->get('uri_img').$media; // Default PATH

		if ( stripos($media, 'youtube.com' )){
			$replace = thumbYoutube($media, $width, $height );
		}
		
		/* Yahoo Video 
		*****************************************************/
		elseif( stripos($media,'video.yahoo') ){	
			
			$replace = thumbVideoYahoo($media, $width, $height);
		}
		
		/* Google Video 
		*****************************************************/
		elseif( stripos($media,'video.google') ){
			
			$replace = thumbVideoGoogle($media, $width, $height);
			
		}
		
		/* Brightcove Video 
		*****************************************************/
		elseif( stripos( $media,'brightcove.tv') ){
	
			$replace = thumbVideoBrightcove( $media, $width, $height );
		}
	
		/* Metacafe.com
		*****************************************************/
		elseif( stripos( $media,'metacafe.com') ){
	
			$replace = thumbVideoMetacafe($media, $width, $height );
		}
		
		/* Tangle.com
		*****************************************************/
		elseif( stripos( $media,'tangle.com') ){
	
			$replace = thumbVideoTangle($media, $width, $height);
		}
		
		/* Megavideo.com
		*****************************************************/
		elseif( stripos( $media,'megavideo.com') ){
	
			$replace = thumbVideoMegavideo($media, $width, $height);
		}
		
		/* Video from files
		******************************************************/ 
		else{			
			$type = substr( $media, strrpos($media, '.') );			
			$type = strtolower($type);
			switch( $type ){
				
			/* Flash .SWF 
			*****************************************************/
			case '.swf':		    
				$replace = thumbMediaSWF($media, $width, $height);
				break;				
			
			/* Music .MP3
			*****************************************************/
			case '.mp3': 
				$replace = thumbAudioMp3($media, $width, $height);
				break;
				
				break;
			
			/* JPG, GIF, PNG
			*****************************************************/
			case '.jpg':
			case '.gif':
			case '.png':
				$replace=thumbImage($media, $width, $height);
			 	break;
				
			/* JPG, GIF, PNG, H264
			*****************************************************/
			case '.h264':
				$replace = thumbH264($media, $width, $height);
				break;
				
			/* Video .FLV
			*****************************************************/
			case '.flv': 	
				$replace = thumbFlv($media, $width, $height);
				break;
				
						
			/* Quicktime MOV,  MP4
			************************************************/
			case '.mov':
			case '.3gp':		
			case '.mp4':
				$replace = thumbVideoQuicktime($media, $width, $height);
				break;
				
			/* Realmedia .RM & .RAM
			*****************************************************/
			case '.rm':
			case '.rmvb':
			case '.ram': 	
				$replace = thumbVideoRealmedia($media, $width, $height);
				break;
				
			/* DivX
			*****************************************************/
			case '.div':
			case '.avi':
			case '.divx':
				$replace = thumbVideoDivx($media, $width, $height);
				break;
			
			/* Windows Media
			*****************************************************/
			case '.asx':
			case '.wma':
			case '.wmv':
			case '.mpg':
			case '.mpeg':
				$replace = thumbVideoWindows($media, $width, $height);
				break;
	
			/* Error
			*****************************************************/
			default: 
				$replace = thumbDefault();
				break;
			}
		}
			
		// Return video
		return $replace;
		
		
	}
	
	public function getParams()
	{
		$db= JFactory::getDbo();
		$db->setQuery("select params from #__extensions where name='PLG_CONTENT_MEDIA' ");
		$res= $db->query();
		if($res){
			$params=$db->loadResult();
			//We get the params in serialized JSON form :) But let's reduce efforts by converting to an Array
			$reg=JRegistry::getInstance(0);
			$reg->loadJSON($params);
			$params=$reg->toArray();
			//Wuhoo we have the params...now send it...
			return $params;
		}
		else{
			return false;
		}
	}
	
	
	public function getExtension($extension)
	{
		include(JPATH_PLUGINS.DS.'content'.DS.'media'.DS.'extensions'.DS.'embed.'.$extension.'.php');
	}
}