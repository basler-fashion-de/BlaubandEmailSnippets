{extends file="parent:backend/blauband_email/index.tpl"}

{block name="header"}
    {$smarty.block.parent}
    <script type="text/javascript" src="{link file="backend/_public/src/js/snippets.js"}"></script>
    <link rel="stylesheet" href="{link file="backend/_public/src/css/snippets.css"}">
    <script>
      var saveSuccessSnippet = '{s namespace="blauband/mail" name="saveSuccess"}{/s}';
    </script>
{/block}

{block name="main-content"}
    <div id="blauband-mail">

        <div class="alerts">
            <div class="ui-state-highlight ui-corner-all">
                <span class="ui-icon ui-icon-info"></span>
                <div class="content"></div>
            </div>
            <div class="ui-state-error ui-corner-all">
                <span class="ui-icon ui-icon-alert"></span>
                <div class="content">{s namespace="blauband/mail" name="saveError"}{/s}</div>
            </div>
        </div>

        <button id="save-button" class="blue" data-url="{url action="save"}">
            {s namespace="blauband/mail" name="save"}Speichern{/s}
        </button>

        <input type="hidden" name="snippetName" id="snippetName" value="{$snippetName}">
        {foreach $snippets as $shopId => $snippet}
            <h4>{$snippet.shopName} - {$snippet.shopLocale}</h4>
            <textarea class="snippetTextarea" id="snippet-{$shopId}" name="snippet-{$shopId}">
                    {$snippet.value}
                </textarea>
        {/foreach}
    </div>
{/block}
