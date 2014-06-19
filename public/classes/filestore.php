<?php

class Filestore {

    public $filename = '';
    public $is_csv = false;
    
    function __construct($filename = '') 
    {
        $this->filename = $filename;

        $this->is_csv = substr($this->filename, -3) == 'csv';
    }

    public function read() 
    {
        if ($this->is_csv) {
            return $this->read_csv();
        } else {
            return $this->read_lines();
        }
    }

    public function write($array) 
    {
        if ($this->is_csv) {
            $this->write_csv($array);
        } else {
            $this->write_lines($array);
        }
    }

    private function read_lines()
    {
    	$filesize = filesize($this->filename);
    	if ($filesize > 0) 
    	{
		    $content = fopen($this->filename, 'r');
		    $fileString = trim(fread($content, $filesize));
		    $file = explode("\n", $fileString);
		    fclose($content);
			return $file;
		} else {
			return [];
		}
	}

    private function write_lines($array)
    {
    	$handle = fopen($this->filename, 'w');
	    $string = implode("\n", $array);
	    fwrite($handle, $string);
	    fclose($handle);

    }

    private function read_csv()
    {
    	$entries = [];
		$handle = fopen($this->filename, 'r');
		while (!feof($handle)) {
			$row = fgetcsv($handle);
			if(is_array($row)) {
				$entries[] = $row;
			}
		}
		fclose($handle);
		return $entries;
    }

    private function write_csv($array)
    {
    	$handle = fopen($this->filename, 'w');
	    foreach ($array as $fields) {
			fputcsv($handle, $fields);
		}
		fclose($handle);
	}

}




