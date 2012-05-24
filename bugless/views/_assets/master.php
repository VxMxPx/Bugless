<?php 
View::Get('_assets/header');
View::Get('_assets/navigation_top');
echo "\n", '<div id="page">', "\n";
View::Get('_assets/navigation_main');
View::Region('main');
echo "\n</div>\n";
View::Get('_assets/footer');