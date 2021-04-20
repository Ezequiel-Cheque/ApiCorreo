<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('content-type: application/json; charset=utf-8');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

require_once 'PHPExcel/Classes/PHPExcel.php';
$objPHPExcel = new PHPExcel();

$datos=json_decode($_POST['datos']);
$correo=$_POST['correo'];

                    // $opResult = [
                    // 'status' => true,
                    // 'datos'=> $datos[0]->name
                    // ];


        if(correo($correo,$datos)){
            $opResult = [
                'status' => true,
                'msg'=>"Archivo creado y enviado con exito"
                ];
        }else{
            $opResult = [
                "status"=>false,
                "msg"=>"Errro al enviar correo"
                ];
        }


///Envio de correo
function correo($correo,$datos){
    $mail = new PHPMailer(true);
    try {
        //Server settings
        $mail->SMTPDebug = 0;                      // Enable verbose debug output
        $mail->isSMTP();                                            // Send using SMTP
        $mail->Host       = 'secure.emailsrvr.com';                    // Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
        $mail->Username   = 'edelaluz@cdcmx.com';                     // SMTP username
        $mail->Password   = 'Cheque_1318';                               // SMTP password
        $mail->SMTPSecure = 'ssl';         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
        $mail->Port       = 465;                                    // TCP port to connect to
        $mail->SMTPOptions = array(
    'ssl' => array(
    'verify_peer' => false,
    'verify_peer_name' => false,
    'allow_self_signed' => true
    )
    );
        //Recipients
        $mail->setFrom('edelaluz@cdcmx.com', 'Admin');
        $mail->addAddress($correo, 'Usuario');     // Add a recipient
        // Content
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = 'Datos de personajes seleccionados';
        $mensaje="";
        foreach ($datos as $key => $val) {
           $mensaje .= 'Nombre: '.$val->name.'<br>Status: '.$val->status.'<br>'.'species: '.$val->species.'<br>'.'type: '.$val->type.'<br>'.'gender: '.$val->gender;
           $mensaje .= '<br>--------------------------------------------------------------------------------';
           $mensaje .='<br><br>';
        }   
        $mail->Body    = $mensaje;
        $valor=$mail->send();
    
        return true;
      //echo 'El mensaje se ha enviado correctamente';
    } catch (Exception $e) {
        //echo "El mensaje no se ha podido mandar Error: {$mail->ErrorInfo}";
        return false;
    }
    }

echo json_encode($opResult);


?>