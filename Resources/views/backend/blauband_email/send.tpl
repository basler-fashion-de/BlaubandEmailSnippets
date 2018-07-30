{extends file="parent:backend/blauband_email/send.tpl"}




{block name="mailContentWrapperAdditional"}
    {$smarty.block.parent}

    <div id="mailCustomSnippets">
        <label>{s namespace="blauband/mail" name="mailSnippets"}{/s}</label>
        <select id="customSnippets" name="customSnippets">
            {foreach $customSnippets as $id => $data}
                <option value="{$id}">{$data.name}</option>
            {/foreach}
        </select>
        <div class="customSnippetsDataWrapper">
            {foreach $customSnippets as $id => $data}
                <div class="customSnippetsData" id="customSnippetsData{$id}">
                    {foreach $data.data as $snippetName => $snippet}
                        <div class="snippetRow">
                            <div class="snippetName">{$snippetName}</div>
                            <div class="snippet">{$snippet}</div>
                        </div>
                    {/foreach}
                </div>
            {/foreach}
        </div>
    </div>
{/block}