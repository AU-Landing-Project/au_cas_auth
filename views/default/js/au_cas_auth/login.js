elgg.provide('elgg.au_cas_auth');

elgg.au_cas_auth.init = function() {
    $(".cas-guest-login-toggle").click(function() {
        $(".cas-guest-login").slideToggle();
    });
};

elgg.register_hook_handler('init', 'system', elgg.au_cas_auth.init);