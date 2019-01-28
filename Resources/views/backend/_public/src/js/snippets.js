var blaubandTempText = '';

$(function () {
  var textarea = getTextarea()
  registerSnippetEvents()
})

function registerSnippetEvents () {
  registerSaveSnippetEvent()
  registerSaveAsSnippet()
  registerDragAndDrop()
  registerSnippetEdit()
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

function createSnippets (snippetValue) {
  openNewIframe('Snippets', 'BlaubandEmailSnippets', 'index', {'snippetValue': snippetValue})
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

          parent[parent.length - 2].location.reload()
          postMessageApi.window.destroy()
        } else {
          showErrorPanel(response.message)
        }
      }
    })
  })
}

function registerSaveAsSnippet () {
  $('.saveAsSnippetBtn').on('mousedown', function () {
    var text = "";

    if (window.getSelection) {
      text = window.getSelection().toString();
    } else if (document.selection && document.selection.type != "Control") {
      text = document.selection.createRange().text;
    }

    blaubandTempText = text;
  } )

  $('.saveAsSnippetBtn').off('click.blauband')
  $('.saveAsSnippetBtn').on('click.blauband', function () {
    if(blaubandTempText != ''){
      createSnippets(blaubandTempText)
    }
  })
}

function registerDragAndDrop () {
  $('.snippetLanguage').off('click.blauband')
  $('.snippetLanguage').on('click.blauband', function () {
    insertInTextarea($(this).find('.snippetLanguageValue').text())
  })
}

function registerSnippetEdit () {
  $('.snippetEdit').off('click.blauband')
  $('.snippetEdit').on('click.blauband', function () {
    openSnippets($(this).data('snippetname'))
  })
}