<?php
/**
 * validarCedula()
 * validarRucPersonaNatural()
 * validarRucSociedadPrivada()
 */
class ValidarIdentificacion
{
    
    protected $error = '';

    public function validarCedula($numero = '')
    {
        // fuerzo parametro de entrada a string
        $numero = (string)$numero;

        // borro por si acaso errores de llamadas anteriores.
       // $this->setError('');

        // validaciones
        try {
            $this->validarInicial($numero, '10');
            $this->validarCodigoProvincia(substr($numero, 0, 2));
            $this->validarTercerDigito($numero[2], 'cedula');
            $this->algoritmoModulo10(substr($numero, 0, 9), $numero[9]);
        } catch (Exception $e) {
         //   $this->setError($e->getMessage());
            return false;
        }

        return true;
    }

    /**
     * Validaciones iniciales para CI y RUC
     */
    protected function validarInicial($numero, $caracteres)
    {
        if (empty($numero)) {
            throw new Exception('Valor no puede estar vacio');
        }

        if (!ctype_digit($numero)) {
            throw new Exception('Valor ingresado solo puede tener dígitos');
        }

        if (strlen($numero) != $caracteres) {
            throw new Exception('Valor ingresado debe tener '.$caracteres.' caracteres');
        }

        return true;
    }

    /**
     * Validación de código de provincia (dos primeros dígitos de CI/RUC)
     */
    protected function validarCodigoProvincia($numero)
    {
        if ($numero < 0 OR $numero > 24) {
            throw new Exception('Codigo de Provincia (dos primeros dígitos) no deben ser mayor a 24 ni menores a 0');
        }

        return true;
    }

    protected function validarTercerDigito($numero, $tipo)
    {
        switch ($tipo) {
            case 'cedula':
            case 'ruc_natural':
                if ($numero < 0 OR $numero > 5) {
                    throw new Exception('Tercer dígito debe ser mayor o igual a 0 y menor a 6 para cédulas y RUC de persona natural');
                }
                break;
            case 'ruc_privada':
                if ($numero != 9) {
                    throw new Exception('Tercer dígito debe ser igual a 9 para sociedades privadas');
                }
                break;

            case 'ruc_publica':
                if ($numero != 6) {
                    throw new Exception('Tercer dígito debe ser igual a 6 para sociedades públicas');
                }
                break;
            default:
                throw new Exception('Tipo de Identificación no existe.');
                break;
        }

        return true;
    }

    /**
     * Algoritmo Modulo10 para validar si CI y RUC de persona natural son válidos.
     *
     * Los coeficientes usados para verificar el décimo dígito de la cédula,
     * mediante el algoritmo “Módulo 10” son:  2. 1. 2. 1. 2. 1. 2. 1. 2
     *
     * Paso 1: Multiplicar cada dígito de los digitosIniciales por su respectivo
     * coeficiente.
     *
     *  Ejemplo
     *  digitosIniciales posicion 1  x 2
     *  digitosIniciales posicion 2  x 1
     *  digitosIniciales posicion 3  x 2
     *  digitosIniciales posicion 4  x 1
     *  digitosIniciales posicion 5  x 2
     *  digitosIniciales posicion 6  x 1
     *  digitosIniciales posicion 7  x 2
     *  digitosIniciales posicion 8  x 1
     *  digitosIniciales posicion 9  x 2
     *
     * Paso 2: Sí alguno de los resultados de cada multiplicación es mayor a o igual a 10,
     * se suma entre ambos dígitos de dicho resultado. Ex. 12->1+2->3
     *
     * Paso 3: Se suman los resultados y se obtiene total
     *
     * Paso 4: Divido total para 10, se guarda residuo. Se resta 10 menos el residuo.
     * El valor obtenido debe concordar con el digitoVerificador
     *
     * Nota: Cuando el residuo es cero(0) el dígito verificador debe ser 0.
     *
     * @param  string $digitosIniciales   Nueve primeros dígitos de CI/RUC
     * @param  string $digitoVerificador  Décimo dígito de CI/RUC
     *
     * @return boolean
     *
     * @throws exception Cuando los digitosIniciales no concuerdan contra
     * el código verificador.
     */
    protected function algoritmoModulo10($digitosIniciales, $digitoVerificador)
    {
        $arrayCoeficientes = array(2,1,2,1,2,1,2,1,2);

        $digitoVerificador = (int)$digitoVerificador;
        $digitosIniciales = str_split($digitosIniciales);

        $total = 0;
        foreach ($digitosIniciales as $key => $value) {

            $valorPosicion = ( (int)$value * $arrayCoeficientes[$key] );

            if ($valorPosicion >= 10) {
                $valorPosicion = str_split($valorPosicion);
                $valorPosicion = array_sum($valorPosicion);
                $valorPosicion = (int)$valorPosicion;
            }

            $total = $total + $valorPosicion;
        }

        $residuo =  $total % 10;

        if ($residuo == 0) {
            $resultado = 0;
        } else {
            $resultado = 10 - $residuo;
        }

        if ($resultado != $digitoVerificador) {
            throw new Exception('Dígitos iniciales no validan contra Dígito Idenficador');
        }

        return true;
    }
}
?>
