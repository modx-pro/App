<meta charset="utf-8">
<title>{$_modx->resource.pagetitle} / {'site_name' | config}</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
{var $assets = ('assets_url' | config) ~ 'components/app/'}

<link rel="apple-touch-icon" sizes="57x57" href="{$assets}/img/favicon/apple-icon-57x57.png">
<link rel="apple-touch-icon" sizes="60x60" href="{$assets}/img/favicon/apple-icon-60x60.png">
<link rel="apple-touch-icon" sizes="72x72" href="{$assets}/img/favicon/apple-icon-72x72.png">
<link rel="apple-touch-icon" sizes="76x76" href="{$assets}/img/favicon/apple-icon-76x76.png">
<link rel="apple-touch-icon" sizes="114x114" href="{$assets}/img/favicon/apple-icon-114x114.png">
<link rel="apple-touch-icon" sizes="120x120" href="{$assets}/img/favicon/apple-icon-120x120.png">
<link rel="apple-touch-icon" sizes="144x144" href="{$assets}/img/favicon/apple-icon-144x144.png">
<link rel="apple-touch-icon" sizes="152x152" href="{$assets}/img/favicon/apple-icon-152x152.png">
<link rel="apple-touch-icon" sizes="180x180" href="{$assets}/img/favicon/apple-icon-180x180.png">
<link rel="icon" type="image/png" sizes="192x192"  href="{$assets}/img/favicon/android-icon-192x192.png">
<link rel="icon" type="image/png" sizes="32x32" href="{$assets}/img/favicon/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="96x96" href="{$assets}/img/favicon/favicon-96x96.png">
<link rel="icon" type="image/png" sizes="16x16" href="{$assets}/img/favicon/favicon-16x16.png">
<link rel="manifest" href="{$assets}/img/favicon/manifest.json">
<meta name="msapplication-TileColor" content="#ffffff">
<meta name="msapplication-TileImage" content="{$assets}/img/favicon/ms-icon-144x144.png">
<meta name="theme-color" content="#ffffff">

{('<meta name="csrf-token" content="' ~ $.session['csrf-token'] ~ '">') | htmlToHead}
{('<meta name="assets-version" content="' ~ $.assets_version ~ '">') | htmlToHead}
{($assets ~ 'css/web/main.css?v=' ~ $.assets_version) | cssToHead}
{($assets ~ 'js/web/lib/require.min.js?v=' ~ $.assets_version) | jsToHead : false}
{($assets ~ 'js/web/config.js?v=' ~ $.assets_version) | jsToHead : false}
{'<script type="text/javascript">requirejs(["app", "app/index"]);</script>' | htmlToBottom }