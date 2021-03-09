<!DOCTYPE html>
<html>
<head>
	<title>tityle</title>
</head>
<style>
html{
	box-sizing:border-box
	}
*,*:before,*:after{
		box-sizing:inherit
	}
html{
	-ms-text-size-adjust:100%;
	-webkit-text-size-adjust:100%
	}
body{
	margin:0
}
<body>

<?php $this->load->view($_view); ?>
</body>
</html>