<?php
include_once("Empresa.php");
include_once("Responsable.php");
include_once("Viaje.php");
include_once("Pasajero.php");

$objEmpresa=new Empresa();
$objResponsable=new Responsable();
$objViaje=new Viaje();
$objResponsable=new Responsable();
$objPasajero=new Pasajero();

function mostrarDatosArray($array){
    foreach($array as $unDato){
        echo $unDato."\n";
        echo "----------------------------------------"."\n";
    }
}



function menu(){ 
    echo "Menu de opciones: "."\n INGRESAR DATOS: "."\n 1. Empresa Nueva "."\n 2. Responsable Nuevo "."\n 3. Viaje Nuevo "."\n 4. Pasajero Nuevo ".
    "\n MODIFICAR DATOS: "."\n 5. Empresa "."\n 6. Responsable" ."\n 7. Viaje "."\n 8. Pasajero ". 
    "\n ELIMINAR DATOS: "."\n 9. Empresa "."\n 10. Responsable "."\n 11. Viaje"."\n 12. Pasajero".
    "\n MOSTRAR DATOS: "."\n 13. Empresa "."\n 14. Responsable "."\n 15. Viaje "."\n 16. Pasajero"."\n SALIR"."\n 17. Salir del Menú "."\n TU OPCIÓN: ";
    $respuesta=trim(fgets(STDIN));
    return $respuesta;
}

