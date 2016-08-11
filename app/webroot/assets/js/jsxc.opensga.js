$(function() {
    jsxc.init({
        app_name: 'SIGA Chat',
        hideOffline: true,
        numberOfMsg: 10,

        loginForm: {
            form: '#form',
            jid: '#username',
            pass: '#password'
        },
        logoutElement: $('#logout'),
        root: '/assets/jsxc',
        otr: {
            enable: false,
        },
        carbons: {
            /** Enable carbon copies? */
            enable: true
        },
        xmpp: {
            url: 'http://siga.uem.mz/http-bind/',
            domain: 'siga.uem.mz',
            resource: 'sigaichat',
            overwrite: true,
            onlogin: true
        },

    });
});


$(document).on('ready.roster.jsxc', function(){
    $(document).trigger('toggle.roster.jsxc', ['hidden', 0]);
});
$(document).on('toggle.roster.jsxc', function(event, state, duration){
    $('#content').animate({
        right: ((state === 'shown') ? $('#jsxc_roster').outerWidth() : 0) + 'px'
    }, duration);
    $('.navbar').animate({
        right: ((state === 'shown') ? $('#jsxc_roster').outerWidth() : 0) + 'px'
    }, duration);
});

    $('#ajuda-suporte').click(function() {
        jsxc.gui.roster.toggle();
    });
$('#sb-toggle').click(function() {
    jsxc.gui.roster.toggle();
});