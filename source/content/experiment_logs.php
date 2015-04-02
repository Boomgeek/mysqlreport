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
					<h3 class="panel-title"><i class="fa fa-long-arrow-right"></i> Experiment times</h3>
				</div>
				<div class="panel-body">
					<div id="content-bar-chart"></div>
					<div class="text-right">
						<a href="javascript:void(0);" data-toggle="modal" data-target="#experimentDetailModal" id="viewDetails-btn">View Details <i class="fa fa-arrow-circle-right"></i></a>
					</div>
				</div>
			</div>
		</div>
	</div>	

	<!--start Model-->
    <div class="modal fade" id="experimentDetailModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <div>
                    	<h4 class="modal-title" id="experimentDetailTitle"></h4>
                    </div>
                </div>
                <div class="modal-body">
                    <div id="experimentDetailStatus"></div>
                    <div class="form-group">
						<div class="form-inline">
							<div class="row">
								<div class="col-md-2">
									<label>Article:</label>
					     			<select id="article-Filter" class="form-control"></select>
								</div>
								<div class="col-md-6">
									<label>Sort By:</label>
					     			<select id="sort-Filter" class="form-control">
					     				<option value="1">SID</option>
					     				<option value="2">Times</option>
					     			</select>
								</div>
							</div>
						</div>
					</div>
					<div id="experimentDetailContent"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!--end model-->

</body>
<script src="./source/js/plugins/morris/raphael.min.js"></script>
<script src="./source/js/plugins/morris/morris.min.js"></script>
<script src="./source/js/content_experiment_logs.js"></script>