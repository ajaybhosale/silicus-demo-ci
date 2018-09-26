$( document ).ready(function() {
    $('ul.nav.navbar-nav.cusModules').html($('ul.nav.navbar-nav.cusModules ul').html()+$('ul.extraMenu').html());
    $('ul.nav.navbar-nav.cusModules li ul').closest('li').addClass('dropdown');
    $('ul.nav.navbar-nav.cusModules li ul').addClass('dropdown-menu');
    //$('ul.nav.navbar-nav.cusModules li.dropdown a:first-child').attr('data-toggle','dropdown');
    $('ul.nav.navbar-nav.cusModules li ul.dropdown-menu li a').prepend('<i class="fa fa-btn fa-sign-out"></i>');
    $('ul.nav.navbar-nav.cusModules li.dropdown a').append(' <i class="fa fa-angle-down"></i>');
    $('ul.nav.navbar-nav.cusModules li.dropdown ul.dropdown-menu li a i.fa.fa-angle-down').remove();
    $("ul.nav.navbar-nav.cusModules ul").filter(function(){
        return $.trim(this.innerHTML) === ""
    }).remove();
});