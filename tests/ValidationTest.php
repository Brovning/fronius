<?php

declare(strict_types=1);
include_once __DIR__.'/stubs/Validator.php';
class ValidationTest extends TestCaseSymconValidation
{
	public function testValidateCoreStubs(): void
	{
		$this->validateLibrary(__DIR__.'/../');
	}

	public function testValidateDNSSDControl(): void
	{
		$dirs = array_filter(glob(__DIR__.'/../*'), 'is_dir');

		foreach ($dirs as $dir)
		{
			if (file_exists($dir."/module.php"))
			{
				echo "\nvalidateModule(): ".$dir;
				$this->validateModule($dir);
			}
		}
	}
}
