<?php
/**
 * Part of ci-phpunit-test
 *
 * @author     Kenji Suzuki <https://github.com/kenjis>
 * @license    MIT License
 * @copyright  2015 Kenji Suzuki
 * @link       https://github.com/kenjis/ci-phpunit-test
 */

class ControllersTest extends TestCase
{
  /** @test */
	public function testLoadController()
	{
    $folder = APPPATH.'controllers/';
		$this->assertTrue(is_dir($folder), 'Controllers folder found');
    if (!is_writable($folder))
      $this->markTestSkipped('Cannot write in controllers folder');
      if (!is_writable($folder.'/ControllerTest.php')) {
        // create stub file
        $success = file_put_contents($folder.'/ControllerTest.php',
                    '<?php class Stub extends CI_Controller { public function index(){} } ?>');
      if (!$success)
          $this->markTestSkipped('Cannot create test controller file');
    }
		
		// remove ControllerTest
		unlink($folder.'/ControllerTest.php');
  }

  /** @test */
	public function testLoadModel()
	{
		$folder = APPPATH.'models/';
		
		$this->assertTrue(is_dir($folder), 'Models folder found');
		
		// check if we can run the test
		if (!is_writable($folder))
			$this->markTestSkipped('Cannot write in models folder');
		
		// create a test controller
		if (!is_writable($folder.'/Stubmodel.php')) {
			// create stub file
			$success = file_put_contents($folder.'/Stubmodel.php',
									'<?php class Stubmodel extends CI_Model { public function check(){ return true; } } ?>');
			if (!$success)
				$this->markTestSkipped('Cannot create test model file');
		}
		
		// remove stub
		unlink($folder.'/Stubmodel.php');
	}
}