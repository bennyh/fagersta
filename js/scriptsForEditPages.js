// =================================================================================
//
// Övriga javascript-calls för editorsidan
// 
// Skapar ajaxform och hanterar knapptryckningar.
// 
// Skapad av: Benny Henrysson
//

$(document).ready(function() {
	$.jGrowl("JGrowl startar...");
	
	$('#editform').ajaxForm({
		dataType: 'json',
		beforeSubmit: function(date, status) {
			$.jGrowl('Sparar...');
		},
		success: function(data, status) {
			$.jGrowl('Sparad den ' + data.timestamp);
			$.jGrowl('Ämne: ' + data.topicId + ', post: ' + data.articleId);
			$('#idTopic').val(data.topicId);
			$('#idArticle').val(data.articleId);
		}
	});
	
	$('#editform').click(function(event){
		event.preventDefault();
		if ($(event.target).is('button#save')) {
			$.jGrowl('Draft-funktion saknas');
		}
		else if ($(event.target).is('button#discard')) {
			history.go(0);
		}
		else if ($(event.target).is('button#publish')) {
			tinyMCE.triggerSave();
			$('#editform').submit();
		}
    });
    
    $('#newform').ajaxForm({
		dataType: 'json',
		beforeSubmit: function(date, status) {
			$.jGrowl('Sparar...');
		},
		success: function(data, status) {
			$.jGrowl('Sparad den ' + data.timestamp);
			$.jGrowl('Ämne: ' + data.topicId + ', post: ' + data.articleId);
			$('#idTopic').val(data.topicId);
			$('#idArticle').val(data.articleId);
		}
	});
	
	$('#newform').click(function(event){
		event.preventDefault();
		if ($(event.target).is('button#save')) {
			$.jGrowl('Draft-funktion saknas');
		}
		else if ($(event.target).is('button#discard')) {
			history.go(0);
		}
		else if ($(event.target).is('button#publish')) {
			tinyMCE.triggerSave();
			$('#newform').submit();
		}
    });

});
