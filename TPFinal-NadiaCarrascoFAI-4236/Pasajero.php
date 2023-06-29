<?php
include_once("BaseDeDatos.php");

class Pasajero{
    private $pdocumento;
    private $pnombre;
    private $papellido;
    private $ptelefono;
    private $objviaje;
    private $mensajeoperacion;

    public function __construct(){
        $this->pdocumento="";
        $this->pnombre="";
        $this->papellido="";
        $this->ptelefono=0;
        $this->objviaje=null;
    }

    public function getNroDocu(){
        return $this->pdocumento;
    }
    public function setNroDocu($pdocumento){
        $this->pdocumento=$pdocumento;
    }
    public function getNombre(){
        return $this->pnombre;
    }
    public function setNombre($pnombre){
        $this->pnombre=$pnombre;
    }
    public function getApellido(){
        return $this->papellido;
    }
    public function setApellido($papellido){
        $this->papellido=$papellido;
    }
    public function getTelefono(){
        return $this->ptelefono;
    }
    public function setTelefono($ptelefono){
        $this->ptelefono=$ptelefono;
    }
    public function getObjViaje(){
        return $this->objviaje;
    }
    public function setObjViaje($objviaje){
        $this->objviaje=$objviaje;
    }
    public function getMensajeOperacion(){
        return $this->mensajeoperacion;
    }
    public function setMensajeOperacion($mensajeoperacion){
        $this->mensajeoperacion=$mensajeoperacion;
    }

    public function __toString(){
        return "\n Documento: ".$this->getNroDocu()."\n Nombre: ".$this->getNombre()."\n Apellido: ".$this->getApellido()."\n Teléfono: ".$this->getTelefono()."\n Viaje: "."\n".$this->getObjViaje();
    }

    public function cargar($pdocumento, $pnombre, $papellido, $ptelefono, $objviaje){
        $this->setNroDocu($pdocumento);
        $this->setNombre($pnombre);
        $this->setApellido($papellido);
        $this->setTelefono($ptelefono);
        $this->setObjViaje($objviaje);

    }

    public function insertar(){
        $base=new BaseDatos();
        $resp=false;
        $consultaInsertar="INSERT INTO pasajero(pdocumento, pnombre, papellido, ptelefono, idviaje) 
        VALUES (". $this->getNroDocu().",'".$this->getNombre()."','".$this->getApellido()."','".$this->getTelefono()."','".$this->getObjViaje()->getIdViaje()."')";
        echo $consultaInsertar;
        if($base->Iniciar()){ 
            if($base->Ejecutar($consultaInsertar)){
                $resp= true;
            } else {
                $this->setMensajeOperacion($base->getError());
              
            }
        } else {
            $this->setMensajeOperacion($base->getError());
        
        }
        return $resp;
    } 

    public function modificar(){
        $base=new BaseDatos();
        $cosultaModifica="UPDATE pasajero SET pnombre='".$this->getNombre()."',papellido='".$this->getApellido()."',ptelefono='".$this->getTelefono()."',idviaje='".$this->getObjViaje()->getIdViaje()."' WHERE pdocumento=".$this->getNroDocu();
        $resp=false;
        if($base->Iniciar()){
            if($base->Ejecutar($cosultaModifica)){
                $resp= true;
            } else{
                $this->setMensajeOperacion($base->getError());
             
            }
        } else{
            $this->setMensajeOperacion($base->getError());
            
        }
        return $resp;
    }

    /** Elimina el registro en la base de datos que corresponde a la class pasajero, si se inicia la conexion con el motor bd.*/

    public function eliminar(){
        $base=new BaseDatos();
        $resp=false;
        if($base->Iniciar()){
            $consultarBorra="DELETE FROM pasajero WHERE pdocumento=".$this->getNroDocu();
            if($base->Ejecutar($consultarBorra)){
                $resp= true;
            } else{
                $this->setMensajeOperacion($base->getError());
               
            }
        }else {
            $this->setMensajeOperacion($base->getError());
            
        }
        return $resp;
    }

    /** para obtener los datos de pasajero, devuelve un arreglo de obj pasajero */
    public static function listar($condicion=""){
        $arregloPasajero=null;
        $base=new BaseDatos();
        $consultaPasajeros="Select * FROM pasajero ";
        if($condicion!=""){
            $consultaPasajeros.=' where '.$condicion;
        }
        $consultaPasajeros.=" order by pdocumento ";

        if($base->Iniciar()){
           if($base->Ejecutar($consultaPasajeros)){
                $arregloPasajero=array();
                while($row2=$base->Registro()){
                    $pdocumento=$row2["pdocumento"];
                    $pnombre=$row2["pnombre"];
                    $papellido=$row2["papellido"];
                    $ptelefono=$row2["ptelefono"];
                    
                    $objviaje=new Viaje();
                    $objviaje->Buscar($row2["idviaje"]);
                    
                    $pasajero=new Pasajero();
                    $pasajero->cargar($pdocumento,$pnombre,$papellido,$ptelefono,$objviaje);
                    $arregloPasajero[]=$pasajero;
                }
            }  else{
                $this->setMensajeOperacion($base->getError());
            
            }
        } else{
            $this->setMensajeOperacion($base->getError());
        }

       return $arregloPasajero; 
    }

    /**
	 * Recupera los datos de un pasajero por dni
	 * @param int $pdocumento
	 * @return true en caso de encontrar los datos
	 */		
    public function Buscar($pdocumento){
        $base=new BaseDatos();
        $consultaPasajeros="Select * FROM pasajero Where pdocumento=".$pdocumento;
        $resp=false;
        if($base->Iniciar()){
            if($base->Ejecutar($consultaPasajeros)){
                if($row2=$base->Registro()){

                    $objviaje=new Viaje();
                    $objviaje->Buscar($row2["idviaje"]);

                    $this->cargar($pdocumento, $row2["pnombre"], $row2["papellido"], $row2["ptelefono"], $objviaje);
                    $resp= true;
                }
            } else {
                $this->setMensajeOperacion($base->getError());
              
            }
        }else{
            $this->setMensajeOperacion($base->getError());
       
        }
        return $resp;
    }

 
}