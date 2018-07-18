<?php
namespace Barbet;

class SyntaxAdapter
{
	private $helper = null;

	public function __construct(SyntaxWriter $writer)
	{
		$this->helper = $writer;
	}

	public function seperateChar($char, $columns)
	{
		return $this->helper->addTrailingCharacters($char, $columns);
	}

	public function writeplaceHolder($key = null)
	{
		return $this->helper->setPlaceHolder($key);
	}

	public function addQuotes($str)
	{
		return $this->helper->addQuotes($str);
	}

	public function ArrayToText($arr)
	{
		return $this->helper->cleanFormatArray($arr);
	}
}

?>