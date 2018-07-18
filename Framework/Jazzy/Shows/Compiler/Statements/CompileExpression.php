<?php
namespace Compile;

trait CompileExpression
{
	public function compileVarExpression($var, $val)
	{
		if (is_string($val)) {
			return "<?php $$var = '$val'; ?> \n";
		} 
			
		if (is_array($val)) {
			$obj = array();
			$objText = '';
			foreach ($val as $k => $v) {
				if (is_object($v)) {
					array_push($obj, (array) $v);
				}
			}
			
			if($obj) {
				foreach ($obj as $k => $v) {
					$tmp = '';
					/*$objText .= "(object) array(" . urldecode(str_replace('=', ' => ', http_build_query($v, null, ', '))) . "),";*/
					foreach ($v as $key => $v) {
						$tmp .= "'{$key}' => '{$v}',";
					}
					$objText .= "(object) array({$tmp}),";
				}
				return "<?php $$var = array({$objText}); ?> \n";
			}
		}

		return "<?php $$var = $val; ?> \n";
	}
}
?>