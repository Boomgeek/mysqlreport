<body>
	<!--#page-header-->
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header"><span class="glyphicon glyphicon-cog" aria-hidden="true"></span> Setting Experiment</h1>	
		</div>
	</div>
	<!--#page-header-->
	<div class="row">
		<div class="col-lg-12 content">
			<div class="form-inline">
				<label>Quantity Unit</label>
				<input type="number" class="form-control" id="unitQuantity" value="1" min="1">
			</div>
			<div class="table-responsive">
				<table class="table table-condensed">
						<thead>
							<tr>
								<th>#</th>
								<th>Unit Name</th>
								<th>Max while practice</th>
								<th>Max after practice</th>
							</tr>
						</thead>
						<tbody id="unitForm">
							<tr>
								<td id="unit1">1</td>
								<td><input type="text" class="form-control"  id="uname1" placeholder="Enter Unit Name"></td>
								<td><input type="number" class="form-control" id="while1" value="0" min="0"></td>
								<td><input type="number" class="form-control" id="after1" value="0" min="0"></td>
							</tr>
							
						</tbody>
				</table>
			</div>
			<button type="button" id="saveSetting" class="btn btn-primary btn-lg center-block">Save</button>
		</div>
	</div>

</body>
<script src="./source/js/content_setting.js"></script>
