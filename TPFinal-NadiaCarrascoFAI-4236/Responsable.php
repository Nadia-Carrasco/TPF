<?php
include_once("BaseDeDatos.php");

class Responsable{
    private $rnumeroempleado;
    private $rnumerolicencia;
    private $rnombre;
    private $rapellido;
    private $mensajeoperacion;

    public function __construct(){
        $this->rnumeroempleado="";
        $this->rnumerolicencia=0;
        $this->rnombre="";
        $this->rapellido="";
    }

    public function getNumeroEmpleado(){
        return $this->rnumeroempleado;
    }
    public function setNumeroEmpleado($rnumeroempleado){
        $this->rnumeroempleado=$rnumeroempleado;
    }

    public function getNumeroLicencia(){
        return $this->rnumerolicencia;
    }
    public function setNumeroLicencia($rnumerolicencia){
        $this->rnumerolicencia=$rnumerolicencia;
    }

    public function getNombre(){
        return $this->rnombre;
    }
    public function setNombre($rnombre){
        $this->rnombre=$rnombre;
    }

    public function getApellido(){
        return $this->rapellido;
    }
    public function setApellido($rapellido){
        $this->rapellido=$rapellido;
    }

    public function getMensajeOperacion(){
        return $this->mensajeoperacion;
    }
    public function setMensajeOperacion($mensajeoperacion){
        $this->mensajeoperacion=$mensajeoperacion;
    }

    public function __toString(){
        return "\n Número de Empleado: ".$this->getNumeroEmpleado()."\n Número de Licencia: ".$this->getNumeroLicencia()."\n Nombre: ".$this->getNombre()."\n Apellido: ".$this->getApellido();
    }

    public function cargar($rnumerolicencia, $rnombre, $rapellido){
        
        $this->setNumeroLicencia($rnumerolicencia);
        $this->setNombre($rnombre);
        $this->setApellido($rapellido);
    }
    
    public function insertar(){
        $base=new BaseDatos();
        $resp=false;
        $cosultaInsertar="INSERT INTO responsable(rnumerolicencia, rnombre, rapellido) 
        VALUES (". $this->getNumeroLicencia().",'".$this->getNombre()."','".$this->getApellido()."')";
        
        if($base->Iniciar()){ 
            
            if($id=$base->devuelveIDInsercion($cosultaInsertar)){
                $this->setNumeroEmpleado($id);
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
        $cosultaModifica="UPDATE responsable SET rnumerolicencia='".$this->getNumeroLicencia()."',rnombre='".$this->getNombre()."',rapellido='".$this->getApellido()."' WHERE rnumeroempleado=".$this->getNumeroEmpleado();
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

     /** Elimina el registro en la base de datos que corresponde a la class responsable, si se inicia la conexion con el motor bd.*/

     public function eliminar(){
        $base=new BaseDatos();
        $resp=false;
        if($base->Iniciar()){
            $consultarBorra="DELETE FROM responsable WHERE rnumeroempleado=".$this->getNumeroEmpleado();
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

    /** para obtener los datos de responsable, devuelve un arreglo de obj responsable */
    public static function listar($condicion=""){
        $arregloResponsable=null;
        $base=new BaseDatos();
        $consultaResponsable="Select * FROM responsable ";
        if($condicion!=""){
            $consultaResponsable.=' where '.$condicion;
        }
        $consultaResponsable.=" order by rapellido ";

        if($base->Iniciar()){
           if($base->Ejecutar($consultaResponsable)){
                $arregloPasajero=array();
                while($row2=$base->Registro()){
                    $rnumeroempleado=$row2["rnumeroempleado"];
                    $rnumerolicencia=$row2["rnumerolicencia"];
                    $rnombre=$row2["rnombre"];
                    $rapellido=$row2["rapellido"];
                    
                    
                    $responsable=new Responsable();
                    $responsable->cargar($rnumerolicencia,$rnombre,$rapellido);
                    $responsable->setNumeroEmpleado($rnumeroempleado);
                    $arregloResponsable[]=$responsable;
                }
            }  else{
                $this->setMensajeOperacion($base->getError());
            
            }
        } else{
            $this->setMensajeOperacion($base->getError());
        }

       return $arregloResponsable; 
    }

    public function Buscar($rnumeroempleado){
        $base=new BaseDatos();
        $consultaResponsable="Select * FROM responsable Where rnumeroempleado=".$rnumeroempleado;
        $resp=false;
        if($base->Iniciar()){
            if($base->Ejecutar($consultaResponsable)){
                if($row2=$base->Registro()){
                   
                   $this->cargar($row2["rnumerolicencia"], $row2["rnombre"], $row2["rapellido"]);
                  
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