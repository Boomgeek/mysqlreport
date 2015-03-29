<body>
	<h1 class="page-header"><span class="fa fa-fw fa-flask" aria-hidden="true"></span> Experiment Logs</h1>
	<div class="form-group">
		<div class="form-inline">
			<div class="row">
				<div class="col-md-2">
					<label>Unit:</label>
	     			<select id="unit-Filter" class="form-control"></select>
				</div>
				<div class="col-md-4">
					<label>Type of practice:</label>
	     			<select id="type-Filter" class="form-control"></select>
				</div>
			</div>
		</div>
	</div>
	<div id="status"></div>
	<div class="row" id="panel-bar-chart">
			<div class="col-lg-12">
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h3 class="panel-title"><i class="fa fa-long-arrow-right"></i> Experiment Frequency Of Students Bar Chart </h3>
					</div>
					<div class="panel-body">
						<div id="content-bar-chart"></div>
						<div class="text-right">
							<a href="#">View Details <i class="fa fa-arrow-circle-right"></i></a>
						</div>
					</div>
				</div>
			</div>
	</div>	
</body>
<script src="./source/js/plugins/morris/raphael.min.js"></script>
<script src="./source/js/plugins/morris/morris.min.js"></script>
<script src="./source/js/content_experiment_logs.js"></script>