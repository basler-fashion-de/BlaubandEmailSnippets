$(function () {
  var textarea = getTextarea()
  registerSnippetEvents()
})

function registerSnippetEvents () {
  registerSaveSnippetEvent()
  registerDragAndDrop()
}

function insertInTextarea (value) {
  var textarea = getTextarea()
  value = value.replace(new RegExp('\\\\n', 'gm'), '\n')

  if (document.selection) {
    //IE
    textarea.focus()
    var sel = document.selection.createRange()
    sel.text = value
    textarea.focus()
  } else if (textarea.selectionStart || textarea.selectionStart == '0') {
    var startPos = textarea.selectionStart
    var endPos = textarea.selectionEnd
    var scrollTop = textarea.scrollTop
    textarea.value = textarea.value.substring(0, startPos) + value + textarea.value.substring(endPos, textarea.value.length)
    textarea.focus()
    textarea.selectionStart = startPos + value.length
    textarea.selectionEnd = startPos + value.length
    textarea.scrollTop = scrollTop
  } else {
    textarea.value += value
    textarea.focus()
  }
}

function getTextarea () {
  var htmlPlain = $('#selectedTab').val()
  return document.getElementById(htmlPlain + 'MailContent')
}

function openSnippets (snippetName) {
  openNewIframe('Snippets', 'BlaubandEmailSnippets', 'index', {'snippetName': snippetName})
}

function registerSaveSnippetEvent () {
  $(plugin_selector + ' #save-button').on('click', function () {
    var url = $(this).data('url')
    var params = $('input, textarea, select').serialize()

    $.ajax({
      type: 'post',
      url: url,
      data: params,
      success: function (response) {
        hideInfoPanel()
        hideErrorPanel()

        if (response.success) {
          alert(saveSuccessSnippet)

          parent[parent.length-2].location.reload();
          postMessageApi.window.destroy();
        } else {
          showErrorPanel(response.message)
        }
      }
    })
  })
}

function registerDragAndDrop () {
  $('.snippetLanguage').off('click.blauband')
  $('.snippetLanguage').on('click.blauband', function () {
    insertInTextarea($(this).find('.snippetLanguageValue').text())
  })

  $('.snippetEdit').off('click.blauband')
  $('.snippetEdit').on('click.blauband', function () {
    openSnippets($(this).data('snippetname'))
  })
}