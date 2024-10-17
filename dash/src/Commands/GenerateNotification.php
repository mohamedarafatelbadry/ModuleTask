<?php
namespace Dash\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class GenerateNotification extends Command {
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'dash:make-notify {notification}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Generate New Notification';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
	}

	protected function getResourceStub() {
		return file_get_contents(__DIR__ .'/myStubs/Notification.stub');
	}

	protected function generate($namespace = 'App\Dash\Notifications', $name) {
		$template = str_replace(
			['{{namespace}}', '{{name}}'],
			[$namespace, $name],
			$this->getResourceStub()
		);

		$path = app_path("Dash/Notifications");
		if (!File::exists($path)) {
			File::makeDirectory($path, 0775, true);
		}

		file_put_contents($path.'/'.$name.'.php', $template);

		// blade views
		file_put_contents(
			resource_path('views').'/'.$name.'_notifications.blade.php',
			file_get_contents(__DIR__ .'/myStubs/NotificationBlade.stub')
		);
	}

	/**
	 * Execute the console command.
	 *
	 * @return int
	 */
	public function handle() {
		$notification = $this->argument('notification');
		$this->generate('App\Dash\Notifications', $notification);
		$this->info($notification.' Notification generated');
		return 0;
	}
}
