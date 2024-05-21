// ----------------------------------------------------------------------------------------------------------------------------
// -------------------- limiter le nombre de caractères sur les descriptions et titres des articles en homepage ---------------
$('.article-description').each(function(index, description) {
    description.innerHTML = trimString(description.innerHTML, 100);
})

$('.article-title a').each(function(index, title) {
    title.innerHTML = trimString(title.innerHTML, 62);
})
// ----------------------------------------------------------------------------------------------------------------------------


// ----------------------------------------------------------------------------------------------------------------------------
// fonction de trim personnalisée pour limiter un string à une certaine longueur sans couper en plein milieu d'un mot ---------
function trimString(title, maxLength) {
    if (title.length <= maxLength) {
        return title;
    }

    var trimmed = title.substring(0, maxLength);
    var lastSpace = trimmed.lastIndexOf(' ');
    if (lastSpace > 0) {
        trimmed = trimmed.substring(0, lastSpace);
    }

    return trimmed + '...';
}
// ----------------------------------------------------------------------------------------------------------------------------



// ----------------------------------------------------------------------------------------------------------------------------
// ------------------- API ----------------------------------------------------------------------------------------------------
const API_ENDPOINT = "https://newsapi.org/v2/top-headlines"
const API_KEY = "a3bc1825ad8d416fafdaf29e5477dc77";

// appel API
function apiCall(country, language, size) {
    $.ajax({
        url: `${API_ENDPOINT}?country=${country}&language=${language}&pageSize=${size}&apiKey=${API_KEY}`,
        method: 'GET',
        dataType: 'json',
        success: function(data) {
            console.log('Récupération d\'articles depuis l\'api "newsapi.org" ..', data);
            displayArticles(data['articles'], 5);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error('#0001 - Error:', errorThrown);
            console.error('#0001 - Error:', textStatus);
            console.error('#0001 - Error:', jqXHR);
        }
    });
}
// ----------------------------------------------------------------------------------------------------------------------------
// ----------------------------------------------------------------------------------------------------------------------------



// ----------------------------------------------------------------------------------------------------------------------------
// afficher x articles dans la deuxième card sur la homepage sous forme de liste ----------------------------------------------
function displayArticles(articles, nb) {
        let newsArticles = $('<div>').append('<h3>News Articles</h3><ul class="list"></ul><p>Source: newsapi.org</p>');
        let ul = newsArticles.find('ul');  // sélection de la nouvelle ul dans le dom
    
        // vérifier que les articles aient un titre (content) correct, puis en sélectionner X (nb en param de méthode) pour les insérer
        articles.filter(article => article.content && article.content.length > 0 && article.content !== "[Removed]")
            .slice(0, nb)
            .forEach(article => {
                ul.append(`<li>${trimString(article.content, 40)}</li>`); // li : contenu de l'article trimmé par la méthode perso
            });
    
        // vider la deuxième card et l'hydrater avec newsArticles créé précedemment
        $('.home-articles .card:nth-of-type(2)').empty().append(newsArticles);
}

// ----------------------------------------------------------------------------------------------------------------------------

// appel initial de l'api
apiCall('us', 'en', '20');