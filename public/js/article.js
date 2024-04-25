// --------------------------------------------------------------------------------------------------------
// ouverture des sous-menus dans article.html -------------------------------------------------------------
$('main > .container:first-of-type .list li').on('click', function(e) {
    var currentUl = $(this).find('ul');

    if (currentUl.length > 0) {
        currentUl.slideUp(350, function() { 
            currentUl.remove();
        });
    } else {
        $('main > .container:first-of-type .list li ul').slideUp(350, function() {
            $(this).remove();
        });

        var ul = $("<ul>").hide();
        
        for (var i = 0; i < 5; i++) {
            var li = $("<li>").html(`Article ${$(this).text()} ${i + 1}`);
            ul.append(li);
        }

        ul.css('margin', '1rem 0 0 1.25rem');
        $(this).append(ul);
        ul.slideDown(350);
        e.stopPropagation();
    }
});

$(document).on('click', function() {
    $('main > .container:first-of-type .list li ul').slideUp(350, function() {
        $(this).remove();
    });
});
// --------------------------------------------------------------------------------------------------------
// --------------------------------------------------------------------------------------------------------