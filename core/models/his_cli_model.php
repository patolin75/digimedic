<?php
class hiscli_model{
    private $db;

    public function __construct(){
        $this->db=Conectar::conexion();
    }

    function get_institucion($code){
        $sql="select * from tb_institucion where id_institucion=$code and estado=1";


        $consulta= Conectar::select($this->db,$sql);    
        return $consulta;
    }

    function get_formatorec($code){
        $sql="select email_usuarios,telefono_usuarios,nombres_usuarios,apellidos_usuarios,formareceta_usuarios from tb_usuarios where id_usuarios=$code and activo_usuarios=1";
        $consulta= Conectar::select($this->db,$sql);        
        return $consulta;
    }

    function get_paciente($id){
            $sql= "select id_cliente as id,clientes_identificacion as identifica, clientes_foto as foto,clientes_apellidos as apellidos,clientes_nombres as nombres,clientes_telefono,
            clientes_email,clientes_tiposangre,clientes_estadocivil,clientes_sexo,DATE_FORMAT(clientes_fechanac,'%Y-%m-%d'),concat (TIMESTAMPDIFF(YEAR,clientes_fechanac,CURDATE()),' años') AS edad,concat (TIMESTAMPDIFF(YEAR,clientes_fechanac,CURDATE()),' años') AS edad,
            clientes_nacionalida,clientes_provincia,clientes_canton, clientes_parroquia, clientes_fecharegistro from tb_clientes where id_cliente='".$id."'";
            $consulta= Conectar::select($this->db,$sql);
            return $consulta;
    }


    function get_pacientes($idinstitu){
        $consulta= "select * FROM tb_clientes where id_empresa='".$idinstitu."'";
        $personas= Conectar::select($this->db,$consulta);
        return $personas; 
    }

    function get_citactual($code){
        $hora=date('G:i');
        $hora=date ('G:i',strtotime ('-1 hour' , strtotime ( $hora ) ));
        if (strlen($hora)==4) $hora='0'.$hora;

        $sql="select  DATE_FORMAT(cita_fechadesde, '%Y-%m-%d') as fecha, cita_nombrepaciente as nombre,cita_apellidopaciente as apellido,  DATE_FORMAT(cita_fechadesde, '%H:%i') 
        as hora from tb_citas where  DATE_FORMAT(cita_fechadesde, '%Y-%m-%d') = '".date('Y-m-d')."' and DATE_FORMAT(cita_fechadesde, '%H:%i') >= '".$hora."' and cita_finalizada=0 and cita_confirmada=1 
        and id_especialista='".$code."' ORDER by fecha LIMIT 3";
        $consulta= Conectar::accion($this->db,$sql);
        if (!$consulta) $error='e'; else $error= 0;

        return $consulta;
    }

    function get_historia($idinstitu, $cliente){
        $consulta= "select id_historias as idh, historias_fecha as fechah, num_historia as numh from tb_historias where id_institucion=".$idinstitu." and id_cliente=".$cliente;
        $personas= Conectar::select($this->db,$consulta);
        return $personas; 
    }

