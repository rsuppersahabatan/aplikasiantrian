<?php
namespace MyApp;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Queue implements MessageComponentInterface {
    protected $clients;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn) {
        // Store the new connection to send messages to later
        $this->clients->attach($conn);

        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $numRecv = count($this->clients) - 1;
        echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
            , $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');

        

        foreach ($this->clients as $client) {
            $call = explode(":",$msg);
            //echo $from->resourceId . " : ". $client."\n";

            if($call[0] == "dequeue"){
                $antrian = file_get_contents("../../data/antrian.json", TRUE);
                $dt = json_decode($antrian, TRUE);
                
                $index = $call[1]-1;

                // $pics   = $_POST['pics'];
                unset($dt['data'][$index]);
                //if($dt['sisa'] == 0)
                $dt['sisa'] = count($dt['data']);
                //else
                   // $dt['sisa'] = $dt['sisa'] - 1;

                $fp = fopen("../../data/antrian.json","w");
                fputs($fp, json_encode($dt));
                fclose($fp);

                $client->send("sisa:".$dt['sisa']."/".$dt['jumlah']);
            }

            if($call[0] == "getnasabah"){
                $antrian = file_get_contents("../../data/nasabah.json", TRUE);
                $dt = json_decode($antrian, TRUE);
                
                $index = $call[3]-1;

                // $pics   = $_POST['pics'];
                $nasabah = $dt['data'][$index];

                $dt_send= "datanasabah:".$call[1].":".$call[2].":".str_replace("data:image/webp;", "", $nasabah[3]).":".$nasabah[4];
                $client->send($dt_send);

            }

            if($msg == "resetnasabah"){
                $nasabah = file_get_contents("../../data/nasabah.json", TRUE);
                $dt = json_decode($nasabah, TRUE);
                $dt['data'] = array();
                $dt['jumlah'] = 0;
                $fp = fopen("../../data/nasabah.json","w");
                fputs($fp, json_encode($dt));
                fclose($fp);

                $antrian = file_get_contents("../../data/antrian.json", TRUE);
                $dt = json_decode($antrian, TRUE);
                $dt['data'] = array();
                $dt['jumlah'] = 0;
                $dt['sisa'] = 0;
                $fp = fopen("../../data/antrian.json","w");
                fputs($fp, json_encode($dt));
                fclose($fp);


                $client->send("sisa:".$dt['sisa']."/".$dt['jumlah']);
            } 

            if($msg == "resetantrian"){
                $fp = fopen("../../data/data.txt","w");
                fputs($fp, 1);
                fclose($fp);
                $client->send("resetantrian:1");
            }

        	if($from === $client){
                if($call[0] == "call"){
                    $location_counter = "../../data/data.txt";
                    $tcounter = file_get_contents($location_counter, true);
                    trim($tcounter);
                    /*$tcounter++;

                    $fp = fopen($location_counter,"w");
                    fputs($fp, $tcounter);
                    fclose($fp);*/
                    $dt_send= "callback".$call[1].":".$tcounter.":".$call[2];
                    $client->send($dt_send);
                }
        		

                if(substr($msg, 0, 6) == 'teller'){
                    $loc_teller = "../../data/teller.json";
                    $t_teller = file_get_contents($loc_teller, true);
                    $dt = json_decode($t_teller, TRUE);
                    //if($dt[$msg] == '' || is_null($dt[$msg]))
                    $dt[$msg] = $from->resourceId;
                    //else
                    //    $from->close();
                        //$client->send("failed:Tidak dapat melakukan koneksi, ".$msg." sudah terbuka !");

                    $fp = fopen($loc_teller,"w");
                    fputs($fp, json_encode($dt));
                    fclose($fp);

                    $antrian = file_get_contents("../../data/antrian.json", TRUE);
                    $dt = json_decode($antrian, TRUE);

                    $location_counter = "../../data/data.txt";
                    $tcounter = file_get_contents($location_counter, true);
                    trim($tcounter);
                    $client->send("sisa:".count($dt['data'])."/".$dt['jumlah']);
                    $client->send("resetantrian:".$tcounter);
                }

                if($call[0] == "write"){
                    $location_counter = "../../data/data.txt";
                    $fp = fopen($location_counter,"w");
                    fputs($fp, $call[1]+1);
                    fclose($fp);
                }

                //echo $msg . " : ". $from->resourceId."\n";
                //echo $msg . " : ". (substr($msg, 0, 5) == 'teller')." ".(substr($msg, 0, 6) == 'teller')."\n";
        	}

            if ($from !== $client) {
                if($call[0] == "call"){
                    // The sender is not the receiver, send to each client connected
                    $location_counter = "../../data/data.txt";
                    $tcounter = file_get_contents($location_counter, true);
                    trim($tcounter);
                    $dt_send= $call[1].":".$tcounter.":call:".$call[2];

                    $client->send($dt_send);
                    
                }

                if($call[0] == "recall"){
                    // The sender is not the receiver, send to each client connected
                    
                    $dt_send= $call[1].":".$call[3].":recall:".$call[2];
                    $client->send($dt_send);
                } 

                if($msg == "next"){
                    $client->send($msg);
                }               
            }

        }
    }

    public function onClose(ConnectionInterface $conn) {
        // The connection is closed, remove it, as we can no longer send it messages
        $this->clients->detach($conn);

        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }
}