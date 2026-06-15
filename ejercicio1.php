<?php

//Aero PHP. Trabajo Practico Evaluativo. 
//Equipo: Andino Enzo (Programador) | Mascioli Mateo (Tester)
//Objetivo: Experimentar el desarrollo de un algoritmo bajo las etapas del ciclo de vida del software.


    $opcion = 0;
    echo "¿Cuantos pasajeros van a ingresar a la Aerolinea. (Solo puede anotar un numero mayor a 0) \n";
    $es_invalido = false;
    do {
        $opcion = trim(fgets(STDIN));
        if (ctype_alpha($opcion)) { //detecta si es una letra. Si lo es, es incorrecto.
            echo "incorrecto, no puede ser una letra \n";
            $es_invalido = true;
        } 
        if (!is_numeric($opcion) || (int)$opcion <= 0) { //detecta si es un numero de punto flotante o menor que 0. Si lo es, entonces incorrecto.
            echo "tiene que ser un numero mayor a 0 \n";
            $es_invalido = true;
        } else {
            $opcion = (int)$opcion;
            $es_invalido = false;
        }

    } while ($es_invalido === true);
    $datos_clases = []; //array vacio para indicar el tipo de clase de los clientes. Economico: 1; Ejecutivo: 2; Primera clase: 3
    $pesos_peaje = []; //declaro un array vacio para llenar los pesos con los clientes respectivos.
    $recargo_USD = 0;
    $ingreso_clase = 0;
    $ingreso_switch_clase = true;
    $sobrepesos = 0;
    $clase_peaje = 0;
    $beneficio_exclusivo = 0;
    $recargo_empresa_USD = 0;
    $precio_pasaje = 0;
    $maleta_rechazada = 0;
    $eleccion_pasajero = 0;
    $eleccion_pasajero_dowhile = false;
    for ($i = 1; $i <= $opcion; $i++) {
        //aca empieza el bucle por cada cliente.
        $es_invalido = false;
        echo "Cliente numero: $i \n";
        echo "Ingrese el Tipo de Pasaje. Economico: 1; Ejecutivo: 2; Primera clase: 3 \n";
        $ingreso_clase = trim(fgets(STDIN));
        
        do {
        switch ($ingreso_clase) {
            case 1:
                echo "---Economico---\n";
                $clase_peaje = 1;
                $precio_pasaje = 60;
                break;
            case 2: 
                echo "---Ejecutivo---\n";
                $clase_peaje = 2;
                $precio_pasaje = 80;
                break;
            case 3: 
                echo "---Primera Clase---\n";
                $clase_peaje = 3;
                $precio_pasaje = 120;
                break;
            default: 
                echo "incorrecto. \n";
                echo "debe ingresar algo valido. \n";
                $ingreso_switch_clase = false;
                break;
        }
        } while ($ingreso_switch_clase === false);
        
        echo "Ingrese el peso de su equipaje.  \n";

        do {
        //El bucle Do-While se activa porque no queremos que el cliente ingrese algo menor que 0, mayor a 45 (por regla) o una letra.
        $peaje = trim(fgets(STDIN));

        if (ctype_alpha($peaje)) { //detecta si es una letra. Si lo es, es incorrecto.
            echo "incorrecto, no puede ser una letra \n";
            $es_invalido = true;
        } 
        if (!is_numeric($peaje) || (int)$peaje <= 0) { //detecta si es un numero de punto flotante o menor que 0. Si lo es, entonces incorrecto.
            echo "tiene que ser un numero mayor a 0 \n";
            $es_invalido = true;
        } else if ($peaje > 45) {
            do {
            echo "No puede ser mayor que 45kg. Por politicas internacionales, ninguna maleta puede exceder ese peso \n";
            echo "Ingrese nuevamente el peso(1) o decida pasar al siguiente pasajero(2) \n";
            $eleccion_pasajero = trim(fgets(STDIN));
            if ($eleccion_pasajero == 1) {
                $es_invalido = true;
            } else if ($eleccion_pasajero == 2) {
            
            } else {
                echo "invalido. \n";
                
            }
            //$maleta_rechazada++;
            //$es_invalido = true;
            } while ($eleccion_pasajero_dowhile === true);
        } else {
            $es_invalido = false;
        }
        //recargos fijos por peso
        if ($peaje > 23 && $peaje <=32) {
            $recargo_USD = 50;
            $sobrepesos++;
            echo "va a tener un recargo de 50USD \n";
        } else if ($peaje > 32 && $peaje <= 45){
            $recargo_USD = 100;
            echo "va a tener un recargo de 100USD \n";
            $sobrepesos++;
        }
        $recargo_empresa_USD = $recargo_USD + $precio_pasaje;
        //Seccion: Beneficio exclusivo.

        if (($peaje > 23 && $peaje <= 45) && ($clase_peaje == 3)) {
            echo "Recibió un beneficio exclusivo del 10% de descuento por ser primera clase y tener sobrepeso \n";
            $beneficio_exclusivo++;
        }


    } while ($es_invalido === true);        
    $pesos_peaje[$i] = (int)$peaje; //datos del peso por cliente.
    $datos_clases[$i] = (int)$clase_peaje; //datos del tipo de clase, sea economica, ejecutiva o primera clase
    echo "                                                 ---Cliente $i equivale a " . ($i - 1) . " en el array ---\n";
    echo "peso del equipaje: $peaje \n";
    echo "|----------------------------------------------| \n";    

}

$empresa_beneficios = $recargo_empresa_USD + ($beneficio_exclusivo*($recargo_empresa_USD*0.10));












?>