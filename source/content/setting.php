<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Setting</title>
</head>
<body>
	<!--#page-header-->
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header">Setting Experiment</h1>	
		</div>
	</div>
	<!--#page-header-->
	<div class="row">
		<div class="col-lg-12 content">
			<div class="form-group">
				<label>Quantity Unit</label>
				<input type="number" class="form-control" name="quantity" value="1" min="1">
				<p class="help-block">Please fill it</p>
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
						<tbody>
							<tr>
								<td>1</td>
								<td><input type="text" class="form-control" placeholder="Enter Unit Name"></td>
								<td><input type="number" class="form-control" name="max_practice_while" value="1" min="1"></td>
								<td><input type="number" class="form-control" name="max_practice_affer" value="1" min="1"></td>
							</tr>
							<tr>
								<td>2</td>
								<td><input type="text" class="form-control" placeholder="Enter Unit Name"></td>
								<td><input type="number" class="form-control" name="max_practice_while" value="1" min="1"></td>
								<td><input type="number" class="form-control" name="max_practice_affer" value="1" min="1"></td>
							</tr>
						</tbody>
				</table>
			</div>
			<button type="button" class="btn btn-primary btn-lg center-block">Create Unit</button>
		</div>
	</div>

</body>
</html>