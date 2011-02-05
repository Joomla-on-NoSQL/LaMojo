<?php
class MediaTypedefault extends MediaType{
	public function getMedia($media='', $width='', $height='', $params=array()){
    	
    	return "<a href='$media' target='_blank'>$media</a>";
    }
}