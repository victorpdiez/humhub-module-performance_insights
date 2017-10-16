<div id="faker-test-outer">
	<?php

	echo $this->render('index_content', [
		'isDeleteSpaceButtonHidden'=>$isDeleteSpaceButtonHidden,
		'isDeleteUserButtonHidden'=>$isDeleteUserButtonHidden
	]);
	?>
</div>