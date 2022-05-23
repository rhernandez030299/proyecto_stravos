<?php
/**
 * Part of ci-phpunit-test
 *
 * @author     Kenji Suzuki <https://github.com/kenjis>
 * @license    MIT License
 * @copyright  2015 Kenji Suzuki
 * @link       https://github.com/kenjis/ci-phpunit-test
 */

class Fases_test extends TestCase
{
  /** @test */
	public function list()
	{

    $url_proyecto = '96-cronos';
    $url_metodologia = '8-Xp';
    $output = $this->request('GET', 'proyectos/fases/96-cronos/8-Xp');

    $this->assertContains(
      '<title>Fases | Proyecto</title>', $output
    );
    
  }

}