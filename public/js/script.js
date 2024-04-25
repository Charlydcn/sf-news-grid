// --------------------------------------------------------------------------------------------------------
// ouverture/fermeture nav mobile -------------------------------------------------------------------------

var navOpen = false;

$('#nav-btn').on('click', function(e) {
    openNav();
    e.stopPropagation();
});

$('#close-btn').on('click', function(e) {
    closeNav();
    e.stopPropagation();
});

$(document).on('click', function(e) {
    if (!$(e.target).closest('#mobile-nav').length && navOpen) {
        closeNav();
    }
});

function openNav() {
    $('body').css('overflow-y', 'hidden'); // to prevent scroll on body while mobile nav is open
    $('#mobile-nav').css('transform', 'translateX(0)');
    navOpen = true;
}

function closeNav() {
    $('body').css('overflow-y', 'auto'); // to prevent scroll on body while mobile nav is open
    $('#mobile-nav').css('transform', 'translateX(100%)');
    navOpen = false;
}
// --------------------------------------------------------------------------------------------------------
// --------------------------------------------------------------------------------------------------------

// ---------------------------------------------------------------------------------------------------
// disparition messages flash ------------------------------------------------------------------------
$(document).ready(function() {
    var i = 5000

    $(".flash").each(function() {
        if ($(this).text().length > 0) {
            $(this).find('.flash-progress').css('width', '0');

            $(this).slideDown(500, function() {
                $(this).delay(i).slideUp(500)
            })

            i += 375
        }
    })

    $(".flash").on('click', function(e) {
        $(this).remove()
    })
})
// ---------------------------------------------------------------------------------------------------

// ---------------------------------------------------------------------------------------------------
// messages flash en js ------------------------------------------------------------------------------
function addFlash(type, message) {
    const flash = $(`<div class="flash flash-${type}">${message}</div>`).hide();
    const progressBar = $(`<div class="flash-progress flash-progress-${type}"></div>`);

    flash.append(progressBar);
    $('#flash-messages').append(flash);
    flash.fadeIn().find('.flash-progress').css('width', '0');

    setTimeout(() => flash.fadeOut(() => flash.remove()), 5000);

    flash.on('click', e => {
        if (e.target === flash[0]) {
            flash.remove();
        }
    });
}

// ---------------------------------------------------------------------------------------------------
// messages flash en js ------------------------------------------------------------------------------
var path = window.location.pathname; // Récupère le chemin de l'URL

if (path === '/home' || path === '/') {
    // Ajoute 'btn-primary' au premier élément de la liste si l'URL est 'home' ou '/'
    $('.nav-links li:first-of-type a').addClass('btn-primary');
} else if (path === '/about') {
    // Ajoute 'btn-primary' au dernier élément de la liste si l'URL est '/about'
    $('.nav-links li:last-of-type a').addClass('btn-primary');
}
// ---------------------------------------------------------------------------------------------------