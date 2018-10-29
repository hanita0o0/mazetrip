<?php

namespace App\Http\Sockets;

use App\User;
use Illuminate\Session\SessionManager;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Crypt;
use Mockery\Exception;
use Orchid\Socket\BaseSocketListener;

use PhpParser\JsonDecoder;
use Ratchet\ConnectionInterface;

class MyClass extends BaseSocketListener
{
    /**
     * Current clients.
     *
     * @var \SplObjectStorage
     */
    protected $clients;
    /**
     * MyClass constructor.
     */
    public function __construct()
    {
//        $this->clients = new \SplObjectStorage();
        $this->clients = array();
    }

    /**
     * @param ConnectionInterface $conn
     */
    public function onOpen(ConnectionInterface $conn)
    {
        //store the new connection to send message to later
//        $this->clients->attach($conn);
//        $data = [$conn , 'first_message'=>true,'user_info'=>['api_token'=>null]];
//        $conn->first_message = true;
        $conn->user_info = null;
        array_push($this->clients,$conn);
        $numbers = count($this->clients);
        echo  "Connections Number ! ({$numbers})\n";
    }

    /**
     * @param ConnectionInterface $from
     * @param $msg
     */
    public function onMessage(ConnectionInterface $from, $msg)
    {
        $target = array_search($from , $this->clients);
//        $msg = json_encode($msg);
        $msg = json_decode($msg);
//        echo $msg->title;
        if (json_last_error() !== JSON_ERROR_NONE){
            echo "we got error";
        }
////        echo  $target;
//        if ($this->clients[$target]->user_info == null){
//            $user = User::where('api_token',$msg)->first();
//            if (empty($user)){
//                unset($this->clients[$target]);
//                $from->send('no access;');
//                $from->close();
//                $numbers = count($this->clients);
////                echo "Connection Numbers ! ({$numbers})\n";
//            }else {
//                $this->clients[$target]->user_info = $user->id;
//            }
//        }
    }

    /**
     * @param ConnectionInterface $conn
     */

    public function onClose(ConnectionInterface $conn)
    {
//        $this->clients->detach($conn);
//        echo  "Dissconnect  ! ({$conn->resourceId})\n";
    }

    /**
     * @param ConnectionInterface $conn
     * @param \Exception          $e
     */
    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo $e;
        $conn->close();
    }

    public function sendNotification ($user_id , $notificationInfo ){
//        $connections_keys  = array_keys([$user_id] , $this->clients);
//        echo '\n'.implode(" ",$connections_keys).'\n';
//        if (isset($connections_keys)){
//            foreach ($connections_keys as $key){
//                $this->clients[$key]->send($notificationInfo);
//            }
//        }

        foreach ($this->clients as $client){
            echo $client->resourceId;
            $client->send("hello");
        }
    }


}
