<?php
namespace Dash\Commands;

use Composer\InstalledVersions;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class GetUpdates extends Command {
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'dash:update {version?}';
	protected $url = 'https://phpdash.com';
	protected $checking = 'updates/check';
	protected $access_key;
	protected $current_version;
	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Download New Updates from Dash';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
		$this->access_key = config('dash.DASH_ACCESS_KEY');
		$this->current_version = substr(InstalledVersions::getVersion('phpanonymous/dash'), 0, -2);
	}

	public function statusConnect() {
		$client = new \GuzzleHttp\Client(['verify' => false]);
		$res = $client->request('GET', $this->url);
		return $res->getStatusCode();
	}

	public function encode_json($response) {
		return json_decode($response);
	}

	public function url($segment, $message = null, $params = '') {
		$client = new \GuzzleHttp\Client(['verify' => false]);

		if ($message) {
			$this->info($message);
		}

		$request = $client->request('GET', $this->url . '/' . $this->checking . '?access_key=' . $this->access_key . $params);
		return $this->encode_json($request->getBody());
	}

	/**
	 * Execute the console command.
	 *
	 * @return int
	 */
	public function handle() {

		$this->info('Checking for new Updates');
		$this->warn('your current version is ' . $this->current_version);

		$status = $this->statusConnect();
///Volumes/Untitled/www/dashtemp/dash/src/publish/public/dashboard/assets/css/material-dashboard.css
		$version = $this->argument('version');
		$rollback = false;
		if ($status == '200') {
			if ($version) {
				// when rollback version
				$message = 'rollback to version ' . $version;
				$rollback = true;
				$content = $this->url($this->checking, $message);
			} else {
				// when search for new updates
				$message = 'Searching for new updates, please wait';
				$content = $this->url($this->checking, $message);
			}

			if (!$content->status) {
				$this->errorMessage($content->message);
			} else {
				if (isset($content->enter_password) && $content->enter_password) {
					$password = $this->secret($content->message);
					if ($password) {
						$params = $rollback ? '&rollabck_version=' . $version : '';
						$params .= '&password=' . $password;
						$content = $this->url($this->checking, $message, $params);

						if (!$content->status) {
							$this->errorMessage($content->message);
						} else {
							$this->extractVersion($content);
						}
					} else {
						$this->errorMessage('failed please enter password');
					}
				}
			}

		} else {
			$this->info('You are doing well, you have the latest updates');
		}

		return 0;
	}

	public function extractVersion($content) {
		if (!$content->status) {
			$this->errorMessage($content->message);
		} else {
			$this->info($content->message);
			if (!empty($content->update)) {
				if (version_compare(
					$content->update->version,
					$this->current_version
					, '>')) {
					$confirm = $this->confirm('there\'s a version (' . $content->update->version . ') higher than your current version (' . $this->current_version . ') , would you like to install it ?');

					if ($confirm) {
						$this->extractNow($content->url);
					} else {
						$this->info('The installation has been cancelled. Thank you for choosing Dash');
					}
				} else {
					$this->info('You have the latest update and everything is fine');
				}
			} else {
				$this->info('You are doing well, you have the latest updates');
			}
		}
	}

	public function errorMessage($message) {
		$this->newLine(2);
		$this->line('===============================');
		$this->error($message);
		$this->line('===============================');
		$this->newLine(2);
	}

	public function extractNow($url) {
		$savePath = 'dashupdates/dash.zip';
		if (!\File::exists(storage_path('app/public/dashupdates'))) {
			\File::makeDirectory(storage_path('app/public/dashupdates'), 0755, true);
		}

		$guzzle = new Client(['verify' => false]);
		$response = $guzzle->get($url);
		\Storage::disk('public')->put($savePath, $response->getBody());

		$zip = new \ZipArchive();
		$status = $zip->open(storage_path('app/public/' . $savePath), \ZipArchive::CREATE);
		if ($status === true) {


			$this->info('Extract file please wait ...');
			if ($zip->extractTo(base_path('/'))) {
				$zip->close();

				if (\Storage::disk('public')->exists($savePath)) {
					\Storage::disk('public')->delete($savePath);
				}

				$this->info('files extracted successfully');

				$this->warn('Updating and installing please wait');

                $this->pushingUpdateFiles();

                $this->pushingUpdateToDashConfig();

				exec('composer update');

				$this->info('Installed successfully Thank you for choosing Dash');
			}
		}

	}

    public function pushingUpdateFiles(){
        $this->warn('updating dashboard files ...');
        if (\File::exists(public_path('dashboard'))){
            File::deleteDirectory(public_path('dashboard'));
        }

        \File::copyDirectory(__DIR__ . '/../publish/public/dashboard', public_path('dashboard'));

    }

    public function pushingUpdateToDashConfig(){
        $this->info('scan dash.php config file for updates ...');

        // add FILESYSTEM_DISK
        if(!empty(array_keys(config('dash'))) && !in_array('FILESYSTEM_DISK',array_keys(config('dash')))){
            $dash_config = file_get_contents(config_path('dash.php'));
            $new_key = "        /**
            *  FILE SYSTEM DISK DRIVER YOU CAN Use public,s3
            * @param string
            */
            'FILESYSTEM_DISK'=>env('FILESYSTEM_DISK','public'),

        ];";
        $dash_config = str_replace('];',$new_key,$dash_config);
        file_put_contents(config_path('dash.php'),$dash_config);
        $this->warn('update FILESYSTEM_DISK dash.php config file ...');
        }

        // add DARK_MODE
        if(!empty(array_keys(config('dash'))) && !in_array('DARK_MODE',array_keys(config('dash')))){
            $dash_config = file_get_contents(config_path('dash.php'));
            $new_key = "         /**
            * DARK MODE Style with  on , off
            * @param string
            */
           'DARK_MODE' => env('DASH_DARK_MODE','on'),

        ];";
            $dash_config = str_replace('];',$new_key,$dash_config);
            file_put_contents(config_path('dash.php'),$dash_config);
            $this->warn('update DARK_MODE dash.php config file ...');
        }


        // add CHECK_VERSION_MODE
        if(!empty(array_keys(config('dash'))) && !in_array('CHECK_VERSION_MODE',array_keys(config('dash')))){
            $dash_config = file_get_contents(config_path('dash.php'));
            $new_key = "         /**
            * CHECK  VERSION from phpdash.com with  on , off
            * @param string
            */
           'CHECK_VERSION_MODE' => env('CHECK_VERSION_MODE','off'),

        ];";
            $dash_config = str_replace('];',$new_key,$dash_config);
            file_put_contents(config_path('dash.php'),$dash_config);
            $this->warn('update CHECK_VERSION_MODE dash.php config file ...');
        }



    }



}
