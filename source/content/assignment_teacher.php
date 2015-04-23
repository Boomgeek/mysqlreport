<body>
	<h1 class="page-header"><span class="fa fa-fw fa-edit" aria-hidden="true"></span>Assignment</h1>
	<div class="form-group">
		<div class="form-inline">
			<div class="row">
				<div class="col-md-3">
					<label>Status:</label>
					<select id="status-Filter" class="form-control">
						<option value="0">Unchecked</option>
						<option value="1">Checked</option>
					</select>
				</div>
				<div class="col-md-2">
					<label>Unit:</label>
	     			<select id="unit-Filter" class="form-control"></select>
				</div>
				<div class="col-md-4">
					<label>Type of practice:</label>
	     			<select id="type-Filter" class="form-control"></select>
				</div>
				<div class="col-md-3">
					<label>Article:</label>
	     			<select id="article-Filter" class="form-control"></select>
				</div>
			</div>
		</div>
	</div>
	<div id="assignment-Status"></div>
	<div id="assignment-Content"></div>
</body>
<script src="./source/js/content_assignment_teacher.js"></script>

