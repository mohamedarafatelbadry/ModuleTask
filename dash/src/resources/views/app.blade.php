<!DOCTYPE html>
<html lang="{{ app()->getLocale() == 'ar' ? 'ar' : 'en' }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : '' }}">

<head>
    <meta charset="utf-8" />
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <link rel="apple-touch-icon" sizes="76x76" href="{{ $DASHBOARD_ICON??url('dashboard/assets/img/dash/PNG/blue.png') }}">
    <link rel="icon" type="image/png" href="{{ $DASHBOARD_ICON??url('dashboard/assets/img/dash/PNG/blue.png') }}">
    <title>{{ $title ?? $APP_NAME }}</title>
    <script type="text/javascript" src="{{ url('dashboard/assets/datatables/js/jquery.min.js') }}"></script>
    <!--  Fonts and icons  -->
    <link rel="stylesheet" type="text/css" href="{{ url('dashboard/assets/fonts/cairo/style.css') }}" />
    <!--fontawesome-free-6.2.0 Css Start-->
    <link rel="stylesheet" href="{{ url('dashboard/assets/fonts/fontawesome-free-6.2.0-web/css/all.min.css') }}" />
    <!--fontawesome-free-6.2.0 Css End-->

    <!-- CSS Files -->
    <!-- Bootstrap css -->
		<link href="{{ url('dashboard/assets/dashtemplate/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" id="style"/>

    <!-- Style css -->
		<link href="{{ url('dashboard/assets/dashtemplate/css/style.min.css') }}" rel="stylesheet" />
		<link href="{{ url('dashboard/assets/dashtemplate/css/boxed.css') }}" rel="stylesheet" />
		<link href="{{ url('dashboard/assets/dashtemplate/css/dark.min.css') }}" rel="stylesheet" />
		<link href="{{ url('dashboard/assets/dashtemplate/css/skin-modes.css') }}" rel="stylesheet" />
		<link href="{{ url('dashboard/assets/dashtemplate/css/transparent-style.css') }}" rel="stylesheet">

		<!-- Animate css -->
		<link href="{{ url('dashboard/assets/dashtemplate/css/animated.css') }}" rel="stylesheet" />

		<!---Icons css-->
		<link href="{{ url('dashboard/assets/dashtemplate/css/icons.css') }}" rel="stylesheet" />

    @if (!empty($fields))
        <!-- Video.js base CSS -->
        <link href="{{ url('dashboard/assets/video.js-7.11.4/dist/video-js.min.css') }}" rel="stylesheet">

        <!-- Video Js Themes -->
        <link href="{{ url('dashboard/assets/video.js-7.11.4/theme/city.css') }}" rel="stylesheet">
        <link href="{{ url('dashboard/assets/video.js-7.11.4/theme/sea.css') }}" rel="stylesheet">
        <link href="{{ url('dashboard/assets/video.js-7.11.4/theme/fantasy.css') }}" rel="stylesheet">
        <link href="{{ url('dashboard/assets/video.js-7.11.4/theme/forest.css') }}" rel="stylesheet">

        <!--select2 Start-->
        <link href="{{ url('dashboard/assets/select2-4-1-0/css/select2.min.css') }}" rel="stylesheet" />

        <script src="{{ url('dashboard/assets/select2-4-1-0/js/select2.min.js') }}"></script>

        @if (app()->getLocale() == 'ar')
            <script src="{{ url('dashboard/assets/select2-4-1-0/js/i18n/ar.js') }}"></script>
            <link rel="stylesheet" type="text/css"
                href="{{ url('dashboard/assets/select2-4-1-0/css/select2-bootstrap-5-theme.rtl.min.css') }}">
        @else
            @if (app()->getLocale() != 'en')
                <script src="{{ url('dashboard/assets/select2-4-1-0/js/i18n/' . app()->getLocale() . '.js') }}"></script>
            @endif

            <link rel="stylesheet" type="text/css"
                href="{{ url('dashboard/assets/select2-4-1-0/css/select2-bootstrap-5-theme.min.css') }}">
        @endif


        <!--select2 End-->
        <script src="{{ url('dashboard/assets/video.js-7.11.4/dist/video.min.js') }}"></script>
        <!-- Video Js End -->
        
        {{-- <script src="{{ url('dashboard/assets/ckeditor/ckeditor.js') }}" {{request('edit_with_inline')?'defer':''}}></script> --}}
        <script src="https://cdn.ckeditor.com/ckeditor5/35.1.0/super-build/ckeditor.js" {{request('edit_with_inline')?'defer':''}} ></script>

        {{-- <script src="https://cdn.ckeditor.com/ckeditor5/35.1.0/super-build/ckeditor.js"></script> --}}


        <link rel="stylesheet" href="{{ url('dashboard/assets/flatpicker/dist/flatpickr.min.css') }}">
        {{--  If Call Ajax Request Start  --}}
        <script type="text/javascript" src="{{ url('dashboard/assets/flatpicker/dist/flatpickr.min.js') }}" defer></script>
       @if(app()->getLocale() != 'en')
       <script type="text/javascript" src="{{ url('dashboard/assets/flatpicker/dist/l10n/' . app()->getLocale() . '.js') }}"
        defer></script>
        @endif
        {{--  If Call Ajax Request End  --}}

        {{--  If not Call Ajax Request Start  --}}
        <script type="text/javascript" src="{{ url('dashboard/assets/flatpicker/dist/flatpickr.min.js') }}"></script>
        @if(app()->getLocale() != 'en')
        <script type="text/javascript" src="{{ url('dashboard/assets/flatpicker/dist/l10n/' . app()->getLocale() . '.js') }}">
        </script>
        @endif
        {{--  If not Call Ajax Request End  --}}


        <link rel="stylesheet" type="text/css" href="{{ url('dashboard/assets/dropzone/min/dropzone.min.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ url('dashboard/assets/dropzone/min/basic.css') }}">
        <script src="{{ url('dashboard/assets/dropzone/min/dropzone.min.js') }}" type="text/javascript"></script>
    @endif


    <!-- system message and notifications -->
    @push('js')
        <script type="text/javascript" src="{{ url('dashboard/assets/toastr/toastr.min.js') }}"></script>
        <link rel="stylesheet" type="text/css" href="{{ url('dashboard/assets/toastr/toastr.min.css') }}">
        <script type="text/javascript">
            $(document).ready(function() {
                toastr.options = {
                    "closeButton": false,
                    "debug": false,
                    "newestOnTop": false,
                    "progressBar": false,
                    "positionClass": "toast-bottom-right",
                    "preventDuplicates": false,
                    "onclick": null,
                    "showDuration": "300",
                    "hideDuration": "5000",
                    "timeOut": "5000",
                    // "extendedTimeOut": "1000",
                    // "showEasing": "swing",
                    // "hideEasing": "linear",
                    // "showMethod": "fadeIn",
                    // "hideMethod": "fadeOut"
                };
                @if (session()->has('success'))
                    toastr.success("{{ session('success') }}");
                @endif
                @if (session()->has('error'))
                    toastr.error("{{ session('error') }}");
                @endif
                @if (session()->has('danger'))
                    toastr.error("{{ session('danger') }}");
                @endif
                @if (session()->has('warning'))
                    toastr.warning("{{ session('warning') }}");
                @endif
                @if (session()->has('info'))
                    toastr.info("{{ session('info') }}");
                @endif
            });
        </script>
    @endpush
    <!-- system message and notifications End-->
    <!--fontawesome-free-6.2.0 -->
    <!--fontawesome-free-6.2.0 Js Start-->
    <script src="{{ url('dashboard/assets/fonts/fontawesome-free-6.2.0-web/js/all.min.js') }}"></script>
    <!--fontawesome-free-6.2.0 Js End-->

