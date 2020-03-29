<?php
define("RandomEffects",[
	"zoompan=z='if(eq(on,1),1,zoom+%f)':x='0':y='0'", 													//Zooming In effects Start
	"zoompan=z='if(eq(on,1),1,zoom+%f)':x='0':y='ih-ih/zoom'",											//Parameter: $zoom_per_frame
	"zoompan=z='if(eq(on,1),1,zoom+%f)':x='iw-iw/zoom':y='0'",											//.
	"zoompan=z='if(eq(on,1),1,zoom+%f)':x='iw-iw/zoom':y='ih-ih/zoom'",									//.
	"zoompan=z='if(eq(on,1),1,zoom+%f)':x='0':y='(ih-ih/zoom)/2'",										//.
	"zoompan=z='if(eq(on,1),1,zoom+%f)':x='iw-iw/zoom':y='(ih-ih/zoom)/2'",								//.
	"zoompan=z='if(eq(on,1),1,zoom+%f)':x='(iw-iw/zoom)/2':y='0'",										//.
	"zoompan=z='if(eq(on,1),1,zoom+%f)':x='(iw-iw/zoom)/2':y='(ih-ih/zoom)'",							//.
	"zoompan=z='if(lte(zoom,1.0),%f,zoom-%f)':x='0':y='0'", 											//Zooming Out effects Start
	"zoompan=z='if(lte(zoom,1.0),%f,zoom-%f)':x='0':y='ih-ih/zoom'",									//Parameter: $zoom, $zoom_per_frame
	"zoompan=z='if(lte(zoom,1.0),%f,zoom-%f)':x='iw-iw/zoom':y='0'",									//.
	"zoompan=z='if(lte(zoom,1.0),%f,zoom-%f)':x='iw-iw/zoom':y='ih-ih/zoom'",							//.
	"zoompan=z='if(lte(zoom,1.0),%f,zoom-%f)':x='0':y='(ih-ih/zoom)/2'",								//.
	"zoompan=z='if(lte(zoom,1.0),%f,zoom-%f)':x='iw-iw/zoom':y='(ih-ih/zoom)/2'",						//.
	"zoompan=z='if(lte(zoom,1.0),%f,zoom-%f)':x='(iw-iw/zoom)/2':y='0'",								//.
	"zoompan=z='if(lte(zoom,1.0),%f,zoom-%f)':x='(iw-iw/zoom)/2':y='(ih-ih/zoom)'",						//.
	"zoompan=z='if(eq(on,1),%f,zoom)':x='(iw-%f*ih)/2':y='(1-on/%f)*(ih-ih/zoom)'",						//Panning effect1 paramerter: $zoom, $zoom_per_frame, $size_y/$size_x, $fps*$duration_per_image
	"zoompan=z='if(eq(on,1),%f,zoom)':x='(1-on/%f)*(iw-iw/zoom)':y='(ih-iw/%f)/2'",						//Panning effect2 paramerter: $zoom, $zoom_per_frame, $fps*$duration_per_image, $size_y/$size_x
	"zoompan=z='if(eq(on,1),%f,zoom)':x='(1-on/%f)*(iw-iw/zoom)':y='(1-on/%f)*(ih-ih/zoom)'",			//Panning effect3 paramerter: $zoom, $zoom_per_frame, $fps*$duration_per_image, $fps*$duration_per_image
]);
define("SpecialEffects",[
	"pad=w=%f:h=%f:x='(ow-iw)/2':y='(oh-ih)/2',zoompan=z='%f':x='(iw-%f*ih)/2':y='(1-on/%f)*(ih-ih/zoom)'", //Panning from bottom to top effect parameter: $height*($size_x/$size_y), $height, ($size_x/$size_y)/($width/$height), $width/$height, $fps*$duration_per_image
	"pad=w=%f:h=%f:x='(ow-iw)/2':y='(oh-ih)/2',zoompan=z='%f':x='(iw-%f*ih)/2':y='(on/%f)*(ih-ih/zoom)'",   //Panning from top to bottom effect parameter: $height*($size_x/$size_y), $height, ($size_x/$size_y)/($width/$height), $width/$height, $fps*$duration_per_image
]);
define("OutroEffect","zoompan=z='zoom+0.000'");																//Outro Effect
define("Fades",[
	"fade=t=out:st=%f:d=%f:alpha=1", 										//Intro Fade parameter: $duration_per_image - $fade_time, $fade_time
	"fade=t=in:st=0:d=%f:alpha=1,fade=t=out:st=%f:d=%f:alpha=1", 			//Middle Fade Parameter: $fade_time, $duration_per_image - $fade_time, $fade_time 
	"fade=t=in:st=0:d=%f:alpha=1" 											//Outro Fade Parameter: $fade_time
]);
class VideoCreator
{
	private $image_array = array();
	private $fps;
	private $duration_per_image;
	private $size_x;
	private $size_y;
	private $fade_time;
	private $zoom_per_frame;
	public function __construct($images = [], $fps = 25, $duration_per_image = 4, $size_x = 800, $size_y = 450, $fade_time = 1)
	{
		$this->image_array = $images;
		$this->fps = $fps;
		$this->duration_per_image = $duration_per_image;
		$this->size_x = $size_x;
		$this->size_y = $size_y;
		$this->fade_time = $fade_time;
	}
	public function generateSlideShow($output) {
		try {
			$instruction = "ffmpeg ".$this->generateParams($output);
			echo $instruction, "<br><br>";
			if (!$instruction) {
				echo "Couldn't get image"."<br>";
				return;
			}
			echo shell_exec($instruction);
				
		} catch(Exception $e) {
			return 'Caught exception: '.$e->getMessage()."<br>";
		}
		return "Generated ".$output;
	}
	public function generateParams($output)
	{
		$param_string = "-y -loglevel panic"; 			//overwrite: yes
		if (empty($this->image_array)) 
			return null;
		// concat "-i image" to param
		foreach ($this->image_array as $image) {
			$param_string .= " -i ".$image;
		}
		// filter_complex
		$param_string .= sprintf(" -filter_complex \"color=c=black:r=%f:size=%dx%d:d=%f[black];",
			$this->fps, $this->size_x, $this->size_y,($this->duration_per_image - $this->fade_time)*(count($this->image_array) - 1) + 2);
		$overlays = "";
		// Filters
		for ($i = 0; $i < count($this->image_array); $i ++) {
			list($width, $height) = getimagesize($this->image_array[$i]);
			if (!$width || !$height) 
				return null;
			$filter = sprintf("[%d:v]format=pix_fmts=yuva420p,",$i);
			
			//Effects
			if ($this->isEndImageIndex($i)) {    //Outro Effect
				$filter .= sprintf("scale=%d:-1,crop=w=%d:h=%d,%s:fps=%f:d=%f:s=%dx%d,",
					$this->size_x * $zoom * 2, $this->size_x * $zoom * 2, $this->size_y * $zoom * 2, OutroEffect, $this->fps, $this->fps, $this->size_x, $this->size_y);
			}
			else if ($height >= $width) {			//Special Effect
				$filter .= sprintf("%s:fps=%f:d=%f:s=%dx%d,",
					$this->generateSpecialPanningEffect($i, $width,$height), $this->fps, $this->fps * $this->duration_per_image, $this->size_x, $this->size_y);	
			}
			else {								//Random Effect
				$zoom = 1 + rand(20,80)/100;
				$filter .= sprintf("scale=%d:-1,crop=w=%d:h=%d,%s:fps=%f:d=%f:s=%dx%d,",
					$this->size_x * $zoom * 2, $this->size_x * $zoom * 2, $this->size_y * $zoom * 2 ,$this->generateRandomEffect($i, $zoom), $this->fps, $this->fps * $this->duration_per_image, $this->size_x, $this->size_y);
			}
			
			//Fade
			$filter .= $this->generateFade($i).sprintf(",setpts=PTS-STARTPTS+%f*%f/TB[v%d];", $i, $this->duration_per_image - $this->fade_time, $i);
			$param_string .= $filter;
			$overlays .= $this->generateOverlay($i);
		}
		$param_string .= $overlays."\" -c:v libx264 ".$output;
		return $param_string;
	}
	private function generateSpecialPanningEffect($index, $width, $height)  {
		$pan_index = rand(0,1);
		return sprintf(SpecialEffects[$pan_index], $height * ($this->size_x/$this->size_y), $height, ($this->size_x / $this->size_y)/($width / $height), $width / $height, $this->fps * $this->duration_per_image);
	}
	private function generateRandomEffect($index, $zoom) 
	{
		$zoom_per_frame = ($zoom - 1)/($this->fps*$this->duration_per_image);
		$effect_index = rand(0, sizeof(RandomEffects) - 1);
		if ($effect_index < 8) {
			return sprintf(RandomEffects[$effect_index], $zoom_per_frame);
		}
		if ($effect_index < 16) {
			return sprintf(RandomEffects[$effect_index], $zoom, $zoom_per_frame);
		}
		$ratio = $this->size_y/$this->size_x;
		$frames = $this->fps*$this->duration_per_image;
		$zoom = 1 + rand(15,40)/100;
		if ($effect_index == 16) {
			return sprintf(RandomEffects[$effect_index], $zoom, $ratio, $frames);
		}
		if ($effect_index == 17) {
			return sprintf(RandomEffects[$effect_index], $zoom, $frames, $ratio);
		}
		if ($effect_index == 18) {
			return sprintf(RandomEffects[$effect_index], $zoom, $frames, $frames);
		}
	}
	private function generateFade($index)
	{
		if ($index == 0) {
			return sprintf(Fades[0], $this->duration_per_image - $this->fade_time, $this->fade_time);
		}
		if ($this->isEndImageIndex($index)) {
			return sprintf(Fades[2], $this->fade_time);
		}
		return sprintf(Fades[1], $this->fade_time, $this->duration_per_image - $this->fade_time, $this->fade_time);
	}
	private function generateOverlay($index) 
	{
		if ($index == 0) {
			return "[black][v0]overlay[ov0];";
		}
		if ($this->isEndImageIndex($index)) {
			return sprintf("[ov%d][v%d]overlay=format=yuv420", $index - 1, $index);
		}
		return sprintf("[ov%d][v%d]overlay[ov%d];", $index - 1, $index, $index);
	}
	private function isEndImageIndex($index) 
	{
		return ($index == count($this->image_array) - 1);
	}
}