<?php
namespace Compile;

trait CompileLoop
{
	protected $firstCase = true;
	
	public function compileFor($expression)
	{
		return "<?php for({$expression}) : ?> \n";
	}
	
	public function compileEndfor()
	{
		return "<?php endfor; ?> \n";
	}

	public function compileForeach($expression)
	{
		return "<?php foreach({$expression}) : ?>";
	}
	
	public function compileEndforeach()
	{
		return "<?php endforeach;  ?>";
	}

	public function compileSwitch($expression)
	{
		$this->firstCase = true;
		return "<?php switch({$expression}) : ";
	}

	public function compileCase($expression)
	{
		if($this->firstCase) {
			$this->firstCase = false;
			return "case {$expression} : ?>";
		}
		return "<?php case {$expression} : ?>";
	}

	public function compileEndswitch()
	{
		return "<?php endswitch;  ?>";
	}

	public function compileBreak()
	{
		return "<?php break;  ?>";
	}

	public function compileContinue()
	{
		return "<?php continue;  ?>";
	}

	public function compileDefault()
	{
		return "<?php default :   ?>";
	}
}
?>