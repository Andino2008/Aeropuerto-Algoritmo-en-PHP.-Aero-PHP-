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
    $total_recaudado_sobrepeso = 0; // Para acumular solo la plata de los recargos
    $eleccion_pasajero = 0;
    $eleccion_pasajero_dowhile = false;
    for ($i = 1; $i <= $opcion; $i++) {
        // Aca empieza el bucle por cada cliente.
        $es_invalido = false;
        $recargo_USD = 0; // REINICIO CLAVE: Evita que un pasajero arrastre el recargo del anterior
        
        echo "Cliente numero: $i \n";
        echo "Ingrese el Tipo de Pasaje. Economico: 1; Ejecutivo: 2; Primera clase: 3 \n";
        
        // El do-while del pasaje (corregido el doble fgets)
        do {
            $ingreso_clase = trim(fgets(STDIN)); 
            $ingreso_switch_clase = true; 

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
                    echo "Incorrecto. Debe ingresar una clase valida (1, 2 o 3): \n";
                    $ingreso_switch_clase = false; 
                    break;
            }
        } while ($ingreso_switch_clase === false);
        
        echo "Ingrese el peso de su equipaje. \n";

        // Bucle para validar el peso
        do {
            $peaje = trim(fgets(STDIN));

            if (ctype_alpha($peaje)) { 
                echo "incorrecto, no puede ser una letra \n";
                $es_invalido = true;
            } 
            if (!is_numeric($peaje) || (int)$peaje <= 0) { 
                echo "tiene que ser un numero mayor a 0 \n";
                $es_invalido = true;
            } else if ($peaje > 45) {
                // SACAMOS el $maleta_rechazada++ de acá arriba
                
                do {
                    $eleccion_pasajero_dowhile = false; 
                    echo "¡ALERTA DE SEGURIDAD! El peso ($peaje kg) excede el limite de 45kg. Maleta rechazada.\n";
                    echo "Elija una opcion:\n";
                    echo "1. Ingresar un nuevo peso para ESTE pasajero\n";
                    echo "2. Saltar al SIGUIENTE pasajero\n";
                    $eleccion_pasajero = trim(fgets(STDIN));

                    if ($eleccion_pasajero == 1) {
                        echo "Ingrese nuevamente el peso para el Cliente $i: \n";
                        $es_invalido = true;
                        $eleccion_pasajero_dowhile = false; 
                    } else if ($eleccion_pasajero == 2) {
                        $maleta_rechazada++; // ¡ACÁ SÍ! Se rechaza permanentemente porque lo salteamos
                        echo "Saltando al siguiente pasajero...\n";
                        echo "|----------------------------------------------| \n";
                        continue 2; 
                    } else {
                        echo "Opcion invalida. Intente de nuevo.\n";
                        $eleccion_pasajero_dowhile = true; 
                    }
                } while ($eleccion_pasajero_dowhile === true);

            } else {
                $es_invalido = false; 
            }

        } while ($es_invalido === true);       

        // --- LOS CÁLCULOS VAN ACÁ ---
        
        if ($peaje > 23 && $peaje <= 32) {
            $recargo_USD = 50;
            $sobrepesos++;
            echo "va a tener un recargo de 50USD \n";
        } else if ($peaje > 32 && $peaje <= 45){
            $recargo_USD = 100;
            echo "va a tener un recargo de 100USD \n";
            $sobrepesos++;
        }
        
        // Seccion: Beneficio exclusivo.
        if (($peaje > 23 && $peaje <= 45) && ($clase_peaje == 3)) {
            echo "Recibió un beneficio exclusivo del 10% de descuento sobre la tasa de sobrepeso por ser primera clase \n";
            $beneficio_exclusivo++;
            $recargo_USD = $recargo_USD - ($recargo_USD * 0.10);
        }

        // Ahora que el recargo final está bien calculado, acumulamos y sumamos el total
        $total_recaudado_sobrepeso += $recargo_USD;
        $recargo_empresa_USD = $recargo_USD + $precio_pasaje;

        $pesos_peaje[$i] = (int)$peaje; 
        $datos_clases[$i] = (int)$clase_peaje; 
        
        echo " ---Cliente $i equivale a " . ($i - 1) . " en el array ---\n";
        echo "peso del equipaje: $peaje \n";
        echo "|----------------------------------------------| \n"; 
    }

// --- METRICAS DE CIERRE ---
echo "\n==============================================\n";
echo "           INFORME DE CIERRE DEL VUELO        \n";
echo "==============================================\n";
echo "Dinero total recaudado por sobrepesos: USD " . $total_recaudado_sobrepeso . "\n";
echo "Total de maletas rechazadas permanentemente: " . $maleta_rechazada . "\n";
echo "==============================================\n";

?>