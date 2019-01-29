<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Config;
use App\Clasess\Base\Memory\RedisClientFactory;
use DateTime;
use DateTimeZone;
use App\Clasess\Base\Managers\ContainerManager\ContainerManager;
class StopContainer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Stop:Container';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Stop running containers, that elapsed one hour from last activity';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $stopHoures = Config::get("docker.container_stop_time.houres");
        $stopMinutes = Config::get("docker.container_stop_time.minutes");
        $stopLogPath = $errorLogPath = Config::get("logs.stop.path");
        // for store key an container id in Redis
        $redis = RedisClientFactory::redis();

        $keys = $redis->keys("*");

        foreach ($keys as $key) {


            $now = time();
            $userLastAction = $redis->hget($key,"timeStamp");

            $now = (new DateTime('@' . $now))->setTimezone(new DateTimeZone('Asia/Tehran'));
            $userLastAction = (new DateTime('@' . $userLastAction))->setTimezone(new DateTimeZone('Asia/Tehran'));

            $diff = date_diff($now, $userLastAction);
            $minutes = $diff->i;
            $houres = $diff->h;
            $secconds = $diff->s;


            try {
                if ($houres >= $stopHoures || $minutes >= $stopMinutes) {

                	$user = explode(':',$key);
                	$userKey = $user[1];


                    $stat = ContainerManager::getContainerState($userKey);


                    if ($stat) {
						print_r($userKey."\n");
                        ContainerManager::stopContainer($userKey);
                        $log = "Container $key Stoped!\nelapsed time => $houres:$minutes:$secconds\n*****************************************************\n";
                        error_log($log, 3, $stopLogPath);
                    }
                }
            }catch (\Exception $e){
                print_r($e->getMessage());
            }
        }

        $redis->disconnect();

    }
}
