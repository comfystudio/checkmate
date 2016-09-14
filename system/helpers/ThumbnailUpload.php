<?php
require_once('Upload.php');
require_once('Thumbnail.php');

class Ps2_ThumbnailUpload extends Ps2_Upload {

  protected $_thumbDestination;
  protected $_deleteOriginal;
  protected $_requiredField;
  protected $_suffix = '';

  public function __construct($path, $deleteOriginal = false, $field_name, $requiredField = false) {
	parent::__construct($path, $field_name);
	$this->_thumbDestination = $path;
	$this->_deleteOriginal = $deleteOriginal;
	$this->_requiredField = $requiredField;
  }

  public function setThumbDestination($path) {
	if (!is_dir($path) || !is_writable($path)) {
	  throw new Exception("$path must be a valid, writable directory.");
	}
    $this->_thumbDestination = $path;
  }

  public function setThumbSuffix($suffix) {
	if (preg_match('/\w+/', $suffix)) {
	  if (strpos($suffix, '_') !== 0) {
	    $this->_suffix = '_' . $suffix;
	  } else {
		$this->_suffix = $suffix;
	  }
	} else {
      $this->_suffix = ''; 
	}
  }

  protected function createThumbnail($image, $number) {
	$thumb = new Ps2_Thumbnail($image);
	$thumb->setDestination($this->_thumbDestination);
	$thumb->setSuffix($this->_suffix);
	$thumb->create($number);
	$messages = $thumb->getMessages();
	$filenames = $thumb->getFilenames();
	
	$this->_messages = array_merge($this->_messages, $messages);
	$this->_filenames = $this->_filenames + $filenames;
  }
  
  protected function checkName($name, $overwrite) {
	$nospaces = str_replace(' ', '_', $name);
	if ($nospaces != $name) {
	  $this->_renamed = true;
	}
	if (!$overwrite) {
	  $existing = scandir($this->_thumbDestination);
	
	  if (InArrayCaseInsensitive($nospaces, $existing)) {
		$dot = strrpos($nospaces, '.');
		if ($dot) {
		  $base = substr($nospaces, 0, $dot);
		  $extension = substr($nospaces, $dot);
		} else {
		  $base = $nospaces;
		  $extension = '';
		}
		$i = 1;
		do {
		  $nospaces = $base . '_' . $i++ . $extension;
		} while (InArrayCaseInsensitive($nospaces, $existing));
		$this->_renamed = true;
	  }
	}
	return $nospaces;
  }

  protected function processFile($filename, $error, $size, $type, $tmp_name, $overwrite, $number) {
	
	$OK = $this->checkError($filename, $error);
	if ($OK) {
	  $sizeOK = $this->checkSize($filename, $size);
	  $typeOK = $this->checkType($filename, $type);
	  if ($sizeOK && $typeOK) {
		$name = $this->checkName($filename, $overwrite);
		$success = move_uploaded_file($tmp_name, $this->_destination . $name);
		if ($success) {
		  // don't add a message if the original image is deleted
		  /*if (!$this->_deleteOriginal) {			
			$message = "$filename uploaded successfully";
			if ($this->_renamed) {
			  $message .= " and renamed $name";
			}
			$this->_messages[] = $message;
		  }*/
		  
		  // create a thumbnail from the uploaded image
		  $this->createThumbnail($this->_destination . $name, $number);
		  
		  // delete the uploaded image if required
		  if ($this->_deleteOriginal) {
			unlink($this->_destination . $name); 
		  }
		} else {
		  $this->_messages[] = "Could not upload $filename";
		}
	  }
	}
  }

}