    function get_qr($code){
        $sql="select clientes_nombres,clientes_apellidos,clientes_telefono,clientes_email,clientes_tiposangre,residencia_provincia,residencia_canton,residencia_parroquia,residencia_cprincipal,residencia_numero,residencia_barrio,adicional_discapa,contacto_llamara,contacto_telefono from tb_clientes a, tb_residencia b, tb_adicionales c,tb_contacto d where a.id_cliente=".$code." and b.id_cliente = a.id_cliente and c.id_cliente = a.id_cliente and d.id_cliente=a.id_cliente";
        $persona= Conectar::select($this->db,$sql);

        foreach ($persona as $valores){
            $name         = $valores['clientes_nombres'].' '.$valores['clientes_apellidos'];
            $sortName     = $valores['clientes_nombres'];
            $phoneCell    = $valores['clientes_telefono'];
            $orgName      = 'Sistema Medico';
            $email        = $valores['clientes_email'];

            if ($valores['clientes_tiposangre']) $tiposangre   = 'Tipo '.$valores['clientes_tiposangre'];
            if ($valores['contacto_llamara']) $emergen      = 'Cont Emergencia '.$valores['contacto_llamara']. ' '.$valores['contacto_telefono'];
            if ($valores['adicional_discapa']) $discapa      = '% Disc '.$valores['adicional_discapa'];
            
            $addressLabel = 'Domicilio';
            if ($valores['residencia_barrio']) $addressPobox     =  $valores['residencia_barrio'];
            if ($valores['residencia_numero']) $addressExt       =  $valores['residencia_numero'];
            if ($valores['residencia_cprincipal']) $addressStreet    =  $valores['residencia_cprincipal'];
            if ($valores['residencia_provincia']) $addressTown      =  $valores['residencia_provincia'];
            if ($valores['residencia_canton']) $addressRegion    =  $valores['residencia_canton'];
            $addressPostCode  = '';
            $addressCountry   = 'EC';
    
            $codeContents  = 'BEGIN:VCARD'."\n";
            $codeContents .= 'VERSION:2.1'."\n";
            $codeContents .= 'N:'.$sortName."\n";
            $codeContents .= 'FN:'.$name."\n";
            $codeContents .= 'ORG:'.$orgName."\n";
    
            $codeContents .= 'TEL;TYPE=cell:'.$phoneCell."\n";

            $codeContents .= 'EMER;TI:'.$tiposangre."\n";
            $codeContents .= 'EMER;CONT:'.$emergen."\n";

            $codeContents .= 'ADR;TYPE=home;'.
                'LABEL="'.$addressLabel.'":'
                .$addressPobox.';'
                .$addressExt.';'
                .$addressStreet.';'
                .$addressTown.';'
                .$addressPostCode.';'
                .$addressCountry
            ."\n";
            $codeContents .= 'EMAIL:'.$email."\n";
            $codeContents .= 'END:VCARD';
        }

        return $codeContents;
    }

    function get_visitas($user,$code){
        $consulta= "select id_histovisitas as idvisit, histovisitas_fecha as fecha from tb_historiavisitas where id_historias=".$code." and id_usuario=".$user." order by fecha desc";
        $visitas= Conectar::select($this->db,$consulta);
        return $visitas; 
    }
    
    function get_valoresvisitas($code){
        $consulta= "select id_histovisitas as idvisit, histovisitas_fecha as fecha, campo1,campo2,campo3,campo4,campo5,campo6,campo7,campo8,campo9,campo10 from tb_historiavisitas where id_histovisitas=".$code;
        $visitas= Conectar::select($this->db,$consulta);
        return $visitas; 
    }

    function get_examenes($code,$user,$institu){
        $consulta= "select id_examenes as id, examenes_fechapedido as fechas from tb_examenes where id_institucion=".$institu. ' and id_cliente='.$code.' and id_usuario='.$user. ' and examenes_fechaentrega is not null order by fechas desc ';
        $exmanes= Conectar::select($this->db,$consulta);
        return $exmanes;
    }

    function get_vademecum(){
        $consulta= "select vademecum_regsan regsan, vademecum_laboratorio laboratorio, vademecum_nombreprod nombreprod, vademecum_administracion administrar, vademecum_presentacion presenta from tb_vademecum";
        $vadem= Conectar::select($this->db,$consulta);
        return $vadem;
    }

    function get_especialidad($id){
        $consulta= "select id_especialidad from tb_usuarios a where a.id_usuarios =".$id;
        $personas= Conectar::select($this->db,$consulta);
        foreach($personas as $valor)
            $especia = $valor['id_especialidad'];
        return $especia; 
       
    }

    function set_updaexam($code,$valor){
        $sql = "update tb_examenes SET examenes_observaciones='".$valor."' WHERE id_examenes=".$code;
        $consulta1= Conectar::accion($this->db,$sql);
    }

