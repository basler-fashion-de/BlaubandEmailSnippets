{extends file="parent:backend/blauband_common/header.tpl"}

{block name="header"}
    {$smarty.block.parent}

    <script type="text/javascript" src="{link file="backend/_public/src/js/snippets.js"}"></script>
    <link rel="stylesheet" href="{link file="backend/_public/src/css/snippets.css"}">

    <script type="application/javascript">
      var shouldDelete = '{s namespace="blauband/mail" name="shouldDelete"}{/s}';
    </script>
{/block}