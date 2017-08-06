<!DOCTYPE html>
<html lang="de">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Open-Access-Quiz</title>
	<?php if (isset($model->showShareButtons)) { ?>
		<link rel="stylesheet" href="assets/shariff.min.css">
	<?php } ?>
	<link rel="stylesheet" href="assets/style.css">
	<link rel="shortcut icon" href="assets/favicon.png" type="image/png">
</head>
<body class="quiz">
	<div class="quiz_warning">Bitte erlauben Sie JavaScript und Cookies für diese Website.</div>

	<header class="quiz_header" role="banner">
		<a href="<?=$this->urlWithoutParams()?>">
			<img class="quiz_logo" src="assets/oa.svg" alt="Open Access">
			<div class="quiz_heading">Quiz</div>
		</a>
	</header>

	<div class="quiz_main">
		<?php require "templates/$template.php"; ?>
	</div>

	<script src="assets/jquery.min.js"></script>
	<script src="assets/quiz.js"></script>
	<?php if (isset($model->showShareButtons)) { ?>
		<script src="assets/shariff.min.js"></script>
	<?php } ?>
</body>
</html>
