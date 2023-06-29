<?php
include_once("BaseDeDatos.php");

class Empresa{
    private $idempresa;
    private $enombre;
    private $edireccion;
    private $mensajeoperacion; 

    public function __construct(){
        $this->idempresa="";
        $this->enombre="";
        $this->edireccion="";
    }
    public function getIdEmpresa(){
        return $this->idempresa;
    }
    public function setIdEmpresa($idempresa){
        $this->idempresa=$idempresa;
    }

    public function getNombre(){
        return $this->enombre;
    }
    public function setNombre($enombre){
        $this->enombre=$enombre;
    }
    public function getDireccion(){
        return $this->edireccion;
    }
    public function setDireccion($edireccion){
        $this->edireccion=$edireccion;
    }
    
    
    public function getMensajeOperacion(){
        return $this->mensajeoperacion;
    }
    public function setMensajeOperacion($mensajeoperacion){
        $this->mensajeoperacion=$mensajeoperacion;
    }

    public function cargar($enombre,$edireccion){
        $this->setNombre($enombre);
        $this->setDireccion($edireccion);
    }
    
    public function insertar(){
        $base=new BaseDatos();
        $resp=false;
        $consultaInsertar="INSERT INTO empresa(enombre,edireccion) 
        VALUES ('" .$this->getNombre()."','".$this->getDireccion()."')";
        
        if($base->Iniciar()){
            
            if($id=$base->devuelveIDInsercion($consultaInsertar)){
                echo "id: ".$id;
                $this->setIdEmpresa($id);
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
        $base=new BaseDatos();
        $cosultaModifica="UPDATE empresa SET enombre='".$this->getNombre()."',edireccion='".$this->getDireccion()."' WHERE idempresa=".$this->getIdEmpresa();
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

     /** Elimina el registro en la base de datos que corresponde a la class empresa, si se inicia la conexion con el motor bd.*/

     public function eliminar(){
        $base=new BaseDatos();
        $resp=false;
        if($base->Iniciar()){
            $consultarBorra="DELETE FROM empresa WHERE idempresa=".$this->getIdEmpresa();
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

    /** para obtener los datos de empresa, devuelve un arreglo de obj empresa */
    public static function listar($condicion=""){
        $arregloEmpresa=null;
        $base=new BaseDatos();
        $consultaEmpresa="Select * FROM empresa ";
        if($condicion!=""){
            $consultaEmpresa.=' where '.$condicion;
        }
        $consultaEmpresa.=" order by enombre ";

        if($base->Iniciar()){
           if($base->Ejecutar($consultaEmpresa)){
                $arregloEmpresa=array();
                while($row2=$base->Registro()){
                    $idempresa=$row2["idempresa"];
                    $enombre=$row2["enombre"];
                    $edireccion=$row2["edireccion"];
                    
                    $empresa=new Empresa();
                    $empresa->cargar($enombre,$edireccion);
                    $empresa->setIdEmpresa($idempresa);
                    $arregloEmpresa[]=$empresa;
                }
            }  else{
                $this->setMensajeOperacion($base->getError());
            
            }
        } else{
            $this->setMensajeOperacion($base->getError());
        }

       return $arregloEmpresa; 
    }

    public function Buscar($idempresa){
        $base=new BaseDatos();
        $consultaEmpresa="Select * FROM empresa Where idempresa=".$idempresa;
        $resp=false;
        if($base->Iniciar()){
            if($base->Ejecutar($consultaEmpresa)){
                if($row2=$base->Registro()){
                    
                    $this->cargar($row2["enombre"],$row2["edireccion"]);
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

    public function __toString(){
        return "\n ID Empresa: ".$this->getIdEmpresa()."\n Nombre: ".$this->getNombre()."\n Dirección: ".$this->getDireccion();
    }
}