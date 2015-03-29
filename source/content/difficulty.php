<body>
	<h1 class="page-header"><span class="fa fa-fw fa-table" aria-hidden="true"></span> Item Difficulty</h1>
	<div class="form-group">
		<div class="form-inline">
			<div class="row">
				<div class="col-md-2">
					<label>Unit:</label>
	     			<select id="unit-Filter" class="form-control"></select>
				</div>
			</div>
		</div>
	</div>
	<!--
	<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h3 class="panel-title"><i class="fa fa-long-arrow-right"></i> Bar Graph Example</h3>
					</div>
					<div class="panel-body">
						<div id="morris-bar-chart"></div>
						<div class="text-right">
							<a href="#">View Details <i class="fa fa-arrow-circle-right"></i></a>
						</div>
					</div>
				</div>
			</div>
	</div>
	-->
	<div class="row">
		<div class="col-lg-6">
			<span class="label label-danger">Very Hard</span>
			<span class="label label-warning">Hard</span>
			<span class="label label-success">Good</span>
			<span class="label label-info">Easy</span>
			<span class="label label-primary">Very Easy</span>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-12">
			<div id="Difficulty-Content"></div>
		</div>
	</div>
</body>
<script src="./source/js/plugins/morris/raphael.min.js"></script>
<script src="./source/js/plugins/morris/morris.min.js"></script>
<script src="./source/js/content_difficulty.js"></script>