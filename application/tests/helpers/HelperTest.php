<?php

class HelperTest extends TestCase
{
	private $CI;
	
	public static function setUp(): void
	{
		$this->resetInstance();
		$this->CI->load->helper('general_helper');
    $this->obj = $this->CI->general_helper;
	}
	
	public function formato_fecha()
	{
		$this->assertTrue($this->obj->formato_fecha('2022-01-01'));
		$this->assertFalse($this->obj->formato_fecha('test#test.com'));
	}
}

?>