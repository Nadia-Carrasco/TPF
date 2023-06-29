<?php 
include_once("BaseDeDatos.php");
class Viaje{
    private $idviaje;
    private $vdestino;
    private $vcantmaxpasajeros; 
    private $objEmpresa;
    private $objResponsable; 
    private $vimporte;
    private $colPasajeros;
    private $mensajeoperacion;


    public function __construct(){
        $this->idviaje=0;
        $this->vdestino="";
        $this->vcatnmaxpasajeros=0;
        $this->objEmpresa=null;
        $this->objResponsable=null;
        $this->vimporte=0;
        $this->colpasajeros=null;
    }
    
    public function getIdViaje(){
        return $this->idviaje;
    }
    public function setIdViaje($idviaje){
        $this->idviaje=$idviaje;
    }

    public function getDestino(){
        return $this->vdestino;
    }
    public function setDestino($vdestino){
        $this->vdestino=$vdestino;
    }

    public function getCantMaxPasajeros(){
        return $this->vcantmaxpasajeros;
    }
    public function setCantMaxPasajeros($vcantmaxpasajeros){
        $this->vcantmaxpasajeros=$vcantmaxpasajeros;
    }

    public function getEmpresa(){
        return $this->objEmpresa;
    }
    public function setEmpresa($objEmpresa){
        $this->objEmpresa=$objEmpresa;
    }

    public function getResponsable(){
        return $this->objResponsable;
    }
    public function setResponsable($objResponsable){
        $this->objResponsable=$objResponsable;
    }

    public function getImporte(){
        return $this->vimporte;
    }
    public function setImporte($vimporte){
        $this->vimporte=$vimporte;
    }

    public function getMensajeOperacion(){
        return $this->mensajeoperacion;
    }
    public function setMensajeOperacion($mensajeoperacion){
        $this->mensajeoperacion=$mensajeoperacion;
    }
    public function getColPasajeros(){
        return $this->colPasajeros;
    }
    public function setColPasajeros($colPasajeros){
        $this->colPasajeros=$colPasajeros;
    }

    

    public function cargar($vdestino,$vcantmaxpasajeros,$objEmpresa,$objResponsable,$vimporte,$colPasajeros){
       
        $this->setDestino($vdestino);
        $this->setCantMaxPasajeros($vcantmaxpasajeros);
        $this->setEmpresa($objEmpresa);
        $this->setResponsable($objResponsable);
        $this->setImporte($vimporte);
        $this->setColPasajeros($colPasajeros);
    }

    public function __toString(){
        return "\n ID Viaje: ".$this->getIdViaje()."\n Destino: ".$this->getDestino()."\n Cant Max Pasajeros: ".$this->getCantMaxPasajeros().$this->getEmpresa()."\n Responsable: ".$this->getResponsable()."\n Importe del Viaje: ".$this->getImporte();
    }

    public function insertar(){
        $base=new BaseDatos();
        $resp=false;
        $consultaInsertar="INSERT INTO viaje(vdestino,vcantmaxpasajeros,idempresa,rnumeroempleado,vimporte)
        VALUES ('".$this->getDestino()."',".$this->getCantMaxPasajeros().",".$this->getEmpresa()->getIdEmpresa().",".$this->getResponsable()->getNumeroEmpleado().",".$this->getImporte().")";
        if($base->Iniciar()){
           
            if($id=$base->devuelveIDInsercion($consultaInsertar)){
                $this->setIdViaje($id);
                $resp=true;
                
            }else{
                $this->setMensajeOperacion($base->getError());
                
            }
        }else{
            $this->setMensajeOperacion($base->getError());
        
        }
        return $resp;
    }

    public function modificar(){
        $resp=false;
        $base=new BaseDatos();
        $cosultaModifica="UPDATE viaje SET vdestino='".$this->getDestino()."',vcantmaxpasajeros='".$this->getCantMaxPasajeros()."',idempresa='".$this->getEmpresa()->getIdEmpresa()."',rnumeroempleado='".$this->getResponsable()->getNumeroEmpleado()."',vimporte='".$this->getImporte()."' WHERE idviaje=".$this->getIdViaje();

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

    /** Elimina el registro en la base de datos que corresponde a la class viaje, si se inicia la conexion con el motor bd.*/

    public function eliminar(){
        $base=new BaseDatos();
        $resp=false;
        if($base->Iniciar()){
            $consultarBorra="DELETE FROM viaje WHERE idviaje=".$this->getIdViaje();
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

     /** para obtener los datos de viaje, devuelve un arreglo de obj viaje */
     public static function listar($condicion=""){
        $arregloViaje=null;
        $base=new BaseDatos();
        $consultaViaje="Select * FROM viaje ";
        if($condicion!=""){
            $consultaViaje.=' where '.$condicion;
        }
        $consultaViaje=$consultaViaje." order by idviaje ";

        if($base->Iniciar()){
           if($base->Ejecutar($consultaViaje)){
                $arregloViaje=array();
                while($row2=$base->Registro()){

                    $objEmpresa=new Empresa();
                    $objResponsable=new Responsable();
                    $objPasajero=new Pasajero();

                    $idviaje=$row2["idviaje"];
                    $vdestino=$row2["vdestino"];
                    $vcantmaxpasajeros=$row2["vcantmaxpasajeros"];

                    $objEmpresa->Buscar($row2["idempresa"]);
                    $objResponsable->Buscar($row2["rnumeroempleado"]);
                    

                    $vimporte=$row2["vimporte"];
                    $colPasajeros=$objPasajero->listar('idviaje='.$idviaje);
                    
                    $viaje=new Viaje();
                    $viaje->cargar($vdestino,$vcantmaxpasajeros,$objEmpresa,$objResponsable,$vimporte,$colPasajeros);
                    $viaje->setIdViaje($idviaje);
            
                    $arregloViaje[]=$viaje;
                }
            }  else{
                $this->setMensajeOperacion($base->getError());
            
            }
        } else{
            $this->setMensajeOperacion($base->getError());
        }

       return $arregloViaje; 
    }

    public function Buscar($idviaje){
        $base=new BaseDatos();
        $consultaViaje="Select * FROM viaje Where idviaje=".$idviaje;

        if($base->Iniciar()){
            if($base->Ejecutar($consultaViaje)){
                if($row2=$base->Registro()){

                  $objEmpresa=new Empresa();
                  $objResponsable=new Responsable();
                  $objResponsable->Buscar($row2['rnumeroempleado']);
                  $objEmpresa->Buscar($row2['idempresa']);
                  $this->cargar($row2["vdestino"],$row2["vcantmaxpasajeros"],$objEmpresa,$objResponsable,$row2["vimporte"],[]);
                    return true;
                }
            } else {
                $this->setMensajeOperacion($base->getError());
                return false;
            }
        }else{
            $this->setMensajeOperacion($base->getError());
            return false;
        }
    }
}