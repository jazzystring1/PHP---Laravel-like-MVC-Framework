<?php
namespace Barbet;

class SyntaxWriter
{
		
		public function addTrailingCharacters($char, $columns) 
		{
			return implode($char, $columns);
		}

		public function setPlaceholder($keys = null)
		{
			if (is_array($keys)) {
				foreach($keys as $key) {
					$newKeys[] = '?';
				}
				return $this->addTrailingCharacters(", ", $newKeys);
			}
			return '?';
		}

		public function cleanFormatArray($parts, $statement = null)
		{
			foreach ($parts as $k => $v)
			{
				$statement = " " . $statement . $v;
			}
			return $statement;
		}

}


?>