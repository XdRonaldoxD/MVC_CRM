<?php

require_once "config/Eventopusher.php";
require_once "models/LogChat.php";
require_once "models/ConsultaGlobal.php";
require_once "models/Usuario.php";


class PusherController
{

    protected $cliente_identificado;
    protected $mensaje_texto;
    protected $conversacion;
    protected $id_usuario;
    protected $pusher;
    protected $correo_usuario;
    protected $nombre_usuario;
    function __construct()
    {
        $this->cliente_identificado = isset($_POST['cliente_identificado']) ? $_POST['cliente_identificado'] : null;
        $this->mensaje_texto = isset($_POST['mensaje_texto']) ? $_POST['mensaje_texto'] : null;
        $this->conversacion = isset($_POST['conversacion']) ? $_POST['conversacion'] : null;
        $this->id_usuario = isset($_POST['id_usuario']) ? $_POST['id_usuario'] : null;
        $this->correo_usuario = isset($_POST['correo_usuario']) ? $_POST['correo_usuario'] : null;
        $this->nombre_usuario = isset($_POST['nombre_usuario']) ? $_POST['nombre_usuario'] : null;
        $this->pusher = Eventopusher::conectar();
    }

    public function ChatboxEventAdministrador()
    {
        $log_chat = Logchat::where("identificadorcliente_log_chat", $this->cliente_identificado)->first();
        $log_chat->id_usuario = $this->id_usuario;
        $log_chat->conversacion_log_chat = $this->conversacion;
        $log_chat->estado_linea_log_chat = 0;
        $log_chat->save();
        $nombre_usuario = Usuario::select("staff.nombre_staff")
            ->join("staff", "staff.id_staff", "usuario.id_staff")
            ->where("id_usuario", $this->id_usuario)
            ->first();
        $datos = [
            'cliente_identificado' => $this->cliente_identificado,
            'mensaje' => $this->mensaje_texto,
            'administrador' => $nombre_usuario->nombre_staff
        ];
        $this->pusher->trigger('ChatboxWoocommerce', 'ChatboxEventAdministrador', $datos);
        echo json_encode("ok enviado");
    }

    public function pusherController()
    {
        $datos = [
            'cliente_identificado' => $this->cliente_identificado,
        ];
        $this->pusher->trigger('ChatboxWoocommerce', 'ChatboxDesconetarClienteEvent', $datos);
        echo ("ok");
    }

    public function TraendoDatosChat()
    {
        $datos = Logchat::where("fechacreacion_log_chat", date('Y-m-d'))->where('estado_log_chat', 1)->get();
        echo ($datos);
    }

    public function CerrarChatBoxCliente()
    {
        $log_chat = Logchat::where("identificadorcliente_log_chat", $this->cliente_identificado)->first();
        $log_chat->estado_log_chat = 0;
        $log_chat->save();
        $datos = [
            'cliente_identificado' => $this->cliente_identificado,
        ];
        $this->pusher->trigger('ChatboxWoocommerce', 'ChatboxCerrarGlobalEvent', $datos);
        echo (true);
    }

    public function ChatActivos()
    {
        $TraerChat = Logchat::where("fechacreacion_log_chat", date('Y-m-d'))->where("estado_log_chat", 1)->where("estado_linea_log_chat", 1)->get();
        // dd($TraerChat);
        echo ($TraerChat);
    }

    //  API DEL CLIENTE WOOCOMMERCE
    function ChatBoxApi()
    {
        $log_chat = Logchat::where("identificadorcliente_log_chat", $this->cliente_identificado)->first();
        $log_chat->conversacion_log_chat = json_decode($this->conversacion);
        $log_chat->save();
        $datos = [
            'identificadorcliente_log_chat' => $this->cliente_identificado,
            'mensaje' => $this->mensaje_texto,
            'conversacion_log_chat' => $this->conversacion

        ];
        $this->pusher->trigger('ChatboxWoocommerce', 'ChatboxEvent', $datos);
        echo json_encode("ok enviado");
    }
    function ChatBoxDatoClienteApi()
    {
        $datos_cliente = [
            'nombre_log_chat' => $this->nombre_usuario,
            'email_log_chat' => $this->correo_usuario,
            'telefono_log_chat' => ($_POST['telefono_log_chat'] == "null") ? null : $_POST['telefono_log_chat'],
            'fechacreacion_log_chat' => date('Y-m-d H:i:s'),
            'estado_log_chat' => 1,
            'estado_linea_log_chat' => 1,
            'identificadorcliente_log_chat' => $this->cliente_identificado,
            "chat_seleccionado"=>0
        ];
        $Logchat=Logchat::create($datos_cliente);
        $datos_cliente+=['id_log_chat'=>$Logchat->id_log_chat];
        $this->pusher->trigger('ChatboxWoocommerce', 'ChatboxOkaDatoClienteEvent', $datos_cliente);
        echo json_encode(true);
    }
    function ChatBoxDatoClienteDesconectadoApi()
    {
        $log_chat = Logchat::where("identificadorcliente_log_chat", $this->cliente_identificado)->first();
        $log_chat->estado_linea_log_chat = 0;
        $log_chat->save();
        $datos = [
            'cliente_identificado' => $this->cliente_identificado,
        ];
        $this->pusher->trigger('ChatboxWoocommerce', 'ChatboxOkaClienteDesconectadoEvent', $datos);
        echo json_encode(true);
    }

    //PARA EL SISTEMA
    function TraerChatLineaActivo()
    {
        $fecha_inicio=date('Y-m-d') . ' 00:00:00';
        $fecha_fin=date('Y-m-d') . ' 23:59:59';
        $TraerChatLineaActivo=(new ConsultaGlobal())->TraerChatLineaActivo($fecha_inicio,$fecha_fin,$_GET['linea']);
        echo json_encode($TraerChatLineaActivo);
    }
}
