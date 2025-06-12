<?php
namespace  Codwelt\HelpersMan;
/**
 * Class HelpersMan
 * @author Juan Diaz - FuriosoJack <iam@furiosojack.com>
 */
class HelpersMan
{
    /**
     * Genera un string aleatorio de con los caracteres de keyspace y de un tamaño indicado con el lenght
     * @param $length  Tamaño del string a generar
     * @param string $keyspace caracteres con los que se va a generar
     * @return string
     * @deprecated se debe usar Stro::random()
     * @throws \Exception
     */
    public static function random_string($length, $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
    {
       Str::random($length,$keyspace);
    }

    /**
     * Elimina caracteres especiales saltos de linea y cualquier cosa que no sea una letra o numero
     * @param $string En el que se va a buscar y reemplazar los caracteres
     * @param string $remplaceTo por lo que se va a reemplazar los caracteres
     * @param string $regex el patron de busqueda se va a seguir para los caracteres a reemplazar
     * @return string
     */
    public static function purificate_string($string, $remplaceTo = '' , $regex = '/[^A-Za-z0-9]/')
    {
        return preg_replace($regex,$remplaceTo,$string);
    }


    /**
     * Se encarga de hace el conteo de las palabras repetidas de un texto Basado en el contado de palabras de pablo https://github.com/pabloguti/contar_palabras
     * @param $text
     * @param bool $deleteAcent
     * @param bool $distinction_lowercase
     * @param array $words_excluyed
     * @return array
     * @throws \Exception
     */
    public static function count_words_repeated($text, $deleteAcent = true, $distinction_lowercase = false, $words_excluyed = array())
    {
        if(!is_array($words_excluyed)){
            throw new \Exception("las palabras excluidas deben venir en un array");
        }

        $order = array("\r\n", "\n", "\r");
        $text = str_replace($order, " ", $text);
        //Eliminacion de intros
        $order_punt = array(",", ".", ":", ";", "(", ")", "?", "¿", "¡", "!",'"');
        $text = str_replace($order_punt, "", $text);
        //eliminacion de signos de puntuacion
        $text = htmlspecialchars_decode($text);

        //Eliminar tildes
        if($deleteAcent){
            $vocales_tilde = array("á", "é", "í", "ó", "ú", "Á", "É", "Í", "Ó", "Ú");
            $vocales_sin = array("a","e","i","o","u","A","E","I","O","U");
            $text = str_replace($vocales_tilde, $vocales_sin, $text);
        }

        if(!$distinction_lowercase){
            $text = strtolower($text);
        }

        $array_palabras = explode(" ", $text);
        $words_excluyed = implode(",",$words_excluyed);
        $excluidas = explode(",", $words_excluyed);

        $array_final = array();
        foreach ($array_palabras as $palabra)/*Recorro todo el array*/
        {
            if (!in_array($palabra, $excluidas)) {//Si la palabra no es de las excluidas
                if (isset($array_final[$palabra])) {//Compruebo si existe ya la palabra
                    $array_final[$palabra] = $array_final[$palabra] + 1;
                } else {
                    $array_final[$palabra] = 1;
                }
            }
        }
        arsort($array_final);
        return $array_final;

    }

    /**
     * Se encarga de eliminar todos los aceptos del string ingresado
     * @param string $string
     * @return mixed|string
     */
    public static function delete_acents($string = '')
    {
        $string = trim($string);
        $string = str_replace(
            array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
            array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
            $string
        );
        $string = str_replace(
            array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
            array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
            $string
        );
        $string = str_replace(
            array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
            array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
            $string
        );
        $string = str_replace(
            array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
            array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
            $string
        );
        $string = str_replace(
            array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
            array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
            $string
        );
        $string = str_replace(
            array('ñ', 'Ñ', 'ç', 'Ç'),
            array('n', 'N', 'c', 'C',),
            $string
        );
        //Esta parte se encarga de eliminar cualquier caracter extraño
        /*$string = str_replace(
            array('"\"', "¨", "º", " - ", "~",
                "#", "@", "|", "!", '"',
                "· ", "$", " % ", " & ", " / ",
                "(", ")", " ? ", "'", "¡",
                "¿", "[", "^", "<code>", "]",
                "+", "}", "{", "¨", "´",
                ">", "< ", ";", ",", ":",
                ".", " "),
            '',
            $string
        );*/
        return $string;
    }

}