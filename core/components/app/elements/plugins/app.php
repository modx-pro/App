<?php
/** @var modX $modx */
/** @var array $scriptProperties */
/** @var App $App */
switch ($modx->event->name) {
    case 'OnMODXInit':
        if ($App = $modx->getService('App', 'App', MODX_CORE_PATH . 'components/app/model/')) {
            $App->initialize();
        }
        break;
    default:
        if ($App = $modx->getService('App')) {
            $App->handleEvent($modx->event, $scriptProperties);
        }
}