$(function() {
    jsxc.init({
        loginForm: {
            form: '#form',
            jid: '#username',
            pass: '#password'
        },
        logoutElement: $('#logout'),
        root: '/assets/jsxc',
        xmpp: {
            url: 'http://siga.uem.mz:7070/http-bind/',
            domain: 'siga.uem.mz',
            resource: '',
            overwrite: true,
            onlogin: true
        }
    });
});