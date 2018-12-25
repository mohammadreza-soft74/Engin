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
        print_r(" time                          KEY                      status\n");
        $stopHoures = Config::get("docker.container_stop_time.houres");
        $stopMinutes = Config::get("docker.container_stop_time.minutes");
        $stopLogPath = $errorLogPath = Config::get("logs.stop.path");
        // for store key an container id in Redis
        $redis = RedisClientFactory::redis("key");

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


            $time = $now->format("H:i:s ");

            try {
                if ($houres >= $stopHoures || $minutes >= $stopMinutes) {

                    $containerId = $redis->hget($key, "id");

                    $stat = ContainerManager::getContainerState($containerId);


                    if ($stat) {
                        ContainerManager::stopContainer($containerId);
                        $log = "Container $key Stoped!\nelapsed time => $houres:$minutes:$secconds\n*****************************************************\n";
                        error_log($log, 3, $stopLogPath);

                        print_r("$time            $key   ");
                        print_r("            Stopped\n");
                    }

                }
                else {
                    print_r("$time            $key   ");
                    print_r("            Running\n");
                }
            }catch (\Exception $e){
                print_r($e->getMessage());
            }
        }

        $redis->disconnect();

    }
}
