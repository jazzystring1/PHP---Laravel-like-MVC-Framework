<?php
namespace Compile;

trait CompileEcho
{
	public function compileEcho($expression)
	{
		return !empty($expression) ? "<?php echo(esc({$expression})); ?>" : "<?php echo(''); ?>";
	}

	public function compileUnescapedEcho($expression)
	{
		return !empty($expression) ? "<?php echo({$expression}); ?>" : "<?php echo(''); ?>";
	}

	public function compileUntouchedEcho($expression)
	{
		return !empty($expression) ? "<?php echo('{{'.esc({$expression}).'}}'); ?>" : "<?php echo(''); ?>";
	}
}

?>