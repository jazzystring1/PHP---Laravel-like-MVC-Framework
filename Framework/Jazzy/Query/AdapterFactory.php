<?php
namespace Barbet;

require_once dirname(__FILE__) . "\Exceptions.php";
require_once dirname(__FILE__) . "\Adapter\QueryFactoryAdapter.php";
require_once dirname(__FILE__) . "\Adapter\WriterAdapter.php";
require_once dirname(__FILE__) . "\Adapter\SyntaxAdapter.php";

use Barbet\BarbetExceptions;
use Barbet\WriterAdapter;
use Barbet\SyntaxAdapter;
use Barbet\Query\QueryFactoryAdapter;

class AdapterFactory
{
	public function createAdapter($type = null)
	{
		try {
			if ($type == null) {
				throw new BarbetExceptions("BarbetQueryBuilder Error : Instance of registered writer class is required as parameter to create adapter");	
			}

			if ($type instanceof Query\QueryFactory) {
				return new QueryFactoryAdapter($type);
			} 

			if ($type instanceof QueryWriter) {
				return new WriterAdapter($type);
			}

			if ($type instanceof SyntaxWriter) {
				return new SyntaxAdapter($type);
			}

			throw new BarbetExceptions("BarbetQueryBuilder Error : Class of " . get_class($type) . " is an invalid class");
		} catch(BarbetExceptions $e) {
			echo $e->getMessage();
		}
		
	}


}

?>