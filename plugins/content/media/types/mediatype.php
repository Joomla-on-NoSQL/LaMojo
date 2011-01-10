<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */



abstract class MediaType{
	
	/**
	 * get the code
	 * Enter description here ...
	 * @param $media
	 */
    public function getMedia($media='', $width='', $height='', $params=array()){
    	
    	return 'Invalid Server';
    }
    
    /**
     * 
     * Enter description here ...
     * @param unknown_type $media
     * @param unknown_type $width
     * @param unknown_type $height
     * @param unknown_type $params
     */
    public function getThumb($media='', $width='', $height='', $params=array()){
    	
    	return '<img scr="defaultimag">';
    }
    
}
?>
