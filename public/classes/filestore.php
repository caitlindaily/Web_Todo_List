<?php

class Filestore {

    public $filename = ' ';

    function __construct($filename = ' ') 
    {
        $this->filename = $filename;
    }

    /**
     * Returns array of lines in $this->filename
     */
    function read_lines()
    {
    	$filesize = filesize($this->filename);
    	if ($filesize > 0) 
    	{
		    $content = fopen($this->filename, 'r');
		    $fileString = trim(fread($content, $this->filesize));
		    $file = explode("\n", $fileString);
		    fclose($content);
			return $file;
		} else {
			return [];
		}
	}

    /**
     * Writes each element in $array to a new line in $this->filename
     */
    function write_lines($array)
    {
    	$handle = fopen($this->filename, 'w');
	    $string = implode("\n", $list);
	    fwrite($handle, $string);
	    fclose($handle);

    }

    /**
     * Reads contents of csv $this->filename, returns an array
     */
    function read_csv()
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

    /**
     * Writes contents of $array to csv $this->filename
     */
    function write_csv($array)
    {
    	$handle = fopen($this->filename, 'w');
	    foreach ($newArray as $fields) {
			fputcsv($handle, $fields);
		}
		fclose($handle);
	}

}




