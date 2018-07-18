<?php
namespace Compile;

trait CompileInclude
{
	public function compileInclude($filename)
	{
		return "<?php include({$filename}); ?> \n";
	}
}

?>