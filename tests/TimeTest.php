<?php
namespace Codwelt\HelpersMan\Tests;
use Codwelt\HelpersMan\HelpersMan;
use Codwelt\HelpersMan\Str;
use Codwelt\HelpersMan\Time;

/**
 * Class BasicTest
 * @package Codwelt\HelpersMan\Tests
 * @author Juan Diaz - FuriosoJack <iam@furiosojack.com>
 */
class TimeTest extends TestCase
{

    /**
     * Se testea que los años generados sean correctamente calculados
     *
     * @return void
     */
    public function test_years_with_date_corect()
    {
        $añosEsperados = 10;
        $añoIndicado = 2015;

        list($edad,$año) = Time::years_with_date($añoIndicado);

        $this->assertEquals($añosEsperados,$edad);
    }
}