</head>
<body class="app sidebar-mini {{ session('DARK_MODE',config('dash.DARK_MODE')) == 'on'?'dark-mode':'' }}
 {{ app()->getLocale() == 'ar' ? 'rtl' : '' }} {{ session('sidenav_toggled','close') == 'open'?'sidenav-toggled':'' }}">

    <!---Global-loader-->
		<div id="global-loader" >
			<img src="{{ url('dashboard/assets/dashtemplate') }}/images/svgs/loader.svg" alt="loader">
		</div>
		<!---End Global-loader-->

		<div class="page">
			<div class="page-main">

				<!--app header-->
                @include('dash::header')
				<!--/app header-->

				<!--app-sidebar-->
                @include('dash::menu')
				<!--app-sidebar closed-->

				<div class="app-content main-content">
					<div class="side-app main-container">

						<!--Page header-->
						<div class="page-header m-1 d-xl-flex d-block">
                            @include('dash::breadcrumbs')
						</div>
						<!--End Page header-->


						<!--Content-->
						{{--  <div class="row">
						</div>  --}}
                            @yield('content')
                        <!--End Content-->

					</div>
				</div><!-- end app-content-->
			</div>

			<!--Footer-->
            @include('dash::footer')
			<!-- End Footer-->

			<!--sidebar-right-->
            @include('dash::notifications')
			<!--/sidebar-right-->

		</div>


	<!-- Back to top -->
		<a href="#top" id="back-to-top"><span class="feather feather-chevrons-up"></span></a>

		<!-- Jquery js-->
		<script src="{{ url('dashboard/assets/dashtemplate') }}/plugins/jquery/jquery.min.js"></script>

		<!--Moment js-->
		<script src="{{ url('dashboard/assets/dashtemplate') }}/plugins/moment/min/moment.min.js"></script>

		<!-- Bootstrap js-->
		<script src="{{ url('dashboard/assets/dashtemplate') }}/plugins/bootstrap/js/popper.min.js"></script>
		<script src="{{ url('dashboard/assets/dashtemplate') }}/plugins/bootstrap/js/bootstrap.min.js"></script>


		<!--Sidemenu js-->
		<script src="{{ url('dashboard/assets/dashtemplate') }}/plugins/sidemenu/sidemenu.js"></script>

		<!-- P-scroll js-->
		<script src="{{ url('dashboard/assets/dashtemplate') }}/plugins/p-scrollbar/p-scrollbar.js"></script>
		<script src="{{ url('dashboard/assets/dashtemplate') }}/plugins/p-scrollbar/p-scroll1.js"></script>

		<!--Sidebar js-->
		<script src="{{ url('dashboard/assets/dashtemplate') }}/plugins/sidebar/sidebar.js"></script>

		<!-- Select2 js -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

        {{-- <script src="{{ url('dashboard/assets/select2-4-1-0/js/select2.min.js') }}" defer></script> --}}

		<!-- INTERNAL Vertical-scroll js-->
		<script src="{{ url('dashboard/assets/dashtemplate') }}/plugins/vertical-scroll/jquery.bootstrap.newsbox.js"></script>
		<script src="{{ url('dashboard/assets/dashtemplate') }}/plugins/vertical-scroll/vertical-scroll.js"></script>


		<!--Sticky js -->
		<script src="{{ url('dashboard/assets/dashtemplate') }}/js/sticky.js"></script>

	  		<!-- Color Theme js -->
		<script src="{{ url('dashboard/assets/dashtemplate') }}/js/themeColors.js"></script>

		<!-- Custom js-->
		<script src="{{ url('dashboard/assets/dashtemplate') }}/js/custom.js"></script>
        <script>
            @if (!empty(request('relationField')))
                @foreach (request('relationField') as $rk => $rv)
                    $('.{{ $rk }}').val('{{ $rv }}').trigger('change');
                @endforeach
            @endif

        </script>

        <style type="text/css">
            @if(app()->getLocale() == 'ar')
            .dt-buttons {
                display: none;
            }
            @endif
            @if(session('DARK_MODE',config('dash.DARK_MODE'))  == 'on')
            .select2-search__field { background-color: #25274a; }
            .select2-search__field input { background-color: #25274a; }
            .select2-results { background-color: #25274a; }

            .select2-container--bootstrap-5 .select2-selection{
                background-color: #25274a !important;
                color:#fff;
            }

            .select2-container--bootstrap-5 .select2-selection:hover{
                background-color: #25274a !important;
                color:#fff;
            }
            @endif
            
            .select2-search .select2-search--inline {
                display:block;
            }
        </style>
    @stack('js')

    <script>
        $(document).ready(function(){
            {{--  This belong to fix the select2 when active search input  --}}
            $.fn.modal.Constructor.prototype.enforceFocus = function () {
                var that = this;
                $(document).on('focusin.modal', function (e) {
                   if ($(e.target).hasClass('select2-input')) {
                      return true;
                   }

                   //console.log('focusin worked');
                   if (that.$element[0] !== e.target && !that.$element.has(e.target).length) {
                       that.$element.focus();
                    }
                });
            };
        });
    </script>
	</body>
</html>
