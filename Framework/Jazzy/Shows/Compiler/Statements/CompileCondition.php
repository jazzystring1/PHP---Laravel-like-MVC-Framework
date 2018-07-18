<?php
namespace Compile;

trait CompileCondition
{
	public function compileIf($expression)
	{
		return "<?php if({$expression}) : ?>";
	}

	public function compileElse()
	{
		return "<?php else: ?>";
	}

	public function compileEndIf()
	{
		return "<?php endif; ?>";
	}

	public function compileIsset($expression)
	{
		return "<?php if(isset({$expression})) : ?> \n";
	}

	public function compileEndisset()
	{
		return "<?php endif; ?> \n";
	}

}

?>