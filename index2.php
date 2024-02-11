<html>
<body>
	<?php
		if($mysqli){
		}
		else{
			echo "DB error!!!";
		}
	echo "<h2><i>" . $akich_larr['welcome_akich'] . "</i></h2>";
	
	echo '<img src="' . AKICH_ROOT . 'elements/arle.gif"><br>';
	
	echo $akich_larr['in_development'] . "<br>";
	echo $akich_larr['go_left'] . "<br>";
	
	?>
	<hr>
	<h1><i>Latest news</i></h1>
	<hr>
	<h2>2024/01/03 - Project update</h2>
	<p>Much work has been done in the past few months! From actually implementing most of the important logic, to refining the UX, AkiChannel has turned into an almost usable product.</p>
	<p>Now, since this is a graduation project first and foremost, the last remaining steps are documentation and defending it in front of a professors' committee. Unfortunately, there have been delays in the documentation process due to life getting in the way, but that should be resolved now. Additionally, it will be necessary to resolve a bunch of accumulated tech debt.</p>
	<p>The plan is to finish documentation no later than late February, and by March to successfully defend the project and graduate. After that, the codebase will likely be made open-source.</p>
	<br>
	<hr>
	<h2>2023/08/02 - Project development begins</h2>
	This marks the beginning of development of the AkiChannel project. Not much to say yet, but stay tuned for updates.
</body>
</html>