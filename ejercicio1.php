<?php

// Aero PHP. Trabajo Practico Evaluativo.
// Equipo: Andino Enzo (Programador) | Mascioli Mateo (Tester)
// Objetivo: Experimentar el desarrollo de un algoritmo bajo las etapas del ciclo de vida del software.

// Variables para inicializar antes de que funcione el programa
function validar_pasajeros()
{
    do {
        $es_invalido = false;
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

    return $opcion;
}


$datos_clases = []; // array vacio para indicar el tipo de clase de los clientes. Economico: 1; Ejecutivo: 2; Primera clase: 3
$pesos_peaje = []; // declaro un array vacio para llenar los pesos con los clientes respectivos.


function validacion_de_clase()
{
    do {
        $clase_peaje = 0;
        $precio_pasaje = 0;
        $ingreso_switch_clase = true; // Reiniciamos el control del switch
        echo "Ingrese el Tipo de Pasaje. Economico: 1; Ejecutivo: 2; Primera clase: 3 \n";
        $ingreso_clase = trim(fgets(STDIN));

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

    // Devolvemos ambos datos en un array
    return ['clase' => $clase_peaje, 'precio' => $precio_pasaje];
}


function validarPeso($id_cliente, $clase_peaje, $precio_pasaje)
{
    echo "Ingrese el peso de su equipaje.  \n";
    $recargo_USD = 0;
    do {
        // El bucle Do-While se activa porque no queremos que el cliente ingrese algo menor que 0, mayor a 45 (por regla) o una letra.
        $es_invalido = false;
        $peaje = trim(fgets(STDIN));

        if (ctype_alpha($peaje)) { //detecta si es una letra. Si lo es, es incorrecto.
            echo "incorrecto, no puede ser una letra \n";
            $es_invalido = true;
        } else if (!is_numeric($peaje) || (float)$peaje <= 0) { //detecta si es un numero menor o igual que 0.
            echo "tiene que ser un numero mayor a 0 \n";
            $es_invalido = true;
        } else if ($peaje > 45) {
            echo "No puede ser mayor que 45kg. Por politicas internacionales, ninguna maleta puede exceder ese peso \n";
            echo "Ingrese nuevamente el peso(1) o decida pasar al siguiente pasajero(2) \n";
            $eleccion_pasajero = trim(fgets(STDIN));

            if ($eleccion_pasajero == 1) {
                $es_invalido = true;
                echo "Reintente ingresar el peso: \n";
            } else if ($eleccion_pasajero == 2) {
                return null; // Devolvemos null para avisar al bucle que salte al siguiente
            } else {
                echo "Opción inválida. Volviendo a pedir el peso... \n";
                $es_invalido = true;
            }
        } else {
            $es_invalido = false;
        }

        // recargos fijos por peso
        if ($peaje > 23 && $peaje <= 32) {
            $recargo_USD = 50;
            echo "va a tener un recargo de 50USD \n";
        } else if ($peaje > 32 && $peaje <= 45) {
            $recargo_USD = 100;
            echo "va a tener un recargo de 100USD \n";
        }

        $recargo_empresa_USD = $recargo_USD + $precio_pasaje;
        // Seccion: Beneficio exclusivo.

        if (($peaje > 23 && $peaje <= 45) && ($clase_peaje == 3)) {
            echo "Recibió un beneficio exclusivo del 10% de descuento por ser primera clase y tener sobrepeso \n";
        }

    } while ($es_invalido === true);

    echo "---Cliente $id_cliente procesado---\n";
    echo "Peso: $peaje kg | Recargo: $recargo_USD USD \n";
    echo "|----------------------------------------------| \n";

    return ['peso' => $peaje, 'recargo' => $recargo_USD];
}


function bucle_magico_hace_todo($cantidad)
{
    $resultados = [];
    $maletas_rechazadas = 0;
    for ($i = 0; $i < $cantidad; $i++) {
        echo "Cliente numero:" . ($i + 1) . "\n";
        
        $info_clase = validacion_de_clase();
        $info_peso = validarPeso($i, $info_clase['clase'], $info_clase['precio']);

        if ($info_peso === null) {
            echo "Pasajero omitido.\n";
            $maletas_rechazadas++;
            continue;
        }

        $resultados[] = [
            'clase' => $info_clase,
            'peso' => $info_peso
        ];
    }
    return [
        'lista' => $resultados,
        'rechazadas' => $maletas_rechazadas
    ];
}


// programa principal

echo "¿Cuantos pasajeros van a ingresar a la Aerolinea. (Solo puede anotar un numero mayor a 0) \n";
$opcion = validar_pasajeros(); // validacion de input.
$resultado_vuelo = bucle_magico_hace_todo($opcion);

$datos_finales = $resultado_vuelo['lista'];
$total_sobrepeso = 0;

foreach ($datos_finales as $pasajero) {
    $total_sobrepeso += $pasajero['peso']['recargo'];
}

echo "\n--- RESUMEN FINAL ---\n";
echo "Pasajeros registrados con éxito: " . count($datos_finales) . "\n";
echo "Total recaudado por sobrepesos: $" . $total_sobrepeso . " USD\n";
echo "Número total de maletas rechazadas: " . $resultado_vuelo['rechazadas'] . "\n";
?>