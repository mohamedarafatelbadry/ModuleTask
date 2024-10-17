<?php
use Dash\Controllers\Authentication;
use Dash\Controllers\Dashboard;
use Dash\Controllers\DashboardTools;
use Dash\Controllers\GUIUpdate;
use Dash\Controllers\SearchableInResouces;
use Illuminate\Support\Facades\Route;

$DASHBOARD_PATH = app('dash')['DASHBOARD_PATH'];

Route::group(['middleware' => ['web']],

	function () use ($DASHBOARD_PATH) {

		Route::fallback(function () {
			return view('dash::errors.404');
		});
		Route::post('select2/load/data', [DashboardTools::class, 'dynamic_select2_search']);
		Route::post('get/statistics', [DashboardTools::class, 'get_statistics']);
		Route::post('gui/update/now', [GUIUpdate::class, 'gui_update_now']);

		Route::get('/', fn() => redirect(auth()->guard('dash')->check() ? $DASHBOARD_PATH . '/dashboard' : $DASHBOARD_PATH . '/login'));
		/////////////////////////// Guest Loggedin ////////////////////
		Route::get('login', [Authentication::

				class, 'index'])->middleware('dash.guest');
		Route::post('login', [Authentication::class, 'loggedin'])->name($DASHBOARD_PATH . '.login')->middleware('dash.guest');
		Route::any('logout', [Authentication::class, 'logout'])->name($DASHBOARD_PATH . '.logout')->middleware('dash.auth');
		/////////////////////////// Guest Loggedin ////////////////////

		Route::get('change/language/{lang}', [Dashboard::class, 'changeLanguage']);
		Route::get('darkmode/{status}', [Dashboard::class, 'changeDarkMode']);
		Route::get('sidenav/toggled/{status}', [Dashboard::class, 'sidenav_toggled']);

		Route::get('/page', function () {
			return redirect(url(app('dash')['DASHBOARD_PATH']));
		});
		Route::get('/resource', function () {
			return redirect(url(app('dash')['DASHBOARD_PATH']));
		});

		/////////////////////////// Auth Links ////////////////////
		Route::middleware('dash.auth')->group(
			function () {
				Route::get('dashboard', [Dashboard::class, 'index']);
				Route::get('no-permission', [Dashboard::class, 'no_permission']);
				Route::post('ui', [Dashboard::class, 'ui']);

				// Deletable Files By Model Start
				Route::post('deletable/by/model', [DashboardTools::class, 'deleteFilesByModel']);
				// Deletable Files By Model End
				// Route::post('upload/image/trix', [DashboardTools::class , 'upload_image']);
				// Route::post('delete/image/trix', [DashboardTools::class , 'delete_image']);
				// to load specific model with morphTo , belongsTo Start
				Route::post('load/model', [DashboardTools::class, 'load_model']);
				Route::post('main/search', [SearchableInResouces::class, 'handel_search']);
				// to load specific model with morphTo , belongsTo End
			});
		/////////////////////////// Auth Links ////////////////////

	});
