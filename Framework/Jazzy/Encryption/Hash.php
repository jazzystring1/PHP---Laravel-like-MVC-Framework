<?php
namespace App;

class Hash
{
	public function sha1_hash($file)
	{
		return sha1($file);
	}

	public function md5_hash($file)
	{
		return md5($file);
	}
}

?>