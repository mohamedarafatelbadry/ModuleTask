<script src="{{ url('dashboard/assets/akit/static/js/app.js') }}"></script>
<!--   Core JS Files   -->
{{--  <script src="{{ url('dashboard/assets/js/core/popper.min.js') }}"></script>
<script src="{{ url('dashboard/assets/js/core/bootstrap.min.js') }}"></script>
<script src="{{ url('dashboard/assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
<script src="{{ url('dashboard/assets/js/plugins/smooth-scrollbar.min.js') }}"></script>  --}}
<script>
    @if (!empty(request('relationField')))
        @foreach (request('relationField') as $rk => $rv)
            $('.{{ $rk }}').val('{{ $rv }}').trigger('change');
        @endforeach
    @endif
</script>

{{--  <script src="{{ url('dashboard/assets/js/dashboard_functions.js') }}"></script>  --}}
<script src="{{ url('dashboard/assets/js/jquery-3.5.1.js') }}"></script>

@if (app()->getLocale() == 'ar')
    <style type="text/css">
        .dt-buttons {
            display: none;
        }
    </style>
@endif

<script src="{{ url('dashboard/assets/select2-4-1-0/js/select2.min.js') }}" defer></script>

	<script>
		document.addEventListener("DOMContentLoaded", function() {
			var ctx = document.getElementById("chartjs-dashboard-line").getContext("2d");
			var gradientLight = ctx.createLinearGradient(0, 0, 0, 225);
			gradientLight.addColorStop(0, "rgba(215, 227, 244, 1)");
			gradientLight.addColorStop(1, "rgba(215, 227, 244, 0)");
			var gradientDark = ctx.createLinearGradient(0, 0, 0, 225);
			gradientDark.addColorStop(0, "rgba(51, 66, 84, 1)");
			gradientDark.addColorStop(1, "rgba(51, 66, 84, 0)");
			// Line chart
			new Chart(document.getElementById("chartjs-dashboard-line"), {
				type: "line",
				data: {
					labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
					datasets: [{
						label: "Sales ($)",
						fill: true,
						backgroundColor: window.theme.id === "light" ? gradientLight : gradientDark,
						borderColor: window.theme.primary,
						data: [
							2115,
							1562,
							1584,
							1892,
							1587,
							1923,
							2566,
							2448,
							2805,
							3438,
							2917,
							3327
						]
					}]
				},
				options: {
					maintainAspectRatio: false,
					legend: {
						display: false
					},
					tooltips: {
						intersect: false
					},
					hover: {
						intersect: true
					},
					plugins: {
						filler: {
							propagate: false
						}
					},
					scales: {
						xAxes: [{
							reverse: true,
							gridLines: {
								color: "rgba(0,0,0,0.0)"
							}
						}],
						yAxes: [{
							ticks: {
								stepSize: 1000
							},
							display: true,
							borderDash: [3, 3],
							gridLines: {
								color: "rgba(0,0,0,0.0)",
								fontColor: "#fff"
							}
						}]
					}
				}
			});
		});
	</script>
	<script>
		document.addEventListener("DOMContentLoaded", function() {
			// Pie chart
			new Chart(document.getElementById("chartjs-dashboard-pie"), {
				type: "pie",
				data: {
					labels: ["Chrome", "Firefox", "IE", "Other"],
					datasets: [{
						data: [4306, 3801, 1689, 3251],
						backgroundColor: [
							window.theme.primary,
							window.theme.warning,
							window.theme.danger,
							"#E8EAED"
						],
						borderWidth: 5,
						borderColor: window.theme.white
					}]
				},
				options: {
					responsive: !window.MSInputMethodContext,
					maintainAspectRatio: false,
					legend: {
						display: false
					},
					cutoutPercentage: 70
				}
			});
		});
	</script>
	<script>
		document.addEventListener("DOMContentLoaded", function() {
			// Bar chart
			new Chart(document.getElementById("chartjs-dashboard-bar"), {
				type: "bar",
				data: {
					labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
					datasets: [{
						label: "This year",
						backgroundColor: window.theme.primary,
						borderColor: window.theme.primary,
						hoverBackgroundColor: window.theme.primary,
						hoverBorderColor: window.theme.primary,
						data: [54, 67, 41, 55, 62, 45, 55, 73, 60, 76, 48, 79],
						barPercentage: .75,
						categoryPercentage: .5
					}]
				},
				options: {
					maintainAspectRatio: false,
					legend: {
						display: false
					},
					scales: {
						yAxes: [{
							gridLines: {
								display: false
							},
							stacked: false,
							ticks: {
								stepSize: 20
							}
						}],
						xAxes: [{
							stacked: false,
							gridLines: {
								color: "transparent"
							}
						}]
					}
				}
			});
		});
	</script>
	<script>
		document.addEventListener("DOMContentLoaded", function() {
			var markers = [{
					coords: [31.230391, 121.473701],
					name: "Shanghai"
				},
				{
					coords: [28.704060, 77.102493],
					name: "Delhi"
				},
				{
					coords: [6.524379, 3.379206],
					name: "Lagos"
				},
				{
					coords: [35.689487, 139.691711],
					name: "Tokyo"
				},
				{
					coords: [23.129110, 113.264381],
					name: "Guangzhou"
				},
				{
					coords: [40.7127837, -74.0059413],
					name: "New York"
				},
				{
					coords: [34.052235, -118.243683],
					name: "Los Angeles"
				},
				{
					coords: [41.878113, -87.629799],
					name: "Chicago"
				},
				{
					coords: [51.507351, -0.127758],
					name: "London"
				},
				{
					coords: [40.416775, -3.703790],
					name: "Madrid "
				}
			];
			var map = new jsVectorMap({
				map: "world",
				selector: "#world_map",
				zoomButtons: true,
				markers: markers,
				markerStyle: {
					initial: {
						r: 9,
						stroke: window.theme.white,
						strokeWidth: 7,
						stokeOpacity: .4,
						fill: window.theme.primary
					},
					hover: {
						fill: window.theme.primary,
						stroke: window.theme.primary
					}
				},
				regionStyle: {
					initial: {
						fill: window.theme["gray-200"]
					}
				},
				zoomOnScroll: false
			});
			window.addEventListener("resize", () => {
				map.updateSize();
			});
			setTimeout(function() {
				map.updateSize();
			}, 250);
		});
	</script>
	<script>
		document.addEventListener("DOMContentLoaded", function() {
			var date = new Date(Date.now() - 5 * 24 * 60 * 60 * 1000);
			var defaultDate = date.getUTCFullYear() + "-" + (date.getUTCMonth() + 1) + "-" + date.getUTCDate();
			document.getElementById("datetimepicker-dashboard").flatpickr({
				inline: true,
				prevArrow: "<span class=\"fas fa-chevron-left\" title=\"Previous month\"></span>",
				nextArrow: "<span class=\"fas fa-chevron-right\" title=\"Next month\"></span>",
				defaultDate: defaultDate
			});
		});
	</script>

    {{--  Settings Start  --}}
    <div class="settings js-settings">
        <div class="settings-toggle js-settings-toggle"> <i class="align-middle" data-feather="sliders"></i> </div>
        <div class="settings-panel">
            <div class="settings-content">
                <div class="settings-title d-flex align-items-center">
                    <button type="button" class="btn-close float-right js-settings-toggle" aria-label="Close"></button>
                    <h4 class="mb-0 ms-2 d-inline-block">
                        Theme Settings
                    </h4>

                </div>

                <div class="settings-body">
                    <div class="alert alert-primary" role="alert">
                        <div class="alert-message"> <strong>
                            Hey there!</strong> Choose the color scheme, sidebar and layout
                            that best fits your project needs.
                        </div>
                    </div>
                    <div class="mb-3">
                        <span class="d-block fw-bold">Color scheme</span> <span class="d-block text-muted mb-2">The perfect
                            color mode for your app.</span>
                        <div class="row g-0 text-center mx-n1 mb-2">

                            <div class="col">

                                <label class="mx-1 d-block mb-1">
                                    <input class="settings-scheme-label" type="radio" name="theme" value="default" />
                                    <div class="settings-scheme">

                                        <div class="settings-scheme-theme settings-scheme-theme-default"></div>

                                    </div>

                                </label>
                                Default
                            </div>

                            <div class="col">

                                <label class="mx-1 d-block mb-1">
                                    <input class="settings-scheme-label" type="radio" name="theme" value="colored" />
                                    <div class="settings-scheme">

                                        <div class="settings-scheme-theme settings-scheme-theme-colored"></div>

                                    </div>

                                </label>
                                Colored
                            </div>
                        </div>
                        <div class="row g-0 text-center mx-n1">
                            <div class="col">

                                <label class="mx-1 d-block mb-1">
                                    <input class="settings-scheme-label" type="radio" name="theme" value="dark" />
                                    <div class="settings-scheme">
                                        <div class="settings-scheme-theme settings-scheme-theme-dark"></div>
                                    </div>
                                </label>
                                Dark
                            </div>
                            <div class="col">
                                <label class="mx-1 d-block mb-1">
                                    <input class="settings-scheme-label" type="radio" name="theme" value="light" />
                                    <div class="settings-scheme">
                                        <div class="settings-scheme-theme settings-scheme-theme-light"></div>
                                    </div>
                                </label>
                                Light
                            </div>
                        </div>

                    </div>

                    <hr />

                    <div class="mb-3">
                        <span class="d-block fw-bold">Sidebar layout</span> <span class="d-block text-muted mb-2">Change the
                            layout of the sidebar.</span>
                        <div>

                            <label>
                                <input class="settings-button-label" type="radio" name="sidebarLayout" value="default" />
                                <div class="settings-button"> Default </div>

                            </label>

                            <label>
                                <input class="settings-button-label" type="radio" name="sidebarLayout" value="compact" />
                                <div class="settings-button"> Compact </div>

                            </label>

                        </div>

                    </div>

                    <hr />

                    <div class="mb-3">
                        <span class="d-block fw-bold">Sidebar position</span> <span class="d-block text-muted mb-2">Toggle
                            the position of the sidebar.</span>
                        <div>

                            <label>
                                <input class="settings-button-label" type="radio" name="sidebarPosition" value="left" />
                                <div class="settings-button"> Left </div>

                            </label>

                            <label>
                                <input class="settings-button-label" type="radio" name="sidebarPosition" value="right" />
                                <div class="settings-button"> Right </div>

                            </label>

                        </div>

                    </div>

                    <hr />

                    <div class="mb-3">
                        <span class="d-block fw-bold">Layout</span> <span class="d-block text-muted mb-2">Toggle container
                            layout system.</span>
                        <div>

                            <label>
                                <input class="settings-button-label" type="radio" name="layout" value="fluid" />
                                <div class="settings-button"> Fluid </div>

                            </label>

                            <label>
                                <input class="settings-button-label" type="radio" name="layout" value="boxed" />
                                <div class="settings-button"> Boxed </div>

                            </label>

                        </div>

                    </div>

                </div>

                <div class="settings-footer">

                </div>
            </div>
        </div>
    </div>

    {{--  Settings End  --}}
