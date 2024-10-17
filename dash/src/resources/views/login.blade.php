<!DOCTYPE html>
<html lang="{{ app()->getLocale()=='ar'?'ar':'en' }}" dir="{{ app()->getLocale()=='ar'?'rtl':'' }}">
	<head>

		<!-- Meta data -->
		<meta charset="UTF-8">
		<meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>

		<!-- Title -->
		<title>@lang('dash::dash.login')</title>
        <link rel="stylesheet" type="text/css" href="{{ url('dashboard/assets/fonts/cairo/style.css') }}" />
		<!--Favicon -->
		<link rel="icon" href="{{ url(config('dash.DASHBOARD_ICON')) }}" type="image/x-icon"/>

		<!-- Bootstrap css -->
		<link href="{{ url('dashboard/assets/dashtemplate') }}/plugins/bootstrap/css/bootstrap.css" rel="stylesheet" />

		<!-- Style css -->
		<link href="{{ url('dashboard/assets/dashtemplate') }}/css/style.css" rel="stylesheet" />
		<link href="{{ url('dashboard/assets/dashtemplate') }}/css/boxed.css" rel="stylesheet" />
		<link href="{{ url('dashboard/assets/dashtemplate') }}/css/dark.css" rel="stylesheet" />
		<link href="{{ url('dashboard/assets/dashtemplate') }}/css/skin-modes.css" rel="stylesheet" />
		<link href="{{ url('dashboard/assets/dashtemplate') }}/css/transparent-style.css" rel="stylesheet">

		<!-- Animate css -->
		<link href="{{ url('dashboard/assets/dashtemplate') }}/css/animated.css" rel="stylesheet" />

		<!---Icons css-->
		<link href="{{ url('dashboard/assets/dashtemplate') }}/css/icons.css" rel="stylesheet" />

	</head>

	<body class="">

		<div class="page  responsive-log login-bg">
			<div class="page-single">
                <div class="col-12">
                    @if(session()->has('error'))
                    <div class="alert alert-warning">
                      {{ session('error') }}
                    </div>
                    @endif
                </div>
				<div class="container">
					<div class="row">
						<div class="col mx-auto">
							<div class="row justify-content-center">
								<div class="col-md-8 col-lg-6 col-xl-4 col-xxl-4">
									<div class="card my-5">
										<div class="p-4 pt-6 text-center">
											<h1 class="mb-2">@lang('dash::dash.login')</h1>
											{{--  <p class="text-muted">Sign In to your account</p>  --}}
										</div>
										<form class="card-body pt-3" id="login"  method="post" action="{{ route(app('dash')['DASHBOARD_PATH'].'.login') }}">
										@csrf
                                            <div class="form-group">
											<label class="form-label">@lang('dash::dash.email')</label>
												<div class="input-group mb-4">
													<div class="input-group">
													   <a href="#" class="input-group-text">
																<i class="fe fe-mail" aria-hidden="true"></i>
															</a>
														<input class="form-control {{ $errors->has('email')?'is-invalid':'' }}" type="email" name="email" value="{{ old('email') }}" value="{{ old('email') }}" placeholder="Email" >
                                                        @error('email')
                                                        <p class="invalid-feedback">{{ $message }}</p>
                                                        @enderror
                                                    </div>
												</div>
											</div>
											<div class="form-group">
											<label class="form-label">@lang('dash::dash.password')</label>
												<div class="input-group mb-4">
													<div class="input-group" id="Password-toggle">
														<a href="#" class="input-group-text">
															<i class="fe fe-eye-off" aria-hidden="true"></i>
														</a>
														<input type="password" name="password" class="form-control {{ $errors->has('password')?'is-invalid':'' }}"  placeholder="@lang('dash::dash.password')">
                                                        @error('password')
                                                        <p class="invalid-feedback">{{ $message }}</p>
                                                        @enderror
                                                    </div>
												</div>
											</div>
											<div class="form-group">
												<label class="custom-control custom-checkbox">
													<input type="checkbox" class="custom-control-input" name="remember_me" value="yes">
													<span class="custom-control-label">@lang('dash::dash.remember_me')</span>
												</label>
											</div>
											<div class="submit">
												<button class="btn btn-primary btn-block" type="submit">@lang('dash::dash.signin')</button>
											</div>
											<div class="text-center mt-3">
												{{--  <p class="mb-2"><a  href="javascript:void(0);">Forgot Password</a></p>  --}}

											</div>
										</form>
										<div class="card-body border-top-0 pb-6 pt-2">
											<div class="text-center">
                                                @if(!empty($DASHBOARD_LANGUAGES) && count($DASHBOARD_LANGUAGES) > 1)
                                                @foreach($DASHBOARD_LANGUAGES as $key=>$value)
                                                 <a href="{{ url($DASHBOARD_PATH.'/change/language/'.$key) }}">
                                                    <small>{{ $value }}</small>
                                                  </a>,
                                                @endforeach
                                                @endif
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- Jquery js-->
		<script src="{{ url('dashboard/assets/dashtemplate') }}/plugins/jquery/jquery.min.js"></script>

		<!-- Bootstrap js-->
		<script src="{{ url('dashboard/assets/dashtemplate') }}/plugins/bootstrap/js/popper.min.js"></script>
		<script src="{{ url('dashboard/assets/dashtemplate') }}/plugins/bootstrap/js/bootstrap.min.js"></script>

		<!-- Select2 js -->
		<script src="{{ url('dashboard/assets/dashtemplate') }}/plugins/select2/select2.full.min.js"></script>

		<!-- P-scroll js-->
		<script src="{{ url('dashboard/assets/dashtemplate') }}/plugins/p-scrollbar/p-scrollbar.js"></script>

		<!--Sticky js -->
		<script src="{{ url('dashboard/assets/dashtemplate') }}/js/sticky.js"></script>


		<!-- Color Theme js -->
		<script src="{{ url('dashboard/assets/dashtemplate') }}/js/themeColors.js"></script>

		<!-- custom js -->
		<script src="{{ url('dashboard/assets/dashtemplate') }}/js/custom.js"></script>

	</body>
</html>
