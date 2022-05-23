<?php

class FasesModelTest extends TestCase
{
    public function setUp(): void
    {
        $this->resetInstance();
        $this->CI->load->model('Fases_Model');
        $this->obj = $this->CI->Fases_Model;
    }

    public function test_eliminar()
    {  
      $actual = $this->obj->eliminar(1);
      $expected = true;
      $this->assertEquals($expected, $actual);
    }

    public function test_consultar_by_id()
    {
        $actual = $this->obj->consultar_by_id(75);
        $expected = (object) [
          'idfase' => '75', 
          'idproyecto_metodologia' => '149', 
          'nombre' => 'asd', 
          'url' => '75-asd', 
          'posicion' => '',
          'estado' => '1', 
          'created_at' => '2022-04-10 20:53:20',
          'updated_at' => '2022-04-10 20:53:20'
        ];
        $this->assertEquals($expected, $actual);
    }

    public function test_consultar_by_data()
    {
      $data_fase = [
        'idfase' => 75
      ];
      $actual = $this->obj->consultar_by_data($data_fase);
      $expected = [
        (object) [
          'idfase' => '75', 
          'idproyecto_metodologia' => '149', 
          'nombre' => 'asd', 
          'url' => '75-asd', 
          'posicion' => '',
          'estado' => '1', 
          'created_at' => '2022-04-10 20:53:20',
          'updated_at' => '2022-04-10 20:53:20'
        ]
      ];
      $this->assertEquals($expected, $actual);
    }

    public function test_ingresar()
    {  
      $data_fase = [
        'idproyecto_metodologia' => 190,
        'nombre' => 'fase prueba',
        'url' => 'fase-prueab',
        'posicion' => '1',
        'estado' => '1'
      ];
      $actual = $this->obj->ingresar($data_fase);
      $expected = true;
      $this->assertEquals($expected, $actual);
    }
    
}