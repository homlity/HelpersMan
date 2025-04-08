<?php 
namespace Codwelt\HelpersMan;

use Exception;

/**
 * Basado en 
 * @link https://github.com/axiacore/number-to-letter-php/blob/master/NumberToLetterConverter.class.php
 */
class NumberToLetterConverter {
  private $UNIDADES = array(
        '',
        'UN ',
        'DOS ',
        'TRES ',
        'CUATRO ',
        'CINCO ',
        'SEIS ',
        'SIETE ',
        'OCHO ',
        'NUEVE ',
        'DIEZ ',
        'ONCE ',
        'DOCE ',
        'TRECE ',
        'CATORCE ',
        'QUINCE ',
        'DIECISEIS ',
        'DIECISIETE ',
        'DIECIOCHO ',
        'DIECINUEVE ',
        'VEINTE '
  );

  private $DECENAS = array(
        'VEINTI',
        'TREINTA ',
        'CUARENTA ',
        'CINCUENTA ',
        'SESENTA ',
        'SETENTA ',
        'OCHENTA ',
        'NOVENTA ',
        'CIEN '
  );

  private $CENTENAS = array(
        'CIENTO ',
        'DOSCIENTOS ',
        'TRESCIENTOS ',
        'CUATROCIENTOS ',
        'QUINIENTOS ',
        'SEISCIENTOS ',
        'SETECIENTOS ',
        'OCHOCIENTOS ',
        'NOVECIENTOS '
  );

  private $MONEDAS = array(
    array('country' => 'Colombia', 'currency' => 'COP', 'singular' => 'PESO COLOMBIANO', 'plural' => 'PESOS COLOMBIANOS', 'symbol', '$'),
    array('country' => 'Estados Unidos', 'currency' => 'USD', 'singular' => 'DÓLAR', 'plural' => 'DÓLARES', 'symbol', 'US$'),
    array('country' => 'El Salvador', 'currency' => 'USD', 'singular' => 'DÓLAR', 'plural' => 'DÓLARES', 'symbol', 'US$'),
    array('country' => 'Europa', 'currency' => 'EUR', 'singular' => 'EURO', 'plural' => 'EUROS', 'symbol', '€'),
    array('country' => 'México', 'currency' => 'MXN', 'singular' => 'PESO MEXICANO', 'plural' => 'PESOS MEXICANOS', 'symbol', '$'),
    array('country' => 'Perú', 'currency' => 'PEN', 'singular' => 'NUEVO SOL', 'plural' => 'NUEVOS SOLES', 'symbol', 'S/'),
    array('country' => 'Reino Unido', 'currency' => 'GBP', 'singular' => 'LIBRA', 'plural' => 'LIBRAS', 'symbol', '£'),
    array('country' => 'Argentina', 'currency' => 'ARS', 'singular' => 'PESO', 'plural' => 'PESOS', 'symbol', '$')
  );

    private $separator = '.';
    private $decimal_mark = ',';
    private $glue = ' CON ';

    /**
     * Evalua si el número contiene separadores o decimales
     * formatea y ejecuta la función conversora
     * @param $number número a convertir
     * @param $miMoneda clave de la moneda
     * @return string completo
     */
    public function to_word($number, $miMoneda = null) {
        if (strpos($number, $this->decimal_mark) === FALSE) {
          $convertedNumber = array(
            $this->convertNumber($number, $miMoneda, 'entero')
          );
        } else {
          $number = explode($this->decimal_mark, str_replace($this->separator, '', trim($number)));

          $convertedNumber = array(
            $this->convertNumber($number[0], $miMoneda, 'entero'),
            $this->convertNumber($number[1], $miMoneda, 'decimal'),
          );
        }
        return trim(implode($this->glue, array_filter($convertedNumber)));
    }

    /**
     * Convierte número a letras
     * @param $number
     * @param $miMoneda     
     * @return $converted string convertido
     */
    private function convertNumber($number, $miMoneda = null) {
        $converted = '';
        
        if ($miMoneda !== null) {
            try {
                $moneda = array_filter($this->MONEDAS, function($m) use ($miMoneda) {
                    return ($m['currency'] == $miMoneda);
                });
    
                $moneda = array_values($moneda);
    
                if (count($moneda) <= 0) {
                    throw new Exception("Tipo de moneda inválido");
                }
    
                $moneda = $number < 2 ? $moneda[0]['singular'] : $moneda[0]['plural'];
            } catch (Exception $e) {
                echo $e->getMessage();
                return;
            }
        } else {
            $moneda = '';
        }
    
        // Acepta hasta billones
        if (!is_numeric($number) || $number < 0 || $number > 999999999999) {
            return false;
        }
    
        $converted = $this->convertBigNumber($number);
        $converted .= $moneda;
    
        return $converted;
    }
    
    private function convertBigNumber($number) {
        $numberStr = str_pad($number, ceil(strlen($number) / 3) * 3, '0', STR_PAD_LEFT);
        $parts = str_split($numberStr, 3);
        $numParts = count($parts);
    
        $converted = '';
        $sufijos = [
            '', // unidades
            'MIL ',
            'MILLONES ',
            'MIL MILLONES ',
            'BILLONES ',
            'MIL BILLONES ', // por si acaso
        ];
    
        foreach ($parts as $i => $part) {
            $intVal = intval($part);
            if ($intVal === 0) continue;
    
            $pos = $numParts - $i - 1;
            $texto = $this->convertGroup($part);
    
            if ($pos == 1 && $part == '001') {
                $converted .= 'MIL ';
            } else if ($pos == 2 && $part == '001') {
                $converted .= 'UN MILLON ';
            } else if ($pos == 2) {
                $converted .= $texto . 'MILLONES ';
            } else if ($pos == 3 && $part == '001') {
                $converted .= 'MIL MILLONES ';
            } else {
                $converted .= $texto . ($sufijos[$pos] ?? '');
            }
        }
    
        return $converted;
    }

    /**
     * Define el tipo de representación decimal (centenas/millares/millones)
     * @param $n
     * @return $output
     */
    private function convertGroup($n) {

        $output = '';

        if ($n == '100') {
            $output = "CIEN ";
        } else if ($n[0] !== '0') {
            $output = $this->CENTENAS[$n[0] - 1];   
        }

        $k = intval(substr($n,1));

        if ($k <= 20) {
            $output .= $this->UNIDADES[$k];
        } else {
            if(($k > 30) && ($n[2] !== '0')) {
                $output .= sprintf('%sY %s', $this->DECENAS[intval($n[1]) - 2], $this->UNIDADES[intval($n[2])]);
            } else {
                $output .= sprintf('%s%s', $this->DECENAS[intval($n[1]) - 2], $this->UNIDADES[intval($n[2])]);
            }
        }

        return $output;
    }
}