$opcion= menu();
do{
    switch($opcion){
        case 1:
            echo "Ingrese Nombre de la Empresa: ";
            $nombre=trim(fgets(STDIN));
            echo "Ingrese Dirección de la Empresa: ";
            $direc=trim(fgets(STDIN));
            $objEmpresa->cargar($nombre,$direc);
            $colEmpresa=$objEmpresa->listar();
            $i=0;
            $encontro=false;
            while($i<count($colEmpresa) && !$encontro ) {
                $unaEmpresa=$colEmpresa[$i];
                if($unaEmpresa->getNombre() === $nombre && $unaEmpresa->getDireccion() === $direc){
                    $encontro=true;
                }
                $i++;
            }
            
            if(!$encontro){
                
                $resp=$objEmpresa->insertar(); 
                
                if($resp){
                    echo "La empresa fue ingresada a la BD"."\n";
                }else{
                    echo "La empresa no se pudo ingresar a la BD"."\n";
                    echo "----------------------------------------"."\n";
                }
            }else{
                echo "La empresa YA EXISTE en la BD"."\n";
                echo "----------------------------------------"."\n";
            }
           
            $opcion= menu();
            break;
        case 2:
                echo "Ingrese Numero de Licencia: ";
                $numLicencia=trim(fgets(STDIN));
                echo "Ingrese Nombre: ";
                $nombre=trim(fgets(STDIN));
                echo "Ingrese Apellido: ";
                $apellido=trim(fgets(STDIN));
                $objResponsable->cargar($numLicencia,$nombre,$apellido);
                $resp=$objResponsable->insertar();
                if($resp){
                    echo "Responsable ingresado a la BD"."\n";
                }else{
                    echo "No se pudo ingresar al Responsable en la BD"."\n";
                }
               
            $opcion=menu();
           
            break;
        case 3:
            $colEmpresa=$objEmpresa->listar();
            mostrarDatosArray($colEmpresa);
        if(count($colEmpresa)>0){
            echo "Ingrese ID de la empresa a la que desea asignarle el viaje: ";
            $idEmpresa=trim(fgets(STDIN));
            if($objEmpresa->Buscar($idEmpresa)){
                echo "Ingrese destino: ";
                $destino=trim(fgets(STDIN));
                echo "Ingrese cantidad máxima de pasajeros: ";
                $cantmaxpas=trim(fgets(STDIN));
                echo "Ingrese importe: ";
                $importe=trim(fgets(STDIN));

                $colResponsable=$objResponsable->listar();
                mostrarDatosArray($colResponsable);
                echo "A quién le asigna este viaje? Ingrese Número de empleado: ";
                $idRespo=trim(fgets(STDIN));
               
                if($objResponsable->Buscar($idRespo)){
                  
                    $objResponsable=$objResponsable->listar('rnumeroempleado='.$idRespo);
                    
                    $objResponsable=$objResponsable[0];
                    $objempresa=$objEmpresa->listar('idempresa='.$idEmpresa);
                    $objempresa=$objempresa[0];
                    
                    //print_r($objempresa) ;
                   // print_r($objResponsable) ;
                    //echo $objResponsable->getNumeroEmpleado();
                    $objViaje->cargar($destino,$cantmaxpas,$objempresa,$objResponsable,$importe,[]);   
                    $resp=$objViaje->insertar();
                    if($resp){
                        echo "Viaje Ingresado a la BD"."\n";
                    }else{
                        echo "El viaje no fue ingresado a la BD"."\n";
                    }
                }else{
                    echo "No se encontro el ID del responsable"."\n";
                }

                
            }else{
                echo "No se encontró el ID de la empresa"."\n";
            }
        }else{
            echo "No se registran empresas";
        }
            $opcion=menu();
            break;
        case 4:
            $colViajes=$objViaje->listar();
        
            mostrarDatosArray($colViajes);
            echo "Ingrese ID del Viaje para agregar un nuevo Pasajero: ";
            $idViaje=trim(fgets(STDIN));
            if($objViaje->Buscar($idViaje)){
            
                $viaje=$objViaje->listar('idviaje='.$idViaje);
                $viaje=$viaje[0];

                $pasajerosDelViaje=$objPasajero->listar('idviaje='.$idViaje);
                echo "Ingrese documento del pasajero: ";
                $numDocu=trim(fgets(STDIN));
                
                if(!$objPasajero->Buscar($numDocu)){
                    if($viaje->getCantMaxPasajeros()>count($pasajerosDelViaje)){
                        echo "Ingrese Nombre del pasajero: ";
                        $nombre=trim(fgets(STDIN));
                        echo "Ingrese Apellido del pasajero: ";
                        $apellido=trim(fgets(STDIN));
                        echo "Ingrese teléfono del pasajero: ";
                        $telefono=trim(fgets(STDIN));

                        $objPasajero->cargar($numDocu,$nombre,$apellido,$telefono,$viaje);
                        $respuesta=$objPasajero->insertar();
                        if($respuesta){
                            echo "El pasajero se ingresó correctamente "."\n";
                        }else{
                            echo "No se pudo ingresar correctamente"."\n";
                        }
                    }else{
                        echo "No hay lugar disponible en el viaje "."\n";
                    }
                }else{
                    echo "El pasajero Ya se encuentra registrado"."\n";
                }
                      
            }else{
                echo "El ID del viaje no se encontró "."\n";

            }
            $opcion=menu();

            
            break;
        case 5:
           
            $colEmpresa=$objEmpresa->listar();
            mostrarDatosArray($colEmpresa);
            echo "Ingrese ID de la empresa que desea modificar: ";
            $idEmpresa=trim(fgets(STDIN));
            $colEmpresa=$objEmpresa->listar();
            if($objEmpresa->Buscar($idEmpresa)){
                $empresa=$objEmpresa->listar('idempresa='.$idEmpresa);
                $empresa=$empresa[0];
                echo "\n 1. Cambiar Nombre "."\n 2. Cambiar Dirección"."\n 3. Modificar ambos"."\n"."Ingrese Opción: ";
                $resp=trim(fgets(STDIN));
                if($resp==1){
                    echo "Ingrese Nombre: ";
                    $nombre=trim(fgets(STDIN));
                    $empresa->setNombre($nombre);
                    $modifica= $empresa->modificar();
                    if($modifica){
                        echo "Modificación Exitosa"."\n";
                    }else{
                        echo "No se modifico correctamente"."\n";
                    }
                }else if($resp==2){
                    echo "Ingrese Dirección: ";
                    $direc=trim(fgets(STDIN));
                    $empresa->setDireccion($direc);
                    $modifica=$empresa->modificar();
                    if($modifica){
                        echo "Modificación Exitosa"."\n";
                    }else{
                        echo "No se modifico correctamente"."\n";
                    }
                }else if($resp==3){
                    echo "Ingrese Nombre: ";
                    $nombre=trim(fgets(STDIN));
                    echo "Ingrese Dirección: ";
                    $direc=trim(fgets(STDIN));
                    $empresa->setNombre($nombre);
                    $empresa->setDireccion($direc);
                    $modifica=$empresa->modificar();
                    if($modifica){
                        echo "Modificación Exitosa"."\n";
                    }else{
                        echo "No se modifico correctamente"."\n";
                    }
                }else{
                    echo "Opción Inválida"."\n";
                }
                
            }else{
                echo "No existe "."\n";
            }
            $opcion=menu();

            break;
        case 6:
          
            $colResponsable=$objResponsable->listar();
            mostrarDatosArray($colResponsable);
            echo "Ingrese Número de empleado del Responsable que desee modificar: ";
            $idRespo=trim(fgets(STDIN));
            if($objResponsable->Buscar($idRespo)){
                $respo=$objResponsable->listar('rnumeroempleado='.$idRespo);
                $respo=$respo[0];
                do{
                    echo "\n 1. Cambiar Número de licencia "."\n 2. Cambiar Nombre "."\n 3. Cambiar Apellido "."\n 4. Salir"."\n Ingrese opción: ";
                    $resp=trim(fgets(STDIN));
                    if($resp==1){
                        echo "Nuevo número de licencia: ";
                        $numLicencia=trim(fgets(STDIN));
                        $respo->setNumeroLicencia($numLicencia);
                        $modifica=$respo->modificar();
                        if($modifica){
                            echo "Se modifico el número de licencia"."\n";
                        }else{
                            echo "No se pudo modificar "."\n";
                        }
                    }else if($resp==2){
                        echo "Nuevo Nombre: ";
                        $nombre=trim(fgets(STDIN));
                        $respo->setNombre($nombre);
                        $modifica=$respo->modificar();
                        if($modifica){
                            echo "Se modifico el nombre del Responsable"."\n";
                        }else{
                            echo "No se pudo modificar "."\n";
                        }
                    }else if($resp==3){
                        echo "Nuevo Apellido: ";
                        $apellido=trim(fgets(STDIN));
                        $respo->setApellido($apellido);
                        $modifica=$respo->modificar();
                        if($modifica){
                            echo "Se modifico el apellido del Responsable"."\n";
                        }else{
                            echo "No se pudo modificar "."\n";
                        }
                    }
                }while($resp!=4);
            }else{
                echo "Empleado no registrado"."\n";
            }
            $opcion=menu();

            break;
        case 7:
            
            $colViajes=$objViaje->listar();
            mostrarDatosArray($colViajes);
            echo "Ingrese ID del viaje que desea modificar: ";
            $idViaje=trim(fgets(STDIN));
            if($objViaje->Buscar($idViaje)){
                $viaje=$objViaje->listar('idviaje='.$idViaje);
                $viaje=$viaje[0];
                do{
                    echo "\n 1. Cambiar Destino "."\n 2. Cambiar cantidad max de pasajeros "."\n 3. Cambiar la Empresa "."\n 4. Cambiar Responsable del Viaje "."\n 5. Cambiar Importe "."\n 6. Salir "."\n Ingrese opción: ";
                    $resp=trim(fgets(STDIN));
                    switch($resp){
                        case 1:
                            echo "Ingrese Nuevo Destino: ";
                            $destino=trim(fgets(STDIN));
                            $viaje->setDestino($destino);
                            $modifica=$viaje->modificar();
                            if($modifica){
                                echo "Se modifico el Destino "."\n";
                            }else{
                                echo "No se pudo modificar"."\n";
                            }
                            break;
                        case 2:
                            echo "Ingrese Nueva cantidad maxima de pasajeros: ";
                            $cantmaxpas=trim(fgets(STDIN));
                            $viaje->setCantMaxPasajeros($cantmaxpas);
                            $modifica=$viaje->modificar();
                            if($modifica){
                                echo "Se modifico la cant max de pasajeros "."\n";
                            }else{
                                echo "No se pudo modificar"."\n";
                            }
                            break;
                        case 3:
                            
                            $colEmpresa=$objEmpresa->listar();
                            mostrarDatosArray($colEmpresa);
                            echo "Ingrese ID de la nueva empresa: ";
                            $idEmpresa=trim(fgets(STDIN));
                            if($objEmpresa->Buscar($idEmpresa)){
                                $viaje->setIdEmpresa($idEmpresa);
                                $modifica=$viaje->modificar();
                                if($modifica){
                                    echo "Se modifico empresa "."\n";
                                }else{
                                    echo "No se pudo modificar"."\n";
                                }  
                            }else{
                                echo "Empresa no registrada"."\n";
                            }
                            break;
                        case 4:
                            
                            $colResponsable=$objResponsable->listar();
                            mostrarDatosArray($colResponsable);
                            echo "Ingrese Número de empleado del nuevo Responsable del viaje: ";
                            $responsable=trim(fgets(STDIN));
                            if($objResponsable->Buscar($responsable)){
                                $viaje->setNumeroEmpleadoRespo($responsable);
                                $modifica=$viaje->modificar();
                                if($modifica){
                                    echo "Responsable modificado"."\n";
                                }else{
                                    echo "NO se pudo modificar"."\n";
                                }
                            }else{
                                echo "Responsable no registrado "."\n";
                            }
                            break;
                        case 5:
                            echo "Ingrese Nuevo Importe del Viaje: ";
                            $importe=trim(fgets(STDIN));
                            $viaje->setImporte($importe);
                            $modifica=$viaje->modificar();
                            if($modifica){
                                echo "Importe modificado"."\n";
                            }else{
                                echo "NO se pudo modificar"."\n";
                            }
                        break;
                    }
                }while($resp!=6);
            }else{
                echo "ID NO registrado"."\n";
            }
            $opcion=menu();
            break;
        case 8:
            
            $colPasajeros=$objPasajero->listar();
            mostrarDatosArray($colPasajeros);
            echo "Ingrese documento del pasajero que desea modificar: ";
            $pdocumento=trim(fgets(STDIN));
            if($objPasajero->Buscar($pdocumento)){
                $pasajero=$objPasajero->listar('pdocumento='.$pdocumento);
                $pasajero=$pasajero[0];
               do{
                echo "\n 1. Cambiar Nombre"."\n 2. Cambiar Apellido". "\n 3. Cambiar teléfono"."\n 4. Cambiar de Viaje"."\n 5. Salir"."\n Ingrese opción: ";
                $resp=trim(fgets(STDIN));
                switch($resp){
                    case 1: 
                        echo "Nuevo Nombre: ";
                        $nombre=trim(fgets(STDIN));
                        $pasajero->setNombre($nombre);
                        $modifica=$pasajero->modificar();
                        if($modifica){
                            echo "Nombre modificado "."\n";
                        }else{
                            echo "NO se pudo modificar"."\n";
                        }
                        break;
                    case 2:
                        echo "Nuevo Apellido: ";
                        $apellido=trim(fgets(STDIN));
                        $pasajero->setApellido($apellido);
                        $modifica=$pasajero->modificar();
                        if($modifica){
                            echo "Apellido modificado "."\n";
                        }else{
                            echo "NO se pudo modificar"."\n";
                        }
                        break;
                    case 3:
                        echo "Nuevo Teléfono: ";
                        $telefono=trim(fgets(STDIN));
                        $pasajero->setTelefono($telefono);
                        $modifica=$pasajero->modificar();
                        if($modifica){
                            echo "Teléfono modificado "."\n";
                        }else{
                            echo "NO se pudo modificar"."\n";
                        }
                        break;
                    case 4:{
                        
                        $colViajes=$objViaje->listar();
                        mostrarDatosArray($colViajes);
                        echo "Ingrese ID del viaje que desee asignar: ";
                        $idViaje=trim(fgets(STDIN));
                        if($objViaje->Buscar($idViaje)){
                            $pasajero->setIdViaje($idViaje);
                            $modifica=$objPasajero->modificar();
                            if($modifica){
                                echo "Viaje modificado "."\n";
                            }else{
                                echo "NO se pudo modificar"."\n";
                            }
                        }else{
                            echo "Viaje no registrado"."\n";
                        }
                    }
                }
               }while($resp!=5);
            }else{
                echo "No se encontró el pasajero"."\n";
            }
            $opcion=menu();
            break;
        case 9: 
            
            echo "AL ELIMINAR LA EMPRESA, SE ELIMINARAN TODOS LOS VIAJES Y PERSONAS REGISTRADAS EN ELLA"."\n";
            echo "Si desea continuar ingrese 1, sino 2: ";
            $resp=trim(fgets(STDIN));
            if($resp==1){
                $todosLosDatos=[];
                mostrarDatosArray($objEmpresa->listar());
                echo "Ingrese ID de la empresa que desee eliminar: ";
                $idEmpresa=trim(fgets(STDIN));
                if($objEmpresa->Buscar($idEmpresa)){ 
                    $empresa=$objEmpresa->listar('idempresa='.$idEmpresa);
                    $empresa=$empresa[0];
                    $viajesDeEmpresa=$objViaje->listar('idempresa='.$idEmpresa);
                    $todosLosDatos=[];
                   
                    for($i=0; $i<count($viajesDeEmpresa); $i++){
                        $idViaje=$viajesDeEmpresa[$i] ->getIdViaje();
                        if($pasajerosDelViaje=$objPasajero->listar('idviaje='.$idViaje)){
                            foreach($pasajerosDelViaje as $unPasajero){
                                $todosLosDatos[]=$unPasajero; 
                            }
                           
                        }  
                    } 
                    foreach($viajesDeEmpresa as $unViaje){
                        $todosLosDatos[]=$unViaje;
                    }
                        
                        $todosLosDatos[]=$empresa;
                        
                         $i=0;
                         $resultado=true;
                    while($i<count($todosLosDatos) && $resultado){
                       if( !$todosLosDatos[$i]->eliminar()){
                        $resultado=false;
                       }
                        $i++;
                    }
                    if($resultado){
                        echo "Los datos se eliminaron correctamente "."\n";
                    }else{
                        echo "Los datos no se eliminaron correctamente"."\n";
                    }
                }else{
                    echo "No se encontró la empresa "."\n";
                }


            }else{
                echo "adiós"."\n";
            }
            $opcion=menu();
            break;
        case 10:
           
            $colResponsable=$objResponsable->listar();
            mostrarDatosArray($colResponsable);
            echo "Ingrese Numero del empleado que desea eliminar: ";
            $numEmpleado=trim(fgets(STDIN));
            if($objResponsable->Buscar($numEmpleado)){
                $responsable=$objResponsable->listar('rnumeroempleado='.$numEmpleado);
                $responsable = $responsable[0];
                if($objViaje->listar('rnumeroempleado='.$numEmpleado)){
                    echo "El responsable no puede ser eliminado porque ya tiene un viaje asignado"."\n";
                }else{
                    if($responsable->eliminar()){
                       echo "Se elimino correctamente "."\n"; 
                    }else{
                    echo "No se pudo eliminar "."\n";
                    }
                }
            }else{
                echo "No está registrado"."\n";
            }
            $opcion=menu();
           
            break;
        case 11: 
            
            $colViaje=$objViaje->listar();
            mostrarDatosArray($colViaje);
            echo "Ingrese ID del viaje que desee eliminar: ";
            $idViaje=trim(fgets(STDIN));
            if($objViaje->Buscar($idViaje)){
                $pasajerosDelViaje=$objPasajero->listar('idviaje='.$idViaje);
                foreach($pasajerosDelViaje as $unPasajero){
                    $unPasajero->eliminar();
                }
                $viaje=$objViaje->listar('idviaje='.$idViaje);
                $viaje=$viaje[0];
                $elimino=$viaje->eliminar();
                if($elimino){
                    echo "Los datos se eliminaron correctamente "."\n";
                }
                

            }else{
                echo "No se encontró el viaje"."\n";
            }
            $opcion=menu();
            break;
        case 12: 
            
            echo "Ingrese Número de Documento del pasajero que desee eliminar: ";
            $numDocu=trim(fgets(STDIN));
            
            if($objPasajero->Buscar($numDocu)){
               $pasajero=$objPasajero->listar('pdocumento='.$numDocu);
                $pasajero=$pasajero[0];
                $eliminar=$pasajero->eliminar();
                if($eliminar){
                    echo "Pasajero eliminado "."\n";
                }else{
                    echo "NO se pudo eliminar "."\n";
                }
            }else{
                echo "pasajero NO registrado"."\n";
            }
            $opcion=menu();
            break;
        case 13: 
            
            $colEmpresa=$objEmpresa->listar();
            
            do{
                echo "\n 1. Para ver datos de una empresa "."\n 2. Para ver los datos de todas las empresas"."\n 3. Salir "."\n Ingrese Opción: ";
                $resp=trim(fgets(STDIN));
                switch($resp){
                    case 1: 
                        echo "Ingrese el ID: ";
                        $idEmpresa=trim(fgets(STDIN));
                        if($objEmpresa->Buscar($idEmpresa)){
                            $empresa=$objEmpresa->listar('idempresa='.$idEmpresa);

                            echo $empresa[0];
                        }else{
                            echo "La empresa NO está registrada."."\n";
                        }
                        break;
                    case 2:
                        mostrarDatosArray($colEmpresa);
                        break;
                    default:
                    echo "opción inválida"."\n";
                        break;
                }
            }while($resp!=3);
            $opcion=menu();
            break;
        case 14:
           
            $colResponsable=$objResponsable->listar();
            do{
                echo "\n 1. Mostrar datos de un Responsable "."\n 2. Mostrar todos los Responsables "."\n 3. Salir "."\n Ingrese opción: ";
                $resp=trim(fgets(STDIN));
                switch($resp){
                    case 1: 
                        echo "Ingrese Numero de empleado para ver sus datos: ";
                        $numEmpleado=trim(fgets(STDIN));
                        if($objResponsable->Buscar($numEmpleado)){
                            $respo=$objResponsable->listar('rnumeroempleado='.$numEmpleado);
                            $respo=$respo[0];
                            echo $respo;
                        }else{
                            echo "Empleado NO registrado"."\n";
                        }
                        break;
                    case 2:
                        mostrarDatosArray($colResponsable);
                        break;
                    default:
                        echo "Opción inválida"."\n";
                        break;
                }

            }while($resp!=3);
            $opcion=menu();
            break;
        case 15:
           
            $colViajes=$objViaje->listar();
            do{
                echo "\n 1. Para ver datos de un viaje "."\n 2. Para ver todos los datos de los viajes "."\n 3. Salir "."\n Ingrese Opción: ";
                $resp=trim(fgets(STDIN));
                switch($resp){
                    case 1:
                        echo "Ingrese ID del viaje para ver sus datos: ";
                        $idViaje=trim(fgets(STDIN));
                        if($objViaje->Buscar($idViaje)){
                            $viaje=$objViaje->listar('idviaje='.$idViaje);
                            $viaje=$viaje[0];
                            echo $viaje;
                        }else{
                            echo "Viaje NO registrado "."\n";
                        }
                        break;
                    case 2:
                        mostrarDatosArray($colViajes);
                        break;
                    default: 
                        echo "Opción Inválida"."\n";
                        break;
                }
            }while($resp!=3);
            $opcion=menu();
            break;
        case 16:
           
            $colPasajeros=$objPasajero->listar();
            do{
                echo "\n 1. Ver datos de un pasajero "."\n 2. Ver datos de todos los pasajeros "."\n 3. Salir "."\n Ingrese Opción: ";
                $resp=trim(fgets(STDIN));
                switch($resp){
                    case 1: 
                        echo "Ingrese numero de documento : ";
                        $numDocu=trim(fgets(STDIN));
                        if($objPasajero->Buscar($numDocu)){
                            $pasajero=$objPasajero->listar('pdocumento='.$numDocu);
                            $pasajero=$pasajero[0];
                            echo $pasajero;
                            
                        }else{
                            echo "Pasajero NO registrado"."\n";
                        }
                        break;
                    case 2:
                        mostrarDatosArray($colPasajeros);
                        break;
                    default:
                        echo "Opción inválida"."\n";
                        break;
                }
            }while($resp!=3);
            $opcion=menu();
            break;
    }
}while($opcion!=17);
