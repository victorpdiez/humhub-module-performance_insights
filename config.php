<?php

use humhub\modules\admin\widgets\AdminMenu;

return [
'id' => 'performance_insights',
'class' => 'humhub\modules\performance_insights\Module',
'namespace' => 'humhub\modules\performance_insights',
'events' => [
['class' => AdminMenu::className(), 'event' => AdminMenu::EVENT_INIT, 'callback' => ['humhub\modules\performance_insights\Events', 'onAdminMenuInit']]
],
];

?>