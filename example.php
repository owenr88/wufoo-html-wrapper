<!DOCTYPE html>

<html>

	<head>

		<meta charset="UTF-8">

		<title>Basic Example of the Wufoo HTML Wrapper</title>

		<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet" integrity="sha256-7s5uDGW3AHqw6xtJmNNtr+OBRJUlgkNJEo78P4b0yRw= sha512-nNo+yCHEyn0smMxSswnf/OnX6/KwJuZTlNZBjauKhTK0c+zT+q5JOCx0UFhXQ6rJR9jg6Es8gPuD2uZcYDLqSw==" crossorigin="anonymous">
	
	</head>

	<body>

		<div class="container">

			<div class="row">

				<div class="col-xs-8 col-xs-offset-2">

					<?php

					include_once 'WufooHTMLWrapper.php';

					$wrapper = new WufooHTMLWrapper();

					$wrapper->enableBootstrap();

					$submission_data = $wrapper->sendSubmission();

					echo $wrapper->buildForm( 'z172ip8e07gen9n' );

					echo $wrapper->buildForm( 'test-form' );

					?>
					
				</div>

			</div>

		</div>

	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha256-KXn5puMvxCw+dAYznun+drMdG1IFl3agK0p/pqT9KAo= sha512-2e8qq0ETcfWRI4HJBzQiA3UoyFk6tbNyG+qSaIBZLyW9Xf3sWZHN/lxe9fTh1U45DpPf07yj94KsUHHWe4Yk1A==" crossorigin="anonymous"></script>

	</body>

</html>
