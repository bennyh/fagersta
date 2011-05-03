// =================================================================================
//
// Implementation av tinyMCE
// 
// Skapad av: Benny Henrysson
//

tinyMCE.init({
        theme : "advanced",
        mode : "textareas",
        plugins : "fullpage",
        plugins : "emotions",
        theme_advanced_disable : "formatselect,styleselect, cleanup, anchor, code, help, justifyfull",
        theme_advanced_buttons1_add : "fontselect,fontsizeselect, forecolor",
        theme_advanced_buttons2_add : "separator, emotions"
});

