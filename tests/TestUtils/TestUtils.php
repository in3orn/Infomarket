<?php

namespace Tests\TestUtils;

class TestUtils {
	public static function getMethod($class, $name) {
		$class = new \ReflectionClass ( $class );
		
		$method = $class->getMethod ( $name );
		$method->setAccessible ( true );
		
		return $method;
	}
}
