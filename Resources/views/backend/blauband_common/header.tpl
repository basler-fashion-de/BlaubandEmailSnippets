{extends file="parent:backend/blauband_common/header.tpl"}

{block name="header"}
    {$smarty.block.parent}

    <script type="text/javascript" src="{link file="backend/base/frame/postmessage-api.js"}"></script>

    <script type="text/javascript" src="{link file="backend/_public/src/js/snippets.js"}"></script>
    <link rel="stylesheet" href="{link file="backend/_public/src/css/snippets.css"}">
{/block}