    function set_savevisita($code,$institu,$user,$hiscli,$visihis,$valor){
        $add=0;
        $partes= "";
        $elemenu ="";
        $elemena ="";
        $valores ="";
        $partes= explode("ª",$valor); 
        $i=1;
        foreach($partes as $valor){
            $elemenu = $elemenu."campo".$i."='".$valor."',";
            $elemena = $elemena."campo".$i.",";
            $valores = $valores."'".$valor."',";
            $i++;
        }
        $elemenu = substr($elemenu, 0, -1);
        $elemena = substr($elemena, 0, -1);
        $valores = substr($valores, 0, -1);

        if ($hiscli ==0) { //AD
            $numhis = self::get_uniquename($code);
            $sql="insert INTO tb_historias (id_institucion, id_cliente,historias_fecha,num_historia) VALUES ('".$institu."', '".$code."','".date('Y-m-d')."','".$numhis."')";
            $consulta= Conectar::accion($this->db,$sql);

            $hiscli=self::get_idhistoria($institu,$code,$this->db);
            if (!$consulta) $error= 'e'; else $error=1;
        }
        if ($hiscli > 0){
            if ($visihis ==0){ //ADD
                $sql="insert INTO tb_historiavisitas (id_historias, id_usuario, histovisitas_fecha,".$elemena.") VALUES (".$hiscli.",".$user.",'".date('Y-m-d')."',".$valores.")";
                $add=1;
            }else //UPDATE
                $sql = "update tb_historiavisitas SET ".$elemenu." WHERE id_histovisitas=".$visihis." and id_historias=".$hiscli;

            $consulta1= Conectar::accion($this->db,$sql);
            if ($add ==1) $error=self::get_idvisita($hiscli,$this->db); else $error=$visihis;
            if (!$consulta1) $error= 'e';
        }

        return $error;
    }

    function set_cerrarse($code){
        $sql = "update tb_citas u 
        INNER JOIN 
        (
        SELECT id_citas FROM tb_clientes a, tb_citas b where b.id_paciente = a.clientes_identificacion and a.id_cliente=".$code." and  DATE_FORMAT(b.cita_fechadesde,'%Y-%m-%d') = '".date('Y-m-d')."' and b.cita_finalizada=0 order by b.cita_fechadesde ASC limit 1
        ) AS t
        ON u.id_citas = t.id_citas
        SET u.cita_finalizada=1";

        $consulta1= Conectar::accion($this->db,$sql);

        if (!$consulta1) $error= 'e'; else $error=1;

        return $error;
    }

    function get_valgrafica($code,$hiscli){
        $consulta="select histovisitas_fecha fecha,campo1,campo2,campo3,campo4,campo5,campo6,campo7,campo8,campo9,campo10 from tb_historias a, tb_historiavisitas b where a.id_historias=".$hiscli." and a.id_cliente=".$code." and b.id_historias=a.id_historias order by histovisitas_fecha desc";
        $grafica= Conectar::select($this->db,$consulta);

        return $grafica; 
    }

    static function get_idvisita($idhis,$db){
        $consulta= "select max(id_histovisitas) as id from tb_historiavisitas where id_historias=".$idhis;
        $historias= Conectar::select($db,$consulta);
        foreach($historias as $valor){
            $id = $valor['id'];
        }
        return $id; 
    }

    static function get_idhistoria($institu,$code,$db){
        $id=0;
        $consulta= "select id_historias from tb_historias where id_institucion='".$institu."' and id_cliente='".$code."'";
        $historias= Conectar::select($db,$consulta);
        foreach($historias as $valor){
            $id = $valor['id_historias'];
        }
        return $id; 
    }

   static function get_uniquename($name){
        $name = $name."_";
        $name= $name.date("YmdHis");
        $name= $name.substr(md5(rand(0, 10000000)), 6);
        return $name;
    }
    
}
?>