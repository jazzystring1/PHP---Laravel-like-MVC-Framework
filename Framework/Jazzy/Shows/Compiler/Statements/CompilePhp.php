<?php
namespace Compile;

trait CompilePhp
{
	public function compilePhp($expression)
	{
		return "<?php {$expression} \n";
	}

	public function compileEndphp()
	{
		return "?> \n";
	}

